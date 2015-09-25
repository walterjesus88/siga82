var app= angular.module('moduloRg', ['ngRoute','chart.js','ui.bootstrap','ui.bootstrap.tpls','ui.router','checklist-model','dialogs','xeditable','angularFileUpload', 'datatables'])

.config(['$routeProvider', function($routeProvider) {
  $routeProvider

  .when("/", {
    controller: "GastosCtrl",
    controllerAs: "vg",
    templateUrl: "/rendiciongastos/index/gastos"
  })

    .when("/rendirgastos/numero/:numero", {
    controller: "RendirgastosCtrl",
    controllerAs: "vr",
    templateUrl: "/rendiciongastos/index/rendirgastos"
  })

  .otherwise({
    redirectTo: '/'
  });
}])