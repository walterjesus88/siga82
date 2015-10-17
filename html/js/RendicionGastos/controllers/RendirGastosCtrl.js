app.controller('RendirGastosCtrl', ['$scope','httpFactory', 'gastosFactory', '$modal', '$location', '$routeParams',
  function($scope,httpFactory, gastosFactory, $modal, $location, $routeParams){

  /*referencia del scope en vr, obtencion de la rendicion seleccionada y el objeto
  que contendra los datos de la rendicion*/

  var vrg = this;
  var numero= $routeParams['numero'];
  var fecha= $routeParams['fecha'];
  var estado_actual = 'A';
  // vrg.listagastos = [];
  // var estado_actual = ;
  // vrg.gastospersona = [];
  // vrg.fecha_factura = new Date();
  // vrg.text = vrg.fecha_factura;


  //console.log(this);
  console.log("fecha "+fecha);

 // console.log(vrg.rendir[0]['numero']);

  //carga de los datos de la rendicion seleccionada
  gastosFactory.getDatosGastos(numero)
  .then(function(data) {
    // console.log("estoy en rendir de gastos");
    // console.log(data);
    vrg.rendir = data;
    console.log(vrg.rendir);
  })
  .catch(function(err) {
    vrg.rendir = [];
  });


  //obtencion de los datos
  /*--------------cliente--------------------------*/
  var iscliente = 'S';
  httpFactory.getClientes(iscliente)
  .then(function(data) {
    vrg.listaclientes = data;
    // console.log("RendirGastosCtrl " + vrg.listagastos)
    // console.log("HOLA " + data)
  })
  .catch(function(err) {
    vrg.listaclientes = [];
  });

  /*--------------proyecto--------------------------*/
  vrg.showProyectos = function() {
    var clienteid=vrg.clienteid;

  httpFactory.getProyectos(clienteid)
  .then(function(data) {
    vrg.listaproyectos = data;
    // console.log("HOLA " + clienteid);
    // vrg.lis = data[0].clienteid;
    // console.log("RendirGastosCtrl " + vrg.listagastos)
  })
  .catch(function(err) {
    vrg.listaproyectos = [];
  });
}
  /*--------------gasto--------------------------*/
  httpFactory.getTiposGasto()
  .then(function(data) {
    vrg.listagastos = data;
    // console.log("RendirGastosCtrl " + vrg.listagastos)
    // console.log("HOLA " + data)
  })
  .catch(function(err) {
    vrg.listagastos = [];
  });

  vrg.ShowFormRendir=function(){
   vrg.formVisibilityRendir=true;
 }



 vrg.GuardarGastos= function(){

    //le da el formato yy-mm-dd a una fecha //
    if(vrg.fecha_factura==null || vrg.fecha_factura=='' || vrg.fecha_factura=='null' || vrg.fecha_factura=='undefined') 
    { 
    }
    else
    { 
     vrg.fecha_factura=gastosFactory.formatoFechas(vrg.fecha_factura);
    }

    // if(vrg.moneda=='Soles'){parseFloat(vrg.monto_total=(vrg.monto_igv*1)+(vrg.otro_impuesto*1)+(vrg.igv*1),2);}
    // if(vrg.moneda=='Dolar Americano'){parseFloat(vrg.monto_total=(vrg.monto_igv*2)+(vrg.otro_impuesto*2)+(vrg.igv*2),2);}

    // dividimos codigo_prop_proy para optener el codigo de proyecto y su revision
    if (vrg.codigo_prop_proy.split('-')[2] ==null) {
    var proyectoid=vrg.codigo_prop_proy.split('-')[0];
    var revision=vrg.codigo_prop_proy.split('-')[1];
      // console.log("2 "+vrg.codigo_prop_proy.split('-')[1]);
    }
    else if (vrg.codigo_prop_proy.split('-')[1] ==null) {
    var proyectoid=vrg.codigo_prop_proy.split('-')[0];
    var revision=' ';
    }
    else {
    var proyectoid=vrg.codigo_prop_proy.split('-')[1];
    var revision=vrg.codigo_prop_proy.split('-')[2];
    }



   gastosFactory.setGuardarGastos(vrg.codigo_prop_proy,proyectoid,revision,vrg.descripcion," ",true,true,vrg.fecha_factura,vrg.num_factura,vrg.moneda,vrg.proveedor,vrg.monto_igv,vrg.otro_impuesto,vrg.igv,vrg.monto_total,numero,fecha)
   .then(function(data) {
    /*insertar una nueva fila*/
    vrg.inserted = {
      codigo_prop_proy:vrg.codigo_prop_proy,
      proyectoid:proyectoid,
      revision:revision,
      descripcion:vrg.descripcion,
      gastoid:" ",
      bill_cliente:true,
      reembolsable:true,
      fecha_factura:vrg.fecha_factura,
      num_factura:vrg.num_factura,
      moneda:vrg.moneda,
      proveedor:vrg.proveedor,
      monto_igv:vrg.monto_igv,
      otro_impuesto:vrg.otro_impuesto,
      igv:vrg.igv,
      monto_total:vrg.monto_total,
      numero_rendicion:numero,
      fecha_gasto:fecha,

    }


    // console.log("numero de rendicion " + numero);
    vrg.rendir.push(vrg.inserted);
      // console.log('guardar rendir');
      // console.log("proyecto " + vrg.proyectoid);
      // console.log("gasto " + vrg.revision);
      // console.log("fecha de rendicion " + fecha);
      // console.log("fecha de factura " + vrg.fecha_factura);
      // console.log("monto total " + vrg.monto_total);
      // vrg.formVisibilityrendir=false;

    })
   .catch(function(err) {
    vrg.rendir = {};
  });
 }




 vrg.CancelarRendir=function(){
  vrg.formVisibilityRendir=false;
}



}]);

app.run(function(editableOptions) {
  editableOptions.theme = 'bs3';
});


/*=============================datepicker=======================================================*/

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
/*=============================datepicker=======================================================*/