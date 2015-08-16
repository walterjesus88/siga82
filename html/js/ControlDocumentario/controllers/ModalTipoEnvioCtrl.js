app.controller('ModalTipoEnvioCtrl', ['$modalInstance', 'httpFactory',
function($modalInstance, httpFactory) {

  mt = this;
  mt.tipo_envio = [];
  mt.edicion = true;

  httpFactory.getEmisiones()
  .then(function(data) {
    mt.tipo_envio = data;
  })
  .catch(function(err) {
    mt.tipo_envio = [];
  });

  mt.tipo = {
    empresa: '',
    abrev: '',
    emitido_para: ''
  }

  mt.seleccionar = function(tipo) {
    mt.tipo.empresa = tipo.empresa;
    mt.tipo.abrev = tipo.abrev;
    mt.tipo.emitido_para = tipo.emitido_para;
  }

  mt.agregar = function() {
    mt.edicion = false;
    mt.tipo.empresa = '';
    mt.tipo.abrev = '';
    mt.tipo.emitido_para = '';
  }

  mt.modificar = function() {
    mt.edicion = false;
  }

  mt.eliminar = function() {
    httpFactory.deleteTipo(mt.tipo)
    .then(function(data) {
      mt.tipo_envio = data;
      mt.tipo.empresa = '';
      mt.tipo.abrev = '';
      mt.tipo.emitido_para = '';
      alert('Tipo de envio eliminado');
    })
    .catch(function(err) {
      alert('No se pudo eliminar el tipo de envio');
    });
  }

  mt.guardar = function() {
    httpFactory.setTipoEnvio(mt.tipo)
    .then(function(data) {
      mt.edicion = true;
      mt.tipo_envio = data;
      alert('Tipo de envio guardado');
    })
    .catch(function(err) {
      alert('No se pudo guardar el tipo de envio');
    });
  }

  mt.cancelar = function() {
    mt.edicion = true;
    mt.tipo.empresa = '';
    mt.tipo.abrev = '';
    mt.tipo.emitido_para = '';
  }

  mt.cerrar = function() {
    $modalInstance.dismiss('cancel');
  }
}]);
