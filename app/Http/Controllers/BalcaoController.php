<?php

namespace App\Http\Controllers;

use App\Models\Bairros;
use App\Models\Product;
use App\Models\VendaItem;
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

        $reservas = VendaItem::whereHas('venda', fn($q) => $q->where('status', 'ABERTA'))
            ->whereIn('produto_id', $produtos->pluck('id'))
            ->selectRaw('produto_id, SUM(quantidade) as total')
            ->groupBy('produto_id')
            ->pluck('total', 'produto_id');

        $produtoData = $produtos->mapWithKeys(function ($produto) use ($reservas) {
            $loteAntigo  = $produto->batches->first();
            $totalFisico = (float) $produto->batches->sum('quantity');
            $reservado   = (float) ($reservas[$produto->id] ?? 0);
            $disponivel  = max(0, $totalFisico - $reservado);

            return [
                $produto->id => [
                    'sale_price'         => $loteAntigo ? (float) $loteAntigo->sale_price : 0,
                    'available_quantity' => $disponivel,
                ],
            ];
        })->filter(fn($d) => $d['available_quantity'] > 0);

        $produtos = $produtos->filter(fn($p) => isset($produtoData[$p->id]));

        $bairros = Bairros::orderBy('nome')->get();

        return view('pedidos.balcao', compact('produtos', 'produtoData', 'bairros'));
    }
}
