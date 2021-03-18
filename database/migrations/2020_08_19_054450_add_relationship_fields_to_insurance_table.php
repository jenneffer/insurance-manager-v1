<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRelationshipFieldsToInsuranceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('insurances', function (Blueprint $table) {
            // $table->unsignedInteger('ins_company')->nullable();
            // $table->foreign('ins_company','company_fk_00001')->references('id')->on('company');

            // $table->unsignedInteger('created_by_id')->nullable();
            // $table->foreign('created_by_id', 'created_by_fk_00002')->references('id')->on('users');

        });
    }

}
