{{-- 
    Guest Layout
    --------------------------------------------------------
    Layout para páginas públicas (login, register, forgot-password).
    Regras do playbook:
     - Bootstrap 5.3
     - Dark/Light Mode com persistência (sem piscar)
     - Lucide Icons
     - Cores flat e claras
     - Layout elegante e minimalista
--}}
<!DOCTYPE html>
<html lang="pt-BR" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'JurisControl') — ERP Jurídico</title>

    {{-- ============================================================
        DARK/LIGHT MODE: aplica o tema ANTES do render (sem piscar)
        Regra do playbook: "Dark/Light Mode (toggle com persistência, sem piscar tela)"
    ============================================================ --}}
    <script>
        (function() {
            // Busca o tema salvo no localStorage (ou usa 'light' como padrão)
            const savedTheme = localStorage.getItem('theme') || 'light';
            // Aplica imediatamente no <html> (antes do CSS carregar)
            document.documentElement.setAttribute('data-bs-theme', savedTheme);
        })();
    </script>

    {{-- Google Fonts: Inter (elegante, moderna, fina) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    {{-- Bootstrap 5.3 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- CSS customizado do sistema --}}
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body class="bg-light-subtle">

    {{-- Container principal do guest (centralizado) --}}
    <div class="min-vh-100 d-flex flex-column justify-content-center align-items-center py-4">

        {{-- Botão de toggle dark/light no canto superior --}}
        <div class="position-fixed top-0 end-0 p-3" style="z-index: 1050;">
            <button type="button"
                    class="btn btn-sm btn-outline-secondary rounded-circle"
                    id="themeToggle"
                    title="Alternar tema"
                    aria-label="Alternar tema claro/escuro">
                {{-- Ícone será trocado via JS --}}
                <i data-lucide="moon" class="icon-sm" id="themeIcon"></i>
            </button>
        </div>

        {{-- Conteúdo da página --}}
        <div class="w-100" style="max-width: 420px;">
            @yield('content')
        </div>

        {{-- Rodapé --}}
        <footer class="text-center text-muted small mt-4">
            <p class="mb-0">
                <i data-lucide="scale" class="icon-xs me-1"></i>
                JurisControl &copy; {{ date('Y') }} — ERP Jurídico
            </p>
        </footer>
    </div>

        {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.min.js"></script>

    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

    {{-- Axios (ADICIONADO: necessário para requisições AJAX) --}}
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>

    {{-- JS customizado do sistema --}}
    <script src="{{ asset('js/app.js') }}"></script>

    {{-- Inicializa ícones Lucide --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof lucide !== 'undefined') {
                lucide.createIcons();
            }
        });
    </script>

    {{-- Stack para scripts adicionais das páginas (onde está o script do login) --}}
    @stack('scripts')
</body>
</html>