<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bill_infos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bill_list_id');
            $table->unsignedBigInteger('bill_subject_id');
            $table->unsignedBigInteger('current_bill_currency_id');
            $table->bigInteger('current_amount');
            $table->bigInteger('transform_amount')->nullable();
            $table->string('remark')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index([
                'bill_list_id',
                'bill_subject_id',
            ], 'bill_infos_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bill_infos');
    }
};
