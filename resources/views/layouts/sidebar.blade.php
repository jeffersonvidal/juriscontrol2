<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo-icon">
            <img src="{{ asset('LogoJurisControl.svg') }}" alt="Logo JurisControl"
                style="filter: brightness(0) invert(1); margin:.25em;">
        </div>
        <span class="sidebar-logo-text">JurisControl</span>
        <button class="sidebar-toggle" id="sidebarToggle" title="Toggle sidebar">
            <i class="bi bi-chevron-left"></i>
        </button>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Principal</div>
        {{-- Dashboard --}}
        <a href="{{ route('dashboard') }}" title="Página Inicial"
            class="sidebar-item {{ request()->routeIs('dashboard') ? 'active' : '' }}">
            <span class="sidebar-item-icon"><i class="bi bi-grid-fill"></i></span>
            <span class="sidebar-item-text">Dashboard</span>
        </a>
        {{-- Empresas --}}
        @can('companies.view')
            <div>
                <a href="#" class="sidebar-item {{ request()->routeIs('companies.*') ? 'active' : '' }}"
                    title="Gestão de Empresas" data-submenu="campaigns-sub">
                    <span class="sidebar-item-icon"><i class="bi bi-buildings"></i></span>
                    <span class="sidebar-item-text">Empresas</span>
                    <span class="sidebar-item-badge">12</span>
                    <i class="bi bi-chevron-down sidebar-item-arrow"></i>
                </a>
                <div class="sidebar-submenu" id="campaigns-sub">

                    <a href="{{ route('companies.index') }}" title="Escritórios" class="sidebar-item active"><span
                            class="sidebar-item-icon"></span><span class="sidebar-item-text">Escritórios</span></a>

                    <a href="#" title="" class="sidebar-item"><span class="sidebar-item-icon"></span><span
                            class="sidebar-item-text">Active</span></a>
                    <a href="#" title="" class="sidebar-item"><span class="sidebar-item-icon"></span><span
                            class="sidebar-item-text">Drafts</span></a>
                    <a href="#" title="" class="sidebar-item"><span class="sidebar-item-icon"></span><span
                            class="sidebar-item-text">Archived</span></a>
                </div>
            </div>
        @endcan

        {{-- Agenda --}}
        <a href="#" class="sidebar-item" title="Gestão de Agenda">
            <span class="sidebar-item-icon"><i class="bi bi-alarm"></i></span>
            <span class="sidebar-item-text">Agenda</span>
            <span class="sidebar-item-badge">12</span>
        </a>

        {{-- Clientes --}}
        <a href="#" class="sidebar-item" title="Gestão de Clientes">
            <span class="sidebar-item-icon"><i class="bi bi-people"></i></span>
            <span class="sidebar-item-text">Clientes</span>
        </a>

        {{-- Processos --}}
        <div>
            <a href="#" title="Gestão de Processos" class="sidebar-item" data-submenu="audience-sub">
                <span class="sidebar-item-icon"><i class="bi bi-briefcase"></i></span>
                <span class="sidebar-item-text">Processos</span>
                <i class="bi bi-chevron-down sidebar-item-arrow"></i>
            </a>
            <div class="sidebar-submenu" id="audience-sub">
                <a href="#" title="" class="sidebar-item"><span class="sidebar-item-icon"></span><span
                        class="sidebar-item-text">Segments</span></a>
                <a href="#" title="" class="sidebar-item"><span class="sidebar-item-icon"></span><span
                        class="sidebar-item-text">Demographics</span></a>
                <a href="#" title="" class="sidebar-item"><span class="sidebar-item-icon"></span><span
                        class="sidebar-item-text">Behavior</span></a>
            </div>
        </div>

        {{-- Documentos --}}
        <div>
            <a href="#" title="Gestão de Documentos" class="sidebar-item" data-submenu="tasks-sub">
                <span class="sidebar-item-icon"><i class="bi bi-file-text"></i></span>
                <span class="sidebar-item-text">Documentos</span>
                <i class="bi bi-chevron-down sidebar-item-arrow"></i>
            </a>
            <div class="sidebar-submenu" id="tasks-sub">
                <a href="#" title="Todos os Documentos" class="sidebar-item"><span
                        class="sidebar-item-icon"></span><span class="sidebar-item-text">Todos os Documentos</span></a>
                <a href="#" title="Modelos de Documentos" class="sidebar-item"><span
                        class="sidebar-item-icon"></span><span class="sidebar-item-text">Modelos de
                        Documentos</span></a>
                <a href="#" title="Tipos de Documentos" class="sidebar-item"><span
                        class="sidebar-item-icon"></span><span class="sidebar-item-text">Tipos de Documentos</span></a>
            </div>
        </div>

        {{-- Gestão --}}
        <div class="nav-section-label">Gestão</div>

        {{-- Usuários --}}
        <a href="#" title="Gestão de Usuários" class="sidebar-item">
            <span class="sidebar-item-icon"><i class="bi bi-person-gear"></i></span>
            <span class="sidebar-item-text">Usuários</span>
        </a>

        {{-- Financeiro --}}
        <div>
            <a href="#" title="Financeiro" class="sidebar-item" data-submenu="financial-sub">
                <span class="sidebar-item-icon"><i class="bi bi-cash-stack"></i></span>
                <span class="sidebar-item-text">Financeiro</span>
                <span class="sidebar-item-badge">5</span>
                <i class="bi bi-chevron-down sidebar-item-arrow"></i>
            </a>
            <div class="sidebar-submenu" id="financial-sub">
                <a href="#" title="Faturas" class="sidebar-item"><span class="sidebar-item-icon"></span><span
                        class="sidebar-item-text">Faturas</span></a>
                <a href="#" title="Pagamentos" class="sidebar-item"><span class="sidebar-item-icon"></span><span
                        class="sidebar-item-text">Pagamentos</span></a>
                <a href="#" title="Fornecedores" class="sidebar-item"><span class="sidebar-item-icon"></span><span
                        class="sidebar-item-text">Fornecedores</span></a>
                <a href="#" title="Carteiras" class="sidebar-item"><span class="sidebar-item-icon"></span><span
                        class="sidebar-item-text">Carteiras</span></a>
            </div>
        </div>

        {{-- Relatórios --}}
        <div>
            <a href="#" title="Relatórios" class="sidebar-item" data-submenu="report-sub">
                <span class="sidebar-item-icon"><i class="bi bi-bar-chart-fill"></i></span>
                <span class="sidebar-item-text">Relatórios</span>
                <i class="bi bi-chevron-down sidebar-item-arrow"></i>
            </a>
            <div class="sidebar-submenu" id="report-sub">
                <a href="#" title="Faturas" class="sidebar-item"><span class="sidebar-item-icon"></span><span
                        class="sidebar-item-text">Faturas</span></a>
                <a href="#" title="Pagamentos" class="sidebar-item"><span class="sidebar-item-icon"></span><span
                        class="sidebar-item-text">Pagamentos</span></a>
                <a href="#" title="Fornecedores" class="sidebar-item"><span class="sidebar-item-icon"></span><span
                        class="sidebar-item-text">Fornecedores</span></a>
                <a href="#" title="Carteiras" class="sidebar-item"><span class="sidebar-item-icon"></span><span
                        class="sidebar-item-text">Carteiras</span></a>
            </div>
        </div>

        {{-- Configurações do Escritório --}}
        <div>
            <a href="#" title="Relatórios" class="sidebar-item" data-submenu="office-config-sub">
                <span class="sidebar-item-icon"><i class="bi bi-gear-fill"></i></span>
                <span class="sidebar-item-text">Config. do Escritório</span>
                <i class="bi bi-chevron-down sidebar-item-arrow"></i>
            </a>
            <div class="sidebar-submenu" id="office-config-sub">
                @can('tags.view')
                    <a href="{{ route('tags.index') }}" title="Tags / Etiquetas"
                        class="sidebar-item {{ request()->routeIs('tags.*') ? 'active' : '' }}"><span
                            class="sidebar-item-icon"></span><span class="sidebar-item-text">Tags</span></a>
                @endcan
                @can('tags.view')
                    <a href="{{ route('system-options.index') }}" title="Configurações do Escritório"
                        class="sidebar-item {{ request()->routeIs('system-options.*') ? 'active' : '' }}"><span
                            class="sidebar-item-icon"></span><span class="sidebar-item-text">Config. do Escritório</span></a>
                @endcan
                @can('drive_settings.view')
                    <a href="{{ route('drive_settings.index') }}" title="Google Drive"
                        class="sidebar-item {{ request()->routeIs('drive_settings.*') ? 'active' : '' }}"><span
                            class="sidebar-item-icon"></span><span class="sidebar-item-text">Google Drive</span></a>
                @endcan
                <a href="#" title="Pagamentos" class="sidebar-item"><span class="sidebar-item-icon"></span><span
                        class="sidebar-item-text">Categ. Tarefas</span></a>
                <a href="#" title="Fornecedores" class="sidebar-item"><span class="sidebar-item-icon"></span><span
                        class="sidebar-item-text">Categ. Financeiro</span></a>
                <a href="#" title="Carteiras" class="sidebar-item"><span class="sidebar-item-icon"></span><span
                        class="sidebar-item-text">Integrações</span></a>
            </div>
        </div>

    </nav>

    {{-- Suporte e Configurações Padrão --}}
    <div class="sidebar-bottom">

        {{-- Suporte --}}
        <a href="#" title="Suporte" class="sidebar-item">
            <span class="sidebar-item-icon"><i class="bi bi-question-circle-fill"></i></span>
            <span class="sidebar-item-text">Suporte</span>
        </a>

        {{-- Configurações - Somente SuperAdmin JurisControl --}}
        <div>
            <a href="#" title="Configurações" class="sidebar-item" data-submenu="config-sub">
                <span class="sidebar-item-icon"><i class="bi bi-gear-fill"></i></span>
                <span class="sidebar-item-text">Configurações</span>
                <i class="bi bi-chevron-down sidebar-item-arrow"></i>
            </a>
            <div class="sidebar-submenu" id="config-sub">
                <a href="#" title="Categorias do Sistema" class="sidebar-item"><span
                        class="sidebar-item-icon"></span><span class="sidebar-item-text">Categ. do Sistema</span></a>
                <a href="#" title="Configurações Padrão" class="sidebar-item"><span
                        class="sidebar-item-icon"></span><span class="sidebar-item-text">Config. Padrão</span></a>
                <a href="#" title="" class="sidebar-item"><span class="sidebar-item-icon"></span><span
                        class="sidebar-item-text">Fornecedores</span></a>
                <a href="#" title="" class="sidebar-item"><span class="sidebar-item-icon"></span><span
                        class="sidebar-item-text">Carteiras</span></a>
            </div>
        </div>

        {{-- Sair do sistema --}}
        <form class="sidebar-item" title="Sair do sistema" action="{{ route('logout') }}" method="POST" class="m-0">
            @csrf
            <button type="submit" class="dropdown-item d-flex align-items-center gap-2 text-danger">
                <i class="bi bi-box-arrow-right"></i> Sair
            </button>
        </form>

        {{-- Dados do usuário logado --}}
        <div class="sidebar-user">
            <div class="sidebar-user-avatar">
                <img src="https://i.pravatar.cc/100?img=47" alt="Emilia Greene">
            </div>
            <div class="sidebar-user-info">
                <div class="sidebar-user-name">{{ auth()->user()->name }}</div>
                <div class="sidebar-user-email">{{ auth()->user()->email }}</div>
            </div>
        </div>
    </div>
</aside>