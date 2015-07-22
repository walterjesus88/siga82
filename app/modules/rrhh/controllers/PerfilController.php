<?php
class Rrhh_PerfilController extends Zend_Controller_Action {

    public function init() {
    	$options = array(
            'layout' => 'inicio',
        );
        Zend_Layout::startMvc($options);
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity()) { 
          $sesion = $auth->getStorage()->read();
          $this->sesion=$sesion;
         // print_r($sesion);
        }
    }
    
    public function indexAction() {
        // $this->_helper->redirector('index','index','admin');
    }

    public function listarAction() {
        // $listapersonas = new Admin_Model_DbTable_Persona();
        // $this->view->lista_personas = $listapersonas->_getPersonasOrdenadoxApellido();
    }

    public function verAction() {

    }

    public function editarAction() {
        $dni = $this->_getParam('dni');
        $form= new Admin_Form_Persona();
        $where=array('dni'=>$dni);
        $dbper=new Admin_Model_DbTable_Persona();
        $datauser=$dbper->_getOne($where);
        
        $this->view->lista_persona=$datauser;
        $this->view->form = $form;
        echo "fffffffffffffff";

        $nombres = $this->_getParam('nombres');
        echo $nombres;
        if ($this->getRequest()->isPost()) {
            $formdata = $this->getRequest()->getPost();                    
                    unset($formdata['submit']);
                    $pk = array('dni'    => $dni   );                   
                    print_r($formdata);
                    $updperson=new Admin_Model_DbTable_Persona();               
                    $updperson->_updateX($formdata,$pk);
                    ?>
                    <script>
                    alert('dddd');
                    </script>
                    <?php
      
        }

    }


    public function cambiarpassAction(){

        $dni = $this->_getParam('dni');
        //echo $dni;

        $dbper=new Admin_Model_DbTable_Persona();
        $where=array('dni'=>$dni);
        $datauser=$dbper->_getOne($where);        
        $this->view->lista_persona=$datauser;
        $veruser=new Admin_Model_DbTable_Usuario();       
        $user=$veruser->_getOne($where);
        //echo $user['uid'];
        if($user)
        {
            $uid=$user['uid'];
            
                if ($this->getRequest()->isPost())
                {
                    $formdata = $this->getRequest()->getPost();
                    //print_r($formdata);
                    if($formdata['newpassword']==$formdata['renewpassword'])
                    {                            
                            $formdata['password']=$formdata['newpassword'];
                            unset($formdata['renewpassword']);
                            unset($formdata['newpassword']);
                            //print_r($formdata);
                            $pku = array('dni' => $dni  ,'uid' => $uid );
                            $updusuario = new Admin_Model_DbTable_Usuario();
                                if($updusuario->_updateX($formdata,$pku))
                                {                              
                                    //echo "La contrasena ha sido cambiada correctamente";  
                                    ?>
                                    <script>
                                        document.location.href="/rrhh/perfil/curriculum/dni/<?php echo $dni?>#password";                                                            
                                    </script>
                                    <?php
                                }
                                else
                                { 
                                    echo "hubo un error intentelo otra vez";
                                }
                    }
                    else
                    {
                        echo "contrasenas no coinciden";
                    }
                }
        }
        else
        {
            echo "no tiene usuario";
        }
    

    }


    public function curriculumAction(){

        $dn=$this->sesion->dni;


        $dni = $this->_getParam('dni');        
        $dbper=new Admin_Model_DbTable_Persona();
        $where=array('dni'=>$dni);
        $datauser=$dbper->_getOne($where);        
        $this->view->lista_persona=$datauser;

        $dbcurriculum=new Admin_Model_DbTable_Curriculum();
        $datacurriculum=$dbcurriculum->_getOne($where); 
        $this->view->lista_curriculum=$datacurriculum;

        $dbpuesto=new Admin_Model_DbTable_Puesto();
        $wherepuesto = array( 'dnipersona' => $dni, 'estado' => 'A');
        if($datapuesto=$dbpuesto->_getFilter($wherepuesto))
        {
            $this->view->lista_puesto=$datapuesto;
            $dbcomp = new Admin_Model_DbTable_Competencia();
            $wherecomp=  array('puesto' => $datapuesto[0]['puestoid'], 'estado' => 'A' );
            $dbcompetencia= $dbcomp->_getFilter($wherecomp);
            $this->view->lista_competencia=$dbcompetencia;

            $dbfun = new Admin_Model_DbTable_Funcion();
            $wherefun=  array('puesto' => $datapuesto[0]['puestoid'] );            
            $dbfuncion= $dbfun->_getFilter($wherefun);
            $this->view->lista_funcion=$dbfuncion;           
        }

        $dbevaluacion=new Admin_Model_DbTable_Evaluacion();
        $dataevaluacion=$dbevaluacion->_getFilter($where); 
        $this->view->lista_evaluacion=$dataevaluacion;
      

        $dbcapacitacion=new Admin_Model_DbTable_Capacitacion();
        $datacapacitacion=$dbcapacitacion->_getFilter($where); 
        $this->view->lista_capacitacion=$datacapacitacion;



        //--------------*esto es guardar------------//
        $dni = $this->_getParam('dni');
        $form= new Admin_Form_Persona();
        $where=array('dni'=>$dni);
        $dbper=new Admin_Model_DbTable_Persona();
        $datauser=$dbper->_getOne($where);
        
        $this->view->lista_persona=$datauser;
        $this->view->form = $form;
        $form->populate($datauser);

        if ($this->getRequest()->isPost()) {
             $formdata = $this->getRequest()->getPost();                    
                    unset($formdata['submit']);
                    $formdata['uid_registro']=$dn;
                    $formdata['fecha_registro']=date('Y-m-d H:m:s');;

                     $pk = array('dni'    => $dni   );                                     
                     //print_r($formdata);
                     $updperson=new Admin_Model_DbTable_Persona();               
                     if($updperson->_updateX($formdata,$pk))
                     { ?>
                        <script type="text/javascript">
                            document.location.href="/rrhh/perfil/curriculum/dni/<?php echo $dni?>";                                                            
                            
                        </script>
                     <?php } 
            
        }
        /*----------------*/
    }

