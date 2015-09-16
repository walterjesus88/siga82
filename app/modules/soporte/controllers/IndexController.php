<?php

class Soporte_IndexController extends Zend_Controller_Action {

    public function init() {
    	$options = array(
            'layout' => 'inicio',
        );
        Zend_Layout::startMvc($options);
    }

    public function indexAction() {

    }

    public function listarareaAction() {

      $this->_helper->layout()->disableLayout();

    }

    public function listarclienteAction() {

      $this->_helper->layout()->disableLayout();

    }

    public function listarunidadmineraAction() {

      $this->_helper->layout()->disableLayout();

    }

}