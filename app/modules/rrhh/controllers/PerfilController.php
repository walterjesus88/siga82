<?php
class Rrhh_PerfilController extends Zend_Controller_Action {

    public function init() {
    	$options = array(
            'layout' => 'layout',
        );
        Zend_Layout::startMvc($options);

    }
    
    public function indexAction() {
        // $this->_helper->redirector('index','index','admin');
            
    }

    public function listarAction() {
        // $listapersonas = new Admin_Model_DbTable_Persona();
        // $this->view->lista_personas = $listapersonas->_getPersonasOrdenadoxApellido();


    }

    public function verAction() {

        // $dni = $this->_getParam('dni');

        // $where=array('dni'=>$dni);
        // $dbper=new Admin_Model_DbTable_Persona();
        // $datauser[0]=$dbper->_getOne($where);
        // //print_r($datauser[0]);
        // $this->view->lista_persona=$datauser[0];

    }

    public function editarAction() {
        //$dni = $this->_getParam('dni');
        // $form= new Admin_Form_Persona();
        // $where=array('dni'=>$dni);
        // $dbper=new Admin_Model_DbTable_Persona();
        // $datauser=$dbper->_getOne($where);
        
        // $this->view->lista_persona=$datauser;
        // $this->view->form = $form;
        // $form->populate($datauser);

        //print_r($datauser[0]);
        //$nombres = $this->_getParam('nombres');
        //echo $nombres;
        // if ($this->getRequest()->isPost()) {
        //     $formdata = $this->getRequest()->getPost();                    
        //             unset($formdata['submit']);
        //             $pk = array('dni'    => $dni   );                   
        //             print_r($formdata);
        //             $updperson=new Admin_Model_DbTable_Persona();               
        //             if($updperson->_updateX($formdata,$pk))
        //             {   ?>
        //                 <script> 
        //                   document.location.href="/rrhh/perfil/ver/dni/<?php echo $dni ?>";
        //                 </script>
        //                 <?php
        //             }
        //             else
        //             {   ?>
        //                   <script>                  
        //                   alert("Error al guardar verifique porfavor");                                                 
        //                   </script>
        //                  <?php
        //             } 
        // }

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
                                        document.location.href="/rrhh/perfil/curriculum/dni/<?php echo $dni?>";                                                            
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
                     $pk = array('dni'    => $dni   );                                     
                     print_r($formdata);
                     $updperson=new Admin_Model_DbTable_Persona();               
                     if($updperson->_updateX($formdata,$pk))
                     {   ?>
                         <script>
                            alert('guardado');
                           //document.location.href="/rrhh/perfil/ver/dni/<?php echo $dni ?>";                          
                         </script>
                         <?php
                     }
                     else
                     {   ?>
                           <script>                  
                           alert("Error al guardar verifique porfavor");                                                 
                           </script>
                          <?php
                     } 
        }
        /*----------------*/
    }



}