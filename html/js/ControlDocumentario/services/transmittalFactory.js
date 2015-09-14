/*servicio Factory para configurar datos de Transmittal con include de
httpFactory para poder enviar los datos de configuracion al servidor*/
app.factory('transmittalFactory', ['httpFactory', '$q', function(httpFactory, $q) {

  var defered = $q.defer();
  var promise = defered.promise;

  var datos = {
    codificacion: '',
    correlativo: '',
    formato: 'ANDDES',
    tipo_envio: 'ANDDES',
    proyecto: '',
    clienteid: '',
    cliente: '',
    control_documentario: '',
    atencion: '',
    dias_alerta: '',
    area: '',
    tipo_proyecto: '',
    correo: '',
    modo_envio: 'F',
    estado_elaboracion: 'En Proceso'
  };

  var publico = {
    cargarTransmittal: function(proyectoid) {
      httpFactory.getTransmittal(proyectoid)
      .then(function(data) {
        publico.setConfiguracion(data);
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.resolve(datos);
      });
    },

    getConfiguracion: function() {
      return promise;
    },

    setConfiguracion: function(transmittal) {
      datos.codificacion = transmittal.codificacion;
      datos.correlativo = transmittal.correlativo;
      datos.formato = transmittal.formato || datos.formato;
      datos.tipo_envio = transmittal.tipo_envio || datos.tipo_envio;
      datos.clienteid = transmittal.clienteid;
      datos.cliente = transmittal.cliente;
      datos.control_documentario = transmittal.control_documentario;
      datos.atencion = transmittal.atencion;
      datos.dias_alerta = transmittal.dias_alerta;
      datos.area = transmittal.area;
      datos.tipo_proyecto = transmittal.tipo_proyecto;
      datos.correo = transmittal.correo;
    },

    setCodificacion: function(codificacion) {
      datos.codificacion = codificacion;
    },
    setFormato: function(formato) {
      datos.formato = formato;
    },
    setTipoEnvio: function(tipo_envio) {
      datos.tipo_envio = tipo_envio;
    },
    getProyecto: function() {
      return datos.proyecto;
    },
    setProyecto: function(proyecto) {
      datos.proyecto = proyecto;
    },
    setClienteId: function(clienteid) {
      datos.clienteid = clienteid;
    },
    setCliente: function(cliente) {
      datos.cliente = cliente;
    },
    setControlDocumentario: function(control_documentario) {
      datos.control_documentario = control_documentario;
    },
    setAtencion: function(atencion) {
      datos.atencion = atencion;
    },
    setDiasAlerta: function(dias_alerta) {
      datos.dias_alerta = dias_alerta;
    },
    setArea: function(area) {
      datos.area = area;
    },
    setTipoProyecto: function(tipo_proyecto) {
      datos.tipo_proyecto = tipo_proyecto;
    },
    setCorreo: function(correo) {
      datos.correo = correo;
    },
    setModoEnvio: function(modo_envio) {
      datos.modo_envio = modo_envio;
      httpFactory.setModoEnvio(datos.codificacion, datos.correlativo, datos.modo_envio)
      .then(function(data) {
        alert('Tipo de envio guardado');
      })
      .catch(function(err) {
        alert('No se pudo guardar el tipo de envio');
      });
    },

    guardarCambios: function() {
      httpFactory.setConfiguracionTransmittal(datos)
      .then(function(data) {
        alert('Transmittal '+ datos.codificacion + '-'  + datos.correlativo +
        ' creado satisfactoriamente');
      })
      .catch(function(err) {
        alert('No se pudo crear el transmittal solicitado');
      });
    },
    emitirTransmittal: function() {
      httpFactory.setEmitido(datos.codificacion, datos.correlativo)
      .then(function(data) {
        datos.estado_elaboracion = 'Emitido';
        alert('Transmittal emitido');
      })
    },
    reenviarTransmittal: function() {
      //retomar los valores de este transmittal para generar otro con estado en elaboracion
    },
    imprimirTransmittal: function() {
      if (datos.estado_elaboracion == 'Emitido') {
        httpFactory.createPdfTR(datos.codificacion, datos.correlativo)
        .then(function(data) {
          window.open(data.archivo, '_blank');
        })
        .catch(function(err) {

        });
      } else {
        alert('Aun no se a emitido el transmittal');
      }

    },
    getTransmittal: function(codificacion, correlativo) {
      var defered = $q.defer();
      var promise = defered.promise;
      httpFactory.getDataTransmittal(codificacion, correlativo)
      .then(function(data) {
        defered.resolve(data);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;
    }
  }

  return publico;
}]);
