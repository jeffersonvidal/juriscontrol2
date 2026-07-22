{{--
App Layout - JurisControl
Layout inspirado no design Apple Dashboard.
Regras do playbook:
- Bootstrap 5.3 + Lucide + SweetAlert2
- Dark/Light Mode com persistência (sem piscar)
- Sidebar com colapso (desktop) e FAB (mobile)
- Header com saudação, notificações e perfil
--}}
<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — JurisControl</title>

    {{-- Dark/Light sem piscar (aplica ANTES do CSS) --}}
    <script>
        (function () {
            const t = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', t);
        })();
    </script>

    {{-- Google Fonts: Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;450;500;600;700&display=swap"
        rel="stylesheet">

    {{-- Bootstrap 5.3 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="icon" type="image/svg+xml" href="{{ asset('LogoJurisControl.svg') }}">
    <!-- Vite -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>

    <div class="app-layout">

        {{-- ============================================================
        SIDEBAR
        ============================================================ --}}
        <aside class="app-sidebar" id="appSidebar">

            {{-- Logo + Botão Toggle (desktop) --}}
            <div class="sidebar-brand">
                <div class="sidebar-brand-icon">
                    <i data-lucide="scale" class="icon-sm"></i>
                </div>
                <div>
                    <div class="sidebar-brand-text">JurisControl</div>
                    <div class="sidebar-brand-sub">ERP Jurídico</div>
                </div>
                {{-- Seta toggle: só visível em desktop --}}
                <button type="button" class="sidebar-collapse-toggle" id="sidebarCollapseToggle"
                    title="Ocultar/Exibir menu" aria-label="Ocultar/Exibir menu">
                    <i data-lucide="chevron-left" class="icon-xs"></i>
                </button>
            </div>

            {{-- Navegação (com scroll vertical automático) --}}
            <nav class="sidebar-nav" id="sidebarNav">
                <ul class="nav flex-column">

                    {{-- Dashboard --}}
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}"
                            class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"
                            data-tooltip="Dashboard">
                            <i data-lucide="layout-dashboard" class="icon-sm"></i>
                            <span class="nav-text">Dashboard</span>
                        </a>
                    </li>

                    {{-- Processos (com submenu) --}}
                    <li class="nav-item mt-1">
                        <a href="#" class="nav-link" data-bs-toggle="collapse" data-bs-target="#menuProcessos"
                            aria-expanded="false" data-tooltip="Processos">
                            <i data-lucide="briefcase" class="icon-sm"></i>
                            <span class="nav-text">Processos</span>
                            <i data-lucide="chevron-down" class="icon-xs nav-chevron"></i>
                        </a>
                        <div class="collapse" id="menuProcessos">
                            <ul class="nav flex-column sidebar-submenu">
                                <li><a href="#" class="nav-link">Todos os Processos</a></li>
                                <li><a href="#" class="nav-link">Processos Ativos</a></li>
                                <li><a href="#" class="nav-link">Processos Arquivados</a></li>
                            </ul>
                        </div>
                    </li>

                    {{-- Prazos --}}
                    <li class="nav-item">
                        <a href="#" class="nav-link" data-tooltip="Prazos">
                            <i data-lucide="calendar-clock" class="icon-sm"></i>
                            <span class="nav-text">Prazos</span>
                        </a>
                    </li>

                    {{-- Clientes (com submenu) --}}
                    <li class="nav-item">
                        <a href="#" class="nav-link" data-bs-toggle="collapse" data-bs-target="#menuClientes"
                            aria-expanded="false" data-tooltip="Clientes">
                            <i data-lucide="users" class="icon-sm"></i>
                            <span class="nav-text">Clientes</span>
                            <i data-lucide="chevron-down" class="icon-xs nav-chevron"></i>
                        </a>
                        <div class="collapse" id="menuClientes">
                            <ul class="nav flex-column sidebar-submenu">
                                <li><a href="#" class="nav-link">Lista de Clientes</a></li>
                                <li><a href="#" class="nav-link">Novo Cliente</a></li>
                                <li><a href="#" class="nav-link">Segmentos</a></li>
                            </ul>
                        </div>
                    </li>

                    {{-- Audiências --}}
                    <li class="nav-item">
                        <a href="#" class="nav-link" data-tooltip="Audiências">
                            <i data-lucide="gavel" class="icon-sm"></i>
                            <span class="nav-text">Audiências</span>
                        </a>
                    </li>

                    {{-- Tarefas (com submenu) --}}
                    <li class="nav-item">
                        <a href="#" class="nav-link" data-bs-toggle="collapse" data-bs-target="#menuTarefas"
                            aria-expanded="false" data-tooltip="Tarefas">
                            <i data-lucide="check-square" class="icon-sm"></i>
                            <span class="nav-text">Tarefas</span>
                            <i data-lucide="chevron-down" class="icon-xs nav-chevron"></i>
                        </a>
                        <div class="collapse" id="menuTarefas">
                            <ul class="nav flex-column sidebar-submenu">
                                <li><a href="#" class="nav-link">Minhas Tarefas</a></li>
                                <li><a href="#" class="nav-link">Tarefas da Equipe</a></li>
                                <li><a href="#" class="nav-link">Concluídas</a></li>
                            </ul>
                        </div>
                    </li>

                    {{-- Documentos --}}
                    <li class="nav-item">
                        <a href="#" class="nav-link" data-tooltip="Documentos">
                            <i data-lucide="file-text" class="icon-sm"></i>
                            <span class="nav-text">Documentos</span>
                        </a>
                    </li>

                    {{-- Financeiro (com submenu) --}}
                    <li class="nav-item mt-1">
                        <a href="#" class="nav-link" data-bs-toggle="collapse" data-bs-target="#menuFinanceiro"
                            aria-expanded="false" data-tooltip="Financeiro">
                            <i data-lucide="wallet" class="icon-sm"></i>
                            <span class="nav-text">Financeiro</span>
                            <i data-lucide="chevron-down" class="icon-xs nav-chevron"></i>
                        </a>
                        <div class="collapse" id="menuFinanceiro">
                            <ul class="nav flex-column sidebar-submenu">
                                <li><a href="#" class="nav-link">Recebimentos</a></li>
                                <li><a href="#" class="nav-link">Despesas</a></li>
                                <li><a href="#" class="nav-link">Fluxo de Caixa</a></li>
                            </ul>
                        </div>
                    </li>

                    {{-- Relatórios --}}
                    <li class="nav-item">
                        <a href="#" class="nav-link" data-tooltip="Relatórios">
                            <i data-lucide="bar-chart-3" class="icon-sm"></i>
                            <span class="nav-text">Relatórios</span>
                        </a>
                    </li>

                    {{-- Comunicações --}}
                    <li class="nav-item">
                        <a href="#" class="nav-link" data-tooltip="Comunicações">
                            <i data-lucide="message-circle" class="icon-sm"></i>
                            <span class="nav-text">Comunicações</span>
                        </a>
                    </li>

                    {{-- Agenda --}}
                    <li class="nav-item">
                        <a href="#" class="nav-link" data-tooltip="Agenda">
                            <i data-lucide="calendar" class="icon-sm"></i>
                            <span class="nav-text">Agenda</span>
                        </a>
                    </li>

                    {{-- Seção: Sistema --}}
                    <li class="nav-item mt-2">
                        <p class="sidebar-section-title">Sistema</p>
                    </li>

                    {{-- Configurações (com submenu) --}}
                    <li class="nav-item">
                        <a href="#" class="nav-link" data-bs-toggle="collapse" data-bs-target="#menuConfig"
                            aria-expanded="false" data-tooltip="Configurações">
                            <i data-lucide="settings" class="icon-sm"></i>
                            <span class="nav-text">Configurações</span>
                            <i data-lucide="chevron-down" class="icon-xs nav-chevron"></i>
                        </a>
                        <div class="collapse" id="menuConfig">
                            <ul class="nav flex-column sidebar-submenu">
                                <li><a href="#" class="nav-link">Geral</a></li>
                                <li><a href="#" class="nav-link">Usuários</a></li>
                                <li><a href="#" class="nav-link">Permissões</a></li>
                                @can('companies.view')
                                    <li>
                                        <a href="{{ route('companies.index') }}"
                                            class="nav-link {{ request()->routeIs('companies.*') ? 'active' : '' }}">
                                            Empresas
                                        </a>
                                    </li>
                                @endcan
                                <li><a href="#" class="nav-link">Integrações</a></li>
                            </ul>
                        </div>
                    </li>

                    {{-- Auditoria --}}
                    @can('audit.logs.view')
                        <li class="nav-item">
                            <a href="#" class="nav-link" data-tooltip="Auditoria">
                                <i data-lucide="history" class="icon-sm"></i>
                                <span class="nav-text">Auditoria</span>
                            </a>
                        </li>
                    @endcan
                </ul>
            </nav>

            {{-- PERFIL REMOVIDO conforme solicitado --}}
        </aside>

        {{-- Overlay mobile (fecha sidebar ao clicar fora) --}}
        <div class="sidebar-overlay" id="sidebarOverlay"></div>

        {{-- ============================================================
        MAIN CONTENT
        ============================================================ --}}
        <div class="app-main">

            {{-- Header --}}
            <header class="app-header">
                {{-- Saudação --}}
                <div class="app-header-greeting">
                    <h1>@yield('greeting', 'Bom dia, ' . explode(' ', auth()->user()->name)[0] . '.')</h1>
                    <p>@yield('greeting-sub', 'Aqui está o resumo do seu dia.')</p>
                </div>

                {{-- Ações do header --}}
                <div class="app-header-actions">
                    {{-- Toggle tema --}}
                    <button type="button" class="btn-theme-toggle" id="themeToggle" title="Alternar tema"
                        aria-label="Alternar tema">
                        <i data-lucide="moon" class="icon-sm" id="themeIcon"></i>
                    </button>

                    {{-- Notificações --}}
                    <button type="button" class="btn-notification" id="btnNotifications" title="Notificações"
                        aria-label="Notificações">
                        <i data-lucide="bell" class="icon-sm"></i>
                        <span class="notification-badge">3</span>
                    </button>

                    {{-- Perfil dropdown --}}
                    <div class="dropdown">
                        <button class="header-profile-btn" type="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(auth()->user()->name) }}&background=4f46e5&color=fff&size=56&font-size=0.4"
                                alt="{{ auth()->user()->name }}" class="header-profile-avatar">
                            <span class="d-none d-md-inline">{{ explode(' ', auth()->user()->name)[0] }}</span>
                            <i data-lucide="chevron-down" class="icon-xs"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm" style="min-width: 200px;">
                            <li>
                                <div class="dropdown-item-text py-2">
                                    <div class="fw-semibold" style="font-size: 0.875rem;">{{ auth()->user()->name }}
                                    </div>
                                    <div class="text-muted-custom" style="font-size: 0.75rem;">
                                        {{ auth()->user()->email }}
                                    </div>
                                </div>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li><a class="dropdown-item d-flex align-items-center gap-2" href="#"><i data-lucide="user"
                                        class="icon-xs"></i> Meu Perfil</a></li>
                            <li><a class="dropdown-item d-flex align-items-center gap-2" href="#"><i
                                        data-lucide="settings" class="icon-xs"></i> Configurações</a></li>
                            <li><a class="dropdown-item d-flex align-items-center gap-2" href="#"><i
                                        data-lucide="shield" class="icon-xs"></i> Privacidade</a></li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit"
                                        class="dropdown-item d-flex align-items-center gap-2 text-danger">
                                        <i data-lucide="log-out" class="icon-xs"></i> Sair
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            {{-- Conteúdo da página --}}
            <main class="app-content">
                @yield('content')
            </main>
        </div>
    </div>

    {{-- ============================================================
    BOTÃO TOGGLE MOBILE (FAB - Floating Action Button)
    Igual à imagem 3 em anexo (botão azul flutuante)
    ============================================================ --}}
    <button type="button" class="sidebar-mobile-toggle" id="sidebarMobileToggle" title="Abrir menu"
        aria-label="Abrir menu">
        <i data-lucide="menu" class="icon-md"></i>
    </button>

    {{-- ============================================================
    PAINEL DE NOTIFICAÇÕES (Slide-in lateral)
    ============================================================ --}}
    <div class="overlay" id="overlayNotifications"></div>
    <div class="notification-panel" id="notificationPanel">
        <div class="notification-panel-header">
            <h6 class="fw-semibold mb-0">Notificações</h6>
            <button type="button" class="btn btn-sm btn-link text-muted p-0" id="btnCloseNotifications">
                <i data-lucide="x" class="icon-sm"></i>
            </button>
        </div>
        <div class="notification-panel-body">
            <div class="notification-item d-flex gap-3">
                <div class="notification-dot mt-1" style="background-color: var(--jc-info);"></div>
                <div>
                    <div class="fw-medium" style="font-size: 0.8125rem;">Audiência confirmada</div>
                    <div class="text-muted-custom" style="font-size: 0.75rem;">Audiência Trabalhista às 09:00</div>
                    <div class="text-light-custom" style="font-size: 0.6875rem; margin-top: 2px;">Há 5 minutos</div>
                </div>
            </div>
            <div class="notification-item d-flex gap-3">
                <div class="notification-dot mt-1" style="background-color: var(--jc-danger);"></div>
                <div>
                    <div class="fw-medium" style="font-size: 0.8125rem;">Prazo crítico</div>
                    <div class="text-muted-custom" style="font-size: 0.75rem;">Processo vence em 2 dias</div>
                    <div class="text-light-custom" style="font-size: 0.6875rem; margin-top: 2px;">Há 1 hora</div>
                </div>
            </div>
            <div class="notification-item d-flex gap-3">
                <div class="notification-dot mt-1" style="background-color: var(--jc-success);"></div>
                <div>
                    <div class="fw-medium" style="font-size: 0.8125rem;">Documento aprovado</div>
                    <div class="text-muted-custom" style="font-size: 0.75rem;">Petição protocolada</div>
                    <div class="text-light-custom" style="font-size: 0.6875rem; margin-top: 2px;">Há 3 horas</div>
                </div>
            </div>
            <div class="notification-item d-flex gap-3">
                <div class="notification-dot mt-1" style="background-color: var(--jc-warning);"></div>
                <div>
                    <div class="fw-medium" style="font-size: 0.8125rem;">Novo documento</div>
                    <div class="text-muted-custom" style="font-size: 0.75rem;">Contrato social adicionado</div>
                    <div class="text-light-custom" style="font-size: 0.6875rem; margin-top: 2px;">Ontem</div>
                </div>
            </div>
        </div>
        <div class="p-3 border-top border-jc text-center">
            <a href="#" class="text-primary" style="font-size: 0.8125rem; font-weight: 500;">Ver todas</a>
        </div>
    </div>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Inicializa ícones Lucide
            if (typeof lucide !== 'undefined') lucide.createIcons();

            // ============================================================
            // SIDEBAR: COLAPSO (DESKTOP) + MOBILE TOGGLE
            // ============================================================
            const sidebar = document.getElementById('appSidebar');
            const collapseToggle = document.getElementById('sidebarCollapseToggle');
            const mobileToggle = document.getElementById('sidebarMobileToggle');
            const overlay = document.getElementById('sidebarOverlay');

            // Chave de persistência no localStorage
            const STORAGE_KEY = 'jc_sidebar_collapsed';

            /**
             * Aplica o estado colapsado/expandido no desktop.
             * No mobile, esse método não tem efeito (usa mobile-open).
             */
            function applyDesktopCollapse(collapsed) {
                if (window.innerWidth < 992) return; // só aplica em desktop

                if (collapsed) {
                    sidebar.classList.add('collapsed');
                    document.body.classList.add('sidebar-collapsed');
                } else {
                    sidebar.classList.remove('collapsed');
                    document.body.classList.remove('sidebar-collapsed');
                }

                // Persiste a preferência
                try {
                    localStorage.setItem(STORAGE_KEY, collapsed ? '1' : '0');
                } catch (e) { }

                // Re-renderiza ícones Lucide (pois tooltips mudaram)
                if (typeof lucide !== 'undefined') lucide.createIcons();
            }

            /**
             * Abre a sidebar no mobile.
             */
            function openMobileSidebar() {
                sidebar.classList.add('mobile-open');
                overlay.classList.add('active');
                document.body.style.overflow = 'hidden'; // evita scroll do body
            }

            /**
             * Fecha a sidebar no mobile.
             */
            function closeMobileSidebar() {
                sidebar.classList.remove('mobile-open');
                overlay.classList.remove('active');
                document.body.style.overflow = '';
            }

            // ----------------------------------------------------------
            // Toggle desktop (seta ao lado do logo)
            // ----------------------------------------------------------
            collapseToggle?.addEventListener('click', function (e) {
                e.stopPropagation();
                const isCollapsed = sidebar.classList.contains('collapsed');
                applyDesktopCollapse(!isCollapsed);
            });

            // ----------------------------------------------------------
            // Toggle mobile (FAB azul)
            // ----------------------------------------------------------
            mobileToggle?.addEventListener('click', function () {
                if (sidebar.classList.contains('mobile-open')) {
                    closeMobileSidebar();
                } else {
                    openMobileSidebar();
                }
            });

            // Fecha sidebar mobile ao clicar no overlay
            overlay?.addEventListener('click', closeMobileSidebar);

            // ----------------------------------------------------------
            // Comportamento ACCORDION: fecha outros submenus ao abrir um
            // ----------------------------------------------------------
            const submenuToggles = sidebar.querySelectorAll('[data-bs-toggle="collapse"]');
            submenuToggles.forEach(toggle => {
                toggle.addEventListener('click', function (e) {
                    const targetId = this.getAttribute('data-bs-target');
                    const targetEl = document.querySelector(targetId);
                    const isCurrentlyOpen = targetEl?.classList.contains('show');

                    // Se a sidebar está colapsada no desktop, expande primeiro
                    if (sidebar.classList.contains('collapsed') && window.innerWidth >= 992) {
                        e.preventDefault();
                        applyDesktopCollapse(false);

                        // Aguarda a animação de expansão e depois abre o submenu
                        setTimeout(() => {
                            // Fecha TODOS os outros submenus primeiro
                            submenuToggles.forEach(otherToggle => {
                                if (otherToggle !== this) {
                                    const otherTargetId = otherToggle.getAttribute('data-bs-target');
                                    const otherTarget = document.querySelector(otherTargetId);
                                    if (otherTarget && otherTarget.classList.contains('show')) {
                                        const bsCollapse = new bootstrap.Collapse(otherTarget, { toggle: false });
                                        bsCollapse.hide();
                                    }
                                }
                            });

                            // Abre o submenu clicado
                            if (targetEl && !isCurrentlyOpen) {
                                const bsCollapse = new bootstrap.Collapse(targetEl, { toggle: true });
                            }
                        }, 280);
                    } else {
                        // Comportamento normal: fecha outros submenus
                        e.preventDefault();

                        submenuToggles.forEach(otherToggle => {
                            if (otherToggle !== this) {
                                const otherTargetId = otherToggle.getAttribute('data-bs-target');
                                const otherTarget = document.querySelector(otherTargetId);
                                if (otherTarget && otherTarget.classList.contains('show')) {
                                    const bsCollapse = new bootstrap.Collapse(otherTarget, { toggle: false });
                                    bsCollapse.hide();
                                }
                            }
                        });

                        // Toggle do submenu clicado
                        if (targetEl) {
                            const bsCollapse = new bootstrap.Collapse(targetEl, { toggle: true });
                        }
                    }
                });
            });

            // ----------------------------------------------------------
            // Restaura estado da sidebar no carregamento
            // ----------------------------------------------------------
            try {
                const saved = localStorage.getItem(STORAGE_KEY);
                if (saved === '1') {
                    applyDesktopCollapse(true);
                }
            } catch (e) { }

            // ----------------------------------------------------------
            // Ajusta em resize de tela
            // ----------------------------------------------------------
            let resizeTimer;
            window.addEventListener('resize', function () {
                clearTimeout(resizeTimer);
                resizeTimer = setTimeout(function () {
                    if (window.innerWidth >= 992) {
                        // Voltando para desktop: fecha modo mobile se estiver aberto
                        closeMobileSidebar();
                    } else {
                        // Indo para mobile: remove estado collapsed do desktop
                        sidebar.classList.remove('collapsed');
                        document.body.classList.remove('sidebar-collapsed');
                    }
                }, 150);
            });

            // ============================================================
            // PAINEL DE NOTIFICAÇÕES (slide-in)
            // ============================================================
            const notifPanel = document.getElementById('notificationPanel');
            const notifOverlay = document.getElementById('overlayNotifications');
            const btnNotifOpen = document.getElementById('btnNotifications');
            const btnNotifClose = document.getElementById('btnCloseNotifications');

            function openNotifPanel() {
                notifPanel.classList.add('open');
                notifOverlay.classList.add('active');
            }

            function closeNotifPanel() {
                notifPanel.classList.remove('open');
                notifOverlay.classList.remove('active');
            }

            btnNotifOpen?.addEventListener('click', openNotifPanel);
            btnNotifClose?.addEventListener('click', closeNotifPanel);
            notifOverlay?.addEventListener('click', closeNotifPanel);
        });
    </script>

    @stack('scripts')
</body>

</html>