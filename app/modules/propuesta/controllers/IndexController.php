<?php

class Propuesta_IndexController extends Zend_Controller_Action {

    public function init() {
    	$options = array(
            'layout' => 'inicio',
        );
        Zend_Layout::startMvc($options);

    }
    
    public function indexAction() {
            
    }

    public function listarAction() {

        $listapropuesta = new Admin_Model_DbTable_Propuesta();
        $lista_enelaboracion=$listapropuesta->_getPropuestaAllOrdenadoxEstadoPropuesta('EE');
        $lista_ganada=$listapropuesta->_getPropuestaAllOrdenadoxEstadoPropuesta('G');
        $lista_perdida=$listapropuesta->_getPropuestaAllOrdenadoxEstadoPropuesta('P');
        $lista_enviada=$listapropuesta->_getPropuestaAllOrdenadoxEstadoPropuesta('E');
        $lista_declinada=$listapropuesta->_getPropuestaAllOrdenadoxEstadoPropuesta('D');
        $lista_anulada=$listapropuesta->_getPropuestaAllOrdenadoxEstadoPropuesta('A');
        $this->view->lista_enelaboracion = $lista_enelaboracion; 
        $this->view->lista_ganada = $lista_ganada; 
        $this->view->lista_perdida = $lista_perdida; 
        $this->view->lista_enviada = $lista_enviada; 
        $this->view->lista_declinada = $lista_declinada; 
        $this->view->lista_anulada = $lista_anulada; 
    }

    public function verAction() {
        $codigo=$this->_getParam('codigo');
        $propuestaid=$this->_getParam('propuestaid');
        $revision=$this->_getParam('revision');
        $buscapropuesta = new Admin_Model_DbTable_Propuesta();
        $busca=$buscapropuesta->_getPropuestaxIndices($codigo,$propuestaid,$revision);
        $this->view->buscapropuesta = $busca; 
    }  

    public function cambiarAction() {
        try {
            $codigo=$this->_getParam('codigo');
            $propuestaid=$this->_getParam('propuestaid');
            $revision=$this->_getParam('revision');
            $estado=$this->_getParam('estado');
            if ($estado=='EE'){$orden = "1"; }
            if ($estado=='G'){$orden = "2"; }
            if ($estado=='P'){$orden = "3"; }
            if ($estado=='E'){$orden = "4"; }
            if ($estado=='D'){$orden = "5"; }
            if ($estado=='A'){$orden = "6"; }
            if ($estado=='S'){$orden = "7"; }
            if ($estado=='ELI'){$orden = "8"; }
            $updatepropuesta = new Admin_Model_DbTable_Propuesta();
            $data["estado_propuesta"]=$estado;
            $data["orden_estado"]=$orden;
            $str="codigo_prop_proy='$codigo' and propuestaid='$propuestaid' and revision='$revision'";
            $update=$updatepropuesta -> _update($data,$str); 
            if($update)
            {   ?>
                <script>                  
                    document.location.href="/propuesta/index/listar";
                </script>
                <?php
            }
            else
            {   ?>
                <script>                  
                    alert("Error al Cambiar estado verifique porfavor");
                    document.location.href="/propuesta/index/listar";                                                 
                </script>
                <?php
            } 
        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        }    
    }  
	
    public function buscarAction() {
        $this->_helper->layout()->disableLayout();
        $buscar_propuesta=$this->_getParam('propuesta');
        $buscar_propuesta=strtolower($buscar_propuesta);
        $buscapropuesta = new Admin_Model_DbTable_Propuesta();
        $buscar=$buscapropuesta->_buscarPropuesta($buscar_propuesta);
        $this->view->lista_buscar = $buscar; 
       
    }  

    public function buscarpropuestaAction() {
        $this->_helper->layout()->disableLayout();
        $buscar_propuesta=$this->_getParam('propuesta');
        
        $buscapropuesta = new Admin_Model_DbTable_Propuesta();
        $buscar=$buscapropuesta->_buscarPropuestaxPropuestaid($buscar_propuesta);
        $this->view->lista_buscar = $buscar; 
       
    } 

