app.controller('ClienteCtrl', ['httpFactory', '$routeParams', 'respuestaFactory',
'transmittalFactory',
function(httpFactory, $routeParams, respuestaFactory, transmittalFactory) {
  cl = this;

  var proyectoid = $routeParams.proyecto;
  var transmittal = {};

  cl.detalles_sin_respuesta = [];

<<<<<<< HEAD
=======
  cl.emitidos = [];
>>>>>>> b3ea4adfd828260c124dc421bb9fb09791b12353
  cl.respuestas = [];

  cl.emisiones = [];

<<<<<<< HEAD
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

  listarEmisiones();

  httpFactory.getDetallesinRespuesta(proyectoid)
  .then(function(data) {
    cl.detalles_sin_respuesta = data;
  })
  .catch(function(err) {
    alert('No se pudo cargar los datos de los entregables emitidos sin respuesta');
  });

  cl.agregar = function() {
=======
  listarRespuestas = function() {
    httpFactory.getRespuestas(proyectoid)
    .then(function(data){
      data.forEach(function(resp) {
        respuesta = new respuestaFactory.Respuesta();
        respuesta.transmittal = resp.transmittal;
        respuesta.detalleid = resp.detalleid;
        respuesta.codigo_anddes = resp.codigo_anddes;
        respuesta.codigo_cliente = resp.codigo_cliente;
        respuesta.descripcion = resp.descripcion;
        respuesta.emitido = resp.emitido;
        respuesta.revision = resp.revision;
        respuesta.fecha = resp.fecha;
        cl.emitidos.push(respuesta);
      })
    })
    .catch(function(err) {
      cl.emitidos = [];
    })
  }

  listarEmisiones = function() {
    transmittalFactory.getConfiguracion()
    .then(function(data) {
      transmittal = data;

      httpFactory.getEmisionesByTipo(transmittal.tipo_envio)
      .then(function(data) {
        cl.emisiones = data;
      })
      .catch(function(err) {
        cl.emisiones = [];
      });
    })
    .catch(function(err) {

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
>>>>>>> b3ea4adfd828260c124dc421bb9fb09791b12353
    respuesta = new respuestaFactory.Respuesta();
    cl.respuestas.push(respuesta);
  }

  cl.guardar = function() {
<<<<<<< HEAD
    cl.respuestas.forEach(function(respuesta) {
      respuesta.guardarRespuesta();
=======
    cl.emitidos.forEach(function(respuesta) {
      respuesta.actualizarRespuesta();
    })
    cl.respuestas.forEach(function(respuesta) {
      if (respuesta.transmittal != null && respuesta.transmittal != undefined &&
      respuesta.transmittal != '') {
        respuesta.guardarRespuesta();
      }
    });
    cl.alerts.push({type: 'success', msg: 'Datos guardados satisfactoriamente'});
  }

  cl.alerts = [];

  cl.closeAlert = function(index) {
    cl.alerts.splice(index, 1);
  }

  cl.eliminar = function() {
    var temp = [];
    for (var i = 0; i < cl.emitidos.length; i++) {
      if (cl.emitidos[i].seleccionado == true) {
        cl.emitidos[i].eliminarRespuesta();
      }  else if (cl.emitidos[i].seleccionado == false) {
        temp.push(cl.emitidos[i]);
      }
    }
    cl.emitidos = temp;
    cl.alerts.push({type: 'success', msg: 'Datos eliminados satisfactoriamente'});
  }

  cl.cancelar = function() {
    cl.respuestas = [];
    cl.emitidos = [];
    listarRespuestas();
  }

  cl.imprimir = function() {
    httpFactory.createPdfCliente(proyectoid)
    .then(function(data) {
      window.open(data.archivo, '_blank');
    })
    .catch(function(err) {

>>>>>>> b3ea4adfd828260c124dc421bb9fb09791b12353
    });
  }
}]);
