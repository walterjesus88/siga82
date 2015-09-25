app.directive('ngEnter', function () {
    return function (scope, element, attrs) {
        element.bind("keydown keypress", function (event) {
            if(event.which === 13) {
                scope.$apply(function (){
                    scope.$eval(attrs.ngEnter);
                });

                event.preventDefault();
            }
        });
    };

    return {
            restrict: 'A',
            scope: {
                confirm: '@',
                confirmAction: '&'
            },
            link: function (scope, element, attrs) {
                element.bind('click', function (e) {
                    if (confirm(scope.confirm)) {
                        scope.confirmAction();
                    }
                });
            }
    };
});




app.controller('ControlCtrl', ['httpFactory', '$scope','$filter','$q',
'proyectoFactory',
function(httpFactory, $scope,$filter,$q,proyectoFactory) {

  va = this;
  //obteniendo el codigo del proyecto del scope padre
  var proyecto = $scope.$parent.vt.proyecto;

  //carga de los datos del proyecto seleccionado
  proyectoFactory.getDatosProyecto(proyecto['codigo'])
  .then(function(data) {
  
    va.proyectop = data; 
    //console.log(va.proyectop);

      proyectoFactory.getVerCronogramaxActivo(proyecto['codigo'])
      .then(function(data) {
        if(data=='')
        {
          //console.log(data)
        }
        else        {
        
          revision=data[0]['revision_cronograma'];  
          //sirve para cargar datos una vez iniciado //
          httpFactory.getTiempos(revision,va.proyectop.codigo_prop_proy,proyecto['codigo'])
          .success(function(data) {         

            va.dat=data[0]['1'];
           // console.log(va.dat);
         
            var max = data[0]['1'].length;     
            var varx=[];
            var vary=[];
            var labelx=[];

            var label= $.map(data[0], function(value, index) {   
                for (var i =0; i < max; i++) {
                  a=[];
                       
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
        }

    })
    .catch(function(err) {
      va.procronograma = {};
      //alert('no hay cronograma');
    });


  })
  .catch(function(err) {
    va.proyectop = {};
  });



/*//va.procronograma=[];
/*Trae datos de cronograma*/
  proyectoFactory.getDatosProyectoxCronograma(proyecto['codigo'])
  .then(function(data) {
    
    //console.log(data);
    //alert('datax');
    va.procronograma=data;

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
    va.revi = {};
    //alert('ddddddddddd');
  });



//////////////////////////////////////////////////////////*Anadir cronograma*///////////////////////////////////////////////////////////

  va.ShowFormCronograma=function(){ 
   va.formVisibilityCronograma=true;    
  }

 
  
  va.doIt = function() { alert('did it!'); };

  va.GuardarCronograma= function(){
    va.estado='A';

    proyectoFactory.setDatosxGuardarxCronograma(va.codigocronograma,
      va.revision,va.estado,va.proyectop.codigo_prop_proy,va.proyectop.codigo)
    .then(function(data) {
      va.inserted = {
        codigo_prop_proy:va.proyectop.codigo_prop_proy,
        proyectoid:va.proyectop.codigo,
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
    //state=data.state;
    //console.log(data);    //console.log(cronogramaid);

    proyectoFactory.setDatosxModificarxCronograma(codigo_cronograma,codigoproyecto,proyectoid,revision_cronograma,cronogramaid)
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
      //console.log('lego');
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
      va.listaentregable_activo = '';
    } else if (panel == 'curva') {
      va.edt_activo = '';
      va.curva_activo = 'active';
      va.cronograma_activo = '';
      va.performance_activo = ''
      va.fechacorte_activo = '';
      va.listaentregable_activo = '';
    } else if (panel == 'cronograma') {
      va.edt_activo = '';
      va.curva_activo = '';
      va.cronograma_activo = 'active';
      va.performance_activo = '';
      va.fechacorte_activo = '';
      va.listaentregable_activo = '';
    } else if (panel == 'performance') {
      va.edt_activo = '';
      va.curva_activo = '';
      va.cronograma_activo = '';
      va.fechacorte_activo = '';
      va.performance_activo = 'active';
      va.listaentregable_activo = '';
    }
      else if (panel == 'fechacorte') {
      va.edt_activo = '';
      va.curva_activo = '';
      va.cronograma_activo = '';
      va.performance_activo = '';
      va.fechacorte_activo = 'active';
      va.listaentregable_activo = '';
    }
      else if (panel == 'listaentregable') {
      va.edt_activo = '';
      va.curva_activo = '';
      va.cronograma_activo = '';
      va.performance_activo = '';
      va.fechacorte_activo = '';
      va.listaentregable_activo = 'active';
    }
  }

  va.saveColumn= function(column){

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

  va.busca = function(revision,codigo,proyectoid) {
 
      console.log("imprimiendo avriables");

      httpFactory.getTiempos(revision.revision_cronograma,codigo,proyectoid)
      .success(function(data) {
        va.dat=data[0]['1'];
  

        var max = data[0]['1'].length;     
        var varx=[];
        var vary=[];
        var labelx=[];

        var label= $.map(data[0], function(value, index) {   
            for (var i =0; i < max; i++) {
              a=[];
              a=value[i]['fecha_ingreso_curvas'];        
              labelx.push(a);
            };
              return [labelx];
        });    
        va.labels=label[0]; 
        var array = $.map(data[0], function(value, index) {   
            for (var i =0; i < max; i++) {
              a=[];
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

  };
  
  //va.series = ['29 Abr', '14 May', '21 May', '28 May', '04 Jun', '11 Jun', '18 Jun','25 Jun','02 Jun',];

  va.series = ['Planeado', 'Real'];

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
    //  this.cronogramaxperformance = [
    //   { revision_cronograma: 'A', expanded: true,
    //     items: [
    //       { 2015-05-05: 'Walk dog', completed: false },
    //       { fecha2: 'Write blog post', completed: true },
    //       { fecha3: 'Buy milk', completed: false },
    //     ]
    //   },
    //   { revision_cronograma: 'B', expanded: false,
    //     items: [
    //       { 2015-05-05: 'Ask for holidays', completed: false }
    //     ]
    //   },
    //   { revision_cronograma: 'C', expanded: false,
    //     items: [
    //       { 2015-05-05: 'War and peace', completed: false },
    //       { fecha2: '1Q84', completed: false },
    //     ]
    //   }
    // ];

///////////////////////////////////////////////////// Desde aqui comienza performance //////////////////////////////////////////////////////////////////////////////////////////////
va.buscaperformance = function(revision) {

  revision_cronograma=revision.revision_cronograma;
  proyectoid=revision.proyectoid;
  codigoproy=revision.codigo_prop_proy;

  // console.log(revision_cronograma);
  // console.log(proyectoid);
  // console.log(revision);

  proyectoFactory.getDatosxProyectoxFechaxCorte(proyectoid,revision_cronograma,codigoproy)
  .then(function(data) {
    //onsole.log(data);
    va.thi=data; 
  })
  .catch(function(err) {
    va.thi = {};
  });

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
  codigoproy=revision.codigo_prop_proy;

  // console.log(proyectoid);
  // console.log(revision);

  proyectoFactory.getDatosxProyectoxFechaxCorte(proyectoid,revision_cronograma,codigoproy)
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

  revision=va.revi.revision_cronograma;
  codigoproyecto=va.proyectop.codigo_prop_proy;
  proyectoid=va.proyectop.codigo;
  fechacorte=va.fechacorte;
  tipocorte=va.tipocorte;

  proyectoFactory.setDatosxGuardarxFechaCorte(revision,codigoproyecto,proyectoid,fechacorte,tipocorte)
  .then(function(data) {
    va.inserted = {    
      codigo_prop_proy:codigoproyecto,
      proyectoid:proyectoid,   
    
      tipo_corte: tipocorte,
      revision_cronograma: revision,
      fecha:fechacorte,     
    };

    if(va.thi)
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
    alert('error al guardar fecha posible ya hay una asignada igual');
    
  });
};


va.saveColumnfechacorte= function(column){
    //console.log(column);
    angular.forEach(va.thi, function(fechacorte) {  

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

  if(data=='')
  {
    //console.log(data)
    va.thi=[];
  }
  else
  {
    codigoproy=data[0]['codigo_prop_proy'];
    revision=data[0]['revision_cronograma'];
    //console.log(codigoproy);

  //F E C H A S  D E  C O R T E///
  proyectoFactory.getDatosxProyectoxFechaxCorte(proyecto['codigo'],revision,codigoproy)
    .then(function(data) {
      va.thi=data;      
      
      angular.forEach(va.thi, function(val,id) {
        
        if(val['state_performance']=='A')
        {
          va.fecha_corte_activa=val['fecha'];
          //console.log(va.fecha_corte_activa);
        }
        if(val['state_performance']=='C')
        {
          va.fecha_corte_cerrada=val['fecha'];
          //alert(va.fecha_corte_cerrada);         
        }


      });
    })
    .catch(function(err) {
      va.thi = {};
    });

   // console.log(va.fecha_corte_activa);

    proyectoFactory.getDatosProyectoxPerfomance(proyecto['codigo'],revision)
    .then(function(datax) {
        va.performance=datax;

        //console.log(va.performance);

        angular.forEach(va.performance, function(val) { 
      
          actividad1digito= val['actividadid'].length;
          if(actividad1digito==1)
          {
            va.subtotal_costopro+=parseInt(val['costo_propuesta']);
            va.subtotal_horaspro+=parseInt(val['horas_propuesta']);
            va.subtotal_porcplani+=parseInt(val['porcentaje_planificado']);
            va.subtotal_porcreal+=parseInt(val['porcentaje_real']);           
          }
        })
        //va.subtotal_costopro='15000';     


       })
    .catch(function(err) {
        va.performance = {};
    });
  }
})
.catch(function(err) {
    va.procronograma = {};
});

//calculara la fecha fin de la actividad //



va.cerrarfechacorte=function(item){ 
   item.checked=true;  


  revision_cronograma=va.revi['revision_cronograma'];
  codigo_prop_proy=va.revi['codigo_prop_proy'];
  proyectoid=va.revi['proyectoid'];

   //console.log(item);
      //console.log(va.revi['codigo_prop_proy']);
      proyectoFactory.getDatosxProyectoxFechaxCorte(va.revi['proyectoid'],va.revi['revision_cronograma'],va.revi['codigo_prop_proy'])
      .then(function(data) {
        //console.log(data);
        //angular.forEach(data, function(val,id)
        for (var i = 0; i < data.length; i++)        
        {
        
          if(data[i]['state_performance']=='A')
          {
            proyectoid=data[i]['proyectoid'];
            codigo_prop_proy=data[i]['codigo_prop_proy'];
            fecha_corte=data[i]['fechacorteid'];

            if(i==data.length-1)
            { }
            else
            {             
              id_cambiar=i+1;      
              fechacorte_cambiar=data[i+1]['fechacorteid'];   
            } 

            var fechacorte_cambiar
            proyectoFactory.getCerrarxProyectoxFechaxCorte(proyectoid,codigo_prop_proy,fecha_corte,fechacorte_cambiar)
            .then(function(data) {
            })
            .catch(function(err) {       
            });

          } 
        };
      //  va.thi=data; 
      })
      .catch(function(err) {
          alert('intentelo de nuevo');
      });

      
      // proyectoFactory.getDatosProyectoxPerfomance(proyectoid,revision_cronograma)
      // .then(function(datax) {
      //     va.performance=datax;
      //     console.log(va.performance);

      //   })
      // .catch(function(err) {
      //     va.performance = {};
      // })

}

va.subtotal_costopro=0;
va.subtotal_horaspro=0;
va.subtotal_porcplani=0;
va.subtotal_porcreal=0;

va.subtotal_fecha=0;
//va.subtotal_fecha[2]=0;
//va.subtotal_fecha[3]=0;

va.checkName=function(data, id)
{
  //C A L C U L O  D E   S U B T O T A L E S//

 actividad1digito= va.performance[id]['actividadid'].length;
 if(actividad1digito==1)
 {
  //alert(va.performance[id]['actividadid']);
  // alert(va.performance[id]['costo_propuesta']);
  // alert(va.performance[id]['horas_propuesta']);
  va.subtotal_costopro+=parseInt(va.performance[id]['costo_propuesta']);
  va.subtotal_horaspro+=parseInt(va.performance[id]['horas_propuesta']);
  va.subtotal_porcplani+=parseInt(va.performance[id]['porcentaje_planificado']);
  va.subtotal_porcreal+=parseInt(va.performance[id]['porcentaje_real']);
  
  angular.forEach(va.performance[id]['items'], function(val,id) {


    for (var i = 0; i <= va.performance[id]['items'].length; i++) {

      if(id==2)
      {
        console.log(i);
        va.subtotal_fecha+=parseInt(val['porcentaje_performance']);
        //va.subtotal_fecha=va.subtotal_fecha[i];
        console.log(id +"-"+ va.subtotal_fecha);

      } 
    
    };

   
    //console.log(id);
  })

  va.dat=[{ porcentaje_ejecutado:va.subtotal_fecha},
          ]

 }


//angular.forEach(va.performance, function(val,id) {
    
  // codigo_prop_proy=val['codigo_prop_proy'] ,
  // predecesoras=val['predecesoras'];
  // //console.log(predecesoras);
  // cadena=predecesoras;

  // duracion=val['duracion'];
  // actividadid=val['actividadid'];
  // f_comienzo=val['fecha_comienzo'];
  // f_fin=val['fecha_fin'];

  // nivel_esquema=val['nivel_esquema'] ,
  // sucesoras=val['sucesoras'] ;
  // codigo_prop_proy=val['codigo_prop_proy'] ,
  // codigo_actividad=val['codigo_actividad'],

  // cronogramaid=val['cronogramaid'],
  // codigo_cronograma=val['codigo_cronograma'] ,
  // codigo_performance=val['codigo_performance'] ,     
  // proyectoid=val['proyectoid'] ,
  // revision_cronograma=val['revision_cronograma'] ,
  // fecha_ingreso_performance=val['fecha_ingreso_performance'],
  // revision_propuesta=val['revision_propuesta'] , 
  // costo_real =val['costo_real'] ,
  // horas_real =val['horas_real'] ,

  // costo_propuesta =val['costo_propuesta'],
  // horas_propuesta =val['horas_propuesta'],
  // horas_planificado =val['horas_planificado'],
  // costo_planificado =val['costo_planificado'],
  // porcentaje_planificado =val['porcentaje_planificado'],
  // porcentaje_real =val['porcentaje_real'];
  // fecha_comienzo_real =val['fecha_comienzo_real'];
  // fecha_fin_real =val['fecha_fin_real'];
  // nombre =val['nombre'];
  // items=val['items'];

 // console.log(va.performance);
  cadena=data; ///predecesoras//
 
  predecesoras=cadena;
 
  duracion=va.performance[id]['duracion'];
  actividadid=va.performance[id]['actividadid'];
  f_comienzo=va.performance[id]['fecha_comienzo'];
  f_fin=va.performance[id]['fecha_fin'];

  nivel_esquema=va.performance[id]['nivel_esquema'] ,
  sucesoras=va.performance[id]['sucesoras'] ;
  codigo_prop_proy=va.performance[id]['codigo_prop_proy'] ,
  codigo_actividad=va.performance[id]['codigo_actividad'],

  cronogramaid=va.performance[id]['cronogramaid'],
  codigo_cronograma=va.performance[id]['codigo_cronograma'] ,
  codigo_performance=va.performance[id]['codigo_performance'] ,     
  proyectoid=va.performance[id]['proyectoid'] ,
  revision_cronograma=va.performance[id]['revision_cronograma'] ,
  fecha_ingreso_performance=va.performance[id]['fecha_ingreso_performance'],
  revision_propuesta=va.performance[id]['revision_propuesta'] , 
  costo_real =va.performance[id]['costo_real'] ,
  horas_real =va.performance[id]['horas_real'] ,

  costo_propuesta =va.performance[id]['costo_propuesta'],
  horas_propuesta =va.performance[id]['horas_propuesta'],
  horas_planificado =va.performance[id]['horas_planificado'],
  costo_planificado =va.performance[id]['costo_planificado'],
  porcentaje_planificado =va.performance[id]['porcentaje_planificado'],
  porcentaje_real =va.performance[id]['porcentaje_real'];
  nombre =va.performance[id]['nombre'];
  items=va.performance[id]['items'];
  fecha_comienzo_real =va.performance[id]['fecha_comienzo_real'];
  fecha_fin_real =va.performance[id]['fecha_fin_real'];
  fecha_corte0 =va.fecha_corte_activa;

 /////C A L C U L A  H O R A S  P L A N I F I C A D A S//////
  h_dias_propuesta=horas_propuesta/duracion;

  c_propuesta=costo_propuesta/duracion;

  fecha_inicio_proyecto=va.proyectop.fecha_inicio;
  //console.log(f_comienzo);
  //console.log(fecha_corte0);
  calcula_fecha_planificadas=proyectoFactory.restaFechas(f_comienzo,fecha_corte0)
  
  
  //console.log(calcula_fecha_planificadas);

  calcula_h_planificadas=calcula_fecha_planificadas*h_dias_propuesta;
  calculo_c_planificadas=calcula_fecha_planificadas*c_propuesta;
  horas_planificado=Math.round(calcula_h_planificadas);
  costo_planificado=Math.round(calculo_c_planificadas);

 // console.log(horas_planificado);
  porcentaje_planificado=Math.round((calculo_c_planificadas/costo_propuesta)*100);
 
 // console.log(cadena);
 // alert(horas_planificado);
 // alert(costo_planificado);

 //cadena=cadena.trim();

if(cadena==null || cadena==''   )
{
    //alert(' es null ');
    if(f_comienzo=='null' && f_fin=='null')
    {
      f_comienzo='';
      f_fin='';
    }
    else
    {
      if(f_comienzo=='null')
      {
        
        fec = f_fin.replace(/-/g, '/');
        fec=fec.toString();
        fecha = new Date(fec);
            
        ki=1; 
        while (ki<=duracion-1) 
        {
          fecha.setTime(fecha.getTime()-24*60*60*1000); // añadimos 1 día
          if (fecha.getDay() == 6  || fecha.getDay() == 0    )
            {  }
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
                      
        fecha_comienzo=year+"-"+month+"-"+day;
        fecha_fin=f_fin;

      }
      else
      {
        //alert(id+":::"+f_comienzo);
        fec=f_comienzo.toString();
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
                       
        fecha_fin=year+"-"+month+"-"+day;
        //alert(id+"fin"+fecha_fin);
        fecha_comienzo=f_comienzo;
      }

    }
}
else
{  

    //alert('es diferente a  null');
    //console.log("duracion"+duracion);
    //console.log(cadena);
    texto =  ['FC','CF','CC','FF'];

    for (var i = texto.length - 1; i >= 0; i--)
    {
      if(cadena.indexOf(texto[i])!=-1)
      {      
        posicion = cadena.indexOf(texto[i]);               
        valoritem=cadena.substring(0, posicion); 

        fecha_sincro_comienzo=va.performance[valoritem]['fecha_comienzo'];
        fecha_sincro_fin=va.performance[valoritem]['fecha_fin']; 

        //console.log("fecha_sincro_comienzo"+fecha_sincro_comienzo);
        //console.log("fecha_sincro_fin"+fecha_sincro_fin);

        switch(texto[i]) {
        case 'CC':
         if(cadena.indexOf('+')!=-1 || cadena.indexOf('-')!=-1)
          {
                  if(cadena.indexOf('+')!=-1)
                  {
                    posiciondelmas = cadena.indexOf('+'); 
                    valordias=cadena.substring(posiciondelmas+1);

                    fecha = new Date(fecha_sincro_comienzo);
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
                  }
                  else
                  {
                    //// L A R E S T A   D E  C C /////////
                    posiciondelmenos = cadena.indexOf('-'); 
                    valordias=cadena.substring(posiciondelmenos+1);

                    fecha_sincro_comienzo = fecha_sincro_comienzo.replace(/-/g, '/');
                   // alert("fecha trans"+fecha_comienzo_pred);
                    fecha = new Date(fecha_sincro_comienzo);
                    
                    ki=1; 
                    while (ki<=valordias-1) 
                    {
                        fecha.setTime(fecha.getTime()-24*60*60*1000); // añadimos 1 día
                        if (fecha.getDay() == 6  || fecha.getDay() == 0    )
                        {
                         // console.log(fecha.getDay());                                        
                        }  
                        else
                        {
                          //console.log(fecha.getDay());
                          ki++;
                        }
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
                  fecha_CC=year+"-"+month+"-"+day,
                  //alert("fecha_CC"+fecha_CC);
                  fecha_comienzo=fecha_CC;

                       /* C A L C U L O  F E C H A  F I N  C O N  D U R A C I O N*/
                  if(fecha_comienzo!=null)
                    {                
                      fec=fecha_comienzo.toString();
                      fecha = new Date(fec);
                      // tiempo=fecha.getTime();
                      // milisegundos=parseInt(duracion*24*60*60*1000);
                      // total=fecha.setTime(tiempo+milisegundos);
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
                        //console.log('si');
                        month = '0' + month;
                      }
                      if (day.toString().length < 2) 
                      {
                        //console.log('si222');
                        day = '0' + day;
                      }
                       
                      fecha_fin=year+"-"+month+"-"+day;
                    }            

                  //console.log("fecha_comienzo:CC + o -"+fecha_comienzo);
          }          
            else
          {
                valordias=1;    
                fecha = new Date(fecha_sincro_comienzo);
                // tiempo=fecha.getTime();
                // milisegundos=parseInt(valordias*24*60*60*1000);
                // total=fecha.setTime(tiempo+milisegundos);
                cc_ki=0; 
                  while (cc_ki<valordias) {
                        
                         fecha.setTime(fecha.getTime()+24*60*60*1000); // añadimos 1 día
                          if (fecha.getDay() == 0 || fecha.getDay() == 6)
                          {     
                           }                  
                          else
                          {         
                            cc_ki++;
                          }
                }
 
                day=fecha.getDate();
                month=fecha.getMonth()+1;
                year=fecha.getFullYear();

                if (month.toString().length < 2) 
                {  month = '0' + month;
                }
                if (day.toString().length < 2) 
                {  day = '0' + day;
                }
                fecha_CC=year+"-"+month+"-"+day,
                //alert("fecha_CC"+fecha_CC);
                fecha_comienzo=fecha_CC; 

                /* C A L C U L O  F E C H A  F I N  C O N  D U R A C I O N*/
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

                    // tiempo=fecha.getTime();
                    // milisegundos=parseInt(duracion*24*60*60*1000);
                    // total=fecha.setTime(tiempo+milisegundos);
                    day=fecha.getDate();
                    month=fecha.getMonth()+1;
                    year=fecha.getFullYear();

                    if (month.toString().length < 2) 
                    {
                      //console.log('si');
                      month = '0' + month;
                    }
                    if (day.toString().length < 2) 
                    {
                      //console.log('si222');
                      day = '0' + day;
                    }
                     
                    fecha_fin=year+"-"+month+"-"+day;
                  }      
     
    
                //console.log("fecha_comienzo:CC // "+fecha_comienzo);
                        
          };
        break;

        case 'FC':
          //alert("FC-----------");
          //signo=['+','-'];
          //for (var i = signo.length - 1; i >= 0; i--) {             
            if(cadena.indexOf('+')!=-1 || cadena.indexOf('-')!=-1)
            {
              //alert("+ o -");               
                //EXTRAE EL ITEM//  
                if(cadena.indexOf('+')!=-1)
                {           

                  posiciondelmas = cadena.indexOf('+');               
                   
                  valordias=cadena.substring(posiciondelmas+1);                        
                  //S U M A 
                  // F U N C I O N  P A R A  E L I M I N A R  S A B A D O S  Y  D O M I N G O S // 
                  fecha = new Date(fecha_sincro_fin);

                  ki=0; 
                  while (ki<valordias) {
                    
                     fecha.setTime(fecha.getTime()+24*60*60*1000); // añadimos 1 día
                      if (fecha.getDay() == 0 || fecha.getDay() == 6)
                      {     
                       }                  
                      else
                      {         
                        ki++;
                      }
                   }
                          
                }
                else
                {
                  //RESTA
                  // F U N C I O N  P A R A  E L I M I N A R  S A B A D O S  Y  D O M I N G O S // 
                  posiciondelmenos = cadena.indexOf('-');            
                   
                  valordias=cadena.substring(posiciondelmenos+1);

                  fecha_sincro_fin = fecha_sincro_fin.replace(/-/g, '/');
                  console.log("fecha trans"+fecha_sincro_fin);
                  fecha = new Date(fecha_sincro_fin);

                  ki=1; 
                  while (ki<=valordias-1) {
                      fecha.setTime(fecha.getTime()-24*60*60*1000); // añadimos 1 día
                      if (fecha.getDay() == 6  || fecha.getDay() == 0    )
                      {                                                            
                      }  
                      else
                      {                      
                        ki++;
                      }
                  }
                 
                }
             //}
                day=fecha.getDate();
                month=fecha.getMonth()+1;
                year=fecha.getFullYear();

                if (month.toString().length < 2) 
                {  month = '0' + month;
                }
                if (day.toString().length < 2) 
                {  day = '0' + day;
                }
                fecha_FC=year+"-"+month+"-"+day,
                fecha_comienzo=fecha_FC;
                /*C A L C U L A R   F E C H A   F I N */
                    
                  if(fecha_comienzo!=null)
                  {
                  //fecha_comienzo = fecha_comienzo.replace(/-/g, '/');
                    fec=fecha_comienzo.toString();
                    fecha = new Date(fec);
                    // tiempo=fecha.getTime();
                    // milisegundos=parseInt(duracion*24*60*60*1000);
                    // total=fecha.setTime(tiempo+milisegundos);

                    ki=0; 
                    while (ki<duracion)
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
                      console.log('si');
                      month = '0' + month;
                    }
                    if (day.toString().length < 2) 
                    {
                      console.log('si222');
                      day = '0' + day;
                    }
                     
                    fecha_fin=year+"-"+month+"-"+day;
                  }                 
        

            }
              else
            {
       
                valordias=1;
       
                fecha = new Date(fecha_sincro_fin);         
                // tiempo=fecha.getTime();
                // milisegundos=parseInt(valordias*24*60*60*1000);
                // total=fecha.setTime(tiempo+milisegundos); 

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
                {  month = '0' + month;
                }
                if (day.toString().length < 2) 
                {  day = '0' + day;
                }
                fecha_FC=year+"-"+month+"-"+day,
         
                fecha_comienzo=fecha_FC; 
                /*C A L C U L A R   F E C H A   C O M I E N Z O*/
                  if(fecha_comienzo!=null)
                  {
                  //fecha_comienzo = fecha_comienzo.replace(/-/g, '/');
                    fec=fecha_comienzo.toString();
                    fecha = new Date(fec);
                    // tiempo=fecha.getTime();
                    // milisegundos=parseInt(duracion*24*60*60*1000);
                    // total=fecha.setTime(tiempo+milisegundos);

                    ki=0; 
                    while (ki<duracion)
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
                      console.log('si');
                      month = '0' + month;
                    }
                    if (day.toString().length < 2) 
                    {
                      console.log('si222');
                      day = '0' + day;
                    }
                     
                    fecha_fin=year+"-"+month+"-"+day;
                  }      
                /*F I N  C A L C U L A R   F E C H A   C O M I E N Z O*/
     
            };
            break;
        
        case 'CF':
          //console.log("CF------------"); 
          if(cadena.indexOf('+')!=-1 || cadena.indexOf('-')!=-1)
          {             
                if(cadena.indexOf('+')!=-1)
                {
                  posiciondelmas = cadena.indexOf('+'); 
                  valordias=cadena.substring(posiciondelmas+1);

                  fecha = new Date(fecha_sincro_comienzo);
                  ki=0; 
                  while (ki<valordias)
                  {
                    fecha.setTime(fecha.getTime()+24*60*60*1000); // añadimos 1 día
                      if (fecha.getDay() == 0 || fecha.getDay() == 6)
                      {                                         
                      }                  
                        else
                      {                       
                        ki++;
                      }
                  }

                }
                else
                {
                  //// L A R E S T A   D E  C C /////////
                  posiciondelmenos = cadena.indexOf('-'); 
                  valordias=cadena.substring(posiciondelmenos+1);

                  fecha_comienzo_pred = fecha_sincro_comienzo.replace(/-/g, '/');
                 // alert("fecha trans"+fecha_comienzo_pred);
                  fecha = new Date(fecha_sincro_comienzo);
                  
                  ki=1; 
                  while (ki<=valordias-1) 
                  {
                      fecha.setTime(fecha.getTime()-24*60*60*1000); // añadimos 1 día
                      if (fecha.getDay() == 6  || fecha.getDay() == 0    )
                      {
                        //console.log(fecha.getDay());                                        
                      }  
                      else
                      {
                        //console.log(fecha.getDay());
                        ki++;
                      }
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
                fecha_CF=year+"-"+month+"-"+day,
                //alert("fecha_CF"+fecha_CF);
                fecha_fin=fecha_CF;

                // C A L C U L O  F E C H A  C O M I E N Z O  R E S T A //
                if(fecha_fin!=null)
                  {
                    fec = fecha_fin.replace(/-/g, '/');
                    fec=fec.toString();
                    fecha = new Date(fec);
                    // tiempo=fecha.getTime();
                    // milisegundos=parseInt(duracion*24*60*60*1000);
                    // total=fecha.setTime(tiempo-milisegundos);
                    ki=1; 
                    while (ki<=duracion-1) 
                    {
                        fecha.setTime(fecha.getTime()-24*60*60*1000); // añadimos 1 día
                        if (fecha.getDay() == 6  || fecha.getDay() == 0    )
                        {
                          //console.log(fecha.getDay());                                        
                        }  
                        else
                        {
                          //console.log(fecha.getDay());
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
                     
                    fecha_comienzo=year+"-"+month+"-"+day;
                  }
                  //F I N  C A L C U L O  F E C H A  C O M I E N Z O  R E S T A //
          } 
          else
          {
                valordias=1;        
                fecha = new Date(fecha_sincro_comienzo);
                // tiempo=fecha.getTime();
                // milisegundos=parseInt(valordias*24*60*60*1000);
                // total=fecha.setTime(tiempo+milisegundos);  

                cf_ki=0; 
                  while (cf_ki<duracion) {
                        
                         fecha.setTime(fecha.getTime()+24*60*60*1000); // añadimos 1 día
                          if (fecha.getDay() == 0 || fecha.getDay() == 6)
                          {     
                           }                  
                          else
                          {         
                            cf_ki++;
                          }
                }           

                day=fecha.getDate();
                month=fecha.getMonth()+1;
                year=fecha.getFullYear();

                if (month.toString().length < 2) 
                {  month = '0' + month;
                }
                if (day.toString().length < 2) 
                {  day = '0' + day;
                }
                fecha_CF=year+"-"+month+"-"+day,
                //alert("fecha_CC"+fecha_CC);
                fecha_fin=fecha_CF;

                // C A L C U L O  F E C H A  C O M I E N Z O  R E S T A //
                if(fecha_fin!=null)
                  {
                    fec = fecha_fin.replace(/-/g, '/');
                    fec=fec.toString();
                    fecha = new Date(fec);

                    // tiempo=fecha.getTime();
                    // milisegundos=parseInt(duracion*24*60*60*1000);
                    // total=fecha.setTime(tiempo-milisegundos);
                    ki=1; 
                    while (ki<=duracion-1) 
                    {
                        fecha.setTime(fecha.getTime()-24*60*60*1000); // añadimos 1 día
                        if (fecha.getDay() == 6  || fecha.getDay() == 0    )
                        {
                          //console.log(fecha.getDay());                                        
                        }  
                        else
                        {
                          //console.log(fecha.getDay());
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
                     
                    fecha_comienzo=year+"-"+month+"-"+day;
                  }     
                  //F I N   C A L C U L O  F E C H A  C O M I E N Z O  R E S T A //
          };
        break;

        case 'FF':

          if(cadena.indexOf('+')!=-1 || cadena.indexOf('-')!=-1)
          {
                if(cadena.indexOf('+')!=-1)
                {
                  posiciondelmas = cadena.indexOf('+'); 
                  valordias=cadena.substring(posiciondelmas+1);

                  fecha = new Date(fecha_sincro_fin);
                  ki=0; 
                  while (ki<valordias)
                  {
                    fecha.setTime(fecha.getTime()+24*60*60*1000); // añadimos 1 día
                      if (fecha.getDay() == 0 || fecha.getDay() == 6)
                      {                                       
                      }                  
                        else
                      {                        
                        ki++;
                      }
                  }

                }
                else
                {
                  //// L A R E S T A   D E  C C /////////
                  posiciondelmenos = cadena.indexOf('-'); 
                  valordias=cadena.substring(posiciondelmenos+1);

                  fec = fecha_sincro_fin.replace(/-/g, '/');
                 // alert("fecha trans"+fecha_comienzo_pred);
                  fecha = new Date(fec);
                  
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
                fecha_FF=year+"-"+month+"-"+day,
                //alert("fecha_CC"+fecha_CC);

                fecha_fin=fecha_FF;

                // C A L C U L O  F E C H A  C O M I E N Z O  R E S T A //
                if(fecha_fin!=null)
                  {
                    fec = fecha_fin.replace(/-/g, '/');
                    fec=fec.toString();
                    fecha = new Date(fec);
                    // tiempo=fecha.getTime();
                    // milisegundos=parseInt(duracion*24*60*60*1000);
                    // total=fecha.setTime(tiempo-milisegundos);
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
                     
                    fecha_comienzo=year+"-"+month+"-"+day;
                  }
                  //F I N  C A L C U L O  F E C H A  C O M I E N Z O  R E S T A //
          } 
          else
          {
                valordias=1;          
                fecha = new Date(fecha_sincro_fin);      

                ff_ki=0; 
                while (ff_ki<valordias) {
                        
                  fecha.setTime(fecha.getTime()+24*60*60*1000); // añadimos 1 día
                    if (fecha.getDay() == 0 || fecha.getDay() == 6)
                    {     
                    }                  
                    else
                    {         
                      ff_ki++;
                    }
                }         
            

                day=fecha.getDate();
                month=fecha.getMonth()+1;
                year=fecha.getFullYear();

                if (month.toString().length < 2) 
                {  month = '0' + month;
                }
                if (day.toString().length < 2) 
                {  day = '0' + day;
                }
                fecha_FF=year+"-"+month+"-"+day,
                //alert("fecha_CC"+fecha_CC);
                fecha_fin=fecha_FF;  

                // C A L C U L O  F E C H A  C O M I E N Z O  R E S T A //
                if(fecha_fin!=null)
                  {
                    fec = fecha_fin.replace(/-/g, '/');
                    fec=fec.toString();
                    fecha = new Date(fec);
                    // tiempo=fecha.getTime();
                    // milisegundos=parseInt(duracion*24*60*60*1000);
                    // total=fecha.setTime(tiempo-milisegundos);
                    ki=1; 
                    while (ki<=duracion-1) 
                    {
                        fecha.setTime(fecha.getTime()-24*60*60*1000); // añadimos 1 día
                        if (fecha.getDay() == 6  || fecha.getDay() == 0    )
                        {
                          //console.log(fecha.getDay());                                        
                        }  
                        else
                        {
                          //console.log(fecha.getDay());
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
                     
                    fecha_comienzo=year+"-"+month+"-"+day;
                  }
                  //F I N  C A L C U L O  F E C H A  C O M I E N Z O  R E S T A //

          };
        break;

         }
      }
    }

}




  //console.log(dsdad);
    va.performance[id] = 
    { 
                  actividadid:actividadid,
                  fecha_comienzo: fecha_comienzo,        
                  fecha_fin: fecha_fin,
                  duracion:duracion,
                  predecesoras:predecesoras,
                  nombre:nombre,
                  nivel_esquema:nivel_esquema ,
                  sucesoras:nivel_esquema ,
                  codigo_prop_proy:codigo_prop_proy ,
                  codigo_actividad:codigo_actividad,

                  cronogramaid:cronogramaid,
                  codigo_cronograma:codigo_cronograma ,
                  codigo_performance:codigo_performance,     
                  proyectoid:proyectoid ,
                  revision_cronograma:revision_cronograma ,
                  fecha_ingreso_performance:fecha_ingreso_performance,
                  revision_propuesta:revision_propuesta, 

                  costo_real :costo_real,
                  horas_real :horas_real,

                  costo_propuesta :costo_propuesta,
                  horas_propuesta :horas_propuesta,
                  horas_planificado :horas_planificado,
                  costo_planificado :costo_planificado,
                  porcentaje_planificado :porcentaje_planificado,
                  porcentaje_real :porcentaje_real,  
                  fecha_comienzo_real:fecha_comienzo_real ,
      fecha_fin_real:fecha_fin_real,
      items:items,      
    };  


   

   //   G U A R D A R  P E R F O R M A N  C E//
      proyectoFactory.setActualizarPerformance(
        codigo_prop_proy,codigo_actividad,actividadid,cronogramaid,codigo_cronograma,codigo_performance,
        proyectoid,revision_cronograma,fecha_ingreso_performance,revision_propuesta,costo_real,
        horas_real,costo_propuesta,horas_propuesta,horas_planificado,costo_planificado,
        porcentaje_planificado,porcentaje_real,fecha_comienzo_real,fecha_fin_real,
        fecha_fin,fecha_comienzo,nivel_esquema,cadena,sucesoras,duracion
      )
      .then(function(datax) {
             //  console.log(datax);
        })
        .catch(function(err) {
               
      });

     angular.forEach(items, function(value) {      

        codigo_prop_proy=value['codigo_prop_proy'];
        codigo_actividad=value['codigo_actividad'];
        actividadid=value['actividadid'];
        cronogramaid=value['cronogramaid'];
        codigo_cronograma=value['codigo_cronograma'];
        codigo_performance=value['codigo_performance'];
        porcentaje_performance=value['porcentaje_performance'];
        proyectoid=value['proyectoid'];
        revision_cronograma=value['revision_cronograma'];
        fecha_ingreso_performance=value['fecha_ingreso_performance'];
        fecha_performance=value['fecha_performance'];

       //alert(fecha_performance);
       //alert(porcentaje_performance);
       // console.log(proyectoid);


        proyectoFactory.setActualizarDatosxPerfomance(codigo_prop_proy,codigo_actividad,actividadid,cronogramaid,
        codigo_cronograma,codigo_performance,porcentaje_performance,proyectoid,revision_cronograma,
        fecha_ingreso_performance,fecha_performance)
        .then(function(data) {
        })
       .catch(function(err) {
          //va.procronograma = {};
        });

      })
 // });
}


//////////////////////////*******/////////////////////////////////////
  //alert(proyecto['codigo']);
  proyectoFactory.getDatosxEDT(proyecto['codigo'])
  .then(function(data) {

        va.edt=data;
        console.log(va.edt);
        
  })
  .catch(function(err) {
      console.log("error edt");
            //va.procronograma = {};
  });


  va.showStatus = function(lista) {
    
    //console.log(lista.edt);    
    console.log(va.edt);

    var selected = [];
    if(lista.edt) {
      selected = $filter('filter')(va.edt, {codigo: lista.edt});
    }
    return selected.length ? selected[0].nombre : 'Not set';

  };

  
  va.operators = [
    {value: 'eq', displayName: 'equals', state:'A'},
    {value: 'eq1', displayName: 'equals1', state:'A'},
    {value: 'neq', displayName: 'not equal', state:'I'}
  ]
   

  va.tipodocumentoE = [
    {value: 'Plano', text: 'Plano'},
    {value: 'Informe', text: 'Informe'},   
  ]; 


  va.showTipodoc = function(lista) {


    var selected = [];
    if(lista.tipo_documento) {
      selected = $filter('filter')(va.tipodocumentoE, {value: lista.tipo_documento});
    }
    return selected.length ? selected[0].text : 'Not set';
  };


  // va.statuses = [
  //   {value: 1, text: 'status1'},
  //   {value: 2, text: 'status2'},
  //   {value: 3, text: 'status3'},
  //   {value: 4, text: 'status4'}
  // ]; 

  // console.log(va.statuses);


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

    console.log(index);
    console.log(codigoedt);
    console.log(va.edt);

    proyectoFactory.setEliminarxEDT(codigoedt,codigoproyecto,proyectoid)
    .then(function(data) {
      va.edt.splice(index, 1);          
    })
    .catch(function(err) {
        console.log("error al eliminar edt");
    });

  }

//  va.oneAtATime = true;

//S I R V E  P A R A  C R O N G R A M A  R E V I S I O N  A C T U A L//

  va.toggleCategory = function(revision) {
      
      console.log(revision);
      console.log(revision['cronogramaid']);
      //revision.expanded = !revision.expanded;

      revision_cronograma=revision.revision_cronograma;
      proyectoid=revision.proyectoid;
      codigoproy=revision.codigo_prop_proy;

      console.log(revision_cronograma);
      console.log(proyectoid);

      proyectoFactory.getDatosxProyectoxFechaxCorte(proyectoid,revision_cronograma,codigoproy)
      .then(function(data) {
        va.thi=data; 
      })
      .catch(function(err) {
        va.thi = {};
      });

      proyectoFactory.getDatosProyectoxPerfomance(proyectoid,revision_cronograma)
      .then(function(datax) {
          va.performance=datax;

        })
      .catch(function(err) {
          va.performance = {};
      })

    };

////////////// L I S T A  D E  E N T R E G A B L E ///////////////////////////////////
//lista de entregables
proyectoFactory.getDatosxEntregable(proyecto['codigo'])
.then(function(data) {
  va.entregable=data;

    for (var i = va.entregable.length - 1; i >= 0; i--) {
      //console.log(va.procronograma[i]['state']);
      if(va.entregable[i]['state']=='A')
      {
        va.revisionE=va.entregable[i]
        console.log(va.revisionE);
     
        proyectoFactory.getDatosListaxEntregables(proyecto['codigo'],va.revisionE['revision_entregable'])
        .then(function(datax) {
          va.listaentregable=datax;
          console.log(va.listaentregable);
        })
        .catch(function(err) {
          va.listaentregable = {};
        })

      }
    };

  //console.log(va.entregable);
})
.catch(function(err) {
  va.entregable = {};X
})

va.buscaentregables = function(revision) {
 
revision_entregable=revision.revision_entregable;
proyectoid=revision.proyectoid;
console.log(revision);

  proyectoFactory.getDatosListaxEntregables(proyecto['codigo'],revision_entregable)
  .then(function(datax) {
    va.listaentregable=datax;
    //console.log(va.listaentregable);
  })
  .catch(function(err) {
    va.listaentregable = {};
  })

};


va.addListaEntregable= function() {

    // va.inserted = {
    //   id: $scope.users.length+1,
    //   name: '',
    //   status: null,
    //   group: null 
    // };
    // va.users.push(va.inserted);

    if(va.listaentregable)
    {
      va.inserted = {
        codigo_prop_proy:va.proyectop.codigo_prop_proy,
        proyectoid:va.proyectop.codigo,  
        revision_entregable: va.revisionE['revision_entregable'],
        id: va.listaentregable.length+1,
        cod_listdet:va.listaentregable.length+1,
        edt: null,
        tipo_documento: null,
        disciplina: null ,
        codigo_anddes: null ,
        codigo_cliente: null ,
        descripcion_entregable: null ,
        fecha_a: null ,
        fecha_b: null ,
        fecha_0: null ,  
        clase:'',       
      };
    }
    else
    {
      va.listaentregable=[];
      va.inserted = {
        codigo_prop_proy:va.proyectop.codigo_prop_proy,
        proyectoid:va.proyectop.codigo, 
        revision_entregable: va.revisionE['revision_entregable'],
        id: va.listaentregable.length+1,
        cod_listdet:va.listaentregable.length+1,        
        edt: null,
        tipo_documento: null,
        disciplina: null ,
        codigo_anddes: null ,
        codigo_cliente: null ,
        descripcion_entregable: null ,
        fecha_a: null ,
        fecha_b: null ,
        fecha_0: null , 
        clase:'',        
      };
    }
    va.listaentregable.push(va.inserted);

};


va.guardatListaentregable = function(data, id) {
    //console.log(data);
    //console.log(id);

    edt=data['edt'];
    tipo_documento=data['tipo_documento'];   
    disciplina=data['disciplina'];
    codigo_anddes=data['codigo_anddes'];
    codigo_cliente=data['codigo_cliente'];
    fecha_0=data['fecha_0'];
    fecha_a=data['fecha_a'];
    fecha_b=data['fecha_b'];
    descripcion_entregable=data['descripcion_entregable'];   
    cod_le=id;
 
    codigo_prop_proy=va.revisionE['codigo_prop_proy'];
    proyectoid=va.revisionE['proyectoid'];
    revision_entregable=va.revisionE['revision_entregable'];

    // console.log(codigo_prop_proy);
    // console.log(proyectoid);
    // console.log(revision_entregable);

    proyectoFactory.setDatosxGuardarxListaxEntregables(
      codigo_prop_proy,proyectoid,revision_entregable,edt,tipo_documento,disciplina,codigo_anddes,codigo_cliente,fecha_0,fecha_a,fecha_b,descripcion_entregable,cod_le)
    .then(function(data) {

        console.log(data);

        proyectoFactory.getDatosxEntregable(proyecto['codigo'])
          .then(function(data) {
            va.entregable=data;

              for (var i = va.entregable.length - 1; i >= 0; i--) {
               
                if(va.entregable[i]['state']=='A')
                {
                  va.revisionE=va.entregable[i]
                  console.log(va.revisionE);
               
                  proyectoFactory.getDatosListaxEntregables(proyecto['codigo'],va.revisionE['revision_entregable'])
                  .then(function(datax) {
                    va.listaentregable=datax;
                    console.log(va.listaentregable);
                  })
                  .catch(function(err) {
                    va.listaentregable = {};
                  })

                }
              };
          
          })
          .catch(function(err) {
            va.entregable = {};X
          })

    })
    .catch(function(err) {
      //va.listaentregable = {};
    })   

    //$scope.user not updated yet
    // angular.extend(data, {id: id});
    // return $http.post('/saveUser', data);
  };

// va.agregarListaentregable = function() {

//     console.log(va.listaentregable);
//     console.log(va.edt);

//     //va.edt=[{ nombre:'xxxx',codigo_edt:'dada'}];

//     if(va.listaentregable)
//     {
//       va.inserted = {
//         codigo_prop_proy:va.proyectop.codigo_prop_proy,
//         proyectoid:va.proyectop.codigo,  
//         revision_entregable: va.revisionE['revision_entregable'],
//         id: va.listaentregable.length+1,
//         cod_listdet:va.listaentregable.length+1,
//         edt: null,
//         tipo_documento: null,
//         disciplina: null ,
//         codigo_anddes: null ,
//         codigo_cliente: null ,
//         descripcion_entregable: null ,
//         fecha_a: null ,
//         fecha_b: null ,
//         fecha_0: null ,  
//         clase:'',       
//       };
//     }
//     else
//     {
//       va.listaentregable=[];
//       va.inserted = {
//         codigo_prop_proy:va.proyectop.codigo_prop_proy,
//         proyectoid:va.proyectop.codigo, 
//         revision_entregable: va.revisionE['revision_entregable'],
//         id: va.listaentregable.length+1,
//         cod_listdet:va.listaentregable.length+1,
        
//         edt: null,
//         tipo_documento: null,
//         disciplina: null ,
//         codigo_anddes: null ,
//         codigo_cliente: null ,
//         descripcion_entregable: null ,
//         fecha_a: null ,
//         fecha_b: null ,
//         fecha_0: null , 
//         clase:'',        
//       };
//     }
//     va.listaentregable.push(va.inserted);

// };


// va.saveTableentregable=function()
// {
//   console.log(va.listaentregable);

//   angular.forEach(va.listaentregable, function(val) {  

//     edt=val['edt'];
//     tipo_documento=val['tipo_documento'];   
//     disciplina=val['disciplina'];
//     codigo_anddes=val['codigo_anddes'];
//     codigo_cliente=val['codigo_cliente'];
//     fecha_0=val['fecha_0'];
//     fecha_a=val['fecha_a'];
//     fecha_b=val['fecha_b'];
//     descripcion_entregable=val['descripcion_entregable'];   
//     cod_le=val['cod_listdet'];
 

//     codigo_prop_proy=va.revisionE['codigo_prop_proy'];
//     proyectoid=va.revisionE['proyectoid'];
//     revision_entregable=va.revisionE['revision_entregable'];

//     proyectoFactory.setDatosxGuardarxListaxEntregables(
//       codigo_prop_proy,proyectoid,revision_entregable,edt,tipo_documento,disciplina,codigo_anddes,codigo_cliente,fecha_0,fecha_a,fecha_b,descripcion_entregable,cod_le)
//     .then(function(data) {
//      // va.listaentregable=data;

//     })
//     .catch(function(err) {
//       //va.listaentregable = {};
//     })   

//   })

// }

va.deleteEntregable=function(index,id)
{
  //alert(index);
  //alert(id);
    codigoproyecto=va.proyectop.codigo_prop_proy;
    proyectoid=va.proyectop.codigo;
    revision_entregable=va.revisionE['revision_entregable'];

    // console.log(index);
    //console.log(codigoentregable);
    //console.log(revision_entregable);
    // console.log(va.listaentregable);
    //var filtered = $filter('filter')(va.listaentregable, {edt: edt,revision_entregable:revision_entregable });

    proyectoFactory.setDatosxEliminarxEntregable(id,codigoproyecto,proyectoid,revision_entregable)
    .then(function(data) {
       //va.listaentregable.splice(va.listaentregable.indexOf(filtered[0]), 1);
       va.listaentregable.splice(index, 1);
    })
    .catch(function(err) {
        console.log("error al eliminar entregable");
    });  
}


va.ShowFormEntregable=function(){  
    va.formVisibilityEntregable=true;    
}

 
va.CancelarEntregable=function(){
      va.formVisibilityEntregable=false;    
}

va.GuardarEntregable=function(){
 codigoproyecto=va.proyectop.codigo_prop_proy;
 proyectoid=va.proyectop.codigo;
 revisionentregable=va.revisionEntregable
 
 //console.log(revisionentregable);
  
  proyectoFactory.setDatosxGuardarxEntregable(codigoproyecto,proyectoid,revisionentregable)
  .then(function(data) {

      console.log(data);
      
      va.inserted = {
        codigo_prop_proy:va.proyectop.codigo_prop_proy,
        proyectoid:va.proyectop.codigo,   
        revision_entregable:revisionentregable,
        state:'A'
      }     
      
      if(va.entregable.length)
      {        
        va.entregable.push(va.inserted);        
      }
      else
      {        
        va.entregable=[];
        va.entregable.push(va.inserted);   
      }
      //alert(data);
      //va.revisionE=va.inserted ;
      console.log(va.entregable);

  })
  .catch(function(err) {
      console.log("error al cargar entregable");
  }); 

}


va.imprimir=function(){
  //console.log('dddddddadad');
  httpFactory.createPdfEntregable('A')
  .then(function(data) {
    //console.log(data);
    //window.open(data.archivo, '_blank');
  })
  .catch(function(err) {

  });

}


va.clases=[
{'title':'a','type':1,ff:false},
{'title':'b','type':2,ff:true},
{'title':'c','type':1,ff:false},
{'title':'d','type':3,ff:true},

]



///////////F I N  L I S T A  D E  E N T R E G A B L E ////////////////////////////////
}]);

app.run(function(editableOptions) {
  editableOptions.theme = 'bs3';
});


