<?php
class Comercial_IndexController extends Zend_Controller_Action {

    public function init() {
    	$options = array(
            'layout' => 'layout',
        );
        Zend_Layout::startMvc($options);

    }
    
    public function indexAction() {
        // $this->_helper->redirector('index','index','admin');
            
    }

    public function listarAction() {
        $listacliente = new Admin_Model_DbTable_Cliente();
        $lista_cliente=$listacliente->_getClienteAllOrdenado();
        $this->view->lista_cliente = $lista_cliente; 
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
        $buscar_propuesta=$this->_getParam('cliente');
        $buscar_propuesta=strtolower($buscar_propuesta);
        $buscapropuesta = new Admin_Model_DbTable_Propuesta();
        $buscar=$buscapropuesta->_buscarPropuesta($buscar_propuesta);
        $this->view->lista_buscar = $buscar; 
       
    }  
}
