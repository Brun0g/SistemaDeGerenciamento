<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Quantity_product_controller;
use App\Http\Controllers\Clientes_controller;
use App\Http\Controllers\Products_controller;
use App\Http\Controllers\Order_controller;
use App\Http\Controllers\Categoria_controller;
use App\Http\Controllers\Graficos_controller;
use App\Http\Controllers\Carrinho_controller;
use App\Http\Controllers\Address_controller;
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
Route::GET('/Products_view_client',[Quantity_product_controller::class,'quantity_product_client'])->middleware(['auth'])->name('Products_view_client');
Route::POST('/Products_view_client',[Quantity_product_controller::class,'criar'])->middleware(['auth']);

// CLIENTE
Route::POST('/Clientes',[Clientes_controller::class,'mainViewClient'])->middleware(['auth'])->middleware(['cors']);
Route::POST('/cadastrarCliente',[Clientes_controller::class,'registerClient'])->middleware(['auth']);
Route::GET('/Cliente/{id}',[Clientes_controller::class,'show'])->middleware(['auth'])->middleware(['cors']);
Route::GET('/Clientes',[Clientes_controller::class,'mainViewClient'])->middleware(['auth'])->name('Clientes')->middleware(['cors']);
Route::GET('/Editar/Cliente/{id}',[Clientes_controller::class,'viewClient'])->middleware(['auth']);
Route::PATCH('/EditarCliente/{id}',[Clientes_controller::class,'editClient'])->middleware(['auth']);
Route::DELETE('/DeletarCliente/{id}',[Clientes_controller::class,'deleteClient'])->middleware(['auth']);


//ENDERECO
Route::POST('/novoEndereco/{id}',[Address_controller::class,'newAddress'])->middleware(['auth']);
Route::GET('/VisualizarEndereco/{id}',[Address_controller::class,'viewAddress'])->middleware(['auth']);
Route::PATCH('/EditarEndereco/{id}',[Address_controller::class,'editAddress'])->middleware(['auth']);
Route::DELETE('/DeletarEndereco/{id}',[Address_controller::class,'deleteAddress'])->middleware(['auth']);

// PRODUTO
Route::POST('/CadastrarProduto', [Products_controller::class, 'newProduct'])->middleware(['auth'])->middleware(['cors']);
Route::POST('/adicionarMultiplos', [Products_controller::class, 'newMultiple'])->middleware(['auth'])->middleware(['cors']);
Route::GET('/Produtos',[Products_controller::class,'ProductsStorageView'])->middleware(['auth'])->name('Produtos');
Route::GET('/Produto/{id}',[Products_controller::class,'showProduct'])->middleware(['auth']);
Route::GET('/EditarProduto/{id}',[Products_controller::class,'viewFilterProducts'])->middleware(['auth']);
Route::GET('/multiplosProdutos',[Products_controller::class,'multipleProductView'])->middleware(['auth'])->name('multiplosProdutos');
Route::GET('/EditarMultiplosProdutos',[Products_controller::class,'EditMultipleProductView'])->middleware(['auth'])->name('EditarMultiplosProdutos');
Route::DELETE('/excluirImagem/{id}',[Products_controller::class,'deleteImage'])->middleware(['auth']);
Route::DELETE('/DeletarProduto/{id}',[Products_controller::class,'deleteProduct'])->middleware(['auth']);
Route::PATCH('/EditarProduto/{id}',[Products_controller::class,'editProduct'])->middleware(['auth']);
Route::PATCH('/EditMultiple', [Products_controller::class, 'EditMultiple'])->middleware(['auth'])->middleware(['cors']);

// PEDIDO
Route::POST('/aprovarPedido/{id_pedido}/{id_cliente}',[Order_controller::class,'finishOrder'])->middleware(['auth']);
Route::DELETE('/ExcluirPedidoCliente/{id_cliente}/{id_product}',[Order_controller::class,'deleteOrderFinish'])->middleware(['auth']);
Route::GET('/pedidofinalizado/{id_pedido}', [Order_controller::class, 'showFinishOrder'])->middleware(['auth']);

//CARRINHO
Route::POST('/finalizarPedido/{id}', [Carrinho_controller::class, 'finishCart'])->middleware(['auth']);
Route::POST('/CadastrarProduto/Cliente/{id}', [Carrinho_controller::class, 'newProductCart'])->middleware(['auth']);
Route::GET('/carrinho/{id}', [Carrinho_controller::class, 'showCart'])->middleware(['auth'])->name('carrinho');
Route::PATCH('/atualizarPedido/{id}', [Carrinho_controller::class, 'updateCart'])->middleware(['auth']);
Route::PATCH('/atualizarPorcentagem/{id}', [Carrinho_controller::class, 'updateDiscountCart'])->middleware(['auth']);
Route::GET('/ExcluirProdutoCliente/{id_cliente}/{id_product}',[Carrinho_controller::class,'deleteCart'])->middleware(['auth']);

// CATEGORIA
Route::GET('/Categoria',[Categoria_controller::class,'categoria_view'])->middleware(['auth'])->name('Categoria');
Route::GET('/Categoria/{id}',[Categoria_controller::class,'showCategory'])->middleware(['auth']);
Route::POST('/CadastrarCategoria', [Categoria_controller::class, 'newCategory'])->middleware(['auth']);
Route::DELETE('/DeletarCategoria/{id}', [Categoria_controller::class, 'deleteCategory'])->middleware(['auth']);
Route::PATCH('/editarCategoria/{id}', [Categoria_controller::class, 'editCategory'])->middleware(['auth']);

// GRÁFICOS
Route::GET('/graficos',[Graficos_controller::class,'viewChart'])->middleware(['auth'])->name('graficos');

// PROMOÇÕES
Route::GET('/promocoes',[PromocoesController::class,'index'])->middleware(['auth'])->name('promocoes');
Route::POST('/adicionarpromocao',[PromocoesController::class,'store'])->middleware(['auth']);
Route::PATCH('/ativarpromocao/{id}',[PromocoesController::class,'update'])->middleware(['auth']);
Route::PATCH('/atualizarpromocao/{id}',[PromocoesController::class,'edit'])->middleware(['auth']);
Route::DELETE('/deletarPromocao/{id}',[PromocoesController::class,'destroy'])->middleware(['auth']);

// ENTRADAS/SAIDAS
Route::GET('/entradas_saidas/{id}',[EntradaController::class,'index'])->middleware(['auth'])->name('entradas_saidas');
Route::PATCH('/entradas_saidas/{id}',[EntradaController::class,'update'])->middleware(['auth']);

// MULTIPLAS ENTRADAS E SAIDAS
Route::GET('/detalhes_ajuste/{id}',[AjusteEstoqueController::class,'show_ajuste'])->middleware(['auth']);
Route::GET('/detalhes_multiplos/{id}',[AjusteEstoqueController::class,'show_multiplos'])->middleware(['auth']);
Route::GET('/visualizar_ajuste',[AjusteEstoqueController::class,'show'])->middleware(['auth'])->name('visualizar_ajuste');
Route::GET('/visualizar_entradas',[AjusteEstoqueController::class,'entradas_view'])->middleware(['auth'])->name('visualizar_entradas');



require __DIR__.'/auth.php';
