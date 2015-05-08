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
        $actividad = new Admin_Model_DbTable_Actividad();
        $data_actividad = $actividad->_getActividadesPadresXproyectoXcodigo($proyectoid, $codigo_prop_proy);
        $this->view->actividades = $data_actividad;
        $this->view->proyectoid = $proyectoid;
        $this->view->codigo_prop_proy = $codigo_prop_proy;
        $this->view->categoriaid = $categoriaid;
        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        } 
    }    


    public function actividadeshijosAction(){
        try {
        $this->_helper->layout()->disableLayout();
        $uid = $this->sesion->uid;
        $dni = $this->sesion->dni;
        $proyectoid = $this->_getParam('proyectoid');
        $codigo_prop_proy = $this->_getParam('codigo_prop_proy');
        $categoriaid = $this->_getParam('categoriaid');
        $actividadid = $this->_getParam('actividadid');
        $revision = $this->_getParam('revision');
        $propuestaid = $this->_getParam('propuestaid');
        $tareo = new Admin_Model_DbTable_Actividad();
        $data_tareo_hijos = $tareo->_getActividadesHijas($proyectoid,$codigo_prop_proy,$propuestaid,$revision,$actividadid);
        $this->view->actividades_hijos = $data_tareo_hijos;
        $this->view->proyectoid = $proyectoid;
        $this->view->codigo_prop_proy = $codigo_prop_proy;
        $this->view->categoriaid = $categoriaid;
        $this->view->actividadid = $actividadid;
        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        }
    }  

    public function actividadestareaAction(){
        try {
        $this->_helper->layout()->disableLayout();
        $uid = $this->sesion->uid;
        $dni = $this->sesion->dni;
        $proyectoid = $this->_getParam('proyectoid');
        $codigo_prop_proy = $this->_getParam('codigo_prop_proy');
        $categoriaid = $this->_getParam('categoriaid');
        $actividadid = $this->_getParam('actividadid');
        $revision = $this->_getParam('revision');
        $propuestaid = $this->_getParam('propuestaid');
        $tareo = new Admin_Model_DbTable_Actividad();
        $data_tareas = $tareo->_getActividadesHijas($proyectoid,$codigo_prop_proy,$propuestaid,$revision,$actividadid);
        $this->view->tareas = $data_tareas;
        $this->view->proyectoid = $proyectoid;
        $this->view->codigo_prop_proy = $codigo_prop_proy;
        $this->view->categoriaid = $categoriaid;
        $this->view->actividadid = $actividadid;
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
