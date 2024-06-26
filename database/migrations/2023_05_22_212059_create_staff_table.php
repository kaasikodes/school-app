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
        Schema::create('staff', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id');
            $table->text('alt_name')->nullable();
            $table->boolean('isActive')->default(true);
            $table->string('staff_no')->nullable();
            $table->timestamps();
            $table->unsignedBigInteger('school_id');

            $table->unique(['staff_no', 'school_id'], 'staff_staff_no_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('staff');
    }
};
