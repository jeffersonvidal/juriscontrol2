<!-- Modal de Configuração de Credenciais -->
<div class="modal fade" id="credentialsModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light border-0">
                <h5 class="modal-title fw-semibold d-flex align-items-center gap-2">
                    <i data-lucide="upload" style="width: 20px; height: 20px;"></i>
                    Configurar Credenciais do Google Drive
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="credentialsForm" action="javascript:void(0)" onsubmit="event.preventDefault();">
                @csrf
                
                <div class="modal-body">
                    <!-- Abas para escolher método de envio -->
                    <ul class="nav nav-tabs mb-4" id="credentialsTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active d-flex align-items-center gap-2" id="file-tab" 
                                    data-bs-toggle="tab" data-bs-target="#file-pane" type="button" role="tab">
                                <i data-lucide="file-up" style="width: 16px; height: 16px;"></i>
                                Upload de Arquivo
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link d-flex align-items-center gap-2" id="json-tab" 
                                    data-bs-toggle="tab" data-bs-target="#json-pane" type="button" role="tab">
                                <i data-lucide="file-text" style="width: 16px; height: 16px;"></i>
                                Colar JSON
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="credentialsTabsContent">
                        <!-- Tab 1: Upload de Arquivo -->
                        <div class="tab-pane fade show active" id="file-pane" role="tabpanel">
                            <div class="mb-3">
                                <label for="credentials_file" class="form-label fw-medium">
                                    Arquivo de Credenciais (.json)
                                </label>
                                <input class="form-control" type="file" id="credentials_file" 
                                       name="credentials_file" accept=".json,.txt">
                                <div class="form-text">
                                    Selecione o arquivo JSON baixado do Google Cloud Console
                                </div>
                            </div>
                        </div>

                        <!-- Tab 2: Colar JSON -->
                        <div class="tab-pane fade" id="json-pane" role="tabpanel">
                            <div class="mb-3">
                                <label for="credentials_json" class="form-label fw-medium">
                                    Conteúdo do JSON
                                </label>
                                <textarea class="form-control font-monospace" id="credentials_json" 
                                          name="credentials_json" rows="10" 
                                          placeholder='Cole aqui o conteúdo completo do arquivo JSON...'></textarea>
                                <div class="form-text">
                                    Cole o conteúdo completo do arquivo JSON das credenciais
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Campo informativo (readonly) -->
                    <div class="mb-3">
                        <label class="form-label fw-medium">
                            E-mail da Service Account (Extraído automaticamente)
                        </label>
                        <input type="text" class="form-control bg-light" id="service_account_email" 
                               readonly placeholder="Aguardando upload ou colagem do JSON...">
                        <div class="form-text text-primary">
                            <i data-lucide="alert-circle" style="width: 14px; height: 14px; vertical-align: text-bottom;"></i>
                            Você precisará compartilhar a pasta "Clientes" do seu Drive com este e-mail
                        </div>
                    </div>

                    <!-- Alerta de segurança -->
                    <div class="alert alert-warning border-0 d-flex gap-2" role="alert">
                        <i data-lucide="shield-alert" style="width: 20px; height: 20px; flex-shrink: 0;"></i>
                        <div class="small">
                            <strong>Segurança:</strong> As credenciais serão criptografadas antes de serem salvas no banco de dados. 
                            Nunca compartilhe este arquivo JSON com terceiros.
                        </div>
                    </div>
                </div>
                
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary d-flex align-items-center gap-2" id="btnSaveCredentials">
                        <i data-lucide="save" style="width: 18px; height: 18px;"></i>
                        Salvar Credenciais
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    console.log('✅ Script do Modal de Credenciais carregado.');

    // 1. Processar JSON (reutilizável)
    function processJsonContent(jsonString) {
        try {
            const json = JSON.parse(jsonString);
            if (json.client_email) {
                document.getElementById('service_account_email').value = json.client_email;
                console.log('✅ JSON processado com sucesso. Email:', json.client_email);
                return true;
            } else {
                console.warn('⚠️ JSON não contém client_email.');
                return false;
            }
        } catch (err) {
            console.error('❌ Erro ao parsear JSON:', err);
            return false;
        }
    }

    // 2. Listener do Input de Arquivo
    const fileInput = document.getElementById('credentials_file');
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            console.log('📁 Arquivo selecionado:', e.target.files[0]?.name);
            if (e.target.files[0]) {
                // Limpa o textarea para evitar conflito
                document.getElementById('credentials_json').value = '';
                
                const reader = new FileReader();
                reader.onload = function(event) {
                    processJsonContent(event.target.result);
                };
                reader.readAsText(e.target.files[0]);
            } else {
                document.getElementById('service_account_email').value = '';
            }
        });
    }

    // 3. Listener do Textarea JSON
    const jsonTextarea = document.getElementById('credentials_json');
    if (jsonTextarea) {
        jsonTextarea.addEventListener('input', function(e) {
            const content = e.target.value.trim();
            if (content) {
                // Limpa o input de arquivo para evitar conflito
                document.getElementById('credentials_file').value = '';
                processJsonContent(content);
            } else {
                document.getElementById('service_account_email').value = '';
            }
        });
    }

    // 4. Listener de SUBMIT do Formulário (Onde provavelmente estava falhando)
    const form = document.getElementById('credentialsForm');
    if (form) {
        console.log('✅ Formulário encontrado. Anexando listener de submit.');
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            console.log('🚀 Evento de SUBMIT disparado!');

            const fileInputEl = document.getElementById('credentials_file');
            const jsonInputEl = document.getElementById('credentials_json');
            const btnSave = document.getElementById('btnSaveCredentials');

            const hasFile = fileInputEl && fileInputEl.files.length > 0;
            const hasJson = jsonInputEl && jsonInputEl.value.trim() !== '';

            console.log('🔍 Validação Frontend -> Tem arquivo?', hasFile, '| Tem JSON?', hasJson);

            if (!hasFile && !hasJson) {
                console.warn('🚫 Bloqueado: Nenhum campo preenchido.');
                Swal.fire({
                    icon: 'warning',
                    title: 'Atenção',
                    text: 'Você deve enviar o arquivo JSON ou colar o conteúdo.',
                    confirmButtonText: 'OK'
                });
                return; // Para a execução aqui
            }

            console.log('📦 Criando FormData...');
            const formData = new FormData(form);

            console.log('🌐 Iniciando requisição Axios...');
            const originalBtnText = btnSave.innerHTML;
            btnSave.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span> Salvando...';
            btnSave.disabled = true;

            axios.post('/drive-settings', formData, {
                headers: { 'Content-Type': 'multipart/form-data' }
            })
            .then(response => {
                console.log('✅ Resposta do servidor recebida:', response.data);
                if (response.data.success) {
                    bootstrap.Modal.getInstance(document.getElementById('credentialsModal')).hide();
                    Swal.fire({
                        icon: 'success',
                        title: 'Sucesso!',
                        html: `<p class="mb-2">${response.data.message}</p>`,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.reload();
                    });
                } else {
                    Swal.fire({ icon: 'error', title: 'Erro', text: response.data.message });
                }
            })
            .catch(error => {
                console.error('❌ Erro na requisição Axios:', error);
                
                let errorMessage = 'Ocorreu um erro de rede ou no servidor.';
                if (error.response && error.response.data && error.response.data.message) {
                    errorMessage = error.response.data.message;
                } else if (error.message) {
                    errorMessage = error.message;
                }
                
                Swal.fire({
                    icon: 'error',
                    title: 'Erro na Comunicação',
                    text: errorMessage,
                    confirmButtonText: 'OK'
                });
            })
            .finally(() => {
                console.log('🔄 Restaurando estado do botão.');
                btnSave.innerHTML = originalBtnText;
                btnSave.disabled = false;
            });
        });
    } else {
        console.error('❌ CRÍTICO: Formulário com id="credentialsForm" NÃO foi encontrado no DOM!');
    }
</script>