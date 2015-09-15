app.factory('clienteFactory', ['httpFactory', '$location', '$q',
function(httpFactory, $location, $q) {

    var publico = {

  /*CLIENTE*/
    getClientes: function() {
      var defered = $q.defer();
      var promise = defered.promise;

      httpFactory.getClientes()
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;
    },

    setGuardarCliente: function(clienteid,nombre_comercial,nombre,codigoid,fecha_registro,web,direccion,paisid,departamentoid,provinciaid,distritoid,estado,tag,isproveedor,iscliente,abreviatura,tipo_cliente,ruc,issocio) {
      var defered = $q.defer();
      var promise = defered.promise;

      httpFactory.setGuardarCliente(clienteid,nombre_comercial,nombre,codigoid,fecha_registro,web,direccion,paisid,departamentoid,provinciaid,distritoid,estado,tag,isproveedor,iscliente,abreviatura,tipo_cliente,ruc,issocio)
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

    setModificarCliente: function(clienteid,nombre_comercial,nombre,codigoid,fecha_registro,web,direccion,paisid,departamentoid,provinciaid,distritoid,estado,tag,isproveedor,iscliente,abreviatura,tipo_cliente,ruc,issocio) {
      var defered = $q.defer();
      var promise = defered.promise;

      httpFactory.setModificarCliente(clienteid,nombre_comercial,nombre,codigoid,fecha_registro,web,direccion,paisid,departamentoid,provinciaid,distritoid,estado,tag,isproveedor,iscliente,abreviatura,tipo_cliente,ruc,issocio)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;
    },

    setEliminarCliente: function(clienteid) {
      var defered = $q.defer();
      var promise = defered.promise;

      httpFactory.setEliminarCliente(clienteid)
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