@extends('layouts.app')

@section('content')
<div class="container-fluid px-4">
    {{-- Header e Breadcrumb --}}
    <div class="d-flex justify-content-between align-items-center mb-4 mt-3">
        <div>
            <h4 class="mb-1 fw-semibold page-title">Tags</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb breadcrumb-custom mb-0" style="--bs-breadcrumb-divider: '>';">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Tags</li>
                </ol>
            </nav>
        </div>
        @can('tags.create')
        <button type="button" class="btn btn-primary d-flex align-items-center gap-2" 
                data-bs-toggle="modal" 
                data-bs-target="#tagModal" 
                id="btnCreateTag">
            <i class="bi bi-plus-lg"></i>
            <span>Nova Tag</span>
        </button>
        @endcan
    </div>

    {{-- Filtros de Pesquisa --}}
    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body p-3">
            <form id="filterForm">
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label class="form-label text-muted small fw-medium">Nome da Tag</label>
                        <input type="text" class="form-control" name="name" placeholder="Buscar por nome...">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted small fw-medium">Status</label>
                        <select class="form-select" name="is_active">
                            <option value="">Todos</option>
                            <option value="1">Ativo</option>
                            <option value="0">Inativo</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-grow-1 d-flex align-items-center justify-content-center gap-2">
                            <i class="bi bi-search"></i> Filtrar
                        </button>
                        <button type="button" class="btn btn-light border" id="btnClearFilters">
                            <i class="bi bi-arrow-counterclockwise"></i>
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Tabela de Dados --}}
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive-custom">
                <table class="table table-hover align-middle mb-0" id="tagsTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 fw-semibold text-secondary small">TAG</th>
                            <th class="fw-semibold text-secondary small">NOME</th>
                            <th class="fw-semibold text-secondary small">STATUS</th>
                            <th class="fw-semibold text-secondary small text-end pe-4">AÇÕES</th>
                        </tr>
                    </thead>
                    <tbody id="tagsTableBody">
                        @include('modules.tags.partials.table', ['tags' => $tags])
                    </tbody>
                </table>
            </div>
            <div class="card-footer border-top-0 p-3 d-flex justify-content-between align-items-center">
                <small class="text-muted">Exibindo <span id="totalRecords">{{ $tags->total() }}</span> registros</small>
                <div id="paginationLinks">
                    {{ $tags->links() }}
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Inclusão do Modal --}}
@include('modules.tags.partials.modal')
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
// ============================================
// CONFIGURAÇÃO GLOBAL DO AXIOS
// ============================================
if (typeof axios !== 'undefined') {
    axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';
    axios.defaults.headers.common['Accept'] = 'application/json';

    axios.interceptors.request.use(function (config) {
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (csrfToken) {
            config.headers['X-CSRF-TOKEN'] = csrfToken;
        }
        return config;
    });
}

// ============================================
// FUNÇÃO AUXILIAR PARA CLAREAR COR (Global)
// ============================================
window.lightenColor = function(hex, percent) {
    if (!hex) return '#f8f9fa';
    hex = hex.replace('#', '');
    if (hex.length === 3) {
        hex = hex.split('').map(c => c + c).join('');
    }
    let r = parseInt(hex.substring(0, 2), 16);
    let g = parseInt(hex.substring(2, 4), 16);
    let b = parseInt(hex.substring(4, 6), 16);
    
    r = Math.round(r + (255 - r) * percent);
    g = Math.round(g + (255 - g) * percent);
    b = Math.round(b + (255 - b) * percent);
    
    return "#" + ((1 << 24) + (r << 16) + (g << 8) + b).toString(16).slice(1).toUpperCase();
};

