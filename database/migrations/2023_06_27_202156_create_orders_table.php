<?php

use App\Models\Discount;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\User;


return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class, "user_id")->nullable();
            $table->string('order_status')->comment("pending, processing, on hold, completed, cancelled, refunded, failed")->nullable();
            $table->string('order_number', 255)->unique();
            $table->string('order_total', 255)->nullable();
            $table->string('order_subtotal', 255)->nullable();
            $table->string('order_discount', 255)->nullable();
            $table->string('order_tax', 255)->nullable();
            $table->string('payment_method', 255)->nullable();
            $table->string('payment_status', 255)->comment("pending, paid, failed, refunded")->nullable();
            $table->foreignIdFor(User::class, "created_by")->nullable();
            $table->foreignIdFor(User::class, "updated_by")->nullable();
            $table->softDeletes();
            $table->foreignIdFor(User::class, "deleted_by")->nullable();
            $table->string('order_date', 255)->nullable();


            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
