<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntradasSaidasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entradas_saidas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('produto_id')->constrained('produtos');
            $table->integer('quantidade');
            $table->string('tipo');
            $table->string('observacao')->nullable();
            $table->foreignId('ajuste_id')->nullable()->constrained('ajustes');
            $table->foreignId('multiplo_id')->nullable()->constrained('multipla_entradas');
            $table->foreignId('pedido_id')->nullable()->constrained('order_totals');
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
        Schema::dropIfExists('entradas_saidas');
    }
}
