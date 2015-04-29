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

    public function cambiarAction() {
        try {
            $codigo=$this->_getParam('codigo');
            $propuestaid=$this->_getParam('propuestaid');
            $revision=$this->_getParam('revision');
            $estado=$this->_getParam('estado');
            $updatepropuesta = new Admin_Model_DbTable_Propuesta();
            $data["estado_propuesta"]=$estado;
            $str="codigo_prop_proy='$codigo' and propuestaid='$propuestaid' and revision='$revision'";
            $update=$updatepropuesta -> _update($data,$str); 
            if($update)
            {   ?>
                <script>                  
                    document.location.href="/propuesta/index/listar";
                </script>
                <?php
            }
            else
            {   ?>
                <script>                  
                    alert("Error al Cambiar estado verifique porfavor");
                    document.location.href="/propuesta/index/listar";                                                 
                </script>
                <?php
            } 
        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        }    
    }  
	
    public function buscarAction() {
        $this->_helper->layout()->disableLayout();
        $buscar_propuesta=$this->_getParam('propuesta');
        $buscar_propuesta=strtolower($buscar_propuesta);
        $buscapropuesta = new Admin_Model_DbTable_Propuesta();
        $buscar=$buscapropuesta->_buscarPropuesta($buscar_propuesta);
        //print_r($buscar);
        $this->view->lista_buscar = $buscar; 
            
    }  

    public function nuevoAction() {
        $buscapropuesta = new Admin_Model_DbTable_Propuesta();
        //$busca=$buscapropuesta->_getPropuestaxIndices($codigo,$propuestaid,$revision);
        //print_r($listapropuesta);
        //$this->view->buscapropuesta = $busca; 
            
    }  
    
}
