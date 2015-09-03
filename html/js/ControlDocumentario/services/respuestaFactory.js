app.factory('respuestaFactory', ['httpFactory', '$routeParams',
function(httpFactory, $routeParams) {

  var detalles_sin_respuesta = [];

  var proyectoid = $routeParams.proyecto;

  httpFactory.getDetallesinRespuesta(proyectoid)
  .then(function(data) {
    detalles_sin_respuesta = data;
  })
  .catch(function(err) {
    detalles_sin_respuesta = [];
  });

  var publico = {
    Respuesta: function() {
      this.detalleid = '';
      this.transmittal = '';
      this.codigo_anddes = '';
      this.codigo_cliente = '';
      this.descripcion = '';
      this.revision = '';
      this.emitido = '';
      this.fecha = '';
<<<<<<< HEAD
=======
      this.seleccionado = false;
>>>>>>> b3ea4adfd828260c124dc421bb9fb09791b12353

      this.cambiarCodigo = function(codigo) {
        var anddes = '';
        var cliente = '';
        var detalleid = '';
        var descripcion = '';
        var revision = '';
        if (codigo == 'anddes') {
          anddes = this.codigo_anddes;
          detalles_sin_respuesta.forEach(function(detalle) {
            if (detalle.codigo_anddes == anddes) {
              cliente = detalle.codigo_cliente;
              detalleid = detalle.detalleid;
              descripcion = detalle.descripcion_entregable;
              revision = detalle.revision;
            }
          })
          this.codigo_cliente = cliente;
          this.detalleid = detalleid;
          this.descripcion = descripcion;
          this.revision = revision;
        } else if (codigo == 'cliente') {
          cliente =  this.codigo_cliente;
          detalles_sin_respuesta.forEach(function(detalle) {
            if (detalle.codigo_cliente == cliente) {
              anddes = detalle.codigo_anddes;
              detalleid = detalle.detalleid;
              descripcion = detalle.descripcion_entregable;
              revision = detalle.revision;
            }
          })
          this.codigo_anddes = anddes;
          this.detalleid = detalleid;
          this.descripcion = descripcion;
          this.revision = revision;
        }
      }

      this.guardarRespuesta = function() {
        httpFactory.setRespuesta(this)
        .then(function(data) {

        })
<<<<<<< HEAD
        .catch(function(data) {
=======
        .catch(function(err) {
>>>>>>> b3ea4adfd828260c124dc421bb9fb09791b12353

        });
      }

<<<<<<< HEAD
=======
      this.actualizarRespuesta = function() {
        httpFactory.updateRespuesta(this)
        .then(function(data) {

        })
        .catch(function(err) {

        });
      }

      this.seleccionarRespuesta = function() {
        if (this.seleccionado == false) {
          this.seleccionado = true;
          this.estilo = 'post-highlight yellow';
        } else {
          this.seleccionado = false;
          this.estilo = '';
        }
      }

      this.eliminarRespuesta = function() {
        httpFactory.deleteRespuesta(this.detalleid)
        .then(function(data) {

        })
        .catch(function(err) {

        });
      }
>>>>>>> b3ea4adfd828260c124dc421bb9fb09791b12353
    }
  }
  return publico;
}]);
