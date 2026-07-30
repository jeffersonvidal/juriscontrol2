<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete(); // Multi-tenancy
            $table->string('name');
            $table->string('name_slug');
            $table->string('color', 7); // Cor da fonte (Ex: #1A73E8)
            $table->string('bg_color', 7); // Cor de fundo clareada (Ex: #E8F0FE)
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes(); // Soft deletes conforme playbook

            // Índices compostos para performance
            $table->index(['company_id', 'name_slug']);
            $table->index(['company_id', 'is_active']);
            $table->unique(['company_id', 'name']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tags');
    }
};