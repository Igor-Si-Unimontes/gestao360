<?php

namespace App\Http\Controllers;

use App\Models\Caixa;
use Illuminate\Http\Request;

class CaixaController extends Controller
{
    public function index()
    {
        $caixas = Caixa::all();
        return view('caixas.index', compact('caixas'));
    }
    public function abrirCaixa(Request $request)
{
    $existe = Caixa::where('status', 'ABERTO')->exists();

    if ($existe) {
        return redirect()->back()->with('error', 'Já existe um caixa aberto');
    }

    Caixa::create([
        'valor_abertura' => $request->valor_abertura,
        'status' => 'ABERTO',
        'usuario_id' => auth()->id()
    ]);

    return redirect()->back()->with('success', 'Caixa aberto com sucesso');
}
    public function fecharCaixa()
{
    $caixa = Caixa::where('status', 'ABERTO')->first();

    if (!$caixa) {
        return redirect()->back()->with('error', 'Nenhum caixa aberto');
    }

    $caixa->update([
        'status' => 'FECHADO',
        'valor_fechamento' => 0
    ]);

    return redirect()->back()->with('success', 'Caixa fechado com sucesso');
}
}
