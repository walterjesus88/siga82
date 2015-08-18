/*Creacion del modulo de Control Documentario con la inyeccion de dependencias
de ngRoute para el manejo de rutas y Chart.js para la creacion de graficos
estadisticos*/

var app = angular.module('moduloCd', ['ngRoute', 'chart.js', 'ui.bootstrap',
'ui.bootstrap.tpls', 'ui.router', 'angularFileUpload', 'datatables']);

/*Configuracion de las rutas disponibles en el modulo y asociacion con las
vistas y controladores necesarios*/

app.config(['$routeProvider', function($routeProvider) {
  $routeProvider
  .when("/", {
    controller: "PanelCtrl",
    controllerAs: "panel",
    templateUrl: "/controldocumentario/index/panel"
  })
  .when("/proyectos", {
    controller: "ProyectoCtrl",
    controllerAs: "vp",
    templateUrl: "/controldocumentario/index/proyectos"
  })
  .when("/carpetas", {
    controller: "CarpetasCtrl",
    controllerAs: "reporte",
    templateUrl: "/controldocumentario/index/carpetas"
  })
  .when("/reporte", {
    controller: "ReporteCtrl",
    controllerAs: "vr",
    templateUrl: "/controldocumentario/index/reporte"
  })
  .when("/transmittal/proyecto/:proyecto", {
    controller: "TransmittalCtrl",
    controllerAs: "vt",
    templateUrl: "/controldocumentario/index/transmittal"
  })
  .otherwise({
    redirectTo: '/'
  });
}]);
