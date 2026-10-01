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
        Schema::create('group', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('course_id')->unsigned();
            $table->string('group_name',250)->nullable();
            $table->text('group_slug')->nullable();
            $table->string('discount_price',50)->nullable();
            $table->string('actual_price',50)->nullable();
            $table->text('demo_url')->nullable();
            $table->string('language',55)->nullable();
            $table->string('group_expired_days',55)->nullable();
            $table->string('group_image',250)->nullable();
            $table->text('description')->nullable();
            $table->string('meta_title',255)->nullable();
            $table->string('meta_keywords',255)->nullable();
            $table->string('meta_description',255)->nullable();
            $table->unsignedInteger('status')->unsigned();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('group');
    }
};
