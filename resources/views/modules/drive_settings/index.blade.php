@extends('layouts.app')

@section('title', 'Configurações Google Drive')

@section('content')
    <div class="container-fluid py-4">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item active" aria-current="page">Configurações Google Drive</li>
            </ol>
        </nav>

        <!-- Header com botão de ação -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h3 mb-1 fw-semibold">Configurações Google Drive</h1>
                <p class="text-muted mb-0">Gerencie as credenciais de integração com o Google Drive</p>
            </div>

            @can('drive_settings.create')
                <button type="button" class="btn btn-primary d-flex align-items-center gap-2" onclick="openCredentialsModal()">
                    <i data-lucide="upload" style="width: 18px; height: 18px;"></i>
                    Configurar Credenciais
                </button>
            @endcan
        </div>

        <!-- Card de Status -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center gap-3">
                        <div class="rounded-circle d-flex align-items-center justify-content-center"
                            style="width: 48px; height: 48px; background-color: {{ $credentials ? 'var(--bs-success-bg-subtle)' : 'var(--bs-secondary-bg-subtle)' }};">
                            <i data-lucide="{{ $credentials ? 'check-circle' : 'alert-circle' }}"
                                style="width: 24px; height: 24px; color: {{ $credentials ? 'var(--bs-success)' : 'var(--bs-secondary)' }};"></i>
                        </div>
                        <div>
                            <h5 class="mb-1 fw-semibold">
                                {{ $credentials ? 'Credenciais Configuradas' : 'Credenciais Não Configuradas' }}</h5>
                            <p class="text-muted mb-0 small">
                                <!-- Exemplo de como deve ficar a verificação na view -->
                                @if($credentials)
                                    <p class="text-muted mb-0 small">
                                        Última atualização: {{ $credentials->updated_at->format('d/m/Y H:i') }}
                                    </p>
                                @endif
                            </p>
                        </div>
                    </div>

                    @if($credentials)
                        <div class="d-flex gap-2">
                            @can('drive_settings.edit')
                                <button type="button" class="btn btn-outline-primary btn-sm d-flex align-items-center gap-2"
                                    onclick="testConnection()">
                                    <i data-lucide="wifi" style="width: 16px; height: 16px;"></i>
                                    Testar Conexão
                                </button>
                            @endcan

                            @can('drive_settings.delete')
                                <button type="button" class="btn btn-outline-danger btn-sm d-flex align-items-center gap-2"
                                    onclick="deleteCredentials({{ $credentials->id }})">
                                    <i data-lucide="trash-2" style="width: 16px; height: 16px;"></i>
                                    Remover
                                </button>
                            @endcan
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Card de Instruções -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-white border-bottom">
                <h5 class="mb-0 fw-semibold d-flex align-items-center gap-2">
                    <i data-lucide="info" style="width: 20px; height: 20px;"></i>
                    Como Configurar
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="alert alert-info border-0 mb-4" role="alert">
                    <div class="d-flex gap-2">
                        <i data-lucide="lightbulb" style="width: 20px; height: 20px; flex-shrink: 0;"></i>
                        <div>
                            <strong>Passo a passo para obter as credenciais:</strong>
                            <ol class="mb-0 mt-2">
                                <li>Acesse o <a href="https://console.cloud.google.com/" target="_blank">Google Cloud
                                        Console</a></li>
                                <li>Crie um projeto ou selecione um existente</li>
                                <li>Habilite a <strong>Google Drive API</strong></li>
                                <li>Vá em <strong>"Credenciais"</strong> → <strong>"Criar Credencial"</strong> →
                                    <strong>"Conta de Serviço"</strong></li>
                                <li>Crie uma nova conta de serviço e gere uma chave no formato <strong>JSON</strong></li>
                                <li>Faça o download do arquivo JSON</li>
                                <li>No seu Google Drive, compartilhe a pasta <strong>"Clientes"</strong> com o e-mail da
                                    conta de serviço (permissão: <strong>Editor</strong>)</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Incluir Modal -->
    @include('modules.drive_settings.partials.modal')

@endsection

@push('scripts')
    <script>
        // Função para abrir o modal de credenciais
        window.openCredentialsModal = function () {
            const modalEl = document.getElementById('credentialsModal');
            const modal = new bootstrap.Modal(modalEl);
            const form = document.getElementById('credentialsForm');

            // Limpar formulário
            form.reset();
            form.classList.remove('was-validated');
            document.getElementById('service_account_email').value = '';

            // Abrir modal com delay para evitar flicker
            setTimeout(() => {
                modal.show();
                if (window.lucide) lucide.createIcons();
            }, 50);
        };

        // Função para testar conexão
        window.testConnection = function () {
            Swal.fire({
                title: 'Testando Conexão',
                text: 'Verificando se as credenciais estão funcionando corretamente...',
                icon: 'info',
                showConfirmButton: false,
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            axios.post('{{ route("drive_settings.test_connection") }}')
                .then(response => {
                    if (response.data.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Conexão Bem-Sucedida!',
                            html: `
                                <p class="mb-0">As credenciais estão funcionando corretamente.</p>
                                <p class="text-muted small mt-2 mb-0">E-mail da conta: <strong>${response.data.dados.email}</strong></p>
                            `,
                            confirmButtonText: 'OK'
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Falha na Conexão',
                            text: response.data.message,
                            confirmButtonText: 'OK'
                        });
                    }
                })
                .catch(error => {
                    console.error(error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Erro',
                        text: 'Não foi possível testar a conexão. Verifique os logs para mais detalhes.',
                        confirmButtonText: 'OK'
                    });
                });
        };

        // Função para deletar credenciais
        window.deleteCredentials = function (optionId) {
            Swal.fire({
                title: 'Tem certeza?',
                text: 'Esta ação removerá as credenciais e desativará a integração com o Google Drive.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Sim, remover',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('_method', 'DELETE');
                    formData.append('_token', '{{ csrf_token() }}');

                    axios.post(`/drive-settings/${optionId}`, formData)
                        .then(response => {
                            if (response.data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Removido!',
                                    text: response.data.message,
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.reload();
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Erro',
                                    text: response.data.message,
                                    confirmButtonText: 'OK'
                                });
                            }
                        })
                        .catch(error => {
                            console.error(error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Erro',
                                text: 'Não foi possível remover as credenciais.',
                                confirmButtonText: 'OK'
                            });
                        });
                }
            });
        };
    </script>
@endpush