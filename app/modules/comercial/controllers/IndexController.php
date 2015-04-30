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
        $buscar_cliente=$this->_getParam('cliente');
        $buscar_cliente=strtolower($buscar_cliente);
        $buscapropuesta = new Admin_Model_DbTable_Cliente();
        $buscar=$buscapropuesta->_buscarCliente($buscar_cliente);
        $this->view->lista_cliente = $buscar; 
    }  
}
