app.factory('httpFactory', ['$http','$q', function($http,$q) {
  var url = '/proyecto/index/';
  //var url = '/controldocumentario/index/'; 

  var publico = {
    getIntegrantes: function(){
      return $http.get(url + 'verjson')
      //return $http.get(url + 'integrantes')
    },
    getUsuarios: function() {
      return $http.get(url + 'usuariosjson');
    },
    getTiempos: function(revision,codigo,proyectoid) {
      return $http.get(url + 'curvasjson/revision/'+revision+"/codigo/"+codigo+"/proyectoid/"+proyectoid);
      // +'/proyectoid/'+proyectoid);
    },   





    setCambiarfechaproyecto: function(value,column,id) {
      // var defered = $q.defer();
      // var promise = defered.promise;
      return $http.post(url + 'cambiarfechaproyeto/value/' +
      value+"/id/"+id+"/column/"+column)
      // .success(function(data) {
      //   defered.resolve(data);
      // })
      // .error(function(err) {
      //   defered.reject(err);
      // });
      //return promise;
    },

    setGuardarCurva:function(codigo_curvas,fecha_ingreso_curvas,porcentaje_ejecutado,porcentaje_propuesta,revision_cronograma,codigo_cronograma,codigo_prop_proy,proyectoid,cronogramaid,revision_propuesta)
    {
      return $http.post(url + 'guardarcurva/codigo_curvas/' +
      codigo_curvas+"/fecha_ingreso_curvas/"+fecha_ingreso_curvas+"/porcentaje_ejecutado/"+porcentaje_ejecutado  
      +"/porcentaje_propuesta/"+porcentaje_propuesta
      +"/revision_cronograma/"+revision_cronograma+"/codigo_cronograma/"+codigo_cronograma+"/codigo_prop_proy/"
      +codigo_prop_proy+"/proyectoid/"+proyectoid+"/cronogramaid/"+cronogramaid+"/revision_propuesta/"+revision_propuesta)
  
    },


    setEliminarfechaproyecto:function(codigo_curvas)
    {
      return $http.post(url + 'eliminarcurva/codigo_curvas/' +
      codigo_curvas)
  
    },

    setDatosxPerfomance: function(codigo_prop_proy,codigo_actividad,actividadid,cronogramaid,
        codigo_cronograma,codigo_performance,porcentaje_performance,fecha_calculo_performance,proyectoid,revision_cronograma,
        fecha_ingreso_performance,fecha_performance) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.get(url + 'modificarperformance/codigo_prop_proy/' + codigo_prop_proy+
        "/codigo_actividad/"+codigo_actividad+
        "/actividadid/"+actividadid+
        "/cronogramaid/"+cronogramaid+
        "/codigo_cronograma/"+codigo_cronograma+
        "/codigo_performance/"+codigo_performance+
        "/porcentaje_performance/"+porcentaje_performance+
        "/fecha_calculo_performance/"+fecha_calculo_performance+
        "/proyectoid/"+proyectoid+
        "/revision_cronograma/"+revision_cronograma+
        "/fecha_ingreso_performance/"+fecha_ingreso_performance+
        "/fecha_performance/"+fecha_performance
      )
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },


    getProyectos: function(estado) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.get(url + 'listaproyectos/estado/' + estado)
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },

    getProyectoById: function(proyectoid) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.get(url + 'proyecto/proyectoid/' + proyectoid)
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },

    getProyectoxPerfomance: function(proyectoid) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.get(url + 'proyectoxperformance/proyectoid/' + proyectoid)
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },

    getProyectoxCronograma: function(proyectoid) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.get(url + 'proyectoxcronograma/proyectoid/' + proyectoid)
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
