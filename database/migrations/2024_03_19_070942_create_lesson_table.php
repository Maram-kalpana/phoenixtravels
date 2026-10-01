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
        Schema::create('lesson', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('course_id')->unsigned();
            $table->unsignedInteger('group_id')->unsigned();
            $table->unsignedInteger('paper_id')->unsigned()->nullable();
            $table->unsignedInteger('subject_id')->unsigned();
            $table->string('lesson_name',250)->nullable();
            $table->string('lesson_slug',250)->nullable();
            $table->text('youtube_url')->nullable();
            $table->string('duration_web',55)->nullable();
            $table->text('youtube_url_mob')->nullable();
            $table->string('duration_mob',55)->nullable();
            $table->string('demo_type',55)->nullable();
            $table->string('lesson_image',250)->nullable();
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
        Schema::dropIfExists('lesson');
    }
};
