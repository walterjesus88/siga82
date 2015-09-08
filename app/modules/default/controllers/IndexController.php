<?php

class IndexController extends Zend_Controller_Action {

    public function init(){
      $options = array(
            'layout' => 'login',
        );
        Zend_Layout::startMvc($options);
    }

    public function  indexAction(){
        try{
            // $sesion1  = Zend_Auth::getInstance();
            // if($sesion1->hasIdentity()){
            //     $sesion = $sesion1->getStorage()->read();
            //     if ($sesion->rid=='AD') {
            //         $this->_helper->redirector('index','index','admin');
            //     }
            //     elseif ($sesion->rid=='AL') {
            //         $this->_helper->redirector('index','index','alumno');
            //     }
            //     elseif ($sesion=='DC') {
            //         $this->_helper->redirector('index','index','docente');
            //     }
            // }
            
            //$msg = $this->_getParam('e');
            //$this->view->msg = $msg;
            $form = new Default_Form_Login();
            $this->view->form = $form;
            if ($this->getRequest()->isPost()) {
                // Envio del Formulario Login
                $formData = $this->getRequest()->getPost();
                if ($form->isValid($formData)) {
                    // Recepcion de valores y asiganacion a variables
                    $metodo = $this->_getParam('metodo');
                    $cod = $form->getValue('usuario');
                    $pass = ($form->getValue('clave'));
                    
                    // Lecturamos el adaptador de BD por defecto
                    $dbAdapter = Zend_Db_Table_Abstract::getDefaultAdapter(); 
                    // Seteamos el adaptador para Auth segun la tabla, caolumnas, y filtros
                    $authAdapter = new Zend_Auth_Adapter_DbTable($dbAdapter,'usuario','uid','password');
                    $authAdapter->getDbSelect()->where("estado = 'A'");
                    
                    // Pasamos valores a la clase Auth
                    $authAdapter->setIdentity($cod)->setCredential($pass);
                    // Instalanciamos Singleton de Auth
                    $auth = Zend_Auth::getInstance();
                    // Verificamos autenticidad del usuario
                    $result = $auth->authenticate($authAdapter);

                    if ($result->isValid()) {
                        // // Registramos la sesion para el Objeto con los parametros minimos
                        // // Selecionamos los campos necesarios de USUARIO para llevarlos a una SESION
                        $data  = $authAdapter->getResultRowObject(array('uid','dni','estado','rid','categoriaid','nivel'));

                        // $data->periodo = $periodo;
                        // $data->nombre_periodo = $nombre_periodo;

                        // Seleccionamos y seteamos los datos de sesion de una persona
                        $persona = new Admin_Model_DbTable_Persona();
                        $rp = $persona->_getPersona($data->dni);

                        $dbucat= new Admin_Model_DbTable_Usuariocategoria();
                        $datosucat = $dbucat->_getUsuarioxPersona($data->uid,$data->dni);
                        
                        $where = array();
                        $where['uid'] = $data->uid;
                        $where['dni'] = $data->dni;
                        $where['estado'] = 'A';
                        $where['nivel'] = '0';

                        $where1['uid'] = $data->uid;
                        $where1['dni'] = $data->dni;
                        $where1['estado'] = 'A';
                        $where1['nivel'] = '2';

                        $where2['uid'] = $data->uid;
                        $where2['dni'] = $data->dni;
                        $where2['estado'] = 'A';
                        $where2['nivel'] = '3';

                        $where3['uid'] = $data->uid;
                        $where3['dni'] = $data->dni;
                        $where3['estado'] = 'A';
                        $where3['nivel'] = '1';

                        $equipo = new Admin_Model_DbTable_Equipo();
                        $data_equipo = $equipo->_getProyectosXuidXEstadoXnivel($where);
                        $data_equipo_jefe = $equipo->_getProyectosXuidXEstadoXnivel($where1);
                        $data_equipo_responsable = $equipo->_getProyectosXuidXEstadoXnivel($where2);
                        $data_equipo_gerente = $equipo->_getProyectosXuidXEstadoXnivel($where3);

                        if ($rp){
                            $data->personal = new stdClass();
                            $data->personal->full_name = $rp['ape_paterno']." ".$rp['ape_materno'].", ".$rp['nombres'];
                            $data->personal->ape_paterno = $rp['ape_paterno'];
                            $data->personal->ape_materno = $rp['ape_materno'];
                            $data->personal->nombres = $rp['nombres'];
                            $data->personal->iscontacto = $rp['iscontacto'];
                            $data->personal->email_anddes = $rp['email_anddes'];
                            $data->personal->isanddes = $rp['isanddes'];
                            $data->personal->sexo= $rp['sexo']; 
                            $data->personal->alias= $rp['alias']; 
                            if ($datosucat) {
                                $data->personal->ucatid= $datosucat[0]['categoriaid']; 
                                $data->personal->ucatareaid= $datosucat[0]['areaid']; 
                                $data->personal->ucatcargo= $datosucat[0]['cargo'];                                
                                $data->personal->ucataprobacion= $datosucat[0]['aprobacion'];  
                            }

                            if ($data_equipo) {
                                $data->is_gerente = 'S';
                            } else {
                                $data->is_gerente = 'N';
                            }

                            if ($data_equipo_jefe) {
                                $data->is_jefe = 'S';
                            } else {
                                $data->is_jefe = 'N';
                            }

                            if ($data_equipo_responsable) {
                                $data->is_responsable = 'S';
                            } else {
                                $data->is_responsable = 'N';
                            }

                             if ($data_equipo_gerente) {
                                $data->is_gerente_area = 'S';
                            } else {
                                $data->is_gerente_area = 'N';
                            }

                                   
                        }
                        $auth->getStorage()->write($data);
                        /*tiempo Expiracion de la Sesion PHP en segundos*/
                        $authSession = new Zend_Session_Namespace('Zend_Auth');
                        $authSession->setExpirationSeconds(1200);

                        // Registrando el Acceso en la BD
                        
                        // $clientIp = $this->getRequest()->getClientIp();
                        // $log = new Admin_Model_DbTable_Log();
                        // $aleatorio = rand(2,9);
                        // $datalog['tokenid']= time()+$aleatorio;
                        // $data->tokenid=$datalog['tokenid'];
                        // $datalog['anho']= date('Y');
                        // $datalog['ip']= $clientIp;
                        // $datalog['eid']= $data->eid;
                        // $datalog['oid']= "2";
                        // $datalog['pid']= $data->pid;
                        // $datalog['rid']= $data->rid;
                        // $datalog['date_access']= date('Y-m-d');
                        // $datalog['hora_acces']= date('H:m:s');
                        // $datalog['date_exit']= date('Y-m-d');
                        // $datalog['hora_exit']= date('H:m:s');
                        // $datalog['estado']= 'A';
                        // $datalog['keysession']= time();
                        // $log->_save($datalog);                      

                        /* Provisional */
                        if ($data->rid=='PROYECTO') {
                            //print_r($data);
                            $this->_helper->redirector('calendar','index','timesheet');
                        }
                        elseif ($data->rid=='PROPUESTA') {
                            $this->_helper->redirector('index','index','admin');
                        }
                    } else {
                        // datos incorrectos, podríamos mostrar un mensaje de error
                        switch ($result->getCode()) {
                            case Zend_Auth_Result::FAILURE_IDENTITY_NOT_FOUND:
                                // usuario inexistente
                                $this->view->msgerror="El usuario que ingreso no se encuentra registrado.";
                                break;
                            case Zend_Auth_Result::FAILURE_CREDENTIAL_INVALID:
                                // password erroneo
                                $this->view->msgerror="Su contraseña es incorrecta.";
                                break; 
                            default:
                                /* otro error */
                                $this->view->msgerror="Se produjo un error al ingreso, intentelo nuevamente.";
                                print_r($result);
                                break;
                        }
                    }
                } else {
                    $form->populate($formData);
                }
            }
        }
        catch (Exception $ex){
            print "Error Login".$ex->getMessage();
        }
    }

    public function salirAction(){
        $sesion  = Zend_Auth::getInstance();
        $sesion_ = $sesion->getStorage()->read();
        Zend_Auth::getInstance()->clearIdentity();
        $this->_redirect("/");
    }

}
