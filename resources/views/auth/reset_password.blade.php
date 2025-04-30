@extends('components.layouts.auth-layout')

@section('title', 'Recuperação de senha')

@section('content')

<div class="container-fluid m-0 p-0">
    <div class="row">
        <div class="col-md-6 d-none d-md-block p-0">
            <div style="background-image: url('{{ asset('images/recuperaSenha.webp') }}'); background-size: cover; background-position: center; height: 100vh; width: 100%;"></div>
        </div>

        <div class="col-md-6 align-items-center justify-content-center" style="background-color: #FAFCFF;">
            <div class="container" style="max-width: 600px; margin-top: 100px;">
                <img src="{{ asset('images/logo.webp') }}" alt="Logo" class="img-fluid mb-4">
            </div>

            <div class="container" style="max-width: 600px;">
                <h2 style="color: #1B1B1B; font-weight: 700; font-size: 46px; margin-top: 50px;">Criar nova senha</h2>
                <p class="text-gray mt-3" style="font-size: 16px; font-weight: 400;">
                    Defina sua nova senha abaixo
                </p>

                <form action="{{ route('resetPasswordUpdate') }}" method="POST" novalidate>
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="mb-3">
                        <label for="new_password" class="form-label text-gray-label mt-4">Nova senha</label>
                        <input type="password" name="new_password" id="new_password" required
                               class="form-control p-3 @error('new_password') is-invalid @enderror"
                               placeholder="Digite sua nova senha...">
                        @error('new_password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label text-gray-label mt-4">Confirme a nova senha</label>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" required
                               class="form-control p-3 @error('new_password_confirmation') is-invalid @enderror"
                               placeholder="Confirme sua nova senha...">
                        @error('new_password_confirmation')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-purple text-white w-100 mt-5" style="font-size: 18px; font-weight: 400;">
                            Atualizar senha
                            <i class=" fas fa-solid fa-arrow-right-to-bracket"></i>
                        </button>
                    </div>
                </form>

                @if(session('server_message'))
                    <div class="alert alert-warning text-center mt-3">
                        {{ session('server_message') }}
                    </div>
                @endif

                <div class="mt-4 text-start">
                    <a href="{{ route('login') }}" class="text-decoration text-gray">
                        Lembrou sua senha? Faça login
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
