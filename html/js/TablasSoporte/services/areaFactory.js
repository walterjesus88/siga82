app.factory('areaFactory', ['httpFactory', '$location', '$q',
function(httpFactory, $location, $q) {

    var publico = {
    Area: function(areaid,nombre,area_padre,isproyecto,ispropuesta,iscontacto,iscomercial,orden) {

      this.areaid = areaid;
      this.nombre = nombre;
      this.area_padre = area_padre;
      this.isproyecto = isproyecto;
      this.ispropuesta = ispropuesta;
      this.iscontacto = iscontacto;
      this.iscomercial = iscomercial;
      this.orden = orden;

    },
	}
  return publico;
}]);