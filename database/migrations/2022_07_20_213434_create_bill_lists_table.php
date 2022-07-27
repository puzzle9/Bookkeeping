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
        Schema::create('bill_lists', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('book_account_id');
            $table->unsignedBigInteger('bill_payee_id');
            $table->timestamp('time_occurrence');
            $table->string('remark')->nullable();
            $table->json('files')->nullable();
            $table->json('address')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index([
                'book_account_id',
                'bill_payee_id',
                'time_occurrence',
            ], 'bill_lists_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bill_lists');
    }
};
