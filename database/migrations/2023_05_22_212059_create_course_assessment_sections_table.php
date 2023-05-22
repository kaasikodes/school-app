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
        Schema::create('course_assessment_sections', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('course_assessment_id')->nullable();
            $table->string('name');
            $table->string('description')->nullable();
            $table->timestamps();
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
        Schema::dropIfExists('course_assessment_sections');
    }
};
