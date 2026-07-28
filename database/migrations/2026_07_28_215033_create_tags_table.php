<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Criação da tabela tags com todos os campos necessários
     * e índices para performance
     */
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            // Chave primária
            $table->id();
            
            // Company ID para multi-tenancy (obrigatório)
            $table->foreignId('company_id')
                  ->constrained('companies')
                  ->onDelete('cascade')
                  ->comment('ID da empresa (tenant)');
            
            // Nome da tag
            $table->string('name', 100)
                  ->comment('Nome da tag');
            
            // Slug do nome para URLs amigáveis
            $table->string('name_slug', 120)
                  ->index()
                  ->comment('Slug do nome da tag');
            
            // Cor da fonte (cor base escolhida pelo usuário)
            $table->string('color', 7)
                  ->default('#000000')
                  ->comment('Cor da fonte em hexadecimal');
            
            // Cor de fundo (clara derivada da cor base)
            $table->string('bg_color', 7)
                  ->default('#f0f0f0')
                  ->comment('Cor de fundo clara derivada da cor base');
            
            // Status da tag (ativa/inativa)
            $table->boolean('is_active')
                  ->default(true)
                  ->index()
                  ->comment('Status da tag: ativo/inativo');
            
            // Timestamps
            $table->timestamps();
            
            // Soft deletes para exclusão lógica
            $table->softDeletes();
            
            // Índices compostos para performance
            $table->index(['company_id', 'name_slug'], 'tags_company_slug_index');
            $table->index(['company_id', 'is_active'], 'tags_company_active_index');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tags');
    }
};