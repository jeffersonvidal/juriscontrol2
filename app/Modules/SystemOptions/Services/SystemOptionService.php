<?php
namespace App\Modules\SystemOptions\Services;

use App\Modules\SystemOptions\Repositories\SystemOptionRepository;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SystemOptionService
{
    public function __construct(protected SystemOptionRepository $repository) {}

    /**
     * Retorna as opções da empresa com filtros aplicados
     */
    public function getCompanyOptions(array $filters = [])
    {
        $companyId = Auth::user()->company_id;
        return $this->repository->getByCompanyId($companyId, $filters);
    }

    public function updateOption(int $id, array $data)
    {
        try {
            $companyId = Auth::user()->company_id;
            return $this->repository->updateOption($id, $companyId, $data);
        } catch (\Exception $e) {
            Log::error('Erro ao atualizar System Option: ' . $e->getMessage());
            throw $e;
        }
    }
}