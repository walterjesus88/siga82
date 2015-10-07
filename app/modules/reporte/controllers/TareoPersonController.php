<?php
class Reporte_TareoPersonController extends Zend_Controller_Action {

    public function init() {
        $sesion  = Zend_Auth::getInstance();
        if(!$sesion->hasIdentity()) {
            $this->_helper->redirector('index',"index",'default');
        }
        $login = $sesion->getStorage()->read();
        $this->sesion = $login;
        $options = array(
            'layout' => 'inicio',
        );
        Zend_Layout::startMvc($options);
    }

    public function indexAction()
    {
      // $tareo = new Reporte_DataTable_Tareo();
    }


}