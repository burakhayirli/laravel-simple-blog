<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Articles extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('category_id'); //0'dan aşağı değer alamasın.
            $table->string('title');
            $table->string('image');
            $table->longText('content');
            $table->integer('hit')->default(0);
            $table->integer('status')->default(1)->comment('0:pasif 1:aktif');
            $table->string('slug');
            $table->softDeletes();
            $table->timestamps();

            $table->foreign('category_id')
              ->references('id')
              ->on('categories');
              //->onDelete('cascade'); //ilgili Kategori silinirse ona bağlı tüm yazıları sil
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('articles');
    }
}
