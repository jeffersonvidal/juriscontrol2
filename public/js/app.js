/**
 * JurisControl - JS Global
 * --------------------------------------------------------
 * Regras do playbook:
 *  - Dark/Light Mode com persistência (sem piscar)
 *  - SweetAlert2 para todas as mensagens
 *  - CSRF token em TODO request AJAX
 *  - Lucide Icons
 */

// ============================================================
// 1. CONFIGURAÇÃO GLOBAL DO AXIOS (CSRF + JSON)
// ============================================================
if (typeof axios !== 'undefined') {
    // Injeta CSRF token em todas as requisições AJAX
    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]')?.content;
    axios.defaults.headers.common['Accept'] = 'application/json';
    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
}

// ============================================================
// 2. DARK / LIGHT MODE TOGGLE
// ============================================================
/**
 * Aplica o tema no <html> e atualiza o ícone do botão.
 * @param {string} theme - 'light' ou 'dark'
 */
function applyTheme(theme) {
    // Aplica no HTML (Bootstrap 5.3 lê data-bs-theme)
    document.documentElement.setAttribute('data-bs-theme', theme);

    // Persiste no localStorage
    localStorage.setItem('theme', theme);

    // Atualiza o ícone do botão de toggle
    const icon = document.getElementById('themeIcon');
    if (icon) {
        // Troca o atributo data-lucide e re-renderiza
        icon.setAttribute('data-lucide', theme === 'dark' ? 'sun' : 'moon');
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }
}

/**
 * Inicializa o toggle de tema.
 * O tema já foi aplicado no <head> (sem piscar),
 * aqui apenas registramos o evento de clique.
 */
function initThemeToggle() {
    const toggleBtn = document.getElementById('themeToggle');
    if (!toggleBtn) return;

    // Aplica o ícone correto baseado no tema atual
    const currentTheme = document.documentElement.getAttribute('data-bs-theme') || 'light';
    const icon = document.getElementById('themeIcon');
    if (icon) {
        icon.setAttribute('data-lucide', currentTheme === 'dark' ? 'sun' : 'moon');
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }
    }

    // Evento de clique: alterna entre light/dark
    toggleBtn.addEventListener('click', function() {
        const current = document.documentElement.getAttribute('data-bs-theme');
        const next = current === 'dark' ? 'light' : 'dark';
        applyTheme(next);
    });
}

// ============================================================
// 3. SWEETALERT2 - HELPERS
// ============================================================
/**
 * Exibe mensagem de sucesso.
 */
function toastSuccess(message) {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
    });
    Toast.fire({
        icon: 'success',
        title: message,
    });
}

/**
 * Exibe mensagem de erro.
 */
function toastError(message) {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
    });
    Toast.fire({
        icon: 'error',
        title: message,
    });
}

/**
 * Confirmação de exclusão.
 * @returns {Promise<boolean>}
 */
function confirmDelete(title = 'Confirmar exclusão', text = 'Esta ação não pode ser desfeita.') {
    return Swal.fire({
        title: title,
        text: text,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#ef4444',
        cancelButtonColor: '#64748b',
        confirmButtonText: 'Sim, excluir',
        cancelButtonText: 'Cancelar',
    }).then((result) => result.isConfirmed);
}

// ============================================================
// 4. INICIALIZAÇÃO
// ============================================================
document.addEventListener('DOMContentLoaded', function() {
    // Inicializa toggle de tema
    initThemeToggle();

    // Inicializa ícones Lucide (caso não tenham sido inicializados no layout)
    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
});