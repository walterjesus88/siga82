app.controller('TablaEntregablesCtrl', ['$scope', 'entregableFactory', 'httpFactory',
'$routeParams',
function($scope, entregableFactory, httpFactory, $routeParams) {

  te = this;
  te.clase = 'Tecnico';
  te.desactivado = true;
  te.colspan_1 = 9;
  te.entregables = $scope.$parent.va.entregables;

  $scope.$on("to_childrens", function(event, data){

		te.entregables = data.lista;

    te.clase = data.clase;
    if (te.clase == 'Tecnico') {
      te.desactivado = true;
      te.colspan_1 = 9;
    } else if (te.clase == 'Gestion') {
      te.desactivado = false;
      te.colspan_1 = 8;
    } else if (te.clase == 'Comunicacion') {
      te.desactivado = false;
      te.colspan_1 = 8;
    }
	})

  httpFactory.getDisciplinas($routeParams.proyecto)
  .then(function(data) {
    te.disciplinas = data;
  })
  .catch(function(err) {
    te.disciplinas = [];
  });

  te.agregar = function() {
    var entregable = new entregableFactory.Entregable();
    entregable.proyectoid = $routeParams.proyecto;
    entregable.clase = te.clase;
    te.entregables.push(entregable);
  }

  te.eliminar = function() {
    for (var i = 0; i < te.entregables.length; i++) {
      if (te.entregables[i].seleccionado == 'selected') {
        te.entregables[i].eliminarEntregable();
        te.entregables.splice(i, 1);
      }
    }
  }

  te.guardar = function() {
    te.entregables.forEach(function(entregable) {
      entregable.guardarEntregable();
    });
    alert('Cambios guardados satisfactoriamente');
  }

  te.cancelar = function() {
    $scope.$emit('to_parents', {asd: 'asf'});
  }
}]);
