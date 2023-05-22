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
        Schema::create('requisitions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('requester_id')->comment('This is the user id of user from auth');
            $table->enum('requested_as', ['course_teacher', 'level_teacher', 'custodian', 'student', 'user'])->default('user')->comment('This indicates what role the user was acting as while making request');
            $table->unsignedBigInteger('current_approver_id')->comment('This is the current user id of the person who is to approve');
            $table->unsignedBigInteger('current_stage_id')->nullable();
            $table->unsignedBigInteger('level_id')->nullable()->comment('This speaks to level requests such as result compilations');
            $table->unsignedBigInteger('course_id')->nullable()->comment('This speaks to course requests such as result compilation');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->enum('type', ['course_result_compilation', 'level_result_compilation', 'other'])->default('other')->comment('This indicates what type of request');
            $table->longText('content')->nullable();
            $table->text('title');
            $table->unsignedBigInteger('school_id')->comment('The school the requests belongs to');
            $table->unsignedBigInteger('session_id')->comment('The session the requests belongs to');
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
        Schema::dropIfExists('requisitions');
    }
};
