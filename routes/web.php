<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BairrosController;
use App\Http\Controllers\BalcaoController;
use App\Http\Controllers\BatchController;
use App\Http\Controllers\CaixaController;
use App\Http\Controllers\CardapioController;
use App\Http\Controllers\CardapioItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CozinhaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EmployeeController;
use App\Http\Controllers\FiscalController;
use App\Http\Controllers\MesaController;
use App\Http\Controllers\OcorrenciaController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\PontoController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\RelatorioController;
use App\Http\Controllers\SangriaController;
use App\Http\Controllers\SupplierController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\VendaController;
use Illuminate\Support\Facades\Route;

Route::get('/menu', [CardapioController::class, 'vitrine'])->name('cardapio.menu');
Route::get('/vitrine-cardapio/{cardapio}', fn () => redirect()->route('cardapio.menu', [], 301));

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

Route::middleware('auth')->group(function () {

    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::middleware('role:Administrador')->group(function () {
        Route::get('/funcionarios', [EmployeeController::class, 'index'])->name('employees.index');
        Route::get('/funcionarios/create', [EmployeeController::class, 'create'])->name('employees.create');
        Route::post('/funcionarios', [EmployeeController::class, 'store'])->name('employees.store');
        Route::get('/funcionarios/{id}/edit', [EmployeeController::class, 'edit'])->name('employees.edit');
        Route::patch('/funcionarios/{id}', [EmployeeController::class, 'update'])->name('employees.update');
        Route::delete('/funcionarios/{id}', [EmployeeController::class, 'destroy'])->name('employees.destroy');
    });

    Route::middleware('role:Administrador|Garcom|Caixa')->group(function () {
        Route::resource('categories', CategoryController::class);
        Route::patch('/categories/{id}/status', [CategoryController::class, 'updateStatus'])->name('categories.updateStatus');
        Route::resource('suppliers', SupplierController::class);
        Route::resource('produtos', ProductController::class);

        Route::get('/cardapio/itens', [CardapioItemController::class, 'index'])->name('cardapio.itens.index');
        Route::get('/cardapio/itens/criar', [CardapioItemController::class, 'create'])->name('cardapio.itens.create');
        Route::post('/cardapio/itens', [CardapioItemController::class, 'store'])->name('cardapio.itens.store');
        Route::get('/cardapio/itens/{item}/editar', [CardapioItemController::class, 'edit'])->name('cardapio.itens.edit');
        Route::put('/cardapio/itens/{item}', [CardapioItemController::class, 'update'])->name('cardapio.itens.update');
        Route::delete('/cardapio/itens/{item}', [CardapioItemController::class, 'destroy'])->name('cardapio.itens.destroy');

        Route::get('/cardapio/dados', [CardapioController::class, 'dados'])->name('cardapio.dados');
        Route::put('/cardapio/dados', [CardapioController::class, 'atualizarDados'])->name('cardapio.dados.update');

        Route::get('/lotes/create/{product}', [BatchController::class, 'create'])->name('lotes.create');
        Route::post('/lotes/{product}', [BatchController::class, 'store'])->name('lotes.store');
        Route::resource('lotes', BatchController::class)
            ->parameters(['lotes' => 'batch'])
            ->except(['create', 'store']);
        Route::patch('/lotes/{batch}/inativar', [BatchController::class, 'inativandoLote'])->name('lotes.inativarLote');
        Route::patch('/lotes/{batch}/ativar', [BatchController::class, 'ativandoLote'])->name('lotes.ativarLote');

        Route::post('/fiscais', [FiscalController::class, 'store'])->name('fiscais.store');
        Route::put('/fiscais/{fiscal}', [FiscalController::class, 'update'])->name('fiscais.update');
    });

    Route::middleware('role:Administrador|Caixa')->group(function () {
        Route::get('/caixas', [CaixaController::class, 'index'])->name('caixas.index');
        Route::get('/caixas/abrir', [CaixaController::class, 'abrirForm'])->name('caixas.abrir.form');
        Route::post('/caixas/abrir', [CaixaController::class, 'abrir'])->name('caixas.abrir');
        Route::get('/caixas/fechar', [CaixaController::class, 'fecharForm'])->name('caixas.fechar.form');
        Route::post('/caixas/fechar', [CaixaController::class, 'fechar'])->name('caixas.fechar');

        Route::get('/sangrias', [SangriaController::class, 'index'])->name('sangrias.index');
        Route::get('/sangrias/create', [SangriaController::class, 'create'])->name('sangrias.create');
        Route::post('/sangrias', [SangriaController::class, 'store'])->name('sangrias.store');
        Route::get('/sangrias/{sangria}/edit', [SangriaController::class, 'edit'])->name('sangrias.edit');
        Route::patch('/sangrias/{sangria}', [SangriaController::class, 'update'])->name('sangrias.update');
        Route::delete('/sangrias/{sangria}', [SangriaController::class, 'destroy'])->name('sangrias.destroy');
    });

    Route::middleware('role:Administrador')->group(function () {
        Route::get('/relatorios', [RelatorioController::class, 'index'])->name('relatorios.index');
        Route::resource('bairros', BairrosController::class);
    });

    Route::get('/ocorrencias', [OcorrenciaController::class, 'index'])->name('ocorrencias.index');
    Route::get('/ocorrencias/create', [OcorrenciaController::class, 'create'])->name('ocorrencias.create');
    Route::post('/ocorrencias', [OcorrenciaController::class, 'store'])->name('ocorrencias.store');
    Route::get('/ocorrencias/{ocorrencia}/edit', [OcorrenciaController::class, 'edit'])->name('ocorrencias.edit');
    Route::patch('/ocorrencias/{ocorrencia}', [OcorrenciaController::class, 'update'])->name('ocorrencias.update');
    Route::delete('/ocorrencias/{ocorrencia}', [OcorrenciaController::class, 'destroy'])->name('ocorrencias.destroy');

    Route::get('/pontos', [PontoController::class, 'index'])->name('pontos.index');
    Route::post('/pontos/abrir', [PontoController::class, 'abrir'])->name('pontos.abrir');
    Route::post('/pontos/fechar', [PontoController::class, 'fechar'])->name('pontos.fechar');
    Route::get('/pontos/registros', [PontoController::class, 'registros'])
        ->middleware('role:Administrador')
        ->name('pontos.registros');

    Route::middleware('role:Administrador|Caixa|Atendente')->group(function () {
        Route::get('/balcao', [BalcaoController::class, 'index'])->name('balcao');
        Route::post('/vendas', [VendaController::class, 'store'])->name('vendas.store');
    });

    Route::middleware('role:Administrador|Garcom|Atendente')->group(function () {
        Route::get('/mesas', [MesaController::class, 'index'])->name('mesas.index');
        Route::post('/mesas/{mesa}/abrir', [MesaController::class, 'abrirMesa'])->name('mesas.abrir');
        Route::get('/mesas/{mesa}/comanda', [MesaController::class, 'comanda'])->name('mesas.comanda');
        Route::get('/mesas/{mesa}/itens', [MesaController::class, 'itensJson'])->name('mesas.itens.json');
        Route::post('/mesas/{mesa}/item', [MesaController::class, 'adicionarItem'])->name('mesas.item.add');
        Route::delete('/mesas/{mesa}/item/{item}', [MesaController::class, 'removerItem'])->name('mesas.item.remove');
        Route::post('/mesas/{mesa}/fechar', [MesaController::class, 'fecharMesa'])->name('mesas.fechar');
        Route::post('/mesas/{mesa}/cancelar', [MesaController::class, 'cancelarMesa'])->name('mesas.cancelar');
    });

    Route::middleware('role:Administrador|Cozinha')->group(function () {
        Route::get('/cozinha', [CozinhaController::class, 'index'])->name('cozinha.index');
        Route::get('/cozinha/pedidos', [CozinhaController::class, 'pedidos'])->name('cozinha.pedidos');
        Route::patch('/cozinha/{venda}/pronto', [CozinhaController::class, 'marcarPronto'])->name('cozinha.pronto');
    });

    Route::middleware('role:Administrador|Garcom|Caixa|Atendente')->group(function () {
        Route::get('/pedidos', [PedidoController::class, 'index'])->name('pedidos.index');
        Route::get('/pedidos/{pedido}', [PedidoController::class, 'show'])->name('pedidos.show');
        Route::patch('/pedidos/{pedido}/status', [PedidoController::class, 'updateStatus'])->name('pedidos.status');
        Route::delete('/pedidos/{pedido}', [PedidoController::class, 'destroy'])->name('pedidos.destroy');
    });

    Route::get('/profile', fn () => view('auth.profile'))->name('profile');
    Route::post('/profile/change_password', [UserController::class, 'changePassword'])->name('profile.change_password');
    Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::post('/delete_account', [UserController::class, 'deleteAccount'])->name('deleteAccount');
});
