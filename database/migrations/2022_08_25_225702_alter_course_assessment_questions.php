<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCourseAssessmentQuestions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_assessment_questions', function (Blueprint $table) {
            $table->decimal('score')->default(1);
            $table->boolean('isActive')->default(true);


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_assessment_questions', function (Blueprint $table) {
            $table->dropColumn('score');
            $table->dropColumn('isActive');
        });
    }
}
