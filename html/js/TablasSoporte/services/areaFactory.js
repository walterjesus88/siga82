app.factory('areaFactory', ['httpFactory', '$location', '$q',
function(httpFactory, $location, $q) {

    var publico = {

  /*AREA*/
    getAreas: function() {
      var defered = $q.defer();
      var promise = defered.promise;

      httpFactory.getAreas()
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;
    },

    setGuardarArea: function(areaid,nombre,area_padre,isproyecto,ispropuesta,iscontacto,iscomercial,orden) {
      var defered = $q.defer();
      var promise = defered.promise;

      httpFactory.setGuardarArea(areaid,nombre,area_padre,isproyecto,ispropuesta,iscontacto,iscomercial,orden)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
        // console.log(data);

      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;
    },

    setModificarArea: function(codigoedt,codigoproyecto,proyectoid,codigoedtmodificado,nombremodificado,descripcionmodificado) {
      var defered = $q.defer();
      var promise = defered.promise;

      httpFactory.setModificarArea(codigoedt,codigoproyecto,proyectoid,codigoedtmodificado,nombremodificado,descripcionmodificado)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;
    },

    setEliminarArea: function(areaid) {
      var defered = $q.defer();
      var promise = defered.promise;

      httpFactory.setEliminarArea(areaid)
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