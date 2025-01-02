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
            $table->id();
            $table->foreignId('create_by')->constrained('users');
            $table->foreignId('delete_by')->nullable()->constrained('users');
            $table->foreignId('restored_by')->nullable()->constrained('users');
            $table->foreignId('cliente_id')->constrained('clientes');
            $table->foreignId('endereco_id')->constrained('enderecos');
            $table->decimal('total', 8 , 2);
            $table->decimal('totalSemDesconto', 8 , 2);
            $table->integer('porcentagem');
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
        Schema::dropIfExists('pedidos');
    }
}
