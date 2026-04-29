<nav class="d-flex flex-column">
    <ul class="nav flex-column w-100">

        <li class="nav-item">
            <a href="{{ route('dashboard') }}"
               class="nav-link {{ request()->routeIs('dashboard') ? 'active bg-purple-sidebar text-purple-active' : 'bg-white text-gray-sidebar' }} px-4 py-3 d-flex align-items-center group"
               style="{{ request()->routeIs('dashboard') ? 'border-left: 4px solid #7212E7; color: #7212E7;' : '' }}">
                <i class="fas fa-home me-3"></i>
                <span>Dashboard</span>
            </a>
        </li>

        @role('Administrador')
        <li class="nav-item">
            <a href="{{ route('employees.index') }}"
               class="nav-link {{ request()->routeIs('employees.*') ? 'active bg-purple-sidebar text-purple-active' : 'bg-white text-gray-sidebar' }} px-4 py-3 d-flex align-items-center group"
               style="{{ request()->routeIs('employees.*') ? 'border-left: 4px solid #7212E7; color: #7212E7;' : '' }}">
                <i class="fas fa-users me-3"></i>
                <span>Funcionários</span>
            </a>
        </li>
        @endrole

        @role('Administrador|Garcom|Caixa')
        <li class="nav-item">
            <div class="nav-link px-4 py-3 d-flex justify-content-between align-items-center
                {{ request()->routeIs('produtos.*') || request()->routeIs('categories.*') || request()->routeIs('suppliers.*') || request()->routeIs('cardapio.*')
                    ? 'active bg-purple-sidebar text-purple-active'
                    : 'bg-white text-gray-sidebar' }}
                cursor-pointer"
                onclick="toggleMenu('menuProdutos')"
                style="{{ request()->routeIs('produtos.*') || request()->routeIs('categories.*') || request()->routeIs('suppliers.*') || request()->routeIs('cardapio.*')
                    ? 'border-left: 4px solid #7212E7; color: #7212E7;'
                    : '' }}">
                <div class="d-flex align-items-center">
                    <i class="fas fa-box me-3"></i>
                    <span>Produtos</span>
                </div>
                <i class="fas fa-chevron-down transition" id="icon-menuProdutos"></i>
            </div>

            <ul id="menuProdutos" class="submenu"
                style="{{ request()->routeIs('produtos.*') || request()->routeIs('categories.*') || request()->routeIs('suppliers.*') || request()->routeIs('cardapio.*') ? 'display:block' : '' }}">
                <li>
                    <a href="{{ route('produtos.index') }}"
                       class="nav-link ps-5 py-2 d-flex align-items-center {{ request()->routeIs('produtos.*') ? 'text-purple-active' : 'text-gray-sidebar' }}">
                        <i class="fas fa-box me-2"></i><span>Produtos</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('categories.index') }}"
                       class="nav-link ps-5 py-2 d-flex align-items-center {{ request()->routeIs('categories.*') ? 'text-purple-active' : 'text-gray-sidebar' }}">
                        <i class="fas fa-tags me-2"></i><span>Categorias</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('suppliers.index') }}"
                       class="nav-link ps-5 py-2 d-flex align-items-center {{ request()->routeIs('suppliers.*') ? 'text-purple-active' : 'text-gray-sidebar' }}">
                        <i class="fas fa-truck me-2"></i><span>Fornecedores</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('cardapio.itens.index') }}"
                       class="nav-link ps-5 py-2 d-flex align-items-center {{ request()->routeIs('cardapio.*') ? 'text-purple-active' : 'text-gray-sidebar' }}">
                        <i class="fas fa-book-open me-2"></i><span>Cardápio</span>
                    </a>
                </li>
            </ul>
        </li>
        @endrole

        @role('Administrador|Caixa')
        @php $caixaNavStatus = \App\Models\Caixa::aberto(); @endphp
        <li class="nav-item">
            <a href="{{ route('caixas.index') }}"
               class="nav-link {{ request()->routeIs('caixas.*') ? 'active bg-purple-sidebar text-purple-active' : 'bg-white text-gray-sidebar' }} px-4 py-3 d-flex align-items-center"
               style="{{ request()->routeIs('caixas.*') ? 'border-left: 4px solid #7212E7; color: #7212E7;' : '' }}">
                <i class="fas fa-cash-register me-3"></i>
                <span class="flex-grow-1">Caixa</span>
                <span style="width:8px; height:8px; border-radius:50%; display:inline-block;
                      background:{{ $caixaNavStatus ? '#22c55e' : '#ef4444' }};"
                      title="{{ $caixaNavStatus ? 'Aberto' : 'Fechado' }}"></span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('sangrias.index') }}"
               class="nav-link {{ request()->routeIs('sangrias.*') ? 'active bg-purple-sidebar text-purple-active' : 'bg-white text-gray-sidebar' }} px-4 py-3 d-flex align-items-center"
               style="{{ request()->routeIs('sangrias.*') ? 'border-left: 4px solid #7212E7; color: #7212E7;' : '' }}">
                <i class="fas fa-hand-holding-usd me-3"></i>
                <span>Sangrias</span>
            </a>
        </li>
        @endrole

        <li class="nav-item">
            <a href="{{ route('ocorrencias.index') }}"
               class="nav-link {{ request()->routeIs('ocorrencias.*') ? 'active bg-purple-sidebar text-purple-active' : 'bg-white text-gray-sidebar' }} px-4 py-3 d-flex align-items-center"
               style="{{ request()->routeIs('ocorrencias.*') ? 'border-left: 4px solid #7212E7; color: #7212E7;' : '' }}">
                <i class="fas fa-triangle-exclamation me-3"></i>
                <span>Ocorrências</span>
            </a>
        </li>

        <li class="nav-item">
            <div class="nav-link px-4 py-3 d-flex justify-content-between align-items-center
                {{ request()->routeIs('pontos.*') ? 'active bg-purple-sidebar text-purple-active' : 'bg-white text-gray-sidebar' }}
                cursor-pointer"
                onclick="toggleMenu('menuPontos')"
                style="{{ request()->routeIs('pontos.*') ? 'border-left: 4px solid #7212E7; color: #7212E7;' : '' }}">
                <div class="d-flex align-items-center">
                    <i class="fas fa-clock me-3"></i>
                    <span>Ponto</span>
                </div>
                <i class="fas fa-chevron-down transition" id="icon-menuPontos"></i>
            </div>

            <ul id="menuPontos" class="submenu"
                style="{{ request()->routeIs('pontos.*') ? 'display:block' : '' }}">
                <li>
                    <a href="{{ route('pontos.index') }}"
                       class="nav-link ps-5 py-2 d-flex align-items-center {{ request()->routeIs('pontos.index') ? 'text-purple-active' : 'text-gray-sidebar' }}">
                        <i class="fas fa-play-circle me-2"></i><span>Bater Ponto</span>
                    </a>
                </li>
                @role('Administrador')
                <li>
                    <a href="{{ route('pontos.registros') }}"
                       class="nav-link ps-5 py-2 d-flex align-items-center {{ request()->routeIs('pontos.registros') ? 'text-purple-active' : 'text-gray-sidebar' }}">
                        <i class="fas fa-table me-2"></i><span>Registros</span>
                    </a>
                </li>
                @endrole
            </ul>
        </li>

        @role('Administrador|Caixa|Atendente')
        <li class="nav-item">
            <a href="{{ route('balcao') }}"
               class="nav-link {{ request()->routeIs('balcao') ? 'active bg-purple-sidebar text-purple-active' : 'bg-white text-gray-sidebar' }} px-4 py-3 d-flex align-items-center"
               style="{{ request()->routeIs('balcao') ? 'border-left: 4px solid #7212E7; color: #7212E7;' : '' }}">
                <i class="fas fa-cash-register me-3"></i>
                <span>Balcão</span>
            </a>
        </li>
        @endrole

        @role('Administrador|Garcom|Atendente')
        <li class="nav-item">
            <a href="{{ route('mesas.index') }}"
               class="nav-link {{ request()->routeIs('mesas.*') ? 'active bg-purple-sidebar text-purple-active' : 'bg-white text-gray-sidebar' }} px-4 py-3 d-flex align-items-center"
               style="{{ request()->routeIs('mesas.*') ? 'border-left: 4px solid #7212E7; color: #7212E7;' : '' }}">
                <i class="fas fa-chair me-3"></i>
                <span>Mesas</span>
            </a>
        </li>
        @endrole

        @role('Administrador|Cozinha')
        <li class="nav-item">
            <a href="{{ route('cozinha.index') }}"
               class="nav-link {{ request()->routeIs('cozinha.*') ? 'active bg-purple-sidebar text-purple-active' : 'bg-white text-gray-sidebar' }} px-4 py-3 d-flex align-items-center"
               style="{{ request()->routeIs('cozinha.*') ? 'border-left: 4px solid #7212E7; color: #7212E7;' : '' }}">
                <i class="fas fa-fire-burner me-3"></i>
                <span>Cozinha</span>
            </a>
        </li>
        @endrole

        @role('Administrador|Garcom|Caixa|Atendente')
        <li class="nav-item">
            <a href="{{ route('pedidos.index') }}"
               class="nav-link {{ request()->routeIs('pedidos.*') ? 'active bg-purple-sidebar text-purple-active' : 'bg-white text-gray-sidebar' }} px-4 py-3 d-flex align-items-center"
               style="{{ request()->routeIs('pedidos.*') ? 'border-left: 4px solid #7212E7; color: #7212E7;' : '' }}">
                <i class="fas fa-receipt me-3"></i>
                <span>Pedidos</span>
            </a>
        </li>
        @endrole

        @role('Administrador')
        <li class="nav-item">
            <a href="{{ route('bairros.index') }}"
               class="nav-link {{ request()->routeIs('bairros.*') ? 'active bg-purple-sidebar text-purple-active' : 'bg-white text-gray-sidebar' }} px-4 py-3 d-flex align-items-center"
               style="{{ request()->routeIs('bairros.*') ? 'border-left: 4px solid #7212E7; color: #7212E7;' : '' }}">
                <i class="fas fa-motorcycle me-3"></i>
                <span>Taxa de Entrega</span>
            </a>
        </li>

        <li class="nav-item">
            <a href="{{ route('relatorios.index') }}"
               class="nav-link {{ request()->routeIs('relatorios.*') ? 'active bg-purple-sidebar text-purple-active' : 'bg-white text-gray-sidebar' }} px-4 py-3 d-flex align-items-center"
               style="{{ request()->routeIs('relatorios.*') ? 'border-left: 4px solid #7212E7; color: #7212E7;' : '' }}">
                <i class="fas fa-chart-pie me-3"></i>
                <span>Relatórios</span>
            </a>
        </li>
        @endrole

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
.cursor-pointer { cursor: pointer; }
.transition { transition: transform 0.3s ease; }
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
