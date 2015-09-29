app.controller('RendirGastosCtrl', ['$scope','httpFactory', 'gastosFactory', '$modal', '$location', '$routeParams',
  function($scope,httpFactory, gastosFactory,  $modal, $location, $routeParams){

  /*referencia del scope en vr, obtencion de la rendicion seleccionada y el objeto
  que contendra los datos de la rendicion*/

  var vrg = this;

  //vrg.rendir = {numero: $routeParams['numero']};

  //console.log(this);
  console.log($routeParams['numero']);

 // console.log(vrg.rendir[0]['numero']);

  //carga de los datos de la rendicion seleccionada
  gastosFactory.getDatosGastos($routeParams['numero'])
  .then(function(data) {
    // console.log("estoy en rendir de gastos");
    // console.log(data);
    vrg.rendir = data;
    //console.log(vr.gasto);
  })
  .catch(function(err) {
    vrg.rendir = [];
  });

  //console.log("llego "+vrg.rendir.numero);

  //funcion para obtener los gastos del servidor
  httpFactory.getRendirPersona($routeParams['numero'])
    .then(function(data) {
      vrg.gastospersona=data;
      // console.log(vrg.gastospersona);
      })
    .catch(function(err) {
      vrg.gastospersona = [];
    });

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