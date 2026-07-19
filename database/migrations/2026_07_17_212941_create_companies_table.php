<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: create_companies_table
 * --------------------------------------------------------
 * Cria a tabela de empresas (tenants) do SaaS.
 * 
 * ALTERAÇÕES em relação à versão anterior:
 *  - trade_name (Nome Fantasia) + trade_name_slug (para URLs amigáveis)
 *  - corporate_reason (Razão Social)
 *  - cnpj_cpf (documento único, 18 chars - aceita CNPJ ou CPF)
 *  - is_active (boolean, substitui o enum 'status')
 *  - user_id (FK para users - responsável pelo cadastro)
 *  - REMOVIDO: softDeletes, trial_ends_at, enum status
 * 
 * IMPORTANTE:
 *  - Esta migration NÃO tem FK para nenhuma outra tabela (exceto users)
 *  - Outras migrations (users, audits) vão referenciar esta tabela
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('companies')) {
            Schema::create('companies', function (Blueprint $table) {
                $table->id();
                $table->string('trade_name')->comment('Nome Fantasia da Companhia/Escritório');
                $table->string('trade_name_slug')->comment('Slug do Nome Fantasia');
                $table->string('corporate_reason')->comment('Razão Social da Companhia/Escritório');
                $table->string('email')->unique()->comment('E-mail de contato');
                $table->string('cnpj_cpf', 18)->unique()->comment('CNPJ/CPF da Companhia/Escritório');
                $table->string('phone', 20)->nullable()->comment('Telefone da Companhia/Escritório');
                $table->unsignedBigInteger('user_id')->nullable()->comment('Usuário responsável pelo cadastro');
                $table->boolean('is_active')->default(true)->comment('Status de ativação da companhia');
                $table->timestamps();

                // Chave estrangeira para users (responsável pelo cadastro)
                $table->foreign('user_id')
                      ->references('id')
                      ->on('users')
                      ->onUpdate('cascade')
                      ->nullOnDelete();

                // Índices compostos para performance (regra do playbook)
                $table->index(['is_active', 'created_at'], 'idx_companies_active_created');
                $table->index(['trade_name_slug'], 'idx_companies_slug');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};