    public function nuevoAction() {
        $dbpropuesta = new Admin_Model_DbTable_Propuesta();
        $formdata['clienteid']=$clienteid = $this->_getParam('clienteid');
        $formdata['unidad_minera']=$uminera = $this->_getParam('uminera');
        $formdata['propuestaid']=$propuestaid = $this->_getParam('propuestaid');
        $formdata['revision']=$revision = $this->_getParam('revision');
        $formdata['nro_propuesta']=$nro_propuesta = $this->_getParam('nro_propuesta');
        $formdata['nombre_propuesta']=$nombre_propuesta = $this->_getParam('nombre_propuesta');   
        $formdata['responsable_porpuesta']=$responsable = $this->_getParam('responsable');   
        $formdata['revisor_final']=$revisor = $this->_getParam('revisor');
        $formdata['gerente_proyecto']=$gerente = $this->_getParam('gerente');
        $formdata['estado_propuesta']=$estado_propuesta = $this->_getParam('estado_propuesta');
        $formdata['tipo_propuesta']=$tipo_propuesta = $this->_getParam('tipo_propuesta');
        $formdata['moneda']=$tipo_moneda = $this->_getParam('tipo_moneda');
        $formdata['tipo_servicio']=$tipo_servicio = $this->_getParam('tipo_servicio');        
        $formdata['costo_gastos']=$costo_gastos = $this->_getParam('costo_gastos');
        
        
        $formdata['costo_honorarios']=$costo_honorarios = $this->_getParam('costo_honorarios');
        $formdata['costo_laboratorio']=$costo_laboratorio = $this->_getParam('costo_laboratorio');
        $formdata['costo_otros']=$costo_otros = $this->_getParam('costo_otros');
        $formdata['descuento']=$descuento = $this->_getParam('descuento');
        $formdata['total']=$total = $this->_getParam('total');        
        $formdata['fecha_entrega_bases']=$f_entrega_bases = $this->_getParam('f_entrega_bases');
        $formdata['fecha_presentacion_consulta']=$f_presentacion_consulta = $this->_getParam('f_presentacion_consulta');
        $formdata['fecha_absolucion_consulta']=$f_absolucion_consulta = $this->_getParam('f_absolucion_consulta');
        $formdata['fecha_presentacion_propuesta']=$f_presentacion_propuesta = $this->_getParam('f_presentacion_propuesta');
        $formdata['fecha_respuesta']=$f_respuesta_propuesta = $this->_getParam('f_respuesta_propuesta');
        $formdata['charla_informativa']=$f_charla_informativa = $this->_getParam('f_charla_informativa');
        $formdata['visita_tecnica']=$f_visita_tecnica = $this->_getParam('f_visita_tecnica');
        $codigo_prop_proy= 'PROP-'.date("Y")."-".$clienteid."-".$uminera."-".$propuestaid."-".$revision;

        $formdata['codigo_prop_proy']=$codigo_prop_proy;
        
        
       
        $dbpropuesta=new Admin_Model_DbTable_Proyecto();
 /*       if($dbpropuesta->_save($formdata))
        {
        echo "Archivo Guardado";

        }*/
       // print_r($formdata);

            
    }  

