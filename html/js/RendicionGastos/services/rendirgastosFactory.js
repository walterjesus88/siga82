app.factory('rendirgastosFactory', ['httpFactory', '$location', '$q',
function(httpFactory, $location, $q) {

    var publico = {

  /*GASTOS PERSONA*/
    getRendirPersona: function(numero) {
      var defered = $q.defer();
      var promise = defered.promise;

      httpFactory.getRendirPersona(numero)
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