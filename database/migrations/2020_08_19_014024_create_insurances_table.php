<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInsurancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('insurances', function (Blueprint $table) {
            $table->increments('id');
            $table->string('ins_agent', 100);
            $table->text('ins_correspond_address');
            $table->string('ins_policy_no', 50);
            $table->string('ins_class', 100);
            $table->text('ins_self_rating');
            $table->date('ins_date_start');
            $table->date('ins_date_end');
            $table->text('ins_issuing_branch');
            $table->text('ins_remark');
            $table->date('ins_issuing_date');
            $table->double('ins_gross_premium', 15, 2);
            $table->double('ins_service_tax', 15, 2);
            $table->double('ins_stamp_duty', 15, 2);
            $table->double('ins_total_sum_insured', 15, 2);
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
        Schema::dropIfExists('insurances');
    }
}
