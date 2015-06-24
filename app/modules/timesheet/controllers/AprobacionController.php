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
                    $numero_registro = count($buscar_registro);
                    $data['numero_historial']=$numero_registro;
                    $guardar_historial_empleado = $tabla_historial_aprobaciones -> _save($data);
                    if ($guardar_historial_empleado)
                    {
                        echo "guardo satisfactoriamente despues de guardar";
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

    public function aprobarhojatiempofiltro2Action(){
        try {

            $this->_helper->layout()->disableLayout();            
            $uid = $this->_getParam('uid');
            $dni = $this->_getParam('dni');
            //$areaid = $this->_getParam('areaid');
            $categoriaid = $this->_getParam('categoriaid');
            $cargo = $this->_getParam('cargo');
            $semana = $this->_getParam('semanaid');
            $comentario = $this->_getParam('coment');
            $estado_historial   = $this->_getParam('estado');
            $uid_validador=$this->_getParam('uid_validacion');
            $dni_validador=$this->_getParam('dni_validacion');
            //$fecha_validacion=$this->_getParam('fecha');
            $etapa_validador=$this->_getParam('etapa');
            $time = time();
            $fecha_envio=date("d-m-Y (H:i:s)", $time);
            
            $data['semanaid']=$semana;
            $data['uid_empleado']=$uid;
            $data['dni_empleado']=$dni;
            //$data['areaid_empleado']=$areaid;
            $data['categoriaid_empleado']=$categoriaid;
            $data['fecha_registro']=$fecha_envio;
           //$data['codigoaprobacion_empleado']=$codigoaprobacion_empleado;
            $data['estado_historial']=$estado_historial;
            $data['etapa_validador']=$etapa_validador;
            $data['comentario']=$comentario;

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
                    echo "existe";
                    print_r($buscar_historial_empleado_filtro2);
                }
                else
                {   
                    echo "no existe";
                    $wheres_empleado = array('semanaid'=>$semana,'uid_empleado'=>$uid,'dni_empleado'=>$dni,'etapa_validador'=>'ENVIO','estado_historial'=>'A');
                    $buscar_historial_empleado = $tabla_historial_aprobaciones -> _getBuscarEmpleadoxSemanaxEstado($wheres_empleado);
                    print_r($buscar_historial_empleado);
                    $numero_registro = count($buscar_registro);
                    $data['numero_historial']=$numero_registro+1;
                    //print_r($data);
                    //$guardar_historial_empleado = $tabla_historial_aprobaciones -> _save($data);
                    //if ($guardar_historial_empleado)
                    //{
                    //    echo "guardo satisfactoriamente despues de guardar";
                    //}
                }
            }
/*      
            $data['cargo']=$cargo;
            $data2['cargo']=$cargo;
            $data['semanaid']=$semana;
            $data2['semanaid']=$semana;
            $data['uid']=$uid;
            $data['dni']=$dni;
            $data2['uid']=$uid;
            $data2['dni']=$dni;
            $data['uid_validacion']=$uid_validacion;
            $data['dni_validacion']=$dni_validacion;
            $data['comentario']=$coment;
            $data['estado_usuario']=$estado;
            $time = time();
            $datetime=date("d-m-Y (H:i:s)", $time);
            $fecha_validacion=$datetime;

            $data['fecha_validacion']=$fecha_validacion;
            $data['etapa']=$etapa_validacion;

            $where['uid']=$uid;
            $where['dni']=$dni;
            //$where['uid_validacion']=$uid_validacion;
            //$where['dni_validacion']=$dni_validacion;
            $where['cargo']=$cargo;
            $where['semanaid']=$semana;
            $vercoment= new Admin_Model_DbTable_Usuariovalidacion();
            if($vcoment=$vercoment->_getOnexUsuario($where))
            {

                $pk = array('dni' => $dni  ,'uid' => $uid,'cargo' => $cargo ,'semanaid' => $semana, );
                $count=$vercoment->_getUsuarioxValidacion($semana,$uid,$dni);
                $data2['comentario']=$coment;
                $data2['estado_usuario']=$estado;
                $data2['fecha_validacion']=$fecha_validacion;
                $data2['etapa']=$etapa_validacion;
                $data2['orden']=count($count)+1;
                $data2['estado']="A";
                
                $data2['uid_validacion']=$uid_validacion;
                $data2['dni_validacion']=$dni_validacion;
                $usercoment=$vercoment->_save($data2);
                if ($usercoment){

                $pk1 = array('dni' => $dni  ,'uid' => $uid,'cargo' => $cargo ,'semanaid' => $semana, 'orden' => count($count));
                $data3['estado']="C";

               // print_r($pk1);
                //    $coment=new Admin_Model_DbTable_Usuariovalidacion();
                    //$usecoment=$coment->_updateX($data2,$pk);
                
                    $usecoment=$vercoment->_updateXUsuario($data3,$pk1);
                }
                echo "update";
                $sumahorassemana = new Admin_Model_DbTable_Sumahorasemana();
                
                 if ( $estado=='R'){
                  $datos_actualizar_sumahoras1['estado']='0';   
                  $datos_actualizar_sumahoras1['estado_real']='RECHAZADO';
                  $str_actualizar_sumahoras1="semanaid='$semana' and uid='$uid' and dni='$dni'";
                        $tareopersona = new Admin_Model_DbTable_Tareopersona();
                        $datos_actualizar['estado']='A';
                        $str_actualizar="semanaid='$semana' and uid='$uid' and dni='$dni' and   estado='C' ";
                        $update_tareopersona=$tareopersona -> _update($datos_actualizar,$str_actualizar);

                        $update=$sumahorassemana -> _update($datos_actualizar_sumahoras1,$str_actualizar_sumahoras1);
                }
                if ( $estado=='B'){
                    $tareopersona = new Admin_Model_DbTable_Tareopersona();
                    $datos_actualizar['estado']='C';
                    $str_actualizar="semanaid='$semana' and uid='$uid' and dni='$dni' and   estado='A' ";
                    $update_tareopersona=$tareopersona -> _update($datos_actualizar,$str_actualizar);  
                    $vercoment= new Admin_Model_DbTable_Usuariovalidacion();
                    $planificacion = new Admin_Model_DbTable_Planificacion();
                    $proyectos=$planificacion->_getSemanaxGerenteProyecto($semana,$uid,$dni);
                    print_r($proyectos);
                    $validado=0;
                    $novalidado=0;
                        foreach ($proyectos as $datos) {
                            $where_validador['uid_validacion']=$datos['uid'];
                            $where_validador['dni_validacion']=$datos['dni'];
                            $where_validador['estado_usuario']='B';
                            $where_validador['etapa']='2';
                            $where_validador['semanaid']=$semana; 
                            $where_validador['uid']=$uid; 
                            $where_validador['dni']=$dni; 

                            $cant_validadores=$vercoment->_getOnexUsuarioxValidador($where_validador);
                            print_r($cant_validadores);
                            if ($cant_validadores){
                                $validado++;
                            }
                            else
                            {
                                $novalidado++;
                            }
                                                
                        }
                             echo "cantidad d evlaidacioes"; echo $validado;
                             echo "cantidad d eno validados";  echo $novalidado;
                             echo "cantidad d evalidsdotres"; echo count($proyectos);

                        if (count($proyectos)==$validado)
                        {
                               echo "apribadoooo";
                            $datos_actualizar_validador['estado_real']='APROBADO';
                            $str_actualizar_validador="semanaid='$semana' and uid='$uid' and dni='$dni' ";
                            $update_validador=$sumahorassemana -> _update($datos_actualizar_validador,$str_actualizar_validador);
                            $datos_actualizar_sumahoras2['estado']='2';
                            $str_actualizar_sumahoras2="semanaid='$semana' and uid='$uid' and dni='$dni'";
                            $update=$sumahorassemana -> _update($datos_actualizar_sumahoras2,$str_actualizar_sumahoras2);

                        }
                        else
                        {
                              echo "rechazadodoodod";
                            $datos_actualizar_validador['estado_real']='PENDIENTE';
                            $str_actualizar_validador="semanaid='$semana' and uid='$uid' and dni='$dni'";
                            $update_validador=$sumahorassemana -> _update($datos_actualizar_validador,$str_actualizar_validador);
                            $datos_actualizar_sumahoras3['estado']='3';
                            $str_actualizar_sumahoras3="semanaid='$semana' and uid='$uid' and dni='$dni'";
                            $update=$sumahorassemana -> _update($datos_actualizar_sumahoras3,$str_actualizar_sumahoras3);

                        }   
                    }

            }
            else
            {//echo "no existe";
                $coment=new Admin_Model_DbTable_Usuariovalidacion();
              //$usercoment=$coment->_save($data);
                //echo "save";
                $sumahorassemana = new Admin_Model_DbTable_Sumahorasemana();
                 if ( $estado=='R'){
                  $datos_actualizar_sumahoras['estado']='0';   
                        $tareopersona = new Admin_Model_DbTable_Tareopersona();
                        $datos_actualizar['estado']='A';
                        $str_actualizar="semanaid='$semana' and uid='$uid' and dni='$dni' and   estado='C' ";
                        $update_tareopersona=$tareopersona -> _update($datos_actualizar,$str_actualizar);
                }
                 if ( $estado=='B'){
                   $datos_actualizar_sumahoras['estado']='2';
                         $tareopersona = new Admin_Model_DbTable_Tareopersona();
                        $datos_actualizar['estado']='C';
                        $str_actualizar="semanaid='$semana' and uid='$uid' and dni='$dni' and   estado='A' ";
                        $update_tareopersona=$tareopersona -> _update($datos_actualizar,$str_actualizar);  
                }

                
                
                $str_actualizar_sumahoras="semanaid='$semana' and uid='$uid' and dni='$dni' 
                ";
                //$update=$sumahorassemana -> _update($datos_actualizar_sumahoras,$str_actualizar_sumahoras);
            }*/

            
        }
        catch (Exception $e) {
                print "Error: ".$e->getMessage();
        }
    }
};