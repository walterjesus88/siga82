<?php
class Admin_Model_DbTable_Formatocp extends Zend_Db_Table_Abstract
{
  protected $formatos = [];
  protected $modelo = '';
  protected $cabecera = [];
  protected $data = [];
  protected $fileName = '';
  protected $pages = [];
  protected $columns = [];
  protected $width_columns = [];
  protected $carpeta = 'reportes/';

public function __construct($formato, $cabecera, $cuerpo) {
    parent::__construct();

    $this->formatos['anddes'] = 'formatos/tr-anddes.pdf';
    $this->formatos['barrick'] = 'formatos/tr-barrick.pdf';
    $this->formatos['cerro_verde'] = 'formatos/tr-cerro-verde.pdf';
    $this->formatos['edt'] = 'formatos/edt.pdf';
    $this->formatos['proyectos'] = 'formatos/proyectos.pdf';
    $this->formatos['carpetas'] = 'formatos/carpetas.pdf';
    $this->formatos['reporte_transmittal'] = 'formatos/reporte-tr.pdf';
    $this->formatos['reporte_cliente'] = 'formatos/cliente.pdf';
    $this->formatos['reporte_cliente'] = 'formatos/cliente.pdf';
    $this->formatos['lista_entregable'] = 'formatos/lista_entregable.pdf';
    $this->formatos['performance'] = 'formatos/performance.pdf';
    $this->modelo = $this->formatos[$formato];
    $this->cabecera = $cabecera;
    $this->data = $cuerpo;
    $this->template = Zend_Pdf::load($this->modelo);
    $this->_setConfiguration();
  }

protected function _setConfiguration()
  {
    $this->font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
    $this->font_size = 9;
    switch ($this->modelo) {
      case $this->formatos['anddes']:
        $this->max_high = 850;
        $this->max_width = 600;
        $this->position = 650;
        break;

      case $this->modelo == $this->formatos['proyectos']:
        $this->max_high = 600;
        $this->max_width = 850;
        $this->position = 470;
        break;

      case  $this->modelo == $this->formatos['lista_entregable']:
        $this->max_high = 600;
        $this->max_width = 850;
        $this->position = 470;
        break;

      case  $this->modelo == $this->formatos['performance']:
        $this->max_high = 600;
        $this->max_width = 850;
        $this->position = 445;
        break;

    default:
      # code...
      break;
  }
}

public function _print()
  {
    try {
      //$pdf = Zend_Pdf::load($this->modelo);
      $pdf_rellenado = $this->_rellenarFormato();
      $pdf_rellenado->save($this->fileName);
      $ruta['archivo'] = $this->fileName;
        return $ruta;
        
    } catch (Exception $e) {
      print $e->getMessage();
    }
}


protected function _rellenarFormato()
{
    //rellenado de los distintos formatos segun sea el caso
    switch ($this->modelo) {
      case $this->formatos['anddes']:
        $pdf = $this->_rellenarFormatoAnddes();
        break;

      case $this->formatos['barrick']:
        # code...
        break;

      case $this->formatos['cerro_verde']:
        # code...
        break;

      case $this->modelo == $this->formatos['edt']:
        $pdf = $this->_rellenarFormatoEdt();
        break;

      case $this->modelo == $this->formatos['proyectos']:
        $pdf = $this->_rellenarFormatoProyectos();
        break;

      case $this->modelo == $this->formatos['carpetas']:
        $pdf = $this->_rellenarFormatoCarpetas();
        break;

      case $this->modelo == $this->formatos['reporte_transmittal']:
        $pdf = $this->_rellenarFormatoReporteTr();
        break;

      case $this->modelo == $this->formatos['reporte_cliente']:
        $pdf = $this->_rellenarFormatoReporteCl();
        break;

      case $this->modelo == $this->formatos['lista_entregable']:
        $pdf = $this->_rellenarFormatoListaEntregable();
        break;

      case $this->modelo == $this->formatos['performance']:
        $pdf = $this->_rellenarFormatoPerformance();
        break;

      default:
        # code...
        break;
    }

  return $pdf;
}


protected function _rellenarFormatoPerformance()
{

  $pdf = new Zend_Pdf();
  $first = true;
  $a = $this->position;
  $j = 0;

  //print_r($a);
  //print_r($this->data);exit();

  for ($i=0; $i < sizeof($this->data); $i++) 
  {
    if ($first == true) {
      $page = clone $this->template->pages[0];
      $page->setFont($this->font, $this->font_size);
      $pdf->pages[] = $page;
        //datos cabecera
      $page->drawText($this->data[$i]['proyectoid'], 755, 568);
      $page->drawText($this->data[$i]['revision_cronograma'], 755, 545);
    }   
      $linea = $a;
      $max = $linea;
      $page->drawText((string)$i + 1, 6, $a);      
      $page->drawText($this->data[$i]['actividadid'], 26, $a);

      do {
        $texto = substr($this->data[$i]['nombre'], 0, 33); 
        // $texto = iconv( "ISO-8859-1//TRANSLIT","Windows-1250", $texto);//print_r($texto);

        $page->drawText($texto, 52, $linea,'UTF-8//IGNORE','ISO-8859-1//TRANSLIT','Windows-1250','UTF-16');

        $this->data[$i]['nombre'] = substr($this->data[$i]['nombre'],
        33, strlen($this->data[$i]['nombre']));
        $linea = $linea - 10;
      } while (strlen($this->data[$i]['nombre']) > 0);

      //$page->drawText($this->data[$i]['nombre'], 80, $a);
      $page->drawText($this->data[$i]['costo_propuesta'], 200, $a);
      $page->drawText($this->data[$i]['horas_propuesta'], 250, $a);
      $page->drawText($this->data[$i]['horas_planificado'], 291, $a);
      $page->drawText($this->data[$i]['costo_planificado'], 329, $a);
      $page->drawText($this->data[$i]['porcentaje_planificado'], 373, $a);
      $page->drawText($this->data[$i]['horas_real'], 415, $a);
      $page->drawText($this->data[$i]['costo_real'], 457, $a);
      $page->drawText($this->data[$i]['porcentaje_real'], 500, $a);
      $page->drawText($this->data[$i]['fecha_comienzo'], 521, $a);
      $page->drawText($this->data[$i]['fecha_fin'], 569, $a);
      $page->drawText($this->data[$i]['duracion'], 630, $a);
      $page->drawText($this->data[$i]['fecha_comienzo_real'], 657, $a);
      $page->drawText($this->data[$i]['fecha_fin_real'], 705, $a);
      $page->drawText($this->data[$i]['fecha_performance'], 754, $a);
      $page->drawText($this->data[$i]['porcentaje_performance'], 811, $a);
     
      //$page->drawText($this->data[$i]['fecha_performance'], 850, $a);
  
    
      $first = false;
      $a = $max - 40;
      $j++;
      if ($a < 10) {
        $a = $this->position;
        $first = true;
        $j = 0;
      }
  }

  $this->fileName = $this->carpeta.'Lista de performance.pdf';
  return $pdf;

}

protected function _rellenarFormatoListaEntregable()
{
  $pdf = new Zend_Pdf();
  $first = true;
  $a = $this->position;
  $j = 0;

  for ($i=0; $i < sizeof($this->data); $i++) 
  {
    if ($first == true) {
      $page = clone $this->template->pages[0];
      $page->setFont($this->font, $this->font_size);
      $pdf->pages[] = $page;
        //datos cabecera
      $page->drawText($this->data[$i]['proyectoid'], 755, 568);
      $page->drawText($this->data[$i]['revision_entregable'], 755, 545);
    }   
      $page->drawText((string)$i + 1, 7, $a);      
      $page->drawText($this->data[$i]['nombre_edt'], 50, $a);
      $page->drawText($this->data[$i]['tipo_documento'], 110, $a);
      $linea = $a;
      $max = $linea;
      do {
        $texto = substr($this->data[$i]['nombre'], 0, 15);
        $page->drawText($texto, 170, $linea, 'UTF-8');
        $this->data[$i]['nombre'] = substr($this->data[$i]['nombre'],
        15, strlen($this->data[$i]['nombre']));
        $linea = $linea - 10;
      } while (strlen($this->data[$i]['nombre']) > 0);
      $linea = $a;
      $max = $linea;
      do {
        $texto = substr($this->data[$i]['codigo_cliente'], 0, 17);
        $page->drawText($texto, 250, $linea, 'UTF-8');
        $this->data[$i]['codigo_cliente'] = substr($this->data[$i]['codigo_cliente'],
        17, strlen($this->data[$i]['codigo_cliente']));
        $linea = $linea - 10;
      } while (strlen($this->data[$i]['codigo_cliente']) > 0);
      $linea = $a;
      $max = $linea;
      do {
        $texto = substr($this->data[$i]['codigo_anddes'], 0, 17);
        $page->drawText($texto, 350, $linea, 'UTF-8');
        $this->data[$i]['codigo_anddes'] = substr($this->data[$i]['codigo_anddes'],
        17, strlen($this->data[$i]['codigo_anddes']));
        $linea = $linea - 10;
      } while (strlen($this->data[$i]['codigo_anddes']) > 0);
      $linea = $a;
      $max = $linea;
      do {
        $texto = substr($this->data[$i]['descripcion_entregable'], 0, 40);
        $page->drawText($texto, 455, $linea, 'UTF-8');
        $this->data[$i]['descripcion_entregable'] = substr($this->data[$i]['descripcion_entregable'],
        40, strlen($this->data[$i]['descripcion_entregable']));
        $linea = $linea - 10;
      } while (strlen($this->data[$i]['descripcion_entregable']) > 0);

      $page->drawText($this->data[$i]['fecha_a'], 640, $a);
      $page->drawText($this->data[$i]['fecha_b'], 710, $a);
      $page->drawText($this->data[$i]['fecha_0'], 780, $a);
      $first = false;
      $a = $max - 50;
      $j++;
      if ($a < 10) {
        $a = $this->position;
        $first = true;
        $j = 0;
      }
  }

  //exit(); 

  $this->fileName = $this->carpeta.'Lista de Entregable.pdf';
  return $pdf;
}

protected function _rellenarFormatoProyectos()
  {
    //estados
    echo "kik";exit();
    $estados = [];
    $estados['A'] = 'Activo';
    $estados['P'] = 'Paralizado';
    $estados['C'] = 'Cerrado';
    $estados['CA'] = 'Cancelado';

    $pdf = new Zend_Pdf();

    $first = true;
    $a = $this->position;
    $j  = 0;

    for ($i=0; $i < sizeof($this->data); $i++) {

      if ($first == true) {
        $page = clone $this->template->pages[0];
        $page->setFont($this->font, $this->font_size);
        $pdf->pages[] = $page;
        //datos cabecera
        $page->drawText(date("d-m-Y"), 750, 572);
        $page->drawText(sizeof($this->data), 750, 543);
      }

      //datos del cuerpo
      $page->drawText((string)$i + 1, 7, $a);
      $page->drawText($this->data[$i]['proyectoid'], 30, $a);

      $linea = $a;
      $max = $linea;
      do {
        $texto = substr($this->data[$i]['nombre_comercial'], 0, 40);
        $page->drawText($texto, 85, $linea, 'UTF-8');
        $this->data[$i]['nombre_comercial'] = substr($this->data[$i]['nombre_comercial'],
        40, strlen($this->data[$i]['nombre_comercial']));
        $linea = $linea - 10;
      } while (strlen($this->data[$i]['nombre_comercial']) > 0);

      if ($max > $linea) {
        $max = $linea;
      }

      $linea = $a;
      $max = $linea;
      do {
        $texto = substr($this->data[$i]['nombre_proyecto'], 0, 52);
        $page->drawText($texto, 260, $linea, 'UTF-8');
        $this->data[$i]['nombre_proyecto'] = substr($this->data[$i]['nombre_proyecto'],
        52, strlen($this->data[$i]['nombre_proyecto']));
        $linea = $linea - 10;
      } while (strlen($this->data[$i]['nombre_proyecto']) > 0);

      if ($max > $linea) {
        $max = $linea;
      }

      $page->drawText($this->data[$i]['gerente_proyecto'], 495, $a);
      $page->drawText($this->data[$i]['control_proyecto'], 590, $a);
      $page->drawText($this->data[$i]['control_documentario'], 685, $a);
      $page->drawText($estados[$this->data[$i]['estado']], 780, $a);

      $first = false;
      $a = $max - 20;
      $j++;

      if ($a < 10) {
        $a = $this->position;
        $first = true;
        $j = 0;
      }

    }

    $this->fileName = $this->carpeta.'Lista de Proyectos.pdf';
    return $pdf;
  }


}
