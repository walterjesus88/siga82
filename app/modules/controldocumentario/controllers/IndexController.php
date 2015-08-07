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

    public function editartransmittalAction()
    {
      $this->_helper->layout()->disableLayout();
    }

    public function modalcontactoAction()
    {
      $this->_helper->layout()->disableLayout();
    }

}
