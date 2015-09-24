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

    public function listaentregablesAction()
    {
      $proyectoid = $this->_getParam('proyectoid');
      $entregable = new Admin_Model_DbTable_Listaentregabledetalle();
      $respuesta = $entregable->_getListaEntregables($proyectoid);
      $this->_helper->json->sendJson($respuesta);
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

    //actualizar el tipo de documento
    public function actualizartipoentregableAction()
    {
      $entregableid = $this->_getParam('entregableid');
      $tipo = $this->_getParam('tipo');
      $entregable = new Admin_Model_DbTable_Listaentregabledetalle();
      $fila = $entregable->_setTipoEntregable($entregableid, $tipo);
      $respuesta['resultado'] = 'guardado';
      $this->_helper->json->sendJson($respuesta);
    }

    //actualizar la disciplina
    public function actualizardisciplinaAction()
    {
      $entregableid = $this->_getParam('entregableid');
      $disciplina = $this->_getParam('disciplina');
      $entregable = new Admin_Model_DbTable_Listaentregabledetalle();
      $fila = $entregable->_setDisciplina($entregableid, $disciplina);
      $respuesta['resultado'] = 'guardado';
      $this->_helper->json->sendJson($respuesta);
    }

    //actualizar la descripcion
    public function actualizardescripcionAction()
    {
      $entregableid = $this->_getParam('entregableid');
      $descripcion = $this->_getParam('descripcion');
      $entregable = new Admin_Model_DbTable_Listaentregabledetalle();
      $fila = $entregable->_setDescripcion($entregableid, $descripcion);
      $respuesta['resultado'] = 'guardado';
      $this->_helper->json->sendJson($respuesta);
    }

    //actualizar la revision
    public function actualizarrevisionentregableAction()
    {
      $entregableid = $this->_getParam('entregableid');
      $revision = $this->_getParam('revision');
      $entregable = new Admin_Model_DbTable_Listaentregabledetalle();
      $fila = $entregable->_setRevision($entregableid, $revision);
      $respuesta['resultado'] = 'guardado';
      $this->_helper->json->sendJson($respuesta);
    }

    //guardar datos de entregables de tipo gestion o comunicacion
    public function guardarentregableAction()
    {
      $data['entregableid'] = $this->_getParam('entregableid');
      $data['proyectoid'] = $this->_getParam('proyectoid');
      $data['tipo_documento'] = $this->_getParam('tipo');
      $data['disciplina'] = $this->_getParam('disciplina');
      $data['codigo_anddes'] = $this->_getParam('codigoanddes');
      $data['codigo_cliente'] = $this->_getParam('codigocliente');
      $data['descripcion'] = $this->_getParam('descripcion');
      $data['revision'] = $this->_getParam('revision');
      $data['clase'] = $this->_getParam('clase');
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

    //reporte de estados de entregables
    public function obtenerreporteAction()
    {
      $proyectoid = $this->_getParam('proyectoid');
      $entregable = new Admin_Model_DbTable_Listaentregabledetalle();
      if ($proyectoid == 'All') {
        $lista = $entregable->_getReporteAll();
      } else {
        $lista = $entregable->_getReportexProyecto($proyectoid);
      }
      $this->_helper->json->sendJson($lista);
    }

    public function guardarrevisionAction()
    {
      $data['entregableid'] = $this->_getParam('entregableid');
      $data['tipo'] = $this->_getParam('tipo');
      $entregable = new Admin_Model_DbTable_Listaentregabledetalle();
      $respuesta = $entregable->_createRevision($data);
      $this->_helper->json->sendJson($respuesta);
    }

  }
