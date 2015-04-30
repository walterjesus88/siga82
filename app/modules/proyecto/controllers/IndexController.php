<?php

class Proyecto_IndexController extends Zend_Controller_Action {

    public function init() {
    	$options = array(
            'layout' => 'layout',
        );
        Zend_Layout::startMvc($options);
    }
    
    public function indexAction() {
        
   
    }
	
    public function listarAction() {

        $listaproyecto = new Admin_Model_DbTable_Proyecto();
        $lista=$listaproyecto->_getProyectoAll();
        $this->view->listaproyecto = $lista;
            
    }


    public function nuevoAction() {

        $propuestas = new Admin_Model_DbTable_Propuesta();
        $prop=$propuestas->_getPropuestaxnoproyectxganado();       
        $this->view->propuestas=$prop;

        $form= new Admin_Form_Proyecto();        
        $this->view->form=$form;   

        $proyectoid = $this->_getParam('proyectoid');
        $propuestaid = $this->_getParam('propuesta');
        $nombre_proyecto = $this->_getParam('nombre_proyecto');
        $codigo_prop_proy = $this->_getParam('cod_proy_prop');

        $control_proyecto = $this->_getParam('control_proyecto');
        $revision = $this->_getParam('revision');   
        $descripcion = $this->_getParam('descripcion');   
        $fecha_inicio = $this->_getParam('fecha_inicio');
        $control_documentario = $this->_getParam('control_documentario');
        $estado = $this->_getParam('estado');
        $gerente_proyecto = $this->_getParam('gerente_proyecto'); 
        $tipo_proyecto = $this->_getParam('tipo_proyecto');
        $tag = $this->_getParam('tag');
 
        $formdata['proyectoid']=$proyectoid['0'];
        $formdata['propuestaid']=$propuestaid;
        $formdata['nombre_proyecto']=$nombre_proyecto;
        $formdata['codigo_prop_proy']=$codigo_prop_proy;
        $formdata['control_proyecto']=$control_proyecto;                       
        $formdata['revision']=$revision;
        $formdata['descripcion']=$descripcion;
        $formdata['fecha_inicio']=$fecha_inicio;
        $formdata['control_documentario']=$control_documentario;
        $formdata['estado']=$estado;
        $formdata['gerente_proyecto']=$gerente_proyecto;
        $formdata['tipo_proyecto']=$tipo_proyecto;
        $formdata['tag']=$tag;
        $formdata['paisid']='01';
        $formdata['oid']='AND-10';
       
        $newrec=new Admin_Model_DbTable_Proyecto();
        $newrec->_save($formdata);
        if($newrec->_save($formdata))
        {

            $pk  =   array(                        
                            'codigo_prop_proy'   =>$codigo_prop_proy,
                            'propuestaid'   =>$propuestaid,
                            'revision'   =>$revision,
                                
                            );
            $data = array(
                            'isproyecto' =>  'S'
                         );


            $updisproject=new Admin_Model_DbTable_Propuesta();
            $updisproject->_updateX($data,$pk);

        }


            
    }

    public function codproypropAction() {
      $this->_helper->layout()->disableLayout();
      $propuestaid= $this->_getParam("propuesta");
      //$propuestaid= '15.10.015';

      $dbpropuestaid = new Admin_Model_DbTable_Propuesta();
      $where['propuesta']=$propuestaid;
      $propuesta=  $dbpropuestaid->_getFilter($propuestaid);
      $this->view->propuesta = $propuesta;
      print_r($propuesta);

    }

    public function clienteAction() {
      $this->_helper->layout()->disableLayout();
      $propuestaid= $this->_getParam("propuesta");
      //$propuestaid= '15.10.015';

      $dbpropuestaid = new Admin_Model_DbTable_Propuesta();
      $where['propuesta']=$propuestaid;
      $propuesta=  $dbpropuestaid->_getFilter($propuestaid);
      $this->view->propuesta = $propuesta;
      //print_r($propuesta);

    }

    public function umineraAction() {
      $this->_helper->layout()->disableLayout();
      $propuestaid= $this->_getParam("propuesta");
      

      $dbpropuestaid = new Admin_Model_DbTable_Propuesta();
      $where['propuesta']=$propuestaid;
      $propuesta=  $dbpropuestaid->_getFilter($propuestaid);
      $this->view->propuesta = $propuesta;
      //print_r($propuesta);

    }

    public function revisionAction() {
      $this->_helper->layout()->disableLayout();
      $propuestaid= $this->_getParam("propuesta");
      //$propuestaid= '15.10.015';

      $dbpropuestaid = new Admin_Model_DbTable_Propuesta();
      $where['propuesta']=$propuestaid;
      $propuesta=  $dbpropuestaid->_getFilter($propuestaid);
      $this->view->propuesta = $propuesta;
      //print_r($propuesta);

    }


    public function cambiarestadoAction() {

        $this->_helper->layout()->disableLayout();
        $proyectoid= $this->_getParam("proyectoid");
        $propuestaid= $this->_getParam("propuesta");
        $nombre_proyecto= $this->_getParam("nombre_proyecto");
        $codigo_prop_proy= $this->_getParam("codigo_prop_proy");
        $estado= $this->_getParam("estado");
        $revision= $this->_getParam("revision");
        $oid= $this->_getParam("oid");
        $gerente_proyecto= $this->_getParam("gerente_proyecto");
        $control_proyecto= $this->_getParam("control_proyecto");
        $control_documentario= $this->_getParam("control_documentario");
        $tipo_proyecto= $this->_getParam("tipo_proyecto");
        $paisid= $this->_getParam("paisid");
        $fecha_inicio= $this->_getParam("fecha_inicio");
         
        $pk  =   array(                        
                        'codigo_prop_proy'   =>$codigo_prop_proy,
                        'proyectoid'   =>$proyectoid,
                        'revision'   =>$revision,
                            
                        );
        $data = array(
                        'estado' =>  $estado
                     );

          
        $updrec=new Admin_Model_DbTable_Proyecto();
        $updrec->_update($data,$pk);


    }
    

    public function deleteAction(){
        try {
            $this->_helper->layout()->disablelayout();
     
            $proyectoid=$this->_getParam("proyectoid");
            $propuesta=$this->_getParam("propuesta");
            $codigo_prop_proy=$this->_getParam("codigo_prop_proy");
            $revision=$this->_getParam("revision");

            $pk  =   array(                        
                        'codigo_prop_proy'   =>$codigo_prop_proy,
                        'proyectoid'   =>$proyectoid,
                                                 
                        );            

            $delproy = new Admin_Model_DbTable_Proyecto();
            $delproy->_delete($pk);         


        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        }
    }



    
}
