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
    

    /*Accion que devuelve la vista principal contenida el el archivo
    ../views/scripts/index/index.phtml*/
    public function indexAction() {
        
    }

    /*Funcion que devuelde los registros con los campos necesarios para visualizacion
    de la vista de reporte tarea persona. Para lo cual han sido parseados como json
    */

    public function tareopersonaAction() {
        $this->_helper->layout()->disableLayout();
        $tareopersona = new Admin_Model_DbTable_Tareopersona();

        {
            $data['uid'] = $this->_getParam('uid');
            $data['dni'] = $this->_getParam('dni');
            $todos_tareopersona = $tareopersona->_getReporte($data);
        }

        $respuesta = [];
        $i = 0;
              
        foreach ($todos_tareopersona as $fila) {
           if ($fila['tipo_actividad']=='P') {
               $fila['tipo_actividad'] = 'Facturable';
           } elseif ($fila['tipo_actividad']=='G') {
               $fila['tipo_actividad'] = 'No Facturable';
           } elseif ($fila['tipo_actividad']=='A') {
               $fila['tipo_actividad'] = 'Administración';
           }

           if ($fila['estado']=='A') {
               $fila['estado'] = 'Activo';
           } elseif ($fila['estado']=='C') {
               $fila['estado'] = 'Cerrado';
           } elseif ($fila['estado']=='E') {
               $fila['estado'] = 'Eliminado';
           } elseif ($fila['estado']=='PA') {
               $fila['estado'] = 'Paralizado';
           } elseif ($fila['estado']=='CA') {
               $fila['estado'] = 'Cancelado';
           }

           $respuesta[$i] = $fila;
           $i++;
           
        }
        
        $this->_helper->json->sendJson($respuesta);      
    }

    public function usuariosAction() {
        $this->_helper->layout()->disableLayout();
        $tareopersona = new Admin_Model_DbTable_Tareopersona();
        $usuarios = $tareopersona->_getUsuarios();
        $this->_helper->json->sendJson($usuarios);
    }

    public function clientesAction(){
        $this->_helper->layout()->disableLayout();
        $cliente = new Admin_Model_DbTable_Cliente();
        $clientes = $cliente->_getClienteAllOrdenado();
        $respuesta = [];
        $i = 0;
        foreach ($clientes as $fila) {
            $filares['id'] = $fila['clienteid'];
            $filares['nombre'] = $fila['nombre_comercial'];
            $respuesta[$i] = $filares;
            $i++; 
        }
        $this->_helper->json->sendJson($respuesta);
    }

    public function unidadmineraAction(){
        $this->_helper->layout()->disableLayout();
        $clienteid = $this->_getParam('clienteid');
        $uni_min = new Admin_Model_DbTable_Unidadminera();
        $uni_mins = $uni_min->_getUnidadmineraxcliente($clienteid);
        $respuesta = [];
        $i = 0;
        foreach ($uni_mins as $fila) {
            $filares['id'] = $fila['unidad_mineraid'];
            $filares['nombre'] = $fila['nombre'];
            $respuesta[$i] = $filares;
            $i++; 
        }
        $this->_helper->json->sendJson($respuesta);
    }

}