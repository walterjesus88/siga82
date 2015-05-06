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



  public function leerexcelAction(){
    try {
      $fecha = $this->_getParam("fecha");
      $ano=date("Y");
      $semana=date('W', strtotime($fecha));  
      $dias = array('lunes', 'martes', 'miercoles', 
      'jueves', 'viernes', 'sabado','domingo');
      $enero = mktime(1,1,1,1,1,$ano); 
      $mos = (11-date('w',$enero))%7-3;
      $inicios = strtotime(($semana-1) . ' weeks '.$mos.' days', $enero); 
      for ($x=0; $x<=6; $x++) {
        $dias[] = date('d-m-Y', strtotime("+ $x day", $inicios));
        $dia[] = date('w', strtotime("+ $x day", $inicios));
      }
      $this->view->semana=$semana;
      $proyectoid = $this->_getParam("proyectoid");
      $responsable = $this->_getParam("responsable");
      $dir = APPLICATION_LIBRARY . "/excel/excel/reader.php";
      include ($dir);
      $data = new Spreadsheet_Excel_Reader();
      $data->setOutputEncoding('CP1251');
      $data->read($proyectoid.'.xls');
      //echo ($data->sheets[0]['numRows']);
      /*  */
      for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
        if (isset( $data->sheets[0]['cells'][1][$j] ))
          { 
            //revisor, revisor principal, gerente
            $categoria=$data->sheets[0]['cells'][1][$j];
            //echo $categoria; echo "<tr>";
          }
      }
      for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
        if (isset( $data->sheets[0]['cells'][$i][1] ))
          {
            $actividadid=$data->sheets[0]['cells'][$i][1];
            $nombre=$data->sheets[0]['cells'][$i][2];
            $actividadint=$actividadid;
            if (ctype_digit(trim($actividadint))) {
              //echo "GUARDAR ACTIVIDADES PADRE";
              $datosactividadpadre["actividadid"]=$actividadid;
              $datosactividadpadre["proyectoid"]=$proyectoid;
              $datosactividadpadre["nombre"]=utf8_encode($nombre);
              $datosactividadpadre["fecha_creacion"]=$fecha;
              $datosactividadpadre["estado"]='P';
              $datosactividadpadre["duracion"]='0';
              $datosactividadpadre["efectivas"]='0';
              $datosactividadpadre["planificadas"]='0';
              $datosactividadpadre["actividad_padre"]='';
              $datosactividadpadre["orden"]=$i-1;
              $bdactividad = new Proyectos_Model_DbTable_Actividad();
              //$datosactividad = $bdactividad->_guardar($datosactividadpadre);
              //print_r($datosactividadpadre);
            }
            else {
              $actividadeshijas = explode(".",$actividadint);
              if (count($actividadeshijas)=='2')
              {
                $datosactividadespecialidad["actividadid"]=$actividadid;
                $datosactividadespecialidad["proyectoid"]=$proyectoid;
                $datosactividadespecialidad["nombre"]=utf8_encode($nombre);
                $datosactividadespecialidad["fecha_creacion"]=$fecha;
                $datosactividadespecialidad["estado"]='P';
                $datosactividadespecialidad["duracion"]='0';
                $datosactividadespecialidad["efectivas"]='0';
                $datosactividadespecialidad["planificadas"]='0';
                $datosactividadespecialidad["actividad_padre"]=intval($actividadid);
                $datosactividadespecialidad["orden"]=$i-1;
                $bdactividad = new Proyectos_Model_DbTable_Actividad();
                $nombrecompleto=utf8_encode($nombre);
                echo utf8_encode($nombre);
                echo "<br>";

                
                //print_r($datosarea);
                //guardar actividades hijas
                //$datosactividad = $bdactividad->_guardar($datosactividadespecialidad);
                //echo "actividad especialidad";
                //echo "<br>";
                //print_r($datosactividadespecialidad);
                $nombreespecialidades = explode(":",$nombrecompleto);
                if (count($nombreespecialidades)=='2')
                {
                  $comparar=strtolower($nombreespecialidades[1]);
                  $bdarea = new Proyectos_Model_DbTable_ProyectoArea();
                  $datosarea = $bdarea->_getxNombreArea($comparar);
                  

                  
                  if($datosarea) {
                    echo "-----";
                    print_r($datosarea);
                  }
                  else
                  {
                    $no_permitidas= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹");
                    $permitidas= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
                    $texto = str_replace($no_permitidas, $permitidas ,$nombreespecialidades[1]);
                    echo $texto;
                    $datos = $bdarea->_getxNombreArea($comparar);
                    if($datos)
                    {
                      echo "++++++";
                      print_r($datos);
                    }
                    //print_r($datosarea);
                  }

                }
              }
              else
              {
                $actividadeshijas = explode(".",$actividadint);
                if (count($actividadeshijas)=='3')
                {
                  $datostareaespecialidad["actividadid"]=$actividadid;
                  $datostareaespecialidad["proyectoid"]=$proyectoid;
                  $datostareaespecialidad["nombre"]=utf8_encode($nombre);
                  $datostareaespecialidad["fecha_creacion"]=$fecha;
                  $datostareaespecialidad["estado"]='P';
                  $datostareaespecialidad["duracion"]='0';
                  $datostareaespecialidad["efectivas"]='0';
                  $datostareaespecialidad["planificadas"]='0';
                  $datostareaespecialidad["actividad_padre"]=$actividadeshijas[0].".".$actividadeshijas[1];
                  $datostareaespecialidad["orden"]=$i-1;
                  $bdactividad = new Proyectos_Model_DbTable_Actividad();
                  //guardar tareas
                  //$datosactividad = $bdactividad->_guardar($datostareaespecialidad);
                  //echo "tareas";
                  //echo "<br>";
                  //print_r($datostareaespecialidad); 
                } 

              }
            }
            echo "<br>";
          }
        else
          {
            echo("<td>"."</td>");
          }
      }
      
      $fila=$data->sheets[0]['numRows'];
        for ($i = 2; $i <= $fila; $i++) {
          $columnas=$data->sheets[0]['numCols'];
          for ($j = 3; $j <= $columnas-1 ; $j++) {
            $actividad=$data->sheets[0]['cells'][$i][1];
            $categoria=$data->sheets[0]['cells'][1][$j];
            if (isset( $data->sheets[0]['cells'][$i][$j] ))
            {

         //     echo("<td>".utf8_encode($data->sheets[0]['cells'][$i][$j]) ."</td>");
              $duracion=utf8_encode($data->sheets[0]['cells'][$i][$j]);
            //  echo $actividad;
            //  echo $categoria;
            //  echo $duracion;

              
              $datostarea["asignado"]=$responsable;
              $datostarea["semana"]=$semana;
              $datostarea["actividadid"]=$actividad;
              $datostarea["proyectoid"]=$proyectoid;
              $datostarea["fecha_tarea"]=$fecha;
              $datostarea["estado"]='I';
              $datostarea["etapa"]='INICIO';
              $datostarea["horas_propuesta"]=$duracion;
              $datostarea["horas_efectivas"]='0';
              $datostarea["horas_planeadas"]='0';
              $datostarea["categoriaid"]=$categoria;
          //    print_r($datostarea);
              //guardar datos de las horas
              $bdtarea = new Proyectos_Model_DbTable_Proyecto();
              //$datostarea = $bdtarea->_guardar($datostarea);
            }
            else
            {
              //echo("<td>"."</td>");
            }
          }
          
        }

      echo("<table>");
      $fila=$data->sheets[0]['numRows'];
   //   echo $fila;
        for ($i = 1; $i <= $fila; $i++) {
          echo("<tr>");
          $columnas=$data->sheets[0]['numCols'];
     //     echo $columnas;
          for ($j = 1; $j <= $columnas ; $j++) {
            //$actividad=$data->sheets[0]['cells'][$i+1][1];
            //$categoria=$data->sheets[0]['cells'][1][$j+1];
            /*if isset($data->sheets[0]['cells'][$i][$j]) {
            $valuesSQL .= "\"" . $data->sheets[0]['cells'][$i][$j] . "\""; 
            } else {
            $valuesSQL .= 'NULL'; }*/
            if (isset( $data->sheets[0]['cells'][$i][$j] ))
            {
              echo("<td>".utf8_encode($data->sheets[0]['cells'][$i][$j]) ."</td>");
              $duracion=utf8_encode($data->sheets[0]['cells'][$i][$j]);
              /*
              $datostarea["asignado"]=$responsable;
              $datostarea["semana"]=$semana;
              $datostarea["actividadid"]=$actividad;
              $datostarea["proyectoid"]=$proyectoid;
              $datostarea["fecha_tarea"]=$fecha;
              $datostarea["estado"]='I';
              $datostarea["etapa"]='INICIO';
              $datostarea["horas_propuesta"]=$duracion;
              $datostarea["horas_efectivas"]='0';
              $datostarea["horas_planeadas "]='0';
              $datostarea["categoriaid"]=$categoria;
              print_r($datostarea);
              */
              //$bdactividad = new Proyectos_Model_DbTable_Actividad();
              //$datosactividad = $bdactividad->_guardar($datosactividad);
            }
            else
            {
              echo("<td>"."</td>");
            }
          }
          echo("</tr>");
        }
      echo("</table>");
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
    print_r($edit);
    $this->view->proyectoid = $edit['proyectoid'];
    $this->view->codigo = $edit['codigo_prop_proy'];




    }
  catch (Exception $e) {
    print "Error: ".$e->getMessage();
  }
}

    
}
