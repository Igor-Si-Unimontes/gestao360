<?php

namespace App\Http\Controllers;

use App\Models\Caixa;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CaixaController extends Controller
{
    public function index()
    {
        $caixaAberto  = Caixa::with('usuario')->where('status', 'ABERTO')->latest()->first();
        $historico    = Caixa::with('usuario')->latest()->take(10)->get();

        return view('caixas.index', compact('caixaAberto', 'historico'));
    }
    public function abrirForm()
    {
        $caixaAberto = Caixa::aberto();

        if ($caixaAberto) {
            return redirect()->route('caixas.index')
                ->with('error', 'Já existe um caixa aberto. Feche-o antes de abrir outro.');
        }

        return view('caixas.abrir');
    }

    public function abrir(Request $request)
    {
        if (Caixa::aberto()) {
            return redirect()->route('caixas.index')
                ->with('error', 'Já existe um caixa aberto.');
        }

        $contagem = $this->extrairContagem($request);
        $total    = Caixa::calcularTotal($contagem);

        if ($total < 0) {
            return redirect()->back()->with('error', 'Valor inválido.');
        }

        Caixa::create([
            'status'             => 'ABERTO',
            'usuario_id'         => auth()->id(),
            'valor_abertura'     => $total,
            'abertura_contagem'  => $contagem,
        ]);

        return redirect()->route('caixas.index')
            ->with('success', "Caixa aberto com R$ " . number_format($total, 2, ',', '.') . " em espécie.");
    }

    public function fecharForm()
    {
        $caixa = Caixa::with('usuario')->where('status', 'ABERTO')->latest()->first();

        if (!$caixa) {
            return redirect()->route('caixas.index')
                ->with('error', 'Nenhum caixa aberto para fechar.');
        }

        $esperado = $caixa->valorEsperadoFechamento();

        return view('caixas.fechar', compact('caixa', 'esperado'));
    }

    public function fechar(Request $request)
    {
        $caixa = Caixa::aberto();

        if (!$caixa) {
            return redirect()->route('caixas.index')
                ->with('error', 'Nenhum caixa aberto para fechar.');
        }

        $contagem = $this->extrairContagem($request);
        $totalFechamento = Caixa::calcularTotal($contagem);
        $esperado        = $caixa->valorEsperadoFechamento();
        $diferenca       = round($totalFechamento - $esperado, 2);

        DB::beginTransaction();
        try {
            $caixa->update([
                'status'               => 'FECHADO',
                'valor_fechamento'     => $totalFechamento,
                'fechamento_contagem'  => $contagem,
                'fechado_em'           => now(),
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage());
        }

        $msg = "Caixa fechado. Total contado: R$ " . number_format($totalFechamento, 2, ',', '.')
             . " | Esperado: R$ " . number_format($esperado, 2, ',', '.')
             . " | Diferença: R$ " . number_format($diferenca, 2, ',', '.');

        return redirect()->route('caixas.index')->with('success', $msg);
    }

    private function extrairContagem(Request $request): array
    {
        $chaves = array_keys(Caixa::$denominacoes);
        $contagem = [];
        foreach ($chaves as $chave) {
            $contagem[$chave] = max(0, (int) $request->input($chave, 0));
        }
        return $contagem;
    }
}
