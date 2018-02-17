@extends('layouts.app')

@section('content')
  <style type="text/css">
    [ng\:cloak], [ng-cloak], [data-ng-cloak], [x-ng-cloak], .ng-cloak, .x-ng-cloak, .ng-hide:not(.ng-hide-animate) {
        display: none !important;
    }
  </style>
  @while(have_posts()) @php(the_post())
    @include('partials.page-header')
    <div class="container">
      <div class="row" ng-controller="TodoListController">
        <div class="col-md-3">
          <!-- <pre>@{{categorias|json}}</pre> -->
          <!-- <pre>@{{categorias|map:'pedidos'|flatten:1|map:'total'|sum|json}}</pre> -->
          <div class="menu" data-toggle="affix">
            <div>
              <label>Valor do pedido</label>
              <p ng-cloak>@{{categorias|map:'pedidos'|flatten:1|map:'total'|sum|currency}}</p>
            </div>
            <hr>
            <div>
              <label>Descontos</label>
              <p ng-cloak>@{{-0|currency}}</p>
            </div>
            <hr>
            <div>
              <label>Valor total</label>
              <p><strong ng-cloak>@{{[(categorias|map:'pedidos'|flatten:1|map:'total'|sum)-0,0]|max|currency}}</strong></p>
            </div>
          </div>
        </div>
        <div class="col-md-9">
          <div class="content-page">
            @include('partials.content-page')
          </div>
          <div>
            <!-- <pre>@{{menu['tortas-salgadas'].products|remove:(pedidos|map:'pedido.produto')|json}}</pre> -->

            <table class="table table-pedidos" width="100%" ng-cloak ng-repeat="categoria in categorias">
              <thead>
                <tr>
                  <th>@{{categoria.title}}</th>
                  <th class="text-center" width="25%">Quantidade</th>
                  <th class="text-center" colspan="2">Valor</th>
                </tr>
              </thead>
              <tbody ng-init="pedidos = categoria.pedidos = [{produto:{price:0}}]">
                <tr ng-repeat="pedido in pedidos">
                  <td width="60%">
                    <select ng-model="pedido.produto" ng-change="($index+1) == pedidos.length && pedidos.push({produto:{price:0}}); !pedido.produto && pedidos.splice($index,1); pedido.qtd = pedido.qtd || 1;" class="form-control border-0">
                      <option ng-if="pedido.produto.product">Remover</option>
                      <option ng-value="pedidos[pedidos.length-1].produto" selected ng-if="!pedido.produto.product">Selecione</option>
                      <option ng-repeat-start="product in menu[categoria.id].products" ng-if="false"></option>
                      <option ng-repeat="price in product.prices" ng-repeat-end ng-value="{product: product, price: price.track}">
                        @{{product.title}} - @{{price.title}}
                      </option>
                    </select>
                  </td>
                  <td>
                    <select ng-model="pedido.qtd" class="form-control border-0">
                      <option value="@{{null}}" ng-selected="true" disabled>Selecione</option>
                      <option ng-repeat="value in []|range:10:1" ng-value="value">@{{value}}</option>
                    </select>
                  </td>
                  <td align="right" class="pl-5 pr-1">R$</td>
                  <td align="right" class="pl-1 pr-5">@{{(pedido.total = pedido.produto.price * pedido.qtd || 0)|currency:''}}</td>
                </tr>
              </tbody>
              <tfoot>
                <tr>
                  <th class="text-center text-uppercase">Total</th>
                  <td class="text-center">@{{(pedidos|map:'qtd'|sum) || 0}}</td>
                  <td align="right" class="pl-5 pr-1">R$</td>
                  <td align="right" class="pl-1 pr-5">@{{pedidos|map:'total'|sum|currency:''}}</td>
                </tr>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.6.6/angular.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-filter/0.5.17/angular-filter.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/angular-i18n/1.6.8/angular-locale_pt-br.min.js"></script>
  @endwhile
@endsection
