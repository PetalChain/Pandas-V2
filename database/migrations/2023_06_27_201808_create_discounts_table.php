<?php

use App\Models\Brand;
use App\Models\OfferType;
use App\Models\User;
use App\Models\VoucherType;
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
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Brand::class)->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->unsignedInteger('voucher_type')->default(0);
            $table->string('slug')->unique();
            $table->text('excerpt')->nullable();
            $table->boolean('is_active')->default(false);
            $table->datetime('starts_at')->nullable();
            $table->datetime('ends_at')->nullable();
            $table->string('api_link')->nullable();
            $table->string('link')->nullable();
            $table->string('cta_text')->nullable();
            $table->unsignedInteger('views')->default(0);
            $table->unsignedInteger('clicks')->default(0);
            $table->string('code')->nullable();
            $table->jsonb('amount')->nullable();
            $table->integer('limit_qty')->nullable();
            $table->unsignedInteger('limit_amount')->nullable();
            $table->unsignedInteger('public_percentage')->nullable();
            $table->unsignedInteger('percentage')->nullable();
            $table->foreignIdFor(User::class, 'created_by_id')->nullable();
            $table->foreignIdFor(User::class, 'updated_by_id')->nullable();
            $table->softDeletes();
            $table->foreignIdFor(User::class, 'deleted_by_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
