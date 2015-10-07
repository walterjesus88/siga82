<?php
class Reporte_PlanningController extends Zend_Controller_Action {

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

    public function downloadpdfAction(){
        $params = $this->getRequest()->getParams();
        $datatable_planning = new Reporte_DataTable_Planning();
        $tb_planning = new Admin_Model_DbTable_Planificacion();
        $tb_project = new Admin_Model_DbTable_Proyecto();
        $data = $datatable_planning->plamings($params);
        $content = '';
        foreach ($data as $key => $plaming) {
            $content = $content . 
                '<tr>
                    <td>'. $plaming["nombre"]. '</td>
                    <td>'. $plaming["uid"]. '</td>
                    <td>'. $plaming["semanaid"]. '</td>
                    <td>'. $tb_project->_getNameManager($plaming["proyectoid"])["gerente_proyecto"]. '</td>
                    <td>'. $tb_planning->_getStatusName($plaming["estado"]). '</td>
                    <td>'. $plaming['billable']. '</td>
                    <td>'. $plaming['nonbillable']. '</td>
                </tr>';
        }
        $this->view->content_table = $content;
    }

    public function jsondataAction()
    {
        $params = $this->getRequest()->getParams();
        $this->_helper->layout()->disableLayout();
        $sheet_times = new Reporte_DataTable_Planning();
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