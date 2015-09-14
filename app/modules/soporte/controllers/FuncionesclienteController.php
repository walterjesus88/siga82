<?php

class Soporte_FuncionesclienteController extends Zend_Controller_Action {

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


    public function llamarclienteAction()
    {
      // $isproyecto = $this->_getParam('isproyecto');

      // $where = array('isproyecto' =>$isproyecto , );
      $clientes= new Admin_Model_DbTable_Cliente();
      // $a=$clientes->_getFilter($where);
      $a=$clientes->_getClienteAll();

      $this->_helper->json->sendJson($a);

    }


    public function guardarclienteAction()
    {

      // print_r($pk);
      // print_r($garea);

      // exit();

        $formdata['clienteid']=$clienteid = $this->_getParam('clienteid');
        $formdata['nombre_comercial']=$nombre_comercial = $this->_getParam("nombre_comercial");
        $formdata['nombre']=$nombre = $this->_getParam("nombre");   
        $formdata['codigoid']=$codigoid = $this->_getParam("codigoid");   
        $formdata['fecha_registro']=$fecha_registro = $this->_getParam("fecha_registro");   
        $formdata['web']=$web = $this->_getParam("web");   
        $formdata['direccion']=$direccion = $this->_getParam("direccion");   
        $formdata['paisid']=$paisid = $this->_getParam("paisid"); 
        $formdata['departamentoid']=$departamentoid = $this->_getParam('departamentoid');
        $formdata['provinciaid']=$provinciaid = $this->_getParam("provinciaid");
        $formdata['distritoid']=$distritoid = $this->_getParam("distritoid");   
        $formdata['estado']=$estado = $this->_getParam("estado");   
        $formdata['tag']=$tag = $this->_getParam("tag");   
        $formdata['isproveedor']=$isproveedor = $this->_getParam("isproveedor");   
        $formdata['iscliente']=$iscliente = $this->_getParam("iscliente");   
        $formdata['abreviatura']=$abreviatura = $this->_getParam("abreviatura"); 
        $formdata['tipo_cliente']=$tipo_cliente = $this->_getParam("tipo_cliente"); 
        $formdata['ruc']=$ruc = $this->_getParam("ruc"); 
        $formdata['issocio']=$issocio = $this->_getParam("issocio"); 
        // print_r($nombre);
        $guardarcliente=new Admin_Model_DbTable_Cliente();
        $gcliente=$guardarcliente->_save($formdata);

        print_r($nombre);
        
      exit();

      $this->_helper->json->sendJson($gcliente);


    }

    public function modificarclienteAction()
    {

      // print_r($pk);
      // print_r($garea);

      // exit();

        $clienteid = $this->_getParam('clienteid');
        $formdata['nombre_comercial']=$nombre_comercial = $this->_getParam("nombre_comercial");
        $formdata['nombre']=$nombre = $this->_getParam("nombre");   
        $formdata['codigoid']=$codigoid = $this->_getParam("codigoid");   
        $formdata['fecha_registro']=$fecha_registro = $this->_getParam("fecha_registro");   
        $formdata['web']=$web = $this->_getParam("web");   
        $formdata['direccion']=$direccion = $this->_getParam("direccion");   
        $formdata['paisid']=$paisid = $this->_getParam("paisid"); 
        $formdata['departamentoid']=$departamentoid = $this->_getParam('departamentoid');
        $formdata['provinciaid']=$provinciaid = $this->_getParam("provinciaid");
        $formdata['distritoid']=$distritoid = $this->_getParam("distritoid");   
        $formdata['estado']=$estado = $this->_getParam("estado");   
        $formdata['tag']=$tag = $this->_getParam("tag");   
        $formdata['isproveedor']=$isproveedor = $this->_getParam("isproveedor");   
        $formdata['iscliente']=$iscliente = $this->_getParam("iscliente");   
        $formdata['abreviatura']=$abreviatura = $this->_getParam("abreviatura"); 
        $formdata['tipo_cliente']=$tipo_cliente = $this->_getParam("tipo_cliente"); 
        $formdata['ruc']=$ruc = $this->_getParam("ruc"); 
        $formdata['issocio']=$issocio = $this->_getParam("issocio"); 
        // print_r($nombre);
        $guardarcliente=new Admin_Model_DbTable_Cliente();
        $gcliente=$guardarcliente->_updatecliente($formdata,$clienteid);

        // print_r($nombre);
        // alert($nombre);
      exit();

      $this->_helper->json->sendJson($gcliente);


    }
}