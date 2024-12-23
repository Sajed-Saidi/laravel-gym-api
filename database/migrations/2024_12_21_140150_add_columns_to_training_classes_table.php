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
        Schema::table('training_classes', function (Blueprint $table) {
            $table->string('category', 100);
            $table->string('image', 255);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('training_classes', function (Blueprint $table) {
            $table->dropColumn('category');
            $table->dropColumn('image');
        });
    }
};
