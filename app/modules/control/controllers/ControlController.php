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

    public function curvasAction()
    {
      //$this->_helper->layout()->disableLayout();
    }


    public function listaentregablesAction() {
    	
        $proyectoid= $this->_getParam("proyectoid");
        $codigo_prop_proy= $this->_getParam("codigo_prop_proy");
        $revision= $this->_getParam("revision");

        $this->view->proyectoid = $proyectoid;
        $this->view->codigo_prop_proy = $codigo_prop_proy;
        $this->view->revision = $revision;

       //$revision='A';

        $where = array('proyectoid' => $proyectoid,'codigo_prop_proy' => $codigo_prop_proy,'revision_entregable' => $revision );
        
        print_r($where);

        $listar_entregables=new Admin_Model_DbTable_Listaentregable();
        $lentreg=$listar_entregables->_getFilter($where);
   
        $this->view->lista = $lentreg;

        $listar_entregables_detalle=new Admin_Model_DbTable_Listaentregabledetalle();
        $lentreg_det=$listar_entregables_detalle->_getFilter($where);

        $this->view->lista_det = $lentreg_det;


        print_r($lentreg_det);
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


       
        $data['edt']=$edt;
        $data['tipo_documento']=$tipo_doc;
        $data['disciplina']=$disc;
        $data['codigo_anddes']=$edt;
        $data['codigo_cliente']=$cod_anddes;
       // $data['descripcion_entregable']=$descripcion_entregable;
        $data['proyectoid']=$proyectoid;
        $data['codigo_prop_proy']=$codigo_prop_proy;
        $data['revision_entregable']=$revision;

        $data['descripcion_entregable']=$descripcion;
        $data['fecha_a']=$fechaA;
        $data['fecha_b']=$fechaB;
        $data['fecha_0']=$fecha0;       



        $lista = array('codigo_prop_proy' => $codigo_prop_proy, 'proyectoid' => $proyectoid, 'revision_entregable' => $revision);

        $glista=new Admin_Model_DbTable_Listaentregable();
        $listar=$glista->_getFilter($lista);

        if($listar)
        {
            $glistadetalle = new Admin_Model_DbTable_Listaentregabledetalle();
            $glistadetalle->_save($data);
        }
        else
        {
            $glista->_save($lista);
            $glistadetalle = new Admin_Model_DbTable_Listaentregabledetalle();
            $glistadetalle->_save($data);            
        }


        print_r($data);

     

        //exit();
     
    }

    public function angularAction() {
    //$this->_helper->layout()->disablelayout();
        
    }


}