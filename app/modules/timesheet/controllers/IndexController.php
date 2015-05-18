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
        $fecha_consulta = $this->_getParam('fecha');

        $equipo = new Admin_Model_DbTable_Equipo();
        $data_equipo = $equipo->_getProyectosxUidXEstadoxCliente($uid,'A',$clienteid,$unidadid);
             
        $this->view->equipo = $data_equipo;
        $this->view->fecha_consulta = $fecha_consulta;

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
        $fecha_consulta = $this->_getParam('fecha');

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
        $this->view->fecha_consulta = $fecha_consulta;
        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        } 
    }    


    public function actividadeshijosAction(){
        try {
        $uid = $this->sesion->uid;
        $dni = $this->sesion->dni;
        $this->view->uid = $uid;
        $this->view->dni = $dni;
        $fecha_consulta = $this->_getParam('fecha');
        $fecha_inicio_semana = date("Y-m-d", strtotime($fecha_consulta));
           
        $semana=date('W', strtotime($fecha_inicio_semana)); 
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
        $fecha_consulta = $this->_getParam('fecha');
        $fecha_inicio_semana = date("Y-m-d", strtotime($fecha_consulta));
        $semana=date('W', strtotime($fecha_inicio_semana)); 
        $this->view->semanaid = $semana;
        $this->view->uid = $uid;
        $this->view->dni = $dni;
 

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
        $categoriaid=$this->sesion->personal->ucatid;
        $areaid=$this->sesion->personal->ucatareaid;
        $cargo=$this->sesion->personal->ucatcargo;

        $this->view->cargo=$cargo;
        $this->view->areaid=$areaid;
        $tareo_persona = new Admin_Model_DbTable_Tareopersona();
        $semana=date('W', strtotime($fecha_inicio_mod)); 
        $this->view->semana = $semana;
        $datos_tareopersona=$tareo_persona->_getTareoxPersonaxSemana($uid,$dni,$semana);
        $datos_tareopersona_NB=$tareo_persona->_getTareoxPersonaxSemanaxNB($uid,$dni,$semana);
        //$data_tareo = $tareo->_getTareoXUid($where);
        $this->view->actividades= $datos_tareopersona;

        //print_r($datos_tareopersona);
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
        $categoriaid=$this->sesion->personal->ucatid;
        $areaid=$this->sesion->personal->ucatareaid;
        $cargo=$this->sesion->personal->ucatcargo;
        
             
        $data['proyectoid']=$proyectoid = $this->_getParam('proyectoid');
        $data['codigo_prop_proy']=$codigo_prop_proy = $this->_getParam('codigo');
        $data['actividadid']=$actividadid = $this->_getParam('actividadid');
        $data['revision']=$revision = $this->_getParam('revision');
        $data['codigo_actividad']=$codigo_actividad = $this->_getParam('codigo_actividad');
        $data['actividad_padre']=$actividad_padre = $this->_getParam('actividad_padre');
        $data['h_propuesta']=$h_propuesta = $this->_getParam('h_propuesta');
        $data['uid']=$uid;
        $data['asignado']= $dni;
        $data['dni']=$dni;
        $data['etapa']='INICIO';
        
        $fecha_inicio = $this->_getParam('fecha');
        $fecha_inicio_mod = date("Y-m-d", strtotime($fecha_inicio));
        $semana=date('W', strtotime($fecha_inicio_mod)); 

        $data['semanaid']=$semana;
        $data['fecha_tarea']=$fecha_inicio_mod;
        $data['fecha_creacion']=$fecha_inicio_mod;
        $data['fecha_planificacion']=$fecha_inicio_mod;
        $data['tipo_actividad']='P';
        $data['estado']='A';
        $data['cargo']=$cargo;
        $data['areaid']=$areaid;
        $data['categoriaid']=$categoriaid;

        $tareopersona = new Admin_Model_DbTable_Tareopersona();
        $data_tareopersona = $tareopersona->_save($data);

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
         $categoriaid=$this->sesion->personal->ucatid;
        $areaid=$this->sesion->personal->ucatareaid;
        $cargo=$this->sesion->personal->ucatcargo;
      
        $fecha_inicio = $this->_getParam('fecha_calendario');
        $fecha_inicio_mod = date("Y-m-d", strtotime($fecha_inicio));
        $semanaid=date('W', strtotime($fecha_inicio_mod)); 
        $tipo_actividad_actualizar= $this->_getParam('tipo_actividad');
        $etapa_origen= $this->_getParam('etapa');
        $etapa_actualizar = str_replace("INICIO", "EJECUCION", $etapa_origen);
        

        $datos_actualizar['fecha_modificacion']=$fecha_inicio_mod;
        $datos_actualizar['h_real']=$h_real= $this->_getParam('horareal');

        //$semanaid=$this->_getParam('semanaid');
        $data['proyectoid']=$proyectoid = $this->_getParam('proyectoid');
        $data['codigo_prop_proy']=$codigo_prop_proy = $this->_getParam('codigo_prop_proy');
        $data['actividadid']=$actividadid = $this->_getParam('actividadid');
        $data['revision']=$revision = $this->_getParam('revision');
        $data['codigo_actividad']=$codigo_actividad = $this->_getParam('codigo_actividad');
        $data['actividad_padre']=$actividad_padre = $this->_getParam('actividad_padre');
        $data['h_propuesta']=$h_propuesta = $this->_getParam('h_propuesta');
        $data['fecha_tarea']=$fecha_tarea= $this->_getParam('fecha_tarea');
        $data['fecha_planificacion']=$fecha_tarea;
        $data['fecha_modificacion']=$fecha_inicio_mod;
        $data['fecha_creacion']=$fecha_inicio_mod;
        $data['h_real']=$h_real= $this->_getParam('horareal');
        $data['semanaid']=$semanaid;

         $actividad_generalid = $this->_getParam('actividad_generalid');
        if($actividad_generalid=='')
        {
            $data['actividad_generalid']=null;
            $data['tipo_actividad']='P';
            $etapa= $this->_getParam('etapa');
            $resultado = str_replace("INICIO", "EJECUCION", $etapa);
            $data['etapa']=$resultado;    
        }
        else
        {
            $data['actividad_generalid']=$actividad_generalid;
            $data['tipo_actividad']='G';
            $etapa= $this->_getParam('etapa');
            $resultado = str_replace("INICIO", "EJECUCION", $etapa);
            $data['etapa']=$resultado;
        }

        $data['categoriaid']=$categoriaid;
        $data['uid']=$uid;
        $data['asignado']= $dni;
        $data['estado']= 'A';
        $data['dni']=$dni;
        $data['cargo']=$cargo;
        $data['areaid']=$areaid;


        $tareopersona = new Admin_Model_DbTable_Tareopersona();
      
         if ($h_real=='')
            {   echo "h_real vacio";

            }else
        {
            $data_tareopersona = $tareopersona->_save($data);
        if ($data_tareopersona){

            $data1['cargo']=$cargo;
            $data1['semanaid']=$semanaid;
            $data1['uid']=$uid;
            $data1['dni']=$dni;
            $data1['fecha_tarea']=$fecha_tarea= $this->_getParam('fecha_tarea');
            $data1['h_totaldia']=$h_real= $this->_getParam('horareal');
            if($tipo_actividad=='G')
            {
                $data1['nonbillable']=$h_real= $this->_getParam('horareal');
                //$data2['nonbillable']=$h_real= $this->_getParam('horareal');
            }
            elseif ($tipo_actividad=='P') {
                $data1['billable']=$h_real= $this->_getParam('horareal');                
                //$data2['billable']=$h_real= $this->_getParam('horareal');                
            }

            $data2['cargo']=$cargo;
            $data2['semanaid']=$semanaid;
            $data2['uid']=$uid;
            $data2['dni']=$dni;  

            $wheres=array('dni'=>$dni,'uid'=>$uid,'cargo'=>$cargo,'semanaid'=>$semanaid,'fecha_tarea'=>$fecha_tarea);
            $wheres2=array('dni'=>$dni,'uid'=>$uid,'cargo'=>$cargo,'semanaid'=>$semanaid);

            $sumahora = new Admin_Model_DbTable_Sumahora();
            if($versum=$sumahora->_getOne($wheres))
            {
            }
            else
            {
                $data_sumahora = $sumahora->_save($data1);
            }

            /*inserta en la tabla suma_controsemana un registro*/
            $suma_control= new Admin_Model_DbTable_Sumahorasemana();
            if($versumcontrol=$suma_control->_getOne($wheres2))
            {

            }
            else
            {
                $data_sumahora = $suma_control->_save($data2); 
            }


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
              $str_actualizar="codigo_prop_proy='$codigo_prop_proy' and proyectoid='$proyectoid' and 
                categoriaid='$categoriaid' and actividadid='$actividadid' and 
                revision='$revision' and codigo_actividad='$codigo_actividad'
                and actividad_padre='$actividad_padre' and cargo='$cargo'
                and semanaid='$semanaid' and areaid='$areaid' and fecha_tarea='$fecha_tarea' 
                and etapa='$etapa_actualizar' and tipo_actividad='$tipo_actividad_actualizar' 
                and  estado='A' 
                ";
            $update=$tareopersona -> _update($datos_actualizar,$str_actualizar);

        } }
       
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
        $categoriaid=$this->sesion->personal->ucatid;
        $areaid=$this->sesion->personal->ucatareaid;
        $cargo=$this->sesion->personal->ucatcargo;
        $fecha_inicio = $this->_getParam('fecha');
        $fecha_inicio_mod = date("Y-m-d", strtotime($fecha_inicio));
    


       
        $data['fecha_tarea']=$fecha_inicio_mod;
        $data['fecha_creacion']=$fecha_inicio_mod;
        $data['fecha_planificacion']=$fecha_inicio_mod;


        
        $data['proyectoid']=$proyectoid = $this->_getParam('proyectoid');
        $data['codigo_prop_proy']=$codigo_prop_proy = $this->_getParam('codigo');
        $data['categoriaid']=$categoriaid;
        $data['revision']=$revision = $this->_getParam('revision');
        $data['actividadid']=$actividadid = $this->_getParam('actividadid');
        $data['codigo_actividad']=$codigo_actividad = $this->_getParam('codigo_actividad');
        $data['actividad_padre']=$actividad_padre = $this->_getParam('actividad_padre');
//        $data['actividad_generalid']=$actividad_generalid = $this->_getParam('actividad_generalid');
//        $data['area_generalid']=$area_generalid = $this->_getParam('area_generalid');
        
        $data['uid']=$uid;
        $data['asignado']= $dni;
        $data['dni']=$dni;
        $data['h_propuesta']="0";
        $data['etapa']="INICIO-NB"."-";
        $data['estado']='A';
        $data['h_real']=null;
        
        $data['fecha_modificacion']=$fecha_inicio_mod;
        $data['semanaid']=$semanaid= $this->_getParam('semanaid');

        $data['tipo_actividad']='G';
     
        $data['cargo']=$cargo;
        $data['areaid']=$areaid;
        //datos para ctualizar
    
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
            ?>

          <script>                  
           //alert("No Se guardo satisfactoriamente");
           // document.location.href="/timesheet/index/calendar";
             
          </script>
        <?php
        
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
        $categoriaid=$this->sesion->personal->ucatid;
        $areaid=$this->sesion->personal->ucatareaid;
        $cargo=$this->sesion->personal->ucatcargo;
        $tareopersona = new Admin_Model_DbTable_Tareopersona();
        $proyectoid = $this->_getParam('proyectoid');
        $codigo_prop_proy = $this->_getParam('codigo');
        $actividadid = $this->_getParam('actividadid');
        $revision = $this->_getParam('revision');
        $codigo_actividad = $this->_getParam('codigo_actividad');
        $actividad_padre = $this->_getParam('actividad_padre');
        $fecha_tarea= $this->_getParam('fecha_tarea');
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
            'uid'   =>$uid,
            'dni'   =>$dni,
            'tipo_actividad'   =>$tipo_actividad,
        );
        
        if ($tareopersona->_deleteTareasxSemana($pk))
        {
            //echo "borro";print_r($pk);
        }
        else
        {
            //echo "no borro";  print_r($pk);
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
        $categoriaid=$this->sesion->personal->ucatid;
        $areaid=$this->sesion->personal->ucatareaid;
        $cargo=$this->sesion->personal->ucatcargo;

        $fecha_inicio = $this->_getParam('fecha');
        $fecha_inicio_mod = date("Y-m-d", strtotime($fecha_inicio));
        
        $proyectoid = $this->_getParam('proyectoid');
        $codigo_prop_proy = $this->_getParam('codigo');
        $actividadid = $this->_getParam('actividadid');
        $revision = $this->_getParam('revision');
        $codigo_actividad = $this->_getParam('codigo_actividad');
        $actividad_padre = $this->_getParam('actividad_padre');
        $actividad_generalid = $this->_getParam('tarea_general');
        $semanaid=$this->_getParam('semanaid');
        $tipo_actividad= $this->_getParam('tipo_actividad');

        $etapa_inicio = $this->_getParam('etapa');
        $etapa_ejecucion = str_replace("INICIO", "EJECUCION", $etapa_inicio);
        
        $datos_inicio['actividad_generalid']=$this->_getParam('tarea_general');
        $datos_ejecucion['actividad_generalid']= $this->_getParam('tarea_general');
        
        $datos_inicio['tipo_actividad']='G';
        $datos_ejecucion['tipo_actividad']='G';

        $datos_inicio['etapa']='INICIO-NB-'.$actividad_generalid;
        $datos_ejecucion['etapa']='EJECUCION-NB-'.$actividad_generalid;

        
        $datos_inicio['fecha_modificacion']=$fecha_inicio_mod;
        $datos_ejecucion['fecha_modificacion']=$fecha_inicio_mod;
     
        $tareopersona = new Admin_Model_DbTable_Tareopersona();
        $str_inicio="codigo_prop_proy='$codigo_prop_proy' and proyectoid='$proyectoid' and 
            categoriaid='$categoriaid' and actividadid='$actividadid' and 
            revision='$revision' and codigo_actividad='$codigo_actividad'
            and actividad_padre='$actividad_padre'   and semanaid='$semanaid' and areaid='$areaid' 
            and etapa='$etapa_inicio' and tipo_actividad='$tipo_actividad' 
            and estado='A' and uid='$uid' and dni='$dni'
            ";

        $str_ejecucion="codigo_prop_proy='$codigo_prop_proy' and proyectoid='$proyectoid' and 
            categoriaid='$categoriaid' and actividadid='$actividadid' and 
            revision='$revision' and codigo_actividad='$codigo_actividad'
            and actividad_padre='$actividad_padre'   and semanaid='$semanaid' and areaid='$areaid' 
            and  etapa='$etapa_ejecucion' and tipo_actividad='$tipo_actividad' 
            and estado='A' and uid='$uid' and dni='$dni'
            ";
            $update_inicio=$tareopersona -> _update($datos_inicio,$str_inicio);
            $update_ejecucion=$tareopersona -> _update($datos_ejecucion,$str_ejecucion);
            if ($update_inicio || $update_ejecucion) { //echo "guardo";
            }
            else
            {?>
                <script>                  
                    alert("No se guardo cargue nuevamente la pagina o ya tiene una tarea facturable creada.");
                </script>
            <?php
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
        $categoriaid=$this->sesion->personal->ucatid;
        $areaid=$this->sesion->personal->ucatareaid;
        $cargo=$this->sesion->personal->ucatcargo;

        $fecha_inicio = $this->_getParam('fecha');
        $fecha_inicio_mod = date("Y-m-d", strtotime($fecha_inicio));
        

        $proyectoid = $this->_getParam('proyectoid');
        $codigo_prop_proy = $this->_getParam('codigo');
        $actividadid = $this->_getParam('actividadid');
        $revision = $this->_getParam('revision');
        $codigo_actividad = $this->_getParam('codigo_actividad');
        $actividad_padre = $this->_getParam('actividad_padre');
        $actividad_generalid = $this->_getParam('tarea_general');
        $semanaid=$this->_getParam('semanaid');
        $tipo_actividad= $this->_getParam('tipo_actividad');

        $etapa_inicio = $this->_getParam('etapa');
        $etapa_ejecucion = str_replace("INICIO", "EJECUCION", $etapa_inicio);
        
        $datos_inicio['actividad_generalid']=null;;
        $datos_ejecucion['actividad_generalid']= null;;
        
        $datos_inicio['tipo_actividad']='P';
        $datos_ejecucion['tipo_actividad']='P';

        $datos_inicio['etapa']='INICIO';
        $datos_ejecucion['etapa']='EJECUCION';

        
        $datos_inicio['fecha_modificacion']=$fecha_inicio_mod;
        $datos_ejecucion['fecha_modificacion']=$fecha_inicio_mod;
     
        $tareopersona = new Admin_Model_DbTable_Tareopersona();
        $str_inicio="codigo_prop_proy='$codigo_prop_proy' and proyectoid='$proyectoid' and 
            categoriaid='$categoriaid' and actividadid='$actividadid' and 
            revision='$revision' and codigo_actividad='$codigo_actividad'
            and actividad_padre='$actividad_padre'   and semanaid='$semanaid' and areaid='$areaid' 
            and etapa='$etapa_inicio' and tipo_actividad='$tipo_actividad' 
            and estado='A' and uid='$uid' and dni='$dni'
            ";

        $str_ejecucion="codigo_prop_proy='$codigo_prop_proy' and proyectoid='$proyectoid' and 
            categoriaid='$categoriaid' and actividadid='$actividadid' and 
            revision='$revision' and codigo_actividad='$codigo_actividad'
            and actividad_padre='$actividad_padre'   and semanaid='$semanaid' and areaid='$areaid' 
            and  etapa='$etapa_ejecucion' and tipo_actividad='$tipo_actividad' 
            and estado='A' and uid='$uid' and dni='$dni'
            ";
            $update_inicio=$tareopersona -> _update($datos_inicio,$str_inicio);
            $update_ejecucion=$tareopersona -> _update($datos_ejecucion,$str_ejecucion);
            if ($update_inicio || $update_ejecucion) { //echo "guardo";
            }
            else
            {?>
                <script>                  
                    alert("No se guardo cargue nuevamente la pagina o ya tiene una tarea facturable creada.");
                </script>
            <?php
        }
       
        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        }
    }



    public function sumahorasAction(){
        try {

    // '/timesheet/index/sumahoras/semanaid/'+semanaid+/'fecha'/+fecha+/'fechainiciomod'/+fechainiciomod+/'uid'/+uid+/'dni'/+dni_+/'cargo'/+cargo;  
        $this->_helper->layout()->disableLayout();

        $semanaid = $this->_getParam('semanaid');
        $fecha = $this->_getParam('fecha');
        $fecha_inicio_mod = $this->_getParam('fechainiciomod');    
        $uid = $this->_getParam('uid');
        $dni = $this->_getParam('dni');
        $cargo = $this->_getParam('cargo');

        $tareo_persona = new Admin_Model_DbTable_Tareopersona();
        $semana=date('W', strtotime($fecha_inicio_mod)); 
        $datos_tareopersona=$tareo_persona->_getTareoxPersonaxSemana($uid,$dni,$semana);
      
        $this->view->actividades= $datos_tareopersona;
      

        $this->view->semanaid = $semanaid;
        $this->view->fecha = $fecha;
  
        $this->view->uid = $uid;
        $this->view->dni = $dni;
        $this->view->cargo = $cargo;

        }
        catch (Exception $e) {
            print "Error: ".$e->getMessage();
        }
    }


    public function traersumahorasAction(){
        try {

    // '/timesheet/index/sumahoras/semanaid/'+semanaid+/'fecha'/+fecha+/'fechainiciomod'/+fechainiciomod+/'uid'/+uid+/'dni'/+dni_+/'cargo'/+cargo;  
        $this->_helper->layout()->disableLayout();

        $semanaid = $this->_getParam('semanaid');
        $fecha = $this->_getParam('fecha');
        $fecha_inicio_mod = $this->_getParam('fechainiciomod');    
        $uid = $this->_getParam('uid');
        $dni = $this->_getParam('dni');
        $cargo = $this->_getParam('cargo');

        $wheres=array('dni'=>$dni,'uid'=>$uid,'cargo'=>$cargo,'semanaid'=>$semanaid,'fecha_tarea'=>$fecha);

        $sumahora = new Admin_Model_DbTable_Sumahora();
        $versum=$sumahora->_getOne($wheres);

        //print_r($versum);
        //echo $this->versum['h_totaldia'];
      
        $this->view->versum= $versum;
      

        $this->view->semanaid = $semanaid;
        $this->view->fecha = $fecha;
  
        $this->view->uid = $uid;
        $this->view->dni = $dni;
        $this->view->cargo = $cargo;

        }
        catch (Exception $e) {
            print "Error: ".$e->getMessage();
        }
    }


    public function historialaprobadoAction(){
        try {

        $user= new Admin_Model_DbTable_Usuario();
        $vuser=$user->_getUsuarioAll();
        $this->view->usuarios=$vuser;
        //print_r($vuser);
  
       // if()


        $suma_hora = new Admin_Model_DbTable_Sumahora();
        $versuma=$suma_hora->_getSumahoraAll();
        $this->view->listasuma=$versuma;
        //print_r($versuma);


        }
         catch (Exception $e) {
            print "Error: ".$e->getMessage();
        }
    }

    public function filtrosAction(){
        try {
            
            $usuario = $this->_getParam('usuario');
            //$dateinicio = $this->_getParam('dateinicio');
            $dateinicio = '2015-05-12';
            $datefin = $this->_getParam('datefin');    
            $estado = $this->_getParam('estado');   

            $wheresumhora = array( 'uid' => $usuario, 'estado' => $estado, 'fecha_tarea' => $dateinicio);
            //$attrib = array('dni', 'uid');
            $order = array('dni ASC');
            $suma_hora = new Admin_Model_DbTable_Sumahora();
            $versuma=$suma_hora->_getFilter($wheresumhora,$attrib=null,$order);
            $this->view->listasuma=$versuma;

            print_r($versuma);



            }
         catch (Exception $e) {
            print "Error: ".$e->getMessage();
        }
    }




}