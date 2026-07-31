<?php

namespace App\Modules\Core\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Exception;

/**
 * Classe CepService
 * 
 * Responsabilidade Única (SRP): Consultar a API ViaCEP para obter dados de endereço.
 * Implementa cache para evitar requisições repetidas e tratamento robusto de erros.
 */
class CepService
{
    /**
     * URL base da API ViaCEP
     */
    private const VIACEP_BASE_URL = 'https://viacep.com.br/ws/';

    /**
     * Tempo de cache em segundos (24 horas)
     */
    private const CACHE_TTL = 86400;

    /**
     * Busca endereço pelo CEP.
     *
     * @param string $cep CEP formatado ou não (ex: "01001000" ou "01001-000")
     * @return array Array associativo com os dados do endereço ou array vazio se não encontrado
     * 
     * Estrutura de retorno:
     * [
     *     'cep' => '01001000',
     *     'logradouro' => 'Praça da Sé',
     *     'complemento' => 'lado ímpar',
     *     'bairro' => 'Sé',
     *     'cidade' => 'São Paulo',
     *     'estado' => 'SP',
     *     'ibge' => '3550308',
     *     'gia' => '1004',
     *     'ddd' => '11',
     *     'siafi' => '7107'
     * ]
     */
    public function buscarEndereco(string $cep): array
    {
        // Guard Clause 1: Remove caracteres não numéricos
        $cleanCep = $this->cleanCep($cep);

        // Guard Clause 2: Valida tamanho do CEP
        if (strlen($cleanCep) !== 8) {
            Log::warning('CEP inválido fornecido', ['cep' => $cep]);
            return [];
        }

        // Tenta recuperar do cache primeiro (reduz latência e chamadas à API externa)
        $cacheKey = "cep_{$cleanCep}";
        
        return Cache::remember($cacheKey, self::CACHE_TTL, function () use ($cleanCep, $cep) {
            try {
                // Faz requisição HTTP com timeout de 5 segundos
                $response = Http::timeout(5)->get(self::VIACEP_BASE_URL . "{$cleanCep}/json/");

                // Guarda Clause 3: Verifica se a requisição foi bem-sucedida
                if (!$response->successful()) {
                    Log::error('Falha na API ViaCEP', [
                        'cep' => $cep,
                        'status' => $response->status(),
                        'body' => $response->body()
                    ]);
                    return [];
                }

                $data = $response->json();

                // Guard Clause 4: Verifica se o CEP foi encontrado na API
                if (isset($data['erro']) && $data['erro'] === true) {
                    Log::info('CEP não encontrado na ViaCEP', ['cep' => $cep]);
                    return [];
                }

                // Retorna dados estruturados e normalizados
                return [
                    'cep' => $data['cep'] ?? '',
                    'logradouro' => $data['logradouro'] ?? '',
                    'complemento' => $data['complemento'] ?? '',
                    'bairro' => $data['bairro'] ?? '',
                    'cidade' => $data['localidade'] ?? '',
                    'estado' => $data['uf'] ?? '',
                    'ibge' => $data['ibge'] ?? '',
                    'gia' => $data['gia'] ?? '',
                    'ddd' => $data['ddd'] ?? '',
                    'siafi' => $data['siafi'] ?? '',
                ];

            } catch (Exception $e) {
                // Log do erro com contexto completo para debugging
                Log::error('Erro ao consultar ViaCEP', [
                    'cep' => $cep,
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ]);
                
                return [];
            }
        });
    }

    /**
     * Remove caracteres não numéricos do CEP.
     *
     * @param string $cep
     * @return string
     */
    private function cleanCep(string $cep): string
    {
        return preg_replace('/\D/', '', $cep);
    }

    /**
     * Verifica se um CEP é válido (apenas estrutura, não consulta API).
     *
     * @param string $cep
     * @return bool
     */
    public static function isValidFormat(string $cep): bool
    {
        $cleanCep = preg_replace('/\D/', '', $cep);
        return strlen($cleanCep) === 8;
    }
}