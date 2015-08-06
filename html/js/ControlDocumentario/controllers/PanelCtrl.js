/*Controlador PanelCtrl para la vista principal del jefe de Control
Documentario con include a httpFactory para obtener los integrantes de control
documentario y sus ratios*/

app.controller('PanelCtrl', ['httpFactory', function(httpFactory) {

  //referencia del scope
  var panel = this;

  //datos a mostrar
  panel.integrantes = [];
  panel.cantidad_proyectos = {
    total: 0,
    en_proceso: 0,
    stand_by: 0,
    cancelado: 0,
    cerrado: 0
  };

  /*propiedades del grafico de barras: labels: las etiquetas de las barras,
  series: conjunto de datos, datos: datos relacionados a cada serie,
  options: propiedades visuales del grafico*/
  panel.labels = [];
  panel.series = ['En Proceso'];
  panel.datos = [];
  panel.options = {
    legend: true,
    animationSteps: 150,
    animationEasing: "easeInOutQuint"
  };

  //obteniendo los datos al cargar la vista y calculo de sumatorias totales
  httpFactory.getIntegrantes()
  .then(function(data) {
    panel.integrantes = [];
    data.forEach(function(integrante) {
      integrante.nombre = integrante.uid.changeFormat();
      panel.integrantes.push(integrante);
    })
    var max = panel.integrantes.length;
    var valores = [];
    for (var i = 0; i < max; i++) {
      panel.cantidad_proyectos.en_proceso += panel.integrantes[i].A;
      panel.cantidad_proyectos.stand_by += panel.integrantes[i].P;
      panel.cantidad_proyectos.cancelado += panel.integrantes[i].CA;
      panel.cantidad_proyectos.cerrado += panel.integrantes[i].C;
      panel.labels.push(panel.integrantes[i].nombre);
      valores.push(panel.integrantes[i].A);
    }
    panel.datos.push(valores);
    panel.cantidad_proyectos.total = panel.cantidad_proyectos.en_proceso +
      panel.cantidad_proyectos.stand_by + panel.cantidad_proyectos.cancelado +
      panel.cantidad_proyectos.cerrado;
  })
  .catch(function(err) {
    alert('No se pueden mostrar los datos, intentelo nuevamente');
  });
}]);
