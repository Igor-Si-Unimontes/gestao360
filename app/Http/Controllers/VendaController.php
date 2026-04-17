<?php

namespace App\Http\Controllers;

use App\Models\Bairros;
use App\Models\Product;
use App\Models\Venda;
use App\Models\VendaItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VendaController extends Controller
{
    public function store(Request $request)
    {
        $produtos = json_decode($request->input('produtos'), true);

        if (empty($produtos) || !is_array($produtos)) {
            return redirect()->back()->with('error', 'Nenhum produto adicionado ao pedido.');
        }

        $formaPagamento = $request->input('forma_pagamento');
        $formasValidas   = ['DINHEIRO', 'PIX', 'CARTAO_DEBITO', 'CARTAO_CREDITO'];
        $enviandoCozinha = $request->input('acao') === 'cozinha';

        if (!$enviandoCozinha && !in_array($formaPagamento, $formasValidas)) {
            return redirect()->back()->with('error', 'Selecione uma forma de pagamento válida.');
        }

        $isDelivery  = $request->input('forma_entrega') === 'entrega';
        $endereco    = null;
        $bairroId    = null;
        $taxaEntrega = 0;

        if ($isDelivery) {
            $endereco = trim($request->input('endereco', ''));
            $bairroId = $request->input('bairro_id');

            if (empty($endereco)) {
                return redirect()->back()->with('error', 'Informe o endereço de entrega.');
            }

            $bairro = Bairros::find($bairroId);
            if (!$bairro) {
                return redirect()->back()->with('error', 'Selecione um bairro válido.');
            }

            $taxaEntrega = (float) $bairro->taxa;
        }

        $statusInicial = $request->input('acao') === 'cozinha' ? 'EM_PREPARO' : 'FINALIZADA';

        DB::beginTransaction();

        try {
            $venda = Venda::create([
                'tipo'            => $isDelivery ? 'DELIVERY' : 'RAPIDA',
                'status'          => $statusInicial,
                'forma_pagamento' => $statusInicial === 'FINALIZADA' ? $formaPagamento : null,
                'usuario_id'      => auth()->id(),
                'valor_total'     => 0,
                'endereco'        => $endereco,
                'bairro_id'       => $bairroId,
                'taxa_entrega'    => $taxaEntrega,
                'observacao'      => trim($request->input('observacao', '')) ?: null,
            ]);

            $valorTotal = 0;

            foreach ($produtos as $item) {
                $product = Product::find($item['id']);

                if (!$product) {
                    throw new \Exception("Produto ID {$item['id']} não encontrado.");
                }

                $quantidadeNecessaria = (float) $item['quantidade'];

                $estoqueDisponivel = $product->batches()
                    ->where('active', true)
                    ->where('quantity', '>', 0)
                    ->sum('quantity');

                if ($estoqueDisponivel < $quantidadeNecessaria) {
                    throw new \Exception("Estoque insuficiente para \"{$product->name}\". Disponível: {$estoqueDisponivel}");
                }

                $loteReferencia = $product->batches()
                    ->where('active', true)
                    ->where('quantity', '>', 0)
                    ->oldest()
                    ->first();

                $valorUnitario = $loteReferencia->sale_price;
                $subtotal      = $valorUnitario * $quantidadeNecessaria;

                $lotes     = $product->batches()
                    ->where('active', true)
                    ->where('quantity', '>', 0)
                    ->oldest()
                    ->get();

                $restante = $quantidadeNecessaria;
                foreach ($lotes as $lote) {
                    if ($restante <= 0) {
                        break;
                    }
                    $deduzir  = min($lote->quantity, $restante);
                    $lote->decrement('quantity', $deduzir);
                    $restante -= $deduzir;
                }

                VendaItem::create([
                    'venda_id'      => $venda->id,
                    'produto_id'    => $product->id,
                    'quantidade'    => $quantidadeNecessaria,
                    'valor_unitario' => $valorUnitario,
                    'valor_total'   => $subtotal,
                ]);

                $valorTotal += $subtotal;
            }

            $venda->update(['valor_total' => $valorTotal + $taxaEntrega]);

            DB::commit();

            $msg = $statusInicial === 'EM_PREPARO'
                ? 'Pedido enviado para a cozinha!'
                : 'Venda finalizada com sucesso!';

            return redirect()->route('balcao')->with('success', $msg);
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
