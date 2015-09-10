app.controller('AdminCtrl', ['$scope','httpFactory', 'adminFactory',
    function($scope,httpFactory, adminFactory) {
        var va = this;
        var estado_actual = 'A';

        va.estadousuario = [
            {value: 'A', text: 'Activo'},
            {value: 'C', text: 'Cesado'},   
        ]; 

        va.showEstadoUsuario = function(usuario) {
            var selected = [];
            if(usuario.estado) {
              selected = $filter('filter')(va.estadousuario, {value: usuario.estado});
            }
            return selected.length ? selected[0].text : 'Not set';
        };

        va.cargarUsuarios = function(estado) {
            listarUsuarios(estado);
        };

        var listarUsuarios = function(estado) {
            estado_actual = estado;
            httpFactory.getUsuarios(estado)
                .then(function(data) {
                    va.usuarios = [];
                    data.forEach(function(item) {
                        usuario = new adminFactory.Usuario(item.uid, item.estado,
                        item.areaid,item.nombreestado);
                        va.usuarios.push(usuario);
                    });
                })
                .catch(function(err) {
                    va.usuarios= [];
                });
        };

        listarUsuarios(estado_actual);

    }
]);

