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
     //  $entregable = new Admin_Model_DbTable_Listaentregabledetalle();
     //  $where = array('revision_entregable' =>$revision, );
     //  $data = $entregable->_getFilter($where);
     //  $cabecera['revision_entregable'] = $revision;

     // // print_r($data);exit();

     //  $formato = new Admin_Model_DbTable_Formato();
     //  $formato->_setFormato('proyectos');
     //  $formato->_setCabecera($cabecera);
     //  $formato->_setData($data);
     //  $respuesta = $formato->_print();
     //  $this->_helper->json->sendJson($respuesta);


      $estado = 'A';
      //$this->_getParam('revision');
      $proyecto = new Admin_Model_DbTable_Proyecto();
      $data = $proyecto->_getAllExtendido($estado);
      $cabecera['estado'] = $estado;
      $formato = new Admin_Model_DbTable_Formatocp();
      $formato->_setFormato('proyectos');
      $formato->_setCabecera($cabecera);
      $formato->_setData($data);


      $respuesta = $formato->_print();
      
      if($respuesta)
      {
        //////print_r($respuesta);
      }
      else
    {
        echo "GG";
    }
      exit();


    

      //$this->_helper->json->sendJson($respuesta);

    }





}