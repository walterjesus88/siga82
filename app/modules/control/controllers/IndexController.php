<?php

class Control_IndexController extends Zend_Controller_Action {

    public function init() {
    	$options = array(
            'layout' => 'inicio',
        );
        Zend_Layout::startMvc($options);
    }

    public function indexAction() {
    	
    }

    public function panelAction() {
      
      $this->_helper->layout()->disableLayout();
    	
    }

    public function proyectosAction() {
      
      $this->_helper->layout()->disableLayout();
    	
    }

    public function curvasAction() {
      
      $this->_helper->layout()->disableLayout();
    	
    }

    public function performanceAction() {
      
      $this->_helper->layout()->disableLayout();
        
    }

    public function fechacorteAction() {
      
      $this->_helper->layout()->disableLayout();
        
    }

    public function edtAction() {
      
      $this->_helper->layout()->disableLayout();
        
    }

    public function listaentregableAction() {
      
      $this->_helper->layout()->disableLayout();
        
    }



    public function cronogramaAction() {
      
      $this->_helper->layout()->disableLayout();
        
    }

    public function detalleAction() {
      
      $this->_helper->layout()->disableLayout();
    	
    }

    public function controlAction() {
      
      $this->_helper->layout()->disableLayout();
        
    }


}