<?php

namespace App\Http\Controllers;

use App\Models\Caixa;
use App\Models\Ponto;
use App\Models\Product;
use App\Models\Venda;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $isAdmin = auth()->user()->hasRole('Administrador');
        $hoje = Carbon::today();

        $pontosHoje = Ponto::with('usuario')
            ->whereDate('entrada_em', $hoje)
            ->orderBy('entrada_em')
            ->get();

        $caixaAberto = Caixa::aberto();

        if (! $isAdmin) {
            return view('home', compact('pontosHoje', 'caixaAberto', 'isAdmin'));
        }

        $inicioSemana = Carbon::now()->startOfWeek();
        $fimSemana    = Carbon::now()->endOfWeek();
        $inicioMes    = Carbon::now()->startOfMonth();
        $fimMes       = Carbon::now()->endOfMonth();

        $vendasHoje   = $this->resumo($hoje, $hoje);
        $vendasSemana = $this->resumo($inicioSemana, $fimSemana);
        $vendasMes    = $this->resumo($inicioMes, $fimMes);

        $topHoje   = $this->topProduto($hoje, $hoje);
        $topSemana = $this->topProduto($inicioSemana, $fimSemana);
        $topMes    = $this->topProduto($inicioMes, $fimMes);

        $vendasPorHora = Venda::query()
            ->where('status', 'FINALIZADA')
            ->whereDate('created_at', $hoje)
            ->selectRaw('HOUR(created_at) as hora, COUNT(*) as qtd, SUM(valor_total) as total')
            ->groupByRaw('HOUR(created_at)')
            ->orderBy('hora')
            ->get()
            ->keyBy('hora');

        $horasLabels = [];
        $horasQtd    = [];
        $horasTotal  = [];
        for ($h = 0; $h <= 23; $h++) {
            $horasLabels[] = str_pad($h, 2, '0', STR_PAD_LEFT).':00';
            $horasQtd[]    = (int) ($vendasPorHora->get($h)->qtd ?? 0);
            $horasTotal[]  = (float) ($vendasPorHora->get($h)->total ?? 0);
        }

        $vendasPorDia = Venda::query()
            ->where('status', 'FINALIZADA')
            ->whereBetween('created_at', [$inicioSemana->copy()->startOfDay(), $fimSemana->copy()->endOfDay()])
            ->selectRaw('DATE(created_at) as dia, COUNT(*) as qtd, SUM(valor_total) as total')
            ->groupByRaw('DATE(created_at)')
            ->orderBy('dia')
            ->get()
            ->keyBy('dia');

        $diasNomes  = ['Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sáb', 'Dom'];
        $diasLabels = [];
        $diasQtd    = [];
        $diasTotal  = [];
        for ($d = 0; $d < 7; $d++) {
            $data         = $inicioSemana->copy()->addDays($d);
            $key          = $data->format('Y-m-d');
            $diasLabels[] = $diasNomes[$d].' '.$data->format('d');
            $diasQtd[]    = (int) ($vendasPorDia->get($key)->qtd ?? 0);
            $diasTotal[]  = (float) ($vendasPorDia->get($key)->total ?? 0);
        }

        return view('home', compact(
            'isAdmin',
            'vendasHoje', 'vendasSemana', 'vendasMes',
            'topHoje', 'topSemana', 'topMes',
            'horasLabels', 'horasQtd', 'horasTotal',
            'diasLabels', 'diasQtd', 'diasTotal',
            'caixaAberto', 'pontosHoje',
        ));
    }

    private function resumo(Carbon $inicio, Carbon $fim): array
    {
        $base = fn () => Venda::query()
            ->where('status', 'FINALIZADA')
            ->whereBetween('created_at', [$inicio->copy()->startOfDay(), $fim->copy()->endOfDay()]);

        return [
            'quantidade' => $base()->count(),
            'total'      => (float) $base()->sum('valor_total'),
        ];
    }

    private function topProduto(Carbon $inicio, Carbon $fim): ?object
    {
        $row = DB::table('venda_itens')
            ->join('vendas', 'venda_itens.venda_id', '=', 'vendas.id')
            ->where('vendas.status', 'FINALIZADA')
            ->whereBetween('vendas.created_at', [$inicio->copy()->startOfDay(), $fim->copy()->endOfDay()])
            ->select('venda_itens.produto_id', DB::raw('SUM(venda_itens.quantidade) as qtd'))
            ->groupBy('venda_itens.produto_id')
            ->orderByDesc('qtd')
            ->first();

        if (! $row) {
            return null;
        }

        $row->produto = Product::find($row->produto_id);

        return $row;
    }
}
