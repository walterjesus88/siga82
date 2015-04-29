<?php

class Propuesta_IndexController extends Zend_Controller_Action {

    public function init() {
    	$options = array(
            'layout' => 'layout',
        );
        Zend_Layout::startMvc($options);

    }
    
    public function indexAction() {
        echo "waaaaaa";
            
    }

    public function listarAction() {

        $listapropuesta = new Admin_Model_DbTable_Propuesta();
        $lista_enelaboracion=$listapropuesta->_getPropuestaAllOrdenadoxEstadoPropuesta('EE');
        $lista_ganada=$listapropuesta->_getPropuestaAllOrdenadoxEstadoPropuesta('G');
        $lista_perdida=$listapropuesta->_getPropuestaAllOrdenadoxEstadoPropuesta('P');
        $lista_enviada=$listapropuesta->_getPropuestaAllOrdenadoxEstadoPropuesta('E');
        $lista_declinada=$listapropuesta->_getPropuestaAllOrdenadoxEstadoPropuesta('D');
        $lista_anulada=$listapropuesta->_getPropuestaAllOrdenadoxEstadoPropuesta('A');
      // print_r($lista);
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
        //print_r($listapropuesta);
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
        //print_r($buscar);
        $this->view->lista_buscar = $buscar; 
            
    }  

    public function nuevoAction() {
        $buscapropuesta = new Admin_Model_DbTable_Propuesta();
        //$busca=$buscapropuesta->_getPropuestaxIndices($codigo,$propuestaid,$revision);
        //print_r($listapropuesta);
        //$this->view->buscapropuesta = $busca; 
            
    }  

    public function reporteexcelAction() {
        $buscapropuesta = new Admin_Model_DbTable_Propuesta();
        /** Include PHPExcel */
        $dir = APPLICATION_LIBRARY . "/PHPExcel1.8/Classes/PHPExcel.php";
        include ($dir);
        //require_once dirname(__FILE__) . '/../Classes/PHPExcel.php';
        $listapropuesta = new Admin_Model_DbTable_Propuesta();
        $status_propuesta=$this->_getParam('status_propuesta');
        If($status_propuesta=='EE'){
        $status="En Elaboración";}
        $lista_enelaboracion=$listapropuesta->_getPropuestaAllOrdenadoxEstadoPropuesta($status_propuesta);
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
        foreach ($lista_enelaboracion as $lista) {
            $buscacliente = new Admin_Model_DbTable_Cliente();
            $buscanombre_cliente=$buscacliente->_getClientexIndice($lista['clienteid']);
        $objPHPExcel->setActiveSheetIndex(0)
            ->setCellValue('A'.$i, $lista['propuestaid'])
            ->setCellValue('B'.$i, $lista['revision'])
            ->setCellValue('C'.$i, $buscanombre_cliente[0]['nombre_comercial'])
            ->setCellValue('D'.$i, $lista['nombre_propuesta'])
            ->setCellValue('E'.$i, $status);
            $i++;
        }
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Simple');
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
    
}
