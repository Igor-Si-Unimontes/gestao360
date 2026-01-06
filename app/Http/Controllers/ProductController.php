<?php

namespace App\Http\Controllers;

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

    public function store(ProductRequest $request)
    {
        Product::create($request->validated());

        return redirect()->route('produtos.index')->with('success', 'Produto criado com sucesso.');
    }

    public function show(Product $produto)
    {
        $produto->load('category', 'supplier', 'batches');
        return view('products.show', compact('produto'));
    }

    public function edit(Product $produto)
    {
        $categories = Category::where('status', 1)->get();
        $suppliers = Supplier::all();
        $produto->load('category', 'supplier', 'batches');
        return view('products.edit', compact('produto', 'categories', 'suppliers'));
    }

    public function update(ProductRequest $request, Product $produto)
    {
        $produto->update($request->validated());

        return redirect()->route('produtos.index')->with('success', 'Produto atualizado com sucesso.');
    }

    public function destroy(Product $produto)
    {
        $produto->delete();

        return redirect()->route('produtos.index')->with('success', 'Produto deletado com sucesso.');
    }
}
