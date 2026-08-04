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
            // Nullable para permitir configurações globais (company_id = null)
            $table->foreignId('company_id')->nullable()->constrained('companies')->cascadeOnDelete();
            
            $table->string('option_name')->comment('Chave da configuração (ex: MAIL_MAILER)');
            $table->text('option_value')->nullable()->comment('Valor da configuração');
            $table->text('option_description')->nullable()->comment('Descrição do que a configuração faz');
            $table->boolean('option_status')->default(1)->comment('1=Ativo/Editável, 0=Inativo/Bloqueado');
            
            $table->softDeletes();
            $table->timestamps();

            // Índice composto para performance e unicidade por empresa
            $table->index(['company_id', 'option_name']);
            $table->unique(['company_id', 'option_name'], 'unique_company_option');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('system_options');
    }
};