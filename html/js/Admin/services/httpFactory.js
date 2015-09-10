app.factory('httpFactory', ['$http','$q', function($http,$q) {
    var url = '/admin/index/';
    var url_print = '/admin/print/';
    var url_json = '/admin/json/';
    var publico = {
        
        getUsuarios: function(estado) {
            var defered = $q.defer();
            var promise = defered.promise;
            $http.get(url_json + 'usuariosxestado/estado/'+estado)
            .success(function(data) {
                defered.resolve(data);
            })
            .error(function(err) {
                defered.reject(err);
            });
            return promise;
        },

        setUsuario: function(uid,estado) {
            var defered = $q.defer();
            var promise = defered.promise;
            $http.post(url_json + 'cambiarestadoxusuario/uid/'+uid+'/estado/'+estado)
                .success(function(data) {
                    defered.resolve(data);
                })
                .error(function(err) {
                    defered.reject(err);
                });
            return promise;
        },

    }
    return publico;
}])