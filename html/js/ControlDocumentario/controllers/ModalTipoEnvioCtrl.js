app.controller('ModalTipoEnvioCtrl', ['$modalInstance', 'httpFactory',
function($modalInstance, httpFactory) {

  mt = this;
  mt.tipo_envio = [];
  mt.nuevos = [];

  mt.agregarFila = function() {
    var tipo = {
      empresa: '',
      abrev: '',
      emitido_para: ''
    }
    mt.nuevos.push(tipo);
  }

  mt.guardar = function() {
    mt.nuevos.forEach(function(tipo) {
      httpFactory.setTipoEnvio(tipo)
      .then(function(data) {
        mt.tipo_envio = data;
      })
      .catch(function(err) {
        mt.tipo_envio = [];
      });
      mt.nuevos = [];
    })
  }

  mt.cancel = function() {
    $modalInstance.dismiss('cancel');
  }

  httpFactory.getEmisiones()
  .then(function(data) {
    mt.tipo_envio = data;
  })
  .catch(function(err) {
    mt.tipo_envio = [];
  })
}]);
