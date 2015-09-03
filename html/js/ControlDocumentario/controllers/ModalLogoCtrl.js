app.controller('ModalLogoCtrl', ['$modalInstance', 'FileUploader', 'httpFactory',
'clienteid',
function($modalInstance, FileUploader, httpFactory, clienteid) {

  ml = this;
  ml.clientes = [];
  ml.clienteid = clienteid;

  httpFactory.getClientes()
  .then(function(data) {
    ml.clientes = data;
  })
  .catch(function(err) {
    ml.clientes = [];
  });

  ml.uploader = new FileUploader({
    url: '/controldocumentario/json/subirlogo/clienteid/' + ml.clienteid
  });

  ml.subirLogo = function() {
    ml.uploader.queue.forEach(function(item) {
      item.upload();
    });
    $modalInstance.close();
  }

  ml.cancel = function() {
    $modalInstance.dismiss('cancel');
  }
}]);
