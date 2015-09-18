app.factory('httpFactory', ['$http','$q', function($http,$q) {

// var url="/listararea/index/";//ruta vista
var url_gastos='/rendiciongastos/gastos/';//ruta controlador gastos

var publico = {

    /*INICIO GASTOS*/
    getGastos: function(estado) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.get(url_gastos + 'gastosxestado/estado/'+estado)
      .success(function(data) {
        defered.resolve(data);
// console.log(data);

      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },

/*FIN GASTOS*/

}
  return publico;
}])