     public function guardarAction() {
        $this->_helper->layout()->disableLayout();
        $dbpropuesta = new Admin_Model_DbTable_Propuesta();
        //$busca=$buscapropuesta->_getPropuestaxIndices($codigo,$propuestaid,$revision);
        //print_r($listapropuesta);
        //$this->view->buscapropuesta = $busca; 
        $formdata['clienteid']=$clienteid = $this->_getParam('clienteid');
        $formdata['unidad_mineraid']=$uminera = $this->_getParam('uminera');
        $formdata['propuestaid']=$propuestaid = $this->_getParam('propuestaid');
        $formdata['revision']=$revision = $this->_getParam('revision');
        $formdata['nro_propuesta']=$nro_propuesta = $this->_getParam('nro_propuesta');
        $nombre_propuesta = $this->_getParam('nombre_propuesta');   
        $formdata['nombre_propuesta'] = str_replace("_"," ",$nombre_propuesta);

        $formdata['responsable_propuesta']=$responsable = $this->_getParam('responsable');   
        $formdata['revisor_final']=$revisor = $this->_getParam('revisor');
        $formdata['gerente_proyecto']=$gerente = $this->_getParam('gerente');
        $formdata['estado_propuesta']=$estado_propuesta = $this->_getParam('estado_propuesta');
        $formdata['tipo_propuesta']=$tipo_propuesta = $this->_getParam('tipo_propuesta');
        $formdata['control_documentario']=$control_documentario = $this->_getParam('control_documentario');
        $formdata['moneda']=$tipo_moneda = $this->_getParam('tipo_moneda');
        $formdata['tipo_servicio']=$tipo_servicio = $this->_getParam('tipo_servicio');        
        $formdata['costo_gastos']=$costo_gastos = $this->_getParam('costo_gastos');
        $formdata['costo_honorarios']=$costo_honorarios = $this->_getParam('costo_honorarios');
        $formdata['costos_laboratorio']=$costo_laboratorio = $this->_getParam('costo_laboratorio');
        $formdata['costo_otros']=$costo_otros = $this->_getParam('costo_otros');
        $formdata['descuento']=$descuento = $this->_getParam('descuento');
        $formdata['total']=$total = $this->_getParam('total');        
        $formdata['fecha_inicio']=$f_inicio_propuesta = $this->_getParam('f_inicio_propuesta');
        $formdata['fecha_entrega_bases']=$f_entrega_bases = $this->_getParam('f_entrega_bases');
        $formdata['fecha_presentacion_consulta']=$f_presentacion_consulta = $this->_getParam('f_presentacion_consulta');
        $formdata['fecha_absolucion_consulta']=$f_absolucion_consulta = $this->_getParam('f_absolucion_consulta');
        $formdata['fecha_presentacion_propuesta']=$f_presentacion_propuesta = $this->_getParam('f_presentacion_propuesta');
        $formdata['fecha_respuesta']=$f_respuesta_propuesta = $this->_getParam('f_respuesta_propuesta');
        $formdata['charla_informativa']=$f_charla_informativa = $this->_getParam('f_charla_informativa');
        $formdata['visita_tecnica']=$f_visita_tecnica = $this->_getParam('f_visita_tecnica');
        $codigo_prop_proy= 'PROP-'.date("Y")."-".$clienteid."-".$uminera."-".$propuestaid."-".$revision;
        $formdata['codigo_prop_proy']=$codigo_prop_proy;
        $formdata['oid']='AND-10';
        $formdata['isproyecto']="N";
        $estado=$this->_getParam('estado_propuesta');
            if ($estado=='EE'){$orden = "1"; }
            if ($estado=='G'){$orden = "2"; }
            if ($estado=='P'){$orden = "3"; }
            if ($estado=='E'){$orden = "4"; }
            if ($estado=='D'){$orden = "5"; }
            if ($estado=='A'){$orden = "6"; }
            if ($estado=='S'){$orden = "7"; }
       $formdata["orden_estado"]=$orden;


        if($dbpropuesta->_save($formdata))
        {
            echo "Archivo Guardado";
        }
      

            
    }


