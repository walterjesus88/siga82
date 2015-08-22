<?php

class ControlDocumentario_TransmittalController extends Zend_Controller_Action {

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

    /*Devuelve el numero incremental a asignar al nuevo transmittal deacuerdo al
    proyecto*/
    public function correlativotransmittalAction()
    {
      $proyectoid = $this->_getParam('proyectoid');
      $transmittal = new Admin_Model_DbTable_Transmittal();
      $correlativo = $transmittal->_getCorrelativo($proyectoid);
      $this->_helper->json->sendJson($correlativo);
    }

    //Guarda los datos de configuracion del transmittal
    public function guardarconfiguraciontransmittalAction()
    {
      $data['codificacion'] = $this->_getParam('codificacion');
      $data['correlativo'] = $this->_getParam('correlativo');
      $data['clienteid'] = $this->_getParam('clienteid');
      $data['proyectoid'] = $this->_getParam('proyectoid');
      $data['formato'] = $this->_getParam('formato');
      $data['tipo_envio'] = $this->_getParam('tipoenvio');
      $data['control_documentario'] = $this->_getParam('controldocumentario');
      $data['dias_alerta'] = $this->_getParam('diasalerta');
      $data['tipo_proyecto'] = $this->_getParam('tipoproyecto');
      $data['atencion'] = $this->_getParam('atencion');
      $transmittal = new Admin_Model_DbTable_Transmittal();
      $respuesta = $transmittal->_saveConfiguracion($data);
      $this->_helper->json->sendJson($respuesta);
    }

    //guardar los detalles del transmittal
    public function guardardetalleAction()
    {
      $data['entregableid'] = $this->_getParam('codigo');
      $data['tipo_envio'] = $this->_getParam('tipoenvio');
      $data['revision'] = $this->_getParam('revision');
      $data['estado_revision'] = $this->_getParam('estadorevision');
      $data['transmittal'] = $this->_getParam('transmittal');
      $data['correlativo'] = $this->_getParam('correlativo');
      $data['emitido'] = $this->_getParam('emitido');
      $data['fecha'] = $this->_getParam('fecha');
      $data['estado'] = $this->_getParam('estado');
      $detalle = new Admin_Model_DbTable_DetalleTransmittal();
      $respuesta = $detalle->_addDetalle($data);
      $this->_helper->json->sendJson($respuesta);
    }

    //guardar respuestas emitidas por los clientes o contratistas
    public function guardarrespuestaAction()
    {
      $data['detalleid'] = $this->_getParam('detalleid');
      $data['respuesta_transmittal'] = $this->_getParam('respuestatransmittal');
      $data['codigo_anddes'] = $this->_getParam('codigoanddes');
      $data['codigo_cliente'] = $this->_getParam('codigocliente');
      $data['descripcion'] = $this->_getParam('descripcion');
      $data['revision'] = $this->_getParam('revision');
      $data['respuesta_emitido'] = $this->_getParam('emitido');
      $data['respuesta_fecha'] = $this->_getParam('fecha');

      $detalle = new Admin_Model_DbTable_DetalleTransmittal();
      $respuesta = $detalle->_setRespuesta($data);
      $this->_helper->json->sendJson($respuesta);
    }

    //eliminar respuesta del cliente o contratistas
    public function eliminarrespuestaAction()
    {
      $detalleid = $this->_getParam('detalleid');
      $detalle = new Admin_Model_DbTable_DetalleTransmittal();
      $respuesta = $detalle->_deleteRespuesta($detalleid);
      $this->_helper->json->sendJson($respuesta);
    }

    //lista de detalles sin respuesta
    public function detallessinrespuestaAction()
    {
      $proyectoid = $this->_getParam('proyectoid');
      $detalle = new Admin_Model_DbTable_DetalleTransmittal();
      $respuesta = $detalle->_getDetalleSinRespuesta($proyectoid);
      $this->_helper->json->sendJson($respuesta);
    }

    //lista de entregables con respuesta
    public function obtenerrespuestasAction()
    {
      $proyectoid = $this->_getParam('proyectoid');
      $detalle = new Admin_Model_DbTable_DetalleTransmittal();
      $respuesta = $detalle->_getDetallesConRespuesta($proyectoid);
      $this->_helper->json->sendJson($respuesta);
    }
}
