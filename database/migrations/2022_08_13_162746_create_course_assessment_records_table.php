<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCourseAssessmentRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('course_assessment_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('course_assessment_id')->nullable();
            $table->foreignId('course_participant_id')->nullable();
            $table->decimal('score')->nullable();

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
        Schema::dropIfExists('course_assessment_records');
    }
}
