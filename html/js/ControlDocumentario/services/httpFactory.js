/*servicio Factory para el manejo de solicitudes http get, post, update y
delete al servidor*/

app.factory('httpFactory', ['$http', function($http) {

  var url = '/controldocumentario/index/';

  var publico = {
    getIntegrantes: function(){
      return $http.get(url + 'integrantes')
    },
    getProyectos: function(estado) {
      return $http.get(url + 'listaproyectos/estado/' + estado);
    },
    getClientes: function() {
      return $http.get(url + 'clientes');
    },
    getTiposProyecto: function() {
      return $http.get(url + 'tipoproyecto');
    },
    getContactosByCliente: function(clienteid) {
      return $http.get(url + 'contactos/clienteid/' + clienteid);
    },
    getProyectoById: function(proyectoid) {
      return $http.get(url + 'proyecto/proyectoid/' + proyectoid);
    },
    getCorrelativoTransmittal: function(proyectoid) {
      return $http.get(url + 'correlativotransmittal/proyectoid/' + proyectoid);
    },
    setControlDocumentario: function(proyectoid, control_documentario) {
      return $http.post(url + 'cambiarcontroldocumentario/proyectoid/' +
      proyectoid + '/controldocumentario/' + control_documentario);
    },
    setConfiguracionTransmittal: function(datos) {
      return $http.post(url + 'guardarconfiguraciontransmittal/codificacion/' +
      datos.codificacion + '/correlativo/' + datos.correlativo + '/formato/' +
      datos.formato + '/tipoenvio/' + datos.tipo_envio + '/cliente/' +
      datos.cliente + '/proyectoid/' + datos.proyecto + '/controldocumentario/' +
      datos.control_documentario + '/atencion/' + datos.atencion + '/diasalerta/'
      + datos.dias_alerta + '/tipoproyecto/' + datos.tipo_proyecto);
    }

  }

  return publico;
}]);
