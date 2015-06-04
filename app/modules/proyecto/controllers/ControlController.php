<?php

class Proyecto_ControlController extends Zend_Controller_Action {

    public function init() {
    	$sesion  = Zend_Auth::getInstance();
        if(!$sesion->hasIdentity() ){
            $this->_helper->redirector('index',"index",'default');
        }
        $login = $sesion->getStorage()->read();
        $this->sesion = $login; 
        $options = array(
            'layout' => 'layout',
        );
        Zend_Layout::startMvc($options);
    
    }
    
    public function indexAction() {
        try {
            $uid = $this->sesion->uid;
            $dni = $this->sesion->dni;
            $listaproyecto = new Admin_Model_DbTable_Proyecto();
            //$lista=$listaproyecto->_getProyectoAll();
            $lista=$listaproyecto->_getProyectosxGerente($uid);
            $this->view->listaproyecto = $lista;
        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        }
    }

}