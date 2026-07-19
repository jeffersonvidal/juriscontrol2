<?php

namespace App\Modules\Companies\Repositories;

use App\Models\CompanyAddress;
use Illuminate\Database\Eloquent\Collection;

/**
 * CompanyAddressRepository
 * --------------------------------------------------------
 * Centraliza o acesso a dados de endereços das empresas.
 */
class CompanyAddressRepository
{
    /**
     * Lista endereços de uma empresa.
     */
    public function getByCompany(int $companyId): Collection
    {
        return CompanyAddress::where('company_id', $companyId)
                             ->orderBy('is_default', 'desc')
                             ->orderBy('id')
                             ->get();
    }

    /**
     * Cria um novo endereço.
     */
    public function create(array $data): CompanyAddress
    {
        return CompanyAddress::create($data);
    }

    /**
     * Atualiza um endereço existente.
     */
    public function update(CompanyAddress $address, array $data): bool
    {
        return $address->update($data);
    }

    /**
     * Exclui um endereço (soft delete).
     */
    public function delete(CompanyAddress $address): bool
    {
        return $address->delete();
    }

    /**
     * Remove todos os endereços de uma empresa (exceto os marcados).
     */
    public function deleteExcept(int $companyId, array $keepIds): int
    {
        return CompanyAddress::where('company_id', $companyId)
                             ->whereNotIn('id', $keepIds)
                             ->delete();
    }

    /**
     * Define um endereço como padrão e remove o flag dos outros.
     */
    public function setAsDefault(int $companyId, int $addressId): void
    {
        // Remove o flag de todos os endereços da empresa
        CompanyAddress::where('company_id', $companyId)
                      ->update(['is_default' => false]);

        // Define o endereço especificado como padrão
        CompanyAddress::where('id', $addressId)
                      ->where('company_id', $companyId)
                      ->update(['is_default' => true]);
    }
}