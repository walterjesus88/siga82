app.factory('entregableFactory', ['httpFactory', 'transmittalFactory',
 function(httpFactory, transmittalFactory) {
  var publico = {
    Entregable: function(item) {
      if (item) {
        this.codigo = item.cod_le;
        this.proyectoid = '';
        this.edt = item.edt;
        this.tipo = item.tipo_documento;
        this.disciplina = item.disciplina;
        this.codigo_anddes = item.codigo_anddes;
        this.codigo_cliente = item.codigo_cliente;
        this.descripcion = item.descripcion_entregable;
        this.tipo_envio = '';
        this.revision = item.revision_documento;
        this.estado_revision = item.estado_revision;
        this.transmittal = item.transmittal;
        this.correlativo = item.correlativo;
        this.transmittal_completo = '';
        this.emitido = item.emitido;
        this.fecha = item.fecha;
        this.respuesta_transmittal = item.respuesta_transmittal;
        this.respuesta_emitido = item.respuesta_emitido;
        this.respuesta_fecha = item.respuesta_fecha;
        this.estado = item.estado;
        this.comentario = item.comentario;
        this.clase = item.clase;
        this.seleccionado = '';
        this.estilo = '';
      }


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
        if (this.codigo != '' && this.codigo !=null && this.codigo !=undefined) {
          httpFactory.setCodigoAnddes(this.codigo, this.codigo_anddes)
          .then(function(data) {
            alert('Codigo Anddes Actualizado.');
          })
          .catch(function(err) {
            alert('No se pudo actualizar el codigo de Anddes');
          });
        }
      }

      this.actualizarCodigoCliente = function() {
        if (this.codigo != '' && this.codigo !=null && this.codigo !=undefined) {
          httpFactory.setCodigoCliente(this.codigo, this.codigo_cliente)
          .then(function(data) {
            alert('Codigo Cliente Actualizado.');
          })
          .catch(function(err) {
            alert('No se pudo actualizar el codigo de Cliente');
          });
        }
      }

      this.actualizarTipo = function() {
        if (this.codigo != '' && this.codigo !=null && this.codigo !=undefined) {
          httpFactory.setTipoEntregable(this.codigo, this.tipo)
          .then(function(data) {
            alert('Tipo de Documento Actualizado.');
          })
          .catch(function(err) {
            alert('No se pudo actualizar el tipo de documento');
          });
        }
      }

      this.actualizarDisciplina = function() {
        if (this.codigo != '' && this.codigo !=null && this.codigo !=undefined) {
          httpFactory.setDisciplina(this.codigo, this.disciplina)
          .then(function(data) {
            alert('Disciplina Actualizada.');
          })
          .catch(function(err) {
            alert('No se pudo actualizar la Disciplina');
          });
        }
      }

      this.actualizarDescripcion = function() {
        if (this.codigo != '' && this.codigo !=null && this.codigo !=undefined) {
          httpFactory.setDescripcion(this.codigo, this.descripcion)
          .then(function(data) {
            alert('La Descripción ha sido actualizada.');
          })
          .catch(function(err) {
            alert('No se pudo actualizar la descripción');
          });
        }
      }

      this.actualizarRevision = function() {
        if (this.codigo != '' && this.codigo !=null && this.codigo !=undefined) {
          httpFactory.setRevisionEntregable(this.codigo, this.revision)
          .then(function(data) {
            alert('La revisión ha sido actualizada.');
          })
          .catch(function(err) {
            alert('No se pudo actualizar la revisión');
          });
        }
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

        })
        .catch(function(err) {

        });
      }

      this.eliminarEntregable = function() {
        httpFactory.deleteEntregable(this.codigo)
        .then(function(data) {
          alert('Entregable eliminado satisfactoriamente');
        })
        .catch(function(err) {
          alert('No se pudo eliminar el entregable');
        });
      }

      this.generarRevision = function(revision) {
        httpFactory.createRevision(this.codigo, revision)
        .then(function(data) {

        })
        .catch(function(err) {

        });
      }
    }
  }
  return publico;
}])
