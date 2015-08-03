/*Controlador de la vista de gestion de transmittals*/

app.controller('TransmittalCtrl', ['httpFactory', 'configuracionTransmittal',
  '$routeParams', '$modal', function(httpFactory, configuracionTransmittal,
  $routeParams, $modal) {

  /*referencia del scope, obtencion del proyecto seleccionado y el objeto que
  contendra los datos del proyecto*/
  var cd = this;
  cd.proyecto_sel = $routeParams.proyecto;
  cd.proyecto = {};

  //panel visible por defecto aplicando la clase css active
  cd.configurarActivo = '';
  cd.anddesActivo = 'active';
  cd.clienteActivo = '';
  cd.contratistaActivo = '';

  /*formatos, tipos de envio y los seleccionados por defecto; arrays de los
  datos a mostrarse en los combobox de cliente, contactos, tipo de proyecto y
  control documentario*/
  cd.formatos = ['Anddes', 'Cerro Verde', 'Barrick'];
  cd.formato_seleccionado = 'Anddes';
  cd.tipos_envio = ['Anddes', 'Cerro Verde', 'Barrick'];
  cd.tipo_seleccionado = 'Anddes';
  cd.clientes = [];
  cd.contactos = [];
  cd.tipos_proyecto = [];
  cd.control_documentario = [];

  //datos seleccionados por defecto de los combobox
  cd.cliente_seleccionado = '';
  cd.contacto_seleccionado = '';
  cd.datos_contacto_seleccionado = {
    area: '',
    correo: ''
  };

  //obtencion de los datos de configuracion del transmittal
  cd.transmittal = configuracionTransmittal.getConfiguracion();

  //carga de los datos del proyecto seleccionado
  httpFactory.getProyectoById(cd.proyecto_sel)
  .success(function(res) {
    cd.proyecto = res;
  })
  .error(function(res) {
    cd.proyecto = {};
  })

  /*obtencion de los datos de clientes, control documentario, tipo de proyectos
  y contactos por cliente*/
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

  httpFactory.getContactosByCliente(cd.proyecto.clienteid)
  .success(function(res) {
    cd.contactos = res;
  })
  .error(function(res) {
    cd.contactos = [];
  })

  //metodo para cambiar el panel visible
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

  //cambio del campo codificacion cada vez que este valor es actualizado en la vista
  cd.cambiarCodificacion = function() {
    configuracionTransmittal.setCodificacion(cd.transmittal.codificacion);
  }

  //cargar los datos de contacto cada vez que se cambia de cliente seleccionado
  cd.cambiarCliente = function() {
    httpFactory.getContactosByCliente(cd.proyecto.clienteid)
    .success(function(res) {
      cd.contactos = res;
    })
    .error(function(res) {
      cd.contactos = [];
    })
  }

  //cargar los datos del contacto de acuerdo al contacto seleccionado
  cd.cambiarContacto = function() {
    cd.contactos.forEach(function(contacto) {
      if (contacto.contactoid == cd.contacto_seleccionado) {
        cd.datos_contacto_seleccionado.area = contacto.puesto_trabajo;
        cd.datos_contacto_seleccionado.correo = contacto.correo;
      }
    })
  }

  //guardar los cambios efectuados en la configuracion del transmittal
  cd.guardarConfiguracion = function() {
    configuracionTransmittal.guardarCambios();
  }

  //metodos para mostrar modales de ingreso de datos
  cd.modalContacto = function() {
    $("#modalcontacto").modal();
  }

  cd.modalLogo = function() {
    $("#edit_logo").modal();
  }
}]);
