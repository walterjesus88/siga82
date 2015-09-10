app.factory('adminFactory', ['httpFactory', '$location', '$q',
function(httpFactory, $location, $q) {
    var datos = {
        uid: '',
        areaid: '',
        estado: '',
    };

    var publico = {
        
        getDatosProyecto: function(proyectoid) {
          var defered = $q.defer();
          var promise = defered.promise;
          httpFactory.getProyectoById(proyectoid)
          .then(function(data) {
            datos = data;
            defered.resolve(datos);
          })
          .catch(function(err) {
            defered.reject(err);
          });
          return promise;      
        },

        setDatosxGuardarxArea: function(nombre,areaid) {
          var defered = $q.defer();
          var promise = defered.promise;     

          httpFactory.setGuardarArea(nombre,areaid)
          .then(function(data) {
            datos = data;
            defered.resolve(datos);
          })
          .catch(function(err) {
            defered.reject(err);
          });
          return promise;      
        },

        setDatosxEliminarxArea: function(areaid) {
          var defered = $q.defer();
          var promise = defered.promise;     

          httpFactory.setEliminarxArea(areaid)
          .then(function(data) {
            datos = data;
            defered.resolve(datos);
          })
          .catch(function(err) {
            defered.reject(err);
          });
          return promise;      
        },

        Usuario: function(uid, estado, areaid ) {
            this.uid = uid;
            this.areaid = areaid;
            this.estado = estado;
            //console.log(this.estado);
            this.cambiarEstadoUsuario = function() {
                httpFactory.setUsuario(this.uid,this.estado,this.areaid)
                    .then(function(data) {
                        alert('Estado del usuario cambiado');
                    })
                    .catch(function(err) {
                        alert('No se pudo cambiar el estado');
                    })
            }
        },
    }
    return publico;
}]);