    public function reporteexcelAction() {
       $buscapropuesta = new Admin_Model_DbTable_Propuesta();
        /** Include PHPExcel */
        $dir = APPLICATION_LIBRARY . "/PHPExcel1.8/Classes/PHPExcel.php";
        include ($dir);
        //require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';
        $listapropuesta = new Admin_Model_DbTable_Propuesta();
        $lista=$listapropuesta->_getPropuestaAllOrdenadoOrdenEstado();
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")
            ->setLastModifiedBy("Maarten Balliauw")
            ->setTitle("Office 2007 XLSX Test Document")
            ->setSubject("Office 2007 XLSX Test Document")
            ->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")
            ->setKeywords("office 2007 openxml php")
            ->setCategory("Test result file");
        // Add some data
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A1', 'Codigo Propuesta')
            ->setCellValue('B1', 'Revisión')
            ->setCellValue('C1', 'Cliente')
            ->setCellValue('D1', 'Nombre')
            ->setCellValue('E1', 'Status');
        // Miscellaneous glyphs, UTF-8
        $i=2;
        foreach ($lista as $lista) {
            $buscacliente = new Admin_Model_DbTable_Cliente();
            $buscanombre_cliente=$buscacliente->_getClientexIndice($lista['clienteid']);
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i, $lista['propuestaid'])
            ->setCellValue('B'.$i, $lista['revision'])
            ->setCellValue('C'.$i, $buscanombre_cliente[0]['nombre_comercial'])
            ->setCellValue('D'.$i, $lista['nombre_propuesta'])
            ->setCellValue('E'.$i, $lista['nombre_propuesta']);
            $i++;
        }
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Reporte Propuesta Anddes');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        // Redirect output to a client’s web browser (Excel2007)
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="01simple.xlsx"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        // If you're serving to IE over SSL, then the following may be needed
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT'); // always modified
        header ('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header ('Pragma: public'); // HTTP/1.0
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save('php://output');
        exit;
    }

    public function umineraAction() {
      $this->_helper->layout()->disableLayout();
      $clienteid= $this->_getParam("clienteid");
      $dbunidadminera = new Admin_Model_DbTable_Unidadminera();
      $uminera=  $dbunidadminera->_getUnidadmineraxIndice($clienteid);
      $this->view->uminera = $uminera;
      //print_r($uminera);

    }    

    public function transmittalAction() {

        $dbtransmittal = new Admin_Model_DbTable_Transmittal();

        $this->view->listenviadoaprobacion = $dbtransmittal->_getTransmittalxStatus('A'); 
        
    }   
    
    public function generartransmittalAction() {
        $clienteid = $this->_getParam('clienteid');
        $this->view->clienteid = $clienteid;
        $uminera = $this->_getParam('unidadid');
        $this->view->uminera = $uminera;
        $propuestaid = $this->_getParam('propuestaid');
        $this->view->propuestaid = $propuestaid;
        $revision = $this->_getParam('revision');
        $this->view->revision = $revision;
        $codigo = $this->_getParam('codigo');
        $this->view->codigo = $codigo;
        $bdcliente = new Admin_Model_DbTable_Cliente();
        $datoscliente=$bdcliente->_getClientexIndice($clienteid);
        $this->view->cliente = $datoscliente[0]['nombre_comercial'];
        $dbunidadminera = new Admin_Model_DbTable_Unidadminera();
        $datosunidad=$dbunidadminera->_getUnidadmineraxid($clienteid,$uminera);
        $this->view->unidad = $datosunidad[0]['nombre'];

        $buscapropuesta = new Admin_Model_DbTable_Propuesta();
        $busca=$buscapropuesta->_getPropuestaxIndices($codigo,$propuestaid,$revision);
        $this->view->buscapropuesta = $busca; 



    }

    public function guardarpartepropuestaAction() {
     $this->_helper->layout()->disableLayout();
        $dbpartepropuesta = new Admin_Model_DbTable_Partepropuesta();
        $formdata['clienteid']=$clienteid = $this->_getParam('clienteid');
        $formdata['unidad_mineraid']=$uminera = $this->_getParam('uminera');
        $formdata['propuestaid']=$propuestaid = $this->_getParam('propuestaid');
        $formdata['revision']=$revision = $this->_getParam('revision');
        $formdata['tipo_revision']=$revision = $this->_getParam('revision');
        $formdata['estado']='A';
        $economica= $this->_getParam('economica');
        if ($economica=='S') {$prop='02';}
        $formdata['nro_propuesta']= $formdata['propuestaid']."-AND-PPA-".$prop;
$formdata['tipo_propuesta']= 'economica';
        
       if($dbpartepropuesta->_save($formdata))
        {
            echo "Archivo Guardado";
        }
    } 
    
