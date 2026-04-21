<?php

namespace App\Http\Controllers;

use App\Models\Ponto;
use Illuminate\Http\Request;

class PontoController extends Controller
{
    public function index()
    {
        $usuario = auth()->user();
        $pontoAberto = Ponto::where('usuario_id', $usuario->id)
            ->whereNull('saida_em')
            ->latest('entrada_em')
            ->first();

        return view('pontos.index', compact('pontoAberto'));
    }

    public function registros()
    {
        $pontos = Ponto::with('usuario')
            ->latest('entrada_em')
            ->get();

        return view('pontos.registros', compact('pontos'));
    }

    public function abrir()
    {
        $usuario = auth()->user();

        $pontoAberto = Ponto::where('usuario_id', $usuario->id)
            ->whereNull('saida_em')
            ->first();

        if ($pontoAberto) {
            return redirect()->route('pontos.index')
                ->with('error', 'Você já possui um ponto aberto.');
        }

        Ponto::create([
            'usuario_id' => $usuario->id,
            'entrada_em' => now(),
        ]);

        return redirect()->route('pontos.index')
            ->with('success', 'Ponto aberto com sucesso.');
    }

    public function fechar()
    {
        $usuario = auth()->user();

        $pontoAberto = Ponto::where('usuario_id', $usuario->id)
            ->whereNull('saida_em')
            ->latest('entrada_em')
            ->first();

        if (!$pontoAberto) {
            return redirect()->route('pontos.index')
                ->with('error', 'Nenhum ponto aberto para fechar.');
        }

        $pontoAberto->update([
            'saida_em' => now(),
        ]);

        return redirect()->route('pontos.index')
            ->with('success', 'Ponto fechado com sucesso.');
    }
}
