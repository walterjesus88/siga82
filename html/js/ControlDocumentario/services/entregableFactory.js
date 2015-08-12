app.factory('entregableFactory', ['httpFactory', 'transmittalFactory',
 function(httpFactory, transmittalFactory) {
  var publico = {
    Entregable: function(codigo, edt, tipo, disciplina, codigo_anddes,
    codigo_cliente, descripcion, revision, estado_revision, transmittal,
    correlativo, emitido, fecha, respuesta_transmittal, respuesta_emitido,
    respuesta_fecha, estado, comentario) {
      this.codigo = codigo;
      this.edt = edt;
      this.tipo = tipo;
      this.disciplina = disciplina;
      this.codigo_anddes = codigo_anddes;
      this.codigo_cliente = codigo_cliente;
      this.descripcion = descripcion;
      this.revision = revision;
      this.estado_revision = estado_revision;
      this.transmittal = transmittal;
      this.correlativo = correlativo;
      this.emitido = emitido;
      this.fecha = fecha;
      this.respuesta_transmittal = respuesta_transmittal;
      this.respuesta_emitido = respuesta_emitido;
      this.respuesta_fecha = respuesta_fecha;
      this.estado = estado;
      this.comentario = comentario;
      this.seleccionado = '';
      this.estilo = '';

      this.actualizarCodigoAnddes = function() {
        httpFactory.setCodigoAnddes(this.codigo, this.codigo_anddes)
        .then(function(data) {
          alert('Codigo Anddes Actualizado.');
        })
        .catch(function(err) {
          alert('No se pudo actualizar el codigo de Anddes');
        });
      }

      this.actualizarCodigoCliente = function() {
        httpFactory.setCodigoCliente(this.codigo, this.codigo_cliente)
        .then(function(data) {
          alert('Codigo Cliente Actualizado.');
        })
        .catch(function(err) {
          alert('No se pudo actualizar el codigo de Cliente');
        });
      }

      this.cambiarRevision = function() {

      }

      this.cambiarEstado = function() {
        this.estado = 'Old';
      }

      this.agregarToTransmittal = function() {
        transmittalFactory.agregarEntregable(this);
      }

      this.agregarRespuesta = function() {

      }

      this.seleccionarEntregable = function() {
        if (this.seleccionado == '') {
          this.seleccionado = 'selected';
          this.estilo = 'post-highlight yellow';
        } else {
          this.seleccionado = '';
          this.estilo = '';
        }
      }
    }
  }
  return publico;
}])
