import './bootstrap';


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
// 1. CONFIGURAÇÃO GLOBAL DO AXIOS
// ============================================================
if (typeof axios !== 'undefined') {
    axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]')?.content;
    axios.defaults.headers.common['Accept'] = 'application/json';
    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
}

// ============================================================
// 2. DARK / LIGHT MODE TOGGLE
// ============================================================
function applyTheme(theme) {
    document.documentElement.setAttribute('data-bs-theme', theme);
    localStorage.setItem('theme', theme);

    const icon = document.getElementById('themeIcon');
    if (icon) {
        icon.setAttribute('data-lucide', theme === 'dark' ? 'sun' : 'moon');
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }
}

function initThemeToggle() {
    const toggleBtn = document.getElementById('themeToggle');
    if (!toggleBtn) return;

    const currentTheme = document.documentElement.getAttribute('data-bs-theme') || 'light';
    const icon = document.getElementById('themeIcon');
    if (icon) {
        icon.setAttribute('data-lucide', currentTheme === 'dark' ? 'sun' : 'moon');
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    toggleBtn.addEventListener('click', function() {
        const current = document.documentElement.getAttribute('data-bs-theme');
        const next = current === 'dark' ? 'light' : 'dark';
        applyTheme(next);
    });
}

// ============================================================
// 3. SWEETALERT2 - HELPERS
// ============================================================
function toastSuccess(message) {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
    });
    Toast.fire({ icon: 'success', title: message });
}

function toastError(message) {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 4000,
        timerProgressBar: true,
    });
    Toast.fire({ icon: 'error', title: message });
}

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
    initThemeToggle();

    if (typeof lucide !== 'undefined') {
        lucide.createIcons();
    }
});