// ============================================
// INICIALIZAÇÃO AO CARREGAR O DOM
// ============================================
document.addEventListener('DOMContentLoaded', function() {
    console.log('🚀 [TAGS] Módulo carregado com Axios');

    // ============================================
    // ELEMENTOS DO DOM (COM VERIFICAÇÃO)
    // ============================================
    const tagForm = document.getElementById('tagForm');
    const tagModal = document.getElementById('tagModal');
    const colorInput = document.getElementById('color');
    const colorText = document.getElementById('colorText');
    const tagPreview = document.getElementById('tag-preview');
    const nameInput = document.getElementById('name');
    const tagIdInput = document.getElementById('tag_id');

    if (!tagForm) {
        console.error('❌ [TAGS] Formulário #tagForm não encontrado!');
        return;
    }

    // ============================================
    // PREVIEW DA COR EM TEMPO REAL
    // ============================================
    if (colorInput && tagPreview && colorText && nameInput) {
        colorInput.addEventListener('input', function() {
            let hex = this.value;
            let lightenedBg = window.lightenColor(hex, 0.85);
            
            tagPreview.style.color = hex;
            tagPreview.style.backgroundColor = lightenedBg;
            colorText.value = hex.toUpperCase();
            tagPreview.innerText = nameInput.value || 'Exemplo';
        });

        nameInput.addEventListener('input', function() {
            tagPreview.innerText = this.value || 'Exemplo';
        });
    }

    // ============================================
    // PREPARAR MODAL AO ABRIR
    // ============================================
    if (tagModal) {
        tagModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const isEditing = tagIdInput && tagIdInput.value !== '';
            
            if (!isEditing) {
                tagForm.reset();
                tagForm.classList.remove('was-validated');
                document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
                
                if (tagPreview) {
                    tagPreview.style.color = '#6c757d';
                    tagPreview.style.backgroundColor = '#f8f9fa';
                    tagPreview.innerText = 'Exemplo';
                }
                if (colorText) colorText.value = '#1A73E8';

                const storeUrl = '{{ route("tags.store") }}';
                tagForm.setAttribute('action', storeUrl);
                tagForm.setAttribute('data-action', storeUrl);
                tagForm.setAttribute('data-method', 'POST');
                
                const modalTitle = document.getElementById('modalTitle');
                if (modalTitle) modalTitle.innerText = 'Nova Tag';
                
                if (tagIdInput) tagIdInput.value = '';
            }
        });
    }

        // ============================================
    // SUBMIT DO FORMULÁRIO COM AXIOS (COM CORREÇÃO DO CHECKBOX)
    // ============================================
    tagForm.addEventListener('submit', function(e) {
        e.preventDefault();

        const formData = new FormData(this);
        const url = this.getAttribute('data-action');
        const method = this.getAttribute('data-method').toUpperCase();

        console.log(`📤 [TAGS] Preparando envio. URL: ${url} | Método Lógico: ${method}`);

        // CORREÇÃO CRÍTICA PARA CHECKBOX:
        // Se o checkbox não estiver marcado, ele não é enviado pelo navegador.
        // Forçamos o valor '0' explicitamente para garantir que o backend saiba que foi desativado.
        const isActiveInput = document.getElementById('is_active');
        if (isActiveInput) {
            if (isActiveInput.checked) {
                formData.set('is_active', '1');
            } else {
                formData.set('is_active', '0');
            }
        }

        // TRUQUE DO LARAVEL: Se for PUT/PATCH, enviamos como POST, mas com o campo _method
        if (method === 'PUT' || method === 'PATCH') {
            formData.append('_method', 'PUT');
            console.log('🔒 [TAGS] Method Spoofing aplicado: _method = PUT');
        }

        Swal.fire({
            title: 'Processando...',
            text: 'Aguarde enquanto salvamos a tag.',
            allowOutsideClick: false,
            didOpen: () => {
                Swal.showLoading();
            }
        });

        // SEMPRE usamos axios.post. O Laravel lerá o _method e roteará para o update()
        axios.post(url, formData)
            .then(response => {
                console.log('✅ [TAGS] Resposta de sucesso:', response.data);
                
                if (response.data.success) {
                    Swal.close();
                    
                    const modalInstance = bootstrap.Modal.getInstance(tagModal);
                    if (modalInstance) modalInstance.hide();
                    
                    Swal.fire({
                        icon: 'success',
                        title: 'Sucesso!',
                        text: response.data.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire('Erro', response.data.message || 'Erro ao processar.', 'error');
                }
            })
            .catch(error => {
                Swal.close();
                console.error('❌ [TAGS] Erro detalhado:', error.response ? error.response.data : error);
                
                if (error.response && error.response.status === 422) {
                    const errors = error.response.data.errors;
                    for (const field in errors) {
                        const input = tagForm.querySelector(`[name="${field}"]`);
                        if (input) {
                            input.classList.add('is-invalid');
                            const feedback = input.nextElementSibling;
                            if (feedback && feedback.classList.contains('invalid-feedback')) {
                                feedback.innerText = errors[field][0];
                            }
                        }
                    }
                } else if (error.response && error.response.status === 404) {
                    Swal.fire('Não Encontrado', 'O registro não existe ou você não tem permissão.', 'warning');
                } else {
                    Swal.fire('Erro', 'Falha na comunicação com o servidor.', 'error');
                }
            });
    });

    // ============================================
    // FILTROS DE PESQUISA COM AXIOS (COM VERIFICAÇÃO)
    // ============================================
    const filterForm = document.getElementById('filterForm');
    if (filterForm) {
        filterForm.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const params = new URLSearchParams(formData).toString();
            
            axios.get(`{{ route('tags.index') }}?${params}`)
                .then(response => {
                    document.getElementById('tagsTableBody').innerHTML = response.data.html;
                    document.getElementById('paginationLinks').innerHTML = response.data.pagination;
                    document.getElementById('totalRecords').innerText = response.data.total;
                })
                .catch(error => {
                    console.error('Erro ao filtrar:', error);
                });
        });

        const btnClearFilters = document.getElementById('btnClearFilters');
        if (btnClearFilters) {
            btnClearFilters.addEventListener('click', function() {
                filterForm.reset();
                filterForm.dispatchEvent(new Event('submit'));
            });
        }
    } else {
        console.warn('⚠️ [TAGS] Formulário de filtros não encontrado (opcional)');
    }

    console.log('✅ [TAGS] Eventos registrados com sucesso.');
});

