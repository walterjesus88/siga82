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
        //$lista=$listaproyecto->_getProyectoAll();
        $lista=$listaproyecto->_getProyectosTodosAnddes();
        $this->view->listaproyecto = $lista;
    }
    
    public function nuevoAction() {

        $propuestas = new Admin_Model_DbTable_Propuesta();
        $prop=$propuestas->_getPropuestaxnoproyectxganado();       
        $this->view->propuestas=$prop;

        $form= new Admin_Form_Proyecto();        
        $this->view->form=$form;   
        $codigo_prop_proy = $this->_getParam('cod_proy_prop');
        $proyectoid = $this->_getParam('proyectoid');
        $e = $this->_getParam('E');
 

        $propuestaid = $this->_getParam('propuesta');
        $nombre_proyecto = $this->_getParam('nombre_proyecto');
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

    public function equipoAction() {
      $this->_helper->layout()->disableLayout();
      $proyectoid= $this->_getParam("proyectoid");
      $codigo_prop_proy= $this->_getParam("codigo_prop_proy");
      $areaid=$this->_getParam("areaid");
      $this->view->proyectoid = $proyectoid;
      $this->view->codigo_prop_proy = $codigo_prop_proy;
      $this->view->areaid = $areaid;
      $bdarea_cat = new Admin_Model_DbTable_Areacategoria();
      $listcat=$bdarea_cat->_buscarCategoriaxAreaxProyecto($areaid);
      $this->view->categoria = $listcat;
    }

    public function modificarequipoAction() {
      $this->_helper->layout()->disableLayout();
      $proyectoid= $this->_getParam("proyectoid");
      $codigo_prop_proy= $this->_getParam("codigo_prop_proy");
      $areaid=$this->_getParam("areaid");
      $dni=$this->_getParam("dni");
      $uid=$this->_getParam("uid");
      $this->view->proyectoid = $proyectoid;
      $this->view->codigo_prop_proy = $codigo_prop_proy;
      $this->view->areaid = $areaid;
      $this->view->dni = $dni;
      $this->view->uid = $uid;
      $bdequipo = new Admin_Model_DbTable_Equipo();
      $wheres=array('codigo_prop_proy'=>$codigo_prop_proy,'proyectoid'=>$proyectoid,'uid'=>$uid,'dni'=>$dni,'areaid'=>$areaid);

      $datos_usuario=$bdequipo->_getUsuarioxProyecto($wheres);
      $this->view->datos_usuario = $datos_usuario;
      //print_r($datos_usuario);
    }


      public function updateequipoAction() {

        $this->_helper->layout()->disableLayout();
        $proyectoid= $this->_getParam("proyectoid");
        $codigo_prop_proy= $this->_getParam("codigo");
        $dni= $this->_getParam("dni");
        $uid= $this->_getParam("uid");
        $areaid= $this->_getParam("areaid");
        $rate= $this->_getParam("rate");
        
         
        $pk  =   array(                        
                        'codigo_prop_proy'   =>$codigo_prop_proy,
                        'proyectoid'   =>$proyectoid,
                        'uid'   =>$uid,
                        'dni'   =>$dni,
                        'areaid'   =>$areaid,
                            
                        );
        $data = array(
                        'rate_proyecto' =>  $rate
                     );

          
        $update_equipo=new Admin_Model_DbTable_Equipo();
        //$update_equipo->_update($data,$pk);
        
        if($update_equipo->_update($data,$pk))
        {   ?>
          <script>                  
          alert("Actualizado");
            //document.location.href="/proyecto/index/listar";
          </script>
                <?php
            }
        else
        {   ?>
          <script>                  
          alert("Error al Cambiar estado verifique porfavor");
          //document.location.href="/proyecto/index/listar";                                                 
          </script>
         <?php
            }
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

        $proyectoid= $this->_getParam("proyectoid");
        $codigo_prop_proy= $this->_getParam("codigo_prop_proy");

        $form= new Admin_Form_Proyecto();
     
        $editproyect= new Admin_Model_DbTable_Proyecto();
        $where = array(
                      'codigo_prop_proy'    => $codigo_prop_proy,
                      'proyectoid'    => $proyectoid,
                      );
        $edit = $editproyect->_getOne($where);
        $this->view->form = $form;
        $form->populate($edit);



        if ($this->getRequest()->isPost()) {
            $formdata = $this->getRequest()->getPost();
              if ($form->isValid($formdata)) {
                    unset($formdata['save']);
                    unset($formdata['whySubmit']);
                    $pk = array(    'codigo_prop_proy'    => $codigo_prop_proy,
                                    'proyectoid'    => $proyectoid,
                                ); 
                   
                    $updrec=new Admin_Model_DbTable_Proyecto();
               
                    if($updrec->_update($formdata,$pk))
                    {   ?>
                        <script>                  
                          document.location.href="/proyecto/index/listar";
                          
                        </script>
                        <?php
                    }
                    else
                    {   ?>
                          <script>                  
                          alert("Error al guardar verifique porfavor");
                          //document.location.href="/proyecto/index/listar";                                                 
                          </script>
                         <?php
                    } 

              }
        }

      
    }



    public function buscarAction() {
        $this->_helper->layout()->disableLayout();
        $buscar_proyecto=$this->_getParam('proyecto');
        $buscar_proyecto=strtolower($buscar_proyecto);
        $buscaproyecto = new Admin_Model_DbTable_Proyecto();
        //$buscar=$buscaproyecto->_buscarProyecto($buscar_proyecto);
        $buscar=$buscaproyecto->_buscarProyectoxReplicon($buscar_proyecto);

        
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



  public function leerexcelAction(){
    try {
      $dir = APPLICATION_LIBRARY . "/excel/excel/reader.php";
      include ($dir);
      $data = new Spreadsheet_Excel_Reader();
      $data->setOutputEncoding('CP1251');
      $data->read('replicon1.xls');
      $numero=1;
      for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
          $actividad=$data->sheets[0]['cells'][$i][7];
          $proyecto=$data->sheets[0]['cells'][$i][5];
          $moneda=$data->sheets[0]['cells'][$i][3];
          $rate=$data->sheets[0]['cells'][$i][4];
          $tipo_actividada=$data->sheets[0]['cells'][$i][6];
          $actividadeshijas = explode("/",$actividad);

          if (count($actividadeshijas)=='1'){
          }

          if (count($actividadeshijas)=='2'){
            //echo utf8_encode($actividadeshijas[1]); echo "<br>"; 
            $datosactividadpadre["actividadid"]=$numero;
            $datosactividadpadre["codigo_actividad"]=$proyecto."-".$numero;

            
            $editproyect= new Admin_Model_DbTable_Proyecto();
            $where = array(
            'proyectoid'    => $proyecto,
            );
            $edit = $editproyect->_getOnexcodigoproyecto($where);
            if ($edit)
            {
              //print_r($edit['codigo_prop_proy']); echo "<br>";
              $datosactividadpadre["codigo_prop_proy"]=$edit['codigo_prop_proy'];
              $datosactividadpadre["revision"]=$edit['revision'];
              $datosactividadpadre["areaid"]='00';
              $datosactividadpadre["proyectoid"]=$proyecto;
              $datosactividadpadre["propuestaid"]=$edit['propuestaid'];
              $datosactividadpadre["actividad_padre"]=null;
              $datosactividadpadre["nombre"]= utf8_encode(trim($actividadeshijas[1]));
              $datosactividadpadre["fecha_creacion"]=date("Y-m-d");
              $datosactividadpadre["estado"]='P';
              $datosactividadpadre["duracion_total"]='0';
              $datosactividadpadre["h_propuesta"]='0';
              $datosactividadpadre["h_extra"]='0';
              $datosactividadpadre["h_planificada"]='0';
              $datosactividadpadre["orden"]=$numero;
              $datosactividadpadre["isproyecto"]='S';
              $datosactividadpadre["moneda"]=$moneda;
             // print_r($datosactividadpadre);echo "<br>";
              
              $bdactividad = new Admin_Model_DbTable_Actividad();
              $existeactividad=$bdactividad->_existeactividad(utf8_encode(trim($actividadeshijas[1])),$proyecto,$edit['codigo_prop_proy'],$edit['revision'],$edit['propuestaid']);
              if ($existeactividad)
              {
                echo "----------------EXISTE LA ACTIVIDAD----------";
                print_r($existeactividad);  
                echo "<br>";
              }
              else {
                $guardaractividad=$bdactividad->_save($datosactividadpadre);
                if ($guardaractividad)
                  {
                    echo "---------SE GUARDO LA ACTIVIDAD ------";
                    echo "<br>";
                    $numero++;
                  }
                else
                  {
                    echo "------ ERROR NO SE GUARADO REVISARLO ---- ";
                    echo "<br>";

                  }
              }
            }
            else
            {
              echo "no existe proyecto";
            }
          }


        
      }

    }
    catch (Exception $e) {
        print "Error: ".$e->getMessage();
    }
  }




  public function leerexcel2Action(){
    try {
      $dir = APPLICATION_LIBRARY . "/excel/excel/reader.php";
      include ($dir);
      $data = new Spreadsheet_Excel_Reader();
      $data->setOutputEncoding('CP1251');
      $data->read('replicon1.xls');
      $numero=1;
      for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
          $actividad=$data->sheets[0]['cells'][$i][7];
          $proyecto=$data->sheets[0]['cells'][$i][5];
          $moneda=$data->sheets[0]['cells'][$i][3];
          $rate=$data->sheets[0]['cells'][$i][4];
          $tipo_actividada=$data->sheets[0]['cells'][$i][6];
          $actividadeshijas = explode("/",$actividad);

          if (count($actividadeshijas)=='1'){
          }

          if (count($actividadeshijas)=='3'){
            //echo utf8_encode($actividadeshijas[1]); echo "<br>"; 
           
            $datosactividadpadre["codigo_actividad"]=$proyecto."-".$numero;

            
            $editproyect= new Admin_Model_DbTable_Proyecto();
            $where = array(
            'proyectoid'    => $proyecto,
            );
            $edit = $editproyect->_getOnexcodigoproyecto($where);
            if ($edit)
            {
              //print_r($edit['codigo_prop_proy']); echo "<br>";
              $datosactividadpadre["codigo_prop_proy"]=$edit['codigo_prop_proy'];
              $datosactividadpadre["revision"]=$edit['revision'];
              $datosactividadpadre["areaid"]='00';
              $datosactividadpadre["proyectoid"]=$proyecto;
              $datosactividadpadre["propuestaid"]=$edit['propuestaid'];
              
              $datosactividadpadre["nombre"]= utf8_encode(trim($actividadeshijas[2]));
              $datosactividadpadre["fecha_creacion"]=date("Y-m-d");
              $datosactividadpadre["estado"]='P';
              $datosactividadpadre["duracion_total"]='0';
              $datosactividadpadre["h_propuesta"]='0';
              $datosactividadpadre["h_extra"]='0';
              $datosactividadpadre["h_planificada"]='0';
              $datosactividadpadre["orden"]=$numero;
              $datosactividadpadre["isproyecto"]='S';
              $datosactividadpadre["moneda"]=$moneda;
             // print_r($datosactividadpadre);echo "<br>";
              
              $bdactividad = new Admin_Model_DbTable_Actividad();
              $existeactividad=$bdactividad->_existeactividad(utf8_encode(trim($actividadeshijas[1])),$proyecto,$edit['codigo_prop_proy'],$edit['revision'],$edit['propuestaid']);
              if ($existeactividad)
              {
                $datosactividadpadre["actividad_padre"]=$existeactividad[0]['actividadid'];
                $datosactividadpadre["actividadid"]=$existeactividad[0]['actividadid']."-".$numero;
                $guardaractividad=$bdactividad->_save($datosactividadpadre);
                print_r($guardaractividad);
               /* if ($guardaractividad)
                  {
                    echo "---------SE GUARDO LA ACTIVIDAD ------";
                    echo "<br>";
                    $numero++;
                  }
                else
                  {
                    echo "------ ERROR NO SE GUARADO REVISARLO ---- ";
                    echo "<br>";

                  }*/
                
              }
              else {

              echo "-----------no existe l actividad padre revisar------------------";                 }
            }
            else
            {
              echo "no existe proyecto";
            }
          }


        
      }

    }
    catch (Exception $e) {
        print "Error: ".$e->getMessage();
    }
  }




  public function leerexceltareoAction(){
    try {
      $dir = APPLICATION_LIBRARY . "/excel/excel/reader.php";
      include ($dir);
      $data = new Spreadsheet_Excel_Reader();
      $data->setOutputEncoding('CP1251');
      $data->read('replicon1.xls');
      $numero=1;

    $columnas=$data->sheets[0]['numCols'];
        

      for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
          $actividad=$data->sheets[0]['cells'][$i][7];
          $proyecto=$data->sheets[0]['cells'][$i][5];
          $moneda=$data->sheets[0]['cells'][$i][3];
          $rate=$data->sheets[0]['cells'][$i][4];
          $tipo_actividada=$data->sheets[0]['cells'][$i][6];
          $actividadeshijas = explode("/",$actividad);

          if (count($actividadeshijas)=='1'){
          }

          if (count($actividadeshijas)=='2'){
            //echo utf8_encode($actividadeshijas[1]); echo "<br>"; 
            $datosactividadpadre["actividadid"]=$numero;
            $datosactividadpadre["codigo_actividad"]=$proyecto."-".$numero;

            
            $editproyect= new Admin_Model_DbTable_Proyecto();
            $where = array(
            'proyectoid'    => $proyecto,
            );
            $edit = $editproyect->_getOnexcodigoproyecto($where);
            if ($edit)
            {
                  //print_r($edit['codigo_prop_proy']); echo "<br>";
                  $datosactividadpadre["codigo_prop_proy"]=$edit['codigo_prop_proy'];
                  $datosactividadpadre["revision"]=$edit['revision'];
                  $datosactividadpadre["areaid"]='00';
                  $datosactividadpadre["proyectoid"]=$proyecto;
                  $datosactividadpadre["propuestaid"]=$edit['propuestaid'];
                  $datosactividadpadre["actividad_padre"]=null;
                  $datosactividadpadre["nombre"]= utf8_encode(trim($actividadeshijas[1]));
                  $datosactividadpadre["fecha_creacion"]=date("Y-m-d");
                  $datosactividadpadre["estado"]='P';
                  $datosactividadpadre["duracion_total"]='0';
                  $datosactividadpadre["h_propuesta"]='0';
                  $datosactividadpadre["h_extra"]='0';
                  $datosactividadpadre["h_planificada"]='0';
                  $datosactividadpadre["orden"]=$numero;
                  $datosactividadpadre["isproyecto"]='S';
                  $datosactividadpadre["moneda"]=$moneda;
                 // print_r($datosactividadpadre);echo "<br>";
                  ///// ACTIVIDAD   ////
                  /*$bdactividad = new Admin_Model_DbTable_Actividad();
                  $existeactividad=$bdactividad->_existeactividad(utf8_encode(trim($actividadeshijas[1])),$proyecto,$edit['codigo_prop_proy'],$edit['revision'],$edit['propuestaid']);
                  if ($existeactividad)
                  {
                    echo "----------------EXISTE LA ACTIVIDAD----------";
                    print_r($existeactividad);  
                    echo "<br>";
                  }
                  else {
                    $guardaractividad=$bdactividad->_save($datosactividadpadre);
                    if ($guardaractividad)
                      {
                        echo "---------SE GUARDO LA ACTIVIDAD ------";
                        echo "<br>";
                        $numero++;
                      }
                    else
                      {
                        echo "------ ERROR NO SE GUARADO REVISARLO ---- ";
                        echo "<br>";

                      }
                  }*/
                  ///// ACTIVIDAD   ////
                
                $dni=$data->sheets[0]['cells'][$i][1];
                $moneda=$data->sheets[0]['cells'][$i][3];
                $rate=$data->sheets[0]['cells'][$i][4];
                $codigoproyecto=$data->sheets[0]['cells'][$i][5];
                $tipo_actividad=$data->sheets[0]['cells'][$i][6];
                $actividad=$data->sheets[0]['cells'][$i][7];
                for ($j = 8; $j <= $columnas ; $j++) {
                  $horarealexiste=$data->sheets[0]['cells'][$i][$j];
                  $horaexiste=trim($horarealexiste);
                  

                 if (isset($horaexiste) )
                    {
                      //echo $data->sheets[0]['cells'][$i][$j];
                      //$hora=$horaexiste;

                      $bdtareo = new Admin_Model_DbTable_Tareopersona();
                      $fecha=$data->sheets[0]['cells'][1][$j];
                      $bdactividad = new Admin_Model_DbTable_Actividad();
                      $existeactividad=$bdactividad->_existeactividad(utf8_encode(trim($actividadeshijas[1])),$proyecto,$edit['codigo_prop_proy'],$edit['revision'],$edit['propuestaid']);
                      if ($existeactividad)
                      {
                        //print_r($existeactividad);

                            $datostareo["actividadid"]=$existeactividad[0]['actividadid'];
                            $datostareo["codigo_actividad"]=$existeactividad[0]['codigo_actividad'];
                            $datostareo["codigo_prop_proy"]=$existeactividad[0]['codigo_prop_proy'];
                            $datostareo["revision"]=$existeactividad[0]['revision'];
                            //$datostareo["areaid"]='00';
                            $datostareo["proyectoid"]=$proyecto;
                            $datostareo["actividad_padre"]='0';

                            $datostareo["fecha_tarea"]=$fecha;
                            $datostareo["fecha_creacion"]='2015-05-20';
                            $datostareo["semanaid"]=date('W', strtotime($fecha)); 
                            $datostareo["fecha_planificacion"]=$fecha;
                            $datostareo["asignado"]=$dni;
                            $datostareo["estado"]='C';
                            $datostareo["h_real"]=$horaexiste;
                            $datostareo["etapa"]='CIERRE';
                            $bdpersona = new Admin_Model_DbTable_Usuario();
                            
                            $where = array(
                            'dni'    => $dni,
                            );
                            $existepersona=$bdpersona->_getOne($where);
                            //print_r($existepersona);
                            if ($existepersona)
                            {
                              $datostareo["asignado"]=$dni;
                              $datostareo["dni"]=$dni;
                              $datostareo["uid"]=$existepersona['uid'];
                              $datostareo["areaid"]=$existepersona['areaid'];
                              $datostareo["categoriaid"]=$existepersona['categoriaid'];
                              $datostareo["cargo"]=$rate."-MONEDA-".$moneda;
                              $datostareo["tipo_actividad"]=$tipo_actividad;
                              //print_r($datostareo);echo  "<br>";
                              
                              if($bdtareo->_save($datostareo)){
                                $equipo = new Admin_Model_DbTable_Usuario();

                                  $dataequipo['areaid']=$existepersona['areaid'];
                                  $dataequipo['categoriaid']=$existepersona['categoriaid'];
                                  $dataequipo['proyectoid']=$proyecto;
                                  $dataequipo['codigo_prop_proy']=$existeactividad[0]['codigo_prop_proy'];
                                  $dataequipo['dni']=$dni;
                                  $dataequipo['uid']=$existepersona['uid'];
                                  $dataequipo['cargo']='EQUIPO';
                                  $dataequipo['fecha_ingreso']=date("Y-m-d");
                                  $dataequipo['estado']='A';
                                  $dataequipo['nivel']='4';
                                  $dataequipo['rate_proyecto']=$rate;
                                  $dataequipo['moneda']=$moneda;
                                 // print_r($dataequipo);

                                  $dbequipo = new Admin_Model_DbTable_Equipo();
                                  $dbequipo->_save($dataequipo);


                              echo "----------guardado -------";echo $dni;
                              echo  "<br>";
                              }
                              else
                              {
                                echo "---------ERROR NO GUARDO -----------"; echo $dni;
                              echo  "<br>";
                              }



                            }
                            else
                            { 
                              echo "---------ERROR NO EXISTE DNI -----------"; echo $dni;
                              echo  "<br>";

                            }
                           
                        }
                        else
                        {
                          echo "-----------NO EXISTE LA ACTIVIDAD ------";
                          echo "<br>";
                          /*if ($tipo_actividad='NB')
                          {
                              $datostareonofacturable["codigo_prop_proy"]=$edit['codigo_prop_proy'];
                              $datostareonofacturable["revision"]=$edit['revision'];
                              $datostareonofacturable["areaid"]='00';
                              $datostareonofacturable["proyectoid"]=$proyecto;
                              $datostareonofacturable["propuestaid"]=$edit['propuestaid'];
                              $datostareonofacturable["actividadid"]=;
                              $datostareonofacturable["codigo_actividad"]=$proyecto."-".$numero;


                   
                          }*/
                        }
                      
                      }
                  } 
              }

            else
            {
              echo "no existe proyecto";
            }

          }
        
      }


    }
    catch (Exception $e) {
        print "Error: ".$e->getMessage();
    }
  }




  public function leerexceltareo2Action(){
    try {
      $dir = APPLICATION_LIBRARY . "/excel/excel/reader.php";
      include ($dir);
      $data = new Spreadsheet_Excel_Reader();
      $data->setOutputEncoding('CP1251');
      $data->read('replicon1.xls');
      $numero=1;

    $columnas=$data->sheets[0]['numCols'];
        

      for ($i = 1; $i <= $data->sheets[0]['numRows']; $i++) {
          $actividad=$data->sheets[0]['cells'][$i][7];
          $proyecto=$data->sheets[0]['cells'][$i][5];
          $moneda=$data->sheets[0]['cells'][$i][3];
          $rate=$data->sheets[0]['cells'][$i][4];
          $tipo_actividada=$data->sheets[0]['cells'][$i][6];
          $actividadeshijas = explode("/",$actividad);

          if (count($actividadeshijas)=='1'){
          }

          if (count($actividadeshijas)=='3'){
            //echo utf8_encode($actividadeshijas[1]); echo "<br>"; 
            $datosactividadpadre["actividadid"]=$numero;
            $datosactividadpadre["codigo_actividad"]=$proyecto."-".$numero;

            
            $editproyect= new Admin_Model_DbTable_Proyecto();
            $where = array(
            'proyectoid'    => $proyecto,
            );
            $edit = $editproyect->_getOnexcodigoproyecto($where);
            if ($edit)
            {
                  //print_r($edit['codigo_prop_proy']); echo "<br>";
                 
                  $datosactividadpadre["codigo_prop_proy"]=$edit['codigo_prop_proy'];
                  $datosactividadpadre["revision"]=$edit['revision'];
                  $datosactividadpadre["areaid"]='00';
                  $datosactividadpadre["proyectoid"]=$proyecto;
                  $datosactividadpadre["propuestaid"]=$edit['propuestaid'];
                  $datosactividadpadre["actividad_padre"]=null;
                  $datosactividadpadre["nombre"]= utf8_encode(trim($actividadeshijas[1]));
                  $datosactividadpadre["fecha_creacion"]=date("Y-m-d");
                  $datosactividadpadre["estado"]='P';
                  $datosactividadpadre["duracion_total"]='0';
                  $datosactividadpadre["h_propuesta"]='0';
                  $datosactividadpadre["h_extra"]='0';
                  $datosactividadpadre["h_planificada"]='0';
                  $datosactividadpadre["orden"]=$numero;
                  $datosactividadpadre["isproyecto"]='S';
                  $datosactividadpadre["moneda"]=$moneda;
                 // print_r($datosactividadpadre);echo "<br>";
                  ///// ACTIVIDAD   ////
                  $bdactividad = new Admin_Model_DbTable_Actividad();
                  $existeactividad=$bdactividad->_existeactividad(utf8_encode(trim($actividadeshijas[1])),$proyecto,$edit['codigo_prop_proy'],$edit['revision'],$edit['propuestaid']);
                  /*if ($existeactividad)
                  {
                    echo "----------------EXISTE LA ACTIVIDAD----------";
                    print_r($existeactividad);  
                    echo "<br>";
                  }
                  else {
                    $guardaractividad=$bdactividad->_save($datosactividadpadre);
                    if ($guardaractividad)
                      {
                        echo "---------SE GUARDO LA ACTIVIDAD ------";
                        echo "<br>";
                        $numero++;
                      }
                    else
                      {
                        echo "------ ERROR NO SE GUARADO REVISARLO ---- ";
                        echo "<br>";

                      }
                  }*/
                  ///// ACTIVIDAD   ////
                
                $dni=$data->sheets[0]['cells'][$i][1];
                $moneda=$data->sheets[0]['cells'][$i][3];
                $rate=$data->sheets[0]['cells'][$i][4];
                $codigoproyecto=$data->sheets[0]['cells'][$i][5];
                $tipo_actividad=$data->sheets[0]['cells'][$i][6];
                $actividad=$data->sheets[0]['cells'][$i][7];

                for ($j = 8; $j <= $columnas ; $j++) {
                  $horarealexiste=$data->sheets[0]['cells'][$i][$j];
                  $horaexiste=trim($horarealexiste);
                  

                 if (isset($horaexiste) )
                    {
                      //echo $data->sheets[0]['cells'][$i][$j];
                      //$hora=$horaexiste;

                      $bdtareo = new Admin_Model_DbTable_Tareopersona();
                      $fecha=$data->sheets[0]['cells'][1][$j];
                      $bdactividad = new Admin_Model_DbTable_Actividad();
                      $existeactividadhija=$bdactividad->_existeactividadhija(utf8_encode(trim($actividadeshijas[2])),$proyecto,$edit['codigo_prop_proy'],$edit['revision'],$edit['propuestaid']);
                      if ($existeactividadhija)
                      {
                        

                        

                            $datostareo["actividadid"]=$existeactividadhija[0]['actividadid'];
                            $datostareo["codigo_actividad"]=$existeactividadhija[0]['codigo_actividad'];
                            $datostareo["codigo_prop_proy"]=$existeactividadhija[0]['codigo_prop_proy'];
                            $datostareo["revision"]=$existeactividadhija[0]['revision'];
                            //$datostareo["areaid"]='00';
                            $datostareo["proyectoid"]=$proyecto;
                            $datostareo["actividad_padre"]=$existeactividad[0]['actividadid'];

                            $datostareo["fecha_tarea"]=$fecha;
                            $datostareo["fecha_creacion"]='2015-05-20';
                            $datostareo["semanaid"]=date('W', strtotime($fecha)); 
                            $datostareo["fecha_planificacion"]=$fecha;
                            $datostareo["asignado"]=$dni;
                            $datostareo["estado"]='C';
                            $datostareo["h_real"]=$horaexiste;
                            $datostareo["etapa"]='CIERRE';
                            $bdpersona = new Admin_Model_DbTable_Usuario();
                            
                            $where = array(
                            'dni'    => $dni,
                            );
                            $existepersona=$bdpersona->_getOne($where);
                            //print_r($existepersona);
                            if ($existepersona)
                            {
                              $datostareo["asignado"]=$dni;
                              $datostareo["dni"]=$dni;
                              $datostareo["uid"]=$existepersona['uid'];
                              $datostareo["areaid"]=$existepersona['areaid'];
                              $datostareo["categoriaid"]=$existepersona['categoriaid'];
                              $datostareo["cargo"]=$rate."-MONEDA-".$moneda;
                              $datostareo["tipo_actividad"]=$tipo_actividad;
                             //print_r($datostareo);echo  "<br>";
                              
                              if($bdtareo->_save($datostareo)){
                                $equipo = new Admin_Model_DbTable_Usuario();

                                  $dataequipo['areaid']=$existepersona['areaid'];
                                  $dataequipo['categoriaid']=$existepersona['categoriaid'];
                                  $dataequipo['proyectoid']=$proyecto;
                                  $dataequipo['codigo_prop_proy']=$existeactividad[0]['codigo_prop_proy'];
                                  $dataequipo['dni']=$dni;
                                  $dataequipo['uid']=$existepersona['uid'];
                                  $dataequipo['cargo']='EQUIPO';
                                  $dataequipo['fecha_ingreso']=date("Y-m-d");
                                  $dataequipo['estado']='A';
                                  $dataequipo['nivel']='4';
                                  $dataequipo['rate_proyecto']=$rate;
                                  $dataequipo['moneda']=$moneda;
                                 // print_r($dataequipo);

                                  $dbequipo = new Admin_Model_DbTable_Equipo();
                                  $dbequipo->_save($dataequipo);


                              echo "----------guardado -------";echo $dni;
                              echo  "<br>";
                              }
                              else
                              {
                                echo "---------ERROR NO GUARDO -----------"; echo $dni;
                              echo  "<br>";
                              }



                            }
                            else
                            { 
                              echo "---------ERROR NO EXISTE DNI -----------"; echo $dni;
                              echo  "<br>";

                            }
                           
                        }
                        else
                        {
                          echo "-----------NO EXISTE LA ACTIVIDAD HIJA ------"; echo $actividadeshijas[2];
                          echo "-----actividadddddd----"; echo $existeactividad[0]['actividadid'];
                          echo "<br>";
                       
                        }
                      
                      }
                  } 
              }

            else
            {
              echo "no existe proyecto";
            }

          }
        
      }


    }
    catch (Exception $e) {
        print "Error: ".$e->getMessage();
    }
  }



  public function leerfechasAction(){
    try {
      $dir = APPLICATION_LIBRARY . "/excel/excel/reader.php";
      include ($dir);
      $data = new Spreadsheet_Excel_Reader();
      $data->setOutputEncoding('CP1251');
      $data->read('replicon1.xls');
      $numero=1;
    
$fila=$data->sheets[0]['numRows'];

for ($j = 8; $j <= $data->sheets[0]['numCols']-1; $j++) {
        for ($i = 2; $i <= $fila; $i++) {    
            $dni=$data->sheets[0]['cells'][$i][1];
            $moneda=$data->sheets[0]['cells'][$i][3];
            $rate=$data->sheets[0]['cells'][$i][4];
            $codigoproyecto=$data->sheets[0]['cells'][$i][5];
            $tipo_actividad=$data->sheets[0]['cells'][$i][6];
            $actividad=$data->sheets[0]['cells'][$i][7];
            $fecha1=$data->sheets[0]['cells'][1][$j];

            echo $dni; echo "-----";
            echo $moneda; echo "-----";
            echo $rate; echo "-----";
            echo $codigoproyecto; echo "-----";
            echo $tipo_actividad; echo "-----";
            echo utf8_encode($actividad); echo "-----";
             echo $fecha1; echo "-----";
             echo "<br>";

              $bdpersona = new Admin_Model_DbTable_Usuario();
                            
                            $where = array(
                            'dni'    => $dni,
                            );
                            $existepersona=$bdpersona->_getOne($where);
                            //print_r($existepersona);
                            if ($existepersona)
                            {
                              
                              
                                echo "---------existe -----------"; echo $dni;
                              echo  "<br>";




                            }
                            else
                            { 
                              echo "---------ERROR NOE EXISTE DNI -----------"; echo $dni;
                              echo  "<br>";

                            }

    }
}


    }
    catch (Exception $e) {
        print "Error: ".$e->getMessage();
    }
  }

