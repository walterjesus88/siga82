<?php

class ControlDocumentario_JsonController extends Zend_Controller_Action {

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

    //devuelve la lista de carpetas de proyectos
    public function carpetasAction()
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
      $clienteid = $this->_getParam('clienteid');
      $contacto = new Admin_Model_DbTable_Contacto();
      $cons = $contacto->_getContactoxCliente($clienteid);
      $this->_helper->json->sendJson($cons);
    }

    //Devuelve la lista de tipos de proyecto de la tabla proyecto
    public function tipoproyectoAction()
    {
      $proyecto = new Admin_Model_DbTable_Proyecto();
      $tipos = $proyecto->_getTipoProyecto();
      $this->_helper->json->sendJson($tipos);
    }

    //Devuelve la lista de tipos de envio
    public function tipoenvioAction()
    {
      $tipo = new Admin_Model_DbTable_Tipoenvio();
      $tipos = $tipo->_getEmpresas();
      $this->_helper->json->sendJson($tipos);
    }

    //Devuelve la lista de tipos de emisiones que hay para un tipo de envio
    public function emisionesAction()
    {
      $tipo = $this->_getParam('tipo');
      $tabla = new Admin_Model_DbTable_Tipoenvio();
      $emisiones = $tabla->_getEmisiones($tipo);
      $this->_helper->json->sendJson($emisiones);
    }

    //Devuelve la lista de todos los tipos de envios disponibles
    public function tiposdeenvioAction()
    {
      $tabla = new Admin_Model_DbTable_Tipoenvio();
      $tipos = $tabla->_getAll();
      $this->_helper->json->sendJson($tipos);
    }

    //Devuelve los datos de un proyecto en particular
    public function proyectoAction()
    {
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

    //Devuelve la lista de edt de cada proyecto
    public function edtAction()
    {
      $proyectoid = $this->_getParam('proyectoid');
      $edt = new Admin_Model_DbTable_ProyectoEdt();
      $lista = $edt->_getEdtxProyectoid($proyectoid);
      $this->_helper->json->sendJson($lista);
    }

    //datos del contacto seleccionado
    public function obtenerdatoscontactoAction()
    {
      $clienteid = $this->_getParam('clienteid');
      $contactoid = $this->_getParam('contactoid');
      $contacto = new Admin_Model_DbTable_Contacto();
      $respuesta = $contacto->_getDatosContacto($clienteid, $contactoid);
      $this->_helper->json->sendJson($respuesta);
    }

    //subir logo del cliente
    public function subirlogoAction()
    {
      $clienteid = $this->_getParam('clienteid');
      $respuesta['status'] = 'cargando';
      $upload = new Zend_File_Transfer_Adapter_Http();
      $ruta = '/../html/img/cliente/'.$clienteid.'.jpg';
      $upload->setDestination(APPLICATION_PATH.'/../html/img/cliente');
      $upload->addFilter('Rename', array('target' => APPLICATION_PATH.$ruta,
      'overwrite' => true));
      if ($upload->receive()) {
        $respuesta['status'] = 'subido';
      } else {
        $messages = $upload->getMessages();
        echo implode("\n", $messages);
      }
      $this->_helper->json->sendJson($respuesta);
    }

    //agregar un contacto al cliente
    public function setcontactoAction()
    {
      $data['clienteid'] = $this->_getParam('clienteid');
      $data['contactoid'] = $this->_getParam('contactoid');
      $data['nombre'] = $this->_getParam('nombre');
      $data['area'] = $this->_getParam('area');
      $data['correo'] = $this->_getParam('correo');
      $contacto = new Admin_Model_DbTable_Contacto();
      if ($data['contactoid'] == '') {
        $lista = $contacto->_addContacto($data);
      } else {
        $lista = $contacto->_updateContacto($data);
      }
      $this->_helper->json->sendJson($lista);
    }

    //eliminar contacto de cliente
    public function eliminarcontactoAction()
    {
      $clienteid = $this->_getParam('clienteid');
      $contactoid = $this->_getParam('contactoid');
      $contacto = new Admin_Model_DbTable_Contacto();
      $lista = $contacto->_deleteContacto($clienteid, $contactoid);
      $this->_helper->json->sendJson($lista);
    }

    //guardar los tipos de envio creados en la vista
    public function guardartiposdeenvioAction()
    {
      $data['empresa'] = $this->_getParam('empresa');
      $data['abrev'] = $this->_getParam('abrev');
      $data['emitido_para'] = $this->_getParam('emitidopara');
      $tipo = new Admin_Model_DbTable_Tipoenvio();
      $respuesta = $tipo->_setTipoEnvio($data);
      $this->_helper->json->sendJson($respuesta);
    }

    //eliminar el tipo de envio de la tabla tipo_envio
    public function eliminartipodeenvioAction()
    {
      $data['empresa'] = $this->_getParam('empresa');
      $data['abrev'] = $this->_getParam('abrev');
      $tipo = new Admin_Model_DbTable_Tipoenvio();
      $lista = $tipo->_deleteTipoEnvio($data);
      $this->_helper->json->sendJson($lista);
    }

    //Actualiza el control documentario asignado a un proyecto
    public function cambiarcontroldocumentarioAction()
    {
      $proyectoid = $this->_getParam('proyectoid');
      $control_documentario = $this->_getParam('controldocumentario');
      $proyecto = new Admin_Model_DbTable_Proyecto();
      $respuesta = $proyecto->_updateControlDocumentario($proyectoid, $control_documentario);
      $this->_helper->json->sendJson($respuesta);
    }

    //exportar lista de proyectos a xml
    public function exportarproyectosxmlAction()
    {
      $estado = $this->_getParam('estado');
      $proyecto = new Admin_Model_DbTable_Proyecto();
      $proyectos = $proyecto->_getAllExtendido($estado);
      $xml = new DOMDocument('1.0', 'UTF-8');
      $node = $xml->createElement('Proyectos');
      $parnode = $xml->appendChild($node);
      foreach ($proyectos as $item) {
        $node = $xml->createElement('Proyecto');
        $newnode = $parnode->appendChild($node);
        $proyectoid = $xml->createElement('proyectoid', $item['proyectoid']);
        $cliente = $xml->createElement('cliente', $item['nombre_comercial']);
        $nombre = $xml->createElement('nombre-proyecto', substr($item['nombre_proyecto'], 0 , 2));
        $gerente = $xml->createElement('gerente', $item['gerente_proyecto']);
        $control = $xml->createElement('control_proyecto', $item['control_proyecto']);
        $cd = $xml->createElement('control_documentario', $item['control_documentario']);
        $estado = $xml->createElement('estado', $item['estado']);
        $newnode->appendChild($proyectoid);
        $newnode->appendChild($cliente);
        $newnode->appendChild($nombre);
        $newnode->appendChild($gerente);
        $newnode->appendChild($control);
        $newnode->appendChild($cd);
        $newnode->appendChild($estado);
      }
      $output = $xml->saveXML();

      // Both layout and view renderer should be disabled
      Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer')->setNoRender(true);
      Zend_Layout::getMvcInstance()->disableLayout();

      // Set up headers and body
      $this->_response->setHeader('Content-Type', 'text/xml; charset=utf-8')
          ->setBody($output);
    }


}
