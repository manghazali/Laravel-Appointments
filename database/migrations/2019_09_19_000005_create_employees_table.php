<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
        $table->increments('id');
        $table->string('name');
        $table->string('email')->nullable();
        $table->string('phone')->nullable();

        $table->unsignedInteger('department_id');
        $table->unsignedInteger('status_id');
        $table->unsignedInteger('user_id')->nullable();

        $table->timestamps();
        $table->softDeletes();

    });

    }
}
