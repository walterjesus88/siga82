app.controller('TablaEntregablesCtrl', ['$scope', 'entregableFactory',
'$routeParams',
function($scope, entregableFactory, $routeParams) {

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

  te.agregar = function() {
    var entregable = new entregableFactory.Entregable();
    entregable.proyectoid = $routeParams.proyecto;
    entregable.clase = te.clase;
    te.entregables.push(entregable);
  }

  te.editar = function() {
    // body...
  }

  te.eliminar = function() {
    te.entregables.forEach(function(entregable) {
      if (entregable.seleccionado == 'selected') {
        entregable.eliminarEntregable();
      }
    })
  }

  te.guardar = function() {
    te.entregables.forEach(function(entregable) {
      entregable.guardarEntregable();
    })
  }
}]);
