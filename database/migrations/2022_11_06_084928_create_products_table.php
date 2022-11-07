<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('description', 1000);
            $table->integer('quantity')->unsigned();
            $table->unsignedTinyInteger('status')->default(\App\Models\Product::UNAVAILABLE_PRODUCT);
            $table->string('image');
            $table->unsignedBigInteger('seller_id');
            $table->timestamps();

            // $table->timestamps();
            $table->foreign('seller_id')
            ->references('id')
            ->on('users');
        

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
};
