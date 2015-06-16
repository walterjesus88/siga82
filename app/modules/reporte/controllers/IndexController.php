<?php

class Reporte_IndexController extends Zend_Controller_Action {

    public function init() {
        $sesion  = Zend_Auth::getInstance();
        if(!$sesion->hasIdentity()) {
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
        
    }

    public function tareopersonaAction() {
        $this->_helper->layout()->disableLayout();
        $tareopersona = new Admin_Model_DbTable_Tareopersona();
        $todos_tareopersona = $tareopersona->_getTareopersonall();
        print_r(json_encode($todos_tareopersona));        
    }

}