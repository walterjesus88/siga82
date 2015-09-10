app.factory('httpFactory', ['$http','$q', function($http,$q) {
    var url = '/admin/index/';
    var url_print = '/admin/print/';
    var url_json = '/admin/json/';
    var publico = {
    getUsuarios: function(){
        var defered = $q.defer();
        var promise = defered.promise;
        $http.get(url_json + 'usuarios')
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
