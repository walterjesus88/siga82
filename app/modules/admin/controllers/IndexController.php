<?php
class Admin_IndexController extends Zend_Controller_Action {

    public function init() {
                 $options = array(
            'layout' => 'inicio',
        );
        Zend_Layout::startMvc($options);

    }
    
    public function indexAction() {
       
   	}

   	public function activarproyectosAction() {
   		$this->_helper->layout()->disableLayout();
        $buscaproyecto = new Admin_Model_DbTable_Proyecto();
        $listar_proyectos=$buscaproyecto->_buscarProyectoxReplicon($buscar_proyecto);

        
        $this->view->lista_buscar = $buscar;
       
   	}

    public function aclAction() {
      $this->_helper->layout()->disableLayout();
             
    }
   
    
}

