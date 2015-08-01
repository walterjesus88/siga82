/*Controlador de la vista lista de proyectos*/

app.controller('ProyectoCtrl', ['$location', 'httpFactory', 'configuracionTransmittal',
function($location, httpFactory, configuracionTransmittal) {

  var cd = this;
  cd.proyectos = [];

  httpFactory.getProyectos('A')
  .success(function(res) {
    cd.proyectos = [];
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

  cd.cargarProyectos = function(estado) {
    httpFactory.getProyectos(estado)
    .success(function(res) {
      cd.proyectos = [];
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
  }

  cd.generarTr = function(proyectoid) {
    configuracionTransmittal.setProyecto_sel(proyectoid);
    configuracionTransmittal.setProyecto(proyectoid);
    $location.path("/transmittal");
  }
}]);
