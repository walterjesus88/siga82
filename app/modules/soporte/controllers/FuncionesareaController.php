<?php

class Soporte_FuncionesareaController extends Zend_Controller_Action {

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


    public function llamarareaAction()
    {
      // $isproyecto = $this->_getParam('isproyecto');

      // $where = array('isproyecto' =>$isproyecto , );
      $areas= new Admin_Model_DbTable_Area();
      // $a=$areas->_getFilter($where);
      $a=$areas->_getAreaAll();

      $this->_helper->json->sendJson($a);

    }


    public function guardarareaAction()
    {

      // print_r($pk);
      // print_r($garea);

      // exit();

        $formdata['areaid']=$areaid = $this->_getParam('areaid');
        $formdata['nombre']=$nombre = $this->_getParam("nombre");
        $formdata['area_padre']=$area_padre = $this->_getParam("area_padre");
        $formdata['isproyecto']=$isproyecto = $this->_getParam("isproyecto");
        $formdata['ispropuesta']=$ispropuesta = $this->_getParam("ispropuesta");
        $formdata['iscontacto']=$iscontacto = $this->_getParam("iscontacto");
        $formdata['iscomercial']=$iscomercial = $this->_getParam("iscomercial");
        $formdata['orden']=$orden = $this->_getParam("orden");
        print_r($nombre);
        $guardararea=new Admin_Model_DbTable_Area();
        $garea=$guardararea->_save($formdata);

        print_r($nombre);
        
      exit();

      $this->_helper->json->sendJson($garea);


    }

    public function modificarareaAction()
    {

      // print_r($pk);
      // print_r($garea);

      // exit();

        $areaid = $this->_getParam('areaid');
        $formdata['nombre']=$nombre = $this->_getParam("nombre");
        $formdata['area_padre']=$area_padre = $this->_getParam("area_padre");
        $formdata['isproyecto']=$isproyecto = $this->_getParam("isproyecto");
        $formdata['ispropuesta']=$ispropuesta = $this->_getParam("ispropuesta");
        $formdata['iscontacto']=$iscontacto = $this->_getParam("iscontacto");
        $formdata['iscomercial']=$iscomercial = $this->_getParam("iscomercial");
        $formdata['orden']=$orden = $this->_getParam("orden");
        print_r($nombre);
        $guardararea=new Admin_Model_DbTable_Area();
        $garea=$guardararea->_updatearea($formdata, $areaid);

        print_r($nombre);
        alert($nombre);
      exit();

      $this->_helper->json->sendJson($garea);


    }
}