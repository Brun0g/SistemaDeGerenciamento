<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VendasController;
use App\Http\Controllers\ClientesController;
use App\Http\Controllers\ProdutosController;
use App\Http\Controllers\PedidosController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\GraficosController;
use App\Http\Controllers\CarrinhoController;
use App\Http\Controllers\EnderecosController;
use App\Http\Controllers\PromocoesController;
use App\Http\Controllers\EntradaController;

use App\Http\Controllers\AjusteEstoqueController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {

    return view('welcome');
});

Route::get('/sessao', function () {
    dd(session()->all());
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');



// VISUALIZAÇÃO DE QUANTIDADE DE PRODUTOS POR CLIENTES
Route::GET('/produtos_vendidos',[VendasController::class,'index'])->middleware(['auth'])->name('produtos_vendidos');



// CLIENTE
Route::GET('/Clientes',[ClientesController::class,'index'])->middleware(['auth'])->name('Clientes')->middleware(['cors']);
Route::GET('/Cliente/{id}',[ClientesController::class,'show'])->middleware(['auth'])->middleware(['cors']);
Route::GET('/ClientesExcluidos',[ClientesController::class, 'softDeletesView'])->middleware(['auth'])->name('clientes_excluidos')->middleware(['cors']);
Route::GET('/Editar/Cliente/{id}',[ClientesController::class,'viewClient'])->middleware(['auth']);
Route::POST('/cadastrarCliente',[ClientesController::class,'registerClient'])->middleware(['auth']);
Route::POST('/restaurarCliente/{id}',[ClientesController::class,'restoredClient'])->middleware(['auth']);
Route::PATCH('/atualizarCliente/{id}',[ClientesController::class,'update'])->middleware(['auth']);
Route::DELETE('/DeletarCliente/{id}',[ClientesController::class,'deleteClient'])->middleware(['auth']);


//ENDERECO
Route::POST('/novoEndereco/{id}',[EnderecosController::class,'store'])->middleware(['auth']);
Route::GET('/VisualizarEndereco/{id}',[EnderecosController::class,'index'])->middleware(['auth']);
Route::PATCH('/EditarEndereco/{id}',[EnderecosController::class,'update'])->middleware(['auth']);
Route::DELETE('/DeletarEndereco/{id}',[EnderecosController::class,'delete'])->middleware(['auth']);

// PRODUTO
Route::POST('/CadastrarProduto', [ProdutosController::class, 'store'])->middleware(['auth'])->middleware(['cors']);
Route::GET('/produtos',[ProdutosController::class,'index'])->middleware(['auth'])->name('Produtos');
Route::GET('/produtos_excluidos',[ProdutosController::class,'softDeletesView'])->middleware(['auth'])->name('produtos_excluidos');
Route::GET('/detalhe_produto/{id}',[ProdutosController::class,'showProduct'])->middleware(['auth']);
Route::GET('/EditarProduto/{id}',[ProdutosController::class,'viewFilterProducts'])->middleware(['auth']);

Route::GET('/multiplas_entradas',[ProdutosController::class,'index_multiple'])->middleware(['auth'])->name('multiplas_entradas');

Route::GET('/ajustar_estoque',[ProdutosController::class,'index_adjustment'])->middleware(['auth'])->name('ajustar_estoque');

Route::DELETE('/excluirImagem/{id}',[ProdutosController::class,'deleteImage'])->middleware(['auth']);
Route::DELETE('/DeletarProduto/{id}',[ProdutosController::class,'deleteProduct'])->middleware(['auth']);
Route::PATCH('/EditarProduto/{id}',[ProdutosController::class,'editProduct'])->middleware(['auth']);
Route::PATCH('/RestaurarProduto/{id}', [ProdutosController::class, 'restored'])->middleware(['auth'])->middleware(['cors']);

// PEDIDO
Route::POST('/aprovarPedido/{id_pedido}/{id_cliente}',[PedidosController::class,'finish'])->middleware(['auth']);
Route::DELETE('/excluirPedido/{id_product}',[PedidosController::class,'delete'])->middleware(['auth']);
Route::GET('/pedidofinalizado/{id_pedido}', [PedidosController::class, 'showFinishOrder'])->middleware(['auth']);
Route::GET('/pedidos_excluidos', [PedidosController::class, 'orders_deleted'])->middleware(['auth'])->name('pedidos_excluidos');
Route::GET('/pedidos_clientes', [PedidosController::class, 'orders_client'])->middleware(['auth'])->name('pedidos_clientes');
Route::POST('/Restaurar_pedido/{id_pedido}', [PedidosController::class, 'orders_active'])->middleware(['auth']);
Route::GET('/trocar_pagina/{page}', [PedidosController::class, 'switch_page'])->middleware(['auth']);
Route::GET('/trocar_pagina_link{page}', [PedidosController::class, 'switch_page_link'])->middleware(['auth']);


//CARRINHO
Route::GET('/carrinho/{id}', [CarrinhoController::class, 'index'])->middleware(['auth'])->name('carrinho');
Route::POST('/CadastrarProduto/Cliente/{id}', [CarrinhoController::class, 'store'])->middleware(['auth']);
Route::POST('/finalizarPedido/{id}', [CarrinhoController::class, 'finish'])->middleware(['auth']);
Route::PATCH('/atualizarPedido/{id}', [CarrinhoController::class, 'update'])->middleware(['auth']);
Route::PATCH('/atualizarPorcentagem/{id}', [CarrinhoController::class, 'store_percentage'])->middleware(['auth']);
Route::GET('/ExcluirProdutoCliente/{id_cliente}/{id_product}',[CarrinhoController::class,'delete'])->middleware(['auth']);

// CATEGORIA
Route::GET('/Categoria',[CategoriaController::class,'index'])->middleware(['auth'])->name('Categoria');
Route::GET('/Categoria/{id}',[CategoriaController::class,'show'])->middleware(['auth']);
Route::POST('/CadastrarCategoria', [CategoriaController::class, 'store'])->middleware(['auth']);
Route::DELETE('/DeletarCategoria/{id}', [CategoriaController::class, 'delete'])->middleware(['auth']);
Route::PATCH('/editarCategoria/{id}', [CategoriaController::class, 'update'])->middleware(['auth']);

// GRÁFICOS
Route::GET('/graficos',[GraficosController::class,'index'])->middleware(['auth'])->name('graficos');

// PROMOÇÕES
Route::GET('/promocoes',[PromocoesController::class,'index'])->middleware(['auth'])->name('promocoes');
Route::GET('/promocoesExcluidas',[PromocoesController::class,'detail'])->middleware(['auth'])->name('promocoes_excluidas');
Route::POST('/adicionarpromocao',[PromocoesController::class,'store'])->middleware(['auth']);
Route::POST('/restaurarPromocao/{id}',[PromocoesController::class,'restored'])->middleware(['auth']);
Route::PATCH('/ativarpromocao/{id}',[PromocoesController::class,'update'])->middleware(['auth']);
Route::PATCH('/atualizarpromocao/{id}',[PromocoesController::class,'edit'])->middleware(['auth']);
Route::DELETE('/deletarPromocao/{id}',[PromocoesController::class,'destroy'])->middleware(['auth']);

// ENTRADAS/SAIDAS
Route::GET('/entradas_saidas/{id}',[EntradaController::class,'index'])->middleware(['auth'])->name('entradas_saidas');
Route::PATCH('/entradas_saidas/{id}',[EntradaController::class,'update'])->middleware(['auth']);

// MULTIPLAS ENTRADAS E SAIDAS
Route::GET('/visualizar_ajuste',[AjusteEstoqueController::class,'index_adjustment'])->middleware(['auth'])->name('visualizar_ajuste');
Route::GET('/visualizar_entradas',[AjusteEstoqueController::class,'index_multiple'])->middleware(['auth'])->name('visualizar_entradas');
Route::GET('/detalhes_ajuste/{id}',[AjusteEstoqueController::class,'detail_adjustment'])->middleware(['auth']);
Route::GET('/detalhes_multiplos/{id}',[AjusteEstoqueController::class,'detail_multiple'])->middleware(['auth']);
Route::POST('/adicionarMultiplos', [AjusteEstoqueController::class, 'storeMultiple'])->middleware(['auth'])->middleware(['cors']);
Route::PATCH('/ajustar', [AjusteEstoqueController::class, 'storeAdjustment'])->middleware(['auth'])->middleware(['cors']);



require __DIR__.'/auth.php';
