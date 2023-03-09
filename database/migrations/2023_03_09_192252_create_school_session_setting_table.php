<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_session_setting', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('school_id');
            $table->unsignedBigInteger('session_id');
            $table->unsignedBigInteger('grading_policy_id')->nullable();
            $table->unsignedBigInteger('student_enrollment_policy_id')->nullable();
            $table->unsignedBigInteger('course_record_template_id')->nullable();
            $table->timestamps();

            $table->unique(['session_id', 'school_id'], 'UNIQUE SCHOOL SESSION SETTING');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('school_session_setting');
    }
};
