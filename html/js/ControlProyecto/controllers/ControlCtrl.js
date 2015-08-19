app.controller('ControlCtrl', ['httpFactory', '$scope','$q',
'proyectoFactory',
function(httpFactory, $scope,$q,proyectoFactory) {

  va = this;


  //obteniendo el codigo del proyecto del scope padre
  var proyecto = $scope.$parent.vt.proyecto;

  //carga de los datos del proyecto seleccionado
  proyectoFactory.getDatosProyecto(proyecto['codigo'])
  .then(function(data) {
    //console.log("estoy en control de proyecto");
    va.proyectop = data; 

    //sirve para cargar datos una vez iniciado //
    httpFactory.getTiempos('A',va.proyectop.codigo_prop_proy,proyecto['codigo'])
    .success(function(data) {         //console.log(data);

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
    //console.log(va.procronograma[0]); 
    console.log("xxxxxxd");
    console.log(va.procronograma[0]);
    console.log("xxxxxxd");

    proyectoFactory.getVerCronogramaxActivo(proyecto['codigo'])
    .then(function(data) {
      va.revi=data[0];
      console.log("va revi");
      console.log(va.revi);
      console.log("va revi");

    })
    .catch(function(err) {
      va.revi = {};
    })
    //va.revi=va.procronograma[0];

  })
 .catch(function(err) {
    va.procronograma = {};
  });

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
  va.comunicacion_activo = '';

  //cambio de panel visible segun menu seleccionado
  va.cambiarPanel = function(panel) {
    if (panel == 'edt') {
      va.edt_activo = 'active';
      va.curva_activo = '';
      va.gestion_activo = '';
      va.comunicacion_activo = '';
    } else if (panel == 'curva') {
      va.edt_activo = '';
      va.curva_activo = 'active';
      va.gestion_activo = '';
      va.perfomance_activo = ''
    } else if (panel == 'gestion') {
      va.edt_activo = '';
      va.curva_activo = '';
      va.gestion_activo = 'active';
      va.comunicacion_activo = '';
    } else if (panel == 'perfomance') {
      va.edt_activo = '';
      va.curva_activo = '';
      va.gestion_activo = '';
      va.perfomance_activo = 'active';
    }
  }

  va.saveColumn= function(column){
    console.log(column);
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
      fecha_ingreso_curvas: null,
      porcentaje_ejecutado: va.porcentaje_ejecutado,
      porcentaje_propuesta: va.porcentaje_propuesta,
      revision_cronograma: va.revi.revision_cronograma,
      codigo_cronograma:va.revi.codigo_cronograma,    
      cronogramaid:va.revi.cronogramaid

    };
      //console.log(va.inserted);
      //
      //va.dat=[];
      if(va.dat.length)
      {
        console.log("si hay");
        va.dat.push(va.inserted);        
      }
      else
      {
        console.log("ppppppppp");
        va.dat=[];
        va.dat.push(va.inserted);
      }
   
      //console.log(va.codigo_cronograma);

      httpFactory.setGuardarCurva('1',va.fecha_curvas,va.porcentaje_ejecutado,
        va.porcentaje_propuesta,va.revi.revision_cronograma,va.revi.codigo_cronograma,va.proyectop.codigo_prop_proy
        ,va.proyectop.codigo,va.revi.cronogramaid,va.revi.revision_propuesta)
       
        .then(function(data) {
          console.log('Curvas cambiado');
        })
        .catch(function(err) {
          console.log('No se pudo cambiar Curvas');
        })
  };

  va.saveUser_l = function(data,id) {
    //console.log(data);
    //console.log(id);
    //angular.extend(data, {id: id});
    //return $http.post('/saveUser', data);
  };

  va.checkName = function(data) {
    //console.log('data');
    // if (data !== 'awesome') {
    //   return "Username should be `awesome`";
    // }
  };

  //va.revision=[]; 
  va.busca = function(revision,codigo,proyectoid) {
    //va.revision=revision;
      // console.log(revision.revision_cronograma);
      // console.log(codigo);
      // console.log(proyectoid);
      console.log("imprimiendo avriables");
      //console.log(va.proyecto.codigo);
      httpFactory.getTiempos(revision.revision_cronograma,codigo,proyectoid)
      .success(function(data) {   //console.log(data);
        va.dat=data[0]['1'];
        console.log(va.dat);

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
  //codigo_prop_proy=revision.codigo_prop_proy;
  proyectoid=revision.proyectoid;
  //codigo_cronograma=revision.codigo_cronograma;
  //cronogramaid=revision.cronogramaid;

  proyectoFactory.getDatosProyectoxPerfomance(proyectoid,revision_cronograma)
  .then(function(datax) {
      va.performance=datax;
      console.log(va.performance);
      for (var i = va.performance.length - 1; i >= 0; i--) {
        //Things[i]
        va.thi=va.performance[i]['items'];
        //console.log(va.thi);
      };
      //console.log("estas en performance");
    })
  .catch(function(err) {
      va.performance = {};
  })

};



//revision='A';
proyectoFactory.getVerCronogramaxActivo(proyecto['codigo'])
.then(function(data) {
  //console.log("fsfs performance");
  revision=data[0]['revision_cronograma'];

  //console.log(data[0]);
  //console.log("fsfs performance");
  proyectoFactory.getDatosProyectoxPerfomance(proyecto['codigo'],revision)
  .then(function(datax) {
      va.performance=datax;
      console.log(va.performance);
      for (var i = va.performance.length - 1; i >= 0; i--) {
        //Things[i]
        va.thi=va.performance[i]['items'];

        //console.log(va.thi);
      };
      //console.log("estas en performance");
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
      revision_cronograma=val['revision_cronograma'] ,
      proyectoid=val['proyectoid'] ,
      codigo_performance=val['codigo_performance'] ,
      revision_propuesta=val['revision_propuesta'] , 
      fecha_ingreso_performance=val['fecha_ingreso_performance'],
      fecha_calculo_performance=val['fecha_calculo_performance'] ,
      costo_real =val['costo_real'] ,
      horas_real =val['horas_real'] ,
      fecha_comienzo_real=val['fecha_comienzo_real'] ,
      fecha_fin_real=val['fecha_fin_real'] ,

      proyectoFactory.setActualizarPerformance(codigo_prop_proy,codigo_actividad,actividadid,cronogramaid,
        codigo_cronograma,codigo_performance,fecha_calculo_performance,proyectoid,
        revision_cronograma,fecha_ingreso_performance,revision_propuesta,costo_real,horas_real,fecha_comienzo_real,
        fecha_fin_real)
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
  //     // actually delete user
  //     if (user.isDeleted) {
  //       $scope.users.splice(i, 1);
  //     }
  //     // mark as not new 
  //     if (user.isNew) {
  //       user.isNew = false;
  //     }

  //     // send on server
  //     results.push($http.post('/saveUser', user));      
  //}

  //   return $q.all(results);
};





}]);

app.run(function(editableOptions) {
  editableOptions.theme = 'bs3';
});app.run(function(editableOptions) {
  editableOptions.theme = 'bs3';
});

