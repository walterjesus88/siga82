app.factory('detalleFactory', ['httpFactory', '$q', function(httpFactory, $q) {
  var defered = $q.defer();
  var promise = defered.promise;

  publico = {
    Detalle: function(item) {
      this.detalleid = item.detalleid;
      this.transmittal = item.transmittal;
      this.correlativo = item.correlativo;
      this.transmittal_completo = this.transmittal + '-' + this.correlativo;
      this.codigo_anddes = item.codigo_anddes;
      this.codigo_cliente = item.codigo_cliente;
      this.descripcion = item.descripcion_entregable;
      this.revision = item.revision_entregable;
      this.estado_revision = item.estado_revision;
      this.emitido = item.emitido;
      this.fecha = item.fecha;
      this.estado = item.estado_elaboracion;
      this.seleccionado = false;
      this.estilo = '';

      this.select = function() {
        if (this.seleccionado == false) {
          this.seleccionado = true;
          this.estilo = 'post-highlight yellow';
          httpFactory.getDatosContactoxDetalle(this.detalleid)
          .then(function(data) {
            defered.resolve(data);
          })
          .catch(function(err) {
            defered.reject(err);
          });
        } else {
          this.seleccionado = false;
          this.estilo = '';
        }
      }

      this.eliminarDetalle = function() {
        httpFactory.deleteDetalle(this.detalleid)
        .then(function(data) {

        })
        .catch(function(err) {

        })
      }

      this.guardarDetalle = function() {
        httpFactory.updateDetalle(this)
        .then(function(data) {

        })
        .catch(function(err) {

        });
      }

      this.emitirTransmittal = function() {
        httpFactory.setEmitido(this.transmittal, this.correlativo)
        .then(function(data) {

        })
        .catch(function(err) {

        })
      }

      this.imprimirTransmittal = function() {
        if (this.estado == 'Emitido') {
          httpFactory.createPdfTR(this.transmittal, this.correlativo)
          .then(function(data) {
            window.open(data.archivo, '_blank');
          })
          .catch(function(err) {

          });
        } else {
          alert('Aun no se a emitido el transmittal');
        }
      }
    },
    obtenerAtencion: function() {
      return promise;
    }
  }
  return publico;
}]);
