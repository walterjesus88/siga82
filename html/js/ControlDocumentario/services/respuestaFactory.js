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

      this.cambiarCodigo = function(codigo) {
        var anddes = '';
        var cliente = '';
        var detalleid = '';
        if (codigo == 'anddes') {
          anddes = this.codigo_anddes;
          detalles_sin_respuesta.forEach(function(detalle) {
            if (detalle.codigo_anddes == anddes) {
              cliente = detalle.codigo_cliente;
              detalleid = detalle.detalleid;
            }
          })
          this.codigo_cliente = cliente;
          this.detalleid = detalleid;
        } else if (codigo == 'cliente') {
          cliente =  this.codigo_cliente;
          detalles_sin_respuesta.forEach(function(detalle) {
            if (detalle.codigo_cliente == cliente) {
              anddes = detalle.codigo_anddes;
              detalleid = detalle.detalleid;
            }
          })
          this.codigo_anddes = anddes;
          this.detalleid = detalleid;
        }
      }

      this.guardarRespuesta = function() {
        httpFactory.setRespuesta(this)
        .then(function(data) {

        })
        .catch(function(data) {

        });
      }

    }
  }
  return publico;
}]);
