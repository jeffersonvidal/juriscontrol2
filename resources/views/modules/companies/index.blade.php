@extends('layouts.app')

@section('title', 'Empresas')
@section('page-title', 'Gerenciamento de Empresas')

@section('content')
<div class="fade-in-up">
    {{-- Card de Filtros e Ações --}}
    <div class="card-jc p-3 mb-4">
        <div class="row g-3 align-items-end">
            <div class="col-md-5">
                <label class="form-label-jc">Buscar</label>
                <input type="text" id="filterSearch" class="form-control form-control-jc" placeholder="Nome fantasia, razão social, documento ou e-mail...">
            </div>
            <div class="col-md-3">
                <label class="form-label-jc">Status</label>
                <select id="filterIsActive" class="form-select form-select-jc">
                    <option value="">Todos</option>
                    <option value="1">Ativo</option>
                    <option value="0">Inativo</option>
                </select>
            </div>
            <div class="col-md-4 d-flex gap-2 justify-content-md-end">
                <button type="button" class="btn btn-jc-outline" id="btnClearFilters">
                    <i data-lucide="x" class="icon-sm me-1"></i> Limpar
                </button>
                @can('companies.create')
                <button type="button" class="btn btn-jc-primary" data-bs-toggle="modal" data-bs-target="#companyModal" id="btnNewCompany">
                    <i data-lucide="plus" class="icon-sm me-1"></i> Nova Empresa
                </button>
                @endcan
            </div>
        </div>
    </div>

    {{-- Tabela de Resultados --}}
    <div class="card-jc p-0 overflow-hidden">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="companiesTable">
                <thead class="bg-surface border-bottom border-jc">
                    <tr>
                        <th class="ps-4" style="width: 25%">Nome Fantasia</th>
                        <th style="width: 25%">Razão Social</th>
                        <th style="width: 15%">CNPJ/CPF</th>
                        <th style="width: 10%">Status</th>
                        <th class="text-end pe-4" style="width: 10%">Ações</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                    {{-- Preenchido via AJAX --}}
                </tbody>
            </table>
        </div>
        <div class="p-3 border-top border-jc d-flex justify-content-center" id="paginationLinks">
            {{-- Links de paginação via AJAX --}}
        </div>
    </div>
</div>

{{-- Modal de Cadastro/Edição --}}
<div class="modal fade" id="companyModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content bg-surface border-jc">
            <form id="companyForm" novalidate>
                @csrf
                <input type="hidden" id="companyId" name="id">
                
                <div class="modal-header border-bottom border-jc">
                    <h5 class="modal-title fw-semibold" id="modalTitle">Nova Empresa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                
                <div class="modal-body p-4">
                    {{-- Seção: Dados da Empresa --}}
                    <h6 class="fw-semibold mb-3 d-flex align-items-center gap-2">
                        <i data-lucide="building-2" class="icon-sm text-primary"></i>
                        Dados da Empresa
                    </h6>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label-jc">Nome Fantasia <span class="text-danger">*</span></label>
                            <input type="text" name="trade_name" id="fieldTradeName" class="form-control form-control-jc" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-jc">Razão Social <span class="text-danger">*</span></label>
                            <input type="text" name="corporate_reason" id="fieldCorporateReason" class="form-control form-control-jc" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-jc">CNPJ/CPF <span class="text-danger">*</span></label>
                            <input type="text" name="cnpj_cpf" id="fieldCnpjCpf" class="form-control form-control-jc" required placeholder="00.000.000/0000-00">
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-jc">E-mail <span class="text-danger">*</span></label>
                            <input type="email" name="email" id="fieldEmail" class="form-control form-control-jc" required>
                            <div class="invalid-feedback"></div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-jc">Telefone</label>
                            <input type="text" name="phone" id="fieldPhone" class="form-control form-control-jc" placeholder="(00) 00000-0000">
                        </div>
                        <div class="col-md-12">
                            <label class="form-label-jc">Status <span class="text-danger">*</span></label>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" name="is_active" id="fieldIsActive" value="1" checked>
                                <label class="form-check-label" for="fieldIsActive">Ativo</label>
                            </div>
                        </div>
                    </div>

                    {{-- Seção: Endereços --}}
                    <h6 class="fw-semibold mb-3 d-flex align-items-center gap-2">
                        <i data-lucide="map-pin" class="icon-sm text-primary"></i>
                        Endereços
                        <button type="button" class="btn btn-sm btn-jc-outline ms-auto" id="btnAddAddress">
                            <i data-lucide="plus" class="icon-xs me-1"></i> Adicionar
                        </button>
                    </h6>
                    <div id="addressesContainer">
                        {{-- Endereços serão inseridos aqui via JS --}}
                    </div>
                    <div id="noAddressesMessage" class="text-center text-muted-custom py-3">
                        <i data-lucide="map-pin-off" class="icon-md mb-2 d-block mx-auto"></i>
                        Nenhum endereço cadastrado.
                    </div>
                </div>
                
                <div class="modal-footer border-top border-jc">
                    <button type="button" class="btn btn-jc-outline" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-jc-primary" id="btnSave">
                        <span class="btn-text">Salvar</span>
                        <span class="spinner-border spinner-border-sm d-none" role="status"></span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Template de Endereço (usado pelo JS para clonar) --}}
