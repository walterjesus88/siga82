<?php
class Comercial_IndexController extends Zend_Controller_Action {

    public function init() {
    	$options = array(
            'layout' => 'inicio',
        );
        Zend_Layout::startMvc($options);

    }
    
    public function indexAction() {
        // $this->_helper->redirector('index','index','admin');
            
    }

    public function listarAction() {
        $listacliente = new Admin_Model_DbTable_Cliente();
        $lista_cliente=$listacliente->_getClientexTipo('cliente');
        $this->view->lista_cliente = $lista_cliente; 

        $lista_laboratorio=$listacliente->_getClientexTipo('cliente laboratorio');
        $lista_potencial=$listacliente->_getClientexTipo('potencial cliente');
        $lista_contratista=$listacliente->_getClientexTipo('contratistas');
        $lista_socio=$listacliente->_getClientexTipoxSocio();

        $this->view->lista_laboratorio = $lista_laboratorio; 
        $this->view->lista_potencial = $lista_potencial; 
        $this->view->lista_contratista = $lista_contratista; 
        $this->view->lista_socio = $lista_socio; 
       

    }

    public function uploadAction(){
    try {
        $ruc=$this->_getParam('ruc');
        $this->view->ruc = $ruc; 
      
    } catch (Exception $e) {
      print "Error: ".$e->getMessage();
    }
    }

   public function buscarAction() {
        $this->_helper->layout()->disableLayout();
        $buscar_cliente=$this->_getParam('cliente');
        $buscar_cliente=strtolower($buscar_cliente);
        $buscapropuesta = new Admin_Model_DbTable_Cliente();
        $buscar=$buscapropuesta->_buscarCliente($buscar_cliente);
        $this->view->lista_cliente = $buscar; 
    }  

    public function contactoAction() {
        $dbcontacto = new Admin_Model_DbTable_Contacto();
        $this->view->lista_contactos = $dbcontacto-> _getConstactoxTipo('cliente'); 
        $this->view->lista_laboratorio = $dbcontacto-> _getConstactoxTipo('laboratorio'); 




        
    }  

     public function direccionAction() {
        $this->_helper->layout()->disableLayout();
        $cliente=$this->_getParam('clienteid');
        $buscapropuesta = new Admin_Model_DbTable_Cliente();
        $buscar=$buscapropuesta->_getClientexIndice($cliente);
        if($buscar){
        print_r($buscar[0]['direccion']);}

        
    }  


    public function guardarcontactoAction() {
  $this->_helper->layout()->disableLayout();
        $dbcontacto = new Admin_Model_DbTable_Contacto();
        $formdata['clienteid']=$clienteid = $this->_getParam('clienteid');
        $formdata['relacion']=$relacion = $this->_getParam('relacion');

        $formdata['areaid']=$areaid = $this->_getParam('areaid');
        $formdata['numero']=$numero = $this->_getParam('numero');
        $nombre_contacto = $this->_getParam('nombre_contacto');
        $direccion = $this->_getParam('direccion');
        $correo = $this->_getParam('correo');
         $anexo = $this->_getParam('anexo');
          $telefono = $this->_getParam('telefono');

        $formdata['direccion'] = str_replace("_"," ",$direccion);
        $formdata['nombre'] = str_replace("_"," ",$nombre_contacto);
        $formdata['correo'] = $correo;
        $formdata['anexo'] = $anexo;
        $formdata['telefono'] = $telefono;

        $formdata['contactoid'] = $formdata['clienteid']."-".$formdata['areaid']."-".$formdata['numero'];
        
       if($dbcontacto->_save($formdata))
        {
            
            ?>
                <script>                  
                    alert("Contacto Guardado");
                    document.location.href="/comercial/index/contacto";                                                 
                </script>
                <?php
        }

        
    }


    public function eliminarcontactoAction() {
  $this->_helper->layout()->disableLayout();
        $dbcontacto = new Admin_Model_DbTable_Contacto();
        $contactoid = $this->_getParam('contactoid');
        $clienteid = $this->_getParam('clienteid');
        $areaid = $this->_getParam('areaid');

                  $pk  =   array(                        
                        'contactoid'   =>$contactoid,
                        'clienteid'   =>$clienteid,
                        'areaid'   =>$areaid,
                                                 
                        );

        
      
            $dbcontacto->_delete($pk); 

         ?>
                <script>                  
                   
                    document.location.href="/comercial/index/contacto";                                                 
                </script>
                <?php

        
    } 



}
