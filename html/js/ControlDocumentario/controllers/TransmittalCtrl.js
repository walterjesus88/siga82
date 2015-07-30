/*Controlador de la vista de gestion de transmittals*/

app.controller('TransmittalCtrl', ['httpFactory', 'configuracionTransmittal',
  function(httpFactory, configuracionTransmittal) {

  var cd = this;
  cd.proyecto_sel = configuracionTransmittal.getProyecto_sel();
  cd.proyecto = {};

  cd.configurarActivo = '';
  cd.anddesActivo = 'active';
  cd.clienteActivo = '';
  cd.contratistaActivo = '';

  cd.formatos = ['Anddes', 'Cerro Verde', 'Barrick'];
  cd.formato_seleccionado = 'Anddes';
  cd.tipos_envio = ['Anddes', 'Cerro Verde', 'Barrick'];
  cd.tipo_seleccionado = 'Anddes';
  cd.clientes = [];
  cd.contactos = [];
  cd.tipos_proyecto = [];
  cd.control_documentario = [];

  cd.cliente_seleccionado = '';
  cd.contacto_seleccionado = '';
  cd.datos_contacto_seleccionado = {
    area: '',
    correo: ''
  };

  cd.transmittal = configuracionTransmittal.getConfiguracion();

  httpFactory.getProyectoById(cd.proyecto_sel)
  .success(function(res) {
    cd.proyecto = res;
  })
  .error(function(res) {
    cd.proyecto = {};
  })

  httpFactory.getClientes()
  .success(function(res) {
    cd.clientes = res;
  })
  .error(function(res) {
    cd.clientes = [];
  });

  httpFactory.getIntegrantes()
  .success(function(res) {
    cd.control_documentario = res;
  })
  .error(function(res) {
    cd.control_documentario = [];
  });

  httpFactory.getTiposProyecto()
  .success(function(res) {
    cd.tipos_proyecto = res;
  })
  .error(function(res) {
    cd.tipos_proyecto = [];
  })

  cd.cambiarPanel = function(panel) {
    if (panel == 'configurar') {
      cd.configurarActivo = 'active';
      cd.anddesActivo = '';
      cd.clienteActivo = '';
      cd.contratistaActivo = '';
    } else if (panel == 'anddes') {
      cd.configurarActivo = '';
      cd.anddesActivo = 'active';
      cd.clienteActivo = '';
      cd.contratistaActivo = '';
    } else if (panel == 'cliente') {
      cd.configurarActivo = '';
      cd.anddesActivo = '';
      cd.clienteActivo = 'active';
      cd.contratistaActivo = '';
    } else if (panel == 'contratista') {
      cd.configurarActivo = '';
      cd.anddesActivo = '';
      cd.clienteActivo = '';
      cd.contratistaActivo = 'active';
    }
  }

  cd.cambiarCliente = function() {
    httpFactory.getContactosByCliente(cd.cliente_seleccionado)
    .success(function(res) {
      cd.contactos = res;
    })
    .error(function(res) {
      cd.contactos = [];
    })
  }

  cd.cambiarContacto = function() {
    cd.contactos.forEach(function(contacto) {
      if (contacto.contactoid == cd.contacto_seleccionado) {
        cd.datos_contacto_seleccionado.area = contacto.puesto_trabajo;
        cd.datos_contacto_seleccionado.correo = contacto.correo;
      }
    })
  }
}]);
