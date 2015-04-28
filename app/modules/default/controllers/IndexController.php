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
                        // // Iniciamos la carga de Periodos para la plataforma
                        // $per = new Admin_Model_DbTable_IdiomasPeriodo();
                        // $t = $per->_getPeriodoactivo($eid,'A');
                        
                        // if ($t){
                        //     $periodo= $t['perid'];
                        //     $nombre_periodo = $t['nombre'];
                        // }else{
                        //         $msg = "Error en la seccion del periodo siguiente para la plataforma";
                        //         $this->_redirect("/error/msg/msg/'$msg'");
                        // }
            
                        // // Registramos la sesion para el Objeto con los parametros minimos
                        // // Selecionamos los campos necesarios de USUARIO para llevarlos a una SESION
                        $data  = $authAdapter->getResultRowObject(array('dni','estado','rid','categoriaid','nivel'));

                        // $data->periodo = $periodo;
                        // $data->nombre_periodo = $nombre_periodo;

                        // Seleccionamos y seteamos los datos de sesion de una persona
                        $persona = new Admin_Model_DbTable_Persona();
                        $rp = $persona->_getPersona($data->dni);
                        if ($rp){
                            $data->personal=$rp['ape_paterno']." ".$rp['ape_materno'].", ".$rp['nombres'];
                            $data->sexo= $rp['sexo']; 
                            $data->alias= $rp['alias']; 
                                                   
                        }
                        //$data->dni = $data->pid;
                        // $data->modulo=''
                        $auth->getStorage()->write($data);
                        
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
                            $this->_helper->redirector('index','index','proyecto');
                        }
                        elseif ($data->rid=='PROPUESTA') {
                            $this->_helper->redirector('index','index','propuesta');
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
    
  

}