     public function guardareconomicamodAction() {
        $this->_helper->layout()->disableLayout();
        $dbpartepropuesta = new Admin_Model_DbTable_Partepropuesta();
        $formdata['clienteid']=$clienteid = $this->_getParam('clienteid');
        $formdata['unidad_mineraid']=$uminera = $this->_getParam('uminera');
        $formdata['propuestaid']=$propuestaid = $this->_getParam('propuestaid');
        $revision = $this->_getParam('revision');
        if ($revision=='A') { $revisionmod='B';}
        if ($revision=='B') { $revisionmod='C';}
        if ($revision=='C') { $revisionmod='D';}
        if ($revision=='D') { $revisionmod='E';}

        
        $data["estado"]='I';
        $str="propuestaid='$propuestaid' and revision='$revision' and clienteid='$clienteid' and unidad_mineraid='$uminera' and tipo_propuesta='economica' ";
        $update=$dbpartepropuesta -> _update($data,$str); 

        $formdata['revision']=$revisionmod;
        $formdata['tipo_revision']=$revisionmod;
        $formdata['estado']='A';
        $economica= $this->_getParam('economica');
        if ($economica=='S') {$prop='02';}
        $formdata['nro_propuesta']= $formdata['propuestaid']."-AND-PPA-".$prop;
        $formdata['tipo_propuesta']= 'economica';
        if($dbpartepropuesta->_save($formdata))
        {
            echo "Archivo Guardado";
        }
    } 

    public function guardartecnicaAction() {
        $this->_helper->layout()->disableLayout();
        $dbpartepropuesta = new Admin_Model_DbTable_Partepropuesta();
        $formdata['clienteid']=$clienteid = $this->_getParam('clienteid');
        $formdata['unidad_mineraid']=$uminera = $this->_getParam('uminera');
        $formdata['propuestaid']=$propuestaid = $this->_getParam('propuestaid');
        $formdata['revision']=$revision = $this->_getParam('revision');
        $formdata['tipo_revision']=$revision = $this->_getParam('revision');
        $formdata['estado']='A';
        $tecnica= $this->_getParam('tecnica');
        if ($tecnica=='S') {$prop='01';}
        $formdata['nro_propuesta']= $formdata['propuestaid']."-AND-PPA-".$prop;
        $formdata['tipo_propuesta']= 'tecnica';
        if($dbpartepropuesta->_save($formdata))
        {
            echo "Archivo Guardado";
        }
    } 

     public function guardartecnicamodAction() {
        $this->_helper->layout()->disableLayout();
        $dbpartepropuesta = new Admin_Model_DbTable_Partepropuesta();
        $formdata['clienteid']=$clienteid = $this->_getParam('clienteid');
        $formdata['unidad_mineraid']=$uminera = $this->_getParam('uminera');
        $formdata['propuestaid']=$propuestaid = $this->_getParam('propuestaid');
        $revision = $this->_getParam('revision');
        if ($revision=='A') { $revisionmod='B';}
        if ($revision=='B') { $revisionmod='C';}
        if ($revision=='C') { $revisionmod='D';}
        if ($revision=='D') { $revisionmod='E';}

        
        $data["estado"]='I';
        $str="propuestaid='$propuestaid' and revision='$revision' and clienteid='$clienteid' and unidad_mineraid='$uminera' and tipo_propuesta='tecnica' ";
        $update=$dbpartepropuesta -> _update($data,$str); 

        $formdata['revision']=$revisionmod;
        $formdata['tipo_revision']=$revisionmod;
        $formdata['estado']='A';
        $tecnica= $this->_getParam('tecnica');
        if ($tecnica=='S') {$prop='01';}
        $formdata['nro_propuesta']= $formdata['propuestaid']."-AND-PPA-".$prop;
        $formdata['tipo_propuesta']= 'tecnica';
        if($dbpartepropuesta->_save($formdata))
        {
            echo "Archivo Guardado";
        }
    } 


