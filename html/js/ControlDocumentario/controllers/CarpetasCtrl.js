app.controller('CarpetasCtrl', ['httpFactory', function(httpFactory) {

  uc = this;

  uc.carpetas = [];
  uc.cantidad_carpetas = {
    en_proceso: 0,
    stand_by: 0,
    cancelado: 0,
    cerrado: 0
  };

  uc.labels = ['En Proceso', 'Paralizado', 'Cancelado', 'Cerrado'];
  uc.series = [];
  uc.datos = [];
  uc.options = {
    legend: true,
    animationSteps: 150,
    animationEasing: "easeInOutQuint"
  };

  httpFactory.getCarpetas()
  .then(function(data) {
    uc.carpetas = data;
    var max = uc.carpetas.length;
    var valores;
    for (var i = 0; i < max; i++) {
      uc.cantidad_carpetas.en_proceso += uc.carpetas[i].A;
      uc.cantidad_carpetas.stand_by += uc.carpetas[i].P;
      uc.cantidad_carpetas.cancelado += uc.carpetas[i].CA;
      uc.cantidad_carpetas.cerrado += uc.carpetas[i].C;
      uc.series.push(uc.carpetas[i].nombre);
      valores = [];
      valores.push(uc.carpetas[i].A);
      valores.push(uc.carpetas[i].P);
      valores.push(uc.carpetas[i].CA);
      valores.push(uc.carpetas[i].C);
      uc.datos.push(valores);
    }
  })
  .catch(function(err) {
    alert('No se pueden mostrar los datos, intentelo nuevamente');
  });

  uc.imprimir = function() {
    httpFactory.createPdfCarpetas()
    .then(function(data) {
      window.open(data.archivo, '_blank');
    })
    .catch(function(err) {

    });
  }

}]);
