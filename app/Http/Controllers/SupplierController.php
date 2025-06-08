<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\SupplierService;
use Devrabiul\ToastMagic\Facades\ToastMagic;
use Illuminate\Validation\Rule;

class SupplierController extends Controller
{
    protected $service;
    public function __construct(SupplierService $service)
    {
        $this->service = $service;
    }
    public function index()
    {
        $suppliers = $this->service->getAll();
        return view('suppliers.index', compact('suppliers'));
    }
    public function create()
    {
        return view('suppliers.create');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:1|max:100',
            'cnpj' => 'required|string|unique:suppliers|min:1|max:18',
            'phone' => 'required|string|min:1|max:20',
        ],[
            'name.required' => 'O campo nome é obrigatório.',
            'name.min' => 'O campo nome deve ter no mínimo :min caracteres.',
            'name.max' => 'O campo nome deve ter no máximo :max caracteres.',
            'cnpj.required' => 'O campo CNPJ é obrigatório.',
            'cnpj.min' => 'O campo CNPJ deve ter no mínimo :min caracteres.',
            'cnpj.max' => 'O campo CNPJ deve ter no máximo :max caracteres.',
            'cnpj.unique' => 'O CNPJ informado ja existe.',
            'phone.required' => 'O campo telefone é obrigatório.',
            'phone.min' => 'O campo telefone deve ter no mínimo :min caracteres.',
            'phone.max' => 'O campo telefone deve ter no máximo :max caracteres.',
        ]);
        $this->service->store($validated);
        ToastMagic::success('Fornecedor cadastrado com sucesso!');
        return redirect()->route('suppliers.index');
    }
    public function edit($id)
    {
        $supplier = $this->service->find($id);
        return view('suppliers.edit', compact('supplier'));
    }
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:1|max:100',
            'cnpj' => ['required','string','min:1','max:18',
                Rule::unique('suppliers')->ignore($id),
            ],
            'phone' => 'required|string|min:1|max:20',
        ],[
            'name.required' => 'O campo nome é obrigatório.',
            'name.min' => 'O campo nome deve ter no mínimo :min caracteres.',
            'name.max' => 'O campo nome deve ter no.maxcdn :max caracteres.',
            'cnpj.required' => 'O campo CNPJ é obrigatório.',
            'cnpj.min' => 'O campo CNPJ deve ter no mínimo :min caracteres.',
            'cnpj.max' => 'O campo CNPJ deve ter no.maxcdn :max caracteres.',
            'cnpj.unique' => 'O CNPJ informado ja existe.',
            'phone.required' => 'O campo telefone é obrigatório.',
            'phone.min' => 'O campo telefone deve ter no mínimo :min caracteres.',
            'phone.max' => 'O campo telefone deve ter no.maxcdn :max caracteres.',
        ]);
        $this->service->update($id, $validated);
        ToastMagic::success('Fornecedor atualizado com sucesso!');
        return redirect()->route('suppliers.index');
    }
    public function destroy($id)
    {
        $this->service->delete($id);
        ToastMagic::success('Fornecedor excluido com sucesso!');
        return redirect()->route('suppliers.index');
    }
}
