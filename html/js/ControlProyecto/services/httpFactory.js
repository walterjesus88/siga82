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

    setGuardarCurva:function(fecha_curvas,porcentaje_ejecutado,porcentaje_propuesta,revision_cronograma,codigo_cronograma,codigo_prop_proy,proyectoid,cronogramaid,revision_propuesta)
    {
      return $http.post(url + 'guardarcurva/fecha_curvas/'+fecha_ingreso_curvas+"/porcentaje_ejecutado/"+porcentaje_ejecutado  
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
      $http.post(url + 'modificarperformance/codigo_prop_proy/' + codigo_prop_proy+
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

    setModificarxPerformance: function(codigo_prop_proy,codigo_actividad,actividadid,cronogramaid,codigo_cronograma,codigo_performance,fecha_calculo_performance,
      proyectoid,revision_cronograma,fecha_ingreso_performance,revision_propuesta,costo_real,horas_real,fecha_comienzo_real,fecha_fin_real) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.post(url + 'modificarperformancepadre/codigo_prop_proy/' + codigo_prop_proy+
        "/codigo_actividad/"+codigo_actividad+
        "/actividadid/"+actividadid+
        "/cronogramaid/"+cronogramaid+
        "/codigo_cronograma/"+codigo_cronograma+
        "/codigo_performance/"+codigo_performance+        
        "/fecha_calculo_performance/"+fecha_calculo_performance+
        "/proyectoid/"+proyectoid+
        "/revision_cronograma/"+revision_cronograma+
        "/fecha_ingreso_performance/"+fecha_ingreso_performance+
        "/revision_propuesta/"+revision_propuesta+
        "/costo_real/"+costo_real+
        "/horas_real/"+horas_real+       
        "/fecha_comienzo_real/"+fecha_comienzo_real+
        "/fecha_fin_real/"+fecha_fin_real       
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

    getProyectoxPerfomance: function(proyectoid,revision) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.get(url + 'proyectoxperformance/proyectoid/' + proyectoid+"/revision/"+revision)
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },

    getProyectoxCronograma: function(proyectoid,revision) {
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

    getCronogramaxActivo: function(proyectoid) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.get(url + 'cronogramaxactivo/proyectoid/' + proyectoid)
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },

/*EDT*/
    getDatosxProyectoxEDT: function(proyectoid) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.get(url + 'datosedt/proyectoid/' + proyectoid)
      .success(function(data) {
        defered.resolve(data);
        
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },

    setDatosxGrabarxEDT: function(codigoedt,nombre,descripcion,codigo_prop_proy,codigo) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.get(url + 'setguardaredt/proyectoid/' + codigo+"/nombre/"+nombre+"/descripcion/"+descripcion+"/codigo_prop_proy/"+codigo_prop_proy+"/codigoedt/"+codigoedt)
      .success(function(data) {
        defered.resolve(data);
        
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },

    setDatosxModificarxEDT: function(codigoedt,codigoproyecto,proyectoid,codigoedtmodificado,nombremodificado,descripcionmodificado) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.get(url + 'setmodificaredt/proyectoid/' + proyectoid+"/codigoedt/"+codigoedt+"/codigoproyecto/"+codigoproyecto+"/codigoedtmodificado/"+codigoedtmodificado+"/nombremodificado/"+nombremodificado+"/descripcionmodificado/"+descripcionmodificado)
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
