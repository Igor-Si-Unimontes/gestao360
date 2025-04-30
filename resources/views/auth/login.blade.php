@extends('components.layouts.auth-layout')

@section('title', 'Login')

@section('content')

<div class="container-fluid m-0 p-0">
    <div class="row">
        <div class="col-md-6 d-none d-md-block p-0">
            <div style="background-image: url('{{ asset('images/login.webp') }}'); background-size: cover; background-position: center; height: 100vh; width: 100%;"></div>
        </div>
        

        <div class="col-md-6 align-items-center justify-content-center" style="background-color: #FAFCFF;">
            <div class="container" style="max-width: 600px; margin-top: 100px;">
                <img src="{{ asset('images/logo.webp') }}" alt="Logo" class="img-fluid mb-4">
            </div>
            <div class="container" style="max-width: 600px;">
                <h2 style="color: #1B1B1B; font-weight: 700; font-size: 46px; margin-top: 50px;">Login</h2>
                <p class="text-gray mt-3" style="font-size: 16px; font-weight: 400;">Entre com seus acessos para logar no sistema</p>

                <form action="{{ route('authenticate') }}" method="POST" novalidate>
                    @csrf

                    <div class="mb-3">
                        <label for="email" class="form-label text-gray-label mt-4">E-mail</label>
                        <input type="email" name="email" id="email" required
                               value="{{ old('email') }}"
                               class="form-control form-control p-3 @error('email') is-invalid @enderror" placeholder="Digite seu email...">
                        @error('email')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label text-gray-label mt-2">Senha</label>
                        <input type="password" name="password" id="password" required
                               class="form-control form-control p-3 @error('password') is-invalid @enderror" placeholder="Digite sua senha...">
                        @error('password')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                        @enderror
                    </div>

                    <div class="mt-4 text-start">
                        <a href="{{ route('forgotPassword') }}" class="text-decoration text-gray">
                            Esqueci minha senha
                        </a>
                    </div>
                
                    <div class="d-grid">
                        <button type="submit" class="btn btn-purple text-white w-100 mt-5" style="font-size: 18px; font-weight: 400;">
                            Entrar
                            <i class=" fas fa-solid fa-arrow-right-to-bracket"></i>
                        </button>
                    </div>
                    
                </form>

                @if(session('invalid_login'))
                    <div class="alert alert-danger text-center mt-3">
                        {{ session('invalid_login') }}
                    </div>
                @endif

                @if(session('password_reset'))
                    <div class="alert alert-success text-center mt-3">
                        {{ session('password_reset') }}
                    </div>
                @endif

                @if(session('account_deleted'))
                    <div class="alert alert-success text-center mt-3">
                        {{ session('account_deleted') }}
                    </div>
                @endif
                    
               <!--<p class="text-center text-muted mt-4 small">
                    Don’t have an account?
                    <a href="{{ route('register') }}" class="text-laravel-yellow-100 text-decoration-none">
                        Get Started
                    </a>
                </p>
            -->
            </div>
        </div>
    </div>
</div>
@endsection
