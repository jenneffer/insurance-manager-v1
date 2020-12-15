<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdditionalInsItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('additional_ins_item', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('risk_id');
            $table->string('ref_no', 25);
            $table->text('description');
            $table->string('rate', 25);
            $table->double('sum_insured', 15, 2);
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
        Schema::dropIfExists('additional_ins_item');
    }
}
