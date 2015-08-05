app.controller('ConfigurarTrCtrl', ['$scope', 'httpFactory', 'configuracionTransmittal',
function($scope, httpFactory, configuracionTransmittal) {

  vc = this;

  vc.proyecto = $scope.$parent.vt.proyecto;
  vc.transmittal = $scope.$parent.vt.transmittal;

  /*formatos, tipos de envio y los seleccionados por defecto; arrays de los
  datos a mostrarse en los combobox de cliente, contactos, tipo de proyecto y
  control documentario*/
  vc.formatos = ['Anddes', 'Cerro Verde', 'Barrick'];
  vc.tipos_envio = ['Anddes', 'Cerro Verde', 'Barrick'];
  vc.clientes = [];
  vc.control_documentario = [];
  vc.tipos_proyecto = [];
  vc.contactos = [];


  /*obtencion de los datos de clientes, control documentario, tipo de proyectos
  y contactos por cliente*/
  httpFactory.getClientes()
  .success(function(res) {
    vc.clientes = res;
  })
  .error(function(res) {
    vc.clientes = [];
  });

  httpFactory.getIntegrantes()
  .success(function(res) {
    vc.control_documentario = [];
    res.forEach(function(integrante) {
      integrante.nombre = integrante.uid.changeFormat();
      vc.control_documentario.push(integrante);
    })
  })
  .error(function(res) {
    vc.control_documentario = [];
  });

  httpFactory.getTiposProyecto()
  .success(function(res) {
    vc.tipos_proyecto = res;
  })
  .error(function(res) {
    vc.tipos_proyecto = [];
  });

  httpFactory.getContactosByCliente(vc.proyecto.clienteid)
  .success(function(res) {
    vc.contactos = res;
  })
  .error(function(res) {
    vc.contactos = [];
  });


  //cambio del campo codificacion cada vez que este valor es actualizado en la vista
  vc.cambiarCodificacion = function() {
    configuracionTransmittal.setCodificacion(vc.transmittal.codificacion);
  }

  //cambio del campo dias de alerta cada vez que es cambiado
  vc.cambiarDiasAlerta = function() {
    configuracionTransmittal.setDiasAlerta(vc.transmittal.dias_alerta);
  }

  /* cambiar los datos del cliente seleccionado en el transmittal y cargar
  los datos de contacto cada vez que se cambia de cliente seleccionado*/
  vc.cambiarCliente = function() {
    vc.clientes.forEach(function(cliente) {
      if (cliente.id == vc.transmittal.clienteid) {
        vc.transmittal.cliente = cliente.nombre;
      }
    });
    configuracionTransmittal.setClienteId(vc.transmittal.clienteid);
    configuracionTransmittal.setCliente(vc.transmittal.cliente);
    httpFactory.getContactosByCliente(vc.transmittal.clienteid)
    .success(function(res) {
      vc.contactos = res;
    })
    .error(function(res) {
      vc.contactos = [];
    });
  }

  /*inicializando la variable de los datos del contacto seleccionado*/
  vc.datos_contacto_seleccionado = {
    area: '',
    correo: ''
  };

  //cargar los datos del contacto de acuerdo al contacto seleccionado
  vc.cambiarContacto = function() {
    configuracionTransmittal.setAtencion(vc.transmittal.atencion);
    vc.contactos.forEach(function(contacto) {
      if (contacto.contactoid == vc.transmittal.atencion) {
        vc.datos_contacto_seleccionado.area = contacto.puesto_trabajo;
        vc.datos_contacto_seleccionado.correo = contacto.correo;
      }
    })
  }

  //guardar los cambios efectuados en la configuracion del transmittal
  vc.guardarConfiguracion = function() {
    configuracionTransmittal.setConfiguracion(vc.transmittal);
    configuracionTransmittal.guardarCambios();
  }

  //metodos para mostrar modales de ingreso de datos
  vc.modalContacto = function() {
    $("#modalcontacto").modal();
  }

  vc.modalLogo = function() {
    $("#edit_logo").modal();
  }

}]);
