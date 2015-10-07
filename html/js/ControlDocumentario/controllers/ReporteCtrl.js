app.controller('ReporteCtrl', ['httpFactory', '$routeParams',
function(httpFactory, $routeParams) {
  reporte = this;

  reporte.grupos = [];
  reporte.abrev = ['L', 'M', 'W', 'J', 'V', 'S', 'D',
  'L', 'M', 'W', 'J', 'V', 'S', 'D',
  'L', 'M', 'W', 'J', 'V', 'S', 'D'];

  reporte.dia_sel = new Date();
  reporte.text = reporte.dia_sel.Ddmmyyyy();

  reporte.dias = [];

  calcularFecha = function(position) {
    var f = reporte.text.toDate();
    var nd = f.getDay();
    var pd = nd + 6;
    var fi = f;
    fi.setDate(f.getDate() - pd);
    f.setDate(fi.getDate() + position);
    return f;
  }

  calcularDias = function() {

    var estilo = 'post-highlight yellow';
    for (var i = 0; i < reporte.grupos.length; i++) {
      //var lista_entregables = reporte.grupos[i].entregables;
      for (var j = 0; j < reporte.grupos[i].entregables.length; j++) {
        var cuadros = [];
        for(var k = 0; k < reporte.dias.length; k++) {
          var fecha1 = reporte.grupos[i].entregables[j].fecha_inicial;
          var fecha2 = reporte.grupos[i].entregables[j].fecha_final;
          if (reporte.dias[k].fecha >= fecha1.toDate() &&
          reporte.dias[k].fecha <= fecha2.toDate()) {
            var dia = estilo;
          } else {
            var dia = '';
          }
          cuadros.push(dia);
        }
        reporte.grupos[i].entregables[j].dias = cuadros;
      }
    }
  }

  cargarDias = function() {
    for (var i = 0; i < reporte.abrev.length; i++) {
      dia = {};
      dia.fecha = calcularFecha(i);
      dia.texto = reporte.abrev[i] + ' ' + dia.fecha.getDate();
      reporte.dias.push(dia);
    }
  }

  cargarDias();

  if ($routeParams.proyecto == '' || $routeParams.proyecto == null ||
  $routeParams.proyecto == undefined) {
    httpFactory.getReporte('All')
    .then(function(data) {
      reporte.grupos = data;
      calcularDias();
    })
    .catch(function(err) {

    });
  } else {
    httpFactory.getReporte($routeParams.proyecto)
    .then(function(data) {
      reporte.grupos = data;
      calcularDias();
    })
    .catch(function(err) {

    });
  }



  reporte.actualizarDias = function() {
    reporte.text = reporte.dia_sel.Ddmmyyyy();
    reporte.dias = [];
    cargarDias();
    calcularDias();
  }

}]);
