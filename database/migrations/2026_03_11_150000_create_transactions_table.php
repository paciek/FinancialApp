<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('transactions')) {
            Schema::create('transactions', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->cascadeOnDelete();
                $table->foreignId('category_id')->constrained()->restrictOnDelete();
                $table->decimal('amount', 12, 2);
                $table->enum('type', ['income', 'expense']);
                $table->string('description')->nullable();
                $table->date('transaction_date');
                $table->timestamps();

                $table->index('user_id');
                $table->index('transaction_date');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};