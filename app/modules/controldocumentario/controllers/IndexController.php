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

    public function proyectosAction()
    {

    }

    public function asignarcdAction()
    {

    }

    public function carpetasAction()
    {

    }

    public function reporteAction()
    {

    }

    public function proyectoingenieriaAction()
    {

    }

    public function configurartrAction()
    {

    }

    public function anddesAction()
    {
      
    }
}
