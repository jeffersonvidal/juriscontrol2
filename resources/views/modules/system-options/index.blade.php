@extends('layouts.app')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h4 class="mb-1 fw-light">Configurações do Sistema</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Configurações</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Card Principal -->
    <div class="card shadow-sm border-0">
        <div class="card-body">
            
            <!-- Formulário de Pesquisa (Sua versão melhorada) -->
            <div class="card mb-4 border-0 bg-light-subtle">
                <div class="card-body">
                    <form id="filterForm" onsubmit="event.preventDefault(); window.loadSystemOptions(1);">
                        <div class="row g-3 align-items-end">
                            <!-- Filtro: Chave -->
                            <div class="col-12 col-md-4">
                                <label for="filter_option_name" class="form-label fw-semibold small text-uppercase text-muted">
                                    <i data-lucide="key" class="icon-xs me-1"></i> Chave
                                </label>
                                <input type="text" class="form-control form-control-sm" id="filter_option_name" name="filter_option_name" placeholder="Ex: MAIL_MAILER">
                            </div>

                            <!-- Filtro: Valor -->
                            <div class="col-12 col-md-2">
                                <label for="filter_option_value" class="form-label fw-semibold small text-uppercase text-muted">
                                    <i data-lucide="file-text" class="icon-xs me-1"></i> Valor
                                </label>
                                <input type="text" class="form-control form-control-sm" id="filter_option_value" name="filter_option_value" placeholder="Ex: smtp">
                            </div>

                            <!-- Filtro: Status -->
                            <div class="col-12 col-md-2">
                                <label for="filter_option_status" class="form-label fw-semibold small text-uppercase text-muted">
                                    <i data-lucide="toggle-right" class="icon-xs me-1"></i> Status
                                </label>
                                <select class="form-select form-select-sm" id="filter_option_status" name="filter_option_status">
                                    <option value="">Todos</option>
                                    <option value="1">Ativo</option>
                                    <option value="0">Inativo</option>
                                </select>
                            </div>

                            <!-- Botões -->
                            <div class="col-12 col-md-4">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary btn-sm flex-fill">
                                        <i data-lucide="search" class="icon-xs me-1"></i> Buscar
                                    </button>
                                    <button type="button" class="btn btn-outline-secondary btn-sm flex-fill" onclick="window.clearFilters()">
                                        <i data-lucide="x-circle" class="icon-xs me-1"></i> Limpar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Tabela de Resultados -->
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="systemOptionsTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4">Configuração</th>
                            <th>Descrição</th>
                            <th>Valor Atual</th>
                            <th>Status</th>
                            <th class="text-end pe-4">Ações</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <tr>
                            <td colspan="5" class="text-center py-5">
                                <div class="spinner-border text-primary" role="status"></div>
                                <p class="mt-2 text-muted">Carregando configurações...</p>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Container da Paginação (CRUCIAL: Não remova este ID) -->
        <div class="card-footer bg-transparent border-top-0 py-3">
            <div id="pagination-container" class="d-flex justify-content-center"></div>
        </div>
    </div>
</div>

@include('modules.system-options.partials.modal')
@endsection

@push('scripts')
<script>
// 1. Inicialização
document.addEventListener('DOMContentLoaded', function() {
    if (typeof lucide !== 'undefined') lucide.createIcons();
    window.loadSystemOptions(1);
});

// 2. Limpar Filtros
window.clearFilters = function() {
    document.getElementById('filterForm').reset();
    window.loadSystemOptions(1);
};

