<!doctype html>
<html lang="pt-BR" class="h-100">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Título da página')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:100,200,300,400,500,600,700,800,900" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600&display=swap" rel="stylesheet">

    @vite([
        'resources/css/app.css', 
        'resources/css/appbs.css', 
        'resources/js/app.js', 
        'resources/fontawesome/all.min.js'
    ])
</head>

<body style="background-color: #f5f5f5; font-family: 'Montserrat', sans-serif;" class="h-100">

<div class="container-fluid p-0">
    <div class="row g-0">
        <nav class="col-md-3 col-lg-2 d-md-block bg-white sidebar p-0 vh-100">
            <div class="position-sticky d-flex flex-column h-100">
                <div class="d-flex justify-content-between align-items-center p-3">
                    <img src="{{ asset('images/logo.webp') }}" alt="logo">
                </div>

                <div class="flex-grow-1 overflow-auto p-1">
                    <x-dashboard-nav/>
                </div>

                <div class="border-top mt-4">
                    <a href="{{ route('logout') }}" 
                       class="d-flex align-items-center gap-3 p-3 rounded bg-red-sidebar text-red text-decoration-none"
                       style="font-size: 1.1rem;">
                       <i class="fas fa-sign-out-alt"></i>
                       <span>Encerrar sessão</span>
                    </a>
                </div>                
            </div>
        </nav>

        <div class="col-md-9 ms-sm-auto col-lg-10 d-flex flex-column vh-100">
    
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom">
                <div class="container-fluid px-4 p-3" style="margin-right: 25px "> 
                    <div class="d-flex align-items-center ms-auto">
                        <div class="text-end me-3">
                            <div style=" color #3C3D37; font-size: 16px; font-weight: 500; line-height: 17px;">
                                Olá, {{ Auth::user()->first_name . ' ' . Auth::user()->last_name }}
                            </div>
                            <div style="color: #7212E7; font-size: 14px; font-weight: 700;">
                                {{ Auth::user()->role ?? 'Cargo' }}
                            </div>
                        </div>
        
                        <div class="avatar rounded-circle overflow-hidden" style="width: 45px; height: 45px;">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->first_name) }}&background=7212E7&color=fff&size=128" 
                                 alt="Avatar" 
                                 class="img-fluid h-100 w-100 object-fit-cover">
                        </div>
        
                    </div>
                </div>
            </nav>
        
            <div class="container-fluid">
                @yield('content')
                @yield('styles')
                @yield('scripts')
            </div>
        
        </div>
        
    </div>
</div>

</body>
</html>
