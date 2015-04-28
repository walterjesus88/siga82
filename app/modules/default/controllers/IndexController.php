<?php

class IndexController extends Zend_Controller_Action {

    public function init(){
      $options = array(
            'layout' => 'login',
        );
        Zend_Layout::startMvc($options);
    }

    public function  indexAction(){
        try{

          echo "hola";
            }
          catch (Exception $ex){
        print "Error Login".$ex->getMessage();
        }
    }
    
  

}
