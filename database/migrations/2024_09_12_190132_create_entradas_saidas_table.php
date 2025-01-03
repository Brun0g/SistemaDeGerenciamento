<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Carbon;


class CreateEntradasSaidasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entradas_saidas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('create_by')->constrained('users');
            $table->foreignId('produto_id')->constrained('produtos');
            $table->integer('quantidade');
            $table->string('observacao')->nullable();
            $table->foreignId('ajuste_id')->nullable()->constrained('ajustes');
            $table->foreignId('multiplo_id')->nullable()->constrained('multiplas_entradas');
            $table->foreignId('pedido_id')->nullable()->constrained('pedidos');
            $table->SoftDeletes();
            $table->timestamps();
        });
    }


    
    
    public function down()
    {
        Schema::dropIfExists('entradas_saidas');
    }
}
