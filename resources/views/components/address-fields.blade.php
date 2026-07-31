{{-- Componente de campos de endereço com auto-preenchimento via CEP --}}
@props(['prefix' => ''])

<div class="row g-3">
    {{-- Campo CEP --}}
    <div class="col-md-3">
        <label for="{{ $prefix }}cep" class="form-label">
            CEP <span class="text-danger">*</span>
        </label>
        <div class="input-group">
            <input 
                type="text" 
                class="form-control" 
                id="{{ $prefix }}cep" 
                name="{{ $prefix }}cep"
                placeholder="00000-000"
                maxlength="9"
                required
            >
            <button 
                class="btn btn-outline-secondary" 
                type="button" 
                id="{{ $prefix }}btn-buscar-cep"
                title="Buscar endereço"
            >
                <i data-lucide="search" class="lucide-sm"></i>
            </button>
        </div>
        <div class="invalid-feedback" id="{{ $prefix }}cep-error"></div>
    </div>

    {{-- Campo Logradouro --}}
    <div class="col-md-7">
        <label for="{{ $prefix }}logradouro" class="form-label">
            Logradouro <span class="text-danger">*</span>
        </label>
        <input 
            type="text" 
            class="form-control" 
            id="{{ $prefix }}logradouro" 
            name="{{ $prefix }}logradouro"
            required
        >
    </div>

    {{-- Campo Número --}}
    <div class="col-md-2">
        <label for="{{ $prefix }}numero" class="form-label">
            Número <span class="text-danger">*</span>
        </label>
        <input 
            type="text" 
            class="form-control" 
            id="{{ $prefix }}numero" 
            name="{{ $prefix }}numero"
            required
        >
    </div>

    {{-- Campo Complemento --}}
    <div class="col-md-4">
        <label for="{{ $prefix }}complemento" class="form-label">Complemento</label>
        <input 
            type="text" 
            class="form-control" 
            id="{{ $prefix }}complemento" 
            name="{{ $prefix }}complemento"
        >
    </div>

    {{-- Campo Bairro --}}
    <div class="col-md-4">
        <label for="{{ $prefix }}bairro" class="form-label">
            Bairro <span class="text-danger">*</span>
        </label>
        <input 
            type="text" 
            class="form-control" 
            id="{{ $prefix }}bairro" 
            name="{{ $prefix }}bairro"
            required
        >
    </div>

    {{-- Campo Cidade --}}
    <div class="col-md-3">
        <label for="{{ $prefix }}cidade" class="form-label">
            Cidade <span class="text-danger">*</span>
        </label>
        <input 
            type="text" 
            class="form-control" 
            id="{{ $prefix }}cidade" 
            name="{{ $prefix }}cidade"
            required
            readonly
        >
    </div>

    {{-- Campo Estado --}}
    <div class="col-md-1">
        <label for="{{ $prefix }}estado" class="form-label">
            UF <span class="text-danger">*</span>
        </label>
        <input 
            type="text" 
            class="form-control" 
            id="{{ $prefix }}estado" 
            name="{{ $prefix }}estado"
            maxlength="2"
            required
            readonly
        >
    </div>
</div>

@push('scripts')
<script>
    // Função global para inicializar busca de CEP (window.funcao conforme playbook)
    window.initCepSearch{{ ucfirst($prefix) }} = function() {
        const prefix = '{{ $prefix }}';
        const cepInput = document.getElementById(`${prefix}cep`);
        const btnBuscar = document.getElementById(`${prefix}btn-buscar-cep`);
        const cepError = document.getElementById(`${prefix}cep-error`);

        if (!cepInput || !btnBuscar) return;

        // Aplica máscara de CEP (00000-000)
        cepInput.addEventListener('input', function(e) {
            let value = e.target.value.replace(/\D/g, '');
            if (value.length > 8) value = value.slice(0, 8);
            
            if (value.length > 5) {
                value = value.replace(/(\d{5})(\d)/, '$1-$2');
            }
            
            e.target.value = value;
        });

        // Busca CEP ao perder foco (evento blur)
        cepInput.addEventListener('blur', async function() {
            await buscarCep(prefix);
        });

        // Busca CEP ao clicar no botão
        btnBuscar.addEventListener('click', async function() {
            await buscarCep(prefix);
        });

        // Função de busca de CEP via AJAX
        async function buscarCep(prefix) {
            const cepInput = document.getElementById(`${prefix}cep`);
            const cep = cepInput.value.replace(/\D/g, '');
            const cepError = document.getElementById(`${prefix}cep-error`);

            // Limpa erros anteriores
            cepInput.classList.remove('is-invalid');
            cepError.textContent = '';

            // Valida se tem 8 dígitos
            if (cep.length !== 8) {
                cepInput.classList.add('is-invalid');
                cepError.textContent = 'CEP deve ter 8 dígitos.';
                return;
            }

            // Desabilita botão e mostra loading
            const btnBuscar = document.getElementById(`${prefix}btn-buscar-cep`);
            btnBuscar.disabled = true;
            btnBuscar.innerHTML = '<span class="spinner-border spinner-border-sm"></span>';

            try {
                // Requisição AJAX via Axios
                const response = await axios.post('{{ route("cep.buscar") }}', {
                    cep: cep,
                    _token: '{{ csrf_token() }}'
                });

                if (response.data.success) {
                    const endereco = response.data.dados;
                    
                    // Preenche os campos automaticamente
                    document.getElementById(`${prefix}logradouro`).value = endereco.logradouro || '';
                    document.getElementById(`${prefix}complemento`).value = endereco.complemento || '';
                    document.getElementById(`${prefix}bairro`).value = endereco.bairro || '';
                    document.getElementById(`${prefix}cidade`).value = endereco.cidade || '';
                    document.getElementById(`${prefix}estado`).value = endereco.estado || '';

                    // Foca no campo número após preenchimento automático
                    document.getElementById(`${prefix}numero`).focus();

                } else {
                    throw new Error(response.data.message || 'CEP não encontrado');
                }

            } catch (error) {
                // Trata erro com SweetAlert2
                Swal.fire({
                    icon: 'warning',
                    title: 'CEP não encontrado',
                    text: error.response?.data?.message || 'Verifique o CEP digitado ou preencha manualmente.',
                    timer: 3000,
                    showConfirmButton: false
                });

                // Marca campo como inválido
                cepInput.classList.add('is-invalid');
                cepError.textContent = 'CEP não encontrado. Preencha manualmente.';

            } finally {
                // Reabilita botão
                btnBuscar.disabled = false;
                btnBuscar.innerHTML = '<i data-lucide="search" class="lucide-sm"></i>';
                
                // Re-renderiza ícones Lucide (se necessário)
                if (window.lucide) {
                    lucide.createIcons();
                }
            }
        }
    };

    // Inicializa automaticamente quando o DOM estiver pronto
    document.addEventListener('DOMContentLoaded', function() {
        window.initCepSearch{{ ucfirst($prefix) }}();
    });
</script>
@endpush