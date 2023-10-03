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
        Schema::table('discounts', function (Blueprint $table) {
            $table->text('terms')->nullable();
            $table->integer('bh_min')->unsigned()->nullable();
            $table->integer('bh_max')->unsigned()->nullable();
            $table->json('bh_options')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('discounts', function (Blueprint $table) {
            $table->dropColumn('terms');
            $table->dropColumn('bh_min');
            $table->dropColumn('bh_max');
            $table->dropColumn('bh_options');
        });
    }
};
