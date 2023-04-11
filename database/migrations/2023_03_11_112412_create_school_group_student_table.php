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
        Schema::create('school_group_student', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('school_group_id');
            $table->unsignedBigInteger('entity_id');
            $table->enum('roles', ['member', 'admin'])->default('member');
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
        Schema::dropIfExists('school_group_student');
    }
};