// 3. Carregar Dados (AJAX)
window.loadSystemOptions = async function(page = 1) {
    const tbody = document.getElementById('tableBody');
    const paginationContainer = document.getElementById('pagination-container');
    
    if (tbody) {
        tbody.innerHTML = `<tr><td colspan="5" class="text-center py-5"><div class="spinner-border text-primary" role="status"></div><p class="mt-2 text-muted">Carregando...</p></td></tr>`;
    }

    try {
        // Coleta filtros do formulário
        const form = document.getElementById('filterForm');
        const formData = new FormData(form);
        formData.append('page', page);
        const params = Object.fromEntries(formData.entries());

        const response = await axios.get('{{ route("system-options.data") }}', { params });
        
        if (!response.data.success) throw new Error(response.data.message || 'Erro desconhecido');

        // Estrutura do paginador do Laravel: response.data.data contém { data: [], links: [], ... }
        const paginator = response.data.data;
        const records = paginator.data;
        const links = paginator.links;

        // Renderiza Tabela
        // Função para limitar o texto
function limitText(text, limit = 20) {
    if (!text) return 'Não definido';

    return text.length > limit
        ? text.substring(0, limit) + '...'
        : text;
}

if (tbody) {
    tbody.innerHTML = '';

    if (!records || records.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="5" class="text-center py-4 text-muted">
                    Nenhuma configuração encontrada.
                </td>
            </tr>
        `;
    } else {
        records.forEach(opt => {
            const tr = document.createElement('tr');

            tr.innerHTML = `
                <td class="ps-4 fw-semibold">${opt.option_name}</td>
                <td class="text-muted small">${opt.option_description || '-'}</td>
                <td>
                    <span class="badge ${opt.option_value ? 'bg-success-subtle text-success' : 'bg-secondary-subtle text-secondary'}">
                        ${limitText(opt.option_value)}
                    </span>
                </td>
                <td>
                    <span class="badge ${opt.option_status ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger'}">
                        ${opt.option_status ? 'Ativo' : 'Inativo'}
                    </span>
                </td>
                <td class="text-end pe-4">
                    @can('system-options.edit')
                    <div class="dropdown">
                        <button class="btn btn-sm btn-light border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i data-lucide="more-vertical" class="icon-sm"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <a class="dropdown-item" href="#" onclick="window.openEditModal(${opt.id}); return false;">
                                    <i data-lucide="edit-3" class="icon-sm me-2"></i>
                                    Editar
                                </a>
                            </li>
                        </ul>
                    </div>
                    @endcan
                </td>
            `;

            tbody.appendChild(tr);
        });
    }
}

        // Renderiza Paginação
        if (paginationContainer && links && links.length > 0) {
            paginationContainer.innerHTML = buildPaginationHtml(links);
            if (typeof lucide !== 'undefined') lucide.createIcons();
        } else if (paginationContainer) {
            paginationContainer.innerHTML = ''; // Limpa se não houver páginas
        }

    } catch (error) {
        console.error('Erro ao carregar:', error);
        if (tbody) tbody.innerHTML = `<tr><td colspan="5" class="text-center py-4 text-danger">Erro ao carregar dados.</td></tr>`;
        Swal.fire('Erro', 'Não foi possível carregar as configurações.', 'error');
    }
};

// 4. Construtor de HTML de Paginação (Blindado)
function buildPaginationHtml(links) {
    let html = '<nav aria-label="Paginação"><ul class="pagination pagination-sm mb-0 justify-content-center">';
    
    links.forEach(link => {
        const activeClass = link.active ? 'active' : '';
        const disabledClass = !link.url ? 'disabled' : '';
        
        // Usa data-page para delegação de eventos (mais seguro que onclick inline)
        let pageAttr = '';
        if (link.url) {
            const urlObj = new URL(link.url);
            pageAttr = `data-page="${urlObj.searchParams.get('page') || 1}"`;
        }

        html += `
            <li class="page-item ${activeClass} ${disabledClass}">
                <a class="page-link" href="${link.url || '#'}" ${pageAttr}>${link.label}</a>
            </li>
        `;
    });
    
    html += '</ul></nav>';
    return html;
}

// 5. Delegação de Eventos para Paginação (Intercepta cliques nos links gerados)
document.addEventListener('click', function(e) {
    // Verifica se o clique foi em um link de paginação que possui o atributo data-page
    const pageLink = e.target.closest('.pagination a[data-page]');
    if (pageLink) {
        e.preventDefault(); // Impede o recarregamento da página
        const page = pageLink.getAttribute('data-page');
        
        window.loadSystemOptions(page);
        
        // Rolagem suave para o topo da tabela
        const table = document.getElementById('systemOptionsTable');
        if (table) table.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
});

// 6. Abrir Modal de Edição
window.openEditModal = async function(id) {
    const form = document.getElementById('systemOptionForm');
    if (form) form.reset();
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
    
    try {
        const response = await axios.get(`/system-options/${id}`);
        const data = response.data.dados;
        
        const elId = document.getElementById('option_id');
        const elName = document.getElementById('option_name');
        const elValue = document.getElementById('option_value');
        const elDesc = document.getElementById('display_description');

        if (elId) elId.value = data.id;
        if (elName) elName.value = data.option_name;
        if (elValue) elValue.value = data.option_value || '';
        if (elDesc) elDesc.textContent = data.option_description || 'Sem descrição';

        setTimeout(() => {
            const modalEl = document.getElementById('systemOptionModal');
            if (modalEl) new bootstrap.Modal(modalEl).show();
        }, 50);
    } catch (error) {
        Swal.fire('Erro', 'Não foi possível carregar os dados.', 'error');
    }
};

// 7. Salvar Alterações
window.saveSystemOption = async function(event) {
    event.preventDefault();
    const form = event.target;
    const id = document.getElementById('option_id')?.value;
    
    if (!id) { Swal.fire('Erro', 'ID não encontrado.', 'error'); return; }

    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    form.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');

    const formData = new FormData(form);
    formData.append('_method', 'PUT');

    const submitBtn = form.querySelector('button[type="submit"]');
    if (submitBtn) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Salvando...';
    }

    try {
        const response = await axios.post(`/system-options/${id}`, formData, { headers: { 'Content-Type': 'multipart/form-data' } });
        
        if (response.data.success) {
            const modalEl = document.getElementById('systemOptionModal');
            if (modalEl) {
                const modal = bootstrap.Modal.getInstance(modalEl);
                if (modal) modal.hide();
            }
            Swal.fire('Sucesso!', response.data.message || 'Salvo com sucesso!', 'success');
            window.loadSystemOptions(1); // Volta para página 1 após salvar
        }
    } catch (error) {
        if (error.response && error.response.status === 422) {
            const errors = error.response.data.errors;
            for (const key in errors) {
                const input = form.querySelector(`[name="${key}"]`);
                if (input) {
                    input.classList.add('is-invalid');
                    const feedback = input.nextElementSibling;
                    if (feedback && feedback.classList.contains('invalid-feedback')) feedback.textContent = errors[key][0];
                }
            }
            Swal.fire('Atenção', 'Corrija os campos destacados.', 'warning');
        } else {
            Swal.fire('Erro', 'Erro inesperado ao salvar.', 'error');
        }
    } finally {
        if (submitBtn) {
            submitBtn.disabled = false;
            submitBtn.innerHTML = 'Salvar Alterações';
        }
    }
};
</script>
@endpush