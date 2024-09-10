<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

use \App\Services\ClientesServiceInterface;
use \App\Services\CategoriaServiceInterface;
use \App\Services\PedidosServiceInterface;
use \App\Services\ProdutosServiceInterface;
use \App\Services\CarrinhoServiceInterface;
use \App\Services\EnderecoServiceInterface;
use \App\Services\PromotionsServiceInterface;
use \App\Services\EntradasServiceInterface;
use \App\Services\SaidaServiceInterface;
use \App\Services\UserServiceInterface;
use \App\Services\RegistroMultiplosServiceInterface;

use \App\Services\DBClientesService;
use \App\Services\DBCategoriasService;
use \App\Services\DBPedidosService;
use \App\Services\DBProdutosService;
use \App\Services\DBEnderecosService;
use \App\Services\DBPromotionsService;
use \App\Services\DBEntradasService;
use \App\Services\DBSaidaService;
use \App\Services\DBUserService;
use \App\Services\DBRegistroMultiplosService;

use \App\Services\SessionClientesService;
use \App\Services\SessionCategoriaService;
use \App\Services\SessionPedidosService;
use \App\Services\SessionProdutosService;
use \App\Services\SessionCarrinhoService;
use \App\Services\SessionEnderecoService;
use \App\Services\SessionPromotionsService;
use \App\Services\SessionEntradasService;
use \App\Services\SessionSaidaService;
use \App\Services\SessionUserService;
use \App\Services\SessionRegistroMultiplosService;


class AppServiceProvider extends ServiceProvider
{


    public $bindings = [

        // ClientesServiceInterface::class => SessionClientesService::class,
        // CategoriaServiceInterface::class => SessionCategoriaService::class,
        // ProdutosServiceInterface::class => SessionProdutosService::class,
        // PedidosServiceInterface::class => SessionPedidosService::class,
        // EnderecoServiceInterface::class => SessionEnderecoService::class,
        // PromotionsServiceInterface::class => SessionPromotionsService::class,
        // EntradasServiceInterface::class => SessionEntradasService::class,
        // SaidaServiceInterface::class => SessionSaidaService::class,
        // RegistroMultiplosServiceInterface::class => SessionRegistroMultiplosService::class,


        ClientesServiceInterface::class => DBClientesService::class,
        CategoriaServiceInterface::class => DBCategoriasService::class,
        ProdutosServiceInterface::class => DBProdutosService::class,
        PedidosServiceInterface::class => DBPedidosService::class,
        EnderecoServiceInterface::class => DBEnderecosService::class,
        PromotionsServiceInterface::class => DBPromotionsService::class,
        EntradasServiceInterface::class => DBEntradasService::class,
        SaidaServiceInterface::class => DBSaidaService::class,
        RegistroMultiplosServiceInterface::class => DBRegistroMultiplosService::class,

        UserServiceInterface::class => DBUserService::class,
        CarrinhoServiceInterface::class => SessionCarrinhoService::class,

    ];

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {

    }
}