var app= angular.module('moduloCp', ['ngRoute', 'chart.js','ui.bootstrap','ui.bootstrap.tpls','ui.router','checklist-model','dialogs','xeditable'])
.config(['$routeProvider', function($routeProvider) {
  $routeProvider

  .when("/panel", {
    controller: "PanelCtrl",
    controllerAs: "CD",
    templateUrl: "/control/index/panel"
  })

  .when("/curvas", {
    controller: "CurvasCtrl",
    controllerAs: "CP",
    templateUrl: "/control/index/curvas"
  })

  .when("/", {
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

