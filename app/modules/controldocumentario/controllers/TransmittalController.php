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

    //Devuelve la configuracion de transmittal
    public function ultimotransmittalAction()
    {
      $proyectoid = $this->_getParam('proyectoid');
      $transmittal = new Admin_Model_DbTable_Transmittal();
      $respuesta = $transmittal->_getConfiguracion($proyectoid);
      $this->_helper->json->sendJson($respuesta);
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
      $data['modo_envio'] = $this->_getParam('modoenvio');
      $data['estado_elaboracion'] = $this->_getParam('estadoelaboracion');
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

    //editar respuesta
    public function editarrespuestaAction()
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
      $respuesta = $detalle->_updateRespuesta($data);
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

    //guardar el modo de envio seleccionado
    public function actualizarmodoenvioAction()
    {
      $transmittal = $this->_getParam('transmittal');
      $correlativo = $this->_getParam('correlativo');
      $modo = $this->_getParam('modo');
      $trans = new Admin_Model_DbTable_Transmittal();
      $respuesta = $trans->_setModoEnvio($transmittal, $correlativo, $modo);
      $this->_helper->json->sendJson($respuesta);
    }

    //cambiar el estado del transmittal a emitido
    public function emitirtransmittalAction()
    {
      try {
        $transmittal = $this->_getParam('transmittal');
        $correlativo = $this->_getParam('correlativo');
        $trans = new Admin_Model_DbTable_Transmittal();
        $respuesta = $trans->_cambiarEstadoElaboracion($transmittal, $correlativo);
        $this->_helper->json->sendJson($respuesta);
      } catch (Exception $e) {
        print $e->getMessage();
      }
    }

    //listar los detalles que han sido generados
    public function detallesgeneradosAction()
    {
      $proyectoid = $this->_getParam('proyectoid');
      $detalle = new Admin_Model_DbTable_DetalleTransmittal();
      $lista = $detalle->_getDetallesxProyecto($proyectoid);
      $this->_helper->json->sendJson($lista);
    }

    //eliminar detalles de transmittals en elaboracion
    public function eliminardetalleAction()
    {
      $detalleid = $this->_getParam('detalleid');
      $detalle = new Admin_Model_DbTable_DetalleTransmittal();
      $respuesta = $detalle->_deleteDetalle($detalleid);
      $this->_helper->json->sendJson($respuesta);
    }

    //guardar cambios detalle
    public function actualizardetalleAction()
    {
      $data['detalleid'] = $this->_getParam('detalleid');
      $data['emitido'] = $this->_getParam('emitido');
      $data['fecha'] = $this->_getParam('fecha');
      $data['cantidad'] = $this->_getParam('cantidad');
      $detalle = new Admin_Model_DbTable_DetalleTransmittal();
      $respuesta = $detalle->_updateDetalle($data);
      $this->_helper->json->sendJson($respuesta);
    }

    //una funcion tipo pila para actualizar los estados de todos los detalles
    public function actualizarestadodetalleAction()
    {
      $detalle = new Admin_Model_DbTable_DetalleTransmittal();
      $lista = $detalle->_getAll();
      $resp = [];
      $i = 0;
      foreach ($lista as $det) {
        $resp[$i] = $detalle->_setEstado($det['detalleid']);
        $i++;
      }
      $this->_helper->json->sendJson($lista);
    }

    //datos del contacto seleccionado
    public function obtenerdatoscontactodedetalleAction()
    {
      $detalleid = $this->_getParam('detalleid');
      $detalle = new Admin_Model_DbTable_DetalleTransmittal();
      $respuesta = $detalle->_getDatosContactoxDetalle($detalleid);
      $this->_helper->json->sendJson($respuesta);
    }

    public function obtenermodoenvioAction()
    {
      $detalleid = $this->_getParam('detalleid');
      $detalle = new Admin_Model_DbTable_DetalleTransmittal();
      $respuesta = $detalle->_getModoEnvio($detalleid);
      $this->_helper->json->sendJson($respuesta);
    }

    public function obtenertransmittalAction()
    {
      $codificacion = $this->_getParam('codificacion');
      $correlativo = $this->_getParam('correlativo');
      $transmittal = new Admin_Model_DbTable_Transmittal();
      $respuesta = $transmittal->_getTransmittal($codificacion, $correlativo);
      $this->_helper->json->sendJson($respuesta);
    }

    public function cambiarcontactoAction()
    {
      $codificacion = $this->_getParam('codificacion');
      $correlativo = $this->_getParam('correlativo');
      $contactoid = $this->_getParam('contactoid');
      $transmittal = new Admin_Model_DbTable_Transmittal();
      $respuesta = $transmittal->_updateContacto($codificacion, $correlativo, $contactoid);
      $this->_helper->json->sendJson($respuesta);
    }

    public function codigopreferencialAction()
    {
      $detalleid = $this->_getParam('detalleid');
      $transmittal = new Admin_Model_DbTable_DetalleTransmittal();
      $respuesta = $transmittal->_getCodigoPreferencial($detalleid);
      $this->_helper->json->sendJson($respuesta);
    }

    public function cambiarcodigopreferencialAction()
    {
      $codificacion = $this->_getParam('codificacion');
      $correlativo = $this->_getParam('correlativo');
      $cod_pre = $this->_getParam('codpre');
      $transmittal = new Admin_Model_DbTable_Transmittal();
      $respuesta = $transmittal->_updateCodigoPreferencial($codificacion, $correlativo, $cod_pre);
      $this->_helper->json->sendJson($respuesta);
    }
}
