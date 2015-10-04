app.factory('httpFactory', ['$http','$q', function($http,$q) {
  var url = '/proyecto/index/';
  var url_print = '/control/print/';
  var url_control = '/control/funciones/';
  //var url = '/controldocumentario/index/'; 

  var publico = {
    getAreas: function(isproyecto) {
      var defered = $q.defer();
      var promise = defered.promise;

      //alert(isproyecto);
      
      $http.get(url_control + 'llamarareas/isproyecto/'+isproyecto)
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },

    setGuardarArea: function(nombre,areaid) {
      var defered = $q.defer();
      var promise = defered.promise;

      //alert(isproyecto);
      
      $http.get(url_control + 'guardararea/nombre/'+nombre+"/areaid/"+areaid)
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },

    setEliminarxArea: function(areaid) {
      var defered = $q.defer();
      var promise = defered.promise;

      //alert(isproyecto);
      
      $http.get(url_control + 'eliminarea/areaid/'+areaid)
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },



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
        codigo_cronograma,codigo_performance,porcentaje_performance,proyectoid,revision_cronograma,
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



    setModificarxPerformance: function(
      codigo_prop_proy,codigo_actividad,actividadid,cronogramaid,codigo_cronograma,codigo_performance,
      proyectoid,revision_cronograma,fecha_ingreso_performance,revision_propuesta,
      costo_real,horas_real,costo_propuesta,horas_propuesta,horas_planificado,costo_planificado,porcentaje_planificado,
      porcentaje_real,fecha_comienzo_real,fecha_fin_real,
      fecha_fin,fecha_comienzo,nivel_esquema,predecesoras,sucesoras,duracion

      ) {
     //alert(horas_real);
      var defered = $q.defer();
      var promise = defered.promise;
        //console.log("predecesoras"+predecesoras);
      $http.post(url + 'modificarperformancepadre/codigo_prop_proy/' + codigo_prop_proy+
        "/codigo_actividad/"+codigo_actividad+
        "/actividadid/"+actividadid+
        "/cronogramaid/"+cronogramaid+
        "/codigo_cronograma/"+codigo_cronograma+
        "/codigo_performance/"+codigo_performance+        
        "/proyectoid/"+proyectoid+
   
        "/revision_cronograma/"+revision_cronograma+
        "/fecha_ingreso_performance/"+fecha_ingreso_performance+
        "/revision_propuesta/"+revision_propuesta+
        "/costo_real/"+costo_real+
        "/horas_real/"+horas_real+
        "/costo_propuesta/"+costo_propuesta+  
        "/horas_propuesta/"+horas_propuesta+
        "/horas_planificado/"+horas_planificado+
        "/costo_planificado/"+costo_planificado+
        "/porcentaje_planificado/"+porcentaje_planificado+
        "/porcentaje_real/"+porcentaje_real+     
        "/fecha_comienzo_real/"+fecha_comienzo_real+
        "/fecha_fin_real/"+fecha_fin_real+
        "/fecha_fin/"+fecha_fin+  
        "/fecha_comienzo/"+fecha_comienzo+  
        
        "/nivel_esquema/"+nivel_esquema+  
        "/predecesoras/"+predecesoras+  
        "/sucesoras/"+sucesoras+  
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

    setCambioEstadoProyecto: function(codigo, estado,codigoproyecto) {
      var defered = $q.defer();
      var promise = defered.promise;
      
      alert(codigo);
      alert(estado);
      alert(codigoproyecto);
      
      $http.get(url + 'setcambioestadoproyecto/estado/' + estado+"/codigo/"+codigo+"/codigoproyecto/"+codigoproyecto)
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

    setModificarxCronograma: function(codigocronograma,codigo_prop_proy,proyectoid,revision,cronogramaid) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.post(url + 'modificarxproyectoxcronograma/codigocronograma/' + codigocronograma+"/revision/"+revision+"/codigo_prop_proy/"+codigo_prop_proy+"/proyectoid/"+proyectoid+"/cronogramaid/"+cronogramaid)
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

    getCerrarxFechaxCorte: function(proyectoid,codigo_prop_proy,fecha_corte,fechacorte_cambiar) {
      var defered = $q.defer();
      var promise = defered.promise;
      //alert(codigoproy);
      $http.post(url + 'cerrarxfechaxcorte/proyectoid/' + proyectoid+"/codigo_prop_proy/"+codigo_prop_proy+"/fecha_corte/"+fecha_corte+"/fechacorte_cambiar/"+fechacorte_cambiar)
      .success(function(data) {
        defered.resolve(data);
        
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },


    getProyectoxFechaxCorte: function(proyectoid,revision,codigoproy) {
      var defered = $q.defer();
      var promise = defered.promise;
      //alert(codigoproy);
      $http.get(url + 'proyectoxfechaxcorte/proyectoid/' + proyectoid+"/revision/"+revision+"/codigoproy/"+codigoproy)
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

      $http.get(url + 'generarrevision/codigo_prop_proy/'+ codigoproyecto+'/proyectoid/'+proyectoid)
      .success(function(data) {
        defered.resolve(data);
        
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },

    getTareoxActividadesxProyecto: function(proyectoid,fecha_inicio,fecha_corte,actividadid) {
       var defered = $q.defer();
       var promise = defered.promise;   

      //console.log("acssss"+actividadid);
      $http.get(url + 'gettareoxactividadesxproyecto/proyectoid/'+ proyectoid+'/fecha_inicio/'+fecha_inicio+'/fecha_corte/'+fecha_corte+'/actividadid/'+actividadid)
      .success(function(data) {

          
        defered.resolve(data);        
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },



  /////////////////////// F I N  F E C H A  C O R T E /////////////////////////
 ///////////////////// L I S T A  D E  E N T R E G A B L E S /////////////////////////
    createPdfEntregable: function(revision) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.get(url_print + 'imprimirentregables/revision/' + revision)
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },



   getListaxEntregables: function(proyectoid,revision,areaid) {
      var defered = $q.defer();
      var promise = defered.promise;     

      $http.get(url + 'getlistaentregables/proyectoid/'+ proyectoid+"/revision/"+revision+"/area/"+areaid)
      .success(function(data) {
        defered.resolve(data);
        
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },


   setGuardarxEntregable: function(codigoproyecto,proyectoid,revisionentregable) {
      var defered = $q.defer();
      var promise = defered.promise;     

      $http.get(url + 'setguardarentregables/proyectoid/'+ proyectoid+"/revisionentregable/"+revisionentregable+"/codigoproyecto/"+codigoproyecto)
      .success(function(data) {
        defered.resolve(data);
        
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },


   getEntregables: function(proyectoid) {
      var defered = $q.defer();
      var promise = defered.promise; 


      $http.get(url + 'getentregables/proyectoid/'+ proyectoid)
      .success(function(data) {
        defered.resolve(data);
        
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },

   setGuardarxListaxEntregables: function(codigo_prop_proy,proyectoid,revision_entregable,edt,tipo_documento,disciplina,codigo_anddes,codigo_cliente,fecha_0,fecha_a,fecha_b,descripcion_entregable,cod_le) {
      var defered = $q.defer();
      var promise = defered.promise; 
 
     

      $http.post(url + 'setguardarlistaentregables/proyectoid/'+ proyectoid
      +"/codigo_prop_proy/"+codigo_prop_proy+
      "/revision_entregable/"+revision_entregable+
      "/edt/"+edt+
      "/tipo_documento/"+tipo_documento+
      "/disciplina/"+disciplina+
      "/codigo_anddes/"+codigo_anddes+
      "/codigo_cliente/"+codigo_cliente+
      "/fecha_0/"+fecha_0+
      "/fecha_a/"+fecha_a+
      "/fecha_b/"+fecha_b+
      "/descripcion_entregable/"+descripcion_entregable+
      "/cod_le/"+cod_le
      )
      .success(function(data) {
        defered.resolve(data);
        
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },

  setEliminarxEntregable: function(id,codigoproyecto,proyectoid,revision) {
      var defered = $q.defer();
      var promise = defered.promise; 
 
      $http.post(url + 'seteliminarentregable/id/'+ id
      +"/codigoproyecto/"+codigoproyecto+
      "/proyectoid/"+proyectoid+"/revision/"+revision
      )
      .success(function(data) {
        defered.resolve(data);
        
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },

  setEstadoListaEntregable: function(value,areaid,codigoproyecto,proyectoid,revision,gerente,status) {
      var defered = $q.defer();
      var promise = defered.promise; 

      //alert(value);
      //console.log(areaid);
      // alert(codigoproyecto);
      // alert(proyectoid);
      // alert(revision);
 
      $http.post(url + 'setcambiarestadolentregable/valor/'+ value
      +"/codigoproyecto/"+codigoproyecto
      +"/proyectoid/"+proyectoid
      +"/revision/"+revision
      +"/area/"+areaid
      +"/gerente/"+gerente      
      +"/status/"+status      
      )
      .success(function(data) {
        defered.resolve(data);        
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
  },


  getLeerSessionUsuario: function(proyectoid) {
      var defered = $q.defer();
      var promise = defered.promise; 
 
      $http.post(url + 'getleersessionusuario/proyectoid/'+proyectoid
      )
      .success(function(data) {
        defered.resolve(data);        
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },

  getLeerEstadosListaEntregable: function(proyectoid,areaid,gerente,jefearea,responsable) {
      var defered = $q.defer();
      var promise = defered.promise;   
 
      $http.post(url + 'getleerestadoslistaentregable/proyectoid/'+proyectoid+'/areaid/'+areaid+'/jefearea/'+jefearea+
                      '/gerente/'+gerente+'/responsable/'+responsable)
      .success(function(data) {
        defered.resolve(data);        
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },

  getDisciplinas: function(proyectoid) {
      var defered = $q.defer();
      var promise = defered.promise;
      $http.get(url + 'disciplinas/proyectoid/' + proyectoid)
      .success(function(data) {
        defered.resolve(data);
      })
      .error(function(err) {
        defered.reject(err);
      });
      return promise;
    },


 ///////////////////// F I N  L I S T A  D E  E N T R E G A B L E S /////////////////////////


  }

  return publico;
}])
