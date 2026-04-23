<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Sangria;
use App\Models\Venda;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RelatorioController extends Controller
{
    private const VER_OPCOES = ['resumo', 'top_produtos', 'pico', 'caixa', 'markup'];

    public function index(Request $request)
    {
        $periodo = $request->input('periodo', 'semana');
        if (! in_array($periodo, ['semana', 'mes', 'ambos'], true)) {
            $periodo = 'semana';
        }

        $refSemana = $request->input('semana', now()->format('Y-m-d'));
        $refMes = $request->input('mes', now()->format('Y-m'));

        $inicioSemana = Carbon::parse($refSemana)->startOfWeek();
        $fimSemana = Carbon::parse($refSemana)->endOfWeek();

        $inicioMes = Carbon::parse($refMes.'-01')->startOfMonth();
        $fimMes = Carbon::parse($refMes.'-01')->endOfMonth();

        $ver = $this->parseVerFiltros($request);

        $needSemana = in_array($periodo, ['semana', 'ambos'], true);
        $needMes = in_array($periodo, ['mes', 'ambos'], true);

        $resumoSemana = null;
        $resumoMes = null;
        $topProdutosSemana = null;
        $topProdutosMes = null;
        $picoSemana = null;
        $picoMes = null;
        $caixaSemana = null;
        $caixaMes = null;
        $markupSemana = null;
        $markupMes = null;

        if ($needSemana) {
            if (in_array('resumo', $ver, true)) {
                $resumoSemana = $this->resumoVendasPeriodo($inicioSemana, $fimSemana);
            }
            if (in_array('top_produtos', $ver, true)) {
                $topProdutosSemana = $this->topProdutosPeriodo($inicioSemana, $fimSemana, 10);
            }
            if (in_array('pico', $ver, true)) {
                $picoSemana = $this->horariosPicoPeriodo($inicioSemana, $fimSemana, 5);
            }
            if (in_array('caixa', $ver, true)) {
                $caixaSemana = $this->fluxoDinheiroPeriodo($inicioSemana, $fimSemana);
            }
            if (in_array('markup', $ver, true)) {
                $markupSemana = $this->markupProdutosPeriodo($inicioSemana, $fimSemana);
            }
        }

        if ($needMes) {
            if (in_array('resumo', $ver, true)) {
                $resumoMes = $this->resumoVendasPeriodo($inicioMes, $fimMes);
            }
            if (in_array('top_produtos', $ver, true)) {
                $topProdutosMes = $this->topProdutosPeriodo($inicioMes, $fimMes, 10);
            }
            if (in_array('pico', $ver, true)) {
                $picoMes = $this->horariosPicoPeriodo($inicioMes, $fimMes, 5);
            }
            if (in_array('caixa', $ver, true)) {
                $caixaMes = $this->fluxoDinheiroPeriodo($inicioMes, $fimMes);
            }
            if (in_array('markup', $ver, true)) {
                $markupMes = $this->markupProdutosPeriodo($inicioMes, $fimMes);
            }
        }

        return view('relatorios.index', compact(
            'periodo',
            'ver',
            'refSemana',
            'refMes',
            'inicioSemana',
            'fimSemana',
            'inicioMes',
            'fimMes',
            'resumoSemana',
            'resumoMes',
            'topProdutosSemana',
            'topProdutosMes',
            'picoSemana',
            'picoMes',
            'caixaSemana',
            'caixaMes',
            'markupSemana',
            'markupMes',
        ));
    }

    /**
     * @return list<string>
     */
    private function parseVerFiltros(Request $request): array
    {
        $raw = $request->input('ver');
        if ($raw === null && ! $request->filled('filtros_submit')) {
            return self::VER_OPCOES;
        }

        if (! is_array($raw)) {
            $raw = $raw === '' || $raw === null ? [] : [(string) $raw];
        }

        $ver = array_values(array_intersect(self::VER_OPCOES, $raw));

        return $ver !== [] ? $ver : ['resumo'];
    }

    private function resumoVendasPeriodo(Carbon $inicio, Carbon $fim): array
    {
        $base = fn () => Venda::query()
            ->where('status', 'FINALIZADA')
            ->whereBetween('created_at', [$inicio->copy()->startOfDay(), $fim->copy()->endOfDay()]);

        return [
            'quantidade' => $base()->count(),
            'valor_total' => (float) $base()->sum('valor_total'),
            'dinheiro' => (float) $base()->where('forma_pagamento', 'DINHEIRO')->sum('valor_total'),
            'pix' => (float) $base()->where('forma_pagamento', 'PIX')->sum('valor_total'),
            'cartao' => (float) $base()->whereIn('forma_pagamento', ['CARTAO_DEBITO', 'CARTAO_CREDITO'])->sum('valor_total'),
            'taxa_entrega' => (float) $base()->where('tipo', 'DELIVERY')->sum('taxa_entrega'),
        ];
    }

    private function topProdutosPeriodo(Carbon $inicio, Carbon $fim, int $limite = 10)
    {
        $rows = DB::table('venda_itens')
            ->join('vendas', 'venda_itens.venda_id', '=', 'vendas.id')
            ->where('vendas.status', 'FINALIZADA')
            ->whereBetween('vendas.created_at', [$inicio->copy()->startOfDay(), $fim->copy()->endOfDay()])
            ->select([
                'venda_itens.produto_id',
                DB::raw('SUM(venda_itens.quantidade) as quantidade_total'),
                DB::raw('SUM(venda_itens.valor_total) as receita_total'),
            ])
            ->groupBy('venda_itens.produto_id')
            ->orderByDesc('quantidade_total')
            ->limit($limite)
            ->get();

        $produtos = Product::whereIn('id', $rows->pluck('produto_id'))->get()->keyBy('id');

        return $rows->map(function ($row) use ($produtos) {
            $row->produto = $produtos->get($row->produto_id);

            return $row;
        });
    }

    private function horariosPicoPeriodo(Carbon $inicio, Carbon $fim, int $limite = 5): array
    {
        $rows = Venda::query()
            ->where('status', 'FINALIZADA')
            ->whereBetween('created_at', [$inicio->copy()->startOfDay(), $fim->copy()->endOfDay()])
            ->selectRaw('HOUR(created_at) as hora, COUNT(*) as total')
            ->groupByRaw('HOUR(created_at)')
            ->orderByDesc('total')
            ->limit($limite)
            ->get();

        return $rows->map(fn ($r) => [
            'hora' => (int) $r->hora,
            'total' => (int) $r->total,
        ])->all();
    }

    private function fluxoDinheiroPeriodo(Carbon $inicio, Carbon $fim): array
    {
        $vendasDinheiro = (float) Venda::query()
            ->where('status', 'FINALIZADA')
            ->where('forma_pagamento', 'DINHEIRO')
            ->whereBetween('created_at', [$inicio->copy()->startOfDay(), $fim->copy()->endOfDay()])
            ->sum('valor_total');

        $sangrias = (float) Sangria::query()
            ->whereBetween('created_at', [$inicio->copy()->startOfDay(), $fim->copy()->endOfDay()])
            ->sum('valor');

        $aberturasCaixa = (float) DB::table('caixas')
            ->whereBetween('created_at', [$inicio->copy()->startOfDay(), $fim->copy()->endOfDay()])
            ->sum('valor_abertura');

        return [
            'vendas_dinheiro' => $vendasDinheiro,
            'sangrias' => $sangrias,
            'liquido_movimento' => round($vendasDinheiro - $sangrias, 2),
            'aberturas_caixa' => $aberturasCaixa,
        ];
    }

    private function markupProdutosPeriodo(Carbon $inicio, Carbon $fim): array
    {
        $itens = DB::table('venda_itens')
            ->join('vendas', 'venda_itens.venda_id', '=', 'vendas.id')
            ->where('vendas.status', 'FINALIZADA')
            ->whereBetween('vendas.created_at', [$inicio->copy()->startOfDay(), $fim->copy()->endOfDay()])
            ->select([
                'venda_itens.produto_id',
                DB::raw('SUM(venda_itens.quantidade) as qtd'),
                DB::raw('SUM(venda_itens.valor_total) as receita'),
            ])
            ->groupBy('venda_itens.produto_id')
            ->get();

        $custosPorProduto = [];
        foreach ($itens->pluck('produto_id')->unique() as $pid) {
            $custosPorProduto[$pid] = $this->custoMedioPonderadoProduto((int) $pid);
        }

        $receitaTotal = 0.0;
        $custoTotal = 0.0;

        foreach ($itens as $row) {
            $receita = (float) $row->receita;
            $qtd = (float) $row->qtd;
            $custoU = $custosPorProduto[$row->produto_id] ?? 0.0;
            $receitaTotal += $receita;
            $custoTotal += $qtd * $custoU;
        }

        $custoTotal = round($custoTotal, 2);
        $lucroEstimado = round($receitaTotal - $custoTotal, 2);
        $markupPct = $custoTotal > 0 ? round((($receitaTotal - $custoTotal) / $custoTotal) * 100, 2) : null;

        return [
            'receita_produtos' => round($receitaTotal, 2),
            'custo_estimado' => $custoTotal,
            'lucro_estimado' => $lucroEstimado,
            'markup_percent' => $markupPct,
        ];
    }

    private function custoMedioPonderadoProduto(int $produtoId): float
    {
        $produto = Product::with(['batches' => fn ($q) => $q->where('active', true)->where('quantity', '>', 0)])
            ->find($produtoId);

        if (! $produto) {
            return 0.0;
        }

        $batches = $produto->batches;
        $qtdSum = (float) $batches->sum('quantity');
        if ($qtdSum <= 0) {
            return 0.0;
        }

        $custoPonderado = $batches->sum(fn ($b) => (float) $b->cost_price * (float) $b->quantity);

        return round($custoPonderado / $qtdSum, 4);
    }
}
