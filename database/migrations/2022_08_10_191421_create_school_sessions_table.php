<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSchoolSessionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('school_sessions', function (Blueprint $table) {
            $table->id();
            $table->string('starts');
            $table->string('ends')->nullable();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            // starts
            // ends
            // duration
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
        Schema::dropIfExists('school_sessions');
    }
}
