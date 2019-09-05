<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('user_id')->unsigned();
            $table->string('first_name');
            $table->string('middle_name');
            $table->string('last_name');
            $table->string('birth_date');
            $table->string('civil_status');
            $table->string('present_address');
            $table->string('permanent_address');
            $table->string('contact_no_1');
            $table->string('contact_no_2');
            $table->string('tin');
            $table->string('sss');
            $table->string('pagibig');
            $table->string('philhealth');
            $table->string('employee_id');
            $table->string('date_hired');
            $table->integer('branch_id')->unsigned();
            $table->string('skype_id');
            $table->integer('department_id')->unsigned();
            $table->string('position');
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
        Schema::dropIfExists('employees');
    }
}
