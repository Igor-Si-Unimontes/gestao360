<div class="p-4 mb-4 flex items-center justify-between">
    <div class="flex flex-col">
        <h1 style="font-size: 20px; font-weight: 700; color: #343A40; margin-left: 60px;">
            {{ $title }}
        </h1>
        <nav aria-label="Breadcrumb" class="ml-8">
            <ol class="list-reset flex">
                <li class="flex items-center">
                    <a href="{{ route('dashboard') }}" style="color: #000000; font-weight: 400; font-size: 14px;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 9.75L12 3l9 6.75V21a1.5 1.5 0 0 1-1.5 1.5H4.5A1.5 1.5 0 0 1 3 21V9.75z" />
                            <path d="M9 22V12h6v10" />
                        </svg>
                    </a>
                </li>
                @foreach ($breadcrumbs as $breadcrumb)
                    <li><span class="mx-2">/</span></li>
                    <li class="flex items-center">
                        @if (!empty($breadcrumb['route']))
                            <a href="{{ route($breadcrumb['route']) }}" style="color: #000000; font-weight: 400; font-size: 14px; text-decoration: none;">
                                {{ $breadcrumb['name'] }}
                            </a>
                        @else
                            <span style="color: #000000; font-weight: 400; font-size: 14px;">
                                {{ $breadcrumb['name'] }}
                            </span>
                        @endif
                    </li>
                @endforeach
            </ol>
        </nav>
    </div>
    <div style="margin-right: 60px;">
        {{ $slot }}
    </div>
</div>
