<?php

namespace App\Http\Controllers;

use App\Models\Mesa;
use App\Models\Product;
use App\Models\Venda;
use App\Models\VendaItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MesaController extends Controller
{

    private function estoqueFisico(int $produtoId): float
    {
        return (float) Product::find($produtoId)
            ?->batches()->where('active', true)->sum('quantity') ?? 0;
    }

    private function estoqueReservado(int $produtoId, ?int $excludeVendaId = null): float
    {
        $query = VendaItem::whereHas('venda', fn($q) => $q->where('status', 'ABERTA'))
            ->where('produto_id', $produtoId);

        if ($excludeVendaId) {
            $query->where('venda_id', '!=', $excludeVendaId);
        }

        return (float) $query->sum('quantidade');
    }

    private function estoqueDisponivel(int $produtoId, ?int $excludeVendaId = null): float
    {
        return max(0, $this->estoqueFisico($produtoId) - $this->estoqueReservado($produtoId, $excludeVendaId));
    }

    private function buildProdutoData($produtos, ?int $excludeVendaId = null): \Illuminate\Support\Collection
    {
        $ids = $produtos->pluck('id');

        $reservas = VendaItem::whereHas('venda', fn($q) => $q->where('status', 'ABERTA'))
            ->when($excludeVendaId, fn($q) => $q->where('venda_id', '!=', $excludeVendaId))
            ->whereIn('produto_id', $ids)
            ->selectRaw('produto_id, SUM(quantidade) as total')
            ->groupBy('produto_id')
            ->pluck('total', 'produto_id');

        return $produtos->mapWithKeys(function ($produto) use ($reservas) {
            $loteAntigo   = $produto->batches->first();
            $totalFisico  = (float) $produto->batches->sum('quantity');
            $reservado    = (float) ($reservas[$produto->id] ?? 0);
            $disponivel   = max(0, $totalFisico - $reservado);

            return [
                $produto->id => [
                    'sale_price'         => $loteAntigo ? (float) $loteAntigo->sale_price : 0,
                    'available_quantity' => $disponivel,
                ],
            ];
        });
    }

    private function buildItensData(Venda $venda): array
    {
        $itens = $venda->itens()->with('produto')->get()->map(fn($i) => [
            'id'             => $i->id,
            'produto_id'     => $i->produto_id,
            'name'           => $i->produto->name ?? '—',
            'valor_unitario' => (float) $i->valor_unitario,
            'quantidade'     => (float) $i->quantidade,
            'valor_total'    => (float) $i->valor_total,
        ])->values()->toArray();

        return [
            'items'       => $itens,
            'total_geral' => array_sum(array_column($itens, 'valor_total')),
        ];
    }

    public function index()
    {
        $mesas = Mesa::with(['vendaAberta'])->orderBy('numero')->get();

        return view('mesas.index', compact('mesas'));
    }

    public function comanda(Mesa $mesa)
    {
        if ($mesa->isLivre()) {
            return redirect()->route('mesas.index')
                ->with('error', "Mesa #{$mesa->numero} não está ocupada.");
        }

        $venda = $mesa->vendaAberta;
        if (!$venda) {
            $mesa->update(['status' => 'livre']);
            return redirect()->route('mesas.index');
        }

        $produtos = Product::whereHas('batches', function ($q) {
            $q->where('active', true)->where('quantity', '>', 0);
        })->with(['batches' => function ($q) {
            $q->where('active', true)->where('quantity', '>', 0)->oldest();
        }])->get();

        $produtoData = $this->buildProdutoData($produtos, $venda->id);

        $itensData = $this->buildItensData($venda);

        return view('mesas.comanda', compact('mesa', 'venda', 'produtos', 'produtoData', 'itensData'));
    }

    public function abrirMesa(Mesa $mesa)
    {
        if (!$mesa->isLivre()) {
            return redirect()->route('mesas.index')
                ->with('error', "Mesa #{$mesa->numero} já está ocupada.");
        }

        DB::beginTransaction();
        try {
            Venda::create([
                'tipo'        => 'MESA',
                'status'      => 'ABERTA',
                'usuario_id'  => auth()->id(),
                'mesa_id'     => $mesa->id,
                'valor_total' => 0,
            ]);

            $mesa->update(['status' => 'ocupada']);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('mesas.index')->with('error', $e->getMessage());
        }

        return redirect()->route('mesas.comanda', $mesa)
            ->with('success', "Mesa #{$mesa->numero} aberta!");
    }

    public function adicionarItem(Request $request, Mesa $mesa)
    {
        $venda = $mesa->vendaAberta;
        if (!$venda) {
            return response()->json(['error' => 'Mesa não está aberta.'], 422);
        }

        $product    = Product::find($request->produto_id);
        $quantidade = (float) $request->quantidade;

        if (!$product) {
            return response()->json(['error' => 'Produto não encontrado.'], 404);
        }
        if ($quantidade <= 0) {
            return response()->json(['error' => 'Quantidade inválida.'], 422);
        }

        $disponivel = $this->estoqueDisponivel($product->id, $venda->id);

        if ($disponivel < $quantidade) {
            return response()->json([
                'error' => "Estoque insuficiente para \"{$product->name}\". Disponível: {$disponivel}",
            ], 422);
        }

        try {
            $loteRef       = $product->batches()->where('active', true)->where('quantity', '>', 0)->oldest()->first();
            $valorUnitario = $loteRef->sale_price;

            VendaItem::create([
                'venda_id'       => $venda->id,
                'produto_id'     => $product->id,
                'quantidade'     => $quantidade,
                'valor_unitario' => $valorUnitario,
                'valor_total'    => $valorUnitario * $quantidade,
            ]);

            $venda->update(['valor_total' => $venda->itens()->sum('valor_total')]);

            return response()->json($this->buildItensData($venda->fresh()));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function removerItem(Mesa $mesa, VendaItem $item)
    {
        $venda = $mesa->vendaAberta;

        if (!$venda || $item->venda_id !== $venda->id) {
            return response()->json(['error' => 'Item não pertence a esta mesa.'], 422);
        }

        try {
            $item->delete();
            $venda->update(['valor_total' => $venda->itens()->sum('valor_total')]);

            return response()->json($this->buildItensData($venda->fresh()));
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function fecharMesa(Request $request, Mesa $mesa)
    {
        $venda = $mesa->vendaAberta;
        if (!$venda) {
            return redirect()->route('mesas.index')
                ->with('error', "Mesa #{$mesa->numero} não está aberta.");
        }

        $formasValidas = ['DINHEIRO', 'PIX', 'CARTAO_DEBITO', 'CARTAO_CREDITO'];
        if (!in_array($request->forma_pagamento, $formasValidas)) {
            return redirect()->back()->with('error', 'Selecione uma forma de pagamento válida.');
        }

        $itens = $venda->itens()->with('produto')->get();

        if ($itens->isEmpty()) {
            return redirect()->back()->with('error', 'Adicione ao menos um produto antes de fechar a mesa.');
        }

        DB::beginTransaction();
        try {
            foreach ($itens as $item) {
                $product = $item->produto;
                if (!$product) {
                    throw new \Exception("Produto do item #{$item->id} não encontrado.");
                }

                $estoqueAtual = $product->batches()->where('active', true)->sum('quantity');

                if ($estoqueAtual < $item->quantidade) {
                    throw new \Exception(
                        "Estoque insuficiente para \"{$product->name}\"." .
                        " Disponível: {$estoqueAtual}, necessário: {$item->quantidade}."
                    );
                }

                $restante = $item->quantidade;
                foreach ($product->batches()->where('active', true)->oldest()->get() as $lote) {
                    if ($restante <= 0) break;
                    $deduzir   = min($lote->quantity, $restante);
                    $lote->decrement('quantity', $deduzir);
                    $restante -= $deduzir;
                }
            }

            $total = $itens->sum('valor_total');

            $venda->update([
                'status'          => 'FINALIZADA',
                'forma_pagamento' => $request->forma_pagamento,
                'valor_total'     => $total,
            ]);

            $mesa->update(['status' => 'livre']);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('mesas.index')
            ->with('success', "Mesa #{$mesa->numero} fechada! Total: R$ " . number_format($venda->valor_total, 2, ',', '.'));
    }

    public function cancelarMesa(Mesa $mesa)
    {
        $venda = $mesa->vendaAberta;
        if (!$venda) {
            return redirect()->route('mesas.index');
        }

        DB::beginTransaction();
        try {
            $venda->itens()->delete();
            $venda->update(['status' => 'CANCELADA', 'valor_total' => 0]);
            $mesa->update(['status' => 'livre']);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }

        return redirect()->route('mesas.index')
            ->with('success', "Mesa #{$mesa->numero} cancelada e liberada.");
    }
}
