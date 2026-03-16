<?php

namespace App\Http\Controllers;

use App\Http\Requests\BatchRequest;
use App\Models\Batch;
use App\Models\Product;
use Illuminate\Http\Request;
use SebastianBergmann\Type\FalseType;

class BatchController extends Controller
{
    public function index()
    {
        $batches = Batch::all();
        return view('batches.index', compact('batches'));
    }

    public function create(Product $product)
    {
        return view('batches.create', compact('product'));
    }

    public function store(BatchRequest $request, Product $product)
    {
        $data = $request->validated();
        $data['product_id'] = $product->id;
        $data['created_by'] = auth()->user()->first_name;
        Batch::create($data);

        return redirect()->route('produtos.index')->with('success', 'Lote criado com sucesso.');
    }

    public function show(Batch $batch)
    {
        return view('batches.show', compact('batch'));
    }

    public function edit(Batch $batch)
    {
        return view('batches.edit', compact('batch'));
    }

    public function update(BatchRequest $request, Batch $batch)
    {
        $data = $request->validated();
        $data['updated_by'] = auth()->user()->first_name;
        $batch->update($data);

        return redirect()->route('produtos.index')->with('success', 'Lote atualizado com sucesso.');
    }

    public function inativandoLote(Batch $batch)
    {
        $batch->active = false;
        $batch->save();

        return redirect()->route('produtos.index')->with('success', 'Lote inativado com sucesso.');
    }
    public function ativandoLote(Batch $batch)
    {
        $batch->active = true;
        $batch->save();

        return redirect()->route('produtos.index')->with('success', 'Lote reativado com sucesso.');
    }
}
