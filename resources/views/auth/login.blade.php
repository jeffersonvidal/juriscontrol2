@extends('layouts.guest')

@section('title', 'Login')

@section('content')
    <div class="login-card">

        {{-- Logo + Título --}}
        <div class="text-center mb-4">
            <div class="login-logo">
                <i data-lucide="scale" class="icon-md"></i>
            </div>
            <h2 class="h4 fw-semibold mb-1">Bem-vindo de volta</h2>
            <p class="text-muted-custom mb-0" style="font-size: 0.9rem;">
                Acesse sua conta no JurisControl
            </p>
        </div>

        {{-- Formulário de login --}}
        <form id="loginForm" novalidate>
            @csrf

            {{-- E-mail --}}
            <div class="mb-3">
                <label for="email" class="form-label-jc">
                    <i data-lucide="mail" class="icon-xs me-1"></i>
                    E-mail
                </label>
                <div class="position-relative">
                    <input type="email"
                           id="email"
                           name="email"
                           class="form-control form-control-jc"
                           placeholder="seu@email.com"
                           value="admin@escritorio.com{{ old('email') }}"
                           autocomplete="email"
                           autofocus
                           required>
                </div>
                <div class="invalid-feedback" id="email-error"></div>
            </div>

            {{-- Senha --}}
            <div class="mb-4">
                <label for="password" class="form-label-jc">
                    <i data-lucide="lock" class="icon-xs me-1"></i>
                    Senha
                </label>
                <div class="position-relative">
                    <input type="password"
                           id="password"
                           name="password"
                           value="ChangeMe@123"
                           class="form-control form-control-jc"
                           placeholder="••••••••"
                           autocomplete="current-password"
                           required>
                    <button type="button"
                            class="btn btn-sm position-absolute top-50 end-0 translate-middle-y me-2 text-muted"
                            id="togglePassword"
                            tabindex="-1"
                            aria-label="Mostrar/ocultar senha"
                            style="z-index: 5;">
                        <i data-lucide="eye" class="icon-xs" id="togglePasswordIcon"></i>
                    </button>
                </div>
                <div class="invalid-feedback" id="password-error"></div>
            </div>

            {{-- Botão de submit --}}
            <button type="submit"
                    id="btnLogin"
                    class="btn btn-jc-primary w-100 d-flex align-items-center justify-content-center gap-2">
                <span id="btnLoginText">Entrar</span>
                <span id="btnLoginLoader" class="d-none">
                    <span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>
                    Entrando...
                </span>
            </button>
        </form>

        {{-- Rodapé do card --}}
        <div class="text-center mt-4">
            <small class="text-muted-custom">
                <i data-lucide="shield-check" class="icon-xs me-1"></i>
                Conexão segura e criptografada
            </small>
        </div>
    </div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    // ============================================================
    // 1. TOGGLE DE SENHA (mostrar/ocultar)
    // ============================================================
    const togglePassword = document.getElementById('togglePassword');
    const passwordInput = document.getElementById('password');
    const toggleIcon = document.getElementById('togglePasswordIcon');

    togglePassword?.addEventListener('click', function() {
        const isPassword = passwordInput.type === 'password';
        passwordInput.type = isPassword ? 'text' : 'password';
        toggleIcon.setAttribute('data-lucide', isPassword ? 'eye-off' : 'eye');
        if (typeof lucide !== 'undefined') lucide.createIcons();
    });

    // ============================================================
    // 2. SUBMIT DO FORMULÁRIO VIA AJAX
    // ============================================================
    const form = document.getElementById('loginForm');
    const btnLogin = document.getElementById('btnLogin');
    const btnText = document.getElementById('btnLoginText');
    const btnLoader = document.getElementById('btnLoginLoader');

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Limpa erros anteriores
        clearErrors();

        // Desabilita botão e mostra loader
        setLoading(true);

        try {
            const response = await axios.post('{{ route("login.process") }}', {
                email: document.getElementById('email').value.trim(),
                password: document.getElementById('password').value,
            });

            // Sucesso: toast + redirect
            if (response.data.success) {
                toastSuccess(response.data.message);
                setTimeout(() => {
                    window.location.href = response.data.redirect;
                }, 800);
            }
                } catch (error) {
            // ============================================================
            // DEBUG: Revelar o erro real do servidor
            // ============================================================
            console.error('=== ERRO NO LOGIN ===', error);
            
            if (error.response) {
                // O servidor respondeu com um status de erro (4xx ou 5xx)
                console.error('Resposta do servidor:', error.response.data);
                
                const serverMessage = error.response.data.message || 'Erro interno do servidor';
                toastError(`Erro ${error.response.status}: ${serverMessage}`);
            } else if (error.request) {
                // A requisição foi feita, mas nenhuma resposta foi recebida
                console.error('Sem resposta do servidor:', error.request);
                toastError('Sem resposta do servidor. Verifique sua conexão.');
            } else {
                // Algo aconteceu ao configurar a requisição
                console.error('Erro na configuração da requisição:', error.message);
                toastError('Erro ao configurar requisição: ' + error.message);
            }
        } finally {
            setLoading(false);
        }
    });

    // ============================================================
    // 3. HELPERS
    // ============================================================
    function setLoading(loading) {
        btnLogin.disabled = loading;
        btnText.classList.toggle('d-none', loading);
        btnLoader.classList.toggle('d-none', !loading);
    }

    function clearErrors() {
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
    }

    function showValidationErrors(errors) {
        Object.keys(errors).forEach(field => {
            const input = document.getElementById(field);
            const feedback = document.getElementById(field + '-error');
            if (input) input.classList.add('is-invalid');
            if (feedback) feedback.textContent = errors[field][0];
        });
    }

    // ============================================================
    // 4. MENSAGENS FLASH (ex: logout bem-sucedido)
    // ============================================================
    @if(session('success'))
        toastSuccess('{{ session("success") }}');
    @endif

    @if(session('error'))
        toastError('{{ session("error") }}');
    @endif
});
</script>
@endpush