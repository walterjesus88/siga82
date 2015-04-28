<?php

class Propuesta_IndexController extends Zend_Controller_Action {

    public function init() {
    	$options = array(
            'layout' => 'layout',
        );
        Zend_Layout::startMvc($options);

    }
    
    public function indexAction() {
        echo "waaaaaa";
            
    }

    public function listarAction() {

         $listapropuesta = new Admin_Model_DbTable_Propuesta();
        $lista=$listapropuesta->_getPropuestaAll();
       //print_r($listapropuesta);
        $this->view->listapropuesta = $lista; 
            
    }

    public function verAction() {
        $codigo=$this->_getParam('codigo');
        $propuestaid=$this->_getParam('propuestaid');
        $revision=$this->_getParam('revision');

        $buscapropuesta = new Admin_Model_DbTable_Propuesta();
        $busca=$buscapropuesta->_getPropuestaxIndices($codigo,$propuestaid,$revision);
        //print_r($listapropuesta);
        $this->view->buscapropuesta = $busca; 
            
    }  
	
    public function nuevoAction() {
        $buscapropuesta = new Admin_Model_DbTable_Propuesta();
        //$busca=$buscapropuesta->_getPropuestaxIndices($codigo,$propuestaid,$revision);
        //print_r($listapropuesta);
        //$this->view->buscapropuesta = $busca; 
            
    }  
    
}