     public function guardarseguridadAction() {
        $this->_helper->layout()->disableLayout();
        $dbpartepropuesta = new Admin_Model_DbTable_Partepropuesta();
        $formdata['clienteid']=$clienteid = $this->_getParam('clienteid');
        $formdata['unidad_mineraid']=$uminera = $this->_getParam('uminera');
        $formdata['propuestaid']=$propuestaid = $this->_getParam('propuestaid');
        $formdata['revision']=$revision = $this->_getParam('revision');
        $formdata['tipo_revision']=$revision = $this->_getParam('revision');
        $formdata['estado']='A';
        $seguridad= $this->_getParam('seguridad');
        if ($seguridad=='S') {$prop='03';}
        $formdata['nro_propuesta']= $formdata['propuestaid']."-AND-PPA-".$prop;
        $formdata['tipo_propuesta']= 'seguridad';
        if($dbpartepropuesta->_save($formdata))
        {
            echo "Archivo Guardado";
        }
    } 

 public function guardarseguridadmodAction() {
        $this->_helper->layout()->disableLayout();
        $dbpartepropuesta = new Admin_Model_DbTable_Partepropuesta();
        $formdata['clienteid']=$clienteid = $this->_getParam('clienteid');
        $formdata['unidad_mineraid']=$uminera = $this->_getParam('uminera');
        $formdata['propuestaid']=$propuestaid = $this->_getParam('propuestaid');
        $revision = $this->_getParam('revision');
        if ($revision=='A') { $revisionmod='B';}
        if ($revision=='B') { $revisionmod='C';}
        if ($revision=='C') { $revisionmod='D';}
        if ($revision=='D') { $revisionmod='E';}

        
        $data["estado"]='I';
        $str="propuestaid='$propuestaid' and revision='$revision' and clienteid='$clienteid' and unidad_mineraid='$uminera' and tipo_propuesta='seguridad' ";
        $update=$dbpartepropuesta -> _update($data,$str); 

        $formdata['revision']=$revisionmod;
        $formdata['tipo_revision']=$revisionmod;
        $formdata['estado']='A';
        $seguridad= $this->_getParam('seguridad');
        if ($seguridad=='S') {$prop='03';}
        $formdata['nro_propuesta']= $formdata['propuestaid']."-AND-PPA-".$prop;
        $formdata['tipo_propuesta']= 'seguridad';
        if($dbpartepropuesta->_save($formdata))
        {
            echo "Archivo Guardado";
        }
    } 


    public function guardarcontactoAction() {
        $this->_helper->layout()->disableLayout();
        $dbcontacto = new Admin_Model_DbTable_Contacto();
        $formdata['clienteid']=$clienteid = $this->_getParam('clienteid');
        $formdata['unidad_mineraid']=$uminera = $this->_getParam('uminera');
        $formdata['areaid']=$propuestaid = $this->_getParam('area_contacto');
        $nombre_contacto = $this->_getParam('nombre_contacto');
        $formdata['nombre'] = str_replace("_"," ",$nombre_contacto);
        $formdata['contactoid'] = $formdata['clienteid']."-".$formdata['areaid'];
        
       if($dbcontacto->_save($formdata))
        {
            echo "Archivo Guardado";
        }
    } 

