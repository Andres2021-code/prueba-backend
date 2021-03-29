<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos', function (Blueprint $table) {
            $table->increments('id');
            $table->string('detalle_pedido');
            $table->integer('id_users')->unsigned();
            $table->integer('id_producto')->unsigned();
            $table->timestamps();
        });

        Schema::table('pedidos', function (Blueprint $table) {
            $table->foreign('id_users')
                ->references('id')->on('users')
                ->onDelete('cascade');
           $table->foreign('id_producto')
                ->references('id')->on('productos')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pedidos');
    }
}
