<?php

namespace App\Modules\Core\Validators;

/**
 * Classe CnpjValidator
 * 
 * Responsabilidade Única (SRP): Validar a estrutura e os dígitos verificadores 
 * de CNPJs, suportando tanto o formato numérico tradicional quanto o novo formato alfanumérico.
 * 
 * Baseado na documentação técnica do SERPRO/Receita Federal para CNPJ Alfanumérico.
 */
class CnpjValidator
{
    /**
     * Pesos para o cálculo do primeiro dígito verificador (12 caracteres base).
     * Distribuídos da direita para a esquerda (2 a 9, reiniciando após o 8º).
     */
    private const WEIGHTS_DV1 = [5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

    /**
     * Pesos para o cálculo do segundo dígito verificador (12 caracteres base + 1º DV).
     */
    private const WEIGHTS_DV2 = [6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2];

    /**
     * Valida se o CNPJ fornecido é válido (numérico ou alfanumérico).
     *
     * @param string $cnpj O CNPJ a ser validado (com ou sem máscara).
     * @return bool True se for válido, False caso contrário.
     */
    public static function isValid(string $cnpj): bool
    {
        // Guard Clause 1: Remove qualquer caractere que não seja alfanumérico
        $cleanCnpj = self::clean($cnpj);

        // Guard Clause 2: O CNPJ deve ter exatamente 14 caracteres
        if (strlen($cleanCnpj) !== 14) {
            return false;
        }

        // Guard Clause 3: Rejeita sequências óbvias de caracteres repetidos (ex: "00000000000000" ou "AAAAAAAAAAAA00")
        if (strlen(str_repeat(substr($cleanCnpj, 0, 1), 14)) === 14) {
            return false;
        }

        // Guard Clause 4: Os 12 primeiros caracteres devem ser alfanuméricos (A-Z, 0-9)
        // Os 2 últimos caracteres (DVs) DEVEM ser estritamente numéricos (0-9)
        if (!preg_match('/^[A-Z0-9]{12}[0-9]{2}$/', $cleanCnpj)) {
            return false;
        }

        // Separa a base (12 chars) dos dígitos verificadores informados (2 chars)
        $base = substr($cleanCnpj, 0, 12);
        $dvInformado1 = (int) substr($cleanCnpj, 12, 1);
        $dvInformado2 = (int) substr($cleanCnpj, 13, 1);

        // Calcula o primeiro dígito verificador
        $dvCalculado1 = self::calculateDigit($base, self::WEIGHTS_DV1);

        // Se o primeiro DV informado não bater com o calculado, já falha (Fail Fast)
        if ($dvInformado1 !== $dvCalculado1) {
            return false;
        }

        // Calcula o segundo dígito verificador (incluindo o 1º DV na base)
        $baseComDv1 = $base . $dvCalculado1;
        $dvCalculado2 = self::calculateDigit($baseComDv1, self::WEIGHTS_DV2);

        // Retorna true apenas se o segundo DV também conferir
        return $dvInformado2 === $dvCalculado2;
    }

    /**
     * Remove caracteres especiais da string, mantendo apenas letras e números,
     * e converte tudo para maiúsculas para padronização.
     *
     * @param string $cnpj
     * @return string
     */
    private static function clean(string $cnpj): string
    {
        // Remove tudo que não for letra ou número e converte para upper case
        return strtoupper(preg_replace('/[^A-Za-z0-9]/', '', $cnpj));
    }

    /**
     * Calcula um dígito verificador baseado na base fornecida e nos pesos.
     *
     * @param string $base A string base (12 ou 13 caracteres).
     * @param array $weights O array de pesos correspondente.
     * @return int O dígito verificador calculado (0-9).
     */
    private static function calculateDigit(string $base, array $weights): int
    {
        $sum = 0;

        // Percorre cada caractere da base para calcular o somatório
        for ($i = 0; $i < strlen($base); $i++) {
            // Obtém o valor numérico do caractere (0-9 para dígitos, 17-42 para letras A-Z)
            $charValue = self::getCharValue($base[$i]);
            
            // Multiplica pelo peso correspondente e acumula
            $sum += $charValue * $weights[$i];
        }

        // Obtém o resto da divisão por 11 (Módulo 11)
        $remainder = $sum % 11;

        // Regra do SERPRO: Se o resto for 0 ou 1, o DV é 0. Caso contrário, DV = 11 - resto.
        return ($remainder < 2) ? 0 : 11 - $remainder;
    }

    /**
     * Converte um caractere alfanumérico em seu valor numérico para o cálculo do DV.
     * Segue a regra do SERPRO: subtrair 48 do valor ASCII do caractere.
     * Ex: '0' (ASCII 48) -> 0 | '9' (ASCII 57) -> 9 | 'A' (ASCII 65) -> 17
     *
     * @param string $char Um único caractere.
     * @return int O valor numérico correspondente.
     */
    private static function getCharValue(string $char): int
    {
        return ord($char) - 48;
    }
}