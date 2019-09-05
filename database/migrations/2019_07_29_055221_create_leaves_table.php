<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLeavesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('leaves', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->unsigned();
            $table->string('type');
            $table->string('pay_type');
            $table->string('from');
            $table->string('to');
            $table->string('time_from')->nullable();
            $table->string('time_to')->nullable();
            $table->longText('reason');
            $table->string('recommending_approval')->default('Pending');
            $table->string('final_approval')->default('Pending');
            $table->double('count');
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
        Schema::dropIfExists('leaves');
    }
}
