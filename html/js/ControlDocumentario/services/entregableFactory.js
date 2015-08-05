app.factory('entregableFactory', ['httpFactory', function(httpFactory) {
  var publico = {
    Entregable: function(codigo, edt, tipo, disciplina, codigo_anddes,
    codigo_cliente, descripcion, revision, estado, transmittal) {
      this.codigo = codigo;
      this.edt = edt;
      this.tipo = tipo;
      this.disciplina = disciplina;
      this.codigo_anddes = codigo_anddes;
      this.codigo_cliente = codigo_cliente;
      this.descripcion = descripcion;
      this.revision = revision;
      this.estado = estado;
      this.transmittal = transmittal;

      this.cambiarRevision = function() {

      }

      this.cambiarEstado = function() {
        this.estado = 'old';
        httpFactory.cambiarEstadoEntregable()
        .success(function(res) {

        })
        .error(function(res) {

        });
      }
    }
  }
  return publico;
}])
