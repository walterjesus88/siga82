<?php

class Control_IndexController extends Zend_Controller_Action {

    public function init() {
    	$options = array(
            'layout' => 'inicio',
        );
        Zend_Layout::startMvc($options);
    }

    public function indexAction() {
    	echo "jol";
    }

}