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
        Schema::create('book_accounts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('cover')->nullable();
            $table->string('remark')->nullable();
            $table->boolean('is_double')->default(false);
            $table->timestamps();
            $table->softDeletes();

            $table->index([
                'user_id',
                'name',
            ], 'book_accounts_index');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('book_accounts');
    }
};
