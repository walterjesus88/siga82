app.controller('LECtrl', ['httpFactory', '$routeParams', 'entregableFactory',
function(httpFactory, $routeParams, entregableFactory) {
  le = this;
  proyectoid = $routeParams.proyecto;
  le.entregables = [];

  httpFactory.getListaEntregables(proyectoid, 'All', 'Tecnico')
  .then(function(data) {
    data.forEach(function(item) {
      entregable = new entregableFactory.Entregable(item);
      entregable.setProyectoId(proyectoid);
      entregable.fecha_a = item.fecha_a;
      entregable.fecha_b = item.fecha_b;
      entregable.fecha_0 = item.fecha_0;
      le.entregables.push(entregable);
    })

  })
  .catch(function(err) {
    le.entregables = [];
  })
}]);
