<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterCourseAssessmentSections extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('course_assessment_sections', function (Blueprint $table) {
            $table->decimal('weight')->default(0);
            $table->boolean('isActive')->default(true);
            $table->bigInteger('time_allowed')->nullable();



        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('course_assessment_sections', function (Blueprint $table) {
            $table->dropColumn('weight');
            $table->dropColumn('isActive');
            $table->dropColumn('time_allowed');
        });
    }
}
