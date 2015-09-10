/*servicio Factory que simula una clase Proyecto con include de httpFactory para
poder actualizar el control documentario y $location para redigir a las vistas
de informacion, generar transmittal y generar reporte*/
app.factory('adminFactory', ['httpFactory', '$location', '$q',
function(httpFactory, $location, $q) {

  var datos = {
    codigo_prop_proy:'',
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
    Proyecto: function(codigo_prop_proy,codigo, cliente, nombre, gerente, control_proyecto,
      control_documentario, estado) {
      var estados = {
        'A': 'A',
        'P': 'P',
        'C': 'C',
        'CA': 'CA'
      }

      //this.codigo=codigo_prop_proy;  
      //console.log("hola");
      //console.log(this.codigo_prop_proy);

      this.codigo_prop_proy = codigo_prop_proy;
      this.codigo = codigo;
      this.cliente = cliente;
      this.nombre = nombre;
      this.gerente = gerente.changeFormat();
      this.control_proyecto = control_proyecto.changeFormat();
      this.control_documentario = control_documentario;
      this.estado = estados[estado];

      // this.cambiarControlDocumentario = function() {
      //   httpFactory.setControlDocumentario(this.codigo, this.control_documentario)
      //   .then(function(data) {
      //     alert('Control Documentario cambiado');
      //   })
      //   .catch(function(err) {
      //     alert('No se pudo cambiar el Control Documentario');
      //   })
      // }


      // this.cambiarEstadoProyecto = function(index) {
      //   httpFactory.setCambioEstadoProyecto(this.codigo, this.estado,this.codigo_prop_proy)
      //   .then(function(data) {
      //     alert('Estado del Proyecto cambiado');
      //     console.log($parent.proyectos);
      //     //this.proyectos.splice(index, 1);  
      //   })
      //   .catch(function(err) {
      //     alert('No se pudo cambiar el Estado del Proyecto');
      //   })
      // }

      this.verInformacion = function() {
        //configuracionTransmittal.setProyecto(proyectoid);
        console.log("verInformacion");
        console.log(this.codigo);
        //console.log(this.codx);

        $location.path("/detalle/proyecto/" +this.codigo);
        // +'/codigo/'+this.codigo_prop_proy        
      }
    },


    getDatosxACL: function() {
        var defered = $q.defer();
        var promise = defered.promise;
        httpFactory.getUsuarios()
            .then(function(data) {
                datos = data;
                defered.resolve(datos);
            })
            .catch(function(err) {
                defered.reject(err);
            });
        return promise;      
    },


    getDatosProyecto: function(proyectoid) {
      var defered = $q.defer();
      var promise = defered.promise;
      httpFactory.getProyectoById(proyectoid)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;      
    },

   


//prueba de area //////////////

    setDatosxGuardarxArea: function(nombre,areaid) {
      var defered = $q.defer();
      var promise = defered.promise;     

      httpFactory.setGuardarArea(nombre,areaid)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;      
    },

    setDatosxEliminarxArea: function(areaid) {
      var defered = $q.defer();
      var promise = defered.promise;     

      httpFactory.setEliminarxArea(areaid)
      .then(function(data) {
        datos = data;
        defered.resolve(datos);
      })
      .catch(function(err) {
        defered.reject(err);
      });
      return promise;      
    },

////////////////////////////////

  


  }
  return publico;
}]);
