<?php

namespace App\Http\Controllers;

use App\Models\Bairros;
use Illuminate\Http\Request;

class BairrosController extends Controller
{
    public function index()
    {
        $bairros = Bairros::all();
        return view('bairros.index', compact('bairros'));
    }
    public function create()
    {
        return view('bairros.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'taxa' => 'required|numeric|min:0',
        ]);

        Bairros::create($request->only('nome', 'taxa'));

        return redirect()->route('bairros.index')->with('success', 'Bairro criado com sucesso!');
    }
    public function edit($id)
    {
        $bairro = Bairros::findOrFail($id);
        return view('bairros.edit', compact('bairro'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'nome' => 'required|string|max:255',
            'taxa' => 'required|numeric|min:0',
        ]);

        $bairro = Bairros::findOrFail($id);
        $bairro->update($request->only('nome', 'taxa'));

        return redirect()->route('bairros.index')->with('success', 'Bairro atualizado com sucesso!');
    }
    public function destroy($id)
    {
        $bairro = Bairros::findOrFail($id);
        $bairro->delete();

        return redirect()->route('bairros.index')->with('success', 'Bairro excluído com sucesso!');
    }
}