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
        $sheet_times = new Reporte_DataTable_HojaTiempo();
        $tb_area = new Admin_Model_DbTable_Area();
        $tb_sheet_times = new Admin_Model_DbTable_Tareopersona();
        $params = $this->getRequest()->getParams();
        $data = $sheet_times->sheet_times($params);
        $content = '';
        foreach ($data as $key => $sheet_time) {
            $name_area = $tb_area->_getName($sheet_time['areaid']);
            $content = $content . 
                '<tr>
                    <td>'. $name_area["nombre"]. '</td>
                    <td>'. $sheet_time["uid"]. '</td>
                    <td>'. $sheet_time["semanaid"]. '</td>
                    <td>'. $tb_sheet_times->_getNameStatus($sheet_time["estado"]). '</td>
                </tr>';
        }
        $this->view->content_table = $content;
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
            // $data_person = $tb_person->fetchRow($tb_person->select()->where('dni = ?', $user['dni']))->toArray();
            $users[$key]["label"] = $user['uid'];
            $users[$key]["value"] = $user['uid'];
        }
        $data['areas'] = $areas;
        $data['users'] = $users;
        $this->_helper->json->sendJson($data);
    }

}