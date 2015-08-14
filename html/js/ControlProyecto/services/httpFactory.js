app.factory('httpFactory', ['$http','$q', function($http,$q) {
  var url = '/proyecto/index/';
  //var url = '/controldocumentario/index/'; 

  var publico = {
    getIntegrantes: function(){
      return $http.get(url + 'verjson')
      //return $http.get(url + 'integrantes')
    },
    getUsuarios: function() {
      return $http.get(url + 'usuariosjson');
    },
    getTiempos: function(revision) {
      return $http.get(url + 'curvasjson/revision/'+revision);
    },   
    setCambiarfechaproyecto: function(value,column,id) {
      // var defered = $q.defer();
      // var promise = defered.promise;
      return $http.post(url + 'cambiarfechaproyeto/value/' +
      value+"/id/"+id+"/column/"+column)
      // .success(function(data) {
      //   defered.resolve(data);
      // })
      // .error(function(err) {
      //   defered.reject(err);
      // });
      //return promise;
    },
    getProyectos: function(estado) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.get(url + 'listaproyectos/estado/' + estado)
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },

    getProyectoById: function(proyectoid) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.get(url + 'proyecto/proyectoid/' + proyectoid)
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
