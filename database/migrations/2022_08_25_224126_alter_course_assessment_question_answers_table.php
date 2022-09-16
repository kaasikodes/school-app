<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCourseAssessmentQuestionAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_assessment_question_answers', function (Blueprint $table) {
            $table->foreignId('participant_id')->nullable();
            $table->foreignId('assessment_id')->nullable();
            $table->foreignId('section_id')->nullable();
            $table->decimal('score')->default(0);


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_assessment_question_answers', function (Blueprint $table) {
            $table->dropColumn('participant_id');
            $table->dropColumn('assessment_id');
            $table->dropColumn('section_id');
            $table->dropColumn('score');
        });
    }
}
