<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Venda;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PedidoController extends Controller
{
    public function index(Request $request)
    {
        $verTodos = $request->query('ver') === 'todos';

        $query = Venda::with(['mesa', 'bairro', 'itens'])->latest();

        if (!$verTodos) {
            $query->whereDate('created_at', today());
        }

        $pedidos = $query->get();

        return view('pedidos.historico', compact('pedidos', 'verTodos'));
    }

    public function show(Venda $pedido)
    {
        $pedido->load(['itens.produto', 'mesa', 'bairro']);

        return response()->json([
            'id'              => $pedido->id,
            'tipo'            => $pedido->tipo,
            'status'          => $pedido->status,
            'forma_pagamento' => $pedido->forma_pagamento,
            'valor_total'     => number_format($pedido->valor_total, 2, ',', '.'),
            'taxa_entrega'    => number_format($pedido->taxa_entrega ?? 0, 2, ',', '.'),
            'endereco'        => $pedido->endereco,
            'bairro'          => $pedido->bairro?->nome,
            'mesa'            => $pedido->mesa?->numero,
            'criado_em'       => $pedido->created_at->format('d/m/Y H:i'),
            'itens'           => $pedido->itens->map(fn($i) => [
                'name'           => $i->produto?->name ?? '—',
                'quantidade'     => $i->quantidade,
                'valor_unitario' => number_format($i->valor_unitario, 2, ',', '.'),
                'valor_total'    => number_format($i->valor_total, 2, ',', '.'),
            ]),
        ]);
    }

    public function updateStatus(Request $request, Venda $pedido)
    {
        $novoStatus = $request->status;

        $transicoes = [
            'ABERTA'     => ['EM_PREPARO', 'CANCELADA'],
            'EM_PREPARO' => ['FINALIZADA', 'CANCELADA'],
            'FINALIZADA' => [],
            'CANCELADA'  => [],
        ];

        if (!in_array($novoStatus, $transicoes[$pedido->status] ?? [])) {
            return redirect()->back()
                ->with('error', "Não é possível mover de \"{$pedido->status}\" para \"{$novoStatus}\".");
        }

        DB::beginTransaction();
        try {
            if ($novoStatus === 'CANCELADA') {

                if ($pedido->tipo === 'MESA') {
                    $pedido->itens()->delete();
                    $pedido->mesa?->update(['status' => 'livre']);
                } else {
                    if ($pedido->status === 'EM_PREPARO') {
                        foreach ($pedido->itens as $item) {
                            $product = Product::find($item->produto_id);
                            if ($product) {
                                $lote = $product->batches()->where('active', true)->oldest()->first();
                                $lote?->increment('quantity', $item->quantidade);
                            }
                        }
                    }
                }

            } elseif ($novoStatus === 'FINALIZADA' && $pedido->tipo === 'MESA') {

                foreach ($pedido->itens as $item) {
                    $product = Product::find($item->produto_id);
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

                $pedido->mesa?->update(['status' => 'livre']);
            }

            $pedido->update(['status' => $novoStatus]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }

        $labels = [
            'EM_PREPARO' => 'Em Preparo',
            'FINALIZADA' => 'Finalizado',
            'CANCELADA'  => 'Cancelado',
        ];

        return redirect()->back()
            ->with('success', "Pedido #{$pedido->id} movido para \"{$labels[$novoStatus]}\".");
    }

    public function destroy(Venda $pedido)
    {
        if ($pedido->status !== 'CANCELADA') {
            return redirect()->back()
                ->with('error', 'Somente pedidos cancelados podem ser excluídos.');
        }

        $pedido->itens()->delete();
        $pedido->delete();

        return redirect()->back()->with('success', "Pedido #{$pedido->id} excluído.");
    }
}
