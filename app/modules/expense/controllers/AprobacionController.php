<?php

class Expense_AprobacionController extends Zend_Controller_Action {

    public function init() {
    	$sesion  = Zend_Auth::getInstance();
        if(!$sesion->hasIdentity() ){
            $this->_helper->redirector('index',"index",'default');
        }
        $login = $sesion->getStorage()->read();
        $this->sesion = $login; 
        $options = array(
            'layout' => 'layout',
        );
        Zend_Layout::startMvc($options);
    
    }
    
    public function indexAction() {
        try {
            $uid = $this->sesion->uid;
            $dni = $this->sesion->dni;
            $equipo = new Admin_Model_DbTable_Equipo();
            $data_equipo = $equipo->_getProyectosXuidXEstado($uid,'A');
            if ($data_equipo[0]['nivel'] == '0') {
                $this->view->equipo = $data_equipo;
                $codigo_prop_proy = $data_equipo[0]['codigo_prop_proy'];
                $proyectoid = $data_equipo[0]['proyectoid'];
            }

            $rendicion = new Admin_Model_DbTable_Gastorendicion();
            $data_rendicion = $rendicion->_getrendicionXestadoXproyecto('E', $codigo_prop_proy, $proyectoid);

            $persona = new Admin_Model_DbTable_Persona();
            for ($i=0; $i < count($data_rendicion); $i++) { 
                $where['numero'] = $data_rendicion[$i]['numero'];
                $data_one = $rendicion->_getOne($where);
                $data_rendicion[$i] = $data_one;

                $data_persona = $persona->_getPersona($data_rendicion[$i]['dni']);
                $data_rendicion[$i]['nombre_persona'] = $data_persona['ape_paterno']. ' ' .$data_persona['ape_materno']. ', ' .$data_persona['nombres']. ' ' .$data_persona['segundo_nombre'];
            }
            $this->view->data_enviados = $data_rendicion;


            $data_rendicion = $rendicion->_getrendicionXestadoXproyecto('R', $codigo_prop_proy, $proyectoid);
            $persona = new Admin_Model_DbTable_Persona();
            for ($i=0; $i < count($data_rendicion); $i++) { 
                $where['numero'] = $data_rendicion[$i]['numero'];
                $data_one = $rendicion->_getOne($where);
                $data_rendicion[$i] = $data_one;

                $data_persona = $persona->_getPersona($data_rendicion[$i]['dni']);
                $data_rendicion[$i]['nombre_persona'] = $data_persona['ape_paterno']. ' ' .$data_persona['ape_materno']. ', ' .$data_persona['nombres']. ' ' .$data_persona['segundo_nombre'];
            }
            $this->view->data_rechazados = $data_rendicion;

            $data_rendicion = $rendicion->_getrendicionXestadoXproyecto('A', $codigo_prop_proy, $proyectoid);
            $persona = new Admin_Model_DbTable_Persona();
            for ($i=0; $i < count($data_rendicion); $i++) { 
                $where['numero'] = $data_rendicion[$i]['numero'];
                $data_one = $rendicion->_getOne($where);
                $data_rendicion[$i] = $data_one;

                $data_persona = $persona->_getPersona($data_rendicion[$i]['dni']);
                $data_rendicion[$i]['nombre_persona'] = $data_persona['ape_paterno']. ' ' .$data_persona['ape_materno']. ', ' .$data_persona['nombres']. ' ' .$data_persona['segundo_nombre'];
            }
            $this->view->data_aprobados = $data_rendicion;
        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        }
    }

    public function estadoAction(){
        try {
            $this->_helper->layout()->disableLayout();
            $numero = $this->_getParam('numero');
            $uid = $this->_getParam('uid');
            $dni = $this->_getParam('dni');
            $state = $this->_getParam('estate');
            $rendicion = new Admin_Model_DbTable_Gastorendicion();
            $pk = array();
            $pk = array(
                    'numero'=>$numero,
                    'uid'=>$uid,
                    'dni'=>$dni);
            $data = array();
            $data['estado'] = $state;
            $rendicion->_update($data,$pk);

            $wheretmp = array();
            $wheretmp['numero_rendicion'] = $numero;
            $wheretmp['dni'] = $dni;
            $wheretmp['uid'] = $uid;
            $gastos = new Admin_Model_DbTable_Gastopersona();
            $data_gastos = $gastos->_getFilter($wheretmp,$attrib=null,$orders=null);
            foreach ($data_gastos as $datatmp) {
                $pk = array();
                $pk = array(
                        'codigo_prop_proy'=>$datatmp['codigo_prop_proy'],
                        'proyectoid'=>$datatmp['proyectoid'],
                        'gasto_persona_id'=>$datatmp['gasto_persona_id']);
                $data = array();
                $data['estado_rendicion'] = $state;
                $gastos->_update($data,$pk);
            }
        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        }
    }
}