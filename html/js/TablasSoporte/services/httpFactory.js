app.factory('httpFactory', ['$http','$q', function($http,$q) {

// var url="/listararea/index/";//ruta vista
var url_area='/soporte/funciones/';//ruta controlador funciones

var publico = {
    getAreas: function() {
      var defered = $q.defer();
      var promise = defered.promise;

      // alert(isproyecto);

      $http.get(url_area + 'llamarareas/')
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },

    setGuardarArea: function(areaid,nombre,area_padre,isproyecto,ispropuesta,iscontacto,iscomercial,orden) {
      var defered = $q.defer();
      var promise = defered.promise;

      //alert(isproyecto);
    // console.log(areaid);
    // console.log(nombre);
    // console.log(area_padre);
    // console.log(isproyecto);
    // console.log(ispropuesta);
    // console.log(iscontacto);
    // console.log(iscomercial);
    // console.log(orden);

      $http.get(url_area + 'guardararea/areaid/'+areaid+"/nombre/"+nombre+"/area_padre/"+area_padre+"/isproyecto/"+isproyecto+"/ispropuesta/"+ispropuesta+"/iscontacto/"+iscontacto+"/iscomercial/"+iscomercial+"/orden/"+orden)
      .success(function(data) {
        defered.resolve(data);
        // alert(data);
        // console.log(areaid,nombre);
        // console.log(nombre);
        // alert(nombre);
        // alert('Cambios guardados satisfactoriamente');
      })
      .error(function(err) {
        defered.reject(err);

      });
      return promise;
    },

   /* setEliminarxArea: function(areaid) {
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
    },*/

}
  return publico;
}])