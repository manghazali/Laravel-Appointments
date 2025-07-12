<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRelationshipFieldsToEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            $table->foreign('status_id', 'status_fk_360717')
                ->references('id')->on('status');

            $table->foreign('department_id', 'department_fk_360718')
                ->references('id')->on('departments');
                
            $table->foreign('user_id', 'users_fk_360719')
                ->references('id')->on('users');
        });
    }

}
