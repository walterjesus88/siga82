/*servicio Factory para el manejo de solicitudes http get, post, update y
delete al servidor*/

app.factory('httpFactory', ['$http', function($http) {

  var url = '/controldocumentario/index/';

  var publico = {
    getIntegrantes: function(){
      return $http.get(url + 'integrantes')
    },
    getProyectos: function() {
      return $http.get(url + 'proyectosjson');
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
    }
  }

  return publico;
}]);
