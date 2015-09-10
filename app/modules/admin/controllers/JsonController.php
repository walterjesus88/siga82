<?php
class Admin_JsonController extends Zend_Controller_Action {

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

    public function usuariosAction()
    {
        $where = array('estado' =>'A' , );
        $usuarios= new Admin_Model_DbTable_Usuario();
        $array=$usuarios->_getFilter($where);
        $this->_helper->json->sendJson($array);
    }

    public function usuariosxestadoAction()
    {
        $estado_usuario = $this->_getParam('estado');    
        $where = array('estado' =>$estado_usuario , );
        $usuarios= new Admin_Model_DbTable_Usuario();
        $array=$usuarios->_getFilter($where);
        $this->_helper->json->sendJson($array);
    }

    public function cambiarestadoxusuarioAction()
    {
        $usuarios= new Admin_Model_DbTable_Usuario();
        $estado = $this->_getParam('estado');    
        $uid = $this->_getParam('uid');    
        $data["estado"]=$estado;
        $str="uid='$uid'";
        $array=$usuarios -> _update($data,$str); 
        $this->_helper->json->sendJson($array);
    }

    public function guardarareaAction()
    {
      $areaid = $this->_getParam('areaid');
      $nombre = $this->_getParam('nombre');
      $where = array('nombre' =>$nombre , );
      $pk = array('areaid' =>$areaid , );
      $guardararea= new Admin_Model_DbTable_Area();
      $garea=$guardararea->_update_pk($where,$pk);
      $this->_helper->json->sendJson($garea);
    }

    public function eliminareaAction()
    {
      $areaid = $this->_getParam('areaid');
      $pk = array('areaid' =>$areaid , );
      $eliminararea= new Admin_Model_DbTable_Area();
      $earea=$eliminararea->_delete($pk);
      $this->_helper->json->sendJson($earea);
    }
}