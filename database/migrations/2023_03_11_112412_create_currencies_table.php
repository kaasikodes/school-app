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
        Schema::create('currencies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('base_equivalent_amount')->default(0);
            $table->string('name');
            $table->string('code')->nullable();
            $table->boolean('isActive')->default(true);
            $table->timestamps();
            $table->unsignedBigInteger('school_id');
            $table->unsignedBigInteger('session_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('currencies');
    }
};
