var app= angular.module('moduloRg', ['ngRoute','chart.js','ui.bootstrap','ui.bootstrap.tpls','ui.router','checklist-model','dialogs','xeditable','angularFileUpload', 'datatables'])
.config(['$routeProvider', function($routeProvider) {
  $routeProvider

  /*.when("/panel", {
    controller: "PanelCtrl",
    controllerAs: "CD",
    templateUrl: "/control/index/panel"
  })*/


  .when("/", {
    controller: "GastosCtrl",
    controllerAs: "vg",
    templateUrl: "/rendiciongastos/index/gastos"
  })

  .otherwise({
    redirectTo: '/'
  });
}])


