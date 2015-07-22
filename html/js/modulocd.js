angular.module('moduloCd', ['ngRoute'])
.config(['$routeProvider', function($routeProvider) {
  $routeProvider
  .when("/", {
    controller: "PanelCtrl",
    controllerAs: "CD",
    templateUrl: "/controldocumentario/index/panel"
  })
  .when("/proyectos", {
    controller: "ProyectoCtrl",
    controllerAs: "CD",
    templateUrl: "/controldocumentario/index/proyectos"
  })
  .when("/asignarcd", {
    controller: "AsignarCtrl",
    controllerAs: "CD",
    templateUrl: "/controldocumentario/index/asignarcd"
  })
  .when("/carpetas", {
    controller: "CarpetasCtrl",
    controllerAs: "CD",
    templateUrl: "/controldocumentario/index/carpetas"
  })
  .when("/reporte", {
    controller: "ReporteCtrl",
    controllerAs: "CD",
    templateUrl: "/controldocumentario/index/reporte"
  })
  .otherwise({
    redirectTo: '/'
  });
}])
.factory('httpFactory', ['$http', function($http) {
  var url = '/controldocumentario/index/';
  var integrantes = [];
  var proyectos = [];

  var publico = {
    ajaxIntegrantes: function() {
      $http.get(url + 'integrantes')
      .success(function(res) {
        integrantes = res;
      })
      .error(function(res) {
        integrantes = [];
      });
    },
    getIntegrantes: function(){
      return integrantes;
    },
    getProyectos: function() {
      return proyectos;
    }
  }

  publico.ajaxIntegrantes();


  return publico;
}])
.controller('PanelCtrl', ['httpFactory', function(httpFactory) {
  var cd = this;
  cd.cantidad_proyectos = {
    total: 0,
    en_proceso: 0,
    stand_by: 0,
    cancelado: 0,
    cerrado: 0
  };
  cd.integrantes = httpFactory.getIntegrantes();
}])
.controller('ProyectoCtrl', ['httpFactory', function(httpFactory) {
  var cd = this;
  cd.proyectos = httpFactory.getProyectos();
}])
.controller('AsignarCtrl', ['httpFactory', function(httpFactory) {

}])
.controller('CarpetasCtrl', ['httpFactory', function(httpFactory) {

}])
.controller('ReporteCtrl', ['httpFactory', function(httpFactory) {

}]);
