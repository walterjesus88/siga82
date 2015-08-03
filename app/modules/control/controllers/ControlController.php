<?php

class Control_ControlController extends Zend_Controller_Action {

    public function init() {
    	$options = array(
            'layout' => 'inicio',
        );
        Zend_Layout::startMvc($options);
    }

    public function indexAction() {
    	echo "jol";
    }

    public function performanceAction() {
    	echo "jol";
    }

    public function listaentregablesAction() {
    	
        $proyectoid= $this->_getParam("proyectoid");
        $codigo_prop_proy= $this->_getParam("codigo_prop_proy");
        $revision= $this->_getParam("revision");

        $this->view->proyectoid = $proyectoid;
        $this->view->codigo_prop_proy = $codigo_prop_proy;
        $this->view->revision = $revision;

        $where = array('proyectoid' => $proyectoid,'codigo_prop_proy' => $codigo_prop_proy,
        'revision_proyecto' => $revision );
        $listar_entregables=new Admin_Model_DbTable_Listaentregable();
        $lentreg=$listar_entregables->_getFilter($where);
   
        $this->view->lista = $lentreg;

        //print_r($lentreg);
    	echo "lista";
    }

    public function guardarlistaAction() {
        $edt= $this->_getParam("edt");
        $tipo_doc= $this->_getParam("tipo_doc");
        $disc= $this->_getParam("disc");
        $cod_anddes= $this->_getParam("cod_anddes");

        $proyectoid= $this->_getParam("proyectoid");
        $codigo_prop_proy= $this->_getParam("codigo_prop_proy");
        $revision= $this->_getParam("revision");

        $cod_cliente= $this->_getParam("cod_cliente");
        $descripcion= $this->_getParam("descripcion");
        $fechaA= $this->_getParam("fechaA");
        $fechaB= $this->_getParam("fechaB");
        $fecha0= $this->_getParam("fecha0");



        echo $edt;
        echo $tipo_doc;
        echo $disc;
        echo $cod_anddes;
        echo $proyectoid;
        echo $codigo_prop_proy;
        echo $revision;
       
        $data['edt']=$edt;
        $data['tipo_documento']=$tipo_doc;
        $data['disciplina']=$disc;
        $data['codigo_anddes']=$edt;
        $data['codigo_cliente']=$cod_anddes;
       // $data['descripcion_entregable']=$descripcion_entregable;
        $data['proyectoid']=$proyectoid;
        $data['codigo_prop_proy']=$codigo_prop_proy;
        $data['revision_proyecto']=$revision;

        $data['descripcion_entregable']=$descripcion;
        $data['fecha_a']=$fechaA;
        $data['fecha_b']=$fechaB;
        $data['fecha_0']=$fecha0;


        $glista=new Admin_Model_DbTable_Listaentregable();
        $glista->_save($data);


        exit();
    }

    public function angularAction() {
    //$this->_helper->layout()->disablelayout();
        
    }


}