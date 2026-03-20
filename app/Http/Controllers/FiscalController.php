<?php

namespace App\Http\Controllers;

use App\Http\Requests\FiscalRequest;
use App\Models\Fiscal;
use Illuminate\Http\Request;

class FiscalController extends Controller
{

    public function store(FiscalRequest $request)
    {
        Fiscal::create($request->validated());

        return redirect()->route('produtos.index')->with('success', 'Fiscal criado com sucesso.');
    }

    public function update(FiscalRequest $request, Fiscal $fiscal)
    {
        $fiscal->update($request->validated());

        return redirect()->route('produtos.index')->with('success', 'Fiscal atualizado com sucesso.');
    }

}