public function nuevoAction() {
    try
    {
        $this->_helper->layout()->disableLayout(); 
        $tabla_aprobaciones = new Admin_Model_DbTable_Aprobacion();
        $aprobadores=$tabla_aprobaciones->_getTodosAprobadores();
        $this->view->aprobadores=$aprobadores;
        $tabla_area = new Admin_Model_DbTable_Area();
        $area=$tabla_area->_getAreaxProyecto();
        $this->view->area=$area;
        $tabla_cat = new Admin_Model_DbTable_Categoria();
        $categoria=$tabla_cat->_getCategoriaOrdenado();
        $this->view->categoria=$categoria;
        $tabla_aprobador = new Admin_Model_DbTable_Aprobacion();
        $aprobador=$tabla_aprobador->_getAprobadoresOrdenado();
        $this->view->aprobador=$aprobador;
        
    }catch (Exception $e) {
        print "Error: ".$e->getMessage();
    }
}

public function guardarpersonaAction() {
    try
    {
        $this->_helper->layout()->disableLayout(); 
        $uid = $this->sesion->uid;
        $formdata['dni']=$dni = $this->_getParam('dni');
        $formdata['ape_paterno']=$ape_paterno = $this->_getParam("ape_paterno");
        $formdata['ape_materno']=$ape_materno = $this->_getParam("ape_materno");   
        $formdata['nombres']=$nombre1 = $this->_getParam("nombre1");   
        $formdata['segundo_nombre']=$nombre2 = $this->_getParam("nombre2");   
        $formdata['tercer_nombre']=$nombre3 = $this->_getParam("nombre3");   
        $formdata['sexo']=$sexo = $this->_getParam("sexo");   
        $formdata['alias']=$alias = $this->_getParam("alias");   
        $formdata['email_anddes']=$email_anddes = $this->_getParam("email_anddes");   
        $formdata['email_personal']=$email_personal = $this->_getParam("email_personal");   
        $formdata['fecha_nacimiento']=date("Y-m-d");
        $formdata['fecha_registro']=date("Y-m-d");
        $formdata['fecha_modificacion']=date("Y-m-d");
        $formdata['uid_registro']=$uid;
        $formdata['uid_modificacion']=$uid;
        $formdata['estado']='A';
        $formdata['condicion']='P';
        $formdata['iscontacto']='N';
        $formdata['isanddes']='S';
        $tabla_persona=new Admin_Model_DbTable_Persona();    
        if($tabla_persona->_save($formdata))
        {
            echo "Archivo Guardado";
        }
        else
        {
            echo "Verifique Datos";   
        }
    }catch (Exception $e) {
        print "Error: ".$e->getMessage();
    }
}

public function guardarusuarioAction() {
    try
    {
        $this->_helper->layout()->disableLayout(); 
        $uid = $this->sesion->uid;
        $formdata['dni']=$dni = $this->_getParam('dni');
        $formdata['uid']=$uni = $this->_getParam('uid');
        $formdata['password']=$password = $this->_getParam('dni');
        $formdata['rid']=$rid = $this->_getParam('rid');
        $formdata['areaid']=$area = $this->_getParam('area');
        $formdata['categoriaid']=$categoria = $this->_getParam('categoria');
        $formdata['nivel']=$nivel = $this->_getParam('nivel');
        $formdata['fecha_registro']=date("Y-m-d");
        $formdata['fecha_modificacion']=date("Y-m-d");
        $formdata['uid_registro']=$uid;
        $formdata['uid_modificacion']=$uid;
        $formdata['estado']='A';
        
        $tabla_usuario=new Admin_Model_DbTable_Usuario();    
        if($tabla_usuario->_save($formdata))
        {
            echo "Archivo Guardado";

            $formdata_usuario['dni']=$dni = $this->_getParam('dni');
            $formdata_usuario['uid']=$uni = $this->_getParam('uid');
            $formdata_usuario['areaid']=$area = $this->_getParam('area');
            $formdata_usuario['categoriaid']=$categoria = $this->_getParam('categoria');
            $nivel = $this->_getParam('nivel');
            if ($nivel=='0')
            {
                $formdata_usuario['cargo']='GERENTE';    
            }
            if ($nivel=='4')
            {
                $formdata_usuario['cargo']='EQUIPO';    
            }
            $formdata_usuario['aprobacion']=$aprobador = $this->_getParam('aprobador');
            $formdata_usuario['fecha_creacion']=date("Y-m-d");
            $formdata_usuario['fecha_modificacion']=date("Y-m-d");
            $formdata_usuario['uid_modificacion']=$uid;
            $formdata_usuario['estado_sistema']='A';    
            $formdata_usuario['estado']='A'; 
            $tabla_usuariocat=new Admin_Model_DbTable_Usuariocategoria();    
            if($tabla_usuariocat->_save($formdata_usuario))
            {
                echo "Guardado todo";
            }
            else
            {
                echo "error al guardar";
            }

        }
        else
        {
            echo "Verifique Datos";   
        }
    }catch (Exception $e) {
        print "Error: ".$e->getMessage();
    }
}

}