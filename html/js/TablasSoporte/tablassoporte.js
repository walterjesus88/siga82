var app=angular.module('moduloTb',['ngRoute', 'chart.js','ui.bootstrap','ui.bootstrap.tpls','ui.router','checklist-model','dialogs','xeditable','datatables',])
.config(['$routeProvider', function($routeProvider){
	$routeProvider


	.when("/area", {
    	controller: "AreaCtrl",
    	controllerAs: "va",
    	templateUrl: "/soporte/index/listararea"
  	})

	/*.when("/",{
		controller: "SoporteCtrl",
		controllerAs: "vs",
		templateUrl: "/soporte/index/"
	})*/

	.otherwise({redirecTo: '/'});
}])