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
        Schema::create('payment_settings', function (Blueprint $table) {
            $table->id();
            $table->string('razorpay_key_live',250)->nullable();
            $table->string('razorpay_key_secret_live',250)->nullable();
            $table->string('razorpay_key_test',200)->nullable();
            $table->string('razorpay_key_secret_test',200)->nullable();
            $table->string('mode_type',55)->nullable();
            $table->string('color',55)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_settings');
    }
};
