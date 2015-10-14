// app.directive('ngEnter', function () {
//     return function (scope, element, attrs) {
//         element.bind("keydown keypress", function (event) {
//             if(event.which === 13) {
//                 scope.$apply(function (){
//                     scope.$eval(attrs.ngEnter);
//                 });

//                 event.preventDefault();
//             }
//         });
//     };

//     return {
//             restrict: 'A',
//             scope: {
//                 confirm: '@',
//                 confirmAction: '&'
//             },
//             link: function (scope, element, attrs) {
//                 element.bind('click', function (e) {
//                     if (confirm(scope.confirm)) {
//                         scope.confirmAction();
//                     }
//                 });
//             }
//     };
// });


app.directive('chart', function(){
    return {
        restrict: 'E',
        replace: true,
        template: '<div></div>',
        scope: {
            config: '='
        },
        link: function (scope, element, attrs) {
            var chart;
            var process = function () {
                var defaultOptions = {

                    chart: { renderTo: element[0] },
                };
                var config = angular.extend(defaultOptions, scope.config);
                chart = new Highcharts.Chart(config);
            };
            process();
            scope.$watch("config.series", function (loading) {
                process();
            });
            scope.$watch("config.loading", function (loading) {
                if (!chart) {
                    return;
                }
                if (loading) {
                    chart.showLoading();
                } else {
                    chart.hideLoading();
                }
            });
        }
    };
});


app.directive('uiDate', ['uiDateConfig', 'uiDateConverter', function (uiDateConfig, uiDateConverter) {
  'use strict';
  var options;
  options = {};
  angular.extend(options, uiDateConfig);
  return {
    require:'?ngModel',
    link:function (scope, element, attrs, controller) {
      var getOptions = function () {
        return angular.extend({}, uiDateConfig, scope.$eval(attrs.uiDate));
      };
      var initDateWidget = function () {
        var showing = false;
        var opts = getOptions();

        function setVal() {
          var keys = ['Hours', 'Minutes', 'Seconds', 'Milliseconds'],
              isDate = angular.isDate(controller.$modelValue),
              preserve = {};

          if(isDate && controller.$modelValue.toDateString() === element.datepicker('getDate').toDateString()) {
            return;
          }

          if (isDate) {
            angular.forEach(keys, function(key) {
              preserve[key] = controller.$modelValue['get' + key]();
            });
          }
          controller.$setViewValue(element.datepicker('getDate'));

          if (isDate) {
            angular.forEach(keys, function(key) {
               controller.$viewValue['set' + key](preserve[key]);
            });
          }
        }

        // If we have a controller (i.e. ngModelController) then wire it up
        if (controller) {

          // Set the view value in a $apply block when users selects
          // (calling directive user's function too if provided)
          var _onSelect = opts.onSelect || angular.noop;
          opts.onSelect = function (value, picker) {
            scope.$apply(function() {
              showing = true;
              setVal();
              _onSelect(value, picker);
              element.blur();
            });
          };

          var _beforeShow = opts.beforeShow || angular.noop;
          opts.beforeShow = function(input, picker) {
            showing = true;
            _beforeShow(input, picker);
          };

          var _onClose = opts.onClose || angular.noop;
          opts.onClose = function(value, picker) {
            showing = false;
            _onClose(value, picker);
          };
          element.off('blur.datepicker').on('blur.datepicker', function() {
            if ( !showing ) {
              scope.$apply(function() {
                element.datepicker('setDate', element.datepicker('getDate'));
                setVal();
              });
            }
          });

          // Update the date picker when the model changes
          controller.$render = function () {
            var date = controller.$modelValue;
            if ( angular.isDefined(date) && date !== null && !angular.isDate(date) ) {
                if ( angular.isString(controller.$modelValue) ) {
                    date = uiDateConverter.stringToDate(attrs.uiDateFormat, controller.$modelValue);
                } else {
                    throw new Error('ng-Model value must be a Date, or a String object with a date formatter - currently it is a ' + typeof date + ' - use ui-date-format to convert it from a string');
                }
            }
            element.datepicker('setDate', date);
          };
        }
        // Check if the element already has a datepicker.
        if (element.data('datepicker')) {
            // Updates the datepicker options
            element.datepicker('option', opts);
            element.datepicker('refresh');
        } else {
            // Creates the new datepicker widget
            element.datepicker(opts);

            //Cleanup on destroy, prevent memory leaking
            element.on('$destroy', function () {
               element.datepicker('destroy');
            });
        }

        if ( controller ) {
          // Force a render to override whatever is in the input text box
          controller.$render();
        }
      };
      // Watch for changes to the directives options
      scope.$watch(getOptions, initDateWidget, true);
    }
  };
}
])
app.factory('uiDateConverter', ['uiDateFormatConfig', function(uiDateFormatConfig){

    function dateToString(dateFormat, value){
        dateFormat = dateFormat || uiDateFormatConfig;
        if (value) {
            if (dateFormat) {
                return jQuery.datepicker.formatDate(dateFormat, value);
            }

            if (value.toISOString) {
                return value.toISOString();
            }
        }
        return null;
    }

    function stringToDate(dateFormat, value) {
        dateFormat = dateFormat || uiDateFormatConfig;
        if ( angular.isString(value) ) {
            if (dateFormat) {
                return jQuery.datepicker.parseDate(dateFormat, value);
            }

            var isoDate = new Date(value);
            return isNaN(isoDate.getTime()) ? null : isoDate;
        }
        return null;
    }

    return {
        stringToDate: stringToDate,
        dateToString: dateToString
    };

}])
app.constant('uiDateFormatConfig', '')
app.directive('uiDateFormat', ['uiDateConverter', function(uiDateConverter) {
  var directive = {
    require:'ngModel',
    link: function(scope, element, attrs, modelCtrl) {
        var dateFormat = attrs.uiDateFormat;

        // Use the datepicker with the attribute value as the dateFormat string to convert to and from a string
        modelCtrl.$formatters.unshift(function(value) {
            return uiDateConverter.stringToDate(dateFormat, value);
        });

        modelCtrl.$parsers.push(function(value){
            return uiDateConverter.dateToString(dateFormat, value);
        });

    }
  };

  return directive;
}]);

