<?php

class ControlDocumentario_EntregableController extends Zend_Controller_Action {

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

    //Devuelve la lista de entregables de un proyecto
    public function entregablesAction()
    {
      $proyectoid = $this->_getParam('proyectoid');
      $estado = $this->_getParam('estado');
      $clase = $this->_getParam('clase');
      $entregable = new Admin_Model_DbTable_Listaentregabledetalle();
      $lista = $entregable->_getEntregablexProyecto($proyectoid, $estado, $clase);
      $this->_helper->json->sendJson($lista);
    }

    //actualizar el codigo de anddes de los entregables
    public function actualizarcodigoanddesAction()
    {
      $entregableid = $this->_getParam('entregableid');
      $codigo_anddes = $this->_getParam('codigoanddes');
      $entregable = new Admin_Model_DbTable_Listaentregabledetalle();
      $fila = $entregable->_setCodigoAnddes($entregableid, $codigo_anddes);
      $respuesta['resultado'] = 'guardado';
      $this->_helper->json->sendJson($respuesta);
    }

    //actualizar el codigo de cliente de los entregables
    public function actualizarcodigoclienteAction()
    {
      $entregableid = $this->_getParam('entregableid');
      $codigo_cliente = $this->_getParam('codigocliente');
      $entregable = new Admin_Model_DbTable_Listaentregabledetalle();
      $fila = $entregable->_setCodigoCliente($entregableid, $codigo_cliente);
      $respuesta['resultado'] = 'guardado';
      $this->_helper->json->sendJson($respuesta);
    }

    //guardar datos de entregables de tipo gestion o comunicacion
    public function guardarentregableAction()
    {
      $data['entregableid'] = $this->_getParam('entregableid');
      $data['proyectoid'] = $this->_getParam('proyectoid');
      $data['tipo'] = $this->_getParam('tipo');
      $data['disciplina'] = $this->_getParam('disciplina');
      $data['codigo_anddes'] = $this->_getParam('codigoanddes');
      $data['codigo_cliente'] = $this->_getParam('codigocliente');
      $data['descripcion'] = $this->_getParam('descripcion');
      $data['revision'] = $this->_getParam('revision');
      $entregable = new Admin_Model_DbTable_Listaentregabledetalle();
      $respuesta = $entregable->_setEntregable($data);
      $this->_helper->json->sendJson($respuesta);
    }

    //eliminar entregables de tipo gestion o comunicacion
    public function eliminarentregableAction()
    {
      $entregableid = $this->_getParam('entregableid');
      $entregable = new Admin_Model_DbTable_Listaentregabledetalle();
      $respuesta = $entregable->_deleteEntregable($entregableid);
      $this->_helper->json->sendJson($respuesta);
    }
  }
