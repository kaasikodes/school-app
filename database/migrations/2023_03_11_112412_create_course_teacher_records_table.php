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
        Schema::create('course_teacher_records', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('staff_id');
            $table->unsignedTinyInteger('can_record')->default(0);
            $table->unsignedTinyInteger('can_create_assessment')->default(0);
            $table->unsignedTinyInteger('can_add_remove_course_participant')->default(0);
            $table->unsignedBigInteger('course_id');
            $table->unsignedBigInteger('level_id');
            $table->unsignedBigInteger('school_session_id');
            $table->timestamps();

            $table->unique(['staff_id', 'course_id', 'level_id', 'school_session_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('course_teacher_records');
    }
};
