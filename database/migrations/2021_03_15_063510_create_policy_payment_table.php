<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePolicyPaymentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('policy_payment', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('insurance_id');
            $table->string('policy_no', 50);
            $table->integer('insurance_details_id');
            $table->float('paid_amount', 15, 2);
            $table->text('remark');
            $table->date('payment_date');            
            $table->softDeletes();
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
        Schema::dropIfExists('policy_payment');
    }
}
