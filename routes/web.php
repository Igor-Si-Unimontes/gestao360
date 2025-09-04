<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use Illuminate\Routing\Router;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\SupplierController;

// Rotas para usuários não autenticados
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'login'])->name('login');
    Route::post('/login', [AuthController::class, 'authenticate'])->name('authenticate');

    Route::get('/register', [AuthController::class, 'register'])->name('register');
    Route::post('/register', [AuthController::class, 'storeUser'])->name('storeUser');

    Route::get('/new_user_confirmation/{token}', [AuthController::class, 'newUserConfirmation'])->name('newUserConfirmation');

    Route::get('/forgot_password', [AuthController::class, 'forgotPassword'])->name('forgotPassword');
    Route::post('/forgot_password', [AuthController::class, 'sendForgotPasswordLink'])->name('sendForgotPasswordLink');
    Route::get('/reset_password/{token}', [AuthController::class, 'resetPassword'])->name('resetPassword');
    Route::post('/reset_password', [AuthController::class, 'resetPasswordUpdate'])->name('resetPasswordUpdate');


});


// Rota para usuários autenticados
Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('home');
    })->name('dashboard');

    // Employees
    Route::get('/funcionarios', [EmployeeController::class, 'index'])->name('employees.index');
    Route::get('/funcionarios/create', [EmployeeController::class, 'create'])->name('employees.create');
    Route::post('/funcionarios', [EmployeeController::class, 'store'])->name('employees.store');
    Route::get('/funcionarios/{id}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
    Route::patch('/funcionarios/{id}', [EmployeeController::class, 'update'])->name('employees.update');
    Route::delete('/funcionarios/{id}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
    //Categories
    Route::resource('categories', CategoryController::class);
    Route::patch('/categories/{id}/status', [CategoryController::class, 'updateStatus'])->name('categories.updateStatus');
    //Suppliers
    Route::resource('suppliers', SupplierController::class);
    //Products
    Route::resource('produtos', ProductController::class);
    //Batches
    Route::get('/lotes/create/{product}', [BatchController::class, 'create'])->name('lotes.create');
    Route::post('/lotes/{product}', [BatchController::class, 'store'])->name('lotes.store');
    Route::resource('lotes', BatchController::class)->except(['create', 'store']);
    //outra rota
    Route::get('/profile', function () {
        return view('auth.profile');
    })->name('profile');

    Route::post('/profile/change_password', [UserController::class, 'changePassword'])->name('profile.change_password');


    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

    Route::post("/delete_account", [UserController::class, 'deleteAccount'])->name('deleteAccount');

});
