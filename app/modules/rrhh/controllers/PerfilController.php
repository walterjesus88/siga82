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
        $listapersonas = new Admin_Model_DbTable_Persona();
        $this->view->lista_personas = $listapersonas->_getPersonasOrdenadoxApellido();


    }

    public function verAction() {

        $dni = $this->_getParam('dni');

        $where=array('dni'=>$dni);
        $dbper=new Admin_Model_DbTable_Persona();
        $datauser[0]=$dbper->_getOne($where);
        //print_r($datauser[0]);
        $this->view->lista_persona=$datauser[0];

    }

    public function editarAction() {
        $dni = $this->_getParam('dni');


        $form= new Admin_Form_Persona();
        $where=array('dni'=>$dni);
        $dbper=new Admin_Model_DbTable_Persona();
        $datauser=$dbper->_getOne($where);
        
        $this->view->lista_persona=$datauser;
        $this->view->form = $form;
        $form->populate($datauser);

        //print_r($datauser[0]);
        $nombres = $this->_getParam('nombres');
        //echo $nombres;

        if ($this->getRequest()->isPost()) {
            $formdata = $this->getRequest()->getPost();
                    
                    unset($formdata['submit']);

                    $pk = array('dni'    => $dni
                               ); 
                   
                    print_r($formdata);
                    $updperson=new Admin_Model_DbTable_Persona();               
                    if($updperson->_updateX($formdata,$pk))
                    {   ?>
                        <script>                  
                          document.location.href="/rrhh/perfil/listar";
                          
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

        // $lname = $this->_getParam('l-name');
        // echo $lname;

        //$this->view->lista_personas = $listapersonas->_getPersonasOrdenadoxApellido();

    }
}