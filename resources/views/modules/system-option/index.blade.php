@extends('layouts.app')

@section('title', 'Opções do Sistema')

@section('content')
<div class="container-fluid py-4">
    <!-- Header com Breadcrumb e Botão Novo -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"
                        class="text-decoration-none">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Opções do Sistema</li>
            </ol>
        </nav>
        @can('system-option.create')
            <button type="button" class="btn btn-primary btn-sm d-flex align-items-center gap-2"
                onclick="window.openSystemOptionModal()">
                <i data-lucide="plus" width="16"></i> Nova Opção
            </button>
        @endcan
    </div>

    <!-- Formulário de Filtros -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form id="filterForm" class="row g-3">
                <div class="col-md-6">
                    <label for="search" class="form-label text-muted small">Buscar</label>
                    <!-- O ID DEVE SER "search" -->
                    <input type="text" class="form-control form-control-sm" id="search" name="search"
                        placeholder="Nome ou descrição...">
                </div>
                <div class="col-md-4">
                    <label for="option_status_filter" class="form-label text-muted small">Status</label>
                    <!-- O ID DEVE SER "option_status_filter" (para não conflitar com o do modal) -->
                    <select class="form-select form-select-sm" id="option_status_filter" name="option_status">
                        <option value="">Todos</option>
                        <option value="1">Ativo</option>
                        <option value="0">Inativo</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-outline-secondary btn-sm w-100"
                        onclick="window.applyFilters()">
                        <i data-lucide="search" width="14" class="me-1"></i> Filtrar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Tabela de Registros -->
    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="systemOptionsTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Nome da Opção</th>
                            <th>Valor</th>
                            <th>Descrição</th>
                            <th class="text-center">Status</th>
                            <th class="text-end pe-4">Ações</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <!-- Dados carregados via AJAX -->
                    </tbody>
                </table>
            </div>
            <div class="p-3 border-top" id="paginationContainer">
                <!-- Paginação injetada via JS -->
            </div>
        </div>
    </div>
</div>

<!-- Inclusão do Modal -->
@include('modules.system-option.partials.modal')


