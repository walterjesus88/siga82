<?php
class Rrhh_IndexController extends Zend_Controller_Action {

    public function init() {
    	$options = array(
            'layout' => 'layout',
        );
        Zend_Layout::startMvc($options);

    }
    
    public function indexAction() {
            
    }

    public function listarAction() {
        $listapersonas = new Admin_Model_DbTable_Persona();
        $this->view->lista_personas = $listapersonas->_getPersonasOrdenadoxApellido();
    }

    public function buscaAction() {
        $this->_helper->layout()->disableLayout();
        $busqueda = $this->_getParam('b');
        $busqueda=strtoupper($busqueda);
        $busqueda = strtoupper($busqueda);
        $listapersonas = new Admin_Model_DbTable_Persona();
        $lista=$listapersonas->_getBuscarPersonas($busqueda);
        $this->view->lista_personas=$lista;
    }


    public function buscaletraAction() {
        $this->_helper->layout()->disableLayout();
        $busqueda = $this->_getParam('inicial');
        $listapersonas = new Admin_Model_DbTable_Persona();
        $lista=$listapersonas->_getBuscarPersonaXInicial($busqueda);
        $this->view->lista_personas=$lista;

    }

    public function asignarpersonaAction() {
        $lista=$listaproyecto->_getProyectosTodosAnddes();
        $this->view->listaproyecto = $lista;
        print_r($lista);

    }
}
