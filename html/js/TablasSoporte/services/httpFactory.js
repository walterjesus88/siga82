app.factory('httpFactory', ['$http','$q', function($http,$q) {

// var url="/listararea/index/";//ruta vista
var url_area='/soporte/funcionesarea/';//ruta controlador funcionesarrea
var url_cliente='/soporte/funcionescliente/';//ruta controlador funcionescliente

var publico = {

    /*INICIO AREA*/
    getAreas: function() {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.get(url_area + 'llamararea/')
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
      $http.get(url_area + 'guardararea/areaid/'+areaid+"/nombre/"+nombre+"/area_padre/"+area_padre+"/isproyecto/"+isproyecto+"/ispropuesta/"+ispropuesta+"/iscontacto/"+iscontacto+"/iscomercial/"+iscomercial+"/orden/"+orden)
      .success(function(data) {
        defered.resolve(data);

      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },

    setModificarArea: function(areaid,nombre,area_padre,isproyecto,ispropuesta,iscontacto,iscomercial,orden) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.get(url_area + 'modificararea/areaid/'+areaid+"/nombre/"+nombre+"/area_padre/"+area_padre+"/isproyecto/"+isproyecto+"/ispropuesta/"+ispropuesta+"/iscontacto/"+iscontacto+"/iscomercial/"+iscomercial+"/orden/"+orden)
      .success(function(data) {
        defered.resolve(data);
        
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },


    setEliminarArea: function(areaid) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.post(url_area + 'eliminarare/areaid/' + areaid)
      .success(function(data) {
        defered.resolve(data);
        
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },
/*FIN AREA*/


/*INICIO CLIENTE*/
getClientes: function() {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.get(url_cliente + 'llamarcliente/')
      .success(function(data) {
        defered.resolve(data);

      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },

    setGuardarCliente: function(clienteid,nombre_comercial,nombre,codigoid,fecha_registro,web,direccion,paisid,departamentoid,provinciaid,distritoid,estado,tag,isproveedor,iscliente,abreviatura,tipo_cliente,ruc,issocio) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.get(url_cliente + 'guardarcliente/clienteid/'+clienteid+"/nombre_comercial/"+nombre_comercial+"/nombre/"+nombre+"/codigoid/"+codigoid+"/fecha_registro/"+fecha_registro+"/web/"+web+"/direccion/"+direccion+"/paisid/"+paisid+"/departamentoid/"+departamentoid+"/provinciaid/"+provinciaid+"/distritoid/"+estado+"/tag/"+tag+"/isproveedor/"+isproveedor+"/iscliente/"+iscliente+"/abreviatura/"+abreviatura+"/tipo_cliente/"+tipo_cliente+"/ruc/"+ruc+"/issocio/"+issocio)
      .success(function(data) {
        defered.resolve(data);

      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },

    setModificarCliente: function(clienteid,nombre_comercial,nombre,codigoid,fecha_registro,web,direccion,paisid,departamentoid,provinciaid,distritoid,estado,tag,isproveedor,iscliente,abreviatura,tipo_cliente,ruc,issocio) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.get(url_cliente + 'modificarcliente/clienteid/'+clienteid+"/nombre_comercial/"+nombre_comercial+"/nombre/"+nombre+"/codigoid/"+codigoid+"/fecha_registro/"+fecha_registro+"/web/"+web+"/direccion/"+direccion+"/paisid/"+paisid+"/departamentoid/"+departamentoid+"/provinciaid/"+provinciaid+"/distritoid/"+estado+"/tag/"+tag+"/isproveedor/"+isproveedor+"/iscliente/"+iscliente+"/abreviatura/"+abreviatura+"/tipo_cliente/"+tipo_cliente+"/ruc/"+ruc+"/issocio/"+issocio)
      .success(function(data) {
        defered.resolve(data);
        
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },


    setEliminarCliente: function(clienteid) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.post(url_cliente + 'eliminarcliente/clienteid/' + clienteid)
      .success(function(data) {
        defered.resolve(data);
        
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },
/*FIN CLIENTE*/



}
  return publico;
}])