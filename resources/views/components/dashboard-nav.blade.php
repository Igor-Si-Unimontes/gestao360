<nav class="d-flex flex-column">
    <ul class="nav flex-column w-100">

        <li class="nav-item">
            <a href="{{ route('dashboard') }}"
               class="nav-link {{ request()->routeIs('dashboard') ? 'active bg-purple-sidebar text-purple-active' : 'bg-white text-gray-sidebar' }} px-4 py-3 d-flex align-items-center group"
               style="{{ request()->routeIs('dashboard') ? 'border-left: 4px solid #7212E7; color: #7212E7;' : '' }}">
                <i class="fas fa-home me-3 group-hover:text-[#7212E7]"></i>
                <span class="group-hover:text-[#7212E7]">Dashboard</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('employees.index') }}"
               class="nav-link {{ request()->routeIs('employees.*') ? 'active bg-purple-sidebar text-purple-active' : 'bg-white text-gray-sidebar' }} px-4 py-3 d-flex align-items-center group"
               style="{{ request()->routeIs('employees.*') ? 'border-left: 4px solid #7212E7; color: #7212E7;' : '' }}">
                <i class="fas fa-users me-3 group-hover:text-[#7212E7]"></i>
                <span class="group-hover:text-[#7212E7]">Funcionários</span>
            </a>
        </li>

        <li class="nav-item">

            <div class="nav-link px-4 py-3 d-flex justify-content-between align-items-center 
                {{ request()->routeIs('produtos.*') || request()->routeIs('categories.*') || request()->routeIs('suppliers.*') 
                    ? 'active bg-purple-sidebar text-purple-active' 
                    : 'bg-white text-gray-sidebar' }} 
                group cursor-pointer"
                onclick="toggleMenu('menuProdutos')"
                style="{{ request()->routeIs('produtos.*') || request()->routeIs('categories.*') || request()->routeIs('suppliers.*') 
                    ? 'border-left: 4px solid #7212E7; color: #7212E7;' 
                    : '' }}">

                <div class="d-flex align-items-center">
                    <i class="fas fa-box me-3 group-hover:text-[#7212E7]"></i>
                    <span class="group-hover:text-[#7212E7]">Produtos</span>
                </div>

                <i class="fas fa-chevron-down transition" id="icon-menuProdutos"></i>
            </div>

            <ul id="menuProdutos" class="submenu"
                style="{{ request()->routeIs('produtos.*') || request()->routeIs('categories.*') || request()->routeIs('suppliers.*') ? 'display:block' : '' }}">

                <li>
                    <a href="{{ route('produtos.index') }}"
                       class="nav-link ps-5 py-2 d-flex align-items-center group
                       {{ request()->routeIs('produtos.*') ? 'text-purple-active' : 'text-gray-sidebar' }}">
                        <i class="fas fa-box me-2 group-hover:text-[#7212E7]"></i>
                        <span class="group-hover:text-[#7212E7]">Produtos</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('categories.index') }}"
                       class="nav-link ps-5 py-2 d-flex align-items-center group
                       {{ request()->routeIs('categories.*') ? 'text-purple-active' : 'text-gray-sidebar' }}">
                        <i class="fas fa-tags me-2 group-hover:text-[#7212E7]"></i>
                        <span class="group-hover:text-[#7212E7]">Categorias</span>
                    </a>
                </li>

                <li>
                    <a href="{{ route('suppliers.index') }}"
                       class="nav-link ps-5 py-2 d-flex align-items-center group
                       {{ request()->routeIs('suppliers.*') ? 'text-purple-active' : 'text-gray-sidebar' }}">
                        <i class="fas fa-truck me-2 group-hover:text-[#7212E7]"></i>
                        <span class="group-hover:text-[#7212E7]">Fornecedores</span>
                    </a>
                </li>

            </ul>
        </li>

        <li class="nav-item">
            <a href="{{ route('caixas.index') }}"
               class="nav-link {{ request()->routeIs('caixas.*') ? 'active bg-purple-sidebar text-purple-active' : 'bg-white text-gray-sidebar' }} px-4 py-3 d-flex align-items-center group"
               style="{{ request()->routeIs('caixas.*') ? 'border-left: 4px solid #7212E7; color: #7212E7;' : '' }}">
                <i class="fas fa-cash-register me-3 group-hover:text-[#7212E7]"></i>
                <span class="group-hover:text-[#7212E7]">Caixas</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('balcao') }}"
               class="nav-link {{ request()->routeIs('balcao') ? 'active bg-purple-sidebar text-purple-active' : 'bg-white text-gray-sidebar' }} px-4 py-3 d-flex align-items-center group"
               style="{{ request()->routeIs('balcao') ? 'border-left: 4px solid #7212E7; color: #7212E7;' : '' }}">
                <i class="fas fa-cash-register me-3 group-hover:text-[#7212E7]"></i>
                <span class="group-hover:text-[#7212E7]">Balcão</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('bairros.index') }}"
               class="nav-link {{ request()->routeIs('bairros.*') ? 'active bg-purple-sidebar text-purple-active' : 'bg-white text-gray-sidebar' }} px-4 py-3 d-flex align-items-center group"
               style="{{ request()->routeIs('bairros.*') ? 'border-left: 4px solid #7212E7; color: #7212E7;' : '' }}">
                <i class="fas fa-motorcycle me-3 group-hover:text-[#7212E7]"></i>
                <span class="group-hover:text-[#7212E7]">Taxa de Entrega</span>
            </a>
        </li>
    </ul>
</nav>

<style>
.submenu {
    display: none;
    list-style: none;
    padding-left: 0;
    margin: 0;
    background: white;
}

.cursor-pointer {
    cursor: pointer;
}

.transition {
    transition: transform 0.3s ease;
}
</style>

<script>
function toggleMenu(id) {
    const menu = document.getElementById(id);
    const icon = document.getElementById('icon-' + id);

    if (menu.style.display === 'block') {
        menu.style.display = 'none';
        icon.style.transform = 'rotate(0deg)';
    } else {
        menu.style.display = 'block';
        icon.style.transform = 'rotate(180deg)';
    }
}
</script>