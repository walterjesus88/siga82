/*servicio Factory que simula una clase Proyecto con include de httpFactory para
poder actualizar el control documentario y $location para redigir a las vistas
de informacion, generar transmittal y generar reporte*/
app.factory('proyectoFactory', ['httpFactory', '$location', '$q',
function(httpFactory, $location, $q) {

  var datos = {
    codigo_prop_proy:'',
    codigo: '',
    nombre: '',
    clienteid: '',
    cliente: '',
    unidad_minera: '',
    estado: '',
    fecha_inicio: '',
    fecha_cierre: '',
    control_documentario: '',
    descripcion: '',
    tipo_proyecto: '',
    logo_cliente: ''
  };

  var publico = {
    Proyecto: function(codigo_prop_proy,codigo, cliente, nombre, gerente, control_proyecto,
      control_documentario, estado) {
      var estados = {
        'A': 'A',
        'P': 'P',
        'C': 'C',
        'CA': 'CA'
      }

      this.codigo_prop_proy = codigo_prop_proy;
      this.codigo = codigo;
      this.cliente = cliente;
      this.nombre = nombre;
      this.gerente = gerente.changeFormat();
      this.control_proyecto = control_proyecto.changeFormat();
      this.control_documentario = control_documentario;
      this.estado = estados[estado];

      // this.cambiarControlDocumentario = function() {
      //   httpFactory.setControlDocumentario(this.codigo, this.control_documentario)
      //   .then(function(data) {
      //     alert('Control Documentario cambiado');
      //   })
      //   .catch(function(err) {
      //     alert('No se pudo cambiar el Control Documentario');
      //   })
      // }

      // this.cambiarEstadoProyecto = function(index) {
      //   httpFactory.setCambioEstadoProyecto(this.codigo, this.estado,this.codigo_prop_proy)
      //   .then(function(data) {
      //     alert('Estado del Proyecto cambiado');
      //     console.log($parent.proyectos);
      //     //this.proyectos.splice(index, 1);  
      //   })
      //   .catch(function(err) {
      //     alert('No se pudo cambiar el Estado del Proyecto');
      //   })
      // }

    this.verInformacion = function() {  
        console.log("verInformacion");
        console.log(this.codigo);
        $location.path("/detalle/proyecto/" +this.codigo);     
      }
    },

    restaFechas : function(f_comienzo,fecha_corte0)
    {
    
       var aFecha1 = f_comienzo.split('-'); 
       var aFecha2 = fecha_corte0.split('-'); 
       var fFecha1 = Date.UTC(aFecha1[0],aFecha1[1]-1,aFecha1[2]); 
       var fFecha2 = Date.UTC(aFecha2[0],aFecha2[1]-1,aFecha2[2]); 
       var dif = fFecha2 - fFecha1;
       var dias = Math.floor(dif / (1000 * 60 * 60 * 24));              
       return dias;
    },

    formatoFechas : function(fecha)
    {     
        fecha = new Date(fecha);
        day=fecha.getDate();
        month=fecha.getMonth()+1;
        year=fecha.getFullYear();

        if (month.toString().length < 2) 
        {
          month = '0' + month;
        }
        if (day.toString().length < 2) 
        {
          day = '0' + day;
        }
                           
        fecha=year+"-"+month+"-"+day;
        return fecha;
    },

    calculosumafecha : function(valordias,fecha)
    {     
   //   posiciondelmas = cadena.indexOf('+'); 
   //   valordias=cadena.substring(posiciondelmas+1);

      fecha = new Date(fecha);
      ki=0; 
      while (ki<valordias)
      {
        fecha.setTime(fecha.getTime()+24*60*60*1000); // añadimos 1 día
        if (fecha.getDay() == 0 || fecha.getDay() == 6)
        {                                             
        }                  
        else
        { ki++;
        }
      }

      day=fecha.getDate();
      month=fecha.getMonth()+1;
      year=fecha.getFullYear();

      if (month.toString().length < 2) 
      {           
        month = '0' + month;
      }
      if (day.toString().length < 2) 
      {          
        day = '0' + day;
      }
      fecha=year+"-"+month+"-"+day

      return fecha;
    }, 


    calculorestafecha : function(cadena,fecha)
    {     
      posiciondelmenos = cadena.indexOf('-'); 
      valordias=cadena.substring(posiciondelmenos+1);

      //alert(fecha);
      fecha = fecha.replace(/-/g, '/');                     
      fecha = new Date(fecha);
                      
      ki=1; 
      while (ki<=valordias-1) 
      {
        fecha.setTime(fecha.getTime()-24*60*60*1000); // añadimos 1 día
        if (fecha.getDay() == 6  || fecha.getDay() == 0    )
        {                                        
        }  
        else
        {
          ki++;
        }
      }

      day=fecha.getDate();
      month=fecha.getMonth()+1;
      year=fecha.getFullYear();

      if (month.toString().length < 2) 
      {           
        month = '0' + month;
      }
      if (day.toString().length < 2) 
      {          
        day = '0' + day;
      }
      fecha=year+"-"+month+"-"+day

      return fecha;
    }, 

    calculofechafin : function(fecha_comienzo)
    {     
      if(fecha_comienzo!=null)
      {
        //fecha_comienzo = fecha_comienzo.replace(/-/g, '/');
        fec=fecha_comienzo.toString();
        fecha = new Date(fec);
        ki=0; 
        while (ki<duracion) {                          
          fecha.setTime(fecha.getTime()+24*60*60*1000); // añadimos 1 día
          if (fecha.getDay() == 0 || fecha.getDay() == 6)
          {     
          }                  
          else
          {         
            ki++;
          }
        }
        day=fecha.getDate();
        month=fecha.getMonth()+1;
        year=fecha.getFullYear();

        if (month.toString().length < 2) 
        {
          month = '0' + month;
        }
        if (day.toString().length < 2) 
        {                      
          day = '0' + day;
        }
       
        fecha=year+"-"+month+"-"+day;
      } 
      return fecha;
    }, 

    calculofechacomienzo : function(fecha_fin)
    {
      if(fecha_fin!=null)
      {
        fec = fecha_fin.replace(/-/g, '/');
        fec=fec.toString();
        fecha = new Date(fec);
        
        ki=1; 
        while (ki<=duracion-1) 
        {
          fecha.setTime(fecha.getTime()-24*60*60*1000); // añadimos 1 día
          if (fecha.getDay() == 6  || fecha.getDay() == 0    )
          {                                                                  
          }  
          else
          {
            ki++;
          }
        }
        day=fecha.getDate();
        month=fecha.getMonth()+1;
        year=fecha.getFullYear();
        if (month.toString().length < 2) 
        {                      
          month = '0' + month;
        }
        if (day.toString().length < 2) 
        {
          day = '0' + day;
        }      
        fecha=year+"-"+month+"-"+day;
      }
      return fecha;

    },

    setDatosxCambiarxEstadoproyecto: function(codigo,estado,codigo_prop_proy) {
      var defered = $q.defer();
      var promise = defered.promise;
      httpFactory.setCambioEstadoProyecto(codigo,estado,codigo_prop_proy)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;      
    },

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

    getVerCronogramaxActivo: function(proyectoid) {
      var defered = $q.defer();
      var promise = defered.promise;
      httpFactory.getCronogramaxActivo(proyectoid)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;      
    },

    getDatosProyectoxCronograma: function(proyectoid) {
      var defered = $q.defer();
      var promise = defered.promise;
      httpFactory.getProyectoxCronograma(proyectoid)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;      
    },

    getDatosProyectoxPerfomance: function(proyectoid,revision) {
      var defered = $q.defer();
      var promise = defered.promise;
      httpFactory.getProyectoxPerfomance(proyectoid,revision)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;      
    },

    setActualizarDatosxPerfomance: function(codigo_prop_proy,codigo_actividad,actividadid,cronogramaid,codigo_cronograma,codigo_performance,porcentaje_performance,proyectoid,revision_cronograma,fecha_ingreso_performance,fecha_performance)
    {
      var defered = $q.defer();
      var promise = defered.promise;
    
      httpFactory.setDatosxPerfomance(codigo_prop_proy,codigo_actividad,actividadid,cronogramaid,
        codigo_cronograma,codigo_performance,porcentaje_performance,proyectoid,revision_cronograma,
        fecha_ingreso_performance,fecha_performance)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;      
    },

    setActualizarPerformance: function(
      codigo_prop_proy,codigo_actividad,actividadid,cronogramaid,codigo_cronograma,codigo_performance,
      proyectoid,revision_cronograma,fecha_ingreso_performance,revision_propuesta,
      costo_real,horas_real,costo_propuesta,horas_propuesta,horas_planificado,costo_planificado,porcentaje_planificado,
      porcentaje_real,fecha_comienzo_real,fecha_fin_real,
      fecha_fin,fecha_comienzo,nivel_esquema,predecesoras,sucesoras,duracion)
      {
      var defered = $q.defer();
      var promise = defered.promise; 
 
      httpFactory.setModificarxPerformance(
        codigo_prop_proy,codigo_actividad,actividadid,cronogramaid,codigo_cronograma,codigo_performance,
        proyectoid,revision_cronograma,fecha_ingreso_performance,revision_propuesta,
        costo_real,horas_real,costo_propuesta,horas_propuesta,horas_planificado,costo_planificado,porcentaje_planificado,
        porcentaje_real,fecha_comienzo_real,fecha_fin_real,
        fecha_fin,fecha_comienzo,nivel_esquema,predecesoras,sucesoras,duracion
        )
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;      
    },

    getDatosxPerformancexLlamar: function(proyectoid,fechaperformance,revision_cronograma,codigoproy) {
      var defered = $q.defer();
      var promise = defered.promise;
     
      httpFactory.getDatosxPerformancexLlamar(proyectoid,fechaperformance,revision_cronograma,codigoproy)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;      
    },

    setGuardarxPorcenxCurvas: function(proyectoid,revision,fecha,porcentaje) {
      var defered = $q.defer();
      var promise = defered.promise;
     
      httpFactory.setGuardarxPorcentajexCurvas(proyectoid,revision,fecha,porcentaje)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;      
    },

    /****EDT**/////
    getDatosxEDT: function(proyectoid) {
      var defered = $q.defer();
      var promise = defered.promise;
     

      httpFactory.getDatosxProyectoxEDT(proyectoid)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;      
    },

    setDatosxGuardarxEDT: function(codigoedt,nombre,descripcion,codigo_prop_proy,codigo) {
      var defered = $q.defer();
      var promise = defered.promise;    
      
      httpFactory.setDatosxGrabarxEDT(codigoedt,nombre,descripcion,codigo_prop_proy,codigo)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;      
    },

    setDatosxModificarxEDT: function(codigoedt,codigoproyecto,proyectoid,codigoedtmodificado,nombremodificado,descripcionmodificado) {
      var defered = $q.defer();
      var promise = defered.promise;     
      
      httpFactory.setDatosxModificarxEDT(codigoedt,codigoproyecto,proyectoid,codigoedtmodificado,nombremodificado,descripcionmodificado)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;      
    },

    setEliminarxEDT: function(codigoedt,codigoproyecto,proyectoid) {
      var defered = $q.defer();
      var promise = defered.promise;     
      
      httpFactory.setDatosxEliminarxEDT(codigoedt,codigoproyecto,proyectoid)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;      
    },
    ////////////////////  F E C H A  D E  C O R T E /////////////////////////
   getCerrarxProyectoxFechaxCorte: function(proyectoid,codigo_prop_proy,fecha_corte,fechacorte_cambiar) {
      var defered = $q.defer();
      var promise = defered.promise;    

      httpFactory.getCerrarxFechaxCorte(proyectoid,codigo_prop_proy,fecha_corte,fechacorte_cambiar)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;      
    },   
    getDatosxGenerarxRevision: function(codigoproyecto,proyectoid) {
      var defered = $q.defer();
      var promise = defered.promise;    

      httpFactory.getGenerarxRevision(codigoproyecto,proyectoid)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;      
    },

    getDatosxProyectoxFechaxCorte: function(proyectoid,revision,codigoproy) {
      var defered = $q.defer();
      var promise = defered.promise;
     

      httpFactory.getProyectoxFechaxCorte(proyectoid,revision,codigoproy)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;      
    },

   
    setDatosxEliminarxFechaCorte: function(fechacorteid) {
      var defered = $q.defer();
      var promise = defered.promise;
      httpFactory.setEliminarxFechaCorte(fechacorteid)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;      
    },
   
    setDatosxGuardarxFechaCorte: function(revision,codigoproyecto,proyectoid,fechacorte,tipocorte) {
      var defered = $q.defer();
      var promise = defered.promise;
     
      httpFactory.setGuardarxFechaCorte(revision,codigoproyecto,proyectoid,fechacorte,tipocorte)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;      
    },

    setDatosxCambiarxFechaxCorte: function(valorcolumna,codigoproyecto,proyectoid,fechacorteid,columna) {
      var defered = $q.defer();
      var promise = defered.promise;
     
      httpFactory.setCambiarxFechaxCorte(valorcolumna,codigoproyecto,proyectoid,fechacorteid,columna)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;      
    },
/////////////////////// F I N  F E C H A  C O R T E /////////////////////////
    setDatosxGuardarxCronograma: function(codigocronograma,revision,estado,codigo_prop_proy,proyectoid) {
      var defered = $q.defer();
      var promise = defered.promise;  

      httpFactory.setGuardarxCronograma(codigocronograma,revision,estado,codigo_prop_proy,proyectoid)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;      
    },

    setDatosxModificarxCronograma: function(codigo_cronograma,codigoproyecto,proyectoid,revision_cronograma,cronogramaid) {
      var defered = $q.defer();
      var promise = defered.promise; 
      httpFactory.setModificarxCronograma(codigo_cronograma,codigoproyecto,proyectoid,revision_cronograma,cronogramaid)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;      
    },

    setDatosxEliminarxCronograma: function(cronogramaid,codigoproyecto,proyectoid) {
      var defered = $q.defer();
      var promise = defered.promise;     

      httpFactory.setEliminarxCronograma(cronogramaid,codigoproyecto,proyectoid)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;      
    },

/////////////////////// L I S T A  D E  E N T R E G A B L E S /////////////////////////
    getDatosxEntregable: function(proyectoid) {
      var defered = $q.defer();
      var promise = defered.promise;     

      httpFactory.getEntregables(proyectoid)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;      
    },

    setDatosxGuardarxEntregable: function(codigoproyecto,proyectoid,revisionentregable) {
      var defered = $q.defer();
      var promise = defered.promise;     

      httpFactory.setGuardarxEntregable(codigoproyecto,proyectoid,revisionentregable)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;      
    },
    getDatosxEntregablexActivo: function(proyectoid) {
      var defered = $q.defer();
      var promise = defered.promise;     

      httpFactory.getEntregables(proyectoid)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;      
    },    
    getDatosListaxEntregables: function(proyectoid,revision) {
      var defered = $q.defer();
      var promise = defered.promise;     

      httpFactory.getListaxEntregables(proyectoid,revision)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;      
    },
    setDatosxGuardarxListaxEntregables: function(codigo_prop_proy,proyectoid,revision_entregable,edt,tipo_documento,disciplina,codigo_anddes,codigo_cliente,fecha_0,fecha_a,fecha_b,descripcion_entregable,cod_le) {
      var defered = $q.defer();
      var promise = defered.promise; 

      httpFactory.setGuardarxListaxEntregables(codigo_prop_proy,proyectoid,revision_entregable,edt,tipo_documento,disciplina,codigo_anddes,codigo_cliente,fecha_0,fecha_a,fecha_b,descripcion_entregable,cod_le)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;      
    },
    setDatosxEliminarxEntregable: function(id,codigoproyecto,proyectoid,revision) {
      var defered = $q.defer();
      var promise = defered.promise;     

      httpFactory.setEliminarxEntregable(id,codigoproyecto,proyectoid,revision)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;      
    },
    setCambiarEstadoListaEntregable: function(value,areaid,codigoproyecto,proyectoid,revision,gerente,status) {
      var defered = $q.defer();
      var promise = defered.promise;     

      //alert(revision);

      httpFactory.setEstadoListaEntregable(value,areaid,codigoproyecto,proyectoid,revision,gerente,status)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;      
    },
    getLeerSessionUsuario: function(proyectoid) {
      var defered = $q.defer();
      var promise = defered.promise;     

      httpFactory.getLeerSessionUsuario(proyectoid)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;      
    },
    getLeerEstadosListaE: function(proyectoid,areaid,gerente,jefearea,responsable) {
      var defered = $q.defer();
      var promise = defered.promise;   
      httpFactory.getLeerEstadosListaEntregable(proyectoid,areaid,gerente,jefearea,responsable)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;      
    },
    getDisciplinaxProyecto: function(proyectoid,gerente,areaid) {
      var defered = $q.defer();
      var promise = defered.promise; 

      httpFactory.getDisciplinas(proyectoid,gerente,areaid)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;      
    },

///////////////////////F I N   L I S T A  D E  E N T R E G A B L E S /////////////////////////
//prueba de area //////////////
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
 }
  return publico;
}]);
