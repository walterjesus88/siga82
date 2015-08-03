/*Controlador de la vista lista de proyectos*/

app.controller('ProyectoCtrl', ['$location', 'httpFactory', 'configuracionTransmittal',
function($location, httpFactory, configuracionTransmittal) {

  /*referencia del scope y  los arrays que contendra a los proyectos y a los
  integrantes de control documentario*/
  var cd = this;
  cd.proyectos = [];
  cd.control_documentario = [];

  //carga inicial de los proyectos con estado activo
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

  //carga inicial de integrantes de control documentario
  httpFactory.getIntegrantes()
  .success(function(res) {
    cd.control_documentario = res;
  })
  .error(function(res) {
    cd.control_documentario = [];
  })

  //metodo para cargar los proyectos de los diferentes estados
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

  //metodo para cambiar el control documentario asignado al proyecto
  cd.cambiarControlDocumentario = function(proyectoid, control_documentario) {
    httpFactory.setControlDocumentario(proyectoid, control_documentario)
    .success(function(res) {
      alert('Control Documentario cambiado');
    })
    .error(function(res) {
      alert('No se pudo cambiar el Control Documentario');
    })
  }

  //metodo para direccionar a la vista de transmittal con los datos del proyecto
  cd.generarTr = function(proyectoid) {
    configuracionTransmittal.setProyecto(proyectoid);
    $location.path("/transmittal/" + proyectoid);
  }
}]);
