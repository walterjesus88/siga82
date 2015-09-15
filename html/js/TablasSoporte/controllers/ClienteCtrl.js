app.controller('ClienteCtrl', ['httpFactory', 'clienteFactory','$filter',
function(httpFactory, clienteFactory,$filter) {

  /*referencia del scope y  los arrays que contendra a los proyectos y a los
  integrantes de control documentario*/
  var vc = this;
  
  httpFactory.getClientes()
    .then(function(data) {
      vc.cliente=data;
      // console.log(vc.cliente);
      })
    .catch(function(err) {
      vc.cliente = [];
    });


vc.ShowFormCliente=function(){ 
   vc.formVisibilityCliente=true;    

  }

vc.GuardarCliente= function(){
    
    clienteFactory.setGuardarCliente(vc.clienteid,vc.nombre_comercial,vc.nombre,vc.codigoid,vc.fecha_registro,vc.web,vc.direccion,vc.paisid,vc.departamentoid,vc.provinciaid,vc.distritoid,vc.estado,vc.tag,vc.isproveedor,vc.iscliente,vc.abreviatura,vc.tipo_cliente,vc.ruc,vc.issocio)
    .then(function(data) {
/*insertar una nueva fila*/
      vc.inserted = {
        clienteid:vc.clienteid,
        nombre_comercial:vc.nombre_comercial,
        nombre:vc.nombre,
        codigoid:vc.codigoid,
        fecha_registro:vc.fecha_registro,
        web:vc.web,
        direccion:vc.direccion,
        paisid:vc.paisid,
        departamentoid:vc.departamentoid,
        provinciaid:vc.provinciaid,
        distritoid:vc.distritoid,
        estado:vc.estado,
        tag:vc.tag,
        isproveedor:vc.isproveedor,
        iscliente:vc.iscliente,
        abreviatura:vc.abreviatura,
        tipo_cliente:vc.tipo_cliente,
        ruc:vc.ruc,
        issocio:vc.issocio,
        
      }

      vc.cliente.push(vc.inserted); 
      console.log('guardar cliente');  
      console.log(vc.cliente);  
      vc.formVisibilityCliente=false;

    })
    .catch(function(err) {
              vc.cliente = {};
    });
  }

vc.ModificarCliente=function(){ 
    console.log(vc.area);
  // console.log(vc.inserted);
  angular.forEach(vc.cliente, function(val) {


    clienteid=val['clienteid'];
    nombre_comercial=val['nombre_comercial'];
    nombre=val['nombre'];
    codigoid=val['codigoid'];
    fecha_registro=val['fecha_registro'];
    web=val['web'];
    direccion=val['direccion'];
    paisid=val['paisid'];
    departamentoid=val['departamentoid'];
    provinciaid=val['provinciaid'];
    distritoid=val['distritoid'];
    estado=val['estado'];
    tag=val['tag'];
    isproveedor=val['isproveedor'];
    iscliente=val['iscliente'];
    abreviatura=val['abreviatura'];
    tipo_cliente=val['tipo_cliente'];
    ruc=val['ruc'];
    issocio=val['issocio'];
    

    httpFactory.setModificarCliente(clienteid,nombre_comercial,nombre,codigoid,fecha_registro,web,direccion,paisid,departamentoid,provinciaid,distritoid,estado,tag,isproveedor,iscliente,abreviatura,tipo_cliente,ruc,issocio)
    .then(function(data) {
     // vc.area=data;
    // alert('Cambios guardados satisfactoriamente');
    })
    .catch(function(err) {
      vc.area = [];
    })

  })
    
  }

  vc.CancelarCliente=function(){
    vc.formVisibilityCliente=false;
  }

  // vc.EliminarArea=function(index,codigoedt){

  //   codigoproyecto=vc.proyectop.codigo_prop_proy;
  //   proyectoid=vc.proyectop.codigo;

  //   console.log(index);
  //   console.log(codigoedt);
  //   console.log(vc.edt);

  //   proyectoFactory.setEliminarxEDT(codigoedt,codigoproyecto,proyectoid)
  //   .then(function(data) {
  //     vc.edt.splice(index, 1);          
  //   })
  //   .catch(function(err) {
  //       console.log("error al eliminar edt");
  //   });

  // }


  // vc.showStatus = function(lista) {
  //   var selected = [];
  //   if(lista.area) {
  //     selected = $filter('filter')(vc.area, {areaid: lista.area});
  //   }
  //   return selected.length ? selected[0].areaid : 'Not set';
  // };

}]);

app.run(function(editableOptions) {
  editableOptions.theme = 'bs3';
});