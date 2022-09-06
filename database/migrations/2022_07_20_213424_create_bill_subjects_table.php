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
        Schema::create('bill_subjects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('book_account_id');
            $table->unsignedBigInteger('bill_currency_id');
            $table->unsignedBigInteger('parent_id');
            $table->string('type');
            $table->bigInteger('balance')->default(0);
            $table->string('name');
            $table->string('cover')->nullable();
            $table->string('remark')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->index([
                'book_account_id',
                'bill_currency_id',
                'parent_id',
                'type',
            ], 'bill_subjects_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bill_subjects');
    }
};
