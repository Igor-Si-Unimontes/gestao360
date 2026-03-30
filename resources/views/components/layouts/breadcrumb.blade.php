<div class="container">
    <div class="py-4 mb-4 d-flex align-items-center justify-content-between">

        <div>
            <h1 class="mb-2" style="font-size: 20px; font-weight: 700; color: #343A40;">
                {{ $title }}
            </h1>

            <nav aria-label="Breadcrumb">
                <ol class="breadcrumb mb-0" style="background: transparent; padding: 0;">

                    <li class="breadcrumb-item d-flex align-items-center">
                        <a href="{{ route('dashboard') }}" class="text-dark">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2"
                                viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 9.75L12 3l9 6.75V21a1.5 1.5 0 0 1-1.5 1.5H4.5A1.5 1.5 0 0 1 3 21V9.75z" />
                                <path d="M9 22V12h6v10" />
                            </svg>
                        </a>
                    </li>

                    @foreach ($breadcrumbs as $breadcrumb)
                        <li class="breadcrumb-item d-flex align-items-center">
                            @if (!empty($breadcrumb['route']))
                                <a href="{{ route($breadcrumb['route']) }}" class="text-dark text-decoration-none">
                                    {{ $breadcrumb['name'] }}
                                </a>
                            @else
                                <span class="text-dark">
                                    {{ $breadcrumb['name'] }}
                                </span>
                            @endif
                        </li>
                    @endforeach

                </ol>
            </nav>
        </div>

        <div>
            {{ $slot }}
        </div>

    </div>
</div>
