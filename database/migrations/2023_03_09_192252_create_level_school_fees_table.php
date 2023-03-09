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
        Schema::create('level_school_fees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('level_id');
            $table->unsignedBigInteger('session_id');
            $table->unsignedBigInteger('school_id');
            $table->unsignedBigInteger('fee_category_id')->default(0);
            $table->string('breakdown_document_url', 50)->default('');
            $table->unsignedBigInteger('amount');
            $table->unsignedBigInteger('currency_id');
            $table->tinyInteger('can_be_installmental')->default(0);
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
        Schema::dropIfExists('level_school_fees');
    }
};
