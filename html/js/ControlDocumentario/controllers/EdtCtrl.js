app.controller('EdtCtrl', ['httpFactory', '$routeParams',
function(httpFactory, $routeParams) {
  vedt = this;
  var proyecto = $routeParams.proyecto;
  //array que contendra la lista de edts por proyecto
  vedt.edt = [];

  //cargar los edt
  httpFactory.getEdts(proyecto)
  .then(function(data){
    vedt.edt = data;
  })
  .catch(function(err) {
    vedt.edt = [];
  });

  //imprimir la lista de edt
  vedt.imprimirEdt = function() {
    httpFactory.createPdfEdt(proyecto)
    .then(function(data) {
      window.open(data.archivo, '_blank');
    })
    .catch(function(err) {

    });
  }
}]);
