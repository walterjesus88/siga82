<?php
class Reporte_HojatiempoController extends Zend_Controller_Action {

    public function init() {
        $sesion  = Zend_Auth::getInstance();
        if(!$sesion->hasIdentity()) {
            $this->_helper->redirector('index',"index",'default');
        }
        $login = $sesion->getStorage()->read();
        $this->sesion = $login;
        $options = array(
            'layout' => 'inicio',
        );
        Zend_Layout::startMvc($options);

        $excelConfig =
            array(
                'excel' => array(
                    'suffix'  => 'excel',
                    'headers' => array(
                        'Content-type' => 'application/vnd.ms-excel')),
            );
        $contextSwitch = $this->_helper->contextSwitch();
        $contextSwitch->setContexts($excelConfig);
        $contextSwitch->addActionContext('index', 'excel');
        $contextSwitch->initContext();
    }

    public function indexAction()
    {
        $params = $this->getRequest()->getParams();
        $this->view->params = $params;
    }

    public function jsondataAction()
    {
        $params = $this->getRequest()->getParams();
        $this->_helper->layout()->disableLayout();
        $sheet_times = new Reporte_DataTable_HojaTiempo();
        $data = $sheet_times->as_json($params);

        $this->_helper->json->sendJson($data);
    }

    public function downloadpdfAction(){
        // $pdf = Zend_Pdf::load('formatos/cliente.pdf');
        $document = "formatos/cliente.pdf";
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();
        $this->getResponse()
            ->setHeader('Content-Disposition', 'inline; filename=foo.pdf')
            ->setHeader('Content-type', 'application/pdf');
        $pdf = Zend_Pdf::load($document);
        echo $pdf->render();
        // $this->_helper->layout->disableLayout();
        // $this->_helper->viewRenderer->setNoRender();
        // $page = $pdf->pages[0];
        // $page->drawText(date("d-m-Y"), 500, 800);





        // $this->getResponse()->setHeader('Content-type', 'application/x-pdf', true);
        // $this->getResponse()->setHeader('Content-disposition', 'inline; filename=Reporte.pdf', true);
        // $this->getResponse()->setBody($page->render());

        // header("Content-Type: application/x-pdf");
        // header("Cache-Control: no-cache, must-revalidate");
        // $sheet_times = new Reporte_DataTable_HojaTiempo();
        // $tb_area = new Admin_Model_DbTable_Area();
        // $tb_sheet_times = new Admin_Model_DbTable_Tareopersona();
        // $params = $this->getRequest()->getParams();
        // $data = $sheet_times->sheet_times($params);

        // $sheet_times = new Reporte_DataTable_HojaTiempo();
        // $tb_area = new Admin_Model_DbTable_Area();
        // $tb_sheet_times = new Admin_Model_DbTable_Tareopersona();
        // $params = $this->getRequest()->getParams();
        // $data = $sheet_times->sheet_times($params);
        // $cabecera['nombre_proyecto'] = "HOLA";
        // $cabecera['proyectoid'] = "GERSNO";
        // $formato = new Admin_Model_DbTable_Formato('report_sheet_times', $cabecera, $data);
        // $respuesta = $formato->fill_data_sheet_time();
        // $respuesta->save("hoadas.pdf", true);
    }

    public function filterAction()
    {   $data = array('areas','users');
        $params = $this->getRequest()->getParams();
        $this->_helper->layout()->disableLayout();
        $tb_area = new Admin_Model_DbTable_Area();
        $areas_t = $tb_area->_getAreaAll();
        $areas = array();
        foreach ($areas_t as $key => $area) {
            $areas[$key]["label"] = $area['nombre'];
            $areas[$key]["value"] = $area["areaid"];
        }
        $tb_user = new Admin_Model_DbTable_Usuario();
        $tb_person = new Admin_Model_DbTable_Persona();
        $users_t = $tb_user->_getUsuarioAll();
        foreach ($users_t as $key => $user) {
            $users[$key]["label"] = $user['uid'];
            $users[$key]["value"] = $user['uid'];
        }
        $data['areas'] = $areas;
        $data['users'] = $users;
        $this->_helper->json->sendJson($data);
    }

}