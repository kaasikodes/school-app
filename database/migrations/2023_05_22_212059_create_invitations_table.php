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
        Schema::create('invitations', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('code');
            $table->string('email')->nullable();
            $table->enum('user_type', ['admin', 'staff', 'custodian', 'student'])->nullable();
            $table->timestamp('created_at')->nullable();
            $table->integer('accepted')->nullable()->default(0);
            $table->timestamp('accepted_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->unsignedBigInteger('school_id');
            $table->bigInteger('session_id')->nullable();

            $table->unique(['code', 'school_id'], 'UNIQUE SCHOOL INVITE CODE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invitations');
    }
};
