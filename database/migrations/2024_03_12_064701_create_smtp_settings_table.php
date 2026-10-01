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
        Schema::create('smtp_settings', function (Blueprint $table) {
            $table->integer('id')->unsigned();
            $table->string('protocol',15)->nullable();
            $table->string('smtp_host',200)->nullable();
            $table->string('smtp_port',15)->nullable();
            $table->string('smtp_user',200)->nullable();
            $table->string('smtp_pass',200)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('smtp_settings');
    }
};
