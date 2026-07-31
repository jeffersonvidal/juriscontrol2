<?php

namespace App\Modules\Core\Validators;

/**
 * Classe CpfValidator
 * 
 * Responsabilidade Única (SRP): Validar a estrutura e os dígitos verificadores de CPFs.
 * Implementa Guard Clauses (Fail Fast) para rejeitar entradas inválidas o mais rápido possível,
 * evitando processamento desnecessário.
 */
class CpfValidator
{
    /**
     * Valida se o CPF fornecido é válido.
     *
     * @param string $cpf O CPF a ser validado (com ou sem máscara).
     * @return bool True se for válido, False caso contrário.
     */
    public static function isValid(string $cpf): bool
    {
        // Guard Clause 1: Remove qualquer caractere que não seja número (pontos, traços, espaços)
        $cleanCpf = self::clean($cpf);

        // Guard Clause 2: O CPF deve ter exatamente 11 dígitos
        if (strlen($cleanCpf) !== 11) {
            return false;
        }

        // Guard Clause 3: Rejeita sequências óbvias de números repetidos (ex: "11111111111", "00000000000")
        // Estes casos passam no cálculo do módulo 11, mas são CPFs matematicamente inválidos.
        if (preg_match('/(\d)\1{10}/', $cleanCpf)) {
            return false;
        }

        // Separa a base (9 dígitos) dos dígitos verificadores informados (2 dígitos)
        $base = substr($cleanCpf, 0, 9);
        $dvInformado1 = (int) substr($cleanCpf, 9, 1);
        $dvInformado2 = (int) substr($cleanCpf, 10, 1);

        // Calcula o primeiro dígito verificador
        $dvCalculado1 = self::calculateDigit($base, 10);

        // Fail Fast: Se o primeiro DV informado não bater com o calculado, já falha
        if ($dvInformado1 !== $dvCalculado1) {
            return false;
        }

        // Calcula o segundo dígito verificador (incluindo o 1º DV na base)
        $baseComDv1 = $base . $dvCalculado1;
        $dvCalculado2 = self::calculateDigit($baseComDv1, 11);

        // Retorna true apenas se o segundo DV também conferir
        return $dvInformado2 === $dvCalculado2;
    }

    /**
     * Remove caracteres especiais da string, mantendo apenas números.
     *
     * @param string $cpf
     * @return string
     */
    private static function clean(string $cpf): string
    {
        // Remove tudo que não for dígito numérico
        return preg_replace('/\D/', '', $cpf);
    }

    /**
     * Calcula um dígito verificador baseado na base fornecida e no peso inicial.
     *
     * @param string $base A string base (9 ou 10 caracteres numéricos).
     * @param int $initialWeight O peso inicial para a multiplicação (10 para o 1º DV, 11 para o 2º DV).
     * @return int O dígito verificador calculado (0-9).
     */
    private static function calculateDigit(string $base, int $initialWeight): int
    {
        $sum = 0;
        $weight = $initialWeight;

        // Percorre cada caractere da base para calcular o somatório
        for ($i = 0; $i < strlen($base); $i++) {
            // Multiplica o valor do dígito pelo peso decrescente e acumula
            $sum += (int) $base[$i] * $weight;
            $weight--;
        }

        // Obtém o resto da divisão por 11 (Módulo 11)
        $remainder = $sum % 11;

        // Regra padrão do CPF: Se o resto for 0 ou 1, o DV é 0. Caso contrário, DV = 11 - resto.
        return ($remainder < 2) ? 0 : 11 - $remainder;
    }
}