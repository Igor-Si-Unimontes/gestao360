<?php

namespace App\Http\Controllers;

use App\Models\Cardapio;
use Illuminate\Http\Request;

class CardapioController extends Controller
{

    public function vitrine()
    {
        $cardapio = Cardapio::unico();
        $itens = $cardapio->itensVisiveis()->get();

        return view('cardapio.vitrine', compact('cardapio', 'itens'));
    }

    public function dados()
    {
        $cardapio = Cardapio::unico();

        return view('cardapio.dados', compact('cardapio'));
    }

    public function atualizarDados(Request $request)
    {
        $cardapio = Cardapio::unico();

        $data = $request->validate([
            'nome' => 'required|string|max:160',
            'descricao' => 'nullable|string|max:2000',
        ]);

        $cardapio->update($data);

        return redirect()->route('cardapio.itens.index')
            ->with('success', 'Dados do cardápio atualizados.');
    }
}
