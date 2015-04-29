<?php

class Proyecto_IndexController extends Zend_Controller_Action {

    public function init() {
    	$options = array(
            'layout' => 'layout',
        );
        Zend_Layout::startMvc($options);
    }
    
    public function indexAction() {
        
      echo "ggggg";
      exit();
            
    }
	
    public function listarAction() {

        $listaproyecto = new Admin_Model_DbTable_Proyecto();
        $lista=$listaproyecto->_getProyectoAll();
        $this->view->listaproyecto = $lista;
            
    }


    public function nuevoAction() {

        $propuestas = new Admin_Model_DbTable_Propuesta();
        $prop=$propuestas->_getPropuestaAll();       
        $this->view->propuestas=$prop;


        //try 
        //{

            $form= new Admin_Form_Proyecto();
            // $form->save->setLabel("Guardar");
            $this->view->form=$form;
            //if ($this->getRequest()->isPost())
            //{
                //$formdata = $this->getRequest()->getPost();

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

 
                 // print_r($proyectoid['0']); 
                 // echo "---";                
                 // print_r($propuestaid);                 
                 // echo "---";                
                 // print_r($nombre_proyecto);                 
                 // echo "---";                
                 // print_r($codigo_prop_proy);                 
                 // echo "---";                
                 // print_r($control_proyecto);                 
                 // echo "---";                
                 // print_r($revision);                 
                 // echo "---";                
                 // print_r($descripcion);                 
                 // echo "---";                
                 // print_r($fecha_inicio);                 
                 // echo "---";                
                 // print_r($control_documentario);                 
                 // echo "---";                
                 // print_r($estado);                 
                 // echo "---";                
                 // print_r($gerente_proyecto);                 
                 // echo "---";                
                 // print_r($tipo_proyecto);                 
                 // echo "---";                
                 // print_r($tag);



                 //print $codigo_prop_proy;
                    //if ($form->isValid($formdata)) {
                       // unset($formdata['guardar']);
                       // unset($formdata['unidad_minera']);
                       // unset($formdata['clienteid']);
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
                
                       
                       $formdata['paisid']='01';
                       $formdata['oid']='AND-10';
       
                       //print_r($formdata);

                      $newrec=new Admin_Model_DbTable_Proyecto();
                      $newrec->_save($formdata);
                      //if($newrec->_save($formdata))
                      //{
                            //$this->_redirect("/proyecto/index/listar");
                      //}
                 
                      
                //         //print_r($formdata);
                //         }
                //         else
                //         {
                //         }
                //     }
                    //} 
                // catch (Exception $ex) 
                // {
                //   print "Error ".$ex->getMessage();
                // } 
            
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
      print_r($propuesta);

    }

    public function umineraAction() {
      $this->_helper->layout()->disableLayout();
      $propuestaid= $this->_getParam("propuesta");
      //$propuestaid= '15.10.015';

      $dbpropuestaid = new Admin_Model_DbTable_Propuesta();
      $where['propuesta']=$propuestaid;
      $propuesta=  $dbpropuestaid->_getFilter($propuestaid);
      $this->view->propuesta = $propuesta;
      print_r($propuesta);

    }

    public function revisionAction() {
      $this->_helper->layout()->disableLayout();
      $propuestaid= $this->_getParam("propuesta");
      //$propuestaid= '15.10.015';

      $dbpropuestaid = new Admin_Model_DbTable_Propuesta();
      $where['propuesta']=$propuestaid;
      $propuesta=  $dbpropuestaid->_getFilter($propuestaid);
      $this->view->propuesta = $propuesta;
      print_r($propuesta);

    }


    public function cambiarestadoAction() {
          //$this->_helper->layout()->disableLayout();
          $propuestaid= $this->_getParam("propuesta");
          //$propuestaid= '15.10.015';

          $dbpropuestaid = new Admin_Model_DbTable_Proyecto();
          $where['propuesta']=$propuestaid;
          $propuesta=  $dbpropuestaid->_getFilter($propuestaid);
          $this->view->propuesta = $propuesta;
          print_r($propuesta);

    }
    
    
}
