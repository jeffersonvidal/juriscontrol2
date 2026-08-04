<div class="modal fade" id="systemOptionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <form id="systemOptionForm" onsubmit="window.saveSystemOption(event)">
                <div class="modal-header border-bottom-0 pb-0">
                    <h5 class="modal-title fw-light">Editar Configuração</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="option_id" name="id">
                    
                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-uppercase text-muted">Chave</label>
                        <input type="text" class="form-control bg-light" id="option_name" name="option_name" readonly>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-uppercase text-muted">Descrição</label>
                        <p id="display_description" class="small text-muted fst-italic mb-0"></p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small text-uppercase text-muted">Novo Valor</label>
                        <input type="text" class="form-control" id="option_value" name="option_value">
                        <!-- ESTA LINHA É CRUCIAL PARA MOSTRAR O ERRO -->
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancelar</button>
                    <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                </div>
            </form>
        </div>
    </div>
</div>