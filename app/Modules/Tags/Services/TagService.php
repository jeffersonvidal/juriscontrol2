<?php

namespace App\Modules\Tags\Services;

use App\Models\Tag;
use App\Modules\Tags\Repositories\TagRepository;
use Illuminate\Support\Str;

class TagService
{
    public function __construct(protected TagRepository $repository) {}

    /**
     * Retorna todas as tags paginadas com filtros.
     */
    public function getAll(array $filters = [], int $perPage = 10)
    {
        return $this->repository->paginate($perPage, $filters);
    }

    /**
     * Busca uma tag específica pelo ID.
     */
    public function getById(int $id): Tag
    {
        return $this->repository->findOrFail($id);
    }

    /**
     * Cria uma nova tag com regras de negócio:
     * - Gera o slug automaticamente
     * - Calcula a cor de fundo clareada em 85%
     * - Atribui o company_id do usuário logado
     */
    public function create(array $data, int $companyId): Tag
    {
        $data['company_id'] = $companyId;
        $data['name_slug'] = Str::slug($data['name']);
        $data['bg_color'] = $this->lightenColor($data['color'], 0.85);
        
        return $this->repository->create($data);
    }

    /**
     * Atualiza uma tag existente recalculando slug e cor de fundo.
     */
    public function update(int $id, array $data): Tag
    {
        $tag = $this->repository->findOrFail($id);
        
        $data['name_slug'] = Str::slug($data['name']);
        $data['bg_color'] = $this->lightenColor($data['color'], 0.85);
        
        $this->repository->update($tag, $data);
        
        return $tag->fresh();
    }

    /**
     * Remove uma tag do sistema (soft delete).
     */
    public function delete(int $id): bool
    {
        $tag = $this->repository->findOrFail($id);
        return $this->repository->delete($tag);
    }

    /**
     * Clareia uma cor HEX misturando-a com branco.
     * @param string $hex Cor em formato #RRGGBB
     * @param float $percent Percentual de clareamento (0 a 1)
     */
    protected function lightenColor(string $hex, float $percent): string
    {
        $hex = ltrim($hex, '#');
        
        if (strlen($hex) == 3) {
            $hex = $hex[0] . $hex[0] . $hex[1] . $hex[1] . $hex[2] . $hex[2];
        }

        $r = hexdec(substr($hex, 0, 2));
        $g = hexdec(substr($hex, 2, 2));
        $b = hexdec(substr($hex, 4, 2));

        $r = (int) ($r + (255 - $r) * $percent);
        $g = (int) ($g + (255 - $g) * $percent);
        $b = (int) ($b + (255 - $b) * $percent);

        return sprintf("#%02x%02x%02x", $r, $g, $b);
    }
}