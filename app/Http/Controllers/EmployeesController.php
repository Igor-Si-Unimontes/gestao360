<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EmployeesService;
use App\Models\User;
use App\Models\Role;
class EmployeesController extends Controller
{
    protected $service;

    public function __construct(EmployeesService $service)
    {
        $this->service = $service;
    }
    public function index()
    {
        return view('employees.index');
    }
    public function create()
    {
        $roles = Role::all();
        return view('employees.create', compact('roles'));
    }
    public function store(Request $request)
    {
        $authController = new AuthController();
        $user = $authController->storeUser($request);
    
        $validated = $request->validate([
            'name'  => 'required|string|min:0|max:80',
            'phone' => 'required|string|min:0|max:20',
        ]);
    
        $validated['email'] = $user->email;
        $validated['user_id'] = $user->id; 
    
        $this->service->store($validated);
    
        return redirect()->route('employees.index')->with('success', 'Funcionário criado com sucesso!');
    }
    
}
