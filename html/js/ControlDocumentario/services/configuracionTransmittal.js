/*servicio Factory para configurar datos de Transmittal*/
app.factory('configuracionTransmittal', ['httpFactory', function(httpFactory) {

  var proyecto_sel = '';

  var datos = {
    codificacion: '',
    formato: 'anddes',
    tipo_envio: 'anddes',
    cliente: '',
    control_documentario: '',
    atencion: '',
    dias_alerta: '',
    area: '',
    tipo_proyecto: '',
    correo: '',
    logo: ''
  }

  var publico = {
    getConfiguracion: function() {
      return datos;
    },

    setConfiguracion: function(codificacion, formato, tipo_envio, cliente,
      control_documentario, atencion, dias_alerta, area, tipo_proyecto,
      correo, logo) {
      datos.codificacion = codificacion;
      datos.formato = formato;
      datos.tipo_envio = tipo_envio;
      datos.cliente = cliente;
      datos.control_documentario = control_documentario;
      datos.atencion = atencion;
      datos.dias_alerta = dias_alerta;
      datos.area = area;
      datos.tipo_proyecto = area;
      datos.correo = correo;
      datos.logo = logo;
    },

    getProyecto_sel: function() {
      return proyecto_sel;
    },

    setProyecto_sel: function(proyectoid) {
      proyecto_sel = proyectoid;
    },

    cambiarControlDocumentario: function(control_documentario) {
      datos.control_documentario = control_documentario;
      httpFactory.updateConfiguracionTr()
      .success(function(res) {

      })
      .error(function(res) {
        alert('No se puedo guargar los datos, intentelo nuevamente');
      })
    }
  }

  return publico;
}]);
