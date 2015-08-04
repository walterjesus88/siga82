//servicio Factory que simula una clase Proyecto
app.factory('proyectoFactory', ['httpFactory', function(httpFactory) {
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
    }
  }
  return publico;
}]);
