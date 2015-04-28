<?php

class Proyecto_IndexController extends Zend_Controller_Action {

    public function init() {
    	        
              $options = array(
            'layout' => 'default',
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

        //$listaproyecto = new Admin_Model_DbTable_Proyecto();

        //try 
        //{

            $form= new Admin_Form_Proyecto();
            // $form->save->setLabel("Guardar");
            $this->view->form=$form;
            // if ($this->getRequest()->isPost())
            // {
            //     $formdata = $this->getRequest()->getPost();
            //     if ($form->isValid($formdata)) {
            //         unset($formdata['save']);
            //         $formdata['eid']=$eid;
            //         $formdata['oid']=$oid;
            //         $formdata['register']=$reg;
            //         trim($formdata['facid']);
            //         trim($formdata['name']);
            //         trim($formdata['abbreviation']);
            //         trim($formdata['created']);
            //         trim($formdata['state']);
            //         $newrec=new Api_Model_DbTable_Faculty();
            //         $newrec->_save($formdata);
            //         //print_r($formdata);
            //         $this->_redirect("/admin/faculty");
            //         }
            //         else
            //         {
            //             echo "Ingrese Nuevamente";
            //         }
            //     }
            // } 
            // catch (Exception $ex) 
            // {
            //   print "Error ".$ex->getMessage();
            // }
            
            
    }
    
    
}
