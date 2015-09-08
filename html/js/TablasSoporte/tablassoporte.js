var app=angular.module('moduloTb',['ngRoute', 'chart.js','ui.bootstrap','ui.bootstrap.tpls','ui.router','checklist-model','dialogs','xeditable',])
.config(['$routeProvider', function($routeProvider){
	$routeProvider


	.when("/listararea", {
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