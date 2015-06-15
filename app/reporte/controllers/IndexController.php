<?php

class Expense_IndexController extends Zend_Controller_Action {

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
        print_r($this->sesion);
    }

    public function reporteAction() {
        $this->view->data = 'jaja';
    }
}