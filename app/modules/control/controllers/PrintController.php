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
      $revision_entregable = $this->_getParam('revision');
      $proyectoid = $this->_getParam('proyectoid');
      $entregable = new Admin_Model_DbTable_Listaentregabledetalle();   
      $data=$entregable->_getFilteristaentregable($proyectoid,$revision_entregable);
      $cabecera['estado'] = $revision_entregable;
      $formato = new Admin_Model_DbTable_Formatocp('lista_entregable', $cabecera, $data);
      $respuesta = $formato->_print();

      $this->_helper->json->sendJson($respuesta);
    }

    public function imprimirperformanceAction()
    {
      $revision_cronograma = $this->_getParam('revision');
      $proyectoid = $this->_getParam('proyectoid');
      $performance = new Admin_Model_DbTable_Performancedetalle(); 
      //$where = array('revision_cronograma' =>$revision_cronograma ,'proyectoid'=>$proyectoid );
      $data=$performance->_getFilterPerformance($proyectoid,$revision_cronograma);
      //print_r($perf);exit();

      //$data['actividadid']//   
      //$entregable = new Admin_Model_DbTable_Listaentregabledetalle();   
      //$data=$entregable->_getFilteristaentregable($proyectoid,$revision_cronograma);

      $cabecera['estado'] = $revision_cronograma;
      $formato = new Admin_Model_DbTable_Formatocp('performance', $cabecera, $data);
      $respuesta = $formato->_print();

      $this->_helper->json->sendJson($respuesta);
    }

    
}