app.controller('ControlCtrl', ['httpFactory', '$scope','$filter',
'proyectoFactory',
function(httpFactory, $scope,$filter,proyectoFactory) {

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
    //console.log("xxxxxxd");
    va.revi=va.procronograma[0];

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
  va.tecnicos_activo = 'active';
  va.gestion_activo = '';
  va.comunicacion_activo = '';

  //cambio de panel visible segun menu seleccionado
  va.cambiarPanel = function(panel) {
    if (panel == 'edt') {
      va.edt_activo = 'active';
      va.tecnicos_activo = '';
      va.gestion_activo = '';
      va.comunicacion_activo = '';
    } else if (panel == 'tecnicos') {
      va.edt_activo = '';
      va.tecnicos_activo = 'active';
      va.gestion_activo = '';
      va.comunicacion_activo = '';
    } else if (panel == 'gestion') {
      va.edt_activo = '';
      va.tecnicos_activo = '';
      va.gestion_activo = 'active';
      va.comunicacion_activo = '';
    } else if (panel == 'comunicacion') {
      va.edt_activo = '';
      va.tecnicos_activo = '';
      va.gestion_activo = '';
      va.comunicacion_activo = 'active';
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
      fecha_curvas:  null,
      fecha_ingreso_curvas: va.fecha_ingreso_curvas,
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

      httpFactory.setGuardarCurva('1',va.fecha_ingreso_curvas,va.porcentaje_ejecutado,
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



}]);

app.run(function(editableOptions) {
  editableOptions.theme = 'bs3';
});app.run(function(editableOptions) {
  editableOptions.theme = 'bs3';
});

