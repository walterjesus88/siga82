var app= angular.module('moduloCp', ['ngRoute', 'chart.js','ui.bootstrap','ui.bootstrap.tpls','ui.router','checklist-model','dialogs','xeditable'])
.config(['$routeProvider', function($routeProvider) {
  $routeProvider

  .when("/", {
    controller: "PanelCtrl",
    controllerAs: "CD",
    templateUrl: "/control/index/panel"
  })

  .when("/curvas", {
    controller: "CurvasCtrl",
    controllerAs: "CP",
    templateUrl: "/control/index/curvas"
  })

  .when("/proyectos", {
    controller: "ProyectoCtrl",
    controllerAs: "vp",
    templateUrl: "/control/index/proyectos"
  })

  .when("/detalle/proyecto/:proyecto", {
    controller: "DetalleCtrl",
    controllerAs: "vt",
    templateUrl: "/control/index/detalle"
  })

  .otherwise({
    redirectTo: '/'
  });
}])