public function subirpropuestaAction(){
  try {
    $proyectoid= $this->_getParam("proyectoid");
    $codigo_prop_proy= $this->_getParam("codigo_prop_proy");
    $bandera= $this->_getParam("bandera");
    if ($bandera=='S')
    {
    $this->view->bandera='S';}
    $editproyect= new Admin_Model_DbTable_Proyecto();
    $where = array(
      'codigo_prop_proy'    => $codigo_prop_proy,
      'proyectoid'    => $proyectoid,
    );
    $edit = $editproyect->_getOne($where);
    //print_r($edit);
    $this->view->proyectoid = $edit['proyectoid'];
    $this->view->codigo = $edit['codigo_prop_proy'];
    $this->view->propuestaid = $edit['propuestaid'];
    $this->view->revision = $edit['revision'];
    $this->view->moneda = $edit['moneda'];


    $nombre_fichero = './upload/proyecto/'.$proyectoid.'-HH.xls';
    if (file_exists($nombre_fichero)) {
      $this->view->bandera='S';      
      } else {
      $this->view->bandera='N';
      $this->view->cargar='S';
      }
    }
    catch (Exception $e) {
    print "Error: ".$e->getMessage();
  }
}

// Subir actvidades segun la nueva estructura //
public function subiractividadesAction(){
  try {
    /*$proyectoid= $this->_getParam("proyectoid");
    $codigo_prop_proy= $this->_getParam("codigo_prop_proy");*/
    $proyectoid='1508.10.01';
    $codigo_prop_proy='15.10.091-1508.10.01-D';
    $editproyect= new Admin_Model_DbTable_Proyecto();
    $where = array(
                      'codigo_prop_proy'    => $codigo_prop_proy,
                      'proyectoid'    => $proyectoid,
                      );
    $edit = $editproyect->_getOne($where);
    //print_r($edit);
    $proyectoid = $edit['proyectoid'];
    $codigo = $edit['codigo_prop_proy'];
    $propuestaid = $edit['propuestaid'];
    $revision = $edit['revision'];
    $moneda = $edit['moneda'];
    $dir = APPLICATION_LIBRARY . "/excel/excel/reader.php";
    include ($dir);
    $data = new Spreadsheet_Excel_Reader();
    $data->setOutputEncoding('CP1251');
    //$data->read('./upload/proyecto/'.$proyectoid.'-HH.xls');
    $data->read('proyecto.xls');
    $k=1;
    $columnas=$data->sheets[0]['numCols'];
    $filas=$data->sheets[0]['numRows'];
    //migrar actividades
    for ($i = 2; $i <= $data->sheets[0]['numRows']-1; $i++) {
      //$colsuma=$columnas-1;
      $actividadid=$data->sheets[0]['cells'][$i][1];
      $nombre=$data->sheets[0]['cells'][$i][2];
      //echo $actividadid; echo "<br>";
      //$suma=$data->sheets[0]['cells'][$i][$colsuma];
      $actividadint=$actividadid;
      //print_r($actividadint);
      if (ctype_digit(trim($actividadint))) {
        $k++;
        $j=0;
        $datosactividadpadre["actividadid"]=$actividadint;
        $datosactividadpadre["codigo_actividad"]=$actividadid;
        $datosactividadpadre["codigo_prop_proy"]=$codigo;
        $datosactividadpadre["revision"]=$revision;
        //$datosactividadpadre["areaid"]=$areaid;
        $datosactividadpadre["proyectoid"]=$proyectoid;
        $datosactividadpadre["propuestaid"]=$propuestaid;
        $datosactividadpadre["actividad_padre"]='0';

        $datosactividadpadre["nombre"]=utf8_encode($nombre);
        $datosactividadpadre["fecha_creacion"]=date("Y-m-d");
        $datosactividadpadre["estado"]='P';
        $datosactividadpadre["duracion_total"]='0';
        $datosactividadpadre["h_propuesta"]='';
        $datosactividadpadre["h_extra"]='0';
        $datosactividadpadre["h_planificada"]='0';
        $datosactividadpadre["orden"]=$k-1;
        $datosactividadpadre["isproyecto"]='S';
        $datosactividadpadre["moneda"]=$moneda;
        $bdactividad = new Admin_Model_DbTable_Actividad();
        //print_r($datosactividadpadre);
        if($bdactividad->_save($datosactividadpadre))
         {echo $actividadint;
          echo ": guardo bien actividad padre";  echo "<br>"; }
        
        } 
        else {
        $actividadeshijas = explode(".",$actividadint);
          if (count($actividadeshijas)=='2'){
            $j++;
            $datosactividadhija["actividadid"]=$actividadint;
            $datosactividadhija["codigo_actividad"]=$actividadid;
            $datosactividadhija["codigo_prop_proy"]=$codigo;
            $datosactividadhija["revision"]=$revision;
            //$datosactividadhija["areaid"]=$areaid;
            $datosactividadhija["proyectoid"]=$proyectoid;
            $datosactividadhija["propuestaid"]=$propuestaid;
            $datosactividadhija["actividad_padre"]=$actividadeshijas[0];
            $datosactividadhija["nombre"]=utf8_encode($nombre);
            $datosactividadhija["fecha_creacion"]=date("Y-m-d");
            $datosactividadhija["estado"]='P';
            $datosactividadhija["duracion_total"]='0';
            $datosactividadhija["h_propuesta"]='';
            $datosactividadhija["h_extra"]='0';
            $datosactividadhija["h_planificada"]='0';
            $datosactividadhija["orden"]=$k.".".$j-1;
            $datosactividadhija["isproyecto"]='S';
            $datosactividadhija["moneda"]=$moneda;
            $bdactividad = new Admin_Model_DbTable_Actividad();
           // print_r($datosactividadhija);
          if($bdactividad->_save($datosactividadhija))
            { echo $actividadint;
              echo "guardo bien actividad disciplina"; echo "<br>";
            }
            }

            if (count($actividadeshijas)=='3'){
            $datosactividadnieta["actividadid"]=$actividadint;
            $datosactividadnieta["codigo_actividad"]=$areaid."-".$actividadid;
            $datosactividadnieta["codigo_prop_proy"]=$codigo;
            $datosactividadnieta["revision"]=$revision;
            $datosactividadnieta["areaid"]=$areaid;
            $datosactividadnieta["proyectoid"]=$proyectoid;
            $datosactividadnieta["propuestaid"]=$propuestaid;
            $datosactividadnieta["actividad_padre"]=$actividadeshijas[0].".".$actividadeshijas[1];

            $datosactividadnieta["nombre"]=utf8_encode($nombre);
            $datosactividadnieta["fecha_creacion"]=date("Y-m-d");
            $datosactividadnieta["estado"]='P';
            $datosactividadnieta["duracion_total"]='0';
            $datosactividadnieta["h_propuesta"]=$suma;
            $datosactividadnieta["h_extra"]='0';
            $datosactividadnieta["h_planificada"]='0';
            $datosactividadnieta["orden"]=$i-1;
            $datosactividadnieta["isproyecto"]='S';
            $datosactividadnieta["moneda"]=$moneda;
            $bdactividad = new Admin_Model_DbTable_Actividad();
           
            if($bdactividad->_save($datosactividadnieta))
            {echo $actividadint;
              echo "guardo bien tarea";echo "<br>";
            }
           

            }
      }
    }


    }
  catch (Exception $e) {
    print "Error: ".$e->getMessage();
  }
}

