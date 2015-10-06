<?php

class Control_PrintController extends Zend_Controller_Action {

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

    public function imprimirentregablesAction()
    {
     //  $revision = $this->_getParam('revision');
     //  $data = $entregable->_getFilter($where);
     //  $cabecera['revision_entregable'] = $revision;

     // // print_r($data);exit();

     //  $formato = new Admin_Model_DbTable_Formato();
     //  $formato->_setFormato('proyectos');
     //  $formato->_setCabecera($cabecera);
     //  $formato->_setData($data);
     //  $respuesta = $formato->_print();
     //  $this->_helper->json->sendJson($respuesta);
     // echo "llegoooooooo";

      $estado = 'A';
      $revision_entregable='A';
      //$this->_getParam('revision');
      //$proyecto = new Admin_Model_DbTable_Proyecto();
      //$data = $proyecto->_getAllExtendido($estado);

      $entregable = new Admin_Model_DbTable_Listaentregabledetalle();   
      $data=$entregable->_getFilteristaentregable('1508.10.01',$revision_entregable);

      //print_r($data);exit();

      $cabecera['estado'] = $estado;
      $formato = new Admin_Model_DbTable_Formatocp('lista_entregable', $cabecera, $data);
      $respuesta = $formato->_print();
     // print_r($respuesta);exit();
      $this->_helper->json->sendJson($respuesta);


      // $formato = new Admin_Model_DbTable_Formatocp();
      // $formato->_setFormato('proyectos');
      // $formato->_setCabecera($cabecera);
      // $formato->_setData($data);


    //   $respuesta = $formato->_print();
      
    //   if($respuesta)
    //   {
    //     //////print_r($respuesta);
    //   }
    //   else
    // {
    //     echo "GG";
    // }
    //   exit();  
    //$this->_helper->json->sendJson($respuesta);

    }





}