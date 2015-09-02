<?php

class ControlDocumentario_PrintController extends Zend_Controller_Action {

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

    //Funciones que envian las paginas para impresion\\

    public function imprimircarpetasAction()
    {
      $carpeta = new Admin_Model_DbTable_Carpeta();
      $cps = $carpeta->_getAll();
      $tipos = ['A', 'P', 'C', 'CA'];
      $i = 0;
      foreach ($cps as $fila) {
        $proyecto = new Admin_Model_DbTable_Proyecto();
        $datos = $proyecto->_getUbicacionesxCarpeta($fila['carpetaid']);
        $data['carpetaid'] = $fila['carpetaid'];
        $data['nombre'] = $fila['nombre'];
        for ($j = 0; $j <  4; $j++) {
          $data[$tipos[$j]] = 0;
          foreach ($datos as $estado) {
            if ($tipos[$j] == $estado['estado']) {
              $data[$tipos[$j]] = $estado['count'];
            }
          }
        }

        $respuesta[$i] = $data;
        $i++;
      }

      $sumatoria = [];
      $sumatoria['A'] = 0;
      $sumatoria['P'] = 0;
      $sumatoria['CA'] = 0;
      $sumatoria['C'] = 0;
      for ($i=0; $i < sizeof($respuesta); $i++) {
        $sumatoria['A'] += $respuesta[$i]['A'];
        $sumatoria['P'] += $respuesta[$i]['P'];
        $sumatoria['CA'] += $respuesta[$i]['CA'];
        $sumatoria['C'] += $respuesta[$i]['C'];
      }

      $formato = new Admin_Model_DbTable_Formato();
      $formato->_setFormato('carpetas');
      $formato->_setCabecera($sumatoria);
      $formato->_setData($respuesta);
      $resp = $formato->_print();
      $this->_helper->json->sendJson($resp);
    }

    public function imprimirproyectosAction()
    {
      $estado = $this->_getParam('estado');
      $proyecto = new Admin_Model_DbTable_Proyecto();
      $data = $proyecto->_getAllExtendido($estado);
      $cabecera['estado'] = $estado;
      $formato = new Admin_Model_DbTable_Formato();
      $formato->_setFormato('proyectos');
      $formato->_setCabecera($cabecera);
      $formato->_setData($data);
      $respuesta = $formato->_print();
      $this->_helper->json->sendJson($respuesta);
    }

    public function imprimiredtAction()
    {
      $proyectoid = $this->_getParam('proyectoid');
      $proyecto = new Admin_Model_DbTable_Proyecto();
      $data['proyectoid'] = $proyectoid;
      $cabecera = $proyecto->_getOnexProyectoidExtendido($data);
      $edt = new Admin_Model_DbTable_ProyectoEdt();
      $lista = $edt->_getEdtxProyectoid($proyectoid);
      $formato = new Admin_Model_DbTable_Formato();
      $formato->_setFormato('edt');
      $formato->_setCabecera($cabecera);
      $formato->_setData($lista);
      $respuesta = $formato->_print();
      $this->_helper->json->sendJson($respuesta);
    }

    public function imprimirreportetransmittalAction()
    {
      $proyectoid = $this->_getParam('proyectoid');
      $estado = $this->_getParam('estado');
      $clase = $this->_getParam('clase');

      $proyecto = new Admin_Model_DbTable_Proyecto();
      $data['proyectoid'] = $proyectoid;
      $cabecera = $proyecto->_getOnexProyectoidExtendido($data);

      $entregable = new Admin_Model_DbTable_Listaentregabledetalle();
      $lista = $entregable->_getEntregablexProyecto($proyectoid, $estado, $clase);

      $formato = new Admin_Model_DbTable_Formato();
      $formato->_setFormato('reporte_transmittal');
      $formato->_setCabecera($cabecera);
      $formato->_setData($lista);
      $respuesta = $formato->_print();
      $this->_helper->json->sendJson($respuesta);
    }

    public function imprimirreporteclienteAction()
    {
      $proyectoid = $this->_getParam('proyectoid');

      $proyecto = new Admin_Model_DbTable_Proyecto();
      $data['proyectoid'] = $proyectoid;
      $cabecera = $proyecto->_getOnexProyectoidExtendido($data);

      $detalle = new Admin_Model_DbTable_DetalleTransmittal();
      $lista = $detalle->_getDetallesConRespuestaExtendido($proyectoid);

      $formato = new Admin_Model_DbTable_Formato();
      $formato->_setFormato('reporte_cliente');
      $formato->_setCabecera($cabecera);
      $formato->_setData($lista);
      $respuesta = $formato->_print();
      $this->_helper->json->sendJson($respuesta);
    }

    public function imprimirtransmittalAction()
    {
      $transmittal = $this->_getParam('transmittal');
      $correlativo = $this->_getParam('correlativo');
      $trans = new Admin_Model_DbTable_Transmittal();
      $cabecera = $trans->_getTransmittal($transmittal, $correlativo);
      $detalle = new Admin_Model_DbTable_DetalleTransmittal();
      $data = $detalle->_getDetallexTramittal($transmittal, $correlativo);
      $formato = new Admin_Model_DbTable_Formato();
      $formato->_setFormato('anddes');
      $formato->_setCabecera($cabecera);
      $formato->_setData($data);
      $respuesta = $formato->_print();
      $this->_helper->json->sendJson($respuesta);
    }
}
