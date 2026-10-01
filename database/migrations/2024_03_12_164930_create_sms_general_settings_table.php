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
        Schema::create('sms_general_settings', function (Blueprint $table) {
            $table->id();
            $table->string('sms_url',350)->nullable();
            $table->string('api_key',150)->nullable();
            $table->string('sender_id',100)->nullable();
            $table->string('username',100)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sms_general_settings');
    }
};
