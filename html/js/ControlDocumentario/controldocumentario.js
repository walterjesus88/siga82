/*Creacion del modulo de Control Documentario con la inyeccion de dependencias
de ngRoute para el manejo de rutas y Chart.js para la creacion de graficos
estadisticos*/

var app = angular.module('moduloCd', ['ngRoute', 'chart.js', 'ui.bootstrap']);

/*Configuracion de las rutas disponibles en el modulo y asociacion con las
vistas y controladores necesarios*/

app.config(['$routeProvider', function($routeProvider) {
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
  .when("/transmittal/:proyecto", {
    controller: "TransmittalCtrl",
    controllerAs: "CD",
    templateUrl: "/controldocumentario/index/transmittal"
  })
  .otherwise({
    redirectTo: '/'
  });
}]);
