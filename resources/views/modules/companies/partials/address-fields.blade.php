<div class="address-card card-jc p-3 mb-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="mb-0 fw-medium d-flex align-items-center gap-2">
            <i data-lucide="map-pin" class="icon-xs text-primary"></i>
            Endereço
        </h6>
        <button type="button" class="btn btn-sm btn-outline-danger btn-remove-address">
            <i data-lucide="trash-2" class="icon-xs"></i>
        </button>
    </div>
    
    <input type="hidden" name="id">
    
    <div class="row g-3">
        <div class="col-md-4">
            <label class="form-label-jc">Rótulo</label>
            <input type="text" name="label" class="form-control form-control-jc" placeholder="Matriz, Filial, etc">
        </div>
        <div class="col-md-8">
            <label class="form-label-jc">Rua <span class="text-danger">*</span></label>
            <input type="text" name="street" class="form-control form-control-jc" required>
            <div class="invalid-feedback"></div>
        </div>
        <div class="col-md-3">
            <label class="form-label-jc">Número</label>
            <input type="text" name="number" class="form-control form-control-jc">
        </div>
        <div class="col-md-5">
            <label class="form-label-jc">Complemento</label>
            <input type="text" name="complement" class="form-control form-control-jc">
        </div>
        <div class="col-md-4">
            <label class="form-label-jc">Bairro</label>
            <input type="text" name="district" class="form-control form-control-jc">
        </div>
        <div class="col-md-5">
            <label class="form-label-jc">Cidade <span class="text-danger">*</span></label>
            <input type="text" name="city" class="form-control form-control-jc" required>
            <div class="invalid-feedback"></div>
        </div>
        <div class="col-md-3">
            <label class="form-label-jc">Estado <span class="text-danger">*</span></label>
            <input type="text" name="state" class="form-control form-control-jc" required maxlength="2" placeholder="SP">
            <div class="invalid-feedback"></div>
        </div>
        <div class="col-md-4">
            <label class="form-label-jc">CEP <span class="text-danger">*</span></label>
            <input type="text" name="zip_code" class="form-control form-control-jc" required placeholder="00000-000">
            <div class="invalid-feedback"></div>
        </div>
        <div class="col-md-4">
            <label class="form-label-jc">País</label>
            <input type="text" name="country" class="form-control form-control-jc" value="Brasil">
        </div>
        <div class="col-md-4 d-flex align-items-end">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="is_default" value="1">
                <label class="form-check-label">Endereço principal</label>
            </div>
        </div>
    </div>
</div>