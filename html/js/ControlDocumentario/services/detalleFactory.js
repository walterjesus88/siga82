app.factory('detalleFactory', ['httpFactory', function(httpFactory) {

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
      this.cantidad = item.cantidad || 1;

      this.select = function() {
        if (this.seleccionado == false) {
          this.seleccionado = true;
          this.estilo = 'post-highlight yellow';
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

      this.cambiarContacto = function(contactoid) {
        var trans = this.transmittal;
        var corr = this.correlativo;
        httpFactory.setContactoAsignado(trans, corr, contactoid)
        .then(function(data) {

        })
        .catch(function(err) {

        });
      }
    }
  }
  return publico;
}]);
