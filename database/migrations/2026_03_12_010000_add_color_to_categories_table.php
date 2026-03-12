<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('categories') && !Schema::hasColumn('categories', 'color')) {
            Schema::table('categories', function (Blueprint $table): void {
                $table->string('color')->default('#6c757d');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('categories') && Schema::hasColumn('categories', 'color')) {
            Schema::table('categories', function (Blueprint $table): void {
                $table->dropColumn('color');
            });
        }
    }
};
