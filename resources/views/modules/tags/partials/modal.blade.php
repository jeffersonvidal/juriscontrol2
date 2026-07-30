<div class="modal fade" id="tagModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-semibold" id="modalTitle">Nova Tag</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body pt-3">
                <form id="tagForm" action="javascript:void(0)" onsubmit="event.preventDefault();" data-action=""
                    data-method="">
                    <input type="hidden" id="tag_id" name="id">

                    {{-- Preview da Tag --}}
                    <div class="text-center mb-4">
                        <div id="tag-preview" class="tag-badge d-inline-block"
                            style="font-size: 1rem; padding: 0.5em 1.5em; color: #6c757d; background-color: #f8f9fa;">
                            Exemplo
                        </div>
                        <small class="text-muted d-block mt-2">Pré-visualização da Tag</small>
                    </div>

                    {{-- Campo Nome --}}
                    <div class="mb-3">
                        <label for="name" class="form-label fw-medium small">Título da Tag</label>
                        <input type="text" class="form-control" id="name" name="name"
                            placeholder="Ex: Urgente, Cliente VIP" required>
                        <div class="invalid-feedback">Por favor, informe o título.</div>
                    </div>

                    {{-- Linha: Cor da Fonte e Ativar Tag (duas colunas separadas) --}}
                    <div class="row g-3 mb-3">
                        {{-- Coluna Esquerda: Cor da Fonte --}}
                        <div class="col-md-6">
                            <label for="color" class="form-label fw-medium small">Cor da Fonte</label>
                            <input type="color" class="form-control form-control-color w-100" id="color" name="color"
                                value="#1A73E8" required title="Escolha a cor da fonte" style="height: 38px;">
                            
                            <div class="invalid-feedback">Selecione uma cor válida.</div>
                        </div>

                        {{-- Coluna Direita: Ativar Tag --}}
                        <div class="col-md-6">
                            <label class="form-label fw-medium small">Status</label>
                            
                                <div class="mt-2 form-switch">
                                    <label class="form-check-label fw-medium small" for="is_active">Ativar Tag</label>
                                    <input class="form-check-input ms-4" type="checkbox" role="switch" id="is_active"
                                    name="is_active" value="1" checked>
                                </div>
                                
                        </div>
                    </div>

                    {{-- Botões de Ação --}}
                    <div class="d-flex justify-content-end gap-2 mt-4 pt-3 border-top">
                        <button type="button" class="btn btn-light px-4" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary px-4 d-flex align-items-center gap-2">
                            <i class="bi bi-check-lg"></i> Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>