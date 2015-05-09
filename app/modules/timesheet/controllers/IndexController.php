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
        $ano=date("Y");
        $semana=date("W");
        /*echo "semana nro: ".(date("W"));
        echo "dia del mes nro: ".(date("j"));
        echo "# dias de la semana".(date("N"));*/
        $dias = array('lunes', 'martes', 'miercoles', 
        'jueves', 'viernes', 'sabado','domingo');
        $enero = mktime(1,1,1,1,1,$ano); 
        //$mos = (11-date('w',1))%7-3; 
        $mos = (11-date('w',$enero))%7-3;
        $inicios = strtotime(($semana-1) . ' weeks '.$mos.' days', $enero); 
        for ($x=0; $x<=6; $x++) {
        $dias[] = date('d/m/Y', strtotime("+ $x day", $inicios));
        $dia[] = date('w', strtotime("+ $x day", $inicios));
        }
        $this->view->diassemana=$dias;
        $this->view->semanalabor=$semana;


        

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
        $fecha_mostrar = date("d", strtotime($fecha_inicio));
        $this->view->fecha = $fecha_inicio;
        $this->view->fecha_mostrar = $fecha_mostrar;
        $uid = $this->sesion->uid;
        $dni = $this->sesion->dni;
        //$where["uid"] = $uid;
        //$tareo = new Admin_Model_DbTable_Tareo();
        $tareo_persona = new Admin_Model_DbTable_Tareopersona();
           $ano=date("Y");
        $semana=date("W");
        $diaactual=date("Y-m-d");

        $datos_tareopersona=$tareo_persona->_getTareoxPersonaxSemana($uid,$dni,$semana);

        //$data_tareo = $tareo->_getTareoXUid($where);
        $this->view->actividades = $datos_tareopersona;
        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        } 
    }


    public function guardartareopersonaAction(){
        try {
        $this->_helper->layout()->disableLayout();
        $uid = $this->sesion->uid;
        $dni = $this->sesion->dni;
        $cargo = 'EQ-CIVIL';
        $areaid='02';

        $data['proyectoid']=$proyectoid = $this->_getParam('proyectoid');
        $data['codigo_prop_proy']=$codigo_prop_proy = $this->_getParam('codigo');
        $data['categoriaid']=$categoriaid = $this->_getParam('categoriaid');
        $data['actividadid']=$actividadid = $this->_getParam('actividadid');
        $data['revision']=$revision = $this->_getParam('revision');
        //$data['propuestaid']=$propuestaid = $this->_getParam('propuestaid');
        $data['codigo_actividad']=$codigo_actividad = $this->_getParam('codigo_actividad');
        $data['actividad_padre']=$actividad_padre = $this->_getParam('actividad_padre');
        $data['h_propuesta']=$h_propuesta = $this->_getParam('h_propuesta');
        $data['uid']=$uid = $uid;
        $data['asignado']= $dni;
        $data['dni']=$dni = $dni;
        $data['h_propuesta']=$h_propuesta = $this->_getParam('h_propuesta');

        $ano=date("Y");
        $semana=date("W");
        $diaactual=date("Y-m-d");
        /*echo "semana nro: ".(date("W"));
        echo "dia del mes nro: ".(date("j"));
        echo "# dias de la semana".(date("N"));*/
        $dias = array('lunes', 'martes', 'miercoles', 
        'jueves', 'viernes', 'sabado','domingo');
        $enero = mktime(1,1,1,1,1,$ano); 
        //$mos = (11-date('w',1))%7-3; 
        $mos = (11-date('w',$enero))%7-3;
        $inicios = strtotime(($semana-1) . ' weeks '.$mos.' days', $enero); 
        for ($x=0; $x<=6; $x++) {
        $dias[] = date('d/m/Y', strtotime("+ $x day", $inicios));
        $dia[] = date('w', strtotime("+ $x day", $inicios));
        }
        $this->view->diassemana=$dias;
        $this->view->semanalabor=$semana;

        $data['semanaid']=$semana;
        $data['fecha_tarea']=$diaactual;
        $data['fecha_creacion']=$diaactual;
        $data['tipo_actividad']='P';
        $data['cargo']=$cargo;
        $data['areaid']=$areaid;


        $tareopersona = new Admin_Model_DbTable_Tareopersona();
        $data_tareopersona = $tareopersona->_save($data);

        //$this->view->tareas = $data_tareas;
        $this->view->proyectoid = $proyectoid;
        $this->view->codigo_prop_proy = $codigo_prop_proy;
        $this->view->categoriaid = $categoriaid;
        $this->view->actividadid = $actividadid;

        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        }
    }  

    


}
