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
        $this->_helper->layout()->disableLayout();
    }





    //Funciones que envian las vistas como plantillas sin datos

    public function indexAction()
    {
      $this->_helper->layout()->enableLayout();
    }

    public function panelAction()
    {

    }

    public function proyectosAction()
    {

    }

    public function carpetasAction()
    {

    }

    public function reporteAction()
    {

    }

    public function transmittalAction()
    {

    }

    public function configurartrAction()
    {

    }

    public function anddesAction()
    {

    }

    public function clienteAction()
    {

    }

    public function contratistaAction()
    {

    }

    public function tablaentregablesAction()
    {

    }

    public function editartransmittalAction()
    {

    }

    public function modalcontactoAction()
    {

    }

    public function modallogoAction()
    {

    }

    public function modaltipoenvioAction()
    {

    }

}
