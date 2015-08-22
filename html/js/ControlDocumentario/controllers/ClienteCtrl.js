app.controller('ClienteCtrl', ['httpFactory', '$routeParams', 'respuestaFactory',
'transmittalFactory',
function(httpFactory, $routeParams, respuestaFactory, transmittalFactory) {
  cl = this;

  var proyectoid = $routeParams.proyecto;

  cl.detalles_sin_respuesta = [];

  cl.emitidos = [];
  cl.respuestas = [];

  cl.emisiones = [];

  listarRespuestas = function() {
    httpFactory.getRespuestas(proyectoid)
    .then(function(data){
      cl.emitidos = data;
    })
    .catch(function(err) {
      cl.emitidos = [];
    })
  }

  listarEmisiones = function() {
    var transmittal = transmittalFactory.getConfiguracion();
    httpFactory.getEmisionesByTipo(transmittal.tipo_envio)
    .then(function(data) {
      cl.emisiones = data;
    })
    .catch(function(err) {
      cl.emisiones = [];
    });
  }

  //obtener los datos de respuestas emitidas y tipos de emision disponibles para estos entregables
  listarRespuestas();
  listarEmisiones();

  cl.agregar = function() {
    httpFactory.getDetallesinRespuesta(proyectoid)
    .then(function(data) {
      cl.detalles_sin_respuesta = data;
    })
    .catch(function(err) {
      alert('No se pudo cargar los datos de los entregables emitidos sin respuesta');
    });
    respuesta = new respuestaFactory.Respuesta();
    cl.respuestas.push(respuesta);
  }

  cl.guardar = function() {
    cl.respuestas.forEach(function(respuesta) {
      respuesta.guardarRespuesta();
      cl.alerts.push({type: 'success', msg: 'Datos guardados satisfactoriamente'});
    });
  }

  cl.alerts = [];

  cl.closeAlert = function(index) {
    cl.alerts.splice(index, 1);
  }
}]);
