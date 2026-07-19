{{-- 
    App Layout
    --------------------------------------------------------
    Layout para páginas autenticadas (após login).
    Regras do playbook:
     - Sidebar com navegação
     - Dark/Light Mode com persistência
     - Header com info do usuário e tenant
     - Bootstrap 5.3 + Lucide + SweetAlert2
--}}
<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — JurisControl</title>

    {{-- Dark/Light sem piscar --}}
    <script>
        (function() {
            const savedTheme = localStorage.getItem('theme') || 'light';
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
        })();
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body>

    <div class="d-flex min-vh-100">

        {{-- ============================================================
            SIDEBAR
        ============================================================ --}}
        <aside class="sidebar bg-surface border-end border-jc d-flex flex-column"
               style="width: 260px; min-height: 100vh;">

            {{-- Logo --}}
            <div class="sidebar-brand p-3 border-bottom border-jc d-flex align-items-center gap-2">
                <div class="login-logo" style="width: 36px; height: 36px; border-radius: 10px; margin: 0;">
                    <i data-lucide="scale" class="icon-sm"></i>
                </div>
                <div>
                    <div class="fw-semibold" style="font-size: 0.95rem;">JurisControl</div>
                    <div class="text-muted-custom" style="font-size: 0.75rem;">ERP Jurídico</div>
                </div>
            </div>

            {{-- Navegação --}}
            <nav class="sidebar-nav flex-grow-1 p-3">
                <ul class="nav flex-column gap-1">
                    <li class="nav-item">
                        <a href="{{ route('dashboard') }}"
                           class="nav-link d-flex align-items-center gap-2 rounded-2 px-3 py-2 {{ request()->routeIs('dashboard') ? 'active bg-primary-subtle text-primary' : 'text-body' }}">
                            <i data-lucide="layout-dashboard" class="icon-sm"></i>
                            <span>Dashboard</span>
                        </a>
                    </li>

                    {{-- Futuros módulos serão adicionados aqui --}}
                    <li class="nav-item mt-3">
                        <small class="text-muted-custom text-uppercase fw-semibold px-3" style="font-size: 0.7rem; letter-spacing: 0.05em;">
                            Sistema
                        </small>
                    </li>

                    @can('audit.logs.view')
                    <li class="nav-item">
                        <a href="#"
                           class="nav-link d-flex align-items-center gap-2 rounded-2 px-3 py-2 text-body">
                            <i data-lucide="history" class="icon-sm"></i>
                            <span>Auditoria</span>
                        </a>
                    </li>
                    @endcan
                </ul>
            </nav>

            {{-- Rodapé da sidebar (info do tenant) --}}
            <div class="sidebar-footer p-3 border-top border-jc">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center"
                         style="width: 32px; height: 32px;">
                        <i data-lucide="building-2" class="icon-xs text-primary"></i>
                    </div>
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="text-truncate fw-medium" style="font-size: 0.85rem;">
                            {{ auth()->user()->company?->trade_name ?? auth()->user()->company?->name ?? 'Super Admin' }}
                        </div>
                        <div class="text-muted-custom text-truncate" style="font-size: 0.7rem;">
                            {{ auth()->user()->company?->document ?? 'Cross-tenant' }}
                        </div>
                    </div>
                </div>
            </div>
        </aside>

        {{-- ============================================================
            CONTEÚDO PRINCIPAL
        ============================================================ --}}
        <div class="flex-grow-1 d-flex flex-column">

            {{-- Header --}}
            <header class="bg-surface border-bottom border-jc px-4 py-3 d-flex align-items-center justify-content-between">
                <div>
                    <h1 class="h5 mb-0 fw-semibold">@yield('page-title', 'Dashboard')</h1>
                </div>

                <div class="d-flex align-items-center gap-3">
                    {{-- Toggle tema --}}
                    <button type="button"
                            class="btn btn-sm btn-outline-secondary rounded-circle"
                            id="themeToggle"
                            title="Alternar tema"
                            aria-label="Alternar tema claro/escuro"
                            style="width: 36px; height: 36px;">
                        <i data-lucide="moon" class="icon-sm" id="themeIcon"></i>
                    </button>

                    {{-- Dropdown do usuário --}}
                    <div class="dropdown">
                        <button class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-2"
                                type="button"
                                data-bs-toggle="dropdown"
                                aria-expanded="false">
                            <div class="rounded-circle bg-primary-subtle d-flex align-items-center justify-content-center"
                                 style="width: 28px; height: 28px;">
                                <span class="fw-semibold text-primary" style="font-size: 0.75rem;">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </span>
                            </div>
                            <span class="d-none d-md-inline">{{ auth()->user()->name }}</span>
                            <i data-lucide="chevron-down" class="icon-xs"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <li>
                                <div class="dropdown-item-text">
                                    <div class="fw-medium" style="font-size: 0.875rem;">{{ auth()->user()->name }}</div>
                                    <div class="text-muted-custom" style="font-size: 0.75rem;">{{ auth()->user()->email }}</div>
                                </div>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="m-0">
                                    @csrf
                                    <button type="submit" class="dropdown-item d-flex align-items-center gap-2 text-danger">
                                        <i data-lucide="log-out" class="icon-xs"></i>
                                        Sair
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>

            {{-- Conteúdo da página --}}
            <main class="flex-grow-1 p-4" style="background-color: var(--jc-bg);">
                @yield('content')
            </main>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') lucide.createIcons();
        });
    </script>
    @stack('scripts')
</body>
</html>