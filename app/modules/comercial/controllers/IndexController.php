<?php
class Comercial_IndexController extends Zend_Controller_Action {

    public function init() {
    	$options = array(
            'layout' => 'layout',
        );
        Zend_Layout::startMvc($options);

    }
    
    public function indexAction() {
         $this->_helper->redirector('index','index','admin');
            
    }

    public function listarAction() {
        $listacliente = new Admin_Model_DbTable_Cliente();
        $lista_cliente=$listacliente->_getClienteAll();
        $this->view->lista_cliente = $lista_cliente; 
    }

    
    
}
