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

    public function enviarhojatiempoAction(){
        try {
            $this->_helper->layout()->disableLayout();   
            $uid = $this->_getParam('uid');
            $dni = $this->_getParam('dni');
            $semana = $this->_getParam('semanaid');
            $etapa = $this->_getParam('etapa');
            $fecha_envio = $this->_getParam('fecha_envio');
            $estado = $this->_getParam('estado');
            $areaid=$this->sesion->personal->ucatareaid;
            $categoriaid=$this->sesion->personal->ucatid;
            $codigoaprobacion_empleado = $this->sesion->personal->ucataprobacion;
            /*Datos Tabla Historial Aprobaciones*/
            $data['semanaid']=$semana;
            $data['uid_empleado']=$uid;
            $data['dni_empleado']=$dni;
            $data['areaid_empleado']=$areaid;
            $data['categoriaid_empleado']=$categoriaid;
            $data['fecha_registro']=$fecha_envio;
            $data['codigoaprobacion_empleado']=$codigoaprobacion_empleado;
            $data['estado_historial']='A';
            $data['etapa_validador']='ENVIO';
            $data['comentario']='Enviado Hoja de Tiempo para aprobacion';
            $data['comentario_estado']=$estado;

            $tabla_historial_aprobaciones= new Admin_Model_DbTable_Historialaprobaciones();
            $buscar_registro=$tabla_historial_aprobaciones->_getBuscarEmpleadoxSemana($semana,$uid,$dni);
            /*Buscar Registro en la tabla Aprobaciones*/
            if ($buscar_registro)
            {   
                $wheres_empleado = array('semanaid'=>$semana,'uid_empleado'=>$uid,'dni_empleado'=>$dni,'etapa_validador'=>'ENVIO','estado_historial'=>'A');
                $buscar_historial_empleado = $tabla_historial_aprobaciones -> _getBuscarEmpleadoxSemanaxEstado($wheres_empleado);
                /*Buscar si tine un registro*/
                if ($buscar_historial_empleado)
                {
                    echo "existe";
                    
                }
                else
                {
                    $numero_registro = count($buscar_registro)+1;
                    $data['numero_historial']=$numero_registro;
                    $guardar_historial_empleado = $tabla_historial_aprobaciones -> _save($data);
                    if ($guardar_historial_empleado)
                    {
                        echo "guardo satisfactoriamente";
                        $tareopersona = new Admin_Model_DbTable_Tareopersona();
                        $datos_actualizar['estado']='C';
                        $str_actualizar="semanaid='$semana' and uid='$uid' and dni='$dni'";
                        $update_tareopersona=$tareopersona -> _update($datos_actualizar,$str_actualizar);  
                        $tabla_planificacion = new Admin_Model_DbTable_Planificacion();
                        $lista_proyectos_empleado=$tabla_planificacion->_getProyectosxSemana($semana,$uid,$dni);
                        /*Esto parte comentada es para poner estado deacuerdo al proyecto mas adelante*/
                        //foreach ($lista_proyectos_empleado as $proyectos_empleado) {
                            //if ($proyectos_empleado['estado']=='R' or $proyectos_empleado['estado']=='RGP' $proyectos_empleado['estado']=='' or $proyectos_empleado['estado'] is null)
                            //{
                                $datos_actualizar_planificacion['estado']='E';
                                //$str_actualizar_planificacion="semanaid='$semana' and uid='$uid' and dni='$dni' and proyectoid='$proyectoid' ";
                                $str_actualizar_planificacion="semanaid='$semana' and uid='$uid' and dni='$dni'";
                                $update_planificacion=$tabla_planificacion -> _update($datos_actualizar_planificacion,$str_actualizar_planificacion);       
                            ///}
                        //}
                    }
                }
            }
            else
            {   
                $numero_registro='1';
                $data['numero_historial']=$numero_registro;
                $guardar_historial_empleado = $tabla_historial_aprobaciones -> _save($data);
                if ($guardar_historial_empleado)
                    {
                        echo "guardo satisfactoriamente primera ves";
                        $tareopersona = new Admin_Model_DbTable_Tareopersona();
                        $datos_actualizar['estado']='C';
                        $str_actualizar="semanaid='$semana' and uid='$uid' and dni='$dni'";
                        $update_tareopersona=$tareopersona -> _update($datos_actualizar,$str_actualizar);  
                        $tabla_planificacion = new Admin_Model_DbTable_Planificacion();
                        $lista_proyectos_empleado=$tabla_planificacion->_getProyectosxSemana($semana,$uid,$dni);
                        /*Esto parte comentada es para poner estado deacuerdo al proyecto mas adelante*/
                        //foreach ($lista_proyectos_empleado as $proyectos_empleado) {
                            //if ($proyectos_empleado['estado']=='R' or $proyectos_empleado['estado']=='RGP' $proyectos_empleado['estado']=='' or $proyectos_empleado['estado'] is null)
                            //{
                                //$proyectoid=$proyectos_empleado['proyectoid']
                                $datos_actualizar_planificacion['estado']='E';
                                //$str_actualizar_planificacion="semanaid='$semana' and uid='$uid' and dni='$dni' and proyectoid='$proyectoid' ";
                                $str_actualizar_planificacion="semanaid='$semana' and uid='$uid' and dni='$dni'";
                                $update_planificacion=$tabla_planificacion -> _update($datos_actualizar_planificacion,$str_actualizar_planificacion);       
                            //}
                        //}
                    }
            }
         } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        } 

    }

    public function mostrarhojatiempofiltro2Action(){
        try {
            $uid = $this->sesion->uid;
            $dni = $this->sesion->dni;     

            $tabla_usuariocategoria= new Admin_Model_DbTable_Usuariocategoria();
            $codigosaprobaciones_empleado = $tabla_usuariocategoria->_getBuscarCodigoAprobacionesxEmpleado($uid,$dni);
            
            $lista_empleados_aprobar=array();
            foreach ($codigosaprobaciones_empleado as $codigoaprobaciones) {
                $tabla_aprobacion = new Admin_Model_DbTable_Aprobacion();
                $codigos_paraaprobar=$tabla_aprobacion-> _getCodigoAprobacionxAprobadorfiltro2($codigoaprobaciones['aprobacion'],'A');
                foreach ($codigos_paraaprobar as $codigo_aprobar) {
                    $tabla_historial_aprobaciones= new Admin_Model_DbTable_Historialaprobaciones();       
                    $listar_historial_aprobaciones = $tabla_historial_aprobaciones -> _getBuscarEmpleadoxSemanaxEstadoxCodigoAprobacion('ENVIO','A',$codigo_aprobar['idaprobacion']);
                    $lista_empleados_aprobar[]=$listar_historial_aprobaciones;
                }
            }
            $this->view->lista_empleados_aprobar= $lista_empleados_aprobar;   
        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        } 
    }

    public function mostrardetallehojatiempofiltro2Action(){
        try {
            $this->_helper->layout()->disableLayout();
            $uid = $this->_getParam('uid');
            $dni = $this->_getParam('dni');
            $semana = $this->_getParam('semanaid');
            $uid_validacion = $this->sesion->uid;
            $dni_validacion = $this->sesion->dni;
            $this->view->uid_validacion=$uid_validacion;
            $this->view->dni_validacion=$dni_validacion;
            $areaid=$this->sesion->personal->ucatareaid;   
            $this->view->cargo = $areaid;
            $this->view->uid = $uid;
            $this->view->dni = $dni;
            /*funcion para devolver dias de la semana*/
            $ano=date("Y");/*ojo cambiar  con el tiempo --revisar */
            $enero = mktime(1,1,1,1,1,$ano); 
            $mos = (11-date('w',$enero))%7-3;
            $this->view->mos=$mos;
            $this->view->enero=$enero;
            $this->view->semana = $semana;
            $tareo_persona = new Admin_Model_DbTable_Tareopersona();
            $datos_tareopersona=$tareo_persona->_getTareoxPersonaxSemana($uid,$dni,$semana);
            $this->view->actividades= $datos_tareopersona;
        }    
         catch (Exception $e) {
            print "Error: ".$e->getMessage();
        }
    }

     public function mostrarhistoricohojatiempofiltro2Action(){
        try {
            $uid = $this->sesion->uid;
            $dni = $this->sesion->dni;     

            $tabla_usuariocategoria= new Admin_Model_DbTable_Usuariocategoria();
            $codigosaprobaciones_empleado = $tabla_usuariocategoria->_getBuscarCodigoAprobacionesxEmpleado($uid,$dni);
            
            $lista_empleados_aprobar=array();
            foreach ($codigosaprobaciones_empleado as $codigoaprobaciones) {
                $tabla_aprobacion = new Admin_Model_DbTable_Aprobacion();
                $codigos_paraaprobar=$tabla_aprobacion-> _getCodigoAprobacionxAprobadorfiltro2($codigoaprobaciones['aprobacion'],'A');
                foreach ($codigos_paraaprobar as $codigo_aprobar) {
                    $tabla_historial_aprobaciones= new Admin_Model_DbTable_Historialaprobaciones();       
                    $listar_historial_aprobaciones = $tabla_historial_aprobaciones -> _getBuscarEmpleadoxHojatiempohistorico('FILTRO2',$codigo_aprobar['idaprobacion']);
                    $lista_empleados_aprobar[]=$listar_historial_aprobaciones;
                }
            }
            $this->view->lista_empleados_aprobar= $lista_empleados_aprobar;   
        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        } 
    }

    public function aprobarhojatiempofiltro2Action(){
        try {

            $this->_helper->layout()->disableLayout();            
            $uid = $this->_getParam('uid');
            $dni = $this->_getParam('dni');
            //$areaid = $this->_getParam('areaid');
            $categoriaid = $this->_getParam('categoriaid');
            $cargo = $this->_getParam('cargo');
            $semana = $this->_getParam('semanaid');
            $comentario_capturado = $this->_getParam('coment');
            $comentario = str_replace("_"," ",$comentario_capturado);

            $estado_historial   = $this->_getParam('estado');
            $uid_validador=$this->_getParam('uid_validacion');
            $dni_validador=$this->_getParam('dni_validacion');
            //$fecha_validacion=$this->_getParam('fecha');
            $etapa_validador=$this->_getParam('etapa');
            $time = time();
            $fecha_envio=date("d-m-Y (H:i:s)", $time);
            
            $tabla_historial_aprobaciones= new Admin_Model_DbTable_Historialaprobaciones();
            $buscar_registro=$tabla_historial_aprobaciones->_getBuscarEmpleadoxSemana($semana,$uid,$dni);
            /*Buscar Registro en la tabla Aprobaciones*/
            if ($buscar_registro)
            {   
                $wheres_empleado_filtro2 = array('semanaid'=>$semana,'uid_empleado'=>$uid,'dni_empleado'=>$dni,'etapa_validador'=>'FILTRO2','estado_historial'=>'A','uid_validador'=>$uid_validador,'dni_validador'=>$dni_validador);
                $buscar_historial_empleado_filtro2 = $tabla_historial_aprobaciones -> _getBuscarEmpleadoxSemanaxEstadoxAprobacionFiltro2($wheres_empleado_filtro2);
                /*Buscar si tine un registro*/
                if ($buscar_historial_empleado_filtro2)
                {
                    //echo "existe";
                    //print_r($buscar_historial_empleado_filtro2);
                }
                else
                {   
                   // echo "no existe";
                    $wheres_empleado = array('semanaid'=>$semana,'uid_empleado'=>$uid,'dni_empleado'=>$dni,'etapa_validador'=>'ENVIO','estado_historial'=>'A');
                    $buscar_historial_empleado = $tabla_historial_aprobaciones -> _getBuscarEmpleadoxSemanaxEstado($wheres_empleado);
                    $numero_registro = count($buscar_registro);
                    
                    $datos_actualizar['estado_historial']='C';
                    $str_actualizar="
                        semanaid='$semana' and uid_empleado='$uid' and dni_empleado='$dni' 
                        and etapa_validador='ENVIO' and estado_historial='A' 
                        ";
                    $update=$tabla_historial_aprobaciones -> _update($datos_actualizar,$str_actualizar);

                    if ($update)
                    {
                        $data['numero_historial']=$numero_registro+1;
                        $data['semanaid']=$semana;
                        $data['uid_empleado']=$uid;
                        $data['dni_empleado']=$dni;
                        $data['uid_validador']=$uid_validador;
                        $data['dni_validador']=$dni_validador;

                        $data['areaid_empleado']=$buscar_historial_empleado['areaid_empleado'];
                        $data['categoriaid_empleado']=$buscar_historial_empleado['categoriaid_empleado'];
                        $data['fecha_registro']=$fecha_envio;
                        $data['codigoaprobacion_empleado']=$buscar_historial_empleado['codigoaprobacion_empleado'];
                        $data['codigoaprobacion_validador']=$this->sesion->personal->ucataprobacion;
                        $data['estado_historial']=$estado_historial;
                        $data['etapa_validador']=$etapa_validador;
                        $data['comentario']=$comentario;
                        $data['comentario_estado']=$estado_historial;
                        ////print_r($data);
                        $guardar_historial_empleado = $tabla_historial_aprobaciones -> _save($data);
                        if ($guardar_historial_empleado)
                        {
                            //echo "guardo satisfactoriamente";
                            if ($estado_historial=='R')
                            {
                                $tareopersona = new Admin_Model_DbTable_Tareopersona();
                                $datos_actualizar_tareo['estado']='A';
                                $str_actualizar_tareo="semanaid='$semana' and uid='$uid' and dni='$dni' and estado='C' ";
                                $update_tareopersona=$tareopersona -> _update($datos_actualizar_tareo,$str_actualizar_tareo);  

                                $tabla_planificacion = new Admin_Model_DbTable_Planificacion();
                                $lista_proyectos_empleado=$tabla_planificacion->_getProyectosxSemana($semana,$uid,$dni);
                                //foreach ($lista_proyectos_empleado as $proyectos_empleado) {
                                    //if ($proyectos_empleado['estado']=='R' or $proyectos_empleado['estado']=='RGP' or $proyectos_empleado['estado']=='E')
                                    //{
                                        //$proyectoid=$proyectos_empleado['proyectoid'];
                                        $datos_actualizar_planificacion['estado']='R';
                                        //$str_actualizar_planificacion="semanaid='$semana' and uid='$uid' and dni='$dni' and proyectoid='$proyectoid' ";
                                        $str_actualizar_planificacion="semanaid='$semana' and uid='$uid' and dni='$dni'";
                                        $update_planificacion=$tabla_planificacion -> _update($datos_actualizar_planificacion,$str_actualizar_planificacion);       
                                    //}
                                //}
                            }
                            if ($estado_historial=='A')
                            {
                                $tabla_planificacion = new Admin_Model_DbTable_Planificacion();
                                //$proyectoid=$proyectos_empleado['proyectoid'];
                                $datos_actualizar_planificacion['estado']='A';
                                $str_actualizar_planificacion="semanaid='$semana' and uid='$uid' and dni='$dni'";
                                $update_planificacion=$tabla_planificacion -> _update($datos_actualizar_planificacion,$str_actualizar_planificacion);       
                            }
                        }
                    }
                }
            }
        }
        catch (Exception $e) {
                print "Error: ".$e->getMessage();
        }
    }

    public function mostrarhojatiempogerenteAction(){
        try {
            $uid = $this->sesion->uid;
            $dni = $this->sesion->dni;     
            $this->view->uid_gerente=$uid;
            $tabla_planificacion = new Admin_Model_DbTable_Planificacion();
            $lista_empleados_aprobar = $tabla_planificacion->_getListarEquipoxAprobacionxGerenteProyecto($uid,$dni);
            $this->view->lista_empleados_aprobar= $lista_empleados_aprobar;   

            
        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        } 
    }

    public function mostrardetallegerenteAction(){
        try {
            $this->_helper->layout()->disableLayout();
            $uid = $this->_getParam('uid');
            $dni = $this->_getParam('dni');
            $semana = $this->_getParam('semanaid');
            $uid_validacion = $this->sesion->uid;
            $dni_validacion = $this->sesion->dni;
            $this->view->uid_validacion=$uid_validacion;
            $this->view->dni_validacion=$dni_validacion;
            $areaid=$this->sesion->personal->ucatareaid;   
            $this->view->cargo = $areaid;
            $this->view->uid = $uid;
            $this->view->dni = $dni;
            /*funcion para devolver dias de la semana*/
            $ano=date("Y");/*ojo cambiar  con el tiempo --revisar */
            $enero = mktime(1,1,1,1,1,$ano); 
            $mos = (11-date('w',$enero))%7-3;
            $this->view->mos=$mos;
            $this->view->enero=$enero;
            $this->view->semana = $semana;
            $tareo_persona = new Admin_Model_DbTable_Tareopersona();
            $datos_tareopersona=$tareo_persona->_getTareoxPersonaxSemana($uid,$dni,$semana);
            $this->view->actividades= $datos_tareopersona;
            $tabla_planificacion = new Admin_Model_DbTable_Planificacion();
            $lista_horas_equipo = $tabla_planificacion->_getHorasxEquipoxSemanaxProyectosGerenteProyecto($uid_validacion,$dni_validacion,$uid,$dni,$semana);
            $this->view->lista_horas_equipo= $lista_horas_equipo;   
        }    
         catch (Exception $e) {
            print "Error: ".$e->getMessage();
        }
    }

    public function aprobarhojatiempogerenteAction(){
        try {

            $this->_helper->layout()->disableLayout();            
            $uid = $this->_getParam('uid');
            $dni = $this->_getParam('dni');
            //$areaid = $this->_getParam('areaid');
            $categoriaid = $this->_getParam('categoriaid');
            $cargo = $this->_getParam('cargo');
            $semana = $this->_getParam('semanaid');
            //$comentario = $this->_getParam('coment');
            $comentario_capturado = $this->_getParam('coment');
            $comentario = str_replace("_"," ",$comentario_capturado);

            $estado_historial   = $this->_getParam('estado');
            $uid_validador=$this->_getParam('uid_validacion');
            $dni_validador=$this->_getParam('dni_validacion');
            //$fecha_validacion=$this->_getParam('fecha');
            $etapa_validador=$this->_getParam('etapa');
            $time = time();
            $fecha_envio=date("d-m-Y (H:i:s)", $time);
            
            $tabla_historial_aprobaciones= new Admin_Model_DbTable_Historialaprobaciones();
            $buscar_registro=$tabla_historial_aprobaciones->_getBuscarEmpleadoxSemana($semana,$uid,$dni);
            /*Buscar Registro en la tabla Aprobaciones*/
            if ($buscar_registro)
            {   
                $wheres_empleado_gerente = array('semanaid'=>$semana,'uid_empleado'=>$uid,'dni_empleado'=>$dni,'etapa_validador'=>'GP','estado_historial'=>'A','uid_validador'=>$uid_validador,'dni_validador'=>$dni_validador);
                $buscar_historial_empleado_gerente = $tabla_historial_aprobaciones -> _getBuscarEmpleadoxSemanaxEstadoxAprobacionGP($wheres_empleado_gerente);
                //print_r($buscar_historial_empleado_gerente);
                /*Buscar si tine un registro*/
                if ($buscar_historial_empleado_gerente)
                {
                   // echo "existe";
                }
                else
                {   
                    //print_r($wheres_empleado_gerente);
                    //echo "no existessss";
                    $wheres_empleado = array('semanaid'=>$semana,'uid_empleado'=>$uid,'dni_empleado'=>$dni,'etapa_validador'=>'ENVIO','estado_historial'=>'C');

                    $buscar_historial_empleado = $tabla_historial_aprobaciones -> _getBuscarEmpleadoxSemanaxEstado($wheres_empleado);
                    //print_r($buscar_historial_empleado);
                    $numero_registro = count($buscar_registro);
                    $data['numero_historial']=$numero_registro+1;
                    $data['semanaid']=$semana;
                    $data['uid_empleado']=$uid;
                    $data['dni_empleado']=$dni;
                    $data['uid_validador']=$uid_validador;
                    $data['dni_validador']=$dni_validador;
                    $data['areaid_empleado']=$buscar_historial_empleado['areaid_empleado'];
                    $data['categoriaid_empleado']=$buscar_historial_empleado['categoriaid_empleado'];
                    $data['fecha_registro']=$fecha_envio;
                    $data['codigoaprobacion_empleado']=$buscar_historial_empleado['codigoaprobacion_empleado'];
                    $data['codigoaprobacion_validador']='2.0';
                    $data['estado_historial']=$estado_historial;
                    $data['etapa_validador']=$etapa_validador;
                    $data['comentario']=$comentario;
                    $data['comentario_estado']=$estado_historial;
                    $guardar_historial_empleado = $tabla_historial_aprobaciones -> _save($data);
                    if ($guardar_historial_empleado)
                    {
                       // echo "guardo satisfactoriamente";
                        if ($estado_historial=='R')
                        {
                            $tareopersona = new Admin_Model_DbTable_Tareopersona();
                            $datos_actualizar_tareo['estado']='A';
                            $str_actualizar_tareo="semanaid='$semana' and uid='$uid' and dni='$dni' and estado='C' ";
                            $update_tareopersona=$tareopersona -> _update($datos_actualizar_tareo,$str_actualizar_tareo);  
                            
                            $tabla_planificacion = new Admin_Model_DbTable_Planificacion();
                            $lista_proyectos_empleado=$tabla_planificacion->_getProyectosxSemana($semana,$uid,$dni);
                            $datos_actualizar_planificacion['estado']='R';
                            $str_actualizar_planificacion="semanaid='$semana' and uid='$uid' and dni='$dni'";
                            //echo ($str_actualizar_planificacion);
                            $update_planificacion=$tabla_planificacion -> _update($datos_actualizar_planificacion,$str_actualizar_planificacion);

                            $datos_actualizar_aprobaciones['estado_historial']='RGP';
                            $str_actualizar_aprobaciones="
                                semanaid='$semana' and uid_empleado='$uid' and dni_empleado='$dni' 
                            ";
                            $update=$tabla_historial_aprobaciones -> _update($datos_actualizar_aprobaciones,$str_actualizar_aprobaciones);
                        }
                        if ($estado_historial=='A')
                        {
                            $tabla_planificacion = new Admin_Model_DbTable_Planificacion();
                            $lista_proyectos_empleado=$tabla_planificacion->_getListarProyectosxSemanaxGerenteProyecto($uid_validador,$dni_validador,$uid,$dni,$semana);
                            foreach ($lista_proyectos_empleado as $proyectos_empleado) {
                                $proyectoid=$proyectos_empleado['proyectoid'];
                                $datos_actualizar_planificacion['estado']='AGP';
                                $str_actualizar_planificacion="semanaid='$semana' and uid='$uid' and dni='$dni' and proyectoid='$proyectoid' ";
                                //print_r($str_actualizar_planificacion);
                                $update_planificacion=$tabla_planificacion -> _update($datos_actualizar_planificacion,$str_actualizar_planificacion);       
                            }

                        }
                    }
                }
            }
        }
        catch (Exception $e) {
                print "Error: ".$e->getMessage();
        }
    }

    public function mostrarhistoricohojatiempogerenteAction(){
        try {
            $uid = $this->sesion->uid;
            $dni = $this->sesion->dni;  

            $tabla_usuariocategoria= new Admin_Model_DbTable_Usuariocategoria();
            $codigosaprobaciones_empleado = $tabla_usuariocategoria->_getBuscarCodigoAprobacionesxEmpleado($uid,$dni);
            
            $lista_empleados_aprobar=array();

            //foreach ($codigosaprobaciones_empleado as $codigoaprobaciones) {
                //$tabla_aprobacion = new Admin_Model_DbTable_Aprobacion();
             $tabla_historial_aprobaciones= new Admin_Model_DbTable_Historialaprobaciones();       
                $lista_empleados_aprobar=$tabla_historial_aprobaciones-> _getListarHistoricoxAprobador($uid,$dni);

               // $codigos_paraaprobar=$tabla_aprobacion-> _getCodigoAprobacionxAprobadorfiltro2($codigoaprobaciones['aprobacion'],'A');
               // foreach ($codigos_paraaprobar as $codigo_aprobar) {
                 //   $tabla_historial_aprobaciones= new Admin_Model_DbTable_Historialaprobaciones();       
                 //   $listar_historial_aprobaciones = $tabla_historial_aprobaciones -> _getBuscarEmpleadoxHojatiempohistorico('GP',$codigo_aprobar['idaprobacion']);
                 //   $lista_empleados_aprobar[]=$listar_historial_aprobaciones;
               // }
           // }
            $this->view->lista_empleados_aprobar= $lista_empleados_aprobar;   

            
        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        } 
    }

};
