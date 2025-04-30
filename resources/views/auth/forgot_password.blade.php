@extends('components.layouts.auth-layout')

@section('title', 'Esqueci a senha')

@section('content')

<div class="container-fluid m-0 p-0">
    <div class="row">
        <div class="col-md-6 d-none d-md-block p-0">
            <div style="background-image: url('{{ asset('images/enviarLinkSenha.webp') }}'); background-size: cover; background-position: center; height: 100vh; width: 100%;"></div>
        </div>

        <div class="col-md-6 align-items-center justify-content-center" style="background-color: #FAFCFF;">
            <div class="container" style="max-width: 600px; margin-top: 100px;">
                <img src="{{ asset('images/logo.webp') }}" alt="Logo" class="img-fluid mb-4">
            </div>

            <div class="container" style="max-width: 600px;">
                <h2 style="color: #1B1B1B; font-weight: 700; font-size: 46px; margin-top: 50px;">Esqueci minha senha</h2>
                <p class="text-gray mt-3" style="font-size: 16px; font-weight: 400;">
                    Informe seu e-mail para receber o link de redefinição de senha
                </p>

                <form action="{{ route('sendForgotPasswordLink') }}" method="POST" novalidate>
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label text-gray-label mt-4">E-mail</label>
                        <input type="email" name="email" id="email" required
                               value="{{ old('email') }}"
                               class="form-control p-3 @error('email') is-invalid @enderror" 
                               placeholder="Digite seu email...">
                        @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-purple text-white w-100 mt-5" style="font-size: 18px; font-weight: 400;">
                            Enviar link
                            <i class="fas fa-paper-plane ms-2"></i>
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