    public function generarrevisionAction() {
        
        $clienteid = $this->_getParam('clienteid');
        $this->view->clienteid = $clienteid;
        $uminera = $this->_getParam('unidadid');
        $this->view->uminera = $uminera;
        $propuestaid = $this->_getParam('propuestaid');
        $this->view->propuestaid = $propuestaid;
        $revision = $this->_getParam('revision');
        $this->view->revision = $revision;
        $codigo = $this->_getParam('codigo');
        $this->view->codigo = $codigo;
        $bdcliente = new Admin_Model_DbTable_Cliente();
        $datoscliente=$bdcliente->_getClientexIndice($clienteid);
        $this->view->cliente = $datoscliente[0]['nombre_comercial'];
        $dbunidadminera = new Admin_Model_DbTable_Unidadminera();
        $datosunidad=$dbunidadminera->_getUnidadmineraxid($clienteid,$uminera);
        $this->view->unidad = $datosunidad[0]['nombre'];

        $buscapropuesta = new Admin_Model_DbTable_Propuesta();
        $busca=$buscapropuesta->_getPropuestaxIndices($codigo,$propuestaid,$revision);
        $this->view->buscapropuesta = $busca; 

           
    }

     public function guardarrevisionAction() {
        $this->_helper->layout()->disableLayout();
        $dbpropuesta = new Admin_Model_DbTable_Propuesta();
        $formdata['clienteid']=$clienteid = $this->_getParam('clienteid');
        $formdata['unidad_mineraid']=$uminera = $this->_getParam('uminera');
        $formdata['propuestaid']=$propuestaid = $this->_getParam('propuestaid');
        $revision = $this->_getParam('revision');
        if ($revision=='A') { $revisionmod='B';}
        if ($revision=='B') { $revisionmod='C';}
        if ($revision=='C') { $revisionmod='D';}
        if ($revision=='D') { $revisionmod='E';}

       $codigo = $this->_getParam('codigo');
        

        $data["estado_propuesta"]='S';
        $data["orden_estado"]='7';
        $str="propuestaid='$propuestaid' and revision='$revision' and clienteid='$clienteid' and unidad_mineraid='$uminera' and  codigo_prop_proy='$codigo'";
        $update=$dbpropuesta -> _update($data,$str); 

        $propuestaant=$dbpropuesta->_getPropuestaxIndices($codigo,$propuestaid,$revision);
        
        $codigo_prop_proy= 'PROP-'.date("Y")."-".$clienteid."-".$uminera."-".$propuestaid."-".$revisionmod;

        $formdata['codigo_prop_proy']=$codigo_prop_proy;
        $formdata['propuestaid']=$propuestaant[0]['propuestaid'];
        $formdata['revision']=$revisionmod;
        $formdata['oid']=$propuestaant[0]['oid'];
        $formdata['clienteid']=$propuestaant[0]['clienteid'];
        $formdata['unidad_mineraid']=$propuestaant[0]['unidad_mineraid'];
        $formdata['gerente_proyecto']=$propuestaant[0]['gerente_proyecto'];
        $formdata['responsable_propuesta']=$propuestaant[0]['responsable_propuesta'];
        $formdata['control_documentario']=$propuestaant[0]['control_documentario'];
        $formdata['revisor_final']=$propuestaant[0]['revisor_final'];
        $formdata['nombre_propuesta']=$propuestaant[0]['nombre_propuesta'];
        $formdata['tipo_propuesta']=$propuestaant[0]['tipo_propuesta'];
        $formdata['estado_propuesta']='EE';
        $formdata['ubicacion']=$propuestaant[0]['ubicacion'];
        $formdata['tipo_servicio']=$propuestaant[0]['tipo_servicio'];
        $formdata['descripcion']=$propuestaant[0]['descripcion'];
        $formdata['tag']=$propuestaant[0]['tag'];
        $formdata['charla_informativa']=$propuestaant[0]['charla_informativa'];
        $formdata['visita_tecnica']=$propuestaant[0]['visita_tecnica'];
        $formdata['transmita_envio']=$propuestaant[0]['transmita_envio'];
        $formdata['resultado_propuesta']=$propuestaant[0]['resultado_propuesta'];
        $formdata['costo_honorarios']=$propuestaant[0]['costo_honorarios'];
        $formdata['costo_gastos']=$propuestaant[0]['costo_gastos'];
        $formdata['costos_laboratorio']=$propuestaant[0]['costos_laboratorio'];
        $formdata['total_horas']=$propuestaant[0]['total_horas'];
        $formdata['sub_total']=$propuestaant[0]['sub_total'];
        $formdata['total']=$propuestaant[0]['total'];
        $formdata['moneda']=$propuestaant[0]['moneda'];
        $formdata['isproyecto']=$propuestaant[0]['isproyecto'];
        $formdata['orden_estado']='1';
        $formdata['nro_propuesta']=$propuestaant[0]['nro_propuesta'];
        $formdata['costo_otros']=$propuestaant[0]['costo_otros'];
        $formdata['descuento']=$propuestaant[0]['descuento'];
        $formdata['fecha_inicio']=$propuestaant[0]['fecha_inicio'];
        $formdata['fecha_entrega_bases']=$propuestaant[0]['fecha_entrega_bases'];
        $formdata['fecha_presentacion_consulta']=$propuestaant[0]['fecha_presentacion_consulta'];
        $formdata['fecha_absolucion_consulta']=$propuestaant[0]['fecha_absolucion_consulta'];
        $formdata['fecha_presentacion_propuesta']=$propuestaant[0]['fecha_presentacion_propuesta'];
        $formdata['fecha_respuesta']=$propuestaant[0]['fecha_respuesta'];
        if($dbpropuesta->_save($formdata))
        {
            echo "Archivo Guardado";
        }
    }  

