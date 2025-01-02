<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProdutosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('create_by')->constrained('users');
            $table->foreignId('delete_by')->nullable()->constrained('users');
            $table->foreignId('restored_by')->nullable()->constrained('users');
            $table->foreignId('update_by')->nullable()->constrained('users');
            $table->string('produto');
            $table->foreignId('categoria_id')->constrained('categorias');
            $table->decimal('valor', 8 , 2);
            $table->string('imagem');
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
        Schema::dropIfExists('produtos');
    }
}
