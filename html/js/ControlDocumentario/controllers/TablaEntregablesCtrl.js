app.controller('TablaEntregablesCtrl', ['$scope', function($scope) {

  te = this;
  te.clase = 'Tecnico';
  te.desactivado = true;

  te.entregables = $scope.$parent.va.entregables;

  $scope.$on("to_childrens", function(event, data){
		te.entregables = data.lista;
    te.clase = data.clase;
    if (te.clase == 'Tecnico') {
      te.desactivado = true;
    } else if (te.clase == 'Gestion') {
      te.desactivado = false;
    } else if (te.clase == 'Comunicacion') {
      te.desactivado = false;
    }
	})
}]);