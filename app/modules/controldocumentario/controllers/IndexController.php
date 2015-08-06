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





    //Funciones que envian las vistas como plantillas sin datos

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

    public function tablaentregablesAction()
    {
      $this->_helper->layout()->disableLayout();
    }

    public function modalcontactoAction()
    {
      $this->_helper->layout()->disableLayout();
    }




    //Funciones que devuelven datos en formato json

    /*Devuelve la lista de las personas trabajando en control documentario y
    la carga de trabajo por estado de proyecto*/
    public function integrantesAction()
    {
      $proyecto = new Admin_Model_DbTable_Proyecto();
      $integrantes = $proyecto->_getCD();
      $tipos = ['A', 'P', 'C', 'CA'];
      $respuesta = [];
      $i = 0;
      foreach ($integrantes as $cd) {
        $carga = $proyecto->_getCargabyCD($cd['control_documentario']);
        $data['uid'] = $cd['control_documentario'];
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

    //Devuelve la lista de proyectos por estado
    public function listaproyectosAction()
    {
      $estado = $this->_getParam('estado');
      $proyecto = new Admin_Model_DbTable_Proyecto();
      $proyectos = $proyecto->_getAllExtendido($estado);
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

    //Devuelve la lista de clientes de Anddes
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

    //Devuelve la lista de contactos por cliente
    public function contactosAction()
    {
      $this->_helper->layout()->disableLayout();
      $clienteid = $this->_getParam('clienteid');
      $contacto = new Admin_Model_DbTable_Contacto();
      $cons = $contacto->_getContactoxCliente($clienteid);
      $this->_helper->json->sendJson($cons);
    }

    //Devuelve la lista de tipos de proyecto de la tabla proyecto
    public function tipoproyectoAction()
    {
      $this->_helper->layout()->disableLayout();
      $proyecto = new Admin_Model_DbTable_Proyecto();
      $tipos = $proyecto->_getTipoProyecto();
      $this->_helper->json->sendJson($tipos);
    }

    //Devuelve los datos de un proyecto en particular
    public function proyectoAction()
    {
      $this->_helper->layout()->disableLayout();
      $data['proyectoid'] = $this->_getParam('proyectoid');
      $proyecto = new Admin_Model_DbTable_Proyecto();
      $datos = $proyecto->_getOnexProyectoidExtendido($data);
      $respuesta['codigo'] = $datos['proyectoid'];
      $respuesta['nombre'] = $datos['nombre_proyecto'];
      $respuesta['clienteid'] = $datos['clienteid'];
      $respuesta['cliente'] = $datos['nombre_comercial'];
      $respuesta['unidad_minera'] = $datos['nombre'];
      $respuesta['estado'] = $datos['estado'];
      $respuesta['fecha_inicio'] = $datos['fecha_inicio'];
      $respuesta['fecha_cierre'] = $datos['fecha_cierre'];
      $respuesta['control_documentario'] = $datos['control_documentario'];
      $respuesta['descripcion'] = $datos['descripcion'];
      $respuesta['tipo_proyecto'] = $datos['tipo_proyecto'];
      $respuesta['logo_cliente'] = '../img/cliente/'.$respuesta['clienteid'].'.jpg';
      $this->_helper->json->sendJson($respuesta);
    }

    /*Devuelve el numero incremental a asignar al nuevo transmittal deacuerdo al
    proyecto*/
    public function correlativotransmittalAction()
    {
      $this->_helper->layout()->disableLayout();
      $proyectoid = $this->_getParam('proyectoid');
      $transmittal = new Admin_Model_DbTable_Transmittal();
      $correlativo = $transmittal->_getCorrelativo($proyectoid);
      $this->_helper->json->sendJson($correlativo);
    }

    //Devuelve la lista de edt de cada proyecto
    public function edtAction()
    {
      $this->_helper->layout()->disableLayout();
      $proyectoid = $this->_getParam('proyectoid');
      //$edt = new Admin_Model_DbTable_Edt();
      //$lista = $edt->_getEdtxProyecto($proyectoid);
      $fila1['codigo'] = '000';
      $fila1['nombre'] = 'General';
      $fila2['codigo'] = '001';
      $fila2['nombre'] = 'Primer piso';
      $respuesta[0] = $fila1;
      $respuesta[1] = $fila2;
      $this->_helper->json->sendJson($respuesta);
    }

    //Devuelve la lista de entregables de un proyecto
    public function entregablesAction()
    {
      $this->_helper->layout()->disableLayout();
      $proyectoid = $this->_getParam('proyectoid');
      $entregable = new Admin_Model_DbTable_Listaentregable();
      $lista = $entregable->_getEntregablesxProyecto($proyectoid);
      $respuesta = $lista;
      $this->_helper->json->sendJson($respuesta);
    }


    //Funciones que cambian datos en la base de datos

    //Actualiza el control documentario asignado a un proyecto
    public function cambiarcontroldocumentarioAction()
    {
      $this->_helper->layout()->disableLayout();
      $proyectoid = $this->_getParam('proyectoid');
      $control_documentario = $this->_getParam('controldocumentario');
      $proyecto = new Admin_Model_DbTable_Proyecto();
      $respuesta = $proyecto->_updateControlDocumentario($proyectoid, $control_documentario);
      $this->_helper->json->sendJson($respuesta);
    }

    //Guarda los datos de configuracion del transmittal
    public function guardarconfiguraciontransmittalAction()
    {
      $this->_helper->layout()->disableLayout();
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

}
