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

    public function proyectoingenieriaAction()
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
}
