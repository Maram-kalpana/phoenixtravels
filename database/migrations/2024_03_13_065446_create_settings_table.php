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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('logo',250)->nullable();
            $table->string('fav',250)->nullable();
            $table->string('address',200)->nullable();
            $table->string('about',200)->nullable();
            $table->bigInteger('phone')->unsigned();
            $table->string('email',100)->nullable();
            $table->string('company_name',100)->nullable();
            $table->string('color',25)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
