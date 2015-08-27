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
      proyectoid,revision_cronograma,fecha_ingreso_performance,revision_propuesta,costo_real,horas_real,fecha_comienzo_real,fecha_fin_real,
      fecha_fin,fecha_comienzo,porcentaje_calculo,nivel_esquema,predecesoras,sucesoras,costo_presupuesto,duracion
      ) {
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
        +"/fecha_fin/"+fecha_fin+  
        "/fecha_comienzo/"+fecha_comienzo+  
        "/porcentaje_calculo/"+porcentaje_calculo+  
        "/nivel_esquema/"+nivel_esquema+  
        "/predecesoras/"+predecesoras+  
        "/sucesoras/"+sucesoras+  
        "/costo_presupuesto/"+costo_presupuesto+  
        "/duracion/"+duracion 
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
////////////////////************cronograma ***********////////////////////////////
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

    setGuardarxCronograma: function(codigocronograma,revision,estado,codigo_prop_proy,proyectoid) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.post(url + 'guardarxproyectoxcronograma/codigocronograma/' + codigocronograma+"/revision/"+revision+"/estado/"+estado+"/codigo_prop_proy/"+codigo_prop_proy+"/proyectoid/"+proyectoid)
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },

    setModificarxCronograma: function(codigocronograma,codigo_prop_proy,proyectoid,revision,cronogramaid,state) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.post(url + 'modificarxproyectoxcronograma/codigocronograma/' + codigocronograma+"/revision/"+revision+"/codigo_prop_proy/"+codigo_prop_proy+"/proyectoid/"+proyectoid+"/cronogramaid/"+cronogramaid+"/state/"+state)
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },

    setEliminarxCronograma: function(cronogramaid,codigoproyecto,proyectoid) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.post(url + 'eliminarxproyectoxcronograma/cronogramaid/' + cronogramaid+"/codigoproyecto/"+codigoproyecto+"/proyectoid/"+proyectoid)
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },



  
/////////////////////////**********fin de cronograma****************//////////////////





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
      $http.post(url + 'setguardaredt/proyectoid/' + codigo+"/nombre/"+nombre+"/descripcion/"+descripcion+"/codigo_prop_proy/"+codigo_prop_proy+"/codigoedt/"+codigoedt)
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
      $http.post(url + 'setmodificaredt/proyectoid/' + proyectoid+"/codigoedt/"+codigoedt+"/codigoproyecto/"+codigoproyecto+"/codigoedtmodificado/"+codigoedtmodificado+"/nombremodificado/"+nombremodificado+"/descripcionmodificado/"+descripcionmodificado)
      .success(function(data) {
        defered.resolve(data);
        
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },


    setDatosxEliminarxEDT: function(codigoedt,codigoproyecto,proyectoid) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.post(url + 'seteliminaredt/codigoedt/' + codigoedt+"/codigoproyecto/"+codigoproyecto+"/proyectoid/"+proyectoid)
      .success(function(data) {
        defered.resolve(data);
        
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },

////////////////////  F E C H A  D E  C O R T E /////////////////////////

    getProyectoxFechaxCorte: function(proyectoid,revision) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.get(url + 'proyectoxfechaxcorte/proyectoid/' + proyectoid+"/revision/"+revision)
      .success(function(data) {
        defered.resolve(data);
        
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },


    setEliminarxFechaCorte: function(fechacorteid) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.post(url + 'eliminardxfechaxcorte/fechacorteid/' + fechacorteid)
      .success(function(data) {
        defered.resolve(data);
        
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },

    setGuardarxFechaCorte: function(revision,codigoproyecto,proyectoid,fechacorte,tipocorte) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.post(url + 'guardarxfechaxcorte/fechacorte/' + fechacorte+"/revision/"+revision+
        "/codigoproyecto/"+codigoproyecto+"/proyectoid/"+proyectoid+"/tipocorte/"+tipocorte)
      .success(function(data) {
        defered.resolve(data);
        
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },

    setCambiarxFechaxCorte: function(valorcolumna,codigoproyecto,proyectoid,fechacorteid,columna) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.post(url + 'cambiarxfechaxcorte/valorcolumna/' + valorcolumna+"/codigoproyecto/"+codigoproyecto+
        "/proyectoid/"+proyectoid+"/fechacorteid/"+fechacorteid+"/columna/"+columna)
      .success(function(data) {
        defered.resolve(data);
        
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },

    getGenerarxRevision: function(codigoproyecto,proyectoid) {
      var defered = $q.defer();
      var promise = defered.promise;
        
        //alert(codigoproyecto);
        //alert(proyectoid);

      $http.get(url + 'generarrevision/codigo_prop_proy/'+ codigoproyecto+'/proyectoid/'+proyectoid)
      .success(function(data) {
        defered.resolve(data);
        
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },


  /////////////////////// F I N  F E C H A  C O R T E /////////////////////////


  }

  return publico;
}])
