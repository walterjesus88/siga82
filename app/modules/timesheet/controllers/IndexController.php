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
        $data_clientes = $equipo ->_getClienteXuidXEstado($uid,'A');
        $this->view->datoscliente = $data_clientes;
        $this->view->equipo = $data_equipo;

       
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

         } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        } 

    }

    public function proyectosAction(){
        try {
        $this->_helper->layout()->disableLayout();
        $uid = $this->sesion->uid;
        $dni = $this->sesion->dni;
        $clienteid = $this->_getParam('clienteid');
        $unidadid = $this->_getParam('unidadid');

        $equipo = new Admin_Model_DbTable_Equipo();
        $data_equipo = $equipo->_getProyectosxUidXEstadoxCliente($uid,'A',$clienteid,$unidadid);
             
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
        /*actividades por categoria habilitadas para el usuario*/
        $actividades_padre=$actividad->_getActividadesPadresXProyectoXCategoria($proyectoid,$categoriaid,$codigo_prop_proy);
        $i=0;
        //print_r($actividades_padre);
        foreach ($actividades_padre as $act_padre) {
        $dato_padre=$actividad->_getActividadesxActividadid($proyectoid,$codigo_prop_proy,$act_padre['padre']);
        $array[$i]=$dato_padre[0];
        $i++;
        }
        //print_r($array);
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
        $uid = $this->sesion->uid;
        $dni = $this->sesion->dni;
        $semana=date("W");
        $this->view->uid = $uid;
        $this->view->dni = $dni;
        $this->view->semanaid = $semana;

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
        
        $semana=date("W");
        $this->view->uid = $uid;
        $this->view->dni = $dni;
        $this->view->semanaid = $semana;

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
        $fecha_inicio_mod = date("Y-m-d", strtotime($fecha_inicio));
        $fecha_inicio = date("d-m-Y", strtotime($fecha_inicio));
        $fecha_mostrar = date("d", strtotime($fecha_inicio));

        $this->view->fecha = $fecha_inicio;
        $this->view->fecha_mostrar = $fecha_mostrar;
        $uid = $this->sesion->uid;
        $dni = $this->sesion->dni;
        $this->view->fecha_inicio_mod = $fecha_inicio_mod;
        $this->view->uid = $uid;
        $this->view->dni = $dni;
        $dbucat= new Admin_Model_DbTable_Usuariocategoria();
        $datosucat = $dbucat->_getUsuarioxPersona($uid,$dni);
        $cargo = $datosucat[0]['cargo'];
        $this->view->cargo=$cargo;
        $areaid = $datosucat[0]['areaid']; 
        $this->view->areaid=$areaid;
        $tareo_persona = new Admin_Model_DbTable_Tareopersona();
        $semana=date('W', strtotime($fecha_inicio_mod)); 
        $this->view->semana = $semana;
        $datos_tareopersona=$tareo_persona->_getTareoxPersonaxSemana($uid,$dni,$semana);
        $datos_tareopersona_NB=$tareo_persona->_getTareoxPersonaxSemanaxNB($uid,$dni,$semana);
        //$data_tareo = $tareo->_getTareoXUid($where);
        $this->view->actividades= $datos_tareopersona;
        $this->view->actividades_NB = $datos_tareopersona_NB;
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
        $data['estado']='A';
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
        $data['actividad_generalid']=$actividad_generalid = $this->_getParam('actividad_generalid');

        $data['uid']=$uid;
        $data['asignado']= $dni;
        $data['estado']= 'A';
        $data['dni']=$dni;
        $data['h_propuesta']=$h_propuesta = $this->_getParam('h_propuesta');
        
        $etapa= $this->_getParam('etapa');
        $resultado = str_replace("INICIO", "EJECUCION", $etapa);



        $data['etapa']=$resultado;
        $data['h_real']=$h_real= $this->_getParam('horareal');
        $data['fecha_tarea']=$fecha_tarea= $this->_getParam('fecha_tarea');
        $cargo= $this->_getParam('cargo');
        $data['fecha_modificacion']=$diaactual;
        $semanaid=$this->_getParam('semanaid');
        $data['semanaid']=$semana;
        $data['fecha_planificacion']=$fecha_tarea;
        $data['tipo_actividad']=$tipo_actividad= $this->_getParam('tipo_actividad');
        $data['fecha_creacion']=$fecha_tarea;
        $data['cargo']=$cargo;
        $data['areaid']=$areaid;
        //datos para ctualizar
        
        $datos['h_real']=$h_real= $this->_getParam('horareal');
        $datos['fecha_modificacion']=$diaactual;
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
        ?>
          <script>                  
          //  alert("Se guardo satisfactoriamente");
          </script>
        <?php
        }else
        {
        ?>
          <script>                  
            //alert("Se actualizo satisfactoriamente");
          </script>
        <?php
            $str="codigo_prop_proy='$codigo_prop_proy' and proyectoid='$proyectoid' and 
            categoriaid='$categoriaid' and actividadid='$actividadid' and 
            revision='$revision' and codigo_actividad='$codigo_actividad'
            and actividad_padre='$actividad_padre' and cargo='$cargo'
            and semanaid='$semanaid' and areaid='$areaid' and fecha_tarea='$fecha_tarea' 
            and fecha_planificacion='$fecha_tarea' and etapa='$resultado' and tipo_actividad='$tipo_actividad' 
            and  estado='A' 
            ";
          //  echo $str;
            $update=$tareopersona -> _update($datos,$str);
        }
       
        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        }
    }




    public function actividadgeneralAction(){
        try {
        $this->_helper->layout()->disableLayout();
        $uid = $this->sesion->uid;
        $dni = $this->sesion->dni;
        $areaid='90';
        $actividad_generales = new Admin_Model_DbTable_Actividadgeneral();
        $data_generales = $actividad_generales->_getActividadgeneralxArea($areaid);
        $this->view->lista = $data_generales;

        $data['proyectoid']=$proyectoid = $this->_getParam('proyectoid');
        $this->view->proyectoid = $proyectoid;        
        $data['codigo_prop_proy']=$codigo_prop_proy = $this->_getParam('codigo');
        $this->view->codigo_prop_proy = $codigo_prop_proy;     
        $data['categoriaid']=$categoriaid = $this->_getParam('categoriaid');
        $this->view->categoriaid = $categoriaid;     
        $data['actividadid']=$actividadid = $this->_getParam('actividadid');
        $this->view->actividadid = $actividadid;     
        $data['revision']=$revision = $this->_getParam('revision');
        $this->view->revision = $revision;     
        $data['codigo_actividad']=$codigo_actividad = $this->_getParam('codigo_actividad');
        $this->view->codigo_actividad = $codigo_actividad;     
        $data['actividad_padre']=$actividad_padre = $this->_getParam('actividad_padre');
        $this->view->actividad_padre = $actividad_padre;     
        $data['h_propuesta']=$h_propuesta = $this->_getParam('h_propuesta');
        $this->view->h_propuesta = $h_propuesta;     
        $data['uid']=$uid;
        $data['asignado']= $dni;
        $data['dni']=$dni;
        $data['etapa']='EJECUCION';
        $data['fecha_tarea']=$fecha_tarea= $this->_getParam('fecha_tarea');
        $this->view->fecha_tarea = $fecha_tarea;     
        $cargo= $this->_getParam('cargo');
        $data['fecha_modificacion']=$fecha_tarea;
     
        $data['semanaid']=$semanaid= $this->_getParam('semanaid');
        $this->view->semanaid = $semanaid;     
        $data['fecha_planificacion']=$fecha_tarea;
        $data['tipo_actividad']=$tipo_actividad= $this->_getParam('tipo_actividad');
        $this->view->tipo_actividad = $tipo_actividad;     
        $data['fecha_creacion']=$fecha_tarea;
        $data['cargo']=$cargo;
        $data['areaid']=$areaid;



        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        } 

        
    }


     public function guardaractividadgeneralAction(){
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
        $data['proyectoid']=$proyectoid = $this->_getParam('proyectoid');
        $data['codigo_prop_proy']=$codigo_prop_proy = $this->_getParam('codigo');
        $data['categoriaid']=$categoriaid = $this->_getParam('categoriaid');
        $data['revision']=$revision = $this->_getParam('revision');
        $data['actividadid']=$actividadid = $this->_getParam('actividadid');
        $data['codigo_actividad']=$codigo_actividad = $this->_getParam('codigo_actividad');
        $data['actividad_padre']=$actividad_padre = $this->_getParam('actividad_padre');
        $data['actividad_generalid']=$actividad_generalid = $this->_getParam('actividad_generalid');
        $data['area_generalid']=$area_generalid = $this->_getParam('area_generalid');
        $data['uid']=$uid;
        $data['asignado']= $dni;
        $data['dni']=$dni;
        $data['h_propuesta']="0";
        $data['etapa']="INICIO-NB"."-".$actividad_generalid;
        $data['estado']='A';
        $data['h_real']="";
        $data['fecha_tarea']=$fecha_tarea= $this->_getParam('fecha_tarea');
        $data['fecha_modificacion']=$diaactual;
        $semanaid=$this->_getParam('semanaid');
        $data['semanaid']=$semanaid= $this->_getParam('semanaid');
        $data['fecha_planificacion']=$fecha_tarea;
        $data['tipo_actividad']=$tipo_actividad= 'G';
        $data['fecha_creacion']=$fecha_tarea;
        $data['cargo']=$cargo;
        $data['areaid']=$areaid;
        //datos para ctualizar
        $datos['h_real']="";
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
        ?>
          <script>                  
            //alert("Se guardo satisfactoriamente");
           // document.location.href="/timesheet/index/calendar";
             
          </script>
        <?php
            
        } else
        {
        
                /*$str="codigo_prop_proy='$codigo_prop_proy' and proyectoid='$proyectoid' and 
            categoriaid='$categoriaid' and actividadid='$actividadid' and 
            revision='$revision' and codigo_actividad='$codigo_actividad'
            and actividad_padre='$actividad_padre' and cargo='$cargo'
            and semanaid='$semanaid' and areaid='$areaid' and fecha_tarea='$fecha_tarea' 
            and fecha_planificacion='$fecha_tarea' and etapa='EJECUCION' and tipo_actividad='$tipo_actividad'";
          //  echo $str;
            $update=$tareopersona -> _update($datos,$str);*/
        }
       
        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        }
    }


