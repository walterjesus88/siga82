<?php

class ControlDocumentario_PrintController extends Zend_Controller_Action {

    public function init()
    {
        $sesion  = Zend_Auth::getInstance();
        if(!$sesion->hasIdentity()) {
            $this->_helper->redirector('index',"index",'default');
        }
        $login = $sesion->getStorage()->read();
        $this->sesion = $login;
        $options = array(
            'layout' => 'layout',
        );
        Zend_Layout::startMvc($options);
    }

    //Funciones que envian las paginas para impresion

    public function imprimirproyectosAction()
    {
      $estado = $this->_getParam('estado');
      $proyecto = new Admin_Model_DbTable_Proyecto();
      $proyectos = $proyecto->_getAllExtendido($estado);

      $fileName = 'Lista-Proyectos-'.$estado.'-'.date("d-m-Y").'.pdf';

      $pdf = new Zend_Pdf();
      $page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4);
      $pdf->pages[] = $page;
      $page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 10);
      $margen_superior = 800;
      $margen_izquierdo = 20;
      $columnas[0] = $margen_izquierdo;
      $columnas[1] = $margen_izquierdo + 60;
      $columnas[2] = $margen_izquierdo + 150;
      $columnas[3] = $margen_izquierdo + 230;
      $columnas[4] = $margen_izquierdo + 320;
      $columnas[5] = $margen_izquierdo + 410;
      $columnas[6] = $margen_izquierdo + 500;
      $page->drawText('CODIGO', $columnas[0], $margen_superior);
      $page->drawText('CLIENTE', $columnas[1], $margen_superior);
      $page->drawText('PROYECTO', $columnas[2], $margen_superior);
      $page->drawText('GERENTE', $columnas[3], $margen_superior);
      $page->drawText('CP', $columnas[4], $margen_superior);
      $page->drawText('CD', $columnas[5], $margen_superior);
      $page->drawText('ESTADO', $columnas[6], $margen_superior);

      for ($i=0; $i < sizeof($proyectos); $i++) {
        $page->drawText($proyectos[$i]['proyectoid'], $columnas[0], $margen_superior - ($i + 1) * 10);
        $page->drawText($proyectos[$i]['nombre_comercial'], $columnas[1], $margen_superior - ($i + 1) * 10);
        $page->drawText($proyectos[$i]['gerente_proyecto'], $columnas[3], $margen_superior - ($i + 1) * 10);
        $page->drawText($proyectos[$i]['control_proyecto'], $columnas[4], $margen_superior - ($i + 1) * 10);
        $page->drawText($proyectos[$i]['control_documentario'], $columnas[5], $margen_superior - ($i + 1) * 10);
        $page->drawText($proyectos[$i]['estado'], $columnas[6], $margen_superior - ($i + 1) * 10);
      }

      // Load image
      //$image = Zend_Pdf_Image::imageWithPath($imagePath);
      // Draw image
      //$page->drawImage($image, $left, $bottom, $right, $top);

      $pdf->save($fileName, true);
      $respuesta['archivo'] = $fileName;
      $this->_helper->json->sendJson($respuesta);
    }

    public function imprimiredtAction()
    {
      $proyectoid = $this->_getParam('proyectoid');
      $edt = new Admin_Model_DbTable_ProyectoEdt();
      $lista = $edt->_getEdtxProyectoid($proyectoid);

      $fileName = 'EDT-'.$proyectoid.'-'.date("d-m-Y").'.pdf';
      $pdf = new Zend_Pdf;
      $page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4);
      $pdf->pages[] = $page;
      $page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 10);
      $margen_superior = 800;
      $margen_izquierdo = 20;
      $columnas[0] = $margen_izquierdo;
      $columnas[1] = $margen_izquierdo + 60;
      $page->drawText('N', $columnas[0], $margen_superior);
      $page->drawText('EDT', $columnas[1], $margen_superior);

      for ($i=0; $i < sizeof($lista); $i++) {
        $page->drawText($lista[$i]['codigo'], $columnas[0], $margen_superior - ($i + 1) * 10);
        $page->drawText($lista[$i]['nombre'], $columnas[1], $margen_superior - ($i + 1) * 10);
      }

      $pdf->save($fileName, true);

      $respuesta['archivo'] = $fileName;
      $this->_helper->json->sendJson($respuesta);
    }

    public function imprimirreportetransmittalAction()
    {
      $proyectoid = $this->_getParam('proyectoid');
      $estado = $this->_getParam('estado');
      $clase = $this->_getParam('clase');
      $entregable = new Admin_Model_DbTable_Listaentregabledetalle();
      $lista = $entregable->_getEntregablexProyecto($proyectoid, $estado, $clase);

      $fileName = 'LE-'.$proyectoid.'-'.$estado.'-'.date("d-m-Y").'.pdf';
      $pdf = new Zend_Pdf;
      $page = $pdf->newPage(Zend_Pdf_Page::SIZE_A4);
      $pdf->pages[] = $page;
      $page->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 10);
      $margen_superior = 800;
      $margen_izquierdo = 20;
      $page->drawText('Proximamente...', $margen_izquierdo, $margen_superior);
      $pdf->save($fileName, true);

      $respuesta['archivo'] = $fileName;
      $this->_helper->json->sendJson($respuesta);
    }
}
