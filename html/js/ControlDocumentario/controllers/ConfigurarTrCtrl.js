app.controller('ConfigurarTrCtrl', ['$routeParams', 'httpFactory', 'transmittalFactory',
'proyectoFactory', '$modal',
function($routeParams, httpFactory, transmittalFactory, proyectoFactory, $modal) {

  vc = this;

  transmittalFactory.cargarTransmittal($routeParams.proyecto);
  //obtencion de los datos de configuracion del transmittal
  transmittalFactory.getConfiguracion()
  .then(function(data) {
    vc.transmittal = data;

    proyectoFactory.getDatosProyecto($routeParams.proyecto)
    .then(function(data) {
      vc.proyecto = data;
      vc.transmittal.proyecto = vc.proyecto.codigo;
      //cargar los datos del transmittal con los datos del proyecto
      if (vc.transmittal.codificacion == '' || vc.transmittal.codificacion == null ||
      vc.transmittal.codificacion == undefined) {
        vc.transmittal.clienteid = vc.proyecto.clienteid;
        vc.transmittal.cliente = vc.proyecto.cliente;
        vc.transmittal.control_documentario = vc.proyecto.control_documentario;
        vc.transmittal.tipo_proyecto = vc.proyecto.tipo_proyecto;
      }

      vc.control_documentario = vc.transmittal.control_documentario.changeFormat();
      //obtencion del numero correlativo que corresponderia a este transmittal
      listarContactos(data.clienteid);
    })
    .catch(function(err) {
      alert('No se pudo cargar los datos del proyecto');
    });
  })
  .catch(function(err) {

  });

  /*formatos, tipos de envio y los seleccionados por defecto; arrays de los
  datos a mostrarse en los combobox de contactos y tipo de proyecto*/
  vc.formatos = [];
  vc.tipos_envio = [];
  vc.tipos_proyecto = [];
  vc.contactos = [];

  //funcion para obtener los contactos de un cliente
  var listarContactos = function(clienteid) {
    httpFactory.getContactosByCliente(clienteid)
    .then(function(data) {
      vc.contactos = data;
      //poniendo como atencion al primer contacto de la lista y obteniendo sus datos
      if (vc.contactos.length != 0) {
        vc.transmittal.atencion = vc.contactos[0].contactoid;
        transmittalFactory.setAtencion(vc.transmittal.atencion);
        vc.contactos.forEach(function(contacto) {
          if (contacto.contactoid == vc.transmittal.atencion) {
            vc.datos_contacto_seleccionado.area = contacto.puesto_trabajo;
            vc.datos_contacto_seleccionado.correo = contacto.correo;
          }
        })
      }
    })
    .catch(function(err) {
      vc.contactos = [];
    });
  }


  //obtencion de los datos de tipo de proyectos y tipos de envio

  httpFactory.getTiposProyecto()
  .then(function(data) {
    vc.tipos_proyecto = data;
    console.log("tipo de gasto " + vc.tipos_proyecto)
  })
  .catch(function(err) {
    vc.tipos_proyecto = [];
  });

  httpFactory.getTiposEnvio()
  .then(function(data) {
    vc.formatos = data;
    vc.tipos_envio = data;
  })
  .catch(function(err) {
    vc.formatos = [];
    vc.tipos_envio = [];
  })

  /*inicializando la variable de los datos del contacto seleccionado*/
  vc.datos_contacto_seleccionado = {
    area: '',
    correo: ''
  };

  //cambio del campo codificacion cada vez que este valor es actualizado en la vista
  vc.cambiarCodificacion = function() {
    transmittalFactory.setCodificacion(vc.transmittal.codificacion);
  }

  //cambio del campo dias de alerta cada vez que es cambiado
  vc.cambiarDiasAlerta = function() {
    transmittalFactory.setDiasAlerta(vc.transmittal.dias_alerta);
  }


  //cargar los datos del contacto de acuerdo al contacto seleccionado
  vc.cambiarContacto = function() {
    transmittalFactory.setAtencion(vc.transmittal.atencion);
    vc.contactos.forEach(function(contacto) {
      if (contacto.contactoid == vc.transmittal.atencion) {
        vc.datos_contacto_seleccionado.area = contacto.puesto_trabajo;
        vc.datos_contacto_seleccionado.correo = contacto.correo;
      }
    })
  }

  //metodos para mostrar modales de ingreso de datos
  vc.editarContacto = function() {
    var modalInstance = $modal.open({
      animation: true,
      controller: 'ModalContactoCtrl',
      controllerAs: 'mc',
      templateUrl: '/controldocumentario/index/modalcontacto',
      size: 'md',
      resolve: {
        cliente: function () {
          return vc.transmittal.clienteid;
        }
      }
    });

    modalInstance.result.then(function () {
    }, function () {
      listarContactos(vc.transmittal.clienteid);
    });
  }

  vc.editarTipoEnvio = function() {
    var modalInstance = $modal.open({
      animation: true,
      controller: 'ModalTipoEnvioCtrl',
      controllerAs: 'mt',
      templateUrl: '/controldocumentario/index/modaltipoenvio',
      size: 'lg'
    });
  }

}]);
