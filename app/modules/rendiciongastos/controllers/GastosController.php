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

      public function guardarrendicionAction()
    {

      // print_r($pk);
      // print_r($garea);

      // exit();
      //   $ceros = '10 - ';
      //       for ($h=0; $h < (8-strlen($result['numero'])); $h++) {
      //           $ceros = $ceros.'0';
      //       }

        // $formdata['uid']=$uid = $this->sesion->uid;
        // $formdata['dni']=$dni = $this->sesion->dni;
      //   // $formdata['numero_completo']= $this->_getParam('numero_completo');
      //   $formdata['numero_completo'] = $ceros.$result['numero'];
        // $formdata['nombre']=$nombre = $this->_getParam("nombre");
        // $formdata['fecha']=$fecha = $this->_getParam("fecha");
        // $formdata['estado']=$estado = $this->_getParam("estado");

      //   print_r($numero_completo);
        // $guardarrendicion=new Admin_Model_DbTable_Gastorendicion();
        // $grendicion=$guardarrendicion->_save($formdata);

      //   print_r($numero_completo);

      // exit();

      // $this->_helper->json->sendJson($grendicion);


            // $uid = $this->sesion->uid;
            // $dni = $this->sesion->dni;

            $where = array();
            $where['fecha'] =$fecha = $this->_getParam("fecha");
            $where['uid'] = $uid = $this->sesion->uid;
            $where['dni'] = $dni = $this->sesion->dni;
            $where['estado'] = $estado = $this->_getParam("estado");
            $where['nombre'] = $nombre = $this->_getParam("nombre");
            $rendicion = new Admin_Model_DbTable_Gastorendicion();
            $result = $rendicion->_save($where);
            $ceros = '10 - ';
            for ($h=0; $h < (8-strlen($result['numero'])); $h++) { 
                $ceros = $ceros.'0';
            }
            $data2['numero_completo'] = $ceros.$result['numero'];
            $rendicion->_update($data2,$result);
            // $this->view->numero = $result['numero'];

            $this->_helper->json->sendJson($result['numero']);



    }


}