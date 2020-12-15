<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInsuranceDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insurance_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('insurance_id');
            $table->string('policy_no', 50);
            $table->string('self_rating', 50);
            $table->string('excess', 50);
            $table->text('remark');
            $table->float('sum_insured');
            $table->float('gross_premium');
            $table->float('service_tax');
            $table->float('stamp_duty');
            $table->date('date_start');
            $table->date('date_end');
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
        Schema::dropIfExists('insurance_renew');
    }
}
