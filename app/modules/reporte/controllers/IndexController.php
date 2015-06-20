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
        $todos_tareopersona = $tareopersona->_getTareopersonall();
        $respuesta = [];
        $i = 0;
        foreach ($todos_tareopersona as $fila) {
            $proyecto = new Admin_Model_DbTable_Proyecto();
            $pro = $proyecto->_show($fila['codigo_prop_proy']);
            $fila['nombre_proyecto'] = $pro['nombre_proyecto'];
            $equipo = new Admin_Model_DbTable_Equipo();
            $eqp = $equipo->_getRatexcppxpidxuid($fila['codigo_prop_proy'], $fila['proyectoid'], $fila['uid']);
            $fila['rate'] = $eqp['rate_proyecto'];
            if ($fila['tipo_actividad'] == 'P') {
                $fila['tipo_actividad'] = 'Facturable';
            } elseif ($fila['tipo_actividad'] == 'G') {
                $fila['tipo_actividad'] = 'No Facturable';
            } elseif ($fila['tipo_actividad'] == 'A') {
                $fila['tipo_actividad'] = 'Administración';
            }
            
            $respuesta[$i] = $fila;
            $i++;
        }
        $this->_helper->json->sendJson($respuesta);      
    }

    public function usuariosAction() {
        $this->_helper->layout()->disableLayout();
        $tareopersona = new Admin_Model_DbTable_Tareopersona();
        $todos_tareopersona = $tareopersona->_getTareopersonall();
        $respuesta = [];
        $i = 0;
        foreach ($todos_tareopersona as $fila) {
            $usuario['usuario'] = $fila['uid'];
            $respuesta[$i] = $usuario;
            $i++;
        }
        $this->_helper->json->sendJson($respuesta);
    }

}