app.factory('httpFactory', ['$http', '$q' , function($http,$q){

var url="/listararea/index/";//ruta vista
var url_area='/soporte/funciones/';//ruta controlador funciones

var publico = {
    getAreas: function(isproyecto) {
      var defered = $q.defer();
      var promise = defered.promise;

      alert(isproyecto);

      $http.get(url_area + 'llamarareas/isproyecto/'+isproyecto)
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },

    setGuardarArea: function(nombre,areaid) {
      var defered = $q.defer();
      var promise = defered.promise;

      //alert(isproyecto);

      $http.get(url_area + 'guardararea/nombre/'+nombre+"/areaid/"+areaid)
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },

    setEliminarxArea: function(areaid) {
      var defered = $q.defer();
      var promise = defered.promise;

      //alert(isproyecto);

      $http.get(url_area + 'eliminarea/areaid/'+areaid)
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