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
                        echo "guardo satisfactoriamente";
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
                        echo "guardo satisfactoriamente";
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
};