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
        Schema::create('course_participant_records', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('course_id')->nullable();
            $table->unsignedBigInteger('level_id')->nullable();
            $table->unsignedBigInteger('school_session_id')->nullable();
            $table->unsignedBigInteger('student_id')->nullable();
            $table->unsignedBigInteger('recorder_id')->nullable();
            $table->decimal('total')->nullable()->default(0);
            $table->json('break_down')->nullable();
            $table->char('grade', 50)->nullable();
            $table->enum('student_passed', ['true', 'false', 'pending'])->nullable()->default('pending');
            $table->string('remark', 50)->nullable();
            $table->timestamps();

            $table->unique(['course_id', 'school_session_id', 'level_id', 'student_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_participant_records');
    }
};
