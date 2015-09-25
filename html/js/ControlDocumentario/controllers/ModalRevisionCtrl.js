app.controller('ModalRevisionCtrl', ['$modalInstance', function($modalInstance) {

  mr = this;

  mr.opcion = 'Numerico';
  mr.alfabetico = 'Alfabetico';
  mr.numerico = 'Numerico';

  mr.cerrar = function() {
    $modalInstance.dismiss('cancel');
  }

  mr.aceptar = function() {
    $modalInstance.close(mr.opcion);
  }

  mr.cancelar = function() {
    $modalInstance.dismiss('cancel');
  }
}]);
