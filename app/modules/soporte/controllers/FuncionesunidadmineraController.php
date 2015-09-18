<?php

class Soporte_FuncionesunidadmineraController extends Zend_Controller_Action {

    public function init()
    {
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


    public function llamarunidadmineraAction()
    {
      // $isproyecto = $this->_getParam('isproyecto');

      // $where = array('isproyecto' =>$isproyecto , );
      $unidadminera= new Admin_Model_DbTable_Unidadminera();
      // $a=$unidadminera->_getFilter($where);
      $a=$unidadminera->_getUnidadmineraAll();

      $this->_helper->json->sendJson($a);

    }


    public function guardarunidadmineraAction()
    {

        $formdata['unidad_mineraid']=$unidad_mineraid = $this->_getParam('unidad_mineraid');
        $formdata['clienteid']=$clienteid = $this->_getParam("clienteid");
        $formdata['nombre']=$nombre = $this->_getParam("nombre");
        $formdata['estado']=$estado = $this->_getParam("estado");
        $formdata['direccion']=$direccion = $this->_getParam("direccion");
        $formdata['paisid']=$paisid = $this->_getParam("paisid");
        $formdata['departamentoid']=$departamentoid = $this->_getParam("departamentoid");
        $formdata['distritoid']=$distritoid = $this->_getParam("distritoid");
        $formdata['tag']=$tag = $this->_getParam("tag");
        $formdata['isunidadminera']=$isunidadminera = $this->_getParam("isunidadminera");

        $guardarunidadminera=new Admin_Model_DbTable_Unidadminera();
        $gunidadminera=$guardarunidadminera->_save($formdata);

// print_r($gunidadminera);
// exit();

      $this->_helper->json->sendJson($gunidadminera);


    }

    public function modificarunidadmineraAction()
    {

        $unidad_mineraid = $this->_getParam('unidad_mineraid');
        $formdata['clienteid']=$clienteid = $this->_getParam("clienteid");
        $formdata['nombre']=$nombre = $this->_getParam("nombre");
        $formdata['estado']=$estado = $this->_getParam("estado");
        $formdata['direccion']=$direccion = $this->_getParam("direccion");
        $formdata['paisid']=$paisid = $this->_getParam("paisid");
        $formdata['departamentoid']=$departamentoid = $this->_getParam("departamentoid");
        $formdata['distritoid']=$distritoid = $this->_getParam("distritoid");
        $formdata['tag']=$tag = $this->_getParam("tag");
        $formdata['isunidadminera']=$isunidadminera = $this->_getParam("isunidadminera");

        print_r($nombre);

        $guardarunidadminera=new Admin_Model_DbTable_Unidadminera();
        $gunidadminera=$guardarunidadminera->_updateunidadminera($formdata, $unidad_mineraid);

        print_r($gunidadminera);

      $this->_helper->json->sendJson($gunidadminera);


    }
}