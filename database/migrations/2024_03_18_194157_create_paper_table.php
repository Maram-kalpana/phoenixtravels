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
        Schema::create('paper', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('course_id')->unsigned();
            $table->unsignedInteger('group_id')->unsigned();
            $table->string('paper_name',250)->nullable();
            $table->string('paper_slug',250)->nullable();
            $table->string('paper_image',250)->nullable();
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
        Schema::dropIfExists('paper');
    }
};
