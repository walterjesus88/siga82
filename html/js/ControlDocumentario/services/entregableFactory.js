app.factory('entregableFactory', ['httpFactory', 'transmittalFactory',
 function(httpFactory, transmittalFactory) {
  var publico = {
    Entregable: function(codigo, edt, tipo, disciplina, codigo_anddes,
    codigo_cliente, descripcion, revision, estado, transmittal, correlativo,
    emitido, fecha) {
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
      this.correlativo = correlativo;
      this.emitido = emitido;
      this.fecha = fecha;

      this.cambiarRevision = function() {

      }

      this.cambiarEstado = function() {
        this.estado = 'Old';
      }

      this.agregarToTransmittal = function() {
        transmittalFactory.agregarEntregable(this);
      }
    }
  }
  return publico;
}])
