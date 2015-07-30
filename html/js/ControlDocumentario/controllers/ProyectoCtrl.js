/*Controlador de la vista lista de proyectos*/

app.controller('ProyectoCtrl', ['$location', 'httpFactory', 'configuracionTransmittal',
function($location, httpFactory, configuracionTransmittal) {

  var cd = this;
  cd.proyectos = [];

  httpFactory.getProyectos()
  .success(function(res) {
    res.forEach(function(item) {
      proyecto = new Proyecto(item.codigo, item.cliente, item.nombre,
        item.gerente, item.control_proyecto, item.control_documentario,
        item.estado);
      cd.proyectos.push(proyecto);
    });
  })
  .error(function(res) {
    cd.proyectos = [];
  })

  cd.seleccionarProyecto = function(proyecto) {
    httpFactory.proyecto_seleccionado = proyecto;
  }

  cd.generarTr = function(proyectoid) {
    configuracionTransmittal.setProyecto_sel(proyectoid);
    $location.path("/transmittal");
  }
}]);
