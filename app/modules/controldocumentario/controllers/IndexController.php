<?php

class ControlDocumentario_IndexController extends Zend_Controller_Action {

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

    /*Accion que devuelve la vista principal contenida el el archivo
    ../views/scripts/index/index.phtml*/
    public function indexAction()
    {

    }

    public function panelAction()
    {
      $this->_helper->layout()->disableLayout();
    }

    public function proyectosAction()
    {
      $this->_helper->layout()->disableLayout();
    }

    public function asignarcdAction()
    {
      $this->_helper->layout()->disableLayout();
    }

    public function carpetasAction()
    {
      $this->_helper->layout()->disableLayout();
    }

    public function reporteAction()
    {
      $this->_helper->layout()->disableLayout();
    }

    public function transmittalAction()
    {
      $this->_helper->layout()->disableLayout();
    }

    public function configurartrAction()
    {
      $this->_helper->layout()->disableLayout();
    }

    public function anddesAction()
    {
      $this->_helper->layout()->disableLayout();
    }

    public function clienteAction()
    {
      $this->_helper->layout()->disableLayout();
    }

    public function contratistaAction()
    {
      $this->_helper->layout()->disableLayout();
    }

    public function integrantesAction()
    {
      $proyecto = new Admin_Model_DbTable_Proyecto();
      $integrantes = $proyecto->_getCD();
      $tipos = ['A', 'P', 'C', 'CA'];
      $respuesta = [];
      $i = 0;
      foreach ($integrantes as $cd) {
        $carga = $proyecto->_getCargabyCD($cd['control_documentario']);
        $data['nombre'] = $cd['control_documentario'];
        for ($j = 0; $j <  4; $j++) {
          $data[$tipos[$j]] = 0;
          foreach ($carga as $estado) {
            if ($tipos[$j] == $estado['estado']) {
              $data[$tipos[$j]] = $estado['count'];
            }
          }
        }
        $respuesta[$i] = $data;
        $i++;
      }
      $this->_helper->json->sendJson($respuesta);
    }

    public function proyectosjsonAction()
    {
      $proyecto = new Admin_Model_DbTable_Proyecto();
      $proyectos = $proyecto->_getAllExtendido();
      $respuesta = [];
      $data = [];
      $i = 0;
      foreach ($proyectos as $item) {
        $data['codigo'] = $item['proyectoid'];
        $data['cliente'] = $item['nombre_comercial'];
        $data['nombre'] = $item['nombre_proyecto'];
        $data['gerente'] = $item['gerente_proyecto'];
        $data['control_proyecto'] = $item['control_proyecto'];
        $data['control_documentario'] = $item['control_documentario'];
        $data['estado'] = $item['estado'];
        $respuesta[$i] = $data;
        $i++;
      }
      $this->_helper->json->sendJson($respuesta);
    }

    public function clientesAction()
    {
      $this->_helper->layout()->disableLayout();
      $cliente = new Admin_Model_DbTable_Cliente();
      $clientes = $cliente->_getClienteAllOrdenado();
      $respuesta = [];
      $i = 0;
      foreach ($clientes as $fila) {
          $filares['id'] = $fila['clienteid'];
          $filares['nombre'] = $fila['nombre_comercial'];
          $respuesta[$i] = $filares;
          $i++;
      }
      $this->_helper->json->sendJson($respuesta);
    }

    public function contactosAction()
    {
      $this->_helper->layout()->disableLayout();
      $clienteid = $this->_getParam('clienteid');
      $contacto = new Admin_Model_DbTable_Contacto();
      $cons = $contacto->_getContactoxCliente($clienteid);
      $this->_helper->json->sendJson($cons);
    }

    public function tipoproyectoAction()
    {
      $this->_helper->layout()->disableLayout();
      $proyecto = new Admin_Model_DbTable_Proyecto();
      $tipos = $proyecto->_getTipoProyecto();
      $this->_helper->json->sendJson($tipos);
    }

    public function proyectoAction()
    {
      $this->_helper->layout()->disableLayout();
      $data['proyectoid'] = $this->_getParam('proyectoid');
      $proyecto = new Admin_Model_DbTable_Proyecto();
      $pro = $proyecto->_getOnexProyectoidExtendido($data);
      $respuesta['codigo'] = $pro[0]['proyectoid'];
      $respuesta['nombre'] = $pro[0]['nombre_proyecto'];
      $respuesta['cliente'] = $pro[0]['nombre_comercial'];
      $respuesta['unidad_minera'] = $pro[0]['nombre'];
      $respuesta['estado'] = $pro[0]['estado'];
      $respuesta['fecha_inicio'] = $pro[0]['fecha_inicio'];
      $respuesta['fecha_cierre'] = $pro[0]['fecha_cierre'];
      $respuesta['control_documentario'] = $pro[0]['control_documentario'];
      $this->_helper->json->sendJson($respuesta);
    }
}
