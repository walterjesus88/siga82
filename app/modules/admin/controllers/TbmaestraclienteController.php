<?php
/**
* 
*/
class Admin_TbmaestraclienteController extends Zend_Controller_Action
{
	
	// listar todos los cliente
	public function listarclienteAction() {
      $tb_cliente = new Admin_Model_DbTable_Cliente();
      $listatabla = $tb_cliente->_getClienteAll();
      $this->view->lista_de_cliente = $listatabla;
   	}

   	// mostra vista nuevo cliente
   	public function nuevoclienteAction(){
      $tb_cliente = new Admin_Model_DbTable_Cliente();
      $listatabla = $tb_cliente->_getClienteAll();
      $this->view->lista_de_cliente = $listatabla;
    }

    // guardar nuevo cliente
    public function guardarclienteAction() {
    try
    {
        $this->_helper->layout()->disableLayout(); 
        $formdata['clienteid']=$clienteid = $this->_getParam('clienteid');
        $formdata['nombre_comercial']=$nombre_comercial = $this->_getParam("nombre_comercial");
        $formdata['nombre']=$nombre = $this->_getParam("nombre");   
        $formdata['codigoid']=$codigoid = $this->_getParam("codigoid");   
        $formdata['fecha_registro']=$fecha_registro = $this->_getParam("fecha_registro");   
        $formdata['web']=$web = $this->_getParam("web");   
        $formdata['direccion']=$direccion = $this->_getParam("direccion");   
        $formdata['paisid']=$paisid = $this->_getParam("paisid"); 
        $formdata['departamentoid']=$departamentoid = $this->_getParam('departamentoid');
        $formdata['provinciaid']=$provinciaid = $this->_getParam("provinciaid");
        $formdata['distritoid']=$distritoid = $this->_getParam("distritoid");   
        $formdata['estado']=$estado = $this->_getParam("estado");   
        $formdata['tag']=$tag = $this->_getParam("tag");   
        $formdata['isproveedor']=$isproveedor = $this->_getParam("isproveedor");   
        $formdata['iscliente']=$iscliente = $this->_getParam("iscliente");   
        $formdata['abreviatura']=$abreviatura = $this->_getParam("abreviatura"); 
        $formdata['tipo_cliente']=$tipo_cliente = $this->_getParam("tipo_cliente"); 
        $formdata['ruc']=$ruc = $this->_getParam("ruc"); 
        $formdata['issocio']=$issocio = $this->_getParam("issocio"); 
        $tabla_cliente=new Admin_Model_DbTable_Cliente();    
        if($tabla_cliente->_save($formdata))
        {
          ?>
          <script type="text/javascript">
          alert("Guardado");

            </script>
          <?php
          
        }
        else
        {print "Error: ".$e->getMessage();
          ?>
          <script type="text/javascript">
          alert("Error");
          </script>

          <?php
            //echo "Verifique Datos";   
        }
    }catch (Exception $e) {
        print "Error: ".$e->getMessage();
    }
    }

    // mostrar datos de cliente
    public function mostrarclienteAction() {
    try
    {
        $clienteid = $this->_getParam('clienteid');
        $tabla_cliente=new Admin_Model_DbTable_Cliente();    
        $datos=$tabla_cliente->_getClientexIndice($clienteid);
        $this->view->datoscliente=$datos;
        
    }catch (Exception $e) {
        print "Error: ".$e->getMessage();
    }
    }

    // actualizar datos del cliente
  	public function updateclienteAction() {
    try
    {
        $this->_helper->layout()->disableLayout(); 
        $formdata['clienteid']=$clienteid = $this->_getParam('clienteid');
        $formdata['nombre_comercial']=$nombre_comercial = $this->_getParam("nombre_comercial");
        $formdata['nombre']=$nombre = $this->_getParam("nombre");   
        $formdata['codigoid']=$codigoid = $this->_getParam("codigoid");   
        $formdata['fecha_registro']=$fecha_registro = $this->_getParam("fecha_registro");   
        $formdata['web']=$web = $this->_getParam("web");   
        $formdata['direccion']=$direccion = $this->_getParam("direccion");   
        $formdata['paisid']=$paisid = $this->_getParam("paisid"); 
        $formdata['departamentoid']=$departamentoid = $this->_getParam('departamentoid');
        $formdata['provinciaid']=$provinciaid = $this->_getParam("provinciaid");
        $formdata['distritoid']=$distritoid = $this->_getParam("distritoid");   
        $formdata['estado']=$estado = $this->_getParam("estado");   
        $formdata['tag']=$tag = $this->_getParam("tag");   
        $formdata['isproveedor']=$isproveedor = $this->_getParam("isproveedor");   
        $formdata['iscliente']=$iscliente = $this->_getParam("iscliente");   
        $formdata['abreviatura']=$abreviatura = $this->_getParam("abreviatura"); 
        $formdata['tipo_cliente']=$tipo_cliente = $this->_getParam("tipo_cliente"); 
        $formdata['ruc']=$ruc = $this->_getParam("ruc"); 
        $formdata['issocio']=$issocio = $this->_getParam("issocio"); 
        
        //print_r($formdata);
        //echo $areaid;
        $tabla_cliente=new Admin_Model_DbTable_Cliente();    
        if($tabla_cliente->_updatecliente($formdata,$clienteid))
        {
          ?>
          <script type="text/javascript">
          alert("Actualizado");
            </script>
          <?php
        }
        else
        {
          ?>
          <script type="text/javascript">
          alert("Error");
          </script>
          <?php
        }
    }catch (Exception $e) {
        print "Error: ".$e->getMessage();
    }
    }

    // eliminar cliente
    public function deleteclienteAction(){
    try {
        $this->_helper->layout()->disablelayout();
        $clienteid=$this->_getParam("clienteid");
	    $pk  =   array(                        
                     'clienteid'   =>$clienteid,
                        );
	    print_r($pk);
        $delcliente = new Admin_Model_DbTable_Cliente();
        $delcliente->_deletecliente($pk); 
            $this->_helper->redirector('listarcliente','tbmaestracliente','admin');    
        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        }
    }

}
