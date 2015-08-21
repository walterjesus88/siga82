<?php
class Admin_Model_DbTable_Formato extends Zend_Db_Table_Abstract
{
  protected $formatos = [];
  protected $modelo = '';
  protected $cabecera = [];
  protected $data = [];
  protected $fileName = '';

  public function _setFormato($formato)
  {
    $this->formatos['anddes'] = 'formatos/tr-anddes.pdf';
    $this->formatos['barrick'] = 'formatos/tr-barrick.pdf';
    $this->formatos['cerro_verde'] = 'formatos/tr-cerro-verde.pdf';
    $this->modelo = $this->formatos[$formato];
  }

  public function _setCabecera($data)
  {
    $this->cabecera = $data;
  }

  public function _setData($data)
  {
    $this->data = $data;
  }

  private function _rellenarFormato($pdf)
  {
    $page = $pdf->pages[0];
    $font = Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA);
    $page->setFont($font, 9);
    if ($this->modelo == $this->formatos['anddes']) {

      //datos de cabecera
      $page->drawText($this->cabecera['clienteid'], 70, 740);
      $page->drawText('Area del Circulo', 70, 725);
      $page->drawText($this->cabecera['atencion'], 70, 710);
      $page->drawText($this->cabecera['codificacion'].'-'.$this->cabecera['correlativo'], 360, 740);
      $page->drawText($this->cabecera['proyectoid'], 360, 725);
      $page->drawText('Referencia de que', 360, 710);

      $page->drawText('X', 96, 693);
      $page->drawText('X', 205, 693);

      $page->drawText('123456', 360, 695);
      $page->drawText(date("d-m-Y"), 510, 695);

      //datos de entregables
    } elseif ($this->modelo == $this->formatos['barrick']) {

    } elseif ($this->modelo == $this->formatos['cerro_verde']) {

    }
    return $pdf;
  }

  public function _print()
  {
    $this->fileName = 'TR-'.date("d-m-Y").'.pdf';
    $pdf = Zend_Pdf::load($this->modelo);
    $pdf_rellenado = $this->_rellenarFormato($pdf);
    $pdf_rellenado->save($this->fileName);
    $ruta['archivo'] = $this->fileName;
    return $ruta;
  }
}
