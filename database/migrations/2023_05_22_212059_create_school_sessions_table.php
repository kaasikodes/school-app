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
        Schema::create('school_sessions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->enum('result_issued', ['YES', 'NO'])->nullable()->default('NO');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('result_issued_at')->nullable();
            $table->timestamp('ended_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->unsignedBigInteger('school_id')->nullable();
            $table->date('starts');
            $table->date('ends')->nullable();
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
};
