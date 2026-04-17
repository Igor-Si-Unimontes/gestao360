<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Venda;
use App\Models\VendaItem;
use Illuminate\Support\Facades\DB;

class CozinhaController extends Controller
{
    public function index()
    {
        $pedidos = $this->pedidosEmPreparo();
        return view('cozinha.index', compact('pedidos'));
    }

    public function pedidos()
    {
        return response()->json($this->pedidosEmPreparo()->values());
    }

    public function marcarPronto(Venda $venda)
    {
        if ($venda->status === 'ABERTA' && $venda->tipo === 'MESA') {
            $atualizados = $venda->itens()
                ->where('status', 'EM_PREPARO')
                ->update(['status' => 'ENTREGUE']);

            if ($atualizados === 0) {
                return response()->json(['error' => 'Nenhum item em preparo nesta mesa.'], 422);
            }

            return response()->json(['ok' => true, 'tipo' => 'mesa_itens']);
        }

        if ($venda->status !== 'EM_PREPARO') {
            return response()->json(['error' => 'Pedido não está em preparo.'], 422);
        }

        DB::beginTransaction();
        try {
            if ($venda->tipo === 'MESA') {
                foreach ($venda->itens as $item) {
                    $product = Product::find($item->produto_id);
                    if (!$product) continue;

                    $restante = $item->quantidade;
                    foreach ($product->batches()->where('active', true)->oldest()->get() as $lote) {
                        if ($restante <= 0) break;
                        $deduzir   = min($lote->quantity, $restante);
                        $lote->decrement('quantity', $deduzir);
                        $restante -= $deduzir;
                    }
                }
                $venda->mesa?->update(['status' => 'livre']);
            }

            $venda->update(['status' => 'FINALIZADA']);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['error' => $e->getMessage()], 500);
        }

        return response()->json(['ok' => true, 'tipo' => 'venda']);
    }

    private function pedidosEmPreparo(): \Illuminate\Support\Collection
    {
        $resultado = collect();

        Venda::with(['itens.produto', 'mesa'])
            ->where('status', 'EM_PREPARO')
            ->oldest()
            ->get()
            ->each(function (Venda $v) use (&$resultado) {
                $minutos = (int) $v->created_at->diffInMinutes(now());
                [$alerta, $cor, $label] = $this->alertaInfo($minutos);

                $resultado->push([
                    'id'          => $v->id,
                    'tipo'        => $v->tipo,
                    'mesa'        => $v->mesa?->numero,
                    'card_type'   => 'venda',          
                    'observacao'  => $v->observacao,
                    'minutos'     => $minutos,
                    'alerta'      => $alerta,
                    'cor'         => $cor,
                    'label_tempo' => $label,
                    'criado_em'   => $v->created_at->format('H:i'),
                    'itens'       => $v->itens->map(fn($i) => [
                        'name'       => $i->produto?->name ?? '—',
                        'quantidade' => $i->quantidade,
                    ])->values(),
                ]);
            });

        Venda::with([
                'mesa',
                'itens' => fn($q) => $q->with('produto')->where('status', 'EM_PREPARO'),
            ])
            ->where('tipo', 'MESA')
            ->where('status', 'ABERTA')
            ->whereHas('itens', fn($q) => $q->where('status', 'EM_PREPARO'))
            ->get()
            ->each(function (Venda $v) use (&$resultado) {
                $maisAntigo = $v->itens->sortBy('updated_at')->first()?->updated_at;
                $minutos    = $maisAntigo ? (int) $maisAntigo->diffInMinutes(now()) : 0;
                [$alerta, $cor, $label] = $this->alertaInfo($minutos);

                $resultado->push([
                    'id'          => $v->id,
                    'tipo'        => 'MESA',
                    'mesa'        => $v->mesa?->numero,
                    'card_type'   => 'mesa_itens',     
                    'observacao'  => null,
                    'minutos'     => $minutos,
                    'alerta'      => $alerta,
                    'cor'         => $cor,
                    'label_tempo' => $label,
                    'criado_em'   => $maisAntigo?->format('H:i') ?? '—',
                    'itens'       => $v->itens->map(fn($i) => [
                        'name'       => $i->produto?->name ?? '—',
                        'quantidade' => $i->quantidade,
                    ])->values(),
                ]);
            });

        return $resultado->sortByDesc('minutos')->values();
    }

    private function alertaInfo(int $minutos): array
    {
        return match (true) {
            $minutos >= 30 => ['urgente',  'danger',  'Urgente'],
            $minutos >= 20 => ['atrasado', 'warning', 'Atrasado'],
            $minutos >= 10 => ['atencao',  'atencao', 'Aguardando'],
            default        => ['normal',   'novo',    'Novo'],
        };
    }
}
