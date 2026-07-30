@foreach($tags as $tag)
<tr data-id="{{ $tag->id }}" class="fade-in-up">
    <td class="ps-4">
        <span class="tag-badge" style="color: {{ $tag->color }}; background-color: {{ $tag->bg_color }};">
            <i class="bi bi-tag-fill"></i>
            {{ $tag->name }}
        </span>
    </td>
    <td class="fw-medium">{{ $tag->name }}</td>
    <td>
        @if($tag->is_active)
            <span class="badge bg-success-subtle text-success rounded-pill px-3 py-2">Ativo</span>
        @else
            <span class="badge bg-danger-subtle text-danger rounded-pill px-3 py-2">Inativo</span>
        @endif
    </td>
    <td class="text-end pe-4">
        @canany(['tags.edit', 'tags.delete', 'tags.view'])
        <div class="dropdown">
            <button class="btn btn-sm btn-light border" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-three-dots-vertical"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                @can('tags.view')
                <li>
                    <a class="dropdown-item d-flex align-items-center gap-2" href="javascript:void(0)" onclick="viewTag({{ $tag->id }})">
                        <i class="bi bi-eye"></i> View
                    </a>
                </li>
                @endcan
                @can('tags.edit')
                <li>
                    <a class="dropdown-item d-flex align-items-center gap-2" href="javascript:void(0)" onclick="editTag({{ $tag->id }})">
                        <i class="bi bi-pencil"></i> Edit
                    </a>
                </li>
                @endcan
                @canany(['tags.edit', 'tags.delete'])
                <li><hr class="dropdown-divider"></li>
                @endcanany
                @can('tags.delete')
                <li>
                    <a class="dropdown-item text-danger d-flex align-items-center gap-2" href="javascript:void(0)" onclick="deleteTag({{ $tag->id }})">
                        <i class="bi bi-trash3"></i> Delete
                    </a>
                </li>
                @endcan
            </ul>
        </div>
        @endcanany
    </td>
</tr>
@endforeach

@if($tags->isEmpty())
<tr>
    <td colspan="4" class="text-center py-5 text-muted">
        <i class="bi bi-inbox" style="font-size: 2.5rem;"></i>
        <p class="mb-0 mt-2">Nenhuma tag encontrada.</p>
    </td>
</tr>
@endif