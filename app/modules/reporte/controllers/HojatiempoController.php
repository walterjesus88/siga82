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

    public function filterAction()
    {   $data = array();
        $params = $this->getRequest()->getParams();
        $this->_helper->layout()->disableLayout();
        $tb_area = new Admin_Model_DbTable_Area();
        $areas = $tb_area->_getAreaAll();
        foreach ($areas as $key => $area) {
            $data[$key]["label"] = $area['nombre'];
            $data[$key]["value"] = $area["areaid"];
        }
        $this->_helper->json->sendJson($data);
    }

}