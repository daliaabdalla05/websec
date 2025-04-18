<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'code')) {
                $table->string('code')->nullable();
            }
            if (!Schema::hasColumn('products', 'model')) {
                $table->string('model')->nullable();
            }
            if (!Schema::hasColumn('products', 'inventory')) {
                $table->integer('inventory')->default(0);
            }
            if (!Schema::hasColumn('products', 'photo')) {
                $table->string('photo')->nullable();
            }
            if (!Schema::hasColumn('products', 'deleted_at')) {
                $table->softDeletes();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['code', 'model', 'inventory', 'photo', 'deleted_at']);
        });
    }
};
