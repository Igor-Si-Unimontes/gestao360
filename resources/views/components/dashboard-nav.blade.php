<nav class="d-flex flex-column">
    <ul class="nav flex-column w-100">

        <li class="nav-item">
            <a href="{{ route('dashboard') }}"
               class="nav-link {{ request()->routeIs('dashboard') ? 'active bg-purple-sidebar text-purple-active' : 'bg-white text-gray-sidebar' }} rounded-0 px-4 py-3 w-100 d-flex align-items-center group"
               style="{{ request()->routeIs('dashboard') ? 'border-left: 4px solid #7212E7; color: #7212E7;' : '' }}">
                <i class="fas fa-home me-3 group-hover:text-[#7212E7]" style="{{ request()->routeIs('dashboard') ? 'color: #7212E7;' : '' }}"></i>
                <span class="group-hover:text-[#7212E7]">
                    Dashboard
                </span>
            </a>
        </li>        

        <li class="nav-item">
            <a href="{{ route('employees.index') }}"
            class="nav-link {{ request()->routeIs('employees.index') ? 'active bg-purple-sidebar text-purple-active' : 'bg-white text-gray-sidebar' }} rounded-0 px-4 py-3 w-100 d-flex align-items-center group"
               style="{{ request()->routeIs('employees.index') ? 'border-left: 4px solid #7212E7; color: #7212E7;' : '' }}">
                <i class="fas fa-users me-3 group-hover:text-[#7212E7]" style="{{ request()->routeIs('employees.index') ? 'color: #7212E7;' : '' }}"></i> 
                <span class="group-hover:text-[#7212E7]">
                    Funcionários
                </span>
            </a>
        </li>

        <li class="nav-item">
            <a href="#"
               class="nav-link {{ request()->routeIs('projects') ? 'active bg-purple-sidebar text-purple-active' : 'bg-white text-gray-sidebar' }} rounded-0 px-4 py-3 w-100 d-flex align-items-center group"
               style="{{ request()->routeIs('projects') ? 'border-left: 4px solid #7212E7; color: #7212E7;' : '' }}">
                <i class="fas fa-folder me-3 group-hover:text-[#7212E7]" style="{{ request()->routeIs('projects') ? 'color: #7212E7;' : '' }}"></i> 
                <span class="group-hover:text-[#7212E7]">
                    Pedidos
                </span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('profile') }}"
               class="nav-link {{ request()->routeIs('profile') ? 'active bg-purple-sidebar text-purple-active' : 'bg-white text-gray-sidebar' }} rounded-0 px-4 py-3 w-100 d-flex align-items-center group"
               style="{{ request()->routeIs('profile') ? 'border-left: 4px solid #7212E7; color: #7212E7;' : '' }}">
                <i class="fas fa-profile me-3 group-hover:text-[#7212E7]" style="{{ request()->routeIs('profile') ? 'color: #7212E7;' : '' }}"></i> 
                <span class="group-hover:text-[#7212E7]">
                    Calendar
                </span>
            </a>
        </li>

    </ul>
</nav>