public function verAction() {
    $proyectoid= $this->_getParam("proyectoid");
    $codigo_prop_proy= $this->_getParam("codigo_prop_proy");
    $this->view->codigoproyecto=$codigo_prop_proy;
    $this->view->proyectoid=$proyectoid;

    $bandera= $this->_getParam("bandera");
    if ($bandera=='S')
    {
    $this->view->bandera='S';}
    $editproyect= new Admin_Model_DbTable_Proyecto();
    $where = array(
      'codigo_prop_proy'    => $codigo_prop_proy,
      'proyectoid'    => $proyectoid,
    );
    $edit = $editproyect->_getOne($where);
    $this->view->proyecto = $edit;
   /// print_r($edit);

    $codigo=$this->_getParam('codigo_prop_proy');
    $propuestaid=$edit['propuestaid'];
    $revision=$edit['revision'];
    $buscapropuesta = new Admin_Model_DbTable_Propuesta();
    $busca=$buscapropuesta->_getPropuestaxIndices($codigo,$propuestaid,$revision);
    $this->view->buscapropuesta = $busca; 


    $areacat=new Admin_Model_DbTable_Area();
    $arcat=$areacat->_getAreaAll();
    $this->view->area = $arcat; 
    


  
}   

  public function subirareacategoriaAction() {
    /*$proyectoid= $this->_getParam("proyectoid");
    $codigo_prop_proy= $this->_getParam("codigo_prop_proy");*/
    $proyectoid='1508.10.01';
    $codigo_prop_proy='15.10.091-1508.10.01-D';
    
    $editproyect= new Admin_Model_DbTable_Proyecto();
    $where = array(
                      'codigo_prop_proy'    => $codigo_prop_proy,
                      'proyectoid'    => $proyectoid,
                      );
    $edit = $editproyect->_getOne($where);
    //print_r($edit);
    $proyectoid = $edit['proyectoid'];
    $codigo = $edit['codigo_prop_proy'];
    $propuestaid = $edit['propuestaid'];
    $revision = $edit['revision'];
    $moneda = $edit['moneda'];


    $dir = APPLICATION_LIBRARY . "/excel/excel/reader.php";
    include ($dir);
    $data = new Spreadsheet_Excel_Reader();
    $data->setOutputEncoding('CP1251');
    //$data->read('./upload/proyecto/'.$proyectoid.'-HH.xls');
    $data->read('proyectosss.xls');
    $columnas=$data->sheets[0]['numCols'];
    $filas=$data->sheets[0]['numRows'];
    


   // for ($i = 2; $i <= $filas; $i++) {
      for ($j =5; $j <= $columnas ; $j++) {
        //$areaid=$data->sheets[0]['cells'][$i][1];
        //$actividadid=$data->sheets[0]['cells'][$i][2];
        $categoria=$data->sheets[0]['cells'][1][$j];
        //echo $categoria; echo "<br>";
       
        $categoria_hija = explode("_",$categoria);

        if (count($categoria_hija)==2)
        {
          //print_r(strtolower($categoria_hija[0])); echo "<br>";
         // print_r(strtolower($categoria_hija[1])); echo "<br>";

          $bdcategoria = new Admin_Model_DbTable_Categoria();
          $datoscat=$bdcategoria-> _buscarCategoriaxTag(strtolower($categoria_hija[0]));
          $idcategoria=$datoscat[0]['categoriaid'];
         // print_r($idcategoria); echo "<br>";


        }

          if (count($categoria_hija)==3)
        {
       // print_r(strtolower($categoria_hija[0])); echo "<br>";
        // print_r(strtolower($categoria_hija[1]));
         // print_r(strtolower($categoria_hija[2]));

          //$bdarea = new Admin_Model_DbTable_Area();
          //$datosarea=$bdarea-> _buscarCategoriaxTag(strtolower($categoria_hija[0]));
          //$areaid=$datosarea[0]['areaid'];
          //print_r($areaid); echo "<br>";
          $areaid=$categoria_hija[0];
         

          $bdcategoria = new Admin_Model_DbTable_Categoria();
          $datoscat=$bdcategoria-> _buscarCategoriaxTag(strtolower($categoria_hija[1]));
          $idcategoria=$datoscat[0]['categoriaid'];
          //print_r($idcategoria); echo "<br>";
          $rate=$categoria_hija[2];
          //echo $rate;
          $dataequipoarea['areaid']=$areaid;
                $dataequipoarea['codigo_prop_proy']=$codigo;
                $dataequipoarea['proyectoid']=$proyectoid;
                $dataequipoarea['categoriaid']=$idcategoria;
                $dataequipoarea['fecha_creacion']=date("Y-m-d");
                $dataequipoarea['estado']='A';
                $dataequipoarea['funcion']='PROYECTO';
                //$bdarea_categoria = new Admin_Model_DbTable_Areacategoria();
                //$existearea_categoria=$bdarea_categoria->_getAreacategoriaxIndice($idcategoria,$areaid);
                //print_r($existearea_categoria);

                $bdequipo_area = new Admin_Model_DbTable_Equipoarea();
        //$datosequipoarea=$bdequipo_area->_buscarEquipoxProyecto($codigo,$proyectoid,$areaid,$idcategoria);
        //if(isset($datosequipoarea)) 

               $bdequipo_area->_save($dataequipoarea);

        }

        /*if (count($categoria_hija)==2)
        {
          print_r($categoria_hija[2]);
        }*/
        
        //echo $categoria;
        /*
        $bdcategoria = new Admin_Model_DbTable_Categoria();
        $datoscat=$bdcategoria-> _buscarCategoriaxTag($categoria);
        $idcategoria=$datoscat[0]['categoriaid'];
        $bdequipo_area = new Admin_Model_DbTable_Equipoarea();
        $datosequipoarea=$bdequipo_area->_buscarEquipoxProyecto($codigo,$proyectoid,$areaid,$idcategoria);
        if(isset($datosequipoarea)) 
            { 
                $dataequipoarea['areaid']=$areaid;
                $dataequipoarea['codigo_prop_proy']=$codigo;
                $dataequipoarea['proyectoid']=$proyectoid;
                $dataequipoarea['categoriaid']=$idcategoria;
                $dataequipoarea['fecha_creacion']=date("Y-m-d");
                $dataequipoarea['estado']='A';
                $dataequipoarea['funcion']='PROPUESTA';
                $bdarea_categoria = new Admin_Model_DbTable_Areacategoria();
                $existearea_categoria=$bdarea_categoria->_getAreacategoriaxIndice($idcategoria,$areaid);
               // print_r($existearea_categoria);
               // echo $idcategoria;
               // echo $areaid;
               // echo "<br>";
              //  print_r($dataequipoarea);
                if($existearea_categoria) {
                    
                   
                 
                    /*if ($bdequipo_area->_save($dataequipoarea))
                            {
                                echo "mmmmmmmmm";
                            }

                    */
              /*  }
                else
                {
                    $bdarea = new Admin_Model_DbTable_Area();
                    $datosarea=$bdarea->_getAreaxIndice($areaid);

                    $dataarea_cat['areaid']=$areaid;
                    $dataarea_cat['categoriaid']=$idcategoria;
                    $dataarea_cat['nombre']=$datosarea[0]['nombre']."-".$datoscat[0]['nombre_categoria'];
                    $dataarea_cat['estado']='A';
                    //print_r($dataarea_cat);
                    /*if ($bdarea_categoria->_save($dataarea_cat))
                    {
                        echo "se creo area_categoria";
                       if ($bdequipo_area->_save($dataequipoarea))
                            {
                                echo "lllllllllll";
                            }

                    } 
                }
        }*/

      }
   // }
}   

