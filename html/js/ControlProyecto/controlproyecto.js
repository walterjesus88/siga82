var app= angular.module('moduloCp', ['scrollable-table','ngRoute','chart.js','ui.bootstrap','ui.bootstrap.tpls','ui.router','checklist-model','dialogs','xeditable','angularFileUpload', 'datatables'])
.constant('uiDateConfig', {})

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


