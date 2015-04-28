<?php

class Propuesta_IndexController extends Zend_Controller_Action {

    public function init() {
    	        
          $options = array(
            'layout' => 'default',
        );
        Zend_Layout::startMvc($options);

    }
    
    public function indexAction() {
echo "aaaaaa";
            
    }
	

    
}