    public function guardartransmittaAction() {
        $this->_helper->layout()->disableLayout();
        $dbtransmittal = new Admin_Model_DbTable_Transmittal();
        $formdata1['clienteid']=$clienteid = $this->_getParam('clienteid');
        $correlativo = $this->_getParam('correlativo');
        $formdata1['unidad_mineraid']=$uminera = $this->_getParam('uminera');
        $formdata1['propuestaid']=$propuestaid = $this->_getParam('propuestaid');
        $formdata1['revision']=$revision = $this->_getParam('revision');
        $formdata1['codigo_prop_proy']=$codigo = $this->_getParam('codigo');
$formdata1['fecha_envio']=$fecha_transmitta = $this->_getParam('fecha_transmitta');
        $formdata1['nro_documento']=$nro_transmitta = $this->_getParam('nro_transmitta');
        $formdata1['transmittaid']="10-TR-AND-16-15".$correlativo;
        
        $formdata1['envio']=$envio = $this->_getParam('envio');
        $formdata1['tipo_envio']=$tipo_envio = $this->_getParam('tipo_envio');
        $formdata1['para']=$contactoid = $this->_getParam('contactoid');
        $formdata1['cc']=$contactoid2 = $this->_getParam('contactoid2');
            $formdata1['autorizado_por']=$autorizado = $this->_getParam('autorizado');

    $formdata1['aprobado_por']=$aprobado = $this->_getParam('aprobado');


        

     
        
       if($dbtransmittal->_save($formdata1))
        {
            echo "Archivo Guardado";
        }
        else
        {
            print_r($dbtransmittal->_save($formdata1));
        }
    } 


    public function impresiontransmittalAction(){
        $this->_helper->layout()->disableLayout();
        $propuestaid = $this->_getParam('propuestaid');
        $revision = $this->_getParam('revision');
        $transmittaid = $this->_getParam('transmittaid');
        $codigo_prop_proy = $this->_getParam('codigo_prop_proy');
        
        // echo $propuestaid;
        // echo $revision;
        // echo $transmittaid;
        // echo $codigo_prop_proy;

        $where['codigo_prop_proy']=$codigo_prop_proy;
        $where['revision']=$revision;
        $where['transmittaid']=$transmittaid;
        $where['propuestaid']=$propuestaid;
        //print_r($where);

        $dbtransmitta = new Admin_Model_DbTable_Transmittal();
        $datatransmitta=$dbtransmitta ->_getOne($where);
        $this->view->transmitta = $datatransmitta; 

        //print_r($datatransmitta);

    }

}
