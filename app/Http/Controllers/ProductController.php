<?php

namespace App\Http\Controllers;

use App\Http\Requests\FiscalRequest;
use App\Http\Requests\ProductRequest;
use App\Models\Category;
use App\Models\Product;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category', 'batches')->get();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        $categories = Category::where('status', 1)->get();
        $suppliers = Supplier::all();
        return view('products.create', compact('categories', 'suppliers'));
    }

    public function store(ProductRequest $request, FiscalRequest $fiscalRequest)
    {
        $product = Product::create($request->validated());

        $fiscalData = $fiscalRequest->validated();

        $fiscalData['product_id'] = $product->id;

        if (collect($fiscalData)->except('product_id')->filter()->isNotEmpty()) {
            $product->fiscal()->create($fiscalData);
        }

        return redirect()->route('produtos.index')
            ->with('success', 'Produto criado com dados fiscais!');
    }

    public function show(Product $produto)
    {
        $produto->load('category', 'supplier', 'batches', 'fiscal');
        return view('products.show', compact('produto'));
    }

    public function edit(Product $produto)
    {
        $categories = Category::where('status', 1)->get();
        $suppliers = Supplier::all();
        $produto->load('category', 'supplier', 'batches', 'fiscal');
        return view('products.edit', compact('produto', 'categories', 'suppliers'));
    }

    public function update(ProductRequest $request, Product $produto)
    {
        $produto->update($request->validated());

        return redirect()->route('produtos.index')->with('success', 'Produto atualizado com sucesso.');
    }

   public function destroy(Product $produto)
    {
        $temLotesAtivos = $produto->batches()
            ->where('active', true)
            ->where('quantity', '>', 0)
            ->exists();

        if ($temLotesAtivos) {
            return redirect()->route('produtos.index')
                ->with('error', 'Não é possível deletar um produto com lotes ativos que possuem estoque.');
        }

        $produto->delete();

        return redirect()->route('produtos.index')
            ->with('success', 'Produto deletado com sucesso.');
    }
}
