app.controller('ModalContactoCtrl', ['$modalInstance', 'cliente', 'httpFactory',
function($modalInstance, cliente, httpFactory) {

  mc = this;
  mc.clienteid = cliente;

  mc.contacto = {
    atencion: '',
    area: '',
    correo: ''
  };

  mc.agregarContacto = function() {
    httpFactory.setContacto(mc.clienteid, mc.contacto)
    .then(function(data) {
      
    })
    .catch(function(err) {

    });
  }

  mc.cancel = function() {
    $modalInstance.dismiss('cancel');
  }
}]);
