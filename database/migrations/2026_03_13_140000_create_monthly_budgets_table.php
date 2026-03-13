<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('monthly_budgets')) {
            Schema::create('monthly_budgets', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->integer('month');
                $table->integer('year');
                $table->decimal('limit_amount', 10, 2);
                $table->timestamps();

                $table->unique(['user_id', 'month', 'year']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('monthly_budgets');
    }
};
