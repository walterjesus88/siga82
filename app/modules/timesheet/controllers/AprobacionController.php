<?php

class Timesheet_AprobacionController extends Zend_Controller_Action {

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

    public function historiconivel4Action(){
        try {
                // $this->_helper->layout()->disableLayout();       
                $uid = $this->sesion->uid;
                $dni = $this->sesion->dni;     
                $fecha = date("Y-m-d");
                $semanaid=date('W', strtotime($fecha)); 
                $this->view->semanaid= $semanaid;    
                $areaid=$this->sesion->personal->ucatareaid;   
                $cargo=$this->sesion->personal->ucatcargo;   
                if ($cargo=='JEFE') 
                {
                    $equipo = new Admin_Model_DbTable_Equipo();
                    $equipo_aprobacion = $equipo->_getListarEquipoArea($areaid);
                    $this->view->equipos_horas_aprobar= $equipo_aprobacion;   
                }        

         } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        } 

    }

};
