<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class BalcaoController extends Controller
{
    public function index()
    {
        $produtos = Product::all();
        return view('pedidos.balcao', compact('produtos'));
    }
}
