<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePedidosIndividuaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pedidos_individuais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('create_by')->constrained('users');
            $table->foreignId('delete_by')->nullable()->constrained('users');
            $table->foreignId('restored_by')->nullable()->constrained('users');
            $table->foreignId('pedido_id')->constrained('pedidos');
            $table->foreignId('produto_id')->constrained('produtos');
            $table->integer('quantidade');
            $table->integer('porcentagem');
            $table->decimal('preco_unidade', 8, 2);
            $table->decimal('total', 8 , 2);
            $table->decimal('totalSemDesconto', 8 , 2);
            $table->timestamp('restored_at')->nullable();
            $table->softDeletes();
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
        Schema::dropIfExists('pedidos_individuais');
    }
}
