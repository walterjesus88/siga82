app.controller('ModalContactoCtrl', ['$modalInstance', 'cliente', 'httpFactory',
function($modalInstance, cliente, httpFactory) {

  mc = this;
  mc.clienteid = cliente;
  mc.contacto = {
    id: '',
    atencion: '',
    area: '',
    correo: ''
  };
  mc.edicion = true;

  httpFactory.getContactosByCliente(mc.clienteid)
  .then(function(data) {
    mc.contactos = data;
  })
  .catch(function(err) {
    mc.contactos = [];
  });

  mc.seleccionar = function() {
    mc.contactos.forEach(function(cont) {
      if (cont.contactoid == mc.contacto.id) {
        mc.contacto.atencion = cont.nombre;
        mc.contacto.area = cont.puesto_trabajo;
        mc.contacto.correo = cont.correo;
      }
    });
  }

  mc.agregar = function() {
    mc.edicion = false;
    mc.contacto.id = '';
    mc.contacto.atencion = '';
    mc.contacto.area = '';
    mc.contacto.correo = '';
  }

  mc.modificar = function() {
    mc.edicion = false;
  }

  mc.eliminar = function() {
    httpFactory.deleteContacto(mc.clienteid, mc.contacto.id)
    .then(function(data) {
      alert('Contacto Eliminado');
      mc.contactos = data;
      mc.contacto.id = '';
      mc.contacto.atencion = '';
      mc.contacto.area = '';
      mc.contacto.correo = '';
    })
    .catch(function(err) {
      alert('Error al momento de eliminar contacto');
    })
  }

  mc.guardar = function() {
    mc.edicion = true;
    httpFactory.setContacto(mc.clienteid, mc.contacto)
    .then(function(data) {
      alert('Contacto Guardado');
      mc.contactos = data;
    })
    .catch(function(err) {
      alert('Error al momento de guardar contacto');
    });
  }

  mc.cancelar = function() {
    if (mc.contacto.id == '') {
      mc.contacto.atencion = '';
      mc.contacto.area = '';
      mc.contacto.correo = '';
    } else {
      mc.seleccionar(mc.contacto.id);
    }
    mc.edicion = true;
  }

  mc.cerrar = function() {
    $modalInstance.dismiss('cancel');
  }
}]);