<template id="addressTemplate">
    @include('modules.companies.partials.address-fields')
</template>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    let currentPage = 1;
    let filters = { search: '', is_active: '' };
    let isEditing = false;
    let addressCounter = 0;

    // ============================================================
    // 1. CARREGAR DADOS (AJAX)
    // ============================================================
    function loadData(page = 1) {
        const params = new URLSearchParams({ ...filters, page });
        
        axios.get(`{{ route('companies.list') }}?${params.toString()}`)
            .then(response => {
                document.getElementById('tableBody').innerHTML = response.data.html;
                document.getElementById('paginationLinks').innerHTML = response.data.links;
                
                if (typeof lucide !== 'undefined') lucide.createIcons();
                
                if (page === 1 && !window.justDeleted) {
                    animateRowsIn();
                }
                window.justDeleted = false;
            })
            .catch(error => {
                console.error('Erro ao carregar dados:', error);
                toastError('Erro ao carregar a lista de empresas.');
            });
    }

    // ============================================================
    // 2. FILTROS
    // ============================================================
    let searchTimeout;
    document.getElementById('filterSearch').addEventListener('input', function(e) {
        clearTimeout(searchTimeout);
        searchTimeout = setTimeout(() => {
            filters.search = e.target.value;
            loadData(1);
        }, 400);
    });

    document.getElementById('filterIsActive').addEventListener('change', function(e) {
        filters.is_active = e.target.value;
        loadData(1);
    });

    document.getElementById('btnClearFilters').addEventListener('click', function() {
        document.getElementById('filterSearch').value = '';
        document.getElementById('filterIsActive').value = '';
        filters = { search: '', is_active: '' };
        loadData(1);
    });

    // Delegação de evento para paginação AJAX
    document.getElementById('paginationLinks').addEventListener('click', function(e) {
        e.preventDefault();
        const link = e.target.closest('a');
        if (link && link.href) {
            const url = new URL(link.href);
            loadData(url.searchParams.get('page') || 1);
        }
    });

    // ============================================================
    // 3. MODAL: ABRIR E LIMPAR
    // ============================================================
    const modalEl = document.getElementById('companyModal');
    modalEl.addEventListener('show.bs.modal', function(event) {
        resetForm();
        
        const button = event.relatedTarget;
        if (button && button.getAttribute('data-action') === 'edit') {
            isEditing = true;
            document.getElementById('modalTitle').textContent = 'Editar Empresa';
            document.getElementById('companyId').value = button.getAttribute('data-id');
            
            axios.get(`{{ url('companies') }}/${button.getAttribute('data-id')}/edit`)
                .then(res => {
                    const data = res.data.data;
                    document.getElementById('fieldTradeName').value = data.trade_name || '';
                    document.getElementById('fieldCorporateReason').value = data.corporate_reason || '';
                    document.getElementById('fieldCnpjCpf').value = data.cnpj_cpf || '';
                    document.getElementById('fieldEmail').value = data.email || '';
                    document.getElementById('fieldPhone').value = data.phone || '';
                    document.getElementById('fieldIsActive').checked = data.is_active;
                    
                    // Carrega endereços
                    if (data.addresses && data.addresses.length > 0) {
                        data.addresses.forEach(addr => addAddressField(addr));
                    }
                });
        } else {
            isEditing = false;
            document.getElementById('modalTitle').textContent = 'Nova Empresa';
        }
    });

    modalEl.addEventListener('hidden.bs.modal', function() {
        resetForm();
    });

    function resetForm() {
        document.getElementById('companyForm').reset();
        document.getElementById('companyId').value = '';
        document.getElementById('companyForm').classList.remove('was-validated');
        document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
        document.querySelectorAll('.invalid-feedback').forEach(el => el.textContent = '');
        document.getElementById('addressesContainer').innerHTML = '';
        document.getElementById('noAddressesMessage').style.display = 'block';
        addressCounter = 0;
    }

    // ============================================================
    // 4. ENDEREÇOS DINÂMICOS
    // ============================================================
    document.getElementById('btnAddAddress').addEventListener('click', function() {
        addAddressField();
    });

    function addAddressField(data = null) {
        const template = document.getElementById('addressTemplate').content.cloneNode(true);
        const wrapper = template.querySelector('.address-card');
        const index = addressCounter++;
        
        // Atualiza os nomes dos campos com o índice
        wrapper.querySelectorAll('[name]').forEach(input => {
            const name = input.getAttribute('name');
            input.setAttribute('name', `addresses[${index}][${name}]`);
            input.setAttribute('data-index', index);
        });
        
        // Preenche com dados se estiver editando
        if (data) {
            wrapper.querySelector('[name="id"]').value = data.id || '';
            wrapper.querySelector('[name="label"]').value = data.label || '';
            wrapper.querySelector('[name="street"]').value = data.street || '';
            wrapper.querySelector('[name="number"]').value = data.number || '';
            wrapper.querySelector('[name="complement"]').value = data.complement || '';
            wrapper.querySelector('[name="district"]').value = data.district || '';
            wrapper.querySelector('[name="city"]').value = data.city || '';
            wrapper.querySelector('[name="state"]').value = data.state || '';
            wrapper.querySelector('[name="zip_code"]').value = data.zip_code || '';
            wrapper.querySelector('[name="country"]').value = data.country || 'Brasil';
            wrapper.querySelector('[name="is_default"]').checked = data.is_default || false;
        }
        
        // Evento de remover endereço
        wrapper.querySelector('.btn-remove-address').addEventListener('click', function() {
            // Se tem ID, marca para exclusão
            const idInput = wrapper.querySelector('[name="id"]');
            if (idInput && idInput.value) {
                const destroyInput = document.createElement('input');
                destroyInput.type = 'hidden';
                destroyInput.name = `addresses[${index}][_destroy]`;
                destroyInput.value = '1';
                wrapper.appendChild(destroyInput);
                wrapper.style.opacity = '0.5';
                wrapper.querySelector('.btn-remove-address').disabled = true;
            } else {
                wrapper.remove();
                updateNoAddressesMessage();
            }
        });
        
        document.getElementById('addressesContainer').appendChild(wrapper);
        updateNoAddressesMessage();
        
        if (typeof lucide !== 'undefined') lucide.createIcons();
    }

    function updateNoAddressesMessage() {
        const hasAddresses = document.getElementById('addressesContainer').children.length > 0;
        document.getElementById('noAddressesMessage').style.display = hasAddresses ? 'none' : 'block';
    }

    // ============================================================
    // 5. SUBMIT DO FORMULÁRIO (AJAX)
    // ============================================================
    document.getElementById('companyForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const form = e.target;
        const btnSave = document.getElementById('btnSave');
        const btnText = btnSave.querySelector('.btn-text');
        const spinner = btnSave.querySelector('.spinner-border');

        btnSave.disabled = true;
        btnText.classList.add('d-none');
        spinner.classList.remove('d-none');

        const formData = new FormData(form);
        const data = Object.fromEntries(formData.entries());
        const id = document.getElementById('companyId').value;

        // Converte is_active para boolean
        data.is_active = document.getElementById('fieldIsActive').checked ? 1 : 0;

        // Coleta endereços
        const addresses = [];
        document.querySelectorAll('#addressesContainer .address-card').forEach(card => {
            const addr = {};
            card.querySelectorAll('[name]').forEach(input => {
                const name = input.getAttribute('name').match(/addresses\[\d+\]\[(.+)\]/);
                if (name) {
                    addr[name[1]] = input.type === 'checkbox' ? (input.checked ? 1 : 0) : input.value;
                }
            });
            if (Object.keys(addr).length > 0) {
                addresses.push(addr);
            }
        });
        data.addresses = addresses;

        const url = isEditing ? `{{ url('companies') }}/${id}` : `{{ url('companies') }}`;
        const method = isEditing ? 'put' : 'post';

        if (isEditing) {
            data._method = 'PUT';
        }

        axios({ method, url, data })
            .then(response => {
                toastSuccess(response.data.message);
                bootstrap.Modal.getInstance(modalEl).hide();
                
                if (!isEditing) {
                    window.justInsertedId = response.data.data.id;
                }
                loadData(1);
            })
            .catch(error => {
                if (error.response && error.response.status === 422) {
                    const errors = error.response.data.errors;
                    Object.keys(errors).forEach(field => {
                        const input = form.querySelector(`[name="${field}"]`);
                        if (input) {
                            input.classList.add('is-invalid');
                            const feedback = input.nextElementSibling;
                            if (feedback && feedback.classList.contains('invalid-feedback')) {
                                feedback.textContent = errors[field][0];
                            }
                        }
                    });
                } else {
                    toastError('Ocorreu um erro inesperado.');
                }
            })
            .finally(() => {
                btnSave.disabled = false;
                btnText.classList.remove('d-none');
                spinner.classList.add('d-none');
            });
    });

    // ============================================================
    // 6. EXCLUIR REGISTRO (AJAX + ANIMAÇÃO)
    // ============================================================
    document.getElementById('tableBody').addEventListener('click', function(e) {
        const btnDelete = e.target.closest('.btn-delete');
        if (!btnDelete) return;

        const id = btnDelete.getAttribute('data-id');
        const row = btnDelete.closest('tr');

        confirmDelete('Excluir Empresa?', 'Tem certeza que deseja excluir esta empresa? Todos os endereços associados também serão excluídos.')
            .then((confirmed) => {
                if (confirmed) {
                    axios.delete(`{{ url('companies') }}/${id}`)
                        .then(response => {
                            toastSuccess(response.data.message);
                            row.style.transition = 'all 0.4s ease';
                            row.style.opacity = '0';
                            row.style.transform = 'translateX(20px)';
                            
                            setTimeout(() => {
                                window.justDeleted = true;
                                loadData(currentPage);
                            }, 400);
                        })
                        .catch(() => toastError('Erro ao excluir empresa.'));
                }
            });
    });

    // ============================================================
    // 7. ANIMAÇÕES UX
    // ============================================================
    function animateRowsIn() {
        const rows = document.querySelectorAll('#tableBody tr');
        rows.forEach((row, index) => {
            row.style.opacity = '0';
            row.style.transform = 'translateY(10px)';
            row.style.transition = 'all 0.3s ease';
            
            setTimeout(() => {
                row.style.opacity = '1';
                row.style.transform = 'translateY(0)';
                
                if (window.justInsertedId && row.getAttribute('data-id') == window.justInsertedId) {
                    row.style.backgroundColor = 'rgba(16, 185, 129, 0.1)';
                    setTimeout(() => {
                        row.style.transition = 'background-color 1s ease';
                        row.style.backgroundColor = 'transparent';
                    }, 1500);
                    window.justInsertedId = null;
                }
            }, index * 50);
        });
    }

    // Carga inicial
    loadData(1);
});
</script>
@endpush