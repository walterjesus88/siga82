<?php

class RendicionGastos_IndexController extends Zend_Controller_Action {

    public function init() {
    	$options = array(
            'layout' => 'inicio',
            );
        Zend_Layout::startMvc($options);
    }

    public function indexAction() {

    }

    public function gastosAction() {

      $this->_helper->layout()->disableLayout();

  }

    public function rendirgastosAction() {

      $this->_helper->layout()->disableLayout();

  }

  public function modalrendicionAction()
  {
      $this->_helper->layout()->disableLayout();
  }

}