<div class="modal fade" id="systemOptionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header bg-light">
                <h5 class="modal-title fw-light">Configurar Opção do Sistema</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            
            <form id="systemOptionForm" action="javascript:void(0)" onsubmit="event.preventDefault(); window.submitSystemOptionForm(event);" data-action="" data-method="">
                <div class="modal-body">
                    <div id="error_messages"></div>
                    
                    <input type="hidden" id="system_option_id" name="id" value="">

                    <div class="mb-3">
                        <label for="option_name" class="form-label small text-muted">Nome da Opção (Chave)</label>
                        <input type="text" class="form-control" id="option_name" name="option_name" required placeholder="Ex: MAIL_MAILER">
                    </div>

                    <div class="mb-3">
                        <label for="option_value" class="form-label small text-muted">Valor</label>
                        <input type="text" class="form-control" id="option_value" name="option_value" placeholder="Ex: smtp">
                    </div>

                    <div class="mb-3">
                        <label for="option_description" class="form-label small text-muted">Descrição</label>
                        <textarea class="form-control" id="option_description" name="option_description" rows="2" placeholder="Descreva a finalidade"></textarea>
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="option_status" name="option_status" checked>
                        <label class="form-check-label small" for="option_status">Opção Ativa</label>
                    </div>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-light btn-sm" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary btn-sm">Salvar</button>
                </div>
            </form>
        </div>
    </div>
</div>