<?php
class Rrhh_IndexController extends Zend_Controller_Action {

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


    public function buscaAction() {
        // $this->_helper->redirector('index','index','admin');
        $this->_helper->layout()->disableLayout();
        $busqueda = $this->_getParam('b');
        //echo $busqueda;
        //$busqueda=strtoupper($busqueda);
        //$where=array('busca'=>$busqueda);
        //print_r($where);

        $listapersonas = new Admin_Model_DbTable_Persona();
        $lista=$listapersonas->_getBuscarPersonas($busqueda);
        $this->view->lista_personas=$lista;
        //print_r($lista);
        //$buscar = $_POST['b'];
      
    }


    public function buscaletraAction() {
        $this->_helper->layout()->disableLayout();
        $busqueda = $this->_getParam('inicial');

        //echo $busqueda;
        
        $listapersonas = new Admin_Model_DbTable_Persona();
        $lista=$listapersonas->_getBuscarPersonaXInicial($busqueda);
        $this->view->lista_personas=$lista;

    }

}