@push('scripts')
<script>
    // TUDO que toca o DOM deve estar DENTRO do DOMContentLoaded
    document.addEventListener('DOMContentLoaded', function() {
        console.log('✅ DOM totalmente carregado. Iniciando sistema...');

        // 1. Configurar Axios CSRF
        const csrfMeta = document.querySelector('meta[name="csrf-token"]');
        if (csrfMeta) {
            axios.defaults.headers.common['X-CSRF-TOKEN'] = csrfMeta.getAttribute('content');
        }

        // 2. Inicializar Ícones Lucide
        if (typeof lucide !== 'undefined') {
            lucide.createIcons();
        }

        // 3. Guard Clause para o campo de busca (RESOLVE o erro addEventListener)
        const searchInput = document.getElementById('search');
        if (searchInput) {
            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    window.loadTableData(1);
                }
            });
        }

        // 4. Carregar dados iniciais da tabela
        window.loadTableData(1);
    });

    // ==========================================
    // FUNÇÕES GLOBAIS (window.) para o HTML
    // ==========================================

    window.loadTableData = function(page = 1) {
        console.log('📊 Carregando dados da página:', page);
        const searchInput = document.getElementById('search');
        const statusInput = document.getElementById('option_status_filter');

        axios.get('/system-options/data', {
            params: {
                page: page,
                search: searchInput ? searchInput.value : '',
                option_status: statusInput ? statusInput.value : ''
            }
        }).then(response => {
            if (response.data.success) {
                renderTable(response.data.dados.data);
                renderPagination(response.data.dados);
                // Recarregar ícones após renderizar a tabela
                setTimeout(() => { if (typeof lucide !== 'undefined') lucide.createIcons(); }, 100);
            }
        }).catch(error => {
            console.error('❌ Erro ao carregar:', error);
            if (typeof Swal !== 'undefined') Swal.fire('Erro', 'Falha ao carregar dados', 'error');
        });
    };

    function renderTable(data) {
        const tbody = document.getElementById('tableBody');
        if (!tbody) return;
        tbody.innerHTML = '';

        if (!data || data.length === 0) {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center py-4 text-muted">Nenhum registro encontrado.</td></tr>';
            return;
        }

        data.forEach(item => {
            const statusBadge = item.option_status == 1
                ? '<span class="badge bg-success-subtle text-success">Ativo</span>'
                : '<span class="badge bg-danger-subtle text-danger">Inativo</span>';

            const row = document.createElement('tr');
            // Playbook: hover suave apenas com background-color, NUNCA transform
            row.style.transition = 'background-color 0.2s ease';
            row.innerHTML = `
                <td class="ps-4 fw-medium">${item.option_name}</td>
                <td class="text-muted small">${item.option_value || '-'}</td>
                <td class="text-muted small">${item.option_description || '-'}</td>
                <td class="text-center">${statusBadge}</td>
                <td class="text-end pe-4">
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i data-lucide="more-vertical" width="16"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                            <li><a class="dropdown-item" href="#" onclick="window.openSystemOptionModal(${item.id})"><i data-lucide="pencil" width="14" class="me-2"></i>Editar</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="#" onclick="window.deleteSystemOption(${item.id})"><i data-lucide="trash-2" width="14" class="me-2"></i>Excluir</a></li>
                        </ul>
                    </div>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    function renderPagination(paginator) {
        const container = document.getElementById('paginationContainer');
        if (!container) return;
        if (paginator.last_page <= 1) { container.innerHTML = ''; return; }
        
        let html = `<nav><ul class="pagination pagination-sm justify-content-end mb-0">`;
        if (paginator.current_page > 1) {
            html += `<li class="page-item"><a class="page-link" href="#" onclick="window.loadTableData(${paginator.current_page - 1})">Anterior</a></li>`;
        }
        for (let i = 1; i <= paginator.last_page; i++) {
            const active = i === paginator.current_page ? 'active' : '';
            html += `<li class="page-item ${active}"><a class="page-link" href="#" onclick="window.loadTableData(${i})">${i}</a></li>`;
        }
        if (paginator.current_page < paginator.last_page) {
            html += `<li class="page-item"><a class="page-link" href="#" onclick="window.loadTableData(${paginator.current_page + 1})">Próxima</a></li>`;
        }
        html += `</ul></nav>`;
        container.innerHTML = html;
    }

    window.openSystemOptionModal = function(id = null) {
        const modalEl = document.getElementById('systemOptionModal');
        const form = document.getElementById('systemOptionForm');
        const errorDiv = document.getElementById('error_messages');
        
        if (!modalEl || !form) return;

        // Playbook: LIMPAR todos os campos primeiro
        form.reset();
        form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        if (errorDiv) errorDiv.innerHTML = '';

        if (id) {
            axios.get(`/system-options/${id}/edit`).then(response => {
                if (response.data.success) {
                    const data = response.data.dados;
                    const idField = document.getElementById('system_option_id');
                    const nameField = document.getElementById('option_name');
                    const valueField = document.getElementById('option_value');
                    const descField = document.getElementById('option_description');
                    const statusField = document.getElementById('option_status');

                    if (idField) idField.value = data.id;
                    if (nameField) nameField.value = data.option_name;
                    if (valueField) valueField.value = data.option_value || '';
                    if (descField) descField.value = data.option_description || '';
                    if (statusField) statusField.checked = (data.option_status == 1);

                    form.setAttribute('data-action', `/system-options/${id}`);
                    form.setAttribute('data-method', 'PUT');
                }
            }).catch(() => {
                if (typeof Swal !== 'undefined') Swal.fire('Erro', 'Não foi possível carregar os dados.', 'error');
            }).finally(() => {
                // Playbook: setTimeout(50ms) ao abrir modal após AJAX
                setTimeout(() => { new bootstrap.Modal(modalEl).show(); }, 50);
            });
        } else {
            form.setAttribute('data-action', '/system-options');
            form.setAttribute('data-method', 'POST');
            setTimeout(() => { new bootstrap.Modal(modalEl).show(); }, 50);
        }
    };

    window.submitSystemOptionForm = function(event) {
        event.preventDefault();
        const form = event.target;
        const action = form.getAttribute('data-action');
        const method = form.getAttribute('data-method');
        
        const formData = new FormData(form);
        const statusCheckbox = document.getElementById('option_status');
        // Playbook: forçar envio do checkbox
        formData.set('option_status', statusCheckbox && statusCheckbox.checked ? 1 : 0);
        
        // Playbook: Method Spoofing para PUT
        if (method === 'PUT') formData.append('_method', 'PUT');

        const submitBtn = form.querySelector('button[type="submit"]');
        if (submitBtn) submitBtn.disabled = true;

        axios.post(action, formData).then(response => {
            if (response.data.success) {
    const modalInstance = bootstrap.Modal.getInstance(document.getElementById('systemOptionModal'));
    if (modalInstance) modalInstance.hide();

    if (typeof Swal !== 'undefined') {
        Swal.fire({
            icon: 'success',
            title: 'Sucesso',
            text: response.data.message,
            timer: 2000, // 2 segundos
            timerProgressBar: true,
            showConfirmButton: false
        }).then(() => {
            location.reload();
        });
    } else {
        location.reload();
    }
}
        }).catch(error => {
            if (error.response && error.response.status === 422) {
                const errors = error.response.data.errors;
                let errorHtml = '<ul class="mb-0 ps-3">';
                for (const key in errors) {
                    errorHtml += `<li>${errors[key][0]}</li>`;
                    const input = form.querySelector(`[name="${key}"]`);
                    if (input) input.classList.add('is-invalid');
                }
                errorHtml += '</ul>';
                const errorDiv = document.getElementById('error_messages');
                if (errorDiv) errorDiv.innerHTML = `<div class="alert alert-danger py-2 small">${errorHtml}</div>`;
            } else {
                if (typeof Swal !== 'undefined') Swal.fire('Erro', 'Ocorreu um erro inesperado.', 'error');
            }
        }).finally(() => {
            if (submitBtn) submitBtn.disabled = false;
        });
    };

    window.deleteSystemOption = function(id) {
    if (typeof Swal !== 'undefined') {
        Swal.fire({
            title: 'Tem certeza?',
            text: 'Esta ação não poderá ser desfeita!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sim, excluir!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                axios.delete(`/system-options/${id}`)
                    .then(response => {
                        if (response.data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Excluído!',
                                text: response.data.message,
                                timer: 2000,
                                timerProgressBar: true,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload();
                            });
                        }
                    })
                    .catch(() => {
                        Swal.fire({
                            icon: 'error',
                            title: 'Erro',
                            text: 'Erro ao excluir o registro.',
                            timer: 2000,
                            timerProgressBar: true,
                            showConfirmButton: false
                        });
                    });
            }
        });
    }
};
</script>
@endpush

@endsection