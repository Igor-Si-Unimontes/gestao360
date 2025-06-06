<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\EmployeeService;
use App\Models\User;
use App\Models\Role;
use Devrabiul\ToastMagic\Facades\ToastMagic;
class EmployeeController extends Controller
{
    protected $service;

    public function __construct(EmployeeService $service)
    {
        $this->service = $service;
    }
    public function index()
    {
        $employees = $this->service->getAll();
        return view('employees.index', compact('employees'));
    }
    public function show($id)
    {
        $employees = $this->service->find($id);
        return view('employees.index', compact('employees'));
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

        ToastMagic::success('Funcionário cadastrado com sucesso!');
        return redirect()->route('employees.index');
    }
    public function edit($id)
    {
        $employees = $this->service->find($id);
        $roles = Role::all();

        return view('employees.edit', compact('employees', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $employee = $this->service->find($id);
        $user = $employee->user;

        $validated = $request->validate([
            'name'  => 'required|string|max:80',
            'phone' => 'required|string|max:20',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role_id' => 'required|exists:roles,id',
        ]);

        $user->email = $validated['email'];
        $user->role_id = $validated['role_id'];

        if ($request->filled('password')) {
            $request->validate([
                'password' => 'required|string|min:6|confirmed',
            ]);
            $user->password = bcrypt($request->password);
        }
        $user->save();

        $this->service->update($id, [
            'name' => $validated['name'],
            'phone' => $validated['phone'],
        ]);
        ToastMagic::success('Funcionário atualizado com sucesso!');
        return redirect()->route('employees.index');
    }

    public function destroy($id)
    {
        $employee = $this->service->find($id);
        $employee->load('user'); 
    
        if ($employee->user) {
            $employee->user->delete();
        }
    
        $this->service->delete($id);
        ToastMagic::success('Funcionário excluído com sucesso!');
        return redirect()->route('employees.index');
    }    
    
}
