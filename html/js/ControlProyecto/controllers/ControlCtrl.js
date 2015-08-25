app.controller('ControlCtrl', ['httpFactory', '$scope','$q',
'proyectoFactory',
function(httpFactory, $scope,$q,proyectoFactory) {

  va = this;


  //obteniendo el codigo del proyecto del scope padre
  var proyecto = $scope.$parent.vt.proyecto;

  //carga de los datos del proyecto seleccionado
  proyectoFactory.getDatosProyecto(proyecto['codigo'])
  .then(function(data) {
  
    va.proyectop = data; 

    proyectoFactory.getVerCronogramaxActivo(proyecto['codigo'])
    .then(function(data) {

      revision=data[0]['revision_cronograma'];
      //console.log(revision);

    //sirve para cargar datos una vez iniciado //
      httpFactory.getTiempos(revision,va.proyectop.codigo_prop_proy,proyecto['codigo'])
      .success(function(data) {         

        va.dat=data[0]['1'];
         //console.log(va.dat);
        var max = data[0]['1'].length;     
        var varx=[];
        var vary=[];
        var labelx=[];

        var label= $.map(data[0], function(value, index) {   
            for (var i =0; i < max; i++) {
              a=[];
                    //console.log(value[i]['porc_avance_plani']);
              a=value[i]['fecha_curvas'];        
              labelx.push(a);
           };
            return [labelx];
        });    
        
        va.labels=label[0];
        //console.log(va.labels);

        var array = $.map(data[0], function(value, index) {   
          for (var i =0; i < max; i++) {
            a=[];
            //console.log(value[i]['porc_avance_plani']);
            a=parseFloat(value[i]['porcentaje_propuesta']);
            b=parseFloat(value[i]['porcentaje_ejecutado']);
            varx.push(a);
            vary.push(b);

          };
        return [varx,vary];
        });
        va.data = array;

      })      
      .error(function(data) {
         va.data = [] ; 
         console.log("no hay datos de busqueda");
      });

    })
    .catch(function(err) {
      //va.proyectop = {};
    });


  })
  .catch(function(err) {
    va.proyectop = {};
  });


/*Trae datos de cronograma*/
  proyectoFactory.getDatosProyectoxCronograma(proyecto['codigo'])
  .then(function(datax) {
    //console.log(datax);
    va.procronograma=datax;
    //console.log(va.procronograma);

    for (var i = va.procronograma.length - 1; i >= 0; i--) {

      //console.log(va.procronograma[i]['state']);
      if(va.procronograma[i]['state']=='A')
      {
        va.revi=va.procronograma[i]
      }
    };
  })
 .catch(function(err) {
    va.procronograma = {};
  });



//////////////////////////////////////////////////////////*Anadir cronograma*///////////////////////////////////////////////////////////

  va.ShowFormCronograma=function(){ 
   va.formVisibilityCronograma=true;    
  }

  va.GuardarCronograma= function(){
    va.estado='A';

    proyectoFactory.setDatosxGuardarxCronograma(va.codigocronograma,
      va.revision,va.estado,va.proyectop.codigo_prop_proy,va.proyectop.codigo)
    .then(function(data) {
      
      console.log(data);

      va.inserted = {
        codigo_cronograma:va.codigocronograma,      
        revision_cronograma:va.revision,
        state:va.estado
      }      
      //alert('LLEGO');
      if(va.procronograma.length)
        {        
          va.procronograma.push(va.inserted);        
        }
      else
      {        
        va.procronograma=[];
        va.procronograma.push(va.inserted);   
      }

    })
    .catch(function(err) {
        alert('intentelo de nuevo');
    });
  }

  va.ModificarCronograma=function(data,cronogramaid){

    codigoproyecto=va.proyectop.codigo_prop_proy;
    proyectoid=va.proyectop.codigo;
    codigo_cronograma=data.codigo_cronograma;
    revision_cronograma=data.revision_cronograma;
    state=data.state;
 

    proyectoFactory.setDatosxModificarxCronograma(codigo_cronograma,codigoproyecto,proyectoid,revision_cronograma,cronogramaid,state)
    .then(function(data) {
          
    })
    .catch(function(err) {
        console.log("error al modificar edt");
    });

  }

  va.EliminarCronograma=function(index,cronogramaid){

    codigoproyecto=va.proyectop.codigo_prop_proy;
    proyectoid=va.proyectop.codigo;
    
    //console.log(index);
    //console.log(codigocronograma);

    proyectoFactory.setDatosxEliminarxCronograma(cronogramaid,codigoproyecto,proyectoid)
    .then(function(data) {
      console.log('lego');
      va.procronograma.splice(index, 1);     

    })
    .catch(function(err) {
        console.log("error al eliminar edt");
    });

  }

  va.CancelarCronograma=function(){
      va.formVisibilityCronograma=false;    
  }





/////////////////////////////////**********FIN CRONOGRAMA **************//////////////////////////////////////////////////////


  /*array que contendra la lista de entregables de los proyectos y el que
  contendra a los elementos seleccionados para generar transmittal*/
  va.entregables = [];
  va.seleccionados = [];

  va.tabla_activa = 'active';
  va.trans_activo = '';

  var cambiarSubPanel = function(panel) {
    if (panel == 'tablas') {
      va.tabla_activa = 'active';
      va.trans_activo = '';
    } else if (panel == 'trans') {
      va.tabla_activa = '';
      va.trans_activo = 'active';
    }
  }
  // listarEntregables(proyecto.codigo, 'Ultimo');
  //array que contendra la lista de edts por proyecto
  va.edt = [];

  va.edt_activo = '';
  va.curva_activo = 'active';
  va.gestion_activo = '';
  va.performance_activo = '';

  //cambio de panel visible segun menu seleccionado
  va.cambiarPanel = function(panel) {
    if (panel == 'edt') {
      va.edt_activo = 'active';
      va.curva_activo = '';
      va.cronograma_activo = '';
      va.performance_activo = '';
      va.fechacorte_activo = '';

    } else if (panel == 'curva') {
      va.edt_activo = '';
      va.curva_activo = 'active';
      va.cronograma_activo = '';
      va.performance_activo = ''
      va.fechacorte_activo = '';

    } else if (panel == 'cronograma') {
      va.edt_activo = '';
      va.curva_activo = '';
      va.cronograma_activo = 'active';
      va.performance_activo = '';
      va.fechacorte_activo = '';

    } else if (panel == 'performance') {
      va.edt_activo = '';
      va.curva_activo = '';
      va.cronograma_activo = '';
      va.fechacorte_activo = '';
      va.performance_activo = 'active';
    }
      else if (panel == 'fechacorte') {
      va.edt_activo = '';
      va.curva_activo = '';
      va.cronograma_activo = '';
      va.performance_activo = '';
      va.fechacorte_activo = 'active';
    }
  }

  va.saveColumn= function(column){
    //console.log(column);
    // var results = [];
    angular.forEach(va.dat, function(fecha) {  
      //a=results.push($http.post('/saveColumn', {column: column, value: fecha[column], id: fecha.id_tproyecto}));
    httpFactory.setCambiarfechaproyecto(fecha[column],column,fecha.codigo_curvas)
        .then(function(data) {
          console.log('Curvas cambiado');
        })
        .catch(function(err) {
          console.log('No se pudo cambiar Curvas');
        })
    })
    //return $q.all(results);   
  };


  va.ShowForm=function(){  
    va.formVisibility=true;    
  }

 
  va.Cancelarcurva=function(){
      va.formVisibility=false;    
  }

  va.deleteFecha=function(codigo_curvas)
  {
    //console.log(codigo_curvas);
    var filtered = $filter('filter')(va.dat, {codigo_curvas: codigo_curvas});
    //console.log(filtered);
    va.dat.splice(va.dat.indexOf(filtered[0]), 1);

    httpFactory.setEliminarfechaproyecto(codigo_curvas)
    .then(function(data) {
      console.log('Curvas eliminada');
    })
    .catch(function(err) {
      console.log('No se pudo eliminar Curvas');
    })    

  }


  va.Guardarcurva = function() { 
    //
    va.inserted = {
    
      codigo_prop_proy:va.proyectop.codigo_prop_proy,
      proyectoid:va.proyectop.codigo,
      //codigo_curvas: '1',
      //va.dat.length+1,  
      fecha_curvas:  va.fecha_curvas,
      //fecha_ingreso_curvas: null,
      porcentaje_ejecutado: va.porcentaje_ejecutado,
      porcentaje_propuesta: va.porcentaje_propuesta,
      revision_cronograma: va.revi.revision_cronograma,
      codigo_cronograma:va.revi.codigo_cronograma,    
      cronogramaid:va.revi.cronogramaid

    };

    if(va.dat.length)
      {        
        va.dat.push(va.inserted);        
      }
    else
      {        
        va.dat=[];
        va.dat.push(va.inserted);
      }
   
      //console.log(va.codigo_cronograma);

      httpFactory.setGuardarCurva(va.fecha_curvas,va.porcentaje_ejecutado,
        va.porcentaje_propuesta,va.revi.revision_cronograma,va.revi.codigo_cronograma,va.proyectop.codigo_prop_proy
        ,va.proyectop.codigo,va.revi.cronogramaid,va.revi.revision_propuesta)
       
        .then(function(data) {
          console.log('Curvas cambiado');
        })
        .catch(function(err) {
          console.log('No se pudo cambiar Curvas');
        })
  };


  //va.revision=[]; 
  va.busca = function(revision,codigo,proyectoid) {
 
      console.log("imprimiendo avriables");

      httpFactory.getTiempos(revision.revision_cronograma,codigo,proyectoid)
      .success(function(data) {   //console.log(data);
        va.dat=data[0]['1'];
        //console.log(va.dat);

        var max = data[0]['1'].length;     
        var varx=[];
        var vary=[];
        var labelx=[];

        var label= $.map(data[0], function(value, index) {   
            for (var i =0; i < max; i++) {
              a=[];
              //console.log(value[i]['porc_avance_plani']);
              a=value[i]['fecha_ingreso_curvas'];        
              labelx.push(a);
            };
              return [labelx];
        });    
        va.labels=label[0];
        //console.log(va.labels);

        var array = $.map(data[0], function(value, index) {   
            for (var i =0; i < max; i++) {
              a=[];
              //console.log(value[i]['porc_avance_plani']);
              a=parseFloat(value[i]['porcentaje_propuesta']);
              b=parseFloat(value[i]['porcentaje_ejecutado']);
              varx.push(a);
              vary.push(b);

            };
              return [varx,vary];
        });

        va.data = array;
        //console.log(va.data[0] );   
       
       })
      .error(function(data) {
         va.data = [] ; 
         console.log("no hay datos de busqueda");
      });

  };
  


/*  console.log(va.revision);
  console.log(va.labelss);*/
  //va.series = ['29 Abr', '14 May', '21 May', '28 May', '04 Jun', '11 Jun', '18 Jun','25 Jun','02 Jun',];

  va.series = ['Planeado', 'Real'];
  //va.legend = ['Planessado', 'Ressasal'];

  va.options = {  
      legend: true,
      animationSteps: 150,
      animationEasing: "easeInOutQuint"
    };
  // va.data = [
  //   [65/100, 59/100, 80/100, 81/100, 56/100, 55/100, 40/100],
  //   [28/100, 48/100, 40/100, 19/100, 86/100, 27/100, 90/100]
  // ];
  // console.log(va.datas);  
  //  va.data = [] ; 
  //  ejemplo de objeto// 
  //   var myObj = {
  //   1: [1, 2, 3],
  //   2: [4, 5, 6]
  //   };

////////////////////////////////////////////////////*aca nace perfomance*////////////////////////////////////////////////////////////////
//      this.performancedata = [
//       { actividadid: 'actividad 1', expanded: true,
//         items: [
//           { 2015-05-05: 'Walk dog', completed: false },
//           { fecha2: 'Write blog post', completed: true },
//           { fecha3: 'Buy milk', completed: false },
//         ]
//       },
//       { actividadid: 'actividad 2', expanded: false,
//         items: [
//           { 2015-05-05: 'Ask for holidays', completed: false }
//         ]
//       },
//       { actividadid: 'actividad 3', expanded: false,
//         items: [
//           { 2015-05-05: 'War and peace', completed: false },
//           { fecha2: '1Q84', completed: false },
//         ]
//       }
//     ];

// console.log(this.performancedata);

// console.log('performance');

///////////////////////////////////////////////////// Desde aqui comienza performance //////////////////////////////////////////////////////////////////////////////////////////////
va.buscaperformance = function(revision) {

  revision_cronograma=revision.revision_cronograma;
  proyectoid=revision.proyectoid;

  proyectoFactory.getDatosProyectoxPerfomance(proyectoid,revision_cronograma)
  .then(function(datax) {
      va.performance=datax;

    })
  .catch(function(err) {
      va.performance = {};
  })

};

/////////////////*******************************F E C H A S  D E  C O R T E ***************************/////////////////
va.generarrevision= function()
{
  codigoproyecto=va.proyectop.codigo_prop_proy;
  proyectoid=va.proyectop.codigo;


  proyectoFactory.getDatosxGenerarxRevision(codigoproyecto,proyectoid)
  .then(function(data) {
    
  })
  .catch(function(err) {
    va.thi = {};
  });
}

va.buscafecha = function(revision) {
 
revision_cronograma=revision.revision_cronograma;
proyectoid=revision.proyectoid;

//console.log(proyectoid);
//console.log(revision);

proyectoFactory.getDatosxProyectoxFechaxCorte(proyectoid,revision_cronograma)
  .then(function(data) {
    va.thi=data;
    //console.log(va.thi);
  })
  .catch(function(err) {
    va.thi = {};
  });


};

va.EliminarFechaCorte= function(index,fechacorteid)
{
  //console.log(index);
  //console.log(fechacorteid);
  
  proyectoFactory.setDatosxEliminarxFechaCorte(fechacorteid)
    .then(function(data) {
      va.thi.splice(index, 1);          
    })
    .catch(function(err) {
        console.log("error al eliminar edt");
    });

}

va.CancelarFechaCorte=function(){
  va.formVisibilityFechacorte=false;    
}

va.ShowFormFechacorte=function(){  
  va.formVisibilityFechacorte=true;    
}


va.GuardarFechaCorte = function() { 

  console.log(va.revi);
  console.log(va.proyectop.codigo_prop_proy);
  console.log(va.proyectop.codigo);
  console.log(va.fechacorte);
  console.log(va.tipocorte);

  revision=va.revi.revision_cronograma;
  codigoproyecto=va.proyectop.codigo_prop_proy;
  proyectoid=va.proyectop.codigo;
  fechacorte=va.fechacorte;
  tipocorte=va.tipocorte;

  proyectoFactory.setDatosxGuardarxFechaCorte(revision,codigoproyecto,proyectoid,fechacorte,tipocorte)
  .then(function(data) {
    //alert('guardo'); 
    va.inserted = {    
      codigo_prop_proy:codigoproyecto,
      proyectoid:proyectoid,    
      //fechacorteid: va.porcentaje_ejecutado,
      tipo_corte: tipocorte,
      revision_cronograma: revision,
      fecha:fechacorte,     
    };

    if(va.thi.length)
      {        
        va.thi.push(va.inserted);        
      }
    else
      {        
        va.thi=[];
        va.thi.push(va.inserted);
      }
      

  })
  .catch(function(err) {
    console.log("error al eliminar edt");
  });
};


va.saveColumnfechacorte= function(column){
    //console.log(column);
    angular.forEach(va.thi, function(fechacorte) {  
      // console.log('ff yiyi');
      // console.log(fechacorte[column]);
      // console.log(fechacorte['codigo_prop_proy']);
      // console.log(fechacorte['proyectoid']);
      // console.log(fechacorte['fechacorteid']);
      // console.log(column);
      proyectoFactory.setDatosxCambiarxFechaxCorte(fechacorte[column],fechacorte['codigo_prop_proy'],fechacorte['proyectoid'],fechacorte['fechacorteid'],column)
        .then(function(data) {
          console.log('Curvas cambiado');
        })
        .catch(function(err) {
          console.log('No se pudo cambiar Curvas');
        })
    })
  
};

///////////////////////////////////////////////F I N  F E C H A S  D E  C O R T E /////////////////////////////////////////////

proyectoFactory.getVerCronogramaxActivo(proyecto['codigo'])
.then(function(data) {
  //console.log('cronogramaid');
  //console.log(data);
  revision=data[0]['revision_cronograma'];
 
  proyectoFactory.getDatosxProyectoxFechaxCorte(proyecto['codigo'],revision)
  .then(function(data) {
    va.thi=data;

  })
  .catch(function(err) {
    va.thi = {};
  });


  proyectoFactory.getDatosProyectoxPerfomance(proyecto['codigo'],revision)
  .then(function(datax) {
      va.performance=datax;
      
      // console.log("performance");
      // console.log(va.performance);
      // console.log("performance");
    })
  .catch(function(err) {
      va.performance = {};
  });


})
.catch(function(err) {
    //va.performance = {};
});




//guardar datos de performance//
va.saveTable = function() {

  angular.forEach(va.performance, function(val) {
    
      codigo_prop_proy=val['codigo_prop_proy'] ,
      codigo_actividad=val['codigo_actividad'],
      actividadid=val['actividadid'],
      cronogramaid=val['cronogramaid'],
      codigo_cronograma=val['codigo_cronograma'] ,
      codigo_performance=val['codigo_performance'] ,
      fecha_calculo_performance=val['fecha_calculo_performance'] ,
      proyectoid=val['proyectoid'] ,
      revision_cronograma=val['revision_cronograma'] ,
      fecha_ingreso_performance=val['fecha_ingreso_performance'],
      revision_propuesta=val['revision_propuesta'] , 
      costo_real =val['costo_real'] ,
      horas_real =val['horas_real'] ,
      fecha_comienzo_real=val['fecha_comienzo_real'] ,
      fecha_fin_real=val['fecha_fin_real'] ,

      fecha_fin=val['fecha_fin'] ,
      fecha_comienzo=val['fecha_comienzo'] ,
      porcentaje_calculo=val['porcentaje_calculo'] ,
      nivel_esquema=val['nivel_esquema'] ,
      predecesoras=val['predecesoras'] ,
      sucesoras=val['sucesoras'] ,
      costo_presupuesto=val['costo_presupuesto'] ,
      duracion=val['duracion'],

      proyectoFactory.setActualizarPerformance(codigo_prop_proy,codigo_actividad,actividadid,cronogramaid,
        codigo_cronograma,codigo_performance,fecha_calculo_performance,proyectoid,
        revision_cronograma,fecha_ingreso_performance,revision_propuesta,costo_real,horas_real,fecha_comienzo_real,
        fecha_fin_real,fecha_fin,fecha_comienzo,porcentaje_calculo,nivel_esquema,predecesoras,sucesoras,costo_presupuesto,
        duracion
        )
        .then(function(data) {
          //console.log(data); 
        })
       .catch(function(err) {
          //va.procronograma = {};
        });

      
      angular.forEach(val['items'], function(value) {
        //console.log(value['porcentaje_performance']);

        codigo_prop_proy=value['codigo_prop_proy'];
        codigo_actividad=value['codigo_actividad'];
        actividadid=value['actividadid'];
        cronogramaid=value['cronogramaid'];
        codigo_cronograma=value['codigo_cronograma'];
        codigo_performance=value['codigo_performance'];
        porcentaje_performance=value['porcentaje_performance'];
        fecha_calculo_performance=value['fecha_calculo_performance'];
        proyectoid=value['proyectoid'];
        revision_cronograma=value['revision_cronograma'];
        fecha_ingreso_performance=value['fecha_ingreso_performance'];
        fecha_performance=value['fecha_performance'];

        proyectoFactory.setActualizarDatosxPerfomance(codigo_prop_proy,codigo_actividad,actividadid,cronogramaid,
        codigo_cronograma,codigo_performance,porcentaje_performance,fecha_calculo_performance,proyectoid,revision_cronograma,
        fecha_ingreso_performance,fecha_performance)
        .then(function(data) {
          //console.log(data); 
        })
       .catch(function(err) {
          //va.procronograma = {};
        });

      })
 
  });
};


//////////////////////////*******/////////////////////////////////////
  //alert(proyecto['codigo']);
  proyectoFactory.getDatosxEDT(proyecto['codigo'])
  .then(function(data) {

        va.edt=data;
  })
  .catch(function(err) {
            //va.procronograma = {};
  });


  va.ShowFormEdt=function(){ 
   va.formVisibilityEdt=true;    
  }

  va.GuardarEdt= function(){
    console.log(va.codigo);
    console.log(va.nombre);
    console.log(va.descripcion);
    console.log(va.proyectop.codigo_prop_proy);
    console.log(va.proyectop.codigo);
    proyectoFactory.setDatosxGuardarxEDT(va.codigo,va.nombre,va.descripcion,va.proyectop.codigo_prop_proy,va.proyectop.codigo)
    .then(function(data) {

      va.inserted = {
        codigo:va.codigo,
        nombre:va.nombre,
        descripcion:va.descripcion,        
      }

      va.edt.push(va.inserted); 
      // console.log('guardar edt');  
      // console.log(va.edt);  
    })
    .catch(function(err) {
              //va.procronograma = {};
    });
  }

  va.ModificarEdt=function(data,codigoedt){ 
    console.log(data);
    console.log(codigoedt);
    codigoproyecto=va.proyectop.codigo_prop_proy;
    proyectoid=va.proyectop.codigo;
    codigoedtmodificado=data.codigo;
    nombremodificado=data.nombre;
    descripcionmodificado=data.descripcion;

    //console.log(nombremodificado);

    proyectoFactory.setDatosxModificarxEDT(codigoedt,codigoproyecto,proyectoid,codigoedtmodificado,nombremodificado,descripcionmodificado)
    .then(function(data) {
          
    })
    .catch(function(err) {
        console.log("error al modificar edt");
    });
    
  }

  va.CancelarEdt=function(){
    va.formVisibilityEdt=false;
  }

  va.EliminarEdt=function(index,codigoedt){

    codigoproyecto=va.proyectop.codigo_prop_proy;
    proyectoid=va.proyectop.codigo;

    proyectoFactory.setEliminarxEDT(codigoedt,codigoproyecto,proyectoid)
    .then(function(data) {
      va.edt.splice(index, 1);          
    })
    .catch(function(err) {
        console.log("error al eliminar edt");
    });

  }

}]);

app.run(function(editableOptions) {
  editableOptions.theme = 'bs3';
});app.run(function(editableOptions) {
  editableOptions.theme = 'bs3';
});

