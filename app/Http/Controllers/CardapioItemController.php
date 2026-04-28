<?php

namespace App\Http\Controllers;

use App\Models\Cardapio;
use App\Models\CardapioItem;
use Illuminate\Http\Request;

class CardapioItemController extends Controller
{
    public function index()
    {
        $cardapio = Cardapio::unico();
        $itens = $cardapio->itens()->get();

        return view('cardapio.itens.index', compact('cardapio', 'itens'));
    }

    public function create()
    {
        $cardapio = Cardapio::unico();

        return view('cardapio.itens.create', compact('cardapio'));
    }

    public function store(Request $request)
    {
        $cardapio = Cardapio::unico();
        $data = $this->validated($request);
        $data['cardapio_id'] = $cardapio->id;
        $data['ordem'] = (int) ($cardapio->itens()->max('ordem') ?? 0) + 1;

        CardapioItem::create($data);

        return redirect()->route('cardapio.itens.index')
            ->with('success', 'Item adicionado ao cardápio.');
    }

    public function edit(CardapioItem $item)
    {
        $this->assertItemDoCardapio($item);
        $cardapio = Cardapio::unico();

        return view('cardapio.itens.edit', compact('cardapio', 'item'));
    }

    public function update(Request $request, CardapioItem $item)
    {
        $this->assertItemDoCardapio($item);

        $item->update($this->validated($request));

        return redirect()->route('cardapio.itens.index')
            ->with('success', 'Item atualizado.');
    }

    public function destroy(CardapioItem $item)
    {
        $this->assertItemDoCardapio($item);

        $item->delete();

        return redirect()->route('cardapio.itens.index')
            ->with('success', 'Item removido.');
    }

    private function validated(Request $request): array
    {
        $request->merge([
            'imagem_url' => $request->filled('imagem_url') ? $request->input('imagem_url') : null,
        ]);

        $keys = implode(',', array_keys(CardapioItem::$categorias));

        $data = $request->validate([
            'categoria' => 'required|in:'.$keys,
            'nome' => 'required|string|max:160',
            'descricao' => 'nullable|string|max:2000',
            'serve_pessoas' => 'required|integer|min:1|max:500',
            'valor' => 'required|numeric|min:0',
            'imagem_url' => 'nullable|url|max:2048',
        ]);

        $data['visivel'] = $request->boolean('visivel');

        return $data;
    }

    private function assertItemDoCardapio(CardapioItem $item): void
    {
        abort_unless((int) $item->cardapio_id === (int) Cardapio::unico()->id, 404);
    }
}
