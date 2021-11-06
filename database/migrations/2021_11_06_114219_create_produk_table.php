<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produk', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('kategori_id');
            $table->index('kategori_id');
            $table->foreign('kategori_id')->references('id')->on('kategori')->onDelete('cascade');
            $table->unsignedBigInteger('merk_id');
            $table->index('merk_id');
            $table->foreign('merk_id')->references('id')->on('merk')->onDelete('cascade');
            $table->string('nama_produk',200);
            $table->text('deskripsi_produk');
            $table->string('harga_produk',10);
            $table->string('foto_produk',200);
            $table->string('url_produk',200);
            $table->index(['nama_produk','harga_produk']);
            $table->uuid('uuid');
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
        Schema::dropIfExists('produk');
    }
}
