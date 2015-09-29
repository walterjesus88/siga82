app.controller('RendirGastosCtrl', ['$scope','httpFactory', 'gastosFactory', 'rendirgastosFactory', '$modal', '$location', '$routeParams',
  function($scope,httpFactory, gastosFactory, rendirgastosFactory, $modal, $location, $routeParams){

  /*referencia del scope en vr, obtencion de la rendicion seleccionada y el objeto
  que contendra los datos de la rendicion*/

  var vrg = this;
  // var estado_actual = ;
  vrg.gastospersona = [];

  var numero= $routeParams['numero'];

  //console.log(this);
  // console.log(numero);

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


       console.log("numero de rendicion " + numero);
  // funcion para obtener los gastos del servidor
  // httpFactory.getRendirPersona($routeParams['numero'])
  //   .then(function(data) {
  //     vrg.gastospersona=data;
  //     //console.log(vrg.gastospersona); 
  //     })
  //   .catch(function(err) {
  //     vrg.gastospersona = [];
  //   });


    vrg.ShowFormRendir=function(){
   vrg.formVisibilityRendir=true;

  }

    vrg.CancelarRendir=function(){
    vrg.formVisibilityRendir=false;
  }

}]);

app.run(function(editableOptions) {
  editableOptions.theme = 'bs3';
});