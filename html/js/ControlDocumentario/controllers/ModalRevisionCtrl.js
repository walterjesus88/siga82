app.controller('ModalRevisionCtrl', ['$modalInstance', function($modalInstance) {

  mr = this;

  mr.opcion = '0';
  mr.revisiones = ['A', 'B', 'C', 'D', 'E', '0', '1', '2', '3', '4', '5'];
  
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
