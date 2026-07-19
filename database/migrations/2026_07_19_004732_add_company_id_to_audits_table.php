<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: add_company_id_to_audits_table
 * --------------------------------------------------------
 * ALTER TABLE: adiciona company_id na tabela audits.
 * IMPORTANTE: roda DEPOIS de create_companies_table.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('audits', function (Blueprint $table) {
            $table->foreignId('company_id')
                  ->nullable()
                  ->after('user_id')
                  ->constrained('companies')
                  ->nullOnDelete();

            $table->index(
                ['company_id', 'auditable_type', 'created_at'],
                'idx_audits_tenant_type_date'
            );
        });
    }

    public function down(): void
    {
        Schema::table('audits', function (Blueprint $table) {
            $table->dropIndex('idx_audits_tenant_type_date');
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
        });
    }
};