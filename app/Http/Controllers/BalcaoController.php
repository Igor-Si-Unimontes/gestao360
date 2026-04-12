<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class BalcaoController extends Controller
{
    public function index()
    {
        $produtos = Product::whereHas('batches', function ($q) {
            $q->where('active', true)->where('quantity', '>', 0);
        })->with(['batches' => function ($q) {
            $q->where('active', true)->where('quantity', '>', 0)->oldest();
        }])->get();

        $produtoData = $produtos->mapWithKeys(function ($produto) {
            $loteAntigo  = $produto->batches->first();
            $totalEstoque = $produto->batches->sum('quantity');

            return [
                $produto->id => [
                    'sale_price'         => $loteAntigo ? (float) $loteAntigo->sale_price : 0,
                    'available_quantity' => (float) $totalEstoque,
                ],
            ];
        });

        return view('pedidos.balcao', compact('produtos', 'produtoData'));
    }
}
