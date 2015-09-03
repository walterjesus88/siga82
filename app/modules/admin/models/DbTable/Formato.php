<?php
class Admin_Model_DbTable_Formato extends Zend_Db_Table_Abstract
{
  protected $formatos = [];
  protected $modelo = '';
  protected $cabecera = [];
  protected $data = [];
  protected $fileName = '';
  protected $pages = [];
  protected $columns = [];
  protected $width_columns = [];

  public function __construct($formato, $cabecera, $cuerpo) {
     parent::__construct();

     /*distintos formatos existentes, apuntando a los archivos donde
     estan las plantillas*/
     $this->formatos['anddes'] = 'formatos/tr-anddes.pdf';
     $this->formatos['barrick'] = 'formatos/tr-barrick.pdf';
     $this->formatos['cerro_verde'] = 'formatos/tr-cerro-verde.pdf';
     $this->formatos['edt'] = 'formatos/edt.pdf';
     $this->formatos['proyectos'] = 'formatos/proyectos.pdf';
     $this->formatos['carpetas'] = 'formatos/carpetas.pdf';
     $this->formatos['reporte_transmittal'] = 'formatos/reporte-tr.pdf';
     $this->formatos['reporte_cliente'] = 'formatos/cliente.pdf';

     //seteando los datos necesarios
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

      case $this->formatos['barrick']:
        $this->max_high = 850;
        $this->max_width = 600;
        break;

      case $this->formatos['cerro_verde']:
        $this->max_high = 850;
        $this->max_width = 600;
        break;

      case $this->modelo == $this->formatos['edt']:
        $this->max_high = 850;
        $this->max_width = 600;
        $this->position = 650;
        break;

      case $this->modelo == $this->formatos['proyectos']:
        $this->max_high = 600;
        $this->max_width = 850;
        $this->position = 470;
        break;

      case $this->modelo == $this->formatos['carpetas']:
        $this->max_high = 850;
        $this->max_width = 600;
        $this->position = 720;
        break;

      case $this->modelo == $this->formatos['reporte_transmittal']:
        $this->max_high = 600;
        $this->max_width = 850;
        $this->position = 385;
        break;

      case $this->modelo == $this->formatos['reporte_cliente']:
        $this->max_high = 600;
        $this->max_width = 850;
        $this->position = 400;
        break;

      default:
        # code...
        break;
    }
  }

  public function _print()
  {
    try {
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

      default:
        # code...
        break;
    }

    return $pdf;
  }

  protected function _rellenarFormatoEdt()
  {
    $page = clone $this->template->pages[0];
    $page->setFont($this->font, $this->font_size);
    //datos de cabecera
    $page->drawText(date("d-m-Y"), 500, 800);
    $page->drawText($this->cabecera['nombre_proyecto'], 140, 740, 'UTF-8');
    $page->drawText($this->cabecera['proyectoid'], 450, 740);
    //datos del cuerpo
    for ($i=0; $i < sizeof($this->data); $i++) {
      $page->drawText($this->data[$i]['codigo'], 140, 650 - ($i * 20));
      $page->drawText($this->data[$i]['nombre'], 200, 650 - ($i * 20));
    }

    $pdf = new Zend_Pdf();
    $pdf->pages[] = $page;

    $this->fileName = $this->cabecera['proyectoid'].'-EDT.pdf';
    return $pdf;
  }

  protected function _rellenarFormatoProyectos()
  {
    //estados
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

    $this->fileName = 'Lista de Proyectos.pdf';
    return $pdf;
  }

  protected function _rellenarFormatoAnddes()
  {
    $page = clone $this->template->pages[0];
    $page->setFont($this->font, $this->font_size);
    //datos de cabecera
    $page->drawText($this->cabecera['nombre_comercial'], 70, 740, 'UTF-8');
    $page->drawText($this->cabecera['puesto_trabajo'], 70, 725, 'UTF-8');
    $page->drawText($this->cabecera['nombre_atencion'], 70, 710, 'UTF-8');
    $page->drawText($this->cabecera['codificacion'].'-'.$this->cabecera['correlativo'], 360, 740);
    $page->drawText($this->cabecera['proyectoid'], 360, 725);
    $page->drawText('¿de que estamos hablando?', 360, 710, 'UTF-8');

    if ($this->cabecera['modo_envio'] == 'C') {
      $page->drawText('X', 96, 693);
    } elseif ($this->cabecera['modo_envio'] == 'F') {
      $page->drawText('X', 205, 693);
    }

    $page->drawText('¿Codigo de que?', 360, 695, 'UTF-8');
    $page->drawText(date("d-m-Y"), 510, 695);

    //cuerpo
    $page->setFont($this->font, 7);
    for ($i=0; $i < sizeof($this->data); $i++) {
      $page->drawText((string)$i + 1, 7, 650 - ($i * 20));
      $page->drawText($this->data[$i]['codigo_anddes'], 30, 650 - ($i * 20));
      $page->drawText($this->data[$i]['revision'], 145, 650 - ($i * 20));
      $page->drawText($this->data[$i]['descripcion_entregable'], 170, 650 - ($i * 20), 'UTF-8');
      $page->drawText($this->data[$i]['tipo_documento'], 505, 650 - ($i * 20));

    }
    $page->setFont($this->font, $this->font_size);
    if ($this->data[0]['emitido'] == 'A') {
      $page->drawText('X', 40, 155);
    } elseif ($this->data[0]['emitido'] == 'B') {
      $page->drawText('X', 40, 133);
    } elseif ($this->data[0]['emitido'] == 'C') {
      $page->drawText('X', 40, 110);
    } elseif ($this->data[0]['emitido'] == 'AP') {
      $page->drawText('X', 325, 155);
    } elseif ($this->data[0]['emitido'] == 'AC') {
      $page->drawText('X', 325, 133);
    } elseif ($this->data[0]['emitido'] == 'NA') {
      $page->drawText('X', 325, 110);
    }

    $pdf = new Zend_Pdf();
    $pdf->pages[] = $page;
    $this->fileName = $this->cabecera['codificacion'].'-'.$this->cabecera['correlativo'].'.pdf';
    return $pdf;
  }

  protected function _rellenarFormatoCarpetas()
  {
    $page = clone $this->template->pages[0];
    $page->setFont($this->font, $this->font_size);
    //cabecera
    $page->drawText(date("d-m-Y"), 510, 800);

    //datos
    for ($i=0; $i < sizeof($this->data); $i++) {
      $page->drawText((string)$i + 1, 7, 720 - ($i * 20));
      $page->drawText($this->data[$i]['nombre'], 30, 720 - ($i * 20), 'UTF-8');
      $page->drawText($this->data[$i]['A'], 380, 720 - ($i * 20), 'UTF-8');
      $page->drawText($this->data[$i]['P'], 440, 720 - ($i * 20), 'UTF-8');
      $page->drawText($this->data[$i]['CA'], 500, 720 - ($i * 20), 'UTF-8');
      $page->drawText($this->data[$i]['C'], 560, 720 - ($i * 20), 'UTF-8');
    }

    $page->drawText($this->cabecera['A'], 380, 10);
    $page->drawText($this->cabecera['P'], 440, 10);
    $page->drawText($this->cabecera['CA'], 500, 10);
    $page->drawText($this->cabecera['C'], 560, 10);
    $pdf = new Zend_Pdf();
    $pdf->pages[] = $page;
    $this->fileName = 'Lista de Carpetas.pdf';
    return $pdf;
  }

  protected function _rellenarFormatoReporteTr()
  {
    $pdf = new Zend_Pdf();
    $first = true;
    $a = $this->position;
    $j  = 0;

    for ($i=0; $i < sizeof($this->data); $i++) {

      if ($first == true) {
        $page = clone $this->template->pages[0];
        $page->setFont($this->font, $this->font_size);
        $pdf->pages[] = $page;
        //cabecera
        $page->drawText(date("d-m-Y"), 730, 550, 'UTF-8');
        $page->drawText($this->cabecera['nombre_proyecto'], 85, 490, 'UTF-8');
        $page->drawText($this->cabecera['proyectoid'], 85, 455, 'UTF-8');
        $page->drawText($this->cabecera['gerente_proyecto'], 700, 490, 'UTF-8');
        $page->drawText($this->cabecera['control_documentario'], 700, 455, 'UTF-8');
      }

      //cuerpo
      $page->setFont($this->font, 6);

      $page->drawText((string)$i + 1, 5, $a);
      $page->drawText($this->data[$i]['edt'], 20, $a);
      $page->drawText($this->data[$i]['tipo_documento'], 43, $a, 'UTF-8');
      $page->drawText($this->data[$i]['disciplina'], 85, $a, 'UTF-8');
      $page->drawText($this->data[$i]['codigo_anddes'], 130, $a);
      $page->drawText($this->data[$i]['codigo_cliente'], 220, $a);
      $page->drawText($this->data[$i]['descripcion_entregable'], 315, $a, 'UTF-8');
      $page->drawText($this->data[$i]['revision_entregable'], 515, $a);
      $page->drawText($this->data[$i]['estado_revision'], 530, $a, 'UTF-8');
      $page->drawText($this->data[$i]['transmittal'], 555, $a);

      $linea = $a;
      $max = $linea;
      do {
        $texto = substr($this->data[$i]['emitido'], 0, 10);
        $page->drawText($texto, 600, $linea, 'UTF-8');
        $this->data[$i]['emitido'] = substr($this->data[$i]['emitido'], 10,
        strlen($this->data[$i]['emitido']));
        $linea = $linea - 10;
      } while (strlen($this->data[$i]['emitido']) > 0);

      if ($max > $linea) {
        $max = $linea;
      }

      $linea = $a;
      do {
        $texto = substr($this->data[$i]['fecha'], 0, 5);
        $page->drawText($texto, 633, $linea);
        $this->data[$i]['fecha'] = substr($this->data[$i]['fecha'], 5,
        strlen($this->data[$i]['fecha']));
        $linea = $linea - 10;
      } while (strlen($this->data[$i]['fecha']) > 0);

      if ($max > $linea) {
        $max = $linea;
      }

      $page->drawText($this->data[$i]['respuesta_transmittal'], 660, $a);
      $page->drawText($this->data[$i]['respuesta_emitido'], 703, $a, 'UTF-8');

      $linea = $a;
      do {
        $texto = substr($this->data[$i]['respuesta_fecha'], 0, 5);
        $page->drawText($texto, 737, $linea);
        $this->data[$i]['respuesta_fecha'] = substr($this->data[$i]['respuesta_fecha'], 5,
        strlen($this->data[$i]['respuesta_fecha']));
        $linea = $linea - 10;
      } while (strlen($this->data[$i]['respuesta_fecha']) > 0);

      if ($max > $linea) {
        $max = $linea;
      }

      $linea = $a;
      do {
        $texto = substr($this->data[$i]['estado'], 0, 10);
        $page->drawText($texto, 762, $linea, 'UTF-8');
        $this->data[$i]['estado'] = substr($this->data[$i]['estado'], 10,
        strlen($this->data[$i]['estado']));
        $linea = $linea - 10;
      } while (strlen($this->data[$i]['estado']) > 0);

      if ($max > $linea) {
        $max = $linea;
      }

      $page->drawText($this->data[$i]['comentario'], 793, $a, 'UTF-8');

      $first = false;

      $a = $max - 20;

      $j++;

      if ($a < 10) {
        $a = $this->position;
        $first = true;
        $j = 0;
      }
    }

    $this->fileName = 'Reporte Transmittal.pdf';
    return $pdf;
  }

  protected function _rellenarFormatoReporteCl()
  {
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
        $page->drawText(date("d-m-Y"), 730, 550);
        $page->drawText($this->cabecera['nombre_proyecto'], 85, 490, 'UTF-8');
        $page->drawText($this->cabecera['proyectoid'], 85, 455);
        $page->drawText($this->cabecera['gerente_proyecto'], 700, 490, 'UTF-8');
        $page->drawText($this->cabecera['control_documentario'], 700, 455);
      }

      //datos del cuerpo
      $page->drawText((string)$i + 1, 5, $a);
      $page->drawText($this->data[$i]['transmittal'], 20, $a);

      $linea = $a;
      $max = $linea;
      do {
        $texto = substr($this->data[$i]['codigo_anddes'], 0, 23);
        $page->drawText($texto, 137, $linea);
        $this->data[$i]['codigo_anddes'] = substr($this->data[$i]['codigo_anddes'],
        23, strlen($this->data[$i]['codigo_anddes']));
        $linea = $linea - 10;
      } while (strlen($this->data[$i]['codigo_anddes']) > 0);

      if ($max > $linea) {
        $max = $linea;
      }

      $linea = $a;
      $max = $linea;
      do {
        $texto = substr($this->data[$i]['codigo_cliente'], 0, 23);
        $page->drawText($texto, 250, $linea);
        $this->data[$i]['codigo_cliente'] = substr($this->data[$i]['codigo_cliente'],
        23, strlen($this->data[$i]['codigo_cliente']));
        $linea = $linea - 10;
      } while (strlen($this->data[$i]['codigo_cliente']) > 0);

      if ($max > $linea) {
        $max = $linea;
      }

      $page->drawText($this->data[$i]['descripcion'], 380, $a);
      $page->drawText($this->data[$i]['revision'], 610, $a);
      $page->drawText($this->data[$i]['emitido'], 635, $a, 'UTF-8');
      $page->drawText($this->data[$i]['fecha'], 775, $a);

      $first = false;
      $a = $max - 20;
      $j++;

      if ($a < 10) {
        $a = $this->position;
        $first = true;
        $j = 0;
      }

    }

    $this->fileName = 'Reporte Cliente.pdf';
    return $pdf;
  }
}
