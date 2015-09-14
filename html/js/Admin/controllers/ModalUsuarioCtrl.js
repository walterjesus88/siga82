app.controller('ModalUsuarioCtrl', ['$scope','$modalInstance',  'httpFactory',
function($scope, $modalInstance,  httpFactory) {
    mc = this;
    mc.contacto = {
        id: '',
        atencion: '',
        area: '',
        correo: '',
        dni:'',
        nombre_completo:''
    };
    mc.edicion = true;

    mc.alerts = [];

    mc.closeAlert = function(index) {
    mc.alerts.splice(index, 1);
    }

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
      mc.alerts.push({type: 'success', msg: 'Contacto eliminado satisfactoriamente'});
      mc.contactos = data;
      mc.contacto.id = '';
      mc.contacto.atencion = '';
      mc.contacto.area = '';
      mc.contacto.correo = '';
    })
    .catch(function(err) {
      mc.alerts.push({type: 'danger', msg: 'Error al momento de eliminar contacto'});
    })
    }

    mc.guardar = function() {
    mc.edicion = true;
    httpFactory.setContacto(mc.clienteid, mc.contacto)
    .then(function(data) {
      mc.alerts.push({type: 'success', msg: 'Contacto guardado satisfactoriamente'});
      mc.contactos = data;
    })
    .catch(function(err) {
      mc.alerts.push({type: 'danger', msg: 'Error al momento de guardar contacto'});
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

    $scope.cargausuarios = function(){
    $http({url: "json.php", 
           method: "GET",
           params: {value: $scope.usuario}
      }).success(function(usuarios) {$scope.usuarios = usuarios;});
    
    }
 
   //Cuando eliges un usuario lo reemplaza en el campo de texto
    $scope.cambiausuario = function(usuario){
    $scope.usuario = usuario;
    $scope.usuarios = null;
    }


}]);
