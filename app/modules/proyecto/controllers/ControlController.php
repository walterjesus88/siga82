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
            'layout' => 'inicio',
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

    public function historicohojatiempoAction() {
        try {
            $uid = $this->sesion->uid;
            $dni = $this->sesion->dni;     
            $this->view->uid_gerente=$uid;
            $tabla_planificacion = new Admin_Model_DbTable_Planificacion();
            $lista_empleados_aprobar = $tabla_planificacion->_getListarEquipoxAprobacionxGerenteProyecto($uid,$dni);
            $this->view->lista_empleados_aprobar= $lista_empleados_aprobar;

            $tabla_historial_aprobaciones= new Admin_Model_DbTable_Historialaprobaciones();       
            $lista_empleados_aprobar=$tabla_historial_aprobaciones-> _getListarHistoricoxAprobador($uid,$dni);
            $this->view->lista_empleados_aprobar= $lista_empleados_aprobar;   
        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        }
    }    
}