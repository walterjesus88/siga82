<?php

class Expense_IndexController extends Zend_Controller_Action {

    public function init() {
    	$options = array(
            'layout' => 'layout',
        );
        Zend_Layout::startMvc($options);
        $auth = Zend_Auth::getInstance();

        if ($auth->hasIdentity()) { 
          $sesion = $auth->getStorage()->read();
          $this->sesion=$sesion;
         // print_r($sesion);
       
        }
    
    }
    
    public function indexAction() {        
        
        $dni=$this->sesion->dni;
        $uid=$this->sesion->uid;

        //print_r($dni);
        //print_r($uid);

        $where=array('uid'=>$uid,'dni'=>$dni);
        $attrib=array('codigo_prop_proy','proyectoid','areaid','cargo','categoriaid','estado');
        $dbusuariocategoria = new Admin_Model_DbTable_Equipo();
        $dataucategoria= $dbusuariocategoria->_getFilter($where,$attrib);

        $this->view->listaproyecto = $dataucategoria;



       // print_r($dataucategoria);
        //echo "ffff";

        // $codigo_prop_proy='PROP-2015-20205467603-1112-15.10.021-B';
        // $proyectoid='test';     
        // $ucategoriaid='U1';

        // $where=array('codigo_prop_proy'=>$codigo_prop_proy,'proyectoid'=>$proyectoid,'ucategoriaid'=>$ucategoriaid);
        // $attrib=array('ucategoriaid','gastoid','fecha_gasto');
        // $dbgastopersona = new Admin_Model_DbTable_Gastopersona();
        // $datagpersona= $dbgastopersona->_getFilter($where,$attrib);

        //print_r($datagpersona);

    }
	
    public function listarAction() {
        /*$listaproyecto = new Admin_Model_DbTable_Proyecto();
        $lista=$listaproyecto->_getProyectoAll();
        $this->view->listaproyecto = $lista;*/            
    }

    public function nuevoAction() {
        
    }   

    public function editarAction() {
      
    }

    public function buscarAction() {      
       
    }

    public function deleteAction(){

    }

    
}
