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
            $table->foreignIdFor(Brand::class, 'brand_id')->nullable();
            $table->string('name', 255)->unique();
            $table->foreignIdFor(VoucherType::class, 'voucher_type_id')->nullable();
            $table->foreignIdFor(OfferType::class, 'offer_type_id')->nullable();
            $table->string('slug', 255)->unique();
            $table->integer('active')->default(0);
            $table->datetime('start_date')->nullable();
            $table->datetime('end_date')->nullable();
            $table->integer('status')->default(1);
            $table->string('api_link')->nullable();
            $table->string('link')->nullable();
            $table->string('cta')->nullable();
            $table->integer('views')->default(0);
            $table->integer('clicks')->default(0);
            $table->string('code')->nullable();
            $table->string('amount')->nullable();
            $table->integer('limit_qty')->nullable();
            $table->decimal('limit_amount', 10, 2)->nullable();
            $table->decimal('public_percentage', 10, 2)->nullable();
            $table->decimal('percentage', 10, 2)->nullable();
            $table->foreignIdFor(User::class, 'created_by')->nullable();
            $table->foreignIdFor(User::class, 'updated_by')->nullable();
            $table->softDeletes();
            $table->foreignIdFor(User::class, 'deleted_by')->nullable();
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
