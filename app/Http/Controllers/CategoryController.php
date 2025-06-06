<?php

namespace App\Http\Controllers;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Devrabiul\ToastMagic\Facades\ToastMagic;

class CategoryController extends Controller
{
    protected $service;
    public function __construct(CategoryService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $categories = $this->service->getAll();
        return view('categories.index', compact('categories'));
    }
    public function create()
    {
        return view('categories.create');
    }
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:1|max:40',
        ],[
            'name.required' => 'O campo nome é obrigatório.',
            'name.min' => 'O campo nome deve ter no mínimo :min caracteres.',
            'name.max' => 'O campo nome deve ter no máximo :max caracteres.',
        ]);
        $this->service->store($validated);

        ToastMagic::success('Categoria cadastrada com sucesso!');
        return redirect()->route('categories.index');
    }
    public function edit($id)
    {
        $category = $this->service->find($id);
        return view('categories.edit', compact('category'));
    }
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|min:1|max:40',
        ],[
            'name.required' => 'O campo nome é obrigatório.',
            'name.min' => 'O campo nome deve ter no mínimo :min caracteres.',
            'name.max' => 'O campo nome deve ter no máximo :max caracteres.',
        ]);
        $this->service->update($id, $validated);

        ToastMagic::success('Categoria atualizada com sucesso!');
        return redirect()->route('categories.index');
    }
    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|boolean',
        ]);
    
        $this->service->update($id, $validated);

        return response()->json(['message' => 'Status atualizado com sucesso.']);
    }
    
    public function destroy($id)
    {
        $this->service->delete($id);
        ToastMagic::success('Categoria excluida com sucesso!');
        return redirect()->route('categories.index');
    }
}