public function eliminartareoAction(){
    try {
        $this->_helper->layout()->disableLayout();
        $uid = $this->sesion->uid;
        $dni = $this->sesion->dni;
        $dbucat= new Admin_Model_DbTable_Usuariocategoria();
        $datosucat = $dbucat->_getUsuarioxPersona($uid,$dni);
        $cargo = $datosucat[0]['cargo'];
        $areaid = $datosucat[0]['areaid']; 
        $tareopersona = new Admin_Model_DbTable_Tareopersona();
    
        $proyectoid = $this->_getParam('proyectoid');
        $codigo_prop_proy = $this->_getParam('codigo');
        $categoriaid = $this->_getParam('categoriaid');
        $actividadid = $this->_getParam('actividadid');
        $revision = $this->_getParam('revision');
        $codigo_actividad = $this->_getParam('codigo_actividad');
        $actividad_padre = $this->_getParam('actividad_padre');
        $etapa= $this->_getParam('etapa');
        
        $resultado = str_replace("INICIO", "EJECUCION", $etapa);

        $fecha_tarea= $this->_getParam('fecha_tarea');
        $fecha_planificacion= $this->_getParam('fecha_planificacion');
        $cargo= $this->_getParam('cargo');
        $semanaid=$this->_getParam('semanaid');
        $tipo_actividad=$this->_getParam('tipo_actividad');

                
        $pk  =   array(                        
            'codigo_prop_proy'   =>$codigo_prop_proy,
            'codigo_actividad'   =>$codigo_actividad,
            'actividadid'   =>$actividadid,
            'revision'   =>$revision,
            'actividad_padre'   =>$actividad_padre,
            'proyectoid'   =>$proyectoid,
            'semanaid'   =>$semanaid,
            'fecha_tarea'   =>$fecha_tarea,
            'uid'   =>$uid,
            'dni'   =>$dni,
            'cargo'   =>$cargo,
            'etapa'   =>$etapa,
            'fecha_planificacion'   =>$fecha_planificacion,
            'tipo_actividad'   =>$tipo_actividad,
        );
        
         $pk1  =   array(                        
            'codigo_prop_proy'   =>$codigo_prop_proy,
            'codigo_actividad'   =>$codigo_actividad,
            'actividadid'   =>$actividadid,
            'revision'   =>$revision,
            'actividad_padre'   =>$actividad_padre,
            'proyectoid'   =>$proyectoid,
            'semanaid'   =>$semanaid,
            'uid'   =>$uid,
            'dni'   =>$dni,
            'cargo'   =>$cargo,
            'etapa'   =>$resultado,
            'tipo_actividad'   =>$tipo_actividad,
        ); 

       $tareopersona->_deleteTareasEtapaEjecucion($pk1);
        if ($tareopersona->_delete($pk)){
            ?>
              <script>      
           //  alert("eliminado");
              </script>
            <?php

          
            

        }else
        {
            ?>
              <script>                  
                alert("No se elimino");
              </script>
            <?php
        }
       
        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        }
    }


