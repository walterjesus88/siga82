app.controller('ClienteCtrl', ['httpFactory', '$routeParams',
function(httpFactory, $routeParams) {
  cl = this;

  var proyectoid = $routeParams.proyecto;

  cl.detalles_sin_respuesta = [];

  httpFactory.getDetallesinRespuesta(proyectoid)
  .then(function(data) {
    cl.detalles_sin_respuesta = data;
  })
  .catch(function(err) {
    alert('No se pudo cargar los datos de los entregables emitidos sin respuesta');
  });
}]);
