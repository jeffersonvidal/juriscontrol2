<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_options', function (Blueprint $table) {
            $table->id();
            // company_id nullable permite opções globais (padrão do sistema) e específicas da empresa
            $table->foreignId('company_id')->nullable()->constrained('companies')->cascadeOnDelete();
            $table->string('option_name')->comment('Nome identificador da opção');
            $table->text('option_value')->nullable()->comment('Valor armazenado da opção');
            $table->text('option_description')->nullable()->comment('Descrição da opção para documentação UI');
            $table->boolean('option_status')->default(true)->comment('Status da opção (ativo/inativo)');
            $table->timestamps();
            $table->softDeletes();

            // Índice único composto: uma empresa não pode ter duas opções com o mesmo nome
            // Permite que company_id NULL (global) e company_id X (específico) coexistam para o mesmo option_name
            $table->unique(['company_id', 'option_name']);
            
            // Índices compostos para performance em filtros
            $table->index(['company_id', 'option_status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_options');
    }
};