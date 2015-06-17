<?php

class Reporte_IndexController extends Zend_Controller_Action {

    public function init() {
        $sesion  = Zend_Auth::getInstance();
        if(!$sesion->hasIdentity()) {
            $this->_helper->redirector('index',"index",'default');
        }
        $login = $sesion->getStorage()->read();
        $this->sesion = $login; 
        $options = array(
            'layout' => 'layout',
        );
        Zend_Layout::startMvc($options);
    }
    
    public function indexAction() {
        
    }

    /*Funcion que devuelde los registros con los campos necesarios para visualozacion
    de la vista de reporte tarea persona. Para lo cual han sido parseados como json
    */

    public function tareopersonaAction() {
        $this->_helper->layout()->disableLayout();
        $tareopersona = new Admin_Model_DbTable_Tareopersona();
        $todos_tareopersona = $tareopersona->_getTareopersonall();
        $respuesta = [];
        $i = 0;
        foreach ($todos_tareopersona as $fila) {
            $proyecto = new Admin_Model_DbTable_Proyecto();
            $pro = $proyecto->_show($fila['codigo_prop_proy']);
            $fila['nombre_proyecto'] = $pro['nombre_proyecto'];
            $respuesta[$i] = $fila;
            $i++;
        }
        print_r(json_encode($respuesta));      
    }

    public function proyectoAction() {

    }

}