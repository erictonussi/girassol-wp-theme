export default {
  init() {

    var toggleAffix = function(affixElement, scrollElement) {

      var top = affixElement.parent().offset().top - 10;

      if (scrollElement.scrollTop() >= top){
          affixElement.addClass("position-fixed");
          affixElement.css('top', 10);
      }
      else {
          affixElement.removeClass("position-fixed");
          affixElement.css('top', 'auto');
      }

    };

    $('[data-toggle="affix"]').each(function() {
      var ele = $(this);

      $(window).on('scroll resize', function() {
          toggleAffix(ele, $(this));
      });

      // init
      toggleAffix(ele, $(window));
    });

    /*global angular*/
    angular.module('Pedido', ['angular.filter'])
      .controller('TodoListController', ['$scope', '$http', function ($scope, $http) {
          $scope.categorias = [
            {
              id: 'salgados-de-festa',
              title: 'Escolha os SALGADOS',
            },
            {
              id: 'doces-de-festa',
              title: 'Escolha os DOCES',
            },
            {
              id: 'tortas-doces',
              title: 'Escolha as TORTAS DOCES',
            },
            {
              id: 'tortas-salgadas',
              title: 'Escolha os TORTAS SALGADAS',
            },
          ];
          $http.get('/wp-admin/admin-ajax.php?action=restaurant_menu')
            .then(function (result) {
              $scope.menu = result.data;
            })
        }]);

    angular.bootstrap(document, ['Pedido']);

  },
  finalize() {
    // JavaScript to be fired on the home page, after the init JS
  },
};
