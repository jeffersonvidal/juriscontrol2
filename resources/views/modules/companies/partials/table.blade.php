@forelse($companies as $company)
<tr data-id="{{ $company->id }}" class="border-bottom border-jc">
    <td class="ps-4 fw-medium">{{ $company->trade_name }}</td>
    <td class="text-muted-custom">{{ $company->corporate_reason }}</td>
    <td>
        <span class="font-monospace" style="font-size: 0.85rem;">{{ $company->cnpj_cpf }}</span>
    </td>
    <td>
        @if($company->is_active)
            <span class="badge-status badge-active">Ativo</span>
        @else
            <span class="badge-status badge-inactive">Inativo</span>
        @endif
    </td>
    <td class="text-end pe-4">
        <div class="dropdown">
            <button class="btn btn-sm btn-link text-muted-custom p-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i data-lucide="more-vertical" class="icon-sm"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm">
                @can('companies.update')
                <li>
                    <button class="dropdown-item d-flex align-items-center gap-2" 
                            data-bs-toggle="modal" 
                            data-bs-target="#companyModal" 
                            data-action="edit" 
                            data-id="{{ $company->id }}">
                        <i data-lucide="pencil" class="icon-xs"></i> Editar
                    </button>
                </li>
                @endcan
                @can('companies.delete')
                <li><hr class="dropdown-divider"></li>
                <li>
                    <button class="dropdown-item d-flex align-items-center gap-2 text-danger btn-delete" data-id="{{ $company->id }}">
                        <i data-lucide="trash-2" class="icon-xs"></i> Excluir
                    </button>
                </li>
                @endcan
            </ul>
        </div>
    </td>
</tr>
@empty
<tr>
    <td colspan="5" class="text-center py-5 text-muted-custom">
        <i data-lucide="inbox" class="icon-lg mb-2 d-block mx-auto"></i>
        Nenhuma empresa encontrada.
    </td>
</tr>
@endforelse