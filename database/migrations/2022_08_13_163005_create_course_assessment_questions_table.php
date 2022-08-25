<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseAssessmentQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_assessment_questions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_assessment_id')->nullable();
            $table->foreignId('course_assessment_section_id')->nullable();
            $table->string('content');
            $table->string('hint')->nullable();
            $table->bigInteger('time_allowed')->nullable();
            $table->enum('type', ['mcq','theory','fill_in_gap'])->default('theory');
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
        Schema::dropIfExists('course_assessment_questions');
    }
}
