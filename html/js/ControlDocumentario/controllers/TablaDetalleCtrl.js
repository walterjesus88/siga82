app.controller('TablaDetalleCtrl', ['httpFactory', '$routeParams', '$scope',
'detalleFactory', 'transmittalFactory', '$modal',
function(httpFactory, $routeParams, $scope, detalleFactory, transmittalFactory,
$modal) {

  td = this;

  proyectoid = $routeParams.proyecto;

  td.detalles = [];

  listarDetalles = function() {
    httpFactory.getDetallesGenerados(proyectoid)
    .then(function(data) {
      td.detalles = [];
      data.forEach(function(item) {
        detalle = new detalleFactory.Detalle(item);
        td.detalles.push(detalle);
      })
    })
  }

  listarDetalles();

  $scope.$on("recarga_detalles", function(event, data){
    listarDetalles();
  })

  //listas de revisiones y emisiones
  td.revisiones = ['A', 'B', 'C', 'D', 'E', '0'];

  //carga de las emisiones disponible para el formato anddes
  td.emisiones = [];

  listarEmisiones = function() {
    httpFactory.getEmisionesByTipo('ANDDES')
    .then(function(data) {
      td.emisiones = data;
    })
    .catch(function(err) {
      td.emisiones = [];
    });

  }

  listarEmisiones();

  //modos de envio disponibles (fisico o orreo electronico)
  td.modo_seleccionado = '';
  td.modos = [{codigo: 'F', nombre: 'Físico'}, {codigo: 'C', nombre: 'Correo'}];

  //objeto que se visualizara al pie de los entregables a emitir
  td.atencion = {
    detalleid: '',
    codigo: '',
    nombre: '',
    area: '',
    correo: ''
  }

  httpFactory.getContactosByProyecto(proyectoid)
  .then(function(data) {
    td.contactos = data;
  })
  .catch(function(err) {
    td.contactos = [];
  });

  //cambiar el modo de envio del transmittal
  td.cambiarModoEnvio = function() {
    var j = 0;
    for (var i = 0; i < td.detalles.length; i++) {
      if (td.detalles[i].seleccionado == true) {
        j++;
        httpFactory.setModoEnvio(td.detalles[i].transmittal, td.detalles[i].correlativo, td.modo_seleccionado)
        .then(function(data) {

        })
        .catch(function(err) {

        });
      }
    }
    if (j != 0) {
      alert('Tipo de envio guardado');
    }
  }

  //emitir el transmittal en edicion
  td.emitir = function() {
    for (var i = 0; i < td.detalles.length; i++) {
      if (td.detalles[i].seleccionado == true) {
        td.detalles[i].emitirTransmittal();
      }
    }
    listarDetalles();
  }

  //imprimir el transmittal en edicion
  td.imprimirTransmittal = function() {
    for (var i = 0; i < td.detalles.length; i++) {
      if (td.detalles[i].seleccionado == true) {
        td.detalles[i].imprimirTransmittal();
      }
    }
    listarDetalles();
  }

  //eliminar los detalles seleccionados
  td.eliminar = function() {
    for (var i = 0; i < td.detalles.length; i++) {
      if (td.detalles[i].seleccionado == true) {
        td.detalles[i].eliminarDetalle();
      }
    }
    listarDetalles();
  }

  //guardar los cambios hechos
  td.guardar = function() {
    for (var i = 0; i < td.detalles.length; i++) {
      td.detalles[i].guardarDetalle();
    }
    listarDetalles();
    alert('Datos guardados satisfactoriamente');
  }

  td.seleccionar = function (index) {
    for (var i = 0; i < td.detalles.length; i++) {
      if (td.detalles[i].seleccionado == true) {
        td.detalles[i].select();
      }

    }
    td.detalles[index].select();

    var detalle = td.detalles[index];

    httpFactory.getDatosContactoxDetalle(detalle.detalleid)
    .then(function(data) {
      td.atencion.codigo = data.codigo;
      td.atencion.nombre = data.nombre;
      td.atencion.area = data.area;
      td.atencion.correo = data.correo;
    })
    .catch(function(err) {
      td.atencion.codigo = '';
      td.atencion.nombre = '';
      td.atencion.area = '';
      td.atencion.correo = '';
    });

    httpFactory.getModoEnvioxDetalle(detalle.detalleid)
    .then(function(data) {
      td.modo_seleccionado = data.modo;
    })
    .catch(function(err) {
      td.modo_seleccionado = '';
    })
  }

  td.agregar = function(index) {
    var trans;
    var corr;
    for (var i = 0; i < td.detalles.length; i++) {
      if (td.detalles[i].seleccionado == true) {
        trans = td.detalles[i].transmittal;
        corr = td.detalles[i].correlativo;
      }
    }

    var modalInstance = $modal.open({
      animation: true,
      controller: 'ModalEntregablesCtrl',
      controllerAs: 'me',
      templateUrl: '/controldocumentario/index/modalentregables',
      size: 'md',
      resolve: {
        proyecto: function() {
          return proyectoid;
        },
        transmittal: function () {
          return trans;
        },
        correlativo: function () {
          return corr;
        }
      }
    });

    modalInstance.result.then(function () {
    }, function () {
      listarDetalles();
    });
  }

  td.cambiarContacto = function() {
    for (var i = 0; i < td.detalles.length; i++) {
      if (td.detalles[i].seleccionado == true) {
        td.detalles[i].cambiarContacto(td.atencion.codigo);
      }
    }
    alert('Contacto cambiado');
  }

}]);
