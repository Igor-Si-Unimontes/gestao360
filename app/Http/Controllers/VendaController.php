<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Venda;
use App\Models\VendaItem;
use App\Models\Produtos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VendaController extends Controller
{
    public function store(Request $request)
    {
        $produtos = json_decode($request->input('produtos'), true);

        // 🔒 Validação básica
        Validator::make(['produtos' => $produtos], [
            'produtos' => 'required|array',
            'produtos.*.id' => 'required|exists:produtos,id',
            'produtos.*.quantidade' => 'required|integer|min:1',
        ])->validate();

        DB::beginTransaction();

        try {

            // 🧾 Cria venda
            $venda = Venda::create([
                'tipo' => 'RAPIDA',
                'status' => 'FINALIZADA',
                'forma_pagamento' => $request->forma_pagamento, // opcional por enquanto
                'usuario_id' => auth()->id(),
                'valor_total' => 0
            ]);

            $valorTotal = 0;

            foreach ($produtos as $item) {

                $produto = Product::find($item['id']);

                if (!$produto) {
                    throw new \Exception("Produto não encontrado");
                }

                // 🔥 valida estoque
                if ($produto->quantidade < $item['quantidade']) {
                    throw new \Exception("Estoque insuficiente para {$produto->nome}");
                }

                // 🔥 valor SEMPRE do banco
                $valorUnitario = $produto->valor;

                $subtotal = $valorUnitario * $item['quantidade'];

                // 📦 cria item
                VendaItem::create([
                    'venda_id' => $venda->id,
                    'produto_id' => $produto->id,
                    'quantidade' => $item['quantidade'],
                    'valor_unitario' => $valorUnitario,
                    'valor_total' => $subtotal,
                ]);

                // 📉 baixa estoque
                $produto->decrement('quantidade', $item['quantidade']);

                $valorTotal += $subtotal;
            }

            // 💰 atualiza total
            $venda->update([
                'valor_total' => $valorTotal
            ]);

            DB::commit();

            return redirect()->back()->with('success', 'Venda criada com sucesso');
        } catch (\Exception $e) {

            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }
    }
}