public function sumatareorealAction(){
    try {
        $this->_helper->layout()->disableLayout();
        $uid = $this->sesion->uid;
        $dni = $this->sesion->dni;
        $dbucat= new Admin_Model_DbTable_Usuariocategoria();
        $datosucat = $dbucat->_getUsuarioxPersona($uid,$dni);
        $cargo = $datosucat[0]['cargo'];
        $areaid = $datosucat[0]['areaid']; 
        $semanaid=$this->_getParam('semanaid');
        $fecha_tarea=$this->_getParam('fecha_tarea');
        $cargoid=$this->_getParam('cargo');
        $tareopersona = new Admin_Model_DbTable_Tareopersona();
        $sumar=$tareopersona-> _getHorasRealxDia($semanaid,$fecha_tarea,$uid,$dni,$cargoid);
        print_r($sumar[0]['tareo_persona_horas_reales']);
          ?>
              <script>                  
                alert("No se elimino");
              </script>
            <?php
        
        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        }
    }

     public function updateetapanbAction(){
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
        $data['codigo_prop_proy']=$codigo_prop_proy = $this->_getParam('codigo');
        $data['categoriaid']=$categoriaid = $this->_getParam('categoriaid');
        $data['actividadid']=$actividadid = $this->_getParam('actividadid');
        $data['revision']=$revision = $this->_getParam('revision');
        $data['codigo_actividad']=$codigo_actividad = $this->_getParam('codigo_actividad');
        $data['actividad_padre']=$actividad_padre = $this->_getParam('actividad_padre');
        $data['h_propuesta']=$h_propuesta = $this->_getParam('h_propuesta');
        $data['uid']=$uid;
        $data['asignado']= $dni;
        $data['estado']= 'A';
        $data['dni']=$dni;
        $data['h_propuesta']=$h_propuesta = $this->_getParam('h_propuesta');
        $etapa = $this->_getParam('etapa');
        $etapa_ejecucion = $this->_getParam('etapa');
        $resultado_ejecucion = str_replace("INICIO", "EJECUCION", $etapa_ejecucion);
        
        $datos['actividad_generalid']=$actividad_generalid = $this->_getParam('tarea_general');

        $datos1['actividad_generalid']= $this->_getParam('tarea_general');
        
$datos['tipo_actividad']='G';
$datos1['tipo_actividad']='G';
        $datos['etapa']='INICIO-NB-'.$actividad_generalid;
        $datos1['etapa']='EJECUCION-NB-'.$actividad_generalid;
        $data['h_real']=$h_real= $this->_getParam('horareal');
        $data['fecha_tarea']=$fecha_tarea= $this->_getParam('fecha_tarea');
        $cargo= $this->_getParam('cargo');
        $data['fecha_modificacion']=$diaactual;
        $semanaid=$this->_getParam('semanaid');
        $data['semanaid']=$semana;
        $data['fecha_planificacion']=$fecha_planificacion=$this->_getParam('fecha_planificacion');
        $data['tipo_actividad']=$tipo_actividad= $this->_getParam('tipo_actividad');
        $data['fecha_creacion']=$fecha_tarea;
        $data['cargo']=$cargo;
        $data['areaid']=$areaid;
        //datos para ctualizar
        
        //$datos['h_real']=$h_real= $this->_getParam('horareal');
        $datos['fecha_modificacion']=$diaactual;
        $datos1['fecha_modificacion']=$diaactual;
        //$updatetareopersona = new Admin_Model_DbTable_Tareopersona();    
        /*$str="codigo_prop_proy='$codigo_prop_proy' and proyectoid='$proyectoid' and 
        categoriaid='$categoriaid' and actividadid='$actividadid' and 
        revision='$revision' and codigo_actividad='$codigo_actividad'
        and actividad_padre='$actividad_padre' and cargo='$cargo'
        and semanaid='$semanaid' and areaid='$areaid'     ";*/
        //$update=$updatetareopersona -> _update($data,$str);
        $tareopersona = new Admin_Model_DbTable_Tareopersona();
        $str="codigo_prop_proy='$codigo_prop_proy' and proyectoid='$proyectoid' and 
            categoriaid='$categoriaid' and actividadid='$actividadid' and 
            revision='$revision' and codigo_actividad='$codigo_actividad'
            and actividad_padre='$actividad_padre'   and semanaid='$semanaid' and areaid='$areaid' 
            and fecha_tarea='$fecha_tarea' 
            and fecha_planificacion='$fecha_planificacion' and etapa='$etapa' and tipo_actividad='$tipo_actividad' 
            and estado='A'
            ";

               $str1="codigo_prop_proy='$codigo_prop_proy' and proyectoid='$proyectoid' and 
            categoriaid='$categoriaid' and actividadid='$actividadid' and 
            revision='$revision' and codigo_actividad='$codigo_actividad'
            and actividad_padre='$actividad_padre'   and semanaid='$semanaid' and areaid='$areaid' 
            and  etapa='$resultado_ejecucion' and tipo_actividad='$tipo_actividad' 
            and estado='A'
            ";
       // echo $str; echo "<br>";
       // echo $str1;echo "<br>";

        //print_r($datos);echo "<br>";
        //print_r($datos1);echo "<br>";
            $update=$tareopersona -> _update($datos,$str);
            $update2=$tareopersona -> _update($datos1,$str1);
            if ($update || $update2) { //echo "guardo";
        }
                else
                    { 

                         ?>
              <script>                  
                alert("No se guardo cargue nuevamente la pagina");
              </script>
            <?php
//            echo "no guardo";
//print_r($update);
            }
       
        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        }
    }

    public function updateetapabAction(){
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
        $data['codigo_prop_proy']=$codigo_prop_proy = $this->_getParam('codigo');
        $data['categoriaid']=$categoriaid = $this->_getParam('categoriaid');
        $data['actividadid']=$actividadid = $this->_getParam('actividadid');
        $data['revision']=$revision = $this->_getParam('revision');
        $data['codigo_actividad']=$codigo_actividad = $this->_getParam('codigo_actividad');
        $data['actividad_padre']=$actividad_padre = $this->_getParam('actividad_padre');
        $data['h_propuesta']=$h_propuesta = $this->_getParam('h_propuesta');
        $data['uid']=$uid;
        $data['asignado']= $dni;
        $data['estado']= 'A';
        $data['dni']=$dni;
        $data['h_propuesta']=$h_propuesta = $this->_getParam('h_propuesta');
        $etapa = $this->_getParam('etapa');
        
        $datos['actividad_generalid']=null;

        $etapa_ejecucion = $this->_getParam('etapa');

        $resultado_ejecucion = str_replace("INICIO", "EJECUCION", $etapa_ejecucion);

        $datos1['actividad_generalid']= null;

$datos1['tipo_actividad']='P';

        $datos1['etapa']='EJECUCION';

        $datos1['fecha_modificacion']=$diaactual;
        
        
$datos['tipo_actividad']='P';
        $datos['etapa']='INICIO';
        $data['h_real']=$h_real= $this->_getParam('horareal');
        $data['fecha_tarea']=$fecha_tarea= $this->_getParam('fecha_tarea');
        $cargo= $this->_getParam('cargo');
        $data['fecha_modificacion']=$diaactual;
        $semanaid=$this->_getParam('semanaid');
        $data['semanaid']=$semana;
        $data['fecha_planificacion']=$fecha_planificacion=$this->_getParam('fecha_planificacion');
        $data['tipo_actividad']=$tipo_actividad= $this->_getParam('tipo_actividad');
        $data['fecha_creacion']=$fecha_tarea;
        $data['cargo']=$cargo;
        $data['areaid']=$areaid;
        //datos para ctualizar
        
        //$datos['h_real']=$h_real= $this->_getParam('horareal');
        $datos['fecha_modificacion']=$diaactual;
        //$updatetareopersona = new Admin_Model_DbTable_Tareopersona();    
        /*$str="codigo_prop_proy='$codigo_prop_proy' and proyectoid='$proyectoid' and 
        categoriaid='$categoriaid' and actividadid='$actividadid' and 
        revision='$revision' and codigo_actividad='$codigo_actividad'
        and actividad_padre='$actividad_padre' and cargo='$cargo'
        and semanaid='$semanaid' and areaid='$areaid'     ";*/
        //$update=$updatetareopersona -> _update($data,$str);
        $tareopersona = new Admin_Model_DbTable_Tareopersona();
        $str="codigo_prop_proy='$codigo_prop_proy' and proyectoid='$proyectoid' and 
            categoriaid='$categoriaid' and actividadid='$actividadid' and 
            revision='$revision' and codigo_actividad='$codigo_actividad'
            and actividad_padre='$actividad_padre'   and semanaid='$semanaid' and areaid='$areaid' 
            and fecha_tarea='$fecha_tarea' 
            and fecha_planificacion='$fecha_planificacion' and etapa='$etapa' and tipo_actividad='$tipo_actividad' 
            and estado='A'
            ";


               $str1="codigo_prop_proy='$codigo_prop_proy' and proyectoid='$proyectoid' and 
            categoriaid='$categoriaid' and actividadid='$actividadid' and 
            revision='$revision' and codigo_actividad='$codigo_actividad'
            and actividad_padre='$actividad_padre'   and semanaid='$semanaid' and areaid='$areaid' 
            and  etapa='$resultado_ejecucion' and tipo_actividad='$tipo_actividad' 
            and estado='A'
            ";


  //      echo $str;
    //    print_r($datos);
            $update=$tareopersona -> _update($datos,$str);
            $update=$tareopersona -> _update($datos1,$str1);
            if ($update) { //echo "guardo";
        }
                else
                    { 
                         ?>
              <script>                  
                alert("No se guardo cargue nuevamente la pagina");
              </script>
            <?php
            //echo "no guardo";
//print_r($update);
            }
       
        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        }
    }



    public function sumahorasAction(){
        try {

            //semanaid/'+semanaid+/'fecha'/+fet+/'act'/+act+/'uid'/+uid+/'dni'/+dni_+/'cargo'/+cargo;
        $semanaid = $this->_getParam('semanaid');
        $fecha = $this->_getParam('fecha');
        $act = $this->_getParam('act');
        $uid = $this->_getParam('uid');
        $dni = $this->_getParam('dni');
        $cargo = $this->_getParam('cargo');

        $tareo_persona = new Admin_Model_DbTable_Tareopersona();
        $semana=date('W', strtotime($fecha_inicio_mod)); 
        // $datos_tareopersona=$tareo_persona->_getTareoxPersonaxSemana($uid,$dni,$semana);
      
        // $this->view->actividades= $datos_tareopersona;
      

        $this->view->semanaid = $semanaid;
        $this->view->fecha = $fecha;
        $this->view->act = $act;
        $this->view->uid = $uid;
        $this->view->dni = $dni;
        $this->view->cargo = $cargo;

        }
        catch (Exception $e) {
            print "Error: ".$e->getMessage();
        }
    }

}