app.controller('ControlCtrl', ['httpFactory', '$scope','$filter','$q',
'proyectoFactory',
function(httpFactory, $scope,$filter,$q,proyectoFactory) {

  va = this;
  //obteniendo el codigo del proyecto del scope padre
  var proyecto = $scope.$parent.vt.proyecto;

proyectoFactory.getLeerSessionUsuario(proyecto['codigo'])
.then(function(data) 
{    
      console.log(data);
      va.gerente=data['is_gerente'];
      va.jefearea=data['is_jefe'];
      va.responsable=data['is_responsableproyecto'];
      va.areaid=data['areaid'];
     
      proyectoFactory.getLeerEstadosListaE(proyecto['codigo'],va.areaid,va.gerente,va.jefearea,va.responsable)
      .then(function(data) {        
          va.statelista=data;
          console.log(data);        
          status=data['status'];
          if(data=='' )
          {
            if(va.responsable=='S' ||  va.gerente=='S'){
              va.statelista.indice=1;              
            }

            if(va.gerente=='S'){
              //console.log('gggg');
              va.activareditar=true; 
            }
          }
          else
          { 
        //Esto era para activar el agregar segun 
            switch(data['indice']) 
            {
              case 1:
                if(data['indice']==1 && (va.responsable=='S' ))
                {
                  va.activareditar=true;              
                }
                else
                {
                  va.activareditar=false;              
                }
              break;
              case 2:
                //if(data['indice']==2 && (va.jefearea=='S' ))
                //{
                  //va.activareditar=true;         
                //} 
                //else
                //{
                  va.activareditar=false;
                //}   
              break;
              case 3:
                //if(va.responsable=='S' || va.gerente=='S')
                //{
                //  va.activareditar=true;    
                //} 
                //else
                //{
                  va.activareditar=false;
                //}   
              break;
              case 4:
                //if(data['indice']==4 && va.gerente=='S' )
                //{
                //  if(status=='gr')
                //  {
                //    va.activareditar=true;                
                //  } 
                //  else
                //  {             
                //    va.activareditar=false;
                //  }
                //} 
                //else
                //{
                  va.activareditar=false;              
                //}           
              break;
              case 5:
                  //if(va.jefearea=='S')
                  //{               
                  //  va.activareditar=true;                
                  // }
                  //else
                  //{
                    va.activareditar=false; 
                  //}
              break;
              case 6:
                  va.activareditar=false; 
              break;
              case 7:
                  //if(va.gerente=='S' )
                  //{
                  //  va.activareditar=true;
                  //}
                  //else
                  //{                
                    va.activareditar=false;            
                  //}
              break;
              case 8:                         
                    va.activareditar=false;
              break;
              case 9:                         
                  va.activareditar=false;
              break;
              default:
                  // if(va.gerente=='S' )
                  // {
                  //   va.activareditar=true;                
                  // }
                  // else
                  // {
                    va.activareditar=false;                
                  //}
            }
          }

      })
      .catch(function(err) {
          console.log("error al eliminar entregable");
      }); 
})
.catch(function(err) {
  console.log("error al eliminar entregable");
}); 


//C A R G A  D E   L O S  D A T O S   D E L   P R O Y E C T O  S E L EC C I O N A D O 
proyectoFactory.getDatosProyecto(proyecto['codigo'])
.then(function(data) {
  va.proyectop = data; 
    proyectoFactory.getVerCronogramaxActivo(proyecto['codigo'])
    .then(function(data)
    {
      if(data=='')
      {        
      }
      else
      {     
        revision=data[0]['revision_cronograma'];  
        //console.log(revision);
        //sirve para cargar datos una vez iniciado //
        httpFactory.getTiempos(revision,va.proyectop.codigo_prop_proy,proyecto['codigo'])
        .success(function(data) {         
            va.dat=data[0]['1'];
        
            var max = data[0]['1'].length;     
            var varx=[];
            var vary=[];
            var labelx=[];

            var label= $.map(data[0], function(value, index) 
            {   
                for (var i =0; i < max; i++) {
                  a=[];
                       
                  a=value[i]['fecha_curvas'];        
                  labelx.push(a);
               };
               return [labelx];
            });    
            
            va.labels=label[0];         

            var array = $.map(data[0], function(value, index)
            {   
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
            console.log(va.data[0]);
            console.log(va.data[1]);

            this.$scope = $scope;
            $scope.chartConfig = {
            xAxis: {
                categories: va.labels
                //['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec']
            },

            plotBands: [{ // visualize the weekend
                from: 4.5,
                to: 6.5,
                color: 'rgba(68, 170, 213, .2)'
            }],
            
            title: 
            {
                text: 'PROYECTO'+' '+ proyecto['codigo'] + "-"+' '+'REVISION'+' '+ revision
            },           

            subtitle: {
                text: document.ontouchstart === undefined ?
                    'CURVA REALIZADA A TRAVES DE PORCENTAJES DE PERFORMANCE':'-'
            },

            yAxis: { title: { text: 'Porcentaje' } },

            tooltip: { valueSuffix: ' celsius' },
            legend: { align: 'center', verticalAlign: 'bottom', borderWidth: 0 },

            plotOptions: {
                series: {
                    animation: {
                        duration: 10000,
                        easing: 'easeInOutQuint',
                        animationSteps: 150,
                    }
                },
                areaspline: {
                fillOpacity: 0.1
                },
        
                areaspline: {
                    fillColor: {
                        linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
                        stops: [
                            [0, Highcharts.getOptions().colors[0]],
                            [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                        ]
                    },
                    marker: {
                        radius:5
                    },
                    lineWidth: 1,
                    states: {
                        hover: {
                            lineWidth: 1
                        }
                    },
                    threshold: null
                }
            },
            series : [
              {
              type:'areaspline',
              name:'Planeado',              
              data: va.data[0],           
              },
              {
              type:'areaspline',
              name:'Real',
              data: va.data[1],           
              },
            ]
          };

        })      
        .error(function(data) {
             va.data = [] ; 
             console.log("no hay datos de busqueda");
        });        
      }
    })
    .catch(function(err) {
      va.procronograma = {};
    });
})
.catch(function(err) {
  va.proyectop = {};
});



//T R A E  D A T O S  D E  C R O N O G R A M A//  
proyectoFactory.getDatosProyectoxCronograma(proyecto['codigo'])
.then(function(data) {
  va.procronograma=data;

  for (var i = va.procronograma.length - 1; i >= 0; i--) {      
    if(va.procronograma[i]['state']=='A')
    {
        va.revi=va.procronograma[i]
    }
  };
})
 .catch(function(err) {
    va.procronograma = {};
    va.revi = {};
  });
//////////////////////////////////////////////////////////*Anadir cronograma*///////////////////////////////////////////////////////////

va.ShowFormCronograma=function(){ 
  va.formVisibilityCronograma=true;    
} 
  
va.doIt = function() { alert('did it!'); };

va.GuardarCronograma= function()
{
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

va.ModificarCronograma=function(data,cronogramaid)
{
  codigoproyecto=va.proyectop.codigo_prop_proy;
  proyectoid=va.proyectop.codigo;
  codigo_cronograma=data.codigo_cronograma;
  revision_cronograma=data.revision_cronograma;
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
  proyectoFactory.setDatosxEliminarxCronograma(cronogramaid,codigoproyecto,proyectoid)
  .then(function(data) {
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
  } 
    else if (panel == 'performance')
  {
    va.edt_activo = '';
    va.curva_activo = '';
    va.cronograma_activo = '';
    va.fechacorte_activo = '';
    va.performance_activo = 'active';
    va.listaentregable_activo = '';
  }
    else if (panel == 'fechacorte')
  {
    va.edt_activo = '';
    va.curva_activo = '';
    va.cronograma_activo = '';
    va.performance_activo = '';
    va.fechacorte_activo = 'active';
    va.listaentregable_activo = '';
  }
    else if (panel == 'listaentregable') 
  {
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
}

va.ShowForm=function(){  
  va.formVisibility=true;    
}

va.Cancelarcurva=function(){
  va.formVisibility=false;    
}

va.deleteFecha=function(codigo_curvas)
{
  var filtered = $filter('filter')(va.dat, {codigo_curvas: codigo_curvas});
  va.dat.splice(va.dat.indexOf(filtered[0]), 1);
  httpFactory.setEliminarfechaproyecto(codigo_curvas)
  .then(function(data) {
    console.log('Curvas eliminada');
  })
  .catch(function(err) {
    console.log('No se pudo eliminar Curvas');
  }) 
}

va.Guardarcurva = function() 
{ 
  va.inserted = {    
    codigo_prop_proy:va.proyectop.codigo_prop_proy,
    proyectoid:va.proyectop.codigo,
    fecha_curvas:  va.fecha_curvas,
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
}

va.busca = function(revision,codigo,proyectoid) {      
  httpFactory.getTiempos(revision.revision_cronograma,codigo,proyectoid)
  .success(function(data) {
    va.dat=data[0]['1'];
    console.log(va.dat);
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
    console.log(va.labels);
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
        console.log(va.data);

        this.$scope = $scope;
        $scope.chartConfig = {
            xAxis: {
                categories: va.labels               
            },

            plotBands: [{ // visualize the weekend
                from: 4.5,
                to: 6.5,
                color: 'rgba(68, 170, 213, .2)'
            }],
            
            title: {
                text: 'PROYECTO'+' '+ proyecto['codigo'] + "-"+' '+'REVISION'+' '+ revision.revision_cronograma
            },           

            subtitle: {
                text: document.ontouchstart === undefined ?
                    'CURVA REALIZADA A TRAVES DE PORCENTAJES DE PERFORMANCE':'-'
            },

            yAxis: { title: { text: 'Porcentaje' } },

            tooltip: { valueSuffix: ' celsius' },
            legend: { align: 'center', verticalAlign: 'bottom', borderWidth: 0 },

            plotOptions: {
                series: {
                    animation: {
                        duration: 10000,
                        easing: 'easeInOutQuint',
                        animationSteps: 150,
                    }
                },
                areaspline: {
                fillOpacity: 0.1
                },
        
                areaspline: {
                    fillColor: {
                        linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1},
                        stops: [
                            [0, Highcharts.getOptions().colors[0]],
                            [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                        ]
                    },
                    marker: {
                        radius:5
                    },
                    lineWidth: 1,
                    states: {
                        hover: {
                            lineWidth: 1
                        }
                    },
                    threshold: null
                }
            },
            series : [
              {
              type:'areaspline',
              name:'Planeado',              
              data: va.data[0],           
              },
              {
              type:'areaspline',
              name:'Real',
              data: va.data[1],           
              },
            ]
          };      
  })
  .error(function(data) {
    va.data = [] ; 
    console.log("no hay datos de busqueda");
  });
};  

//va.series = ['29 Abr', '14 May', '21 May', '28 May', '04 Jun', '11 Jun', '18 Jun','25 Jun','02 Jun',];
// va.series = ['Planeado', 'Real'];
// console.log(va.series);
// va.options = {  
//   legend: true,
//   animationSteps: 150,
//   animationEasing: "easeInOutQuint",
//   exporting: { enabled: true },
// };

  // va.data = [
  //   [65/100, 59/100, 80/100, 81/100, 56/100, 55/100, 40/100],
  //   [28/100, 48/100, 40/100, 19/100, 86/100, 27/100, 90/100]
  // ];

////////////////////////////////////////////////////*P E R F O R M A N C E*////////////////////////////////////////////////////////////////
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
  proyectoFactory.getDatosxProyectoxFechaxCorte(proyectoid,revision_cronograma,codigoproy)
  .then(function(data)
  {
    va.thi=data;
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
    va.thi=[];
  }
  else
  {
    codigoproy=data[0]['codigo_prop_proy'];
    revision=data[0]['revision_cronograma'];

  //F E C H A S  D E  C O R T E///
  proyectoFactory.getDatosxProyectoxFechaxCorte(proyecto['codigo'],revision,codigoproy)
    .then(function(data) {
      va.thi=data;    
      console.log(va.thi.length); 
      va.subtotal_costopro=0;

      va.subtotal_horaspro=0;
      va.subtotal_porcplani=0;
      va.subtotal_porcreal=0;
      va.subtotal_fecha = [];

      for (var i = 0; i < va.thi.length; i++) 
      { 
        va.subtotal_fecha[i]=0;
      };

      angular.forEach(va.thi, function(val,id) {        
        if(val['state_performance']=='A')
        {
          va.fecha_corte_activa=val['fecha'];
        }
        if(val['state_performance']=='C')
        {
          va.fecha_corte_cerrada=val['fecha'];           
        }
      });
    })
    .catch(function(err) {
      va.thi = {};
    });

    proyectoFactory.getDatosProyectoxPerfomance(proyecto['codigo'],revision)
    .then(function(datax) {
        va.performance=datax;

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

  proyectoFactory.getDatosxProyectoxFechaxCorte(va.revi['proyectoid'],va.revi['revision_cronograma'],va.revi['codigo_prop_proy'])
  .then(function(data) {
    var fechacorte_cambiar
    var fechacorte_cam


    for (var i = 0; i < data.length; i++)        
    {
      if(data[i]['state_performance']=='I')
      {
        fechacorte_cam=data[i]['fechacorteid'];
        //alert(fechacorte_cam);
      }

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
          //fechacorte_cambiar=data[i+1]['fechacorteid'];
          fechacorte_cambiar=fechacorte_cam;
          //alert(fechacorte_cambiar);
        } 

        proyectoFactory.getCerrarxProyectoxFechaxCorte(proyectoid,codigo_prop_proy,fecha_corte,fechacorte_cambiar)
        .then(function(data) {
        })
        .catch(function(err) {       
        });
      } 
    };     
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

va.checkName=function(data, id)
{
  //C A L C U L O  D E   S U B T O T A L E S//
  revision_cronograma=va.performance[id]['revision_cronograma'];
   actividad1digito= va.performance[id]['actividadid'].length;
   if(actividad1digito==1)
   {
    va.subtotal_costopro+=parseInt(va.performance[id]['costo_propuesta']);
    va.subtotal_horaspro+=parseInt(va.performance[id]['horas_propuesta']);
    va.subtotal_porcplani+=parseInt(va.performance[id]['porcentaje_planificado']);
    va.subtotal_porcreal+=parseInt(va.performance[id]['porcentaje_real']);       
    va.dat=[];    
    
    for (var i = 0; i < va.thi.length; i++)     
    {     
      angular.forEach(va.performance[id]['items'], function(val,ids) {
        if(va.thi[i]['fecha']==val['fecha_performance'])
        {
          va.subtotal_fecha[i]+=parseFloat(val['porcentaje_performance']);
          va.inserted = {
            fecha:va.thi[i]['fecha'],
            porcentaje_ejecutado:va.subtotal_fecha[i],       
          }
          va.dat.push(va.inserted);            

          proyectoFactory.setGuardarxPorcenxCurvas(proyecto['codigo'],revision_cronograma,va.thi[i]['fecha'],va.subtotal_fecha[i])
          .then(function(data) { 
          })
          .catch(function(err) {
              console.log("error edt");                    
          });
        }

      })
    };
        // for (var i = container.length - 1; i >= 0; i--) {    
        //   porcentaje={ porcentaje_ejecutado:container[i]},        
        //   va.dat.push(porcentaje);
        // };
        //   console.log(va.dat);
        // angular.forEach(va.dat, function(valporcentaje,id) {
        //   console.log(valporcentaje);
        // })        
   }

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

  //alert(cadena);
  if(cadena==null || cadena=='')
  {   // alert(' es null ');
      // alert(f_comienzo);
      // alert(f_fin);
      if(f_comienzo==null && f_fin==null)
      {
        f_comienzo='';
        f_fin='';
        fecha_comienzo='';
        fecha_fin='';
      }
      else
      {
        if(f_comienzo==null || f_comienzo=='' )
        {     
          //alert('esta aqui');
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
        else if(f_fin==null || f_fin=='')
             {
                //alert('yy-mm-dd');
                // console.log('ddd');         
                // console.log(f_fin);
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
                fecha_comienzo=f_comienzo;

                //alert(fecha_comienzo);
                //alert(fecha_fin);
                //console.log(kkkkkooooo);
              }else if(f_fin!=null && f_comienzo!=null)
              {
                fecha_comienzo=f_comienzo;
                fecha_fin=f_fin;
              }
      }
      //console.log(hhhhhuuuuuu);
  }
  else
  {  
      texto =  ['FC','CF','CC','FF'];

      for (var i = texto.length - 1; i >= 0; i--)
      {
        if(cadena.indexOf(texto[i])!=-1)
        {      
          posicion = cadena.indexOf(texto[i]);               
          valoritem=cadena.substring(0, posicion); 

          fecha_sincro_comienzo=va.performance[valoritem]['fecha_comienzo'];
          //alert(fecha_sincro_comienzo);
          fecha_sincro_fin=va.performance[valoritem]['fecha_fin']; 

          switch(texto[i]) {
          case 'CC':
           if(cadena.indexOf('+')!=-1 || cadena.indexOf('-')!=-1)
            {
                    if(cadena.indexOf('+')!=-1)
                    {
                      //alert("function"+fecha);
                      posiciondelmas = cadena.indexOf('+'); 
                      valordias=cadena.substring(posiciondelmas+1);

                      fecha=proyectoFactory.calculosumafecha(valordias,fecha_sincro_comienzo);
                      // fecha = new Date(fecha_sincro_comienzo);
                      // ki=0; 
                      // while (ki<valordias)
                      // {
                      //   fecha.setTime(fecha.getTime()+24*60*60*1000); // añadimos 1 día
                      //     if (fecha.getDay() == 0 || fecha.getDay() == 6)
                      //     {                                             
                      //     }                  
                      //       else
                      //     { ki++;
                      //     }
                      // }                    
                    }
                    else
                    {
                      fecha=proyectoFactory.calculorestafecha(cadena,fecha_sincro_comienzo);
                      //alert("function"+fecha);
                      //// L A R E S T A   D E  C C /////////

                      // posiciondelmenos = cadena.indexOf('-'); 
                      // valordias=cadena.substring(posiciondelmenos+1);

                      // fecha_sincro_comienzo = fecha_sincro_comienzo.replace(/-/g, '/');                     
                      // fecha = new Date(fecha_sincro_comienzo);
                      
                      // ki=1; 
                      // while (ki<=valordias-1) 
                      // {
                      //     fecha.setTime(fecha.getTime()-24*60*60*1000); // añadimos 1 día
                      //     if (fecha.getDay() == 6  || fecha.getDay() == 0    )
                      //     {                                        
                      //     }  
                      //     else
                      //     {
                      //       ki++;
                      //     }
                      // }
                    }

                    // day=fecha.getDate();
                    // month=fecha.getMonth()+1;
                    // year=fecha.getFullYear();

                    // if (month.toString().length < 2) 
                    // {           
                    //   month = '0' + month;
                    // }
                    // if (day.toString().length < 2) 
                    // {          
                    //   day = '0' + day;
                    // }
                    // fecha_CC=year+"-"+month+"-"+day,
                    fecha_CC=fecha;
                    fecha_comienzo=fecha_CC;
                    //alert(fecha_comienzo);


                    /* C A L C U L O  F E C H A  F I N  C O N  D U R A C I O N*/
                    fecha_fin=proyectoFactory.calculofechafin(fecha_comienzo);
                    // if(fecha_comienzo!=null)
                    //   {                
                    //     fec=fecha_comienzo.toString();
                    //     fecha = new Date(fec);         
                    //     ki=0; 
                    //     while (ki<duracion) {
                          
                    //       fecha.setTime(fecha.getTime()+24*60*60*1000); // añadimos 1 día
                    //         if (fecha.getDay() == 0 || fecha.getDay() == 6)
                    //         {     
                    //          }                  
                    //         else
                    //         {         
                    //           ki++;
                    //         }
                    //     }

                    //     day=fecha.getDate();
                    //     month=fecha.getMonth()+1;
                    //     year=fecha.getFullYear();

                    //     if (month.toString().length < 2) 
                    //     {
                    //       month = '0' + month;
                    //     }
                    //     if (day.toString().length < 2) 
                    //     {                          
                    //       day = '0' + day;
                    //     }                         
                    //     fecha_fin=year+"-"+month+"-"+day;
                    //   }          
                    
            }          
            else
            {
                  valordias=1;

                  fecha_FC=proyectoFactory.calculosumafecha(valordias,fecha_sincro_comienzo);

                  // fecha = new Date(fecha_sincro_comienzo);               
                  // cc_ki=0; 
                  // while (cc_ki<valordias) {                          
                  //     fecha.setTime(fecha.getTime()+24*60*60*1000); // añadimos 1 día
                  //     if (fecha.getDay() == 0 || fecha.getDay() == 6)
                  //     {     
                  //     }                  
                  //     else
                  //     {         
                  //       cc_ki++;
                  //     }
                  // }
   
                  // day=fecha.getDate();
                  // month=fecha.getMonth()+1;
                  // year=fecha.getFullYear();

                  // if (month.toString().length < 2) 
                  // {  month = '0' + month;
                  // }
                  // if (day.toString().length < 2) 
                  // {  day = '0' + day;
                  // }
                  // fecha_CC=year+"-"+month+"-"+day,

                  fecha_comienzo=fecha_CC; 

                  /* C A L C U L O  F E C H A  F I N  C O N  D U R A C I O N*/
                  fecha_fin=proyectoFactory.calculofechafin(fecha_comienzo);

                  //if(fecha_comienzo!=null)
                    //{
                      ///fecha_comienzo = fecha_comienzo.replace(/-/g, '/');
                    //   fec=fecha_comienzo.toString();
                    //   fecha = new Date(fec);
                    //   ki=0; 
                    //   while (ki<duracion) {                          
                    //        fecha.setTime(fecha.getTime()+24*60*60*1000); // añadimos 1 día
                    //         if (fecha.getDay() == 0 || fecha.getDay() == 6)
                    //         {     
                    //          }                  
                    //         else
                    //         {         
                    //           ki++;
                    //         }
                    //   }
                    //   day=fecha.getDate();
                    //   month=fecha.getMonth()+1;
                    //   year=fecha.getFullYear();

                    //   if (month.toString().length < 2) 
                    //   {
                    //     month = '0' + month;
                    //   }
                    //   if (day.toString().length < 2) 
                    //   {                      
                    //     day = '0' + day;
                    //   }
                       
                    //   fecha_fin=year+"-"+month+"-"+day;
                    // }                       
            };
          break;

          case 'FC':
                  
              if(cadena.indexOf('+')!=-1 || cadena.indexOf('-')!=-1)
              {          
                  if(cadena.indexOf('+')!=-1)
                  {
                    //alert(fecha);

                    posiciondelmas = cadena.indexOf('+');                     
                    valordias=cadena.substring(posiciondelmas+1);                        
                    
                    fecha=proyectoFactory.calculosumafecha(valordias,fecha_sincro_fin);
                    // //S U M A 
                    // // F U N C I O N  P A R A  E L I M I N A R  S A B A D O S  Y  D O M I N G O S // 
                    // fecha = new Date(fecha_sincro_fin);

                    // ki=0; 
                    // while (ki<valordias) {
                      
                    //    fecha.setTime(fecha.getTime()+24*60*60*1000); // añadimos 1 día
                    //     if (fecha.getDay() == 0 || fecha.getDay() == 6)
                    //     {     
                    //      }                  
                    //     else
                    //     {         
                    //       ki++;
                    //     }
                    //  }
                            
                  }
                  else
                  {
                    fecha=proyectoFactory.calculorestafecha(cadena,fecha_sincro_fin);
                    //alert(fecha);
                    //RESTA
                    // F U N C I O N  P A R A  E L I M I N A R  S A B A D O S  Y  D O M I N G O S // 
                    // posiciondelmenos = cadena.indexOf('-');  
                    // valordias=cadena.substring(posiciondelmenos+1);

                    // fecha_sincro_fin = fecha_sincro_fin.replace(/-/g, '/');
                    // console.log("fecha trans"+fecha_sincro_fin);
                    // fecha = new Date(fecha_sincro_fin);

                    // ki=1; 
                    // while (ki<=valordias-1) {
                    //     fecha.setTime(fecha.getTime()-24*60*60*1000); // añadimos 1 día
                    //     if (fecha.getDay() == 6  || fecha.getDay() == 0    )
                    //     {                                                            
                    //     }  
                    //     else
                    //     {                      
                    //       ki++;
                    //     }
                    // }                   
                  }
               
                  // day=fecha.getDate();
                  // month=fecha.getMonth()+1;
                  // year=fecha.getFullYear();

                  // if (month.toString().length < 2) 
                  // {  month = '0' + month;
                  // }
                  // if (day.toString().length < 2) 
                  // {  day = '0' + day;
                  // }
                  // fecha_FC=year+"-"+month+"-"+day,
                  fecha_FC=fecha;
                  fecha_comienzo=fecha_FC;
                 
                  /*C A L C U L A R   F E C H A   F I N */
                  fecha_fin=proyectoFactory.calculofechafin(fecha_comienzo);
                      
                    // if(fecha_comienzo!=null)
                    // {
                    //   fec=fecha_comienzo.toString();
                    //   fecha = new Date(fec);
           
                    //   ki=0; 
                    //   while (ki<duracion)
                    //   {
                    //     fecha.setTime(fecha.getTime()+24*60*60*1000); // añadimos 1 día
                    //       if (fecha.getDay() == 0 || fecha.getDay() == 6)
                    //       {                                             
                    //       }                  
                    //         else
                    //       { ki++;
                    //       }
                    //   }

                    //   day=fecha.getDate();
                    //   month=fecha.getMonth()+1;
                    //   year=fecha.getFullYear();

                    //   if (month.toString().length < 2) 
                    //   {
                    //     console.log('si');
                    //     month = '0' + month;
                    //   }
                    //   if (day.toString().length < 2) 
                    //   {
                    //     console.log('si222');
                    //     day = '0' + day;
                    //   }
                       
                    //   fecha_fin=year+"-"+month+"-"+day;
                    // } 
              }
                else
              {         
                  valordias=1;
                  
                  fecha_FC=proyectoFactory.calculosumafecha(valordias,fecha_sincro_fin);
         
                  // fecha = new Date(fecha_sincro_fin);         
                  // ki=0; 
                  //     while (ki<valordias)
                  //     {
                  //       fecha.setTime(fecha.getTime()+24*60*60*1000); // añadimos 1 día
                  //         if (fecha.getDay() == 0 || fecha.getDay() == 6)
                  //         {                                             
                  //         }                  
                  //           else
                  //         { ki++;
                  //         }
                  // }

                  // day=fecha.getDate();
                  // month=fecha.getMonth()+1;
                  // year=fecha.getFullYear();

                  // if (month.toString().length < 2) 
                  // {  month = '0' + month;
                  // }
                  // if (day.toString().length < 2) 
                  // {  day = '0' + day;
                  // }
                  // fecha_FC=year+"-"+month+"-"+day,
           
                  fecha_comienzo=fecha_FC; 
                  /*C A L C U L A R   F E C H A   F I N*/
                  fecha_fin=proyectoFactory.calculofechafin(fecha_comienzo);

                    // if(fecha_comienzo!=null)
                    // {
                    //   fec=fecha_comienzo.toString();
                    //   fecha = new Date(fec);
                     
                    //   ki=0; 
                    //   while (ki<duracion)
                    //   {
                    //     fecha.setTime(fecha.getTime()+24*60*60*1000); // añadimos 1 día
                    //       if (fecha.getDay() == 0 || fecha.getDay() == 6)
                    //       {                                             
                    //       }                  
                    //         else
                    //       { ki++;
                    //       }
                    //   }

                    //   day=fecha.getDate();
                    //   month=fecha.getMonth()+1;
                    //   year=fecha.getFullYear();

                    //   if (month.toString().length < 2) 
                    //   {
                    //     console.log('si');
                    //     month = '0' + month;
                    //   }
                    //   if (day.toString().length < 2) 
                    //   {
                    //     console.log('si222');
                    //     day = '0' + day;
                    //   }
                       
                    //   fecha_fin=year+"-"+month+"-"+day;
                    // }      
                  /*F I N  C A L C U L A R   F E C H A   C O M I E N Z O*/
       
              };
              break;
          
          case 'CF':
            // alert("CF------------"); 
            // alert("f_comienzo"+f_comienzo);
            // alert("f_fin"+f_fin);
            // alert("fecha_corte0"+fecha_corte0);
            if(cadena.indexOf('+')!=-1 || cadena.indexOf('-')!=-1)
            {             
                  if(cadena.indexOf('+')!=-1)
                  {
                    posiciondelmas = cadena.indexOf('+'); 
                    valordias=cadena.substring(posiciondelmas+1);
                    //alert(fecha_sincro_comienzo);          
                    fecha=proyectoFactory.calculosumafecha(valordias,fecha_sincro_comienzo);
                    //alert(fecha);

                    // fecha = new Date(fecha_sincro_comienzo);
                    // ki=0; 
                    // while (ki<valordias)
                    // {
                    //   fecha.setTime(fecha.getTime()+24*60*60*1000); // añadimos 1 día
                    //     if (fecha.getDay() == 0 || fecha.getDay() == 6)
                    //     {                                         
                    //     }                  
                    //       else
                    //     {                       
                    //       ki++;
                    //     }
                    // }

                  }
                  else
                  {
                    //// L A R E S T A   D E  C C /////////
                    fecha=proyectoFactory.calculorestafecha(cadena,fecha_sincro_comienzo);
                    //alert(fecha);
                    // posiciondelmenos = cadena.indexOf('-'); 
                    // valordias=cadena.substring(posiciondelmenos+1);

                    // fecha_comienzo_pred = fecha_sincro_comienzo.replace(/-/g, '/');
                    // fecha = new Date(fecha_sincro_comienzo);
                    
                    // ki=1; 
                    // while (ki<=valordias-1) 
                    // {
                    //     fecha.setTime(fecha.getTime()-24*60*60*1000); // añadimos 1 día
                    //     if (fecha.getDay() == 6  || fecha.getDay() == 0    )
                    //     {
                                                    
                    //     }  
                    //     else
                    //     {
                    //       ki++;
                    //     }
                    // }
                  }

                  // day=fecha.getDate();
                  // month=fecha.getMonth()+1;
                  // year=fecha.getFullYear();

                  // if (month.toString().length < 2) 
                  // {           
                  //   month = '0' + month;
                  // }
                  // if (day.toString().length < 2) 
                  // {          
                  //   day = '0' + day;
                  // }
                  //fecha_CF=year+"-"+month+"-"+day,

                  fecha_CF=fecha;
                  fecha_fin=fecha_CF;
                  //alert(fecha_fin);

                  // C A L C U L O  F E C H A  C O M I E N Z O  R E S T A //
                  fecha_comienzo=proyectoFactory.calculofechacomienzo(fecha_fin);
                  //alert(fecha_comienzo);                  
                  // if(fecha_fin!=null)
                  //   {
                  //     fec = fecha_fin.replace(/-/g, '/');
                  //     fec=fec.toString();
                  //     fecha = new Date(fec);
         
                  //     ki=1; 
                  //     while (ki<=duracion-1) 
                  //     {
                  //         fecha.setTime(fecha.getTime()-24*60*60*1000); // añadimos 1 día
                  //         if (fecha.getDay() == 6  || fecha.getDay() == 0    )
                  //         {
                                                                   
                  //         }  
                  //         else
                  //         {
                  //           ki++;
                  //         }
                  //     }

                  //     day=fecha.getDate();
                  //     month=fecha.getMonth()+1;
                  //     year=fecha.getFullYear();

                  //     if (month.toString().length < 2) 
                  //     {                      
                  //       month = '0' + month;
                  //     }
                  //     if (day.toString().length < 2) 
                  //     {
                  //       day = '0' + day;
                  //     }
                       
                  //     fecha_comienzo=year+"-"+month+"-"+day;
                  //  }
                    //F I N  C A L C U L O  F E C H A  C O M I E N Z O  R E S T A //
            } 
            else
            {
                  valordias=1;  

                  fecha_CF=proyectoFactory.calculosumafecha(valordias,fecha_sincro_comienzo);
                  // fecha = new Date(fecha_sincro_comienzo);
                  // cf_ki=0; 
                  //   while (cf_ki<duracion) {
                          
                  //          fecha.setTime(fecha.getTime()+24*60*60*1000); // añadimos 1 día
                  //           if (fecha.getDay() == 0 || fecha.getDay() == 6)
                  //           {     
                  //            }                  
                  //           else
                  //           {         
                  //             cf_ki++;
                  //           }
                  // }           

                  // day=fecha.getDate();
                  // month=fecha.getMonth()+1;
                  // year=fecha.getFullYear();

                  // if (month.toString().length < 2) 
                  // {  month = '0' + month;
                  // }
                  // if (day.toString().length < 2) 
                  // {  day = '0' + day;
                  // }
                  // fecha_CF=year+"-"+month+"-"+day,

                  fecha_fin=fecha_CF;

                  // C A L C U L O  F E C H A  C O M I E N Z O  R E S T A //
                  fecha_comienzo=proyectoFactory.calculofechacomienzo(fecha_fin); 
                  // if(fecha_fin!=null)
                  //   {
                  //     fec = fecha_fin.replace(/-/g, '/');
                  //     fec=fec.toString();
                  //     fecha = new Date(fec);

                  //     ki=1; 
                  //     while (ki<=duracion-1) 
                  //     {
                  //         fecha.setTime(fecha.getTime()-24*60*60*1000); // añadimos 1 día
                  //         if (fecha.getDay() == 6  || fecha.getDay() == 0    )
                  //         {
                                                                   
                  //         }  
                  //         else
                  //         {
                  //           ki++;
                  //         }
                  //     }

                  //     day=fecha.getDate();
                  //     month=fecha.getMonth()+1;
                  //     year=fecha.getFullYear();

                  //     if (month.toString().length < 2) 
                  //     {                     
                  //       month = '0' + month;
                  //     }
                  //     if (day.toString().length < 2) 
                  //     {                      
                  //       day = '0' + day;
                  //     }
                       
                  //     fecha_comienzo=year+"-"+month+"-"+day;
                  //  }     
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
                    fecha=proyectoFactory.calculosumafecha(valordias,fecha_sincro_fin);
                    //alert(fecha);

                    // fecha = new Date(fecha_sincro_fin);
                    // ki=0; 
                    // while (ki<valordias)
                    // {
                    //   fecha.setTime(fecha.getTime()+24*60*60*1000); // añadimos 1 día
                    //     if (fecha.getDay() == 0 || fecha.getDay() == 6)
                    //     {                                       
                    //     }                  
                    //       else
                    //     {                        
                    //       ki++;
                    //     }
                    // }

                  }
                  else
                  {
                    fecha=proyectoFactory.calculorestafecha(cadena,fecha_sincro_fin);
                    //alert(fecha);

                    //// L A R E S T A   D E  C C /////////
                    // posiciondelmenos = cadena.indexOf('-'); 
                    // valordias=cadena.substring(posiciondelmenos+1);

                    // fec = fecha_sincro_fin.replace(/-/g, '/');
                    // fecha = new Date(fec);
                    
                    // ki=1; 
                    // while (ki<=valordias-1) 
                    // {
                    //     fecha.setTime(fecha.getTime()-24*60*60*1000); // añadimos 1 día
                    //     if (fecha.getDay() == 6  || fecha.getDay() == 0    )
                    //     {                                                               
                    //     }  
                    //     else
                    //     {                        
                    //       ki++;
                    //     }
                    // }
                  }

                  // day=fecha.getDate();
                  // month=fecha.getMonth()+1;
                  // year=fecha.getFullYear();

                  // if (month.toString().length < 2) 
                  // {           
                  //   month = '0' + month;
                  // }
                  // if (day.toString().length < 2) 
                  // {          
                  //   day = '0' + day;
                  // }
                  // fecha_FF=year+"-"+month+"-"+day,
               
                  fecha_FF=fecha;
                  fecha_fin=fecha_FF;

                  // C A L C U L O  F E C H A  C O M I E N Z O  R E S T A //
                  fecha_comienzo=proyectoFactory.calculofechacomienzo(fecha_fin);               

                  // if(fecha_fin!=null)
                  //   {
                  //     fec = fecha_fin.replace(/-/g, '/');
                  //     fec=fec.toString();
                  //     fecha = new Date(fec);
                     
                  //     ki=1; 
                  //     while (ki<=duracion-1) 
                  //     {
                  //         fecha.setTime(fecha.getTime()-24*60*60*1000); // añadimos 1 día
                  //         if (fecha.getDay() == 6  || fecha.getDay() == 0    )
                  //         {                                                 
                  //         }  
                  //         else
                  //         {
                  //           ki++;
                  //         }
                  //     }

                  //     day=fecha.getDate();
                  //     month=fecha.getMonth()+1;
                  //     year=fecha.getFullYear();

                  //     if (month.toString().length < 2) 
                  //     {                      
                  //       month = '0' + month;
                  //     }
                  //     if (day.toString().length < 2) 
                  //     {
                  //       day = '0' + day;
                  //     }
                       
                  //     fecha_comienzo=year+"-"+month+"-"+day;
                  //   }
                    //F I N  C A L C U L O  F E C H A  C O M I E N Z O  R E S T A //
            } 
            else
            {
                  valordias=1;  

                  fecha_FF=proyectoFactory.calculosumafecha(valordias,fecha_sincro_fin);

                  // fecha = new Date(fecha_sincro_fin);
                  // ff_ki=0; 
                  // while (ff_ki<valordias) {
                          
                  //   fecha.setTime(fecha.getTime()+24*60*60*1000); // añadimos 1 día
                  //     if (fecha.getDay() == 0 || fecha.getDay() == 6)
                  //     {     
                  //     }                  
                  //     else
                  //     {         
                  //       ff_ki++;
                  //     }
                  // }         
              

                  // day=fecha.getDate();
                  // month=fecha.getMonth()+1;
                  // year=fecha.getFullYear();

                  // if (month.toString().length < 2) 
                  // {  month = '0' + month;
                  // }
                  // if (day.toString().length < 2) 
                  // {  day = '0' + day;
                  // }
                  // fecha_FF=year+"-"+month+"-"+day,
                  
                  fecha_fin=fecha_FF;  

                  // C A L C U L O  F E C H A  C O M I E N Z O  R E S T A //
                  fecha_comienzo=proyectoFactory.calculofechacomienzo(fecha_fin); 
                  // if(fecha_fin!=null)
                  //   {
                  //     fec = fecha_fin.replace(/-/g, '/');
                  //     fec=fec.toString();
                  //     fecha = new Date(fec);
                  //     // tiempo=fecha.getTime();
                  //     // milisegundos=parseInt(duracion*24*60*60*1000);
                  //     // total=fecha.setTime(tiempo-milisegundos);
                  //     ki=1; 
                  //     while (ki<=duracion-1) 
                  //     {
                  //         fecha.setTime(fecha.getTime()-24*60*60*1000); // añadimos 1 día
                  //         if (fecha.getDay() == 6  || fecha.getDay() == 0    )
                  //         {
                  //           //console.log(fecha.getDay());                                        
                  //         }  
                  //         else
                  //         {                            
                  //           ki++;
                  //         }
                  //     }

                  //     day=fecha.getDate();
                  //     month=fecha.getMonth()+1;
                  //     year=fecha.getFullYear();

                  //     if (month.toString().length < 2) 
                  //     {                      
                  //       month = '0' + month;
                  //     }
                  //     if (day.toString().length < 2) 
                  //     {
                  //       day = '0' + day;
                  //     }
                       
                  //     fecha_comienzo=year+"-"+month+"-"+day;
                  //   }
                    //F I N  C A L C U L O  F E C H A  C O M I E N Z O  R E S T A //

            };
          break;
          }
        }
      }
  }

  //alert(fecha_comienzo);
  calcula_fecha_planificadas=proyectoFactory.restaFechas(fecha_comienzo,fecha_corte0);
  //alert(calcula_fecha_planificadas);
  calcula_h_planificadas=calcula_fecha_planificadas*h_dias_propuesta;
  calculo_c_planificadas=calcula_fecha_planificadas*c_propuesta;
  horas_planificado=Math.round(calcula_h_planificadas);
  costo_planificado=Math.round(calculo_c_planificadas);
  porcentaje_planificado=Math.round((calculo_c_planificadas/costo_propuesta)*100);
    
  if(fecha_comienzo=='NaN-NaN-NaN' || fecha_fin=='NaN-NaN-NaN')
  {
    fecha_comienzo=null;
    fecha_fin=null;
  }

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
  proyectoFactory.setActualizarPerformance(codigo_prop_proy,codigo_actividad,actividadid,cronogramaid,codigo_cronograma,codigo_performance,
        proyectoid,revision_cronograma,fecha_ingreso_performance,revision_propuesta,costo_real,
        horas_real,costo_propuesta,horas_propuesta,horas_planificado,costo_planificado,
        porcentaje_planificado,porcentaje_real,fecha_comienzo_real,fecha_fin_real,
        fecha_fin,fecha_comienzo,nivel_esquema,cadena,sucesoras,duracion)
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
    
    proyectoFactory.setActualizarDatosxPerfomance(codigo_prop_proy,codigo_actividad,actividadid,cronogramaid,
      codigo_cronograma,codigo_performance,porcentaje_performance,proyectoid,revision_cronograma,
      fecha_ingreso_performance,fecha_performance)
    .then(function(data) {
    })
    .catch(function(err) {
      //va.procronograma = {};
    });
  });
}
//////////////////////////*******/////////////////////////////////////
proyectoFactory.getDatosxEDT(proyecto['codigo'])
.then(function(data) {
    va.edt=data;  
})
.catch(function(err) {
  console.log("error edt");       
});

va.showStatus = function(lista) {
  var selected = [];
  if(lista.edt) {
  selected = $filter('filter')(va.edt, {codigo: lista.edt});
  }
  
  return selected.length ? selected[0].nombre : 'editar edt';
 };

va.tipodocumentoE = [
  {value: 'Documento', text: 'Documento'},
  {value: 'Informe', text: 'Informe'},   
]; 

proyectoFactory.getDisciplinaxProyecto(proyecto['codigo'],va.gerente,va.areaid)
.then(function(data) {
  va.disciplina=data;       
})
.catch(function(err) {
       
});

va.showTipodoc = function(lista) {
  var selected = [];
  if(lista.tipo_documento) {
    selected = $filter('filter')(va.tipodocumentoE, {value: lista.tipo_documento});
  }
  return selected.length ? selected[0].text : 'eitar tipo_documento';
};




va.showDisciplina = function(lista) {
  var selected = [];
  if(lista.disciplina) {
    selected = $filter('filter')(va.disciplina, {areaid: lista.disciplina});
  }
  return selected.length ? selected[0].nombre : 'editar disciplina';
};

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

    })
    .catch(function(err) {
          
    });
}

va.ModificarEdt=function(data,codigoedt){ 
  codigoproyecto=va.proyectop.codigo_prop_proy;
  proyectoid=va.proyectop.codigo;
  codigoedtmodificado=data.codigo;
  nombremodificado=data.nombre;
  descripcionmodificado=data.descripcion;

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

//S I R V E  P A R A  C R O N G R A M A  R E V I S I O N  A C T U A L//

va.toggleCategory = function(revision) {

  console.log(revision);
  console.log(revision['cronogramaid']);
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

va.imprimirperformance=function(){
  console.log(va.revi);
  proyectoid=va.revi['proyectoid'];
  revision_cro=va.revi['revision_cronograma'];

  httpFactory.createPdfPerformance(proyectoid,revision_cro)
  .then(function(data) {
    //console.log(data);
    window.open(data.archivo, '_blank');
  })
  .catch(function(err) {
  });
}

////////////// L I S T A  D E  E N T R E G A B L E ///////////////////////////////////
//lista de entregables
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
  va.entregable = {};
})

va.buscaentregables = function(revision) { 
  revision_entregable=revision.revision_entregable;
  proyectoid=revision.proyectoid;
  console.log(revision);

  proyectoFactory.getDatosListaxEntregables(proyecto['codigo'],revision_entregable)
  .then(function(datax) {
    va.listaentregable=datax;
    
  })
  .catch(function(err) {
    va.listaentregable = {};
  })

};

va.addListaEntregable= function() {
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
  console.log(data);
  
  //alert(lista.fecha_a);
  //rowform.$visible = !rowform.$visible;

  edt=data['edt'];
  tipo_documento=data['tipo_documento'];   
  disciplina=data['disciplina'];
  codigo_anddes=data['codigo_anddes'];
  codigo_cliente=data['codigo_cliente'];
  //fecha_a=data['fecha_a'];
  fecha_a=va.fecha_a;
  fecha_b=va.fecha_b;
  fecha_0=va.fecha_0;
  // fecha_b=data['fecha_b'];
  // fecha_0=data['fecha_0'];
  descripcion_entregable=data['descripcion_entregable'];   
  cod_le=id;
    // fecha_a=(fecha_a=='' || fecha_a==null || fecha_a!='null' || fecha_a!='undefined') ? "" : proyectoFactory.formatoFechas(fecha_a); 
    // fecha_b=(fecha_b=='' || fecha_a==null || fecha_a!='null' || fecha_b!='undefined') ? "" : proyectoFactory.formatoFechas(fecha_b); 
    // fecha_0=(fecha_0=='' || fecha_0==null || fecha_0!='null' || fecha_0!='undefined') ? "" : proyectoFactory.formatoFechas(fecha_0); 

    //le da el formato yy-mm-dd a una fecha //
  if(fecha_a==null || fecha_a=='' || fecha_a=='null' || fecha_a=='undefined') 
  { 
  }
  else
  { 
   fecha_a=proyectoFactory.formatoFechas(fecha_a);
  }

  if(fecha_b==null || fecha_b=='' || fecha_b=='null' || fecha_b=='undefined') 
  { 
  }
  else
  { 
    fecha_b=proyectoFactory.formatoFechas(fecha_b);
  }
  if(fecha_0==null || fecha_0=='' || fecha_0=='null' || fecha_0=='undefined') 
  { 
  }
  else
  { 
    fecha_0=proyectoFactory.formatoFechas(fecha_0);
  }
  codigo_prop_proy=va.revisionE['codigo_prop_proy'];
  proyectoid=va.revisionE['proyectoid'];
  revision_entregable=va.revisionE['revision_entregable'];
  proyectoFactory.setDatosxGuardarxListaxEntregables(
    codigo_prop_proy,proyectoid,revision_entregable,edt,tipo_documento,disciplina,codigo_anddes,codigo_cliente,fecha_0,fecha_a,fecha_b,descripcion_entregable,cod_le)
  .then(function(data) {
  
    proyectoFactory.getDatosxEntregable(proyecto['codigo'])
    .then(function(data) {
      va.entregable=data;
      for (var i = va.entregable.length - 1; i >= 0; i--) {
        if(va.entregable[i]['state']=='A')
        {
          va.revisionE=va.entregable[i];                          
          proyectoFactory.getDatosListaxEntregables(proyecto['codigo'],va.revisionE['revision_entregable'])
          .then(function(datax) {
            va.listaentregable=datax;
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
  })
};

va.deleteEntregable=function(index,id)
{
    codigoproyecto=va.proyectop.codigo_prop_proy;
    proyectoid=va.proyectop.codigo;
    revision_entregable=va.revisionE['revision_entregable'];
 
    proyectoFactory.setDatosxEliminarxEntregable(id,codigoproyecto,proyectoid,revision_entregable)
    .then(function(data) {
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
 
  proyectoFactory.setDatosxGuardarxEntregable(codigoproyecto,proyectoid,revisionentregable)
  .then(function(data) {
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
  })
  .catch(function(err) {
      console.log("error al cargar entregable");
  }); 
}

va.imprimir=function(){
  console.log(va.revisionE);
  proyectoid=va.revisionE['proyectoid'];
  revision=va.revisionE['revision_entregable'];

  httpFactory.createPdfEntregable(proyectoid,revision)
  .then(function(data) {
    //console.log(data);
    window.open(data.archivo, '_blank');
  })
  .catch(function(err) {
  });
}

va.desabilitar=1;

va.CambiarEstadoListaEntregable = function(value)
{
  codigoproyecto=va.revisionE['codigo_prop_proy'];
  proyectoid=va.revisionE['proyectoid'];
  revision_entregable=va.revisionE['revision_entregable'];

  proyectoFactory.setCambiarEstadoListaEntregable(value,va.areaid,codigoproyecto,proyectoid,revision_entregable,va.gerente,status)
  .then(function(data) {

    proyectoFactory.getLeerEstadosListaE(proyecto['codigo'],va.areaid,va.gerente,va.jefearea,va.responsable)
    .then(function(data) {      
        va.statelista=data;
        status=data['status'];   
        switch(data['indice']) 
        {
          case 1:
            if(data['indice']==1 && (va.responsable=='S' || va.gerente=='S'))
            {
              va.activareditar=true;            
            }
            else
            {
              va.activareditar=false;              
            }
          break;
          case 2:
            if(data['indice']==2 && (va.jefearea=='S' ))
            {
              va.activareditar=true;
            } 
            else
            {
              va.activareditar=false;
            }   
          break;
          case 3:
            if(va.responsable=='S'  || va.gerente=='S')
            {
              va.activareditar=true;
            } 
            else
            {
              va.activareditar=false;
            }   
          break;
          case 4:
            if(data['indice']==4 && va.gerente=='S' )
            {
              if(status=='gr')
              {
                va.activareditar=true;
              } 
              else
              {
                va.activareditar=false;
              }
            } 
            else
            {
              va.activareditar=false;              
            }
          break;
          case 5:
              if(va.jefearea=='S')
              {                          
                va.activareditar=true;                
              }
              else
              {
                va.activareditar=false; 
              }
          break;
          case 6:
              va.activareditar=false;
          break;
          case 7:
              if(va.gerente=='S' )
              {
                va.activareditar=true;
              }
              else
              {                
                va.activareditar=false;            
              }

          break;
          case 8:                         
                va.activareditar=false;
          break;
          case 9:                         
                va.activareditar=false;
          break;
          default:
              va.activareditar=false;
        } 
    })
    .catch(function(err) {
        //console.log("error al eliminar entregable");
    }); 
  })
  .catch(function(err) {
    //console.log("error al eliminar entregable");
  });  
};
///////////F I N  L I S T A  D E  E N T R E G A B L E ////////////////////////////////
}]);

app.run(function(editableOptions) {
  editableOptions.theme = 'bs3';
});


