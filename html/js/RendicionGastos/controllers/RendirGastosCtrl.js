app.controller('RendirGastosCtrl', ['$scope','httpFactory', 'gastosFactory', '$modal', '$location', '$routeParams',
  function($scope,httpFactory, gastosFactory, $modal, $location, $routeParams){

  /*referencia del scope en vr, obtencion de la rendicion seleccionada y el objeto
  que contendra los datos de la rendicion*/

  var vrg = this;

  vrg.rendir = {numero: $routeParams.rendir};

  console.log(this);
  console.log($routeParams);

  //carga de los datos de la rendicion seleccionada
  gastosFactory.getDatosGastos(vrg.rendir.numero)
  .then(function(data) {
    console.log("estoy en rendir de gastos");
    console.log(data);
    vrg.rendir = data;
    //console.log(vr.gasto);
  })
  .catch(function(err) {
    vrg.rendir = [];
  });

  console.log("llego "+vrg.rendir.numero);

}]);