<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: create_audits_table
 * --------------------------------------------------------
 * Migration PADRÃO do laravel-auditing (vendor).
 * NÃO deve conter company_id aqui — será adicionado
 * pela migration separada: add_company_id_to_audits_table.
 *
 * IMPORTANTE: Esta migration roda ANTES de create_companies_table,
 * então NÃO pode ter FK para companies.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('audits', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('user_type')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('event');
            $table->morphs('auditable');
            $table->text('old_values')->nullable();
            $table->text('new_values')->nullable();
            $table->text('url')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->string('user_agent', 1023)->nullable();
            $table->string('tags')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'user_type']);
        });
    }

    public function down(): void
    {
        Schema::drop('audits');
    }
};