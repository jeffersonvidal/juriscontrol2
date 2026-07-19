<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration: add_company_id_to_users_table
 * --------------------------------------------------------
 * ALTER TABLE: adiciona company_id e status na tabela users.
 * IMPORTANTE: roda DEPOIS de create_companies_table.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('company_id')
                  ->nullable()
                  ->after('id')
                  ->constrained('companies')
                  ->nullOnDelete();

            $table->enum('status', ['active', 'inactive'])
                  ->default('active')
                  ->after('company_id');

            $table->index(['company_id', 'status'], 'idx_users_company_status');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('idx_users_company_status');
            $table->dropForeign(['company_id']);
            $table->dropColumn(['company_id', 'status']);
        });
    }
};