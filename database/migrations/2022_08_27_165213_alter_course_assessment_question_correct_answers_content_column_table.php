<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCourseAssessmentQuestionCorrectAnswersContentColumnTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_assessment_question_correct_answers', function (Blueprint $table) {
            $table->string('content');

            



        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_assessment_question_correct_answers', function (Blueprint $table) {
            $table->dropColumn('content');
        
        });
    }
}
