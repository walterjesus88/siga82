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
        try {
        $uid = $this->sesion->uid;
        $dni = $this->sesion->dni;
        $equipo = new Admin_Model_DbTable_Equipo();
        $data_equipo = $equipo->_getProyectosXuidXEstado($uid,'A');
        $this->view->equipo = $data_equipo;
        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        } 
    }

    public function actividadesAction(){
        try {
        $this->_helper->layout()->disableLayout();
        $uid = $this->sesion->uid;
        $dni = $this->sesion->dni;
        $proyectoid = $this->_getParam('proyectoid');
        $codigo_prop_proy = $this->_getParam('codigo_prop_proy');
        $categoriaid = $this->_getParam('categoriaid');
        $tareo = new Admin_Model_DbTable_Tareo();
        $data_tareo = $tareo->_getTareoXUid($where);
        $this->view->actividades = $data_tareo;
        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        } 
    }    

    public function registroAction(){
        try {
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
        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        } 
    }
}
