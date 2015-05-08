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

public function subiractividadesAction(){
  try {

    $proyectoid= $this->_getParam("proyectoid");
    $codigo_prop_proy= $this->_getParam("codigo_prop_proy");
    

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
    $data->read('./upload/proyecto/'.$proyectoid.'-HH.xls');
    $columnas=$data->sheets[0]['numCols'];
    $filas=$data->sheets[0]['numRows'];


    //migrar actividades
    for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
      $colsuma=$columnas-1;
      $areaid=$data->sheets[0]['cells'][$i][1];
      $actividadid=$data->sheets[0]['cells'][$i][2];
      $nombre=$data->sheets[0]['cells'][$i][3];
      $suma=$data->sheets[0]['cells'][$i][$colsuma];

      $actividadint=$actividadid;
      if (ctype_digit(trim($actividadint))) {
        $datosactividadpadre["actividadid"]=$actividadid;
        $datosactividadpadre["codigo_actividad"]=$areaid."-".$actividadid;
        $datosactividadpadre["codigo_prop_proy"]=$codigo;
        $datosactividadpadre["revision"]=$revision;
        $datosactividadpadre["areaid"]=$areaid;
        $datosactividadpadre["proyectoid"]=$proyectoid;
        $datosactividadpadre["propuestaid"]=$propuestaid;
        $datosactividadpadre["actividad_padre"]='';

        $datosactividadpadre["nombre"]=utf8_encode($nombre);
        $datosactividadpadre["fecha_creacion"]=date("Y-m-d");
        $datosactividadpadre["estado"]='P';
        $datosactividadpadre["duracion_total"]='0';
        $datosactividadpadre["h_propuesta"]=$suma;
      $datosactividadpadre["h_extra"]='0';
        $datosactividadpadre["h_planificada"]='0';
        $datosactividadpadre["orden"]=$i-1;
        $datosactividadpadre["isproyecto"]='S';
        $datosactividadpadre["moneda"]=$moneda;
        $bdactividad = new Admin_Model_DbTable_Actividad();

        if($bdactividad->_save($datosactividadpadre))
          {
          echo "guardo bien";   }
        
        } 
        else {
        $actividadeshijas = explode(".",$actividadint);
          if (count($actividadeshijas)=='2'){
            $datosactividadhija["actividadid"]=$actividadint;
            $datosactividadhija["codigo_actividad"]=$areaid."-".$actividadid;
            $datosactividadhija["codigo_prop_proy"]=$codigo;
            $datosactividadhija["revision"]=$revision;
            $datosactividadhija["areaid"]=$areaid;
            $datosactividadhija["proyectoid"]=$proyectoid;
            $datosactividadhija["propuestaid"]=$propuestaid;
            $datosactividadhija["actividad_padre"]=$actividadeshijas[0];

            $datosactividadhija["nombre"]=utf8_encode($nombre);
            $datosactividadhija["fecha_creacion"]=date("Y-m-d");
            $datosactividadhija["estado"]='P';
            $datosactividadhija["duracion_total"]='0';
            $datosactividadhija["h_propuesta"]=$suma;
          $datosactividadhija["h_extra"]='0';
            $datosactividadhija["h_planificada"]='0';
            $datosactividadhija["orden"]=$i-1;
            $datosactividadhija["isproyecto"]='S';
            $datosactividadhija["moneda"]=$moneda;
            $bdactividad = new Admin_Model_DbTable_Actividad();
          if($bdactividad->_save($datosactividadhija))
            {
              echo "guardo bien";
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
            {
              echo "guardo bien";
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

  
}   

public function subirareacategoriaAction() {
    
    $proyectoid= $this->_getParam("proyectoid");
    $codigo_prop_proy= $this->_getParam("codigo_prop_proy");
    

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
    $data->read('./upload/proyecto/'.$proyectoid.'-HH.xls');
    $columnas=$data->sheets[0]['numCols'];
    $filas=$data->sheets[0]['numRows'];

    for ($i = 2; $i <= $filas; $i++) {
        for ($j = 6; $j <= $columnas-2 ; $j++) {
        

        $areaid=$data->sheets[0]['cells'][$i][1];
        $actividadid=$data->sheets[0]['cells'][$i][2];
        $categoria=$data->sheets[0]['cells'][1][$j];
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
                if($existearea_categoria) {
                    
                   
                 
                    if ($bdequipo_area->_save($dataequipoarea))
                            {
                                echo "mmmmmmmmm";
                            }

                    
                }
                else
                {
                    $bdarea = new Admin_Model_DbTable_Area();
                    $datosarea=$bdarea->_getAreaxIndice($areaid);

                    $dataarea_cat['areaid']=$areaid;
                    $dataarea_cat['categoriaid']=$idcategoria;
                    $dataarea_cat['nombre']=$datosarea[0]['nombre']."-".$datoscat[0]['nombre_categoria'];
                    $dataarea_cat['estado']='A';
                    //print_r($dataarea_cat);
                    if ($bdarea_categoria->_save($dataarea_cat))
                    {
                        echo "se creo area_categoria";
                       if ($bdequipo_area->_save($dataequipoarea))
                            {
                                echo "lllllllllll";
                            }

                    } 
                }
        }

      }
    }
}   

public function subirtareoAction() {
   $proyectoid= $this->_getParam("proyectoid");
    $codigo_prop_proy= $this->_getParam("codigo_prop_proy");
    

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
    $data->read('./upload/proyecto/'.$proyectoid.'-HH.xls');
    $columnas=$data->sheets[0]['numCols'];
    $filas=$data->sheets[0]['numRows'];

    for ($i = 2; $i <= $filas; $i++) {
        for ($j = 6; $j <= $columnas-2 ; $j++) {

        $areaid=$data->sheets[0]['cells'][$i][1];
        $actividadid=$data->sheets[0]['cells'][$i][2];
        $categoria=$data->sheets[0]['cells'][1][$j];
        $nombre_actividad=$data->sheets[0]['cells'][$i][3];
        

        if (isset( $data->sheets[0]['cells'][$i][$j] ))
        {
            $suma=$data->sheets[0]['cells'][$i][$j];
        $horas_propuesta=utf8_encode($data->sheets[0]['cells'][$i][$j]);

        $bdcategoria = new Admin_Model_DbTable_Categoria();
        $datoscat=$bdcategoria-> _buscarCategoriaxTag($categoria);
        $idcategoria=$datoscat[0]['categoriaid'];
        $actividadint=$actividadid;
        $actividadeshijas = explode(".",$actividadint);
        if (count($actividadeshijas)=='2'){
            $codigo_actividad=$areaid."-".$actividadid;
            $bdtareo = new Admin_Model_DbTable_Tareo();
            $existe_tareo=$bdtareo->_getTareoxProyectoxActividadHijaxAreaxCategoria($proyectoid,$codigo,$revision,$actividadeshijas[0],$actividadid,$codigo_actividad,$areaid,$idcategoria);
            if(isset($existe_tareo)) { 
                $datostareo["actividadid"]=$actividadid;
                $datostareo["codigo_actividad"]=$areaid."-".$actividadid;
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
                if($bdtareo->_save($datostareo2)){
                    
                }
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
    $dataequipo['fecha_ingreso']=date("Y-m-d");
    $dataequipo['estado']='A';
    print_r($dataequipo);

    $dbequipo = new Admin_Model_DbTable_Equipo();
    $dbequipo->_save($dataequipo);
    
  }

}
