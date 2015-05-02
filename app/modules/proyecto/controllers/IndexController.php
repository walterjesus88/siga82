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
        $formdata['fecha_probable_cierre']=date("Y-m-d h:m:s"); 
        $formdata['fecha_cierre']=date("Y-m-d h:m:s");
        $formdata['fecha_inicio']=$fecha_inicio;
        $formdata['control_documentario']=$control_documentario;
        $formdata['estado']=$estado;
        $formdata['gerente_proyecto']=$gerente_proyecto;
        $formdata['tipo_proyecto']=$tipo_proyecto;
        $formdata['tag']=$tag;
        $formdata['paisid']='01';
        $formdata['oid']='AND-10';
       
        $newrec=new Admin_Model_DbTable_Proyecto();
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
        
        if($updrec->_update($data,$pk))
        {   ?>
          <script>                  
            document.location.href="/proyecto/index/listar";
          </script>
                <?php
            }
        else
        {   ?>
          <script>                  
          alert("Error al Cambiar estado verifique porfavor");
          document.location.href="/proyecto/index/listar";                                                 
          </script>
         <?php
            }
    }


    public function editarAction() {

        //$this->_helper->layout()->disableLayout();
        $proyectoid= $this->_getParam("proyectoid");
        $propuestaid= $this->_getParam("propuestaid");
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
        /*campos adicionales*/
        $ubicacion= $this->_getParam("ubicacion");
        $departamentoid= $this->_getParam("departamentoid");
        $provinciaid= $this->_getParam("provinciaid");
        $provinciaid= $this->_getParam("provinciaid");
        $distritoid= $this->_getParam("distritoid");
        $prioridad= $this->_getParam("prioridad");
        $progreso= $this->_getParam("progreso");
        $fecha_probable_cierre= $this->_getParam("fecha_probable_cierre");        
        $fecha_cierre= $this->_getParam("fecha_cierre");
        $monto_total= $this->_getParam("monto_total");
        $moneda= $this->_getParam("moneda");
        $acs= $this->_getParam("acs");
        $monto_total= $this->_getParam("monto_total");
        $sistema= $this->_getParam("sistema");
        $unidad_red= $this->_getParam("unidad_red");
        $ruta= $this->_getParam("ruta");
        $sistema= $this->_getParam("sistema");
        $descripcion= $this->_getParam("descripcion");
        $observacion= $this->_getParam("observacion");
        //print_r($propuestaid);
        $color_estilo= $this->_getParam("color_estilo");
        $tag= $this->_getParam("tag");
         
        $pk  =   array(                        
                        'codigo_prop_proy'   =>$codigo_prop_proy,
                        'proyectoid'   =>$proyectoid,
                        //'revision'   =>$revision,
                            
                        );
        $data = array(
                        //'proyectoid' =>  $proyectoid,
                        'propuestaid' =>  $propuestaid,
                        'nombre_proyecto' =>  $nombre_proyecto,
                        //'codigo_prop_proy' =>  $codigo_prop_proy,
                        'estado' =>  $estado,
                        'revision' =>  $revision,
                        'oid' =>  $oid,
                        'gerente_proyecto' =>  $gerente_proyecto,
                        'control_proyecto' =>  $control_proyecto,
                        'control_documentario' =>  $control_documentario,
                        'tipo_proyecto' =>  $tipo_proyecto,
                        'paisid' =>  $paisid,
                        'fecha_inicio' =>  $fecha_inicio,                    
                        //--------------------//
                        'ubicacion' =>  $ubicacion,
                        'departamentoid' =>  $departamentoid,
                        'provinciaid' =>  $provinciaid,
                        'distritoid' =>  $distritoid,
                        'prioridad' =>  $prioridad,
                        'progreso' =>  $progreso,                  
                        'fecha_probable_cierre' =>  $fecha_probable_cierre,
                        'fecha_cierre' =>  $fecha_cierre,
                        'monto_total' =>  $monto_total,
                        'moneda' =>  $moneda,
                        'acs' =>  $acs,
                        'monto_total' =>  $monto_total,
                        'sistema' =>  $sistema,
                        'unidad_red' =>  $unidad_red,
                        'ruta' =>  $ruta,
                        'sistema' =>  $sistema,
                        'descripcion' =>  $descripcion,
                        'observacion' =>  $observacion,
                        'color_estilo' =>  $color_estilo,
                        'tag' =>  $tag,
                    );

      
        $updrec=new Admin_Model_DbTable_Proyecto();
        
        if($updrec->_update($data,$pk))
        {   ?>
          <script>                  
            //document.location.href="/proyecto/index/listar";
          </script>
                <?php
            }
        else
        {   ?>
          <script>                  
          //alert("Error al Cambiar estado verifique porfavor");
          //document.location.href="/proyecto/index/listar";                                                 
          </script>
         <?php
            } 
    }



    public function buscarAction() {
        $this->_helper->layout()->disableLayout();
        $buscar_proyecto=$this->_getParam('proyecto');
        $buscar_proyecto=strtolower($buscar_proyecto);
        $buscaproyecto = new Admin_Model_DbTable_Proyecto();
        $buscar=$buscaproyecto->_buscarProyecto($buscar_proyecto);
        $this->view->lista_buscar = $buscar;
      
       
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

            print_r($pk);

            $delproy = new Admin_Model_DbTable_Proyecto();
            $delproy->_delete($pk);         


        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        }
    }



    
}
