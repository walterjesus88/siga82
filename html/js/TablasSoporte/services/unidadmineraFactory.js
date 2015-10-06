app.factory('unidadmineraFactory', ['httpFactory', '$location', '$q',
function(httpFactory, $location, $q) {

    var publico = {

  /*UNIDAD MINERA*/
    getUnidadMineras: function() {
      var defered = $q.defer();
      var promise = defered.promise;

      httpFactory.getUnidadMineras()
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;
    },

    setGuardarUnidadMinera: function(unidad_mineraid,clienteid,nombre,estado,direccion,paisid,departamentoid,distritoid,tag,isunidadminera) {
      var defered = $q.defer();
      var promise = defered.promise;

      httpFactory.setGuardarUnidadMinera(unidad_mineraid,clienteid,nombre,estado,direccion,paisid,departamentoid,distritoid,tag,isunidadminera)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
        console.log(data);

      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;
    },

    setModificarUnidadMinera: function(unidad_mineraid,clienteid,nombre,estado,direccion,paisid,departamentoid,distritoid,tag,isunidadminera) {
      var defered = $q.defer();
      var promise = defered.promise;

      httpFactory.setModificarUnidadMinera(unidad_mineraid,clienteid,nombre,estado,direccion,paisid,departamentoid,distritoid,tag,isunidadminera)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;
    },

    setEliminarUnidadMinera: function(unidad_mineraid) {
      var defered = $q.defer();
      var promise = defered.promise;

      httpFactory.setEliminarUnidadMinera(unidad_mineraid)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;
    },

}
  return publico;
}]);