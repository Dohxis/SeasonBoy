<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateUsersWithTutorial extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function(Blueprint $table){
            $table->boolean('tutorial')->default(false);
            $table->boolean('world_half')->default(false);
            $table->boolean('world_all')->default(false);
            $table->boolean('army_50')->default(false);
            $table->boolean('army_100')->default(false);
            $table->boolean('won_15')->default(false);
            $table->boolean('won_10')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
