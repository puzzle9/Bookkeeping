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
        Schema::create('bill_currencies', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('book_account_id');
            $table->string('name');
            $table->unsignedBigInteger('sort');
            $table->timestamps();
            $table->softDeletes();

            $table->index([
                'book_account_id',
                'name',
                'sort',
            ], 'bill_currencies_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bill_currencies');
    }
};
