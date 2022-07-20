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
            $table->unsignedBigInteger('current_amount');
            $table->unsignedBigInteger('transform_amount');
            $table->timestamps();
            $table->softDeletes();

            $table->index([
                'bill_list_id',
                'bill_subject_id',
                'current_bill_currency_id',
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
