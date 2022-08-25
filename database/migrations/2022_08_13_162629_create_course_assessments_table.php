<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseAssessmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_assessments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_id')->nullable();
            $table->foreignId('level_id')->nullable();
            $table->foreignId('school_session_id')->nullable();
            $table->foreignId('course_teacher_id')->nullable();
            $table->string('name');
            $table->string('content')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_assessments');
    }
}
