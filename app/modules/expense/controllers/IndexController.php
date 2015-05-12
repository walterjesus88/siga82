<?php

class Expense_IndexController extends Zend_Controller_Action {

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
    
    public function registroAction(){
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

    public function disciplinaAction(){
    try {
        $this->_helper->layout()->disableLayout();
        $uid = $this->sesion->uid;
        $dni = $this->sesion->dni;
        $proyectoid = $this->_getParam('proyectoid');
        $codigo_prop_proy = $this->_getParam('codigo_prop_proy');
        $categoriaid = $this->_getParam('categoriaid');
        $actividad = new Admin_Model_DbTable_Actividad();
        $data_actividad = $actividad->_getActividadesPadresXproyectoXcodigo($proyectoid, $codigo_prop_proy);
        $actividades_padre=$actividad->_getActividadesPadresXProyectoXCategoria($proyectoid,$categoriaid,$codigo_prop_proy);
        $i=0;
        $array = [];
        foreach ($actividades_padre as $act_padre) {
            $dato_padre=$actividad->_getActividadesxActividadid($proyectoid,$codigo_prop_proy,$act_padre['padre']);
            if ($dato_padre[0]['isgasto'] == 'S') {
                $array[$i]=$dato_padre[0];
                $i++;
            }
        }
        if ($i != 0) {
            $this->view->actividades = $array;
        } else {
            $equipo = new Admin_Model_DbTable_Equipo();
            $data_equipo = $equipo->_getProyectosXuidXEstado($uid,'A');
            $this->view->equipo = $data_equipo;
        }
        $this->view->proyectoid = $proyectoid;
        $this->view->codigo_prop_proy = $codigo_prop_proy;
        $this->view->categoriaid = $categoriaid;
        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        } 
    }

    public function actividadespadresAction(){
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
        $codigo_act_padres_hijas = $tareo->_getActividadesHijasxActividadesPadresXCategoria($proyectoid,$codigo_prop_proy,$propuestaid,$revision,$actividadid,$categoriaid);
        $j=0;    
        foreach ($codigo_act_padres_hijas as $act_padre) {
            $actividadespadres = explode(".",$act_padre['actividad_padre']);
            if (count($actividadespadres)=='1'){
                $dato_tarea=$tareo->_getTareasxActividadPadrexCategoria($proyectoid,$codigo_prop_proy,$propuestaid,$revision,$act_padre['actividad_padre'],$categoriaid);
            }
            if (count($actividadespadres)=='2'){
                $dato_padre=$tareo->_getActividadesxActividadid($proyectoid,$codigo_prop_proy,$act_padre['actividad_padre']);
                if ($dato_padre[0]['isgasto'] == 'S') {
                    $array[$j]=$dato_padre[0];
                    $j++;
                }
            }
        }
        $this->view->datos_tarea = $dato_tarea;
        $this->view->datos_disciplina = $array;
        $this->view->actividades_hijos = $data_tareo_hijos;
        $this->view->proyectoid = $proyectoid;
        $this->view->codigo_prop_proy = $codigo_prop_proy;
        $this->view->categoriaid = $categoriaid;
        $this->view->actividadid = $actividadid;
        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        }
    }

    public function tareasAction(){
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
        $dato_tarea=$tareo->_getTareasxActividadPadrexCategoria($proyectoid,$codigo_prop_proy,$propuestaid,$revision,$actividadid,$categoriaid);
        $this->view->tareas = $dato_tarea;
        $this->view->proyectoid = $proyectoid;
        $this->view->codigo_prop_proy = $codigo_prop_proy;
        $this->view->categoriaid = $categoriaid;
        $this->view->actividadid = $actividadid;
        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        }
    }

    public function ingresoAction(){
    try {
        $this->_helper->layout()->disableLayout();
        $uid = $this->sesion->uid;
        $dni = $this->sesion->dni;
        $fecha = $this->_getParam('fecha');
        $where ['fecha_gasto'] = $fecha;
        $gasto = new Admin_Model_DbTable_Gastopersona();
        $data_gasto = $gasto->_getgastoXfecha($where);
        $this->view->gasto = $data_gasto;
        $this->view->fecha = $fecha;
        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        }
    }

    public function guardargastopersonaAction(){
        try {
            $this->_helper->layout()->disableLayout();
            $uid = $this->sesion->uid;
            $dni = $this->sesion->dni;
            $proyectoid = $this->_getParam('proyectoid');
            $codigo_prop_proy = $this->_getParam('codigo-prop-proy');
            $categoriaid = $this->_getParam('categoriaid');
            $areaid = $this->_getParam('areaid');
            $cargo = $this->_getParam('cargo');
            $revision = $this->_getParam('revision');
            $data ['proyectoid'] = $proyectoid;
            $data ['codigo_prop_proy'] = $codigo_prop_proy;
            $data ['categoriaid'] = $categoriaid;
            $data ['areaid'] = $areaid;
            $data ['uid'] = $uid;
            $data ['dni'] = $dni;
            $data ['cargo'] = $cargo;
            $data ['uid_ingreso'] = $uid;
            $data ['asignado'] = $uid;
            $data ['fecha_gasto'] = date("Y-m-d"); ;
            $data ['gastoid'] = 1;
            $data ['revision'] = $revision;
            $gasto = new Admin_Model_DbTable_Gastopersona();
            $gasto->_save($data);
        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        }
    }
}
