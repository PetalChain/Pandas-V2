<?php

use App\Models\User;
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
        Schema::create('brands', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255)->unique();
            $table->string('link', 255)->unique();
            $table->string('slug', 255)->unique();
            $table->string('uniqid', 255)->unique();
            $table->string('description')->nullable();
            $table->string('logo')->nullable();
            $table->integer('views')->default(0);
            $table->integer('status')->default(1);
            $table->foreignIdFor(User::class, 'created_by_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignIdFor(User::class, 'updated_by_id')->nullable()->constrained()->nullOnDelete();
            $table->softDeletes();
            $table->foreignIdFor(User::class, 'deleted_by_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('brands');
    }
};
