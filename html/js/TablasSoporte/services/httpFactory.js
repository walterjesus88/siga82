app.factory('httpFactory', ['$http','$q', function($http,$q) {

// var url="/listararea/index/";//ruta vista
var url_area='/soporte/funcionesarea/';//ruta controlador funcionesarrea
var url_cliente='/soporte/funcionescliente/';//ruta controlador funcionescliente
var url_unimin='/soporte/funcionesunidadminera/';//ruta controlador funcionesunidadminera

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
      $http.post(url_area + 'eliminararea/areaid/' + areaid)
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

/*INICIO UNIDAD MINERA*/
    getUnidadMineras: function() {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.get(url_unimin + 'llamarunidadminera/')
      .success(function(data) {
        defered.resolve(data);

      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },

    setGuardarUnidadMinera: function(unidad_mineraid,clienteid,nombre,estado,direccion,paisid,departamentoid,distritoid,tag,isunidadminera) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.get(url_unimin + 'guardarunidadminera/unidad_mineraid/'+unidad_mineraid+"/clienteid/"+clienteid+"/nombre/"+nombre+"/estado/"+estado+"/direccion/"+direccion+"/paisid/"+paisid+"/departamentoid/"+departamentoid+"/distritoid/"+distritoid+"/tag/"+tag+"/isunidadminera/"+isunidadminera)
      .success(function(data) {
        defered.resolve(data);
        alert(unidad_mineraid,clienteid);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },

    setModificarUnidadMinera: function(unidad_mineraid,clienteid,nombre,estado,direccion,paisid,departamentoid,distritoid,tag,isunidadminera) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.get(url_unimin + 'modificarunidadminera/unidad_mineraid/'+unidad_mineraid+"/clienteid/"+clienteid+"/nombre/"+nombre+"/estado/"+estado+"/direccion/"+direccion+"/paisid/"+paisid+"/departamentoid/"+departamentoid+"/distritoid/"+distritoid+"/tag/"+tag+"/isunidadminera/"+isunidadminera)
      .success(function(data) {
        defered.resolve(data);
        // alert(url_unimin);
        // console.log(unidad_mineraid,clienteid,nombre,estado,direccion,paisid,departamentoid,distritoid,tag,isunidadminera);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },


    setEliminarUnidadMinera: function(unidad_mineraid,clienteid) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.post(url_unimin + 'eliminarunidadminera/unidad_mineraid/' + unidad_mineraid+"/clienteid/"+clienteid)
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },
/*FIN UNIDAD MINERA*/



}
  return publico;
}])