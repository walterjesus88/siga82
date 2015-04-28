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

        //$listaproyecto = new Admin_Model_DbTable_Proyecto();

        //try 
        //{

            $form= new Admin_Form_Proyecto();
            // $form->save->setLabel("Guardar");
            $this->view->form=$form;
            //if ($this->getRequest()->isPost())
            //{
                $formdata = $this->getRequest()->getPost();

                 $codigo_prop_proy = $this->_getParam('codigo_prop_proy');
                 $proyectoid = $this->_getParam('proyectoid');
                 $propuestaid = $this->_getParam('propuestaid');
                 $revision = $this->_getParam('revision');   
                 $nombre_proyecto = $this->_getParam('nombre_proyecto');
                 $gerente_proyecto = $this->_getParam('gerente_proyecto');
                 $control_proyecto = $this->_getParam('control_proyecto');
                 $control_documentario = $this->_getParam('control_documentario');
                 $estado = $this->_getParam('estado');
                 $tipo_proyecto = $this->_getParam('tipo_proyecto');
                 $fecha_inicio = $this->_getParam('fecha_inicio');

                 //print $codigo_prop_proy;
                    if ($form->isValid($formdata)) {
                       unset($formdata['guardar']);
                       unset($formdata['unidad_minera']);
                       unset($formdata['clienteid']);
                       $formdata['codigo_prop_proy']=$codigo_prop_proy;
                       $formdata['proyectoid']=$proyectoid;
                       $formdata['propuestaid']=$propuestaid;
                       $formdata['revision']=$revision;
                       $formdata['paisid']='01';
                       $formdata['oid']='AND-10';
                       $formdata['fecha_inicio']=$fecha_inicio;
                       $formdata['nombre_proyecto']=$nombre_proyecto;
                       $formdata['gerente_proyecto']=$gerente_proyecto;
                       $formdata['control_proyecto']=$control_proyecto;
                       $formdata['control_documentario']=$control_documentario;
                       $formdata['estado']=$estado;
                       $formdata['tipo_proyecto']=$tipo_proyecto;
               
                       print_r($formdata);
                       $newrec=new Admin_Model_DbTable_Proyecto();
                       if($newrec->_save($formdata))
                       {

                           $this->_redirect("/proyecto/index/listar");
                       }
                       else
                       {

                           echo "Ingrese Nuevamente";
                       }
                //         //print_r($formdata);
                //         }
                //         else
                //         {
                //         }
                //     }
                    } 
                // catch (Exception $ex) 
                // {
                //   print "Error ".$ex->getMessage();
                // }
            
            
    }
    
    
}
