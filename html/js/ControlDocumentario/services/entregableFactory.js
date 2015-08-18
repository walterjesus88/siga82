app.factory('entregableFactory', ['httpFactory', 'transmittalFactory',
 function(httpFactory, transmittalFactory) {
  var publico = {
    Entregable: function(codigo, edt, tipo, disciplina, codigo_anddes,
    codigo_cliente, descripcion, revision, estado_revision, transmittal,
    correlativo, emitido, fecha, respuesta_transmittal, respuesta_emitido,
    respuesta_fecha, estado, comentario) {
      this.codigo = codigo;
      this.proyectoid = '';
      this.edt = edt;
      this.tipo = tipo;
      this.disciplina = disciplina;
      this.codigo_anddes = codigo_anddes;
      this.codigo_cliente = codigo_cliente;
      this.descripcion = descripcion;
      this.tipo_envio = '';
      this.revision = revision;
      this.estado_revision = estado_revision;
      this.transmittal = transmittal;
      this.correlativo = correlativo;
      this.transmittal_completo = '';
      this.emitido = emitido;
      this.fecha = fecha;
      this.respuesta_transmittal = respuesta_transmittal;
      this.respuesta_emitido = respuesta_emitido;
      this.respuesta_fecha = respuesta_fecha;
      this.estado = estado;
      this.comentario = comentario;
      this.seleccionado = '';
      this.estilo = '';

      if (this.transmittal == '' || this.transmittal == null ||
      this.correlativo == '' || this.correlativo == null) {
        this.transmittal_completo = '';
      } else {
        this.transmittal_completo = this.transmittal + '-' + this.correlativo;
      }

      this.setProyectoId = function(proyectoid) {
        this.proyectoid = proyectoid;
      }

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

      this.agregarToTransmittal = function(transmittal) {
        this.transmittal = transmittal.codificacion;
        this.correlativo = transmittal.correlativo;
        this.tipo_envio = transmittal.tipo_envio;
        if (this.transmittal == '' || this.transmittal == null ||
        this.correlativo == '' || this.correlativo == null) {
          this.transmittal_completo = '';
        } else {
          this.transmittal_completo = this.transmittal + '-' + this.correlativo;
        }
        f = new Date();
        this.fecha = f.Ddmmyyyy();
        this.estado = 'Pendiente por el cliente';
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

      this.guardarDetalle = function() {
        httpFactory.setDetalleTransmittal(this)
        .then(function(data) {

        })
        .catch(function(data) {

        });
      }

      this.guardarEntregable = function() {
        httpFactory.setEntregable(this)
        .then(function(data) {
          alert('Entregable guardado satisfactoriamente');
        })
        .catch(function(err) {
          alert('Error al guardar datos del entregable');
        });
      }

      this.eliminarEntregable = function() {
        httpFactory.deleteEntregable(this.codigo)
        .then(function(data) {
          alert('Entregable eliminado satisfactoriamente');
        })
        .catch(function(err) {
          alert("Error al momento de eliminar entregable");
        });
      }
    }
  }
  return publico;
}])
