<div class="address-card card-jc p-3 mb-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0 fw-medium d-flex align-items-center gap-2">
            <i data-lucide="map-pin" class="icon-xs" style="color: var(--jc-primary);"></i>
            Endereço
        </h6>
        <button type="button" class="btn btn-sm btn-outline-danger btn-remove-address" title="Remover endereço">
            <i data-lucide="trash-2" class="icon-xs"></i>
        </button>
    </div>
    
    <input type="hidden" name="id">
    
    <div class="row g-3">
        {{-- CEP EM PRIMEIRO --}}
        <div class="col-md-4">
            <label class="form-label-jc">CEP <span class="text-danger">*</span></label>
            <input type="text" name="zip_code" class="form-control form-control-jc via-cep" 
                   required placeholder="00000-000" maxlength="9" data-index="{{ $index ?? 0 }}">
            <div class="invalid-feedback"></div>
            <small class="text-muted-custom" style="font-size: 0.7rem; margin-top: 0.25rem;">
                <i data-lucide="info" class="icon-xs me-1"></i>
                Digite o CEP para preencher automaticamente
            </small>
        </div>
        
        <div class="col-md-8">
            <label class="form-label-jc">Rua <span class="text-danger">*</span></label>
            <input type="text" name="street" class="form-control form-control-jc address-street" required>
            <div class="invalid-feedback"></div>
        </div>
        
        <div class="col-md-3">
            <label class="form-label-jc">Número</label>
            <input type="text" name="number" class="form-control form-control-jc">
        </div>
        
        <div class="col-md-5">
            <label class="form-label-jc">Complemento</label>
            <input type="text" name="complement" class="form-control form-control-jc address-complement">
        </div>
        
        <div class="col-md-4">
            <label class="form-label-jc">Bairro</label>
            <input type="text" name="district" class="form-control form-control-jc address-district">
        </div>
        
        <div class="col-md-5">
            <label class="form-label-jc">Cidade <span class="text-danger">*</span></label>
            <input type="text" name="city" class="form-control form-control-jc address-city" required>
            <div class="invalid-feedback"></div>
        </div>
        
        <div class="col-md-3">
            <label class="form-label-jc">Estado <span class="text-danger">*</span></label>
            <input type="text" name="state" class="form-control form-control-jc address-state" 
                   required maxlength="2" placeholder="SP">
            <div class="invalid-feedback"></div>
        </div>
        
        <div class="col-md-4">
            <label class="form-label-jc">País</label>
            <input type="text" name="country" class="form-control form-control-jc" value="Brasil">
        </div>
        
        <div class="col-md-4 d-flex align-items-end pb-2">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_default" value="1" id="is_default_{{ $index ?? 0 }}">
                <label class="form-check-label" for="is_default_{{ $index ?? 0 }}">Endereço principal</label>
            </div>
        </div>
    </div>
</div>