// ============================================
// FUNÇÕES GLOBAIS
// ============================================

window.viewTag = function(id) {
    Swal.fire('Visualizar', `Detalhes da Tag ID: ${id}`, 'info');
};

window.editTag = function(id) {
    console.log('✏️ [EDIT] Editando Tag ID:', id);
    
    const form = document.getElementById('tagForm');
    if (!form) return Swal.fire('Erro', 'Formulário não encontrado.', 'error');
    
    // 1. Limpa o formulário e remove validações anteriores
    form.reset();
    form.classList.remove('was-validated');
    document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    
    // 2. Busca os dados via Axios
    axios.get(`/tags/${id}/edit`)
        .then(response => {
            const tag = response.data;
            console.log('✅ [EDIT] Dados recebidos:', tag);
            
            // 3. Configura o formulário para modo de Edição (PUT)
            const modalTitle = document.getElementById('modalTitle');
            if (modalTitle) modalTitle.innerText = 'Editar Tag';
            
            const updateUrl = `/tags/${id}`;
            form.setAttribute('action', updateUrl);
            form.setAttribute('data-action', updateUrl);
            form.setAttribute('data-method', 'PUT');
            
            // 4. Preenche os campos com Guard Clauses (verifica se o elemento existe antes de atribuir)
            const tagIdInput = document.getElementById('tag_id');
            if (tagIdInput) tagIdInput.value = tag.id;

            const nameInput = document.getElementById('name');
            if (nameInput) nameInput.value = tag.name;

            const colorInput = document.getElementById('color');
            if (colorInput) colorInput.value = tag.color;

            const isActiveInput = document.getElementById('is_active');
            if (isActiveInput) {
                // Normaliza o valor booleano vindo do backend
                isActiveInput.checked = (tag.is_active === 1 || tag.is_active === true || tag.is_active === '1');
            }

            // 5. Atualiza o Preview Visual da Tag
            const tagPreview = document.getElementById('tag-preview');
            if (tagPreview && tag.color) {
                tagPreview.style.color = tag.color;
                tagPreview.style.backgroundColor = tag.bg_color || window.lightenColor(tag.color, 0.85);
                tagPreview.innerText = tag.name || 'Exemplo';
            }
            
            // 6. Abre o Modal
            const tagModal = document.getElementById('tagModal');
            if (tagModal) {
                // Pequeno timeout para garantir que o DOM esteja pronto para o Bootstrap
                setTimeout(() => {
                    new bootstrap.Modal(tagModal).show();
                }, 50);
            }
        })
        .catch(error => {
            console.error('❌ [EDIT] Erro:', error);
            Swal.fire('Erro', 'Não foi possível carregar os dados da tag.', 'error');
        });
};

window.deleteTag = function(id) {
    Swal.fire({
        title: 'Tem certeza?',
        text: "Você não poderá reverter isso!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sim, excluir!',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire({
                title: 'Excluindo...',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            axios.delete(`/tags/${id}`)
                .then(response => {
                    if (response.data.success) {
                        Swal.close();
                        Swal.fire('Excluído!', response.data.message, 'success');
                        
                        const row = document.querySelector(`tr[data-id="${id}"]`);
                        if (row) {
                            row.style.transition = 'all 0.4s ease';
                            row.style.opacity = '0';
                            row.style.transform = 'translateX(-20px)';
                            setTimeout(() => row.remove(), 400);
                        }
                    } else {
                        Swal.fire('Erro', response.data.message, 'error');
                    }
                })
                .catch(error => {
                    Swal.close();
                    console.error('Erro ao excluir:', error);
                    Swal.fire('Erro', 'Falha ao excluir o registro.', 'error');
                });
        }
    });
};
</script>
@endpush