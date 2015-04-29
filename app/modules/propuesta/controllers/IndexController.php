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
        $lista_enelaboracion=$listapropuesta->_getPropuestaAllOrdenadoxEstadoPropuesta('EE');
        $lista_ganada=$listapropuesta->_getPropuestaAllOrdenadoxEstadoPropuesta('G');
        $lista_perdida=$listapropuesta->_getPropuestaAllOrdenadoxEstadoPropuesta('P');
        $lista_enviada=$listapropuesta->_getPropuestaAllOrdenadoxEstadoPropuesta('E');
        $lista_declinada=$listapropuesta->_getPropuestaAllOrdenadoxEstadoPropuesta('D');
        $lista_anulada=$listapropuesta->_getPropuestaAllOrdenadoxEstadoPropuesta('A');
      // print_r($lista);
        $this->view->lista_enelaboracion = $lista_enelaboracion; 
        $this->view->lista_ganada = $lista_ganada; 
        $this->view->lista_perdida = $lista_perdida; 
        $this->view->lista_enviada = $lista_enviada; 
        $this->view->lista_declinada = $lista_declinada; 
        $this->view->lista_anulada = $lista_anulada; 
            
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
