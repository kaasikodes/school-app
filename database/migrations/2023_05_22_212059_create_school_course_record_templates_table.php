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
        Schema::create('school_course_record_templates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('school_id');
            $table->string('title', 50)->default('');
            $table->boolean('isActive')->unsigned()->default(true);
            $table->json('break_down');
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
        Schema::dropIfExists('school_course_record_templates');
    }
};