public function subirtareoAction() {
    /*$proyectoid= $this->_getParam("proyectoid");
    $codigo_prop_proy= $this->_getParam("codigo_prop_proy");*/
    $proyectoid='1508.10.01';
    $codigo_prop_proy='15.10.091-1508.10.01-D';


    $editproyect= new Admin_Model_DbTable_Proyecto();
    $where = array(
                      'codigo_prop_proy'    => $codigo_prop_proy,
                      'proyectoid'    => $proyectoid,
                      );
    $edit = $editproyect->_getOne($where);
    //print_r($edit);
    $proyectoid = $edit['proyectoid'];
    $codigo = $edit['codigo_prop_proy'];
    $propuestaid = $edit['propuestaid'];
    $revision = $edit['revision'];
    $moneda = $edit['moneda'];


    $dir = APPLICATION_LIBRARY . "/excel/excel/reader.php";
    include ($dir);
    $data = new Spreadsheet_Excel_Reader();
    $data->setOutputEncoding('CP1251');
    //$data->read('./upload/proyecto/'.$proyectoid.'-HH.xls');
    $data->read('proyectosss.xls');
    $columnas=$data->sheets[0]['numCols'];
    $filas=$data->sheets[0]['numRows'];

    for ($i = 2; $i <= $filas; $i++) {
        for ($j = 5; $j <= $columnas-2 ; $j++) {

        //$areaid=$data->sheets[0]['cells'][$i][1];
        $actividadid=$data->sheets[0]['cells'][$i][1];
        
        $categoria=$data->sheets[0]['cells'][1][$j];
        //echo $categoria; echo "<br>";
           $categoria_hija = explode("_",$categoria);

        $nombre_actividad=$data->sheets[0]['cells'][$i][2];
              $areaid=$categoria_hija[0];


        if (isset( $data->sheets[0]['cells'][$i][$j] ))
        {
        $suma=$data->sheets[0]['cells'][$i][$j];
        $horas_propuesta=utf8_encode($data->sheets[0]['cells'][$i][$j]);

        $bdcategoria = new Admin_Model_DbTable_Categoria();
       $datoscat=$bdcategoria-> _buscarCategoriaxTag(strtolower($categoria_hija[1]));
          $idcategoria=$datoscat[0]['categoriaid'];
        $actividadint=$actividadid;
        $actividadeshijas = explode(".",$actividadint);
        if (count($actividadeshijas)=='2'){
            $codigo_actividad=$actividadid;
            $bdtareo = new Admin_Model_DbTable_Tareo();
            $existe_tareo=$bdtareo->_getTareoxProyectoxActividadHijaxAreaxCategoria($proyectoid,$codigo,$revision,$actividadeshijas[0],$actividadid,$codigo_actividad,$areaid,$idcategoria);
            if(isset($existe_tareo)) { 
                $datostareo["actividadid"]=$actividadid;
                $datostareo["codigo_actividad"]=$actividadid;
                $datostareo["codigo_prop_proy"]=$codigo;
                $datostareo["revision"]=$revision;
                $datostareo["areaid"]=$areaid;
                $datostareo["proyectoid"]=$proyectoid;
                $datostareo["actividad_padre"]=$actividadeshijas[0];
                $datostareo["fecha_creacion"]=date("Y-m-d");
                $datostareo["estado"]='P';
                $datostareo["h_propuesta"]=$suma;
                $datostareo["duracion"]='0';
                $datostareo["h_extra"]='0';
                $datostareo["h_planificada"]='0';
                $datostareo["h_real"]='0';
                $datostareo["isproyecto"]='S';
                $datostareo["categoriaid"]=$idcategoria;
                $datostareo["nombre"]=utf8_encode($nombre_actividad);
                //print_r($datostareo);
                if($bdtareo->_save($datostareo)){
                    echo "guardado ";
                }
            }
        }

        if (count($actividadeshijas)=='3'){
            $codigo_actividad=$areaid."-".$actividadid;
            $bdtareo = new Admin_Model_DbTable_Tareo();
            $existe_tareo=$bdtareo->_getTareoxProyectoxActividadHijaxAreaxCategoria($proyectoid,$codigo,$revision,$actividadeshijas[0],$actividadid,$codigo_actividad,$areaid,$idcategoria);
            if(isset($existe_tareo)) { 
                $datostareo2["actividadid"]=$actividadid;
                $datostareo2["codigo_actividad"]=$areaid."-".$actividadid;
                $datostareo2["codigo_prop_proy"]=$codigo;
                $datostareo2["revision"]=$revision;
                $datostareo2["areaid"]=$areaid;
                $datostareo2["proyectoid"]=$proyectoid;
                $datostareo2["actividad_padre"]=$actividadeshijas[0].".".$actividadeshijas[1];
                $datostareo2["fecha_creacion"]=date("Y-m-d");
                $datostareo2["estado"]='P';
                $datostareo2["h_propuesta"]=$suma;
                $datostareo2["duracion"]='0';
                $datostareo2["h_extra"]='0';
                $datostareo2["h_planificada"]='0';
                $datostareo2["h_real"]='0';
                $datostareo2["isproyecto"]='S';
                $datostareo2["categoriaid"]=$idcategoria;
                $datostareo2["nombre"]=utf8_encode($nombre_actividad);
                //if($bdtareo->_save($datostareo2)){
                //    
                //}
            }
        }

      }
    } }
}   
  public function cargarhorasAction() {

  $ano=date("Y");
  $semana=date("W");
  /*echo "semana nro: ".(date("W"));
  echo "dia del mes nro: ".(date("j"));
  echo "# dias de la semana".(date("N"));*/
  $dias = array('lunes', 'martes', 'miercoles', 
    'jueves', 'viernes', 'sabado','domingo');
  $enero = mktime(1,1,1,1,1,$ano); 
  //$mos = (11-date('w',1))%7-3; 
  $mos = (11-date('w',$enero))%7-3;
  $inicios = strtotime(($semana-1) . ' weeks '.$mos.' days', $enero); 
  for ($x=0; $x<=6; $x++) {
    $dias[] = date('d/m/Y', strtotime("+ $x day", $inicios));
    $dia[] = date('w', strtotime("+ $x day", $inicios));
  }
  $this->view->diassemana=$dias;
  $this->view->semanalabor=$semana;

   
  }

