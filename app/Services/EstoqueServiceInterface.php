<?php

namespace App\Services;

interface EstoqueServiceInterface
{
   function adicionarAjuste();
   function adicionarMultiplos();
   function adicionarAjusteIndividuais($ajuste_id, $produto_id, $quantidade);
   function listarAjuste($ajuste_id, $provider_user, $provider_produto);
   function buscarAjuste($ajuste_id);
   function atualizarEstoque($produto_id, $quantidade, $entrada_ou_saida, $observacao, $provider_entradas_saidas, $pedido_id,  $ajuste_id, $multiplo_id);
}
