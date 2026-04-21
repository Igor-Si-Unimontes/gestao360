<?php

namespace App\Http\Controllers;

use App\Models\Caixa;
use App\Models\Sangria;
use Illuminate\Http\Request;

class SangriaController extends Controller
{
    public function index()
    {
        $caixa = Caixa::aberto();

        $sangrias = Sangria::with(['caixa', 'usuario'])
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get();

        return view('sangrias.index', compact('sangrias', 'caixa'));
    }

    public function create()
    {
        $caixa = Caixa::aberto();

        if (!$caixa) {
            return redirect()->route('sangrias.index')
                ->with('error', 'Nenhum caixa aberto. Abra o caixa antes de registrar uma sangria.');
        }

        $disponivelEspecie = $caixa->valorEsperadoFechamento();

        return view('sangrias.create', compact('caixa', 'disponivelEspecie'));
    }

    public function store(Request $request)
    {
        $caixa = Caixa::aberto();

        if (!$caixa) {
            return redirect()->route('sangrias.index')
                ->with('error', 'Nenhum caixa aberto.');
        }

        $disponivelEspecie = $caixa->valorEsperadoFechamento();

        $request->validate([
            'data_retirada' => 'required|date',
            'categoria'     => 'required|in:' . implode(',', array_keys(Sangria::$categorias)),
            'valor'         => ['required', 'numeric', 'min:0.01', "max:{$disponivelEspecie}"],
            'observacao'    => 'nullable|string|max:255',
        ], [
            'valor.max' => "O valor não pode exceder o disponível em espécie (R$ " . number_format($disponivelEspecie, 2, ',', '.') . ").",
        ]);

        Sangria::create([
            'caixa_id'      => $caixa->id,
            'usuario_id'    => auth()->id(),
            'data_retirada' => $request->data_retirada,
            'categoria'     => $request->categoria,
            'valor'         => $request->valor,
            'observacao'    => $request->observacao,
        ]);

        return redirect()->route('sangrias.index')
            ->with('success', 'Sangria registrada com sucesso.');
    }

    public function edit(Sangria $sangria)
    {
        $caixaAberto = Caixa::aberto();
        $caixa = $sangria->caixa;

        if (!$caixa || $caixa->status !== 'ABERTO') {
            return redirect()->route('sangrias.index')
                ->with('error', 'Só é possível editar sangrias de caixas abertos.');
        }

        $disponivelEspecie = (
            $caixaAberto &&
            $caixa &&
            (int) $caixaAberto->id === (int) $caixa->id
        )
            ? round($caixaAberto->valorEsperadoFechamento() + (float) $sangria->valor, 2)
            : 0;

        return view('sangrias.edit', compact('sangria', 'caixa', 'disponivelEspecie'));
    }

    public function update(Request $request, Sangria $sangria)
    {
        if (!$sangria->caixa || $sangria->caixa->status !== 'ABERTO') {
            return redirect()->route('sangrias.index')
                ->with('error', 'Só é possível editar sangrias de caixas abertos.');
        }

        $caixaAberto       = Caixa::aberto();
        $sangriaDoAberto   = $caixaAberto && (int) $caixaAberto->id === (int) $sangria->caixa_id;
        $disponivelEspecie = $sangriaDoAberto
            ? round($caixaAberto->valorEsperadoFechamento() + (float) $sangria->valor, 2)
            : null;

        $valorRules = ['required', 'numeric', 'min:0.01'];
        if ($disponivelEspecie !== null) {
            $valorRules[] = "max:{$disponivelEspecie}";
        }

        $request->validate([
            'data_retirada' => 'required|date',
            'categoria'     => 'required|in:' . implode(',', array_keys(Sangria::$categorias)),
            'valor'         => $valorRules,
            'observacao'    => 'nullable|string|max:255',
        ], [
            'valor.max' => "O valor não pode exceder o disponível em espécie (R$ " . number_format($disponivelEspecie ?? 0, 2, ',', '.') . ").",
        ]);

        $sangria->update([
            'data_retirada' => $request->data_retirada,
            'categoria'     => $request->categoria,
            'valor'         => $request->valor,
            'observacao'    => $request->observacao,
        ]);

        return redirect()->route('sangrias.index')
            ->with('success', 'Sangria atualizada com sucesso.');
    }

    public function destroy(Sangria $sangria)
    {
        $sangria->delete();

        return redirect()->route('sangrias.index')
            ->with('success', 'Sangria removida com sucesso.');
    }
}
