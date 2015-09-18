var app=angular.module('moduloTb',['ngRoute', 'chart.js','ui.bootstrap','ui.bootstrap.tpls','ui.router','checklist-model','dialogs','xeditable','datatables',])
app.config(['$routeProvider', function($routeProvider){
	$routeProvider


	.when("/area", {
    	controller: "AreaCtrl",
    	controllerAs: "va",
    	templateUrl: "/soporte/index/listararea"
  	})

	.when("/cliente",{
		controller: "ClienteCtrl",
		controllerAs: "vc",
		templateUrl: "/soporte/index/listarcliente"
	})

	.when("/unidadminera",{
		controller: "UnidadMineraCtrl",
		controllerAs: "vum",
		templateUrl: "/soporte/index/listarunidadminera"
	})

	.otherwise({redirecTo: '/'});
}])