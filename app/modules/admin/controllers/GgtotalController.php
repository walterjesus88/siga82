<?php
class Admin_GgtotalController extends Zend_Controller_Action {

    public function init() {
                 $options = array(
            'layout' => 'inicio'
        );
        Zend_Layout::startMvc($options);

    }
    
    public function indexAction() {
       $hola="hola mundo";
       $this->view->variablevista = $hola;
   	}


   	public function ggtotalAction() {
      $tb_gastos = new Admin_Model_DbTable_Gastorendicion();
      $listatabla = $tb_gastos->_getGastosTotales();
      //print_r($listatabla);
      $this->view->lista_de_gasto = $listatabla;

   	}
}