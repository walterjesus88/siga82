/*servicio Factory que simula una clase Proyecto con include de httpFactory para
poder actualizar el control documentario y $location para redigir a las vistas
de informacion, generar transmittal y generar reporte*/
app.factory('proyectoFactory', ['httpFactory', '$location',
function(httpFactory, $location) {
  var publico = {
    Proyecto: function(codigo, cliente, nombre, gerente, control_proyecto,
      control_documentario, estado) {
      var estados = {
        'A': 'Activo',
        'P': 'Paralizado',
        'C': 'Cerrado',
        'CA': 'Cancelado'
      }
      this.codigo = codigo;
      this.cliente = cliente;
      this.nombre = nombre;
      this.gerente = gerente.changeFormat();
      this.control_proyecto = control_proyecto.changeFormat();
      this.control_documentario = control_documentario;
      this.estado = estados[estado];

      this.cambiarControlDocumentario = function() {
        httpFactory.setControlDocumentario(this.codigo, this.control_documentario)
        .success(function(res) {
          alert('Control Documentario cambiado');
        })
        .error(function(res) {
          alert('No se pudo cambiar el Control Documentario');
        })
      }

      this.accesoGenerarTr = function() {
        //configuracionTransmittal.setProyecto(proyectoid);
        $location.path("/transmittal/proyecto/" + this.codigo);
      }
    }
  }
  return publico;
}]);
