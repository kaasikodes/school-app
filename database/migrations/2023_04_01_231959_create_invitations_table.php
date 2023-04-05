<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invitations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('school_id')->default(0);
            $table->string('email')->unique();
            $table->string('code');
            $table->tinyInteger('accepted')->default(0);
            $table->timestamps('accepted_at')->nullable();
            $table->enum('user_type', ['admin', 'staff', 'custodian', 'student']);
            $table->unique(['code', 'school_id'], 'school_invite_code');
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
        Schema::dropIfExists('invitations');
    }
}
