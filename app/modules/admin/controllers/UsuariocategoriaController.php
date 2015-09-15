<?php
class Admin_UsuariocategoriaController extends Zend_Controller_Action {

    public function init() {
                 $options = array(
            'layout' => 'layout',
        );
        Zend_Layout::startMvc($options);

    }
    
    public function indexAction() {
       
   	}


   	public function listarAction() {
        $listausuariocategoria = new Admin_Model_DbTable_Usuariocategoria();
        $this->view->lucategoria = $listausuariocategoria->_getUsuariocategoriaAll();
   	}


   	public function editarAction() {
       
   	}
   
    
}

