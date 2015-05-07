<?php

class Timesheet_IndexController extends Zend_Controller_Action {

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
        
    }

    public function calendarAction(){
        
    }

    public function registroAction(){
        $this->_helper->layout()->disableLayout();
        $fecha_inicio = $this->_getParam('fecha');
        $fecha_inicio = date("d-m-Y", strtotime($fecha_inicio));
        $this->view->fecha = $fecha_inicio;
        $uid = $this->sesion->uid;
        $dni = $this->sesion->dni;
        $where["uid"] = $uid;
        $tareo = new Admin_Model_DbTable_Tareo();
        $data_tareo = $tareo->_getTareoXUid($where);
        $this->view->actividades = $data_tareo;
    }
}
