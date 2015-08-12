app.controller('ModalTipoEnvioCtrl', ['$modalInstance', 'httpFactory',
function($modalInstance, httpFactory) {

  mt = this;
  mt.tipo_envio = [];

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
