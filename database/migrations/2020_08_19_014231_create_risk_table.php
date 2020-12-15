<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRiskTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('risk', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ins_id');
            $table->integer('risk_riskno');
            $table->string('risk_location', 100);
            $table->text('risk_address');
            $table->text('risk_description');
            // $table->text('risk_construction_code');
            $table->text('risk_remark')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('risk');
    }
}
