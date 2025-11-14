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
        Schema::table('ingredient_recipe', function (Blueprint $table) {
            $table->string('amount');
            $table->string('unit');
            $table->unique(['recipe_id', 'ingredient_id', 'amount', 'unit']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('ingredient_recipe', function (Blueprint $table) {
            $table->dropUnique(['recipe_id', 'ingredient_id', 'amount', 'unit']);
            $table->dropColumn('amount');
            $table->dropColumn('unit');
        });
    }
};
