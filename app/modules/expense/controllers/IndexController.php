<?php

class Expense_IndexController extends Zend_Controller_Action {

    public function init() {
    	$options = array(
            'layout' => 'layout',
        );
        Zend_Layout::startMvc($options);
    }
    
    public function indexAction() {
        
   
    }
	
    public function listarAction() {
        /*$listaproyecto = new Admin_Model_DbTable_Proyecto();
        $lista=$listaproyecto->_getProyectoAll();
        $this->view->listaproyecto = $lista;*/            
    }


    public function nuevoAction() {
        
    }
   

    public function editarAction() {
      
    }



    public function buscarAction() {      
       
    }  

    public function deleteAction(){

    }

    
}
