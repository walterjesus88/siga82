<?php

class Control_FuncionesController extends Zend_Controller_Action {

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


    public function llamarareasAction()
    {
      $isproyecto = $this->_getParam('isproyecto');

      $where = array('isproyecto' =>$isproyecto , );
      $areas= new Admin_Model_DbTable_Area();
      $a=$areas->_getFilter($where);



      $this->_helper->json->sendJson($a);

    }


    public function guardarareaAction()
    {
      $areaid = $this->_getParam('areaid');
      $nombre = $this->_getParam('nombre');

      $where = array('nombre' =>$nombre , );
      $pk = array('areaid' =>$areaid , );
      $guardararea= new Admin_Model_DbTable_Area();
      $garea=$guardararea->_update_pk($where,$pk);

      // print_r($where);
      // print_r($pk);
      // print_r($garea);

      // exit();

      $this->_helper->json->sendJson($garea);

    }


    public function eliminareaAction()
    {
      $areaid = $this->_getParam('areaid');

      ///$where = array('nombre' =>$nombre , );
      $pk = array('areaid' =>$areaid , );
      $eliminararea= new Admin_Model_DbTable_Area();
      $earea=$eliminararea->_delete($pk);

      // print_r($where);
      // print_r($pk);
      // print_r($garea);

      // exit();

      $this->_helper->json->sendJson($earea);

    }


 



}