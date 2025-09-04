<?php

namespace App\Http\Controllers;

use App\Http\Requests\BatchRequest;
use App\Models\Batch;
use App\Models\Product;
use Illuminate\Http\Request;

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
        $batch->update($request->validated());

        return redirect()->route('produtos.index')->with('success', 'Lote atualizado com sucesso.');
    }

    public function destroy(Batch $batch)
    {
        $batch->delete();

        return redirect()->route('batches.index')->with('success', 'Lote deletado com sucesso.');
    }
}
