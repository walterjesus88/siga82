/*servicio Factory que simula una clase Proyecto con include de httpFactory para
poder actualizar el control documentario y $location para redigir a las vistas
de informacion, generar transmittal y generar reporte*/
app.factory('proyectoFactory', ['httpFactory', '$q',
function(httpFactory, $q) {

  var datos = {
    codigo: '',
    nombre: '',
    clienteid: '',
    cliente: '',
    unidad_minera: '',
    estado: '',
    fecha_inicio: '',
    fecha_cierre: '',
    control_documentario: '',
    descripcion: '',
    tipo_proyecto: '',
    logo_cliente: ''
  };

  var publico = {
    Proyecto: function(item) {
      var estados = {
        'A': 'Activo',
        'P': 'Paralizado',
        'C': 'Cerrado',
        'CA': 'Cancelado'
      }
      // this.codigo = item.codigo;
      // this.cliente = item.cliente;
      // this.nombre = item.nombre;
      // this.gerente = item.gerente.changeFormat();
      // this.control_proyecto = item.control_proyecto.changeFormat();
      // this.control_documentario = item.control_documentario;
      // this.estado = estados[item.estado];
      // this.carpeta = item.unidad_red;

      this.cambiarCarpeta = function() {
        httpFactory.setCarpeta(this.codigo, this.carpeta)
        .then(function(data) {
          alert('Carpeta cambiada');
        })
        .catch(function(err) {
          alert('No se pudo cambiar la carpeta');
        });
      }

      this.cambiarControlDocumentario = function() {
        httpFactory.setControlDocumentario(this.codigo, this.control_documentario)
        .then(function(data) {
          alert('Control Documentario cambiado');
        })
        .catch(function(err) {
          alert('No se pudo cambiar el Control Documentario');
        })
      }
    },

    // getDatosProyecto: function(proyectoid) {
    //   var defered = $q.defer();
    //   var promise = defered.promise;
    //   httpFactory.getProyectoById(proyectoid)
    //   .then(function(data) {
    //     datos = data;
    //     defered.resolve(datos);
    //   })
    //   .catch(function(err) {
    //     defered.reject(err);
    //   });
    //   return promise;
    // }
  }
  return publico;
}]);
