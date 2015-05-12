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
        /*IMPRESION INTERVALO FECHAS */
        $ano=date("Y");
        $semana=date("W");
        $dias = array('lunes', 'martes', 'miercoles', 
        'jueves', 'viernes', 'sabado','domingo');
        $enero = mktime(1,1,1,1,1,$ano); 
        $mos = (11-date('w',$enero))%7-3;
        $inicios = strtotime(($semana-1) . ' weeks '.$mos.' days', $enero); 
        for ($x=0; $x<=6; $x++) {
        $dias[] = date('d/m/Y', strtotime("+ $x day", $inicios));
        $dia[] = date('w', strtotime("+ $x day", $inicios));
        }
        $this->view->diassemana=$dias;
        $this->view->semanalabor=$semana;
        /*IMPRESION INTERVALO FECHAS */
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
        /*actividades por categoria habilitadas para el usuario*/
        $actividades_padre=$actividad->_getActividadesPadresXProyectoXCategoria($proyectoid,$categoriaid,$codigo_prop_proy);
        $i=0;
        foreach ($actividades_padre as $act_padre) {
        $dato_padre=$actividad->_getActividadesxActividadid($proyectoid,$codigo_prop_proy,$act_padre['padre']);
        $array[$i]=$dato_padre[0];
        $i++;
        }
        $this->view->actividades = $array;
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
        $codigo_act_padres_hijas = $tareo->_getActividadesHijasxActividadesPadresXCategoria($proyectoid,$codigo_prop_proy,$propuestaid,$revision,$actividadid,$categoriaid);
        // tareo con actividades para listar actividades por categoria
        $j=0;    
        foreach ($codigo_act_padres_hijas as $act_padre) {
        $actividadespadres = explode(".",$act_padre['actividad_padre']);
            if (count($actividadespadres)=='1')
            {
                $dato_tarea=$tareo->_getTareasxActividadPadrexCategoria($proyectoid,$codigo_prop_proy,$propuestaid,$revision,$act_padre['actividad_padre'],$categoriaid);
            }
            if (count($actividadespadres)=='2')
            {
                $dato_padre=$tareo->_getActividadesxActividadid($proyectoid,$codigo_prop_proy,$act_padre['actividad_padre']);
                $array[$j]=$dato_padre[0];
                $j++;
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
        $dato_tarea=$tareo->_getTareasxActividadPadrexCategoria($proyectoid,$codigo_prop_proy,$propuestaid,$revision,$actividadid,$categoriaid);
        //$this->view->tareas = $data_tareas;
        $this->view->tareas = $dato_tarea;
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
        $this->view->uid = $uid;
        $this->view->dni = $dni;
        $dbucat= new Admin_Model_DbTable_Usuariocategoria();
        $datosucat = $dbucat->_getUsuarioxPersona($uid,$dni);
        $cargo = $datosucat[0]['cargo'];
        $this->view->cargo=$cargo;

        $areaid = $datosucat[0]['areaid']; 
        $this->view->areaid=$areaid;

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
        $dbucat= new Admin_Model_DbTable_Usuariocategoria();
        $datosucat = $dbucat->_getUsuarioxPersona($uid,$dni);
        $cargo = $datosucat[0]['cargo'];
        $areaid = $datosucat[0]['areaid']; 
        
        $data['proyectoid']=$proyectoid = $this->_getParam('proyectoid');
        $data['codigo_prop_proy']=$codigo_prop_proy = $this->_getParam('codigo');
        $data['categoriaid']=$categoriaid = $this->_getParam('categoriaid');
        $data['actividadid']=$actividadid = $this->_getParam('actividadid');
        $data['revision']=$revision = $this->_getParam('revision');
        //$data['propuestaid']=$propuestaid = $this->_getParam('propuestaid');
        $data['codigo_actividad']=$codigo_actividad = $this->_getParam('codigo_actividad');
        $data['actividad_padre']=$actividad_padre = $this->_getParam('actividad_padre');
        $data['h_propuesta']=$h_propuesta = $this->_getParam('h_propuesta');
        $data['uid']=$uid;
        $data['asignado']= $dni;
        $data['dni']=$dni;
        $data['h_propuesta']=$h_propuesta = $this->_getParam('h_propuesta');
        $data['etapa']='INICIO';
        $semana=date("W");
        $diaactual=date("Y-m-d");
   
        $data['semanaid']=$semana;
        $data['fecha_tarea']=$diaactual;
        $data['fecha_creacion']=$diaactual;
        $data['fecha_planificacion']=$diaactual;
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

    public function updatetareorealAction(){
        try {
        $this->_helper->layout()->disableLayout();
        $uid = $this->sesion->uid;
        $dni = $this->sesion->dni;
        $dbucat= new Admin_Model_DbTable_Usuariocategoria();
        $datosucat = $dbucat->_getUsuarioxPersona($uid,$dni);
        //print_r($datosucat);
        $cargo = $datosucat[0]['cargo'];
        $areaid = $datosucat[0]['areaid']; 
        $diaactual=date("Y-m-d");
        $semana=date("W");
  

        $data['proyectoid']=$proyectoid = $this->_getParam('proyectoid');
        $data['codigo_prop_proy']=$codigo_prop_proy = $this->_getParam('codigo_prop_proy');
        $data['categoriaid']=$categoriaid = $this->_getParam('categoriaid');
        $data['actividadid']=$actividadid = $this->_getParam('actividadid');
        $data['revision']=$revision = $this->_getParam('revision');
        $data['codigo_actividad']=$codigo_actividad = $this->_getParam('codigo_actividad');
        $data['actividad_padre']=$actividad_padre = $this->_getParam('actividad_padre');
        $data['h_propuesta']=$h_propuesta = $this->_getParam('h_propuesta');
        $data['uid']=$uid;
        $data['asignado']= $dni;
        $data['dni']=$dni;
        $data['h_propuesta']=$h_propuesta = $this->_getParam('h_propuesta');
        $data['etapa']='EJECUCION';
        $data['h_real']=$h_real= $this->_getParam('horareal');
        $datos['h_real']=$h_real= $this->_getParam('horareal');
        $data['fecha_tarea']=$fecha_tarea= $this->_getParam('fecha_tarea');
        $cargo= $this->_getParam('cargo');
        $data['fecha_modificacion']=$diaactual;
        $semanaid=$this->_getParam('semanaid');
        
        $data['semanaid']=$semana;
        $data['fecha_planificacion']=$fecha_tarea;
        $data['tipo_actividad']='P';
         $data['fecha_creacion']=$fecha_tarea;
        $data['cargo']=$cargo;
        $data['areaid']=$areaid;

        //$updatetareopersona = new Admin_Model_DbTable_Tareopersona();    
        /*$str="codigo_prop_proy='$codigo_prop_proy' and proyectoid='$proyectoid' and 
        categoriaid='$categoriaid' and actividadid='$actividadid' and 
        revision='$revision' and codigo_actividad='$codigo_actividad'
        and actividad_padre='$actividad_padre' and cargo='$cargo'
        and semanaid='$semanaid' and areaid='$areaid'     ";*/
        //$update=$updatetareopersona -> _update($data,$str);
        $tareopersona = new Admin_Model_DbTable_Tareopersona();
        $data_tareopersona = $tareopersona->_save($data);


        if ($data_tareopersona){
            echo "guardao";
        }else
        {
            echo("no gutradoo") ;
            $str="codigo_prop_proy='$codigo_prop_proy' and proyectoid='$proyectoid' and 
        categoriaid='$categoriaid' and actividadid='$actividadid' and 
        revision='$revision' and codigo_actividad='$codigo_actividad'
        and actividad_padre='$actividad_padre' and cargo='$cargo'
        and semanaid='$semanaid' and areaid='$areaid'     ";
        $update=$tareopersona -> _update($datos,$str);
        }
       
        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        }
    }  

    


}