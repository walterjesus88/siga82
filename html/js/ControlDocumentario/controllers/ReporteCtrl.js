app.controller('ReporteCtrl', ['httpFactory', function(httpFactory) {
  reporte = this;

  reporte.grupos = [];
  reporte.dias = ['L', 'M', 'W', 'J', 'V'];

  httpFactory.getReporte('All')
  .then(function(data) {
    reporte.grupos = data;
  })
  .catch(function(err) {

  });

}]);