public function cargartareaAction() {
        $codigo='PROP-2015-20209133394-1407-15.10.053-B';
        $proyectoid='1215.10.25';
        $propuestaid='15.10.053';
        $revision='B';

        $editproyect= new Admin_Model_DbTable_Proyecto();
        $where = array(
                      'codigo_prop_proy'    => $codigo,
                      'proyectoid'    => $proyectoid,
                      );
        $edit = $editproyect->_getOne($where);
        $this->view->proyecto = $edit;
         
    }

public function cargartarea2Action() {
   $this->_helper->layout()->disablelayout();
        $codigo='PROP-2015-20209133394-1407-15.10.053-B';
        $proyectoid='1215.10.25';
        $propuestaid='15.10.053';
        $revision='B';

        $editproyect= new Admin_Model_DbTable_Proyecto();
        $where = array(
                      'codigo_prop_proy'    => $codigo,
                      'proyectoid'    => $proyectoid,
                      );
        $edit = $editproyect->_getOne($where);
        $this->view->proyecto = $edit;


        $actividadpadre= new Admin_Model_DbTable_Actividad();
        $list=$actividadpadre->_getActividadesPadres($proyectoid,$codigo,$propuestaid,$revision);
          $this->view->list = $list;
    }

    public function cargartarea3Action() {
   $this->_helper->layout()->disablelayout();
        $codigo='PROP-2015-20209133394-1407-15.10.053-B';
        $proyectoid='1215.10.25';
        $propuestaid='15.10.053';
        $revision='B';
        $actividadid= $this->_getParam("actividadid");

        $editproyect= new Admin_Model_DbTable_Proyecto();
        $where = array(
                      'codigo_prop_proy'    => $codigo,
                      'proyectoid'    => $proyectoid,
                      );
        $edit = $editproyect->_getOne($where);
        $this->view->proyecto = $edit;


        $actividadpadre= new Admin_Model_DbTable_Actividad();
        $list=$actividadpadre->_getActividadesHijas($proyectoid,$codigo,$propuestaid,$revision,$actividadid);
          $this->view->list = $list;
    }



    public function cargartarea4Action() {
   $this->_helper->layout()->disablelayout();
        $codigo='PROP-2015-20209133394-1407-15.10.053-B';
        $proyectoid='1215.10.25';
        $propuestaid='15.10.053';
        $revision='B';
        $actividadid= $this->_getParam("disciplinaid");

        $editproyect= new Admin_Model_DbTable_Proyecto();
        $where = array(
                      'codigo_prop_proy'    => $codigo,
                      'proyectoid'    => $proyectoid,
                      );
        $edit = $editproyect->_getOne($where);
        $this->view->proyecto = $edit;


        $actividadpadre= new Admin_Model_DbTable_Actividad();
        $list=$actividadpadre->_getActividadesHijas($proyectoid,$codigo,$propuestaid,$revision,$actividadid);
          $this->view->list = $list;
    }

  public function buscarcategoriaAction() {
    $this->_helper->layout()->disablelayout();
    $areaid= $this->_getParam("areaid");
    $bdarea_cat = new Admin_Model_DbTable_Areacategoria();
    $listcat=$bdarea_cat->_buscarCategoriaxAreaxProyecto($areaid);
    $this->view->categoria = $listcat;
  }
  
  public function buscarpersonasxcategoriaAction() {
    $this->_helper->layout()->disablelayout();
    $areaid= $this->_getParam("areaid");
    $categoriaid= $this->_getParam("categoria");
    $proyectoid= $this->_getParam("proyectoid");
    $codigo= $this->_getParam("codigo");
    $this->view->areaid = $areaid;
    $this->view->categoriaid = $categoriaid;
    $this->view->proyectoid = $proyectoid;
    $this->view->codigo = $codigo;

    $bdarea_cat = new Admin_Model_DbTable_Usuariocategoria();
    $listusuarios=$bdarea_cat->_buscarUsuarioxAreaxCategoria($areaid,$categoriaid);
    $this->view->listusuarios = $listusuarios;
  }

  

  public function guardarpersonaequipoAction() {
    $this->_helper->layout()->disablelayout();
    $dataequipo['areaid']=$areaid= $this->_getParam("areaid");
    $dataequipo['categoriaid']=$categoriaid= $this->_getParam("categoriaid");
    $dataequipo['proyectoid']=$proyectoid= $this->_getParam("proyectoid");
    $dataequipo['codigo_prop_proy']=$codigo= $this->_getParam("codigo");
    $dataequipo['dni']=$dni= $this->_getParam("dni");
    $dataequipo['uid']=$uid= $this->_getParam("uid");
    $dataequipo['cargo']=$cargo= $this->_getParam("cargo");
    $dataequipo['estado']=$estado= $this->_getParam("estado");
    $dataequipo['fecha_ingreso']=date("Y-m-d");
    $wheres=array('codigo_prop_proy'=>$codigo,'proyectoid'=>$proyectoid,'uid'=>$uid,'dni'=>$dni,'areaid'=>$areaid);
    $equipo= new Admin_Model_DbTable_Equipo();
    $activar= $equipo->_getOne($wheres);
    if($activar)
      {
        echo "existe";
        print_r($wheres);
        $datact['fecha_ingreso']=date("Y-m-d");
        $datact['estado']=$estado;
        $upactiv= $equipo->_update($datact,$wheres);
      }
    else
      {      
        echo "no existe";
        print_r($dataequipo);
        $gactiv= $equipo->_save($dataequipo);
      }
  }

  public function asignaractividadAction(){
    try
    {
      $this->_helper->layout()->disablelayout();
      $proyectoid= $this->_getParam("proyectoid");
      $codigo_prop_proy= $this->_getParam("codigo_prop_proy");
      $areaid= $this->_getParam("areaid");
      $this->view->proyectoid = $proyectoid;
      $this->view->codigo_prop_proy = $codigo_prop_proy;
      $this->view->areaid = $areaid;
      $where = array( 'proyectoid' => $proyectoid,'codigo_prop_proy'=>$codigo_prop_proy,'estado' =>'P','isproyecto'=>'S');
      $veract = new Admin_Model_DbTable_Actividad();
      $viewactivity=$veract->_getFilter($where);
      $this->view->actividades = $viewactivity;
      $wherekip = array( 'proyectoid' => $proyectoid,'codigo_prop_proy'=>$codigo_prop_proy,'estado' =>'A','areaid'=>$areaid);    
      $verequipo= new Admin_Model_DbTable_Equipo();
      $viewequipo=$verequipo->_getFilter($wherekip);
      $this->view->equipo = $viewequipo;
      $area = new Admin_Model_DbTable_Area();
      $area_view = $area->_getAreaAll();
      $this->view->area = $area_view;
    }
    catch (Exception $e) {
      print "Error: ".$e->getMessage();
    }
  }



  public function agregaactividadAction(){
    try
    {  
      $cargo= $this->_getParam("cargo");
      $areaid= $this->_getParam("areaid");
      $uid= $this->_getParam("uid");
      $dni= $this->_getParam("dni");
      $proyectoid= $this->_getParam("proyectoid");
      $categoriaid= $this->_getParam("categoriaid");  
      $actividadid= $this->_getParam("actividadid");
      $revision= $this->_getParam("revision");
      $codigo_actividad= $this->_getParam("codigo_actividad");
      $codigo_prop_proy= $this->_getParam("codigo_prop_proy");
      $estado= $this->_getParam("estado");
      $actividad_padre= $this->_getParam("actividad_padre");
      //"codigo_prop_proy","codigo_actividad", "proyectoid", "actividadid", "uid", "dni","cargo", "areaid", "categoriaid");
      $wheres=array('codigo_prop_proy'=>$codigo_prop_proy,'codigo_actividad'=>$codigo_actividad,'proyectoid'=>$proyectoid,'actividadid'=>$actividadid
              ,'uid'=>$uid,'dni'=>$dni,'cargo'=>$cargo,'areaid'=>$areaid,'categoriaid'=>$categoriaid);
      $act= new Admin_Model_DbTable_Activaractividad();
      $activar= $act->_getOne($wheres);
      //print_r($wheres);
        if($activar)
        {

          $datact['fecha']=date("Y-m-d");
          $datact['estado']=$estado;
          $upactiv= $act->_updateX($datact,$wheres);
        }
        else
        {      
          $data['codigo_prop_proy']=$codigo_prop_proy;
          $data['proyectoid']=$proyectoid;
          $data['codigo_actividad']=$codigo_actividad;
          $data['actividadid']=$actividadid; 
          $data['revision']=$revision;
          $data['cargo']=$cargo;
          $data['categoriaid']=$categoriaid;
          $data['areaid']=$areaid;
          $data['uid']=$uid;
          $data['dni']=$dni;
          $data['fecha']=date("Y-m-d");
          $data['estado']=$estado;
          $data['actividad_padre']=$actividad_padre;    
          $gactiv= $act->_save($data);
        }
     } 
      catch (Exception $e) {
      print "Error: ".$e->getMessage();
    }

  }


  public function agregarpersonaequipoAction(){
    try
    {  
      $cargo= $this->_getParam("cargo");
      $areaid= $this->_getParam("areaid");
      $uid= $this->_getParam("uid");
      $dni= $this->_getParam("dni");
      $proyectoid= $this->_getParam("proyectoid");
      $categoriaid= $this->_getParam("categoriaid");  
      $actividadid= $this->_getParam("actividadid");
      $revision= $this->_getParam("revision");
      $codigo_actividad= $this->_getParam("codigo_actividad");
      $codigo_prop_proy= $this->_getParam("codigo_prop_proy");
      $estado= $this->_getParam("estado");
      $actividad_padre= $this->_getParam("actividad_padre");
      //"codigo_prop_proy","codigo_actividad", "proyectoid", "actividadid", "uid", "dni","cargo", "areaid", "categoriaid");
      $wheres=array('codigo_prop_proy'=>$codigo_prop_proy,'codigo_actividad'=>$codigo_actividad,'proyectoid'=>$proyectoid,'actividadid'=>$actividadid
              ,'uid'=>$uid,'dni'=>$dni,'cargo'=>$cargo,'areaid'=>$areaid,'categoriaid'=>$categoriaid);
      $act= new Admin_Model_DbTable_Activaractividad();
      $activar= $act->_getOne($wheres);
      //print_r($wheres);
        if($activar)
        {
          $datact['fecha']=date("Y-m-d");
          $datact['estado']=$estado;
          $upactiv= $act->_updateX($datact,$wheres);
        }
        else
        {      
          $data['codigo_prop_proy']=$codigo_prop_proy;
          $data['proyectoid']=$proyectoid;
          $data['codigo_actividad']=$codigo_actividad;
          $data['actividadid']=$actividadid; 
          $data['revision']=$revision;
          $data['cargo']=$cargo;
          $data['categoriaid']=$categoriaid;
          $data['areaid']=$areaid;
          $data['uid']=$uid;
          $data['dni']=$dni;
          $data['fecha']=date("Y-m-d");
          $data['estado']=$estado;
          $data['actividad_padre']=$actividad_padre;    
          $gactiv= $act->_save($data);
        }
     } 
      catch (Exception $e) {
      print "Error: ".$e->getMessage();
    }

  }

  public function agregartodoactividadAction(){
    try
    {
      $cargo= $this->_getParam("cargo");
      $areaid= $this->_getParam("areaid");
      $uid= $this->_getParam("uid");
      $dni= $this->_getParam("dni");
      $proyectoid= $this->_getParam("proyectoid");
      $categoriaid= $this->_getParam("categoriaid");
      $codigo_prop_proy= $this->_getParam("codigo_prop_proy");   
      $estado= $this->_getParam("estado");   
      $act= new Admin_Model_DbTable_Actividad();
      $activar= $act->_getRepliconActividades($proyectoid,$codigo_prop_proy);
      for($i=0;$i<count($activar);$i++)
      {
        $codigo_actividad = $activar[$i]['codigo_actividad'];
        $actividadid = $activar[$i]['actividadid'];
        $revision = $activar[$i]['revision'];
        $actividad_padre = $activar[$i]['actividad_padre'];
        $wheres=array('codigo_prop_proy'=>$codigo_prop_proy,'codigo_actividad'=>$activar[$i]['codigo_actividad'],
               'proyectoid'=>$proyectoid,'actividadid'=>$activar[$i]['actividadid'],'uid'=>$uid,'dni'=>$dni,'cargo'=>$cargo,
               'areaid'=>$areaid,'categoriaid'=>$categoriaid);
        $acti= new Admin_Model_DbTable_Activaractividad();
        $veract= $acti->_getOne($wheres);
        if($veract)
        {
          $datact['fecha']=date("Y-m-d");
          $datact['estado']=$estado;
          $upactiv= $acti->_updateX($datact,$wheres);
        }
        else
        {
          $data['codigo_prop_proy']=$codigo_prop_proy;
          $data['proyectoid']=$proyectoid;
          $data['codigo_actividad']=$activar[$i]['codigo_actividad'];
          $data['actividadid']=$actividadid;
          $data['revision']=$revision;
          $data['cargo']=$cargo;
          $data['categoriaid']=$categoriaid;
          $data['areaid']=$areaid;
          $data['uid']=$uid;
          $data['dni']=$dni;
          $data['fecha']=date("Y-m-d");
          $data['estado']=$estado;
          $data['actividad_padre']=$actividad_padre;
          $gactiv= $acti->_save($data);
        }
      }
    }
      catch (Exception $e) {
      print "Error: ".$e->getMessage();
    }
  }



  public function guardarareaequipoAction(){
    try
    {
      $this->_helper->layout()->disablelayout();      
      $proyectoid= $this->_getParam("proyectoid");
      $codigo_prop_proy= $this->_getParam("codigo_prop_proy");
      $categoriaid= $this->_getParam("cat");
      $areaid= $this->_getParam("area");
      $funcion= $this->_getParam("funcion");


      $data['codigo_prop_proy']=$codigo_prop_proy;
      $data['proyectoid']=$proyectoid;
      
      $data['categoriaid']=$categoriaid;
      $data['areaid']=$areaid;
   
      $data['fecha_creacion']=date("Y-m-d");
      $data['estado']='A';
   
      print_r($data);
      
      $equiparea= new Admin_Model_DbTable_Equipoarea();
      $gequiparea= $equiparea->_save($data);

    }
    catch (Exception $e) {
      print "Error: ".$e->getMessage();
    }
  }

}
