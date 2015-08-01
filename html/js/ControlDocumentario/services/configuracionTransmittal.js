/*servicio Factory para configurar datos de Transmittal*/
app.factory('configuracionTransmittal', ['httpFactory', function(httpFactory) {

  var proyecto_sel = '';

  var datos = {
    codificacion: '',
    formato: 'anddes',
    tipo_envio: 'anddes',
    proyecto: '',
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

    setCodificacion: function(codificacion) {
      datos.codificacion = codificacion;
    },
    setFormato: function(formato) {
      datos.formato = formato;
    },
    setTipoEnvio: function(tipo_envio) {
      datos.tipo_envio = tipo_envio;
    },
    setProyecto:function(proyecto) {
      datos.proyecto = proyecto;
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
    setLogo: function(logo) {
      datos.logo = logo;
    },

    guardarCambios: function() {
      console.log(datos);
      httpFactory.setConfiguracionTransmittal(datos)
      .success(function(res) {
        alert('Cambios guardados satisfactoriamente');
      })
      .error(function(res) {
        alert('Error al guardar cambios, intentelo denuevo');
      })
    }

  }

  return publico;
}]);
