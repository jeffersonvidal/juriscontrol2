<?php

namespace App\Modules\Tags\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use App\Modules\Tags\Http\Requests\TagRequest;
use App\Modules\Tags\Services\TagService;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function __construct(protected TagService $service) 
    {
        // Aplica o TagPolicy automaticamente em todas as rotas de recurso
        $this->authorizeResource(Tag::class, 'tag');
    }

    /**
     * Exibe a view principal ou retorna dados filtrados via AJAX.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $tags = $this->service->getAll(
                $request->only(['name', 'is_active']), 
                $request->get('per_page', 10)
            );
            
            return response()->json([
                'html' => view('modules.tags.partials.table', compact('tags'))->render(),
                'pagination' => $tags->links()->toHtml(),
                'total' => $tags->total()
            ]);
        }

        $tags = $this->service->getAll([], 10);
        return view('modules.tags.index', compact('tags'));
    }

    /**
     * Armazena uma nova tag no banco de dados.
     */
    public function store(TagRequest $request)
    {
        try {
            $tag = $this->service->create(
                $request->validated(), 
                auth()->user()->company_id
            );
            
            return response()->json([
                'success' => true,
                'message' => 'Tag criada com sucesso!',
                'tag' => $tag
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro ao criar tag: ' . $e->getMessage(), [
                'user_id' => auth()->id()
            ]);
            
            return response()->json([
                'success' => false, 
                'message' => 'Erro ao criar tag. Tente novamente.'
            ], 500);
        }
    }

    /**
     * Retorna os dados de uma tag para edição.
     */
    public function edit(Tag $tag)
    {
        return response()->json($tag);
    }

    /**
     * Atualiza uma tag existente.
     */
    public function update(TagRequest $request, Tag $tag)
    {
        try {
            $updatedTag = $this->service->update($tag->id, $request->validated());
            
            return response()->json([
                'success' => true,
                'message' => 'Tag atualizada com sucesso!',
                'tag' => $updatedTag
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro ao atualizar tag: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'tag_id' => $tag->id
            ]);
            
            return response()->json([
                'success' => false, 
                'message' => 'Erro ao atualizar tag. Tente novamente.'
            ], 500);
        }
    }

    /**
     * Remove uma tag do banco de dados (soft delete).
     */
    public function destroy(Tag $tag)
    {
        try {
            $this->service->delete($tag->id);
            
            return response()->json([
                'success' => true,
                'message' => 'Tag excluída com sucesso!'
            ]);
        } catch (\Exception $e) {
            \Log::error('Erro ao excluir tag: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'tag_id' => $tag->id
            ]);
            
            return response()->json([
                'success' => false, 
                'message' => 'Erro ao excluir tag. Tente novamente.'
            ], 500);
        }
    }
}