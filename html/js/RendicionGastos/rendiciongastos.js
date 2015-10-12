var app= angular.module('moduloRg', ['ngRoute','chart.js','ui.bootstrap','ui.bootstrap.tpls','ui.router','checklist-model','dialogs','xeditable','angularFileUpload', 'datatables'])
.constant('uiDateConfig', {})

.config(['$routeProvider', function($routeProvider) {
  $routeProvider

  .when("/", {
    controller: "GastosCtrl",
    controllerAs: "vg",
    templateUrl: "/rendiciongastos/index/gastos"
  })

  .when("/rendir/fecha/:fecha/numero/:numero/", {
    controller: "RendirGastosCtrl",
    controllerAs: "vrg",
    templateUrl: "/rendiciongastos/index/rendir"
  })

  .otherwise({
    redirectTo: '/'
  });
}])