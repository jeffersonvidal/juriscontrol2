<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: create_company_addresses_table
 * --------------------------------------------------------
 * Cria a tabela de endereços das empresas.
 * Uma empresa pode ter múltiplos endereços (matriz, filiais, etc).
 * 
 * Regras do playbook:
 *  - company_id com foreign key (multi-tenancy)
 *  - Soft deletes quando aplicável
 *  - Índices compostos para performance
 *  - Campos created_at e updated_at
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('company_addresses')) {
            Schema::create('company_addresses', function (Blueprint $table) {
                $table->id();
                $table->foreignId('company_id')->comment('ID da Empresa associada')->constrained('companies')->cascadeOnDelete();
                $table->string('label')->nullable()->comment('Residencial/Comercial/Parentes');
                $table->string('street')->comment('Rua');
                $table->string('number')->nullable()->comment('Nº da casa');
                $table->string('complement')->nullable()->comment('Complemento');
                $table->string('district')->nullable()->comment('Bairro');
                $table->string('city')->comment('Cidade');
                $table->string('state')->comment('Estado/UF');
                $table->string('zip_code')->comment('CEP');
                $table->string('country')->default('Brasil')->comment('País');
                $table->boolean('is_default')->default(false)->comment('Endereço principal');
                $table->timestamps();
                $table->softDeletes();

                // Índice composto para performance (regra do playbook)
                $table->index(['company_id', 'is_default'], 'idx_addresses_company_default');
            });
        }
    }

    public function down(): void
    {
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('company_addresses');
        Schema::enableForeignKeyConstraints();
    }
};