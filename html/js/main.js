angular.module('angularSuma',[])
.controller('mainController', function ($scope, $http) {
	
	scope = this;
	scope.elementos = [];
	scope.resultado = 0;
	
	scope.agregar = function() {
		scope.elementos.push(0);
	}

	scope.sumar = function() {
		scope.resultado = 0;
		for (var i = 0; i<scope.elementos.length; i++) {
			scope.resultado = scope.resultado + parseInt(scope.elementos[i]);
		};
	}
});