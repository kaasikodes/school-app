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
        Schema::create('students', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('school_id');
            $table->unsignedBigInteger('current_level_id');
            $table->unsignedBigInteger('latest_session_id');
            $table->string('id_number', 50)->default('');
            $table->string('alt_phone', 50)->nullable();
            $table->string('alt_email', 50)->nullable();
            $table->tinyInteger('isActive')->default(1);
            $table->timestamps();

            $table->unique(['user_id', 'school_id'], 'unique_student');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('students');
    }
};
