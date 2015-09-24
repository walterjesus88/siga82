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
        $path_template = "formatos/hoja_tiempo.pdf";
        $this->_helper->viewRenderer->setNoRender();
        $this->_helper->layout()->disableLayout();
        $template = Zend_Pdf::load($path_template);
        $pdf = new Zend_Pdf();
        $sheet_times = new Reporte_DataTable_HojaTiempo();
        $tb_area = new Admin_Model_DbTable_Area();
        $tb_sheet_times = new Admin_Model_DbTable_Tareopersona();
        $params = $this->getRequest()->getParams();
        $position_y = 720;
        $count_all = $tb_sheet_times ->_count_all($sheet_times->sort_column($params), $params);
        $params["iDisplayLength"] = 46;
        $num_page = round(count($count_all) / 46);
        for ($i=0; $i <= $num_page ; $i++) {
            $params["iDisplayStart"] = $i;
            $pdf->pages[$i] = clone $template->pages[0];
            $pdf->pages[$i]->setFont(Zend_Pdf_Font::fontWithName(Zend_Pdf_Font::FONT_HELVETICA), 10);
            $pdf->pages[$i]->drawText(date("d-m-Y"), 450, 803);
            $data_sheet_times = $sheet_times->sheet_times($params);
            foreach ($data_sheet_times as $key => $sheet_time) {
                $row_index = $key + 1;
                $name_area = $tb_area->_getName($sheet_time["areaid"]);
                $pdf->pages[$i]->drawText( (string) $row_index , 10, $position_y);
                $pdf->pages[$i]->drawText( (string) $name_area['nombre'] , 40, $position_y, 'UTF-8');
                $pdf->pages[$i]->drawText( (string) $sheet_time['uid'] , 135, $position_y, 'UTF-8');
                $pdf->pages[$i]->drawText( (string) $sheet_time['semanaid'] , 330, $position_y, 'UTF-8');
                $pdf->pages[$i]->drawText( (string) $tb_sheet_times->convert_number_of_week_to_date($sheet_time['semanaid']) , 385, $position_y, 'UTF-8' );
                $pdf->pages[$i]->drawText( (string) $tb_sheet_times->_getNameStatus($sheet_time["estado"]), 520, $position_y, 'UTF-8');
                $position_y = $position_y - 15;
            }
        }
        $this->getResponse()->setHeader('Content-type', 'application/x-pdf', true);
        $this->getResponse()->setHeader('Content-disposition', 'inline; filename=Reporte.pdf', true);
        $this->getResponse()->setBody($pdf->render());
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