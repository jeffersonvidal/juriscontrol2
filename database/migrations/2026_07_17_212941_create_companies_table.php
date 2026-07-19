<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: create_companies_table
 * --------------------------------------------------------
 * Cria a tabela de empresas (tenants) do SaaS.
 * Esta é a tabela RAIZ do multi-tenancy.
 *
 * IMPORTANTE:
 *  - Esta migration NÃO tem FK para nenhuma outra tabela
 *  - Outras migrations (users, audits) vão referenciar esta tabela
 *  - Ordem de execução: roda ANTES das migrations que adicionam company_id
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');                                 // Razão social
            $table->string('trade_name')->nullable();               // Nome fantasia
            $table->string('document', 20)->unique();               // CNPJ
            $table->string('email')->nullable();
            $table->string('phone', 20)->nullable();
            $table->enum('status', ['active', 'inactive'])
                  ->default('active')
                  ->index();                                        // Status da empresa
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Índice composto para performance (regra do playbook)
            $table->index(['status', 'created_at'], 'idx_companies_status_created');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};