<?php
class Rendiciongastos_GastosController extends Zend_Controller_Action {

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

  public function gastosAction()
  {

    // $uid = $this->sesion->uid;
    // $dni = $this->sesion->dni;
    // $where = array();
    // $where['uid'] = $uid;
    // $where['dni'] = $dni;
    // $where['estado'] = 'B';
    // $rendicion = new Admin_Model_DbTable_Gastorendicion();
    // $data_rendicion = $rendicion->_getAllXuidXestado($where);

    // $this->_helper->json->sendJson($data_rendicion);

    $where = array('estado' =>'B' , );
    $gastos= new Admin_Model_DbTable_Gastorendicion();
    $array=$gastos->_getFilter($where);
    $this->_helper->json->sendJson($array);

  }

  public function gastosxestadoAction()
  {

    // $estado_gasto = $this->_getParam('estado');
    // $where = array('estado' =>$estado_gasto , );
    // $gastos= new Admin_Model_DbTable_Gastorendicion();
    // $array=$gastos->GastoxEstado($estado_gasto);
    // $this->_helper->json->sendJson($array);


    $uid = $this->sesion->uid;
    $dni = $this->sesion->dni;
    $estado_gasto = $this->_getParam('estado');
    $where = array();
    $where['uid'] = $uid;
    $where['dni'] = $dni;
    $where['estado'] = $estado_gasto;
    $rendicion = new Admin_Model_DbTable_Gastorendicion();
    $data_rendicion = $rendicion->_getAllXuidXestado($where);
    $this->_helper->json->sendJson($data_rendicion);

  }


}