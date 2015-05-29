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
            $data_proyectos = $equipo->_getProyectosXuidXEstadoXnivelXcategoria($uid,'GER-PROY','0','A');
            for ($i=0; $i < count($data_proyectos); $i++) { 
                $rendicion = new Admin_Model_DbTable_Gastorendicion();
                $data_rendicion = $rendicion->_getrendicionXestadoXproyecto('E', $data_proyectos[$i]['codigo_prop_proy'], $data_proyectos[$i]['proyectoid']);
                
                $persona = new Admin_Model_DbTable_Persona();
                for ($j=0; $j < count($data_rendicion); $j++) { 
                    $where['numero'] = $data_rendicion[$j]['numero'];
                    $data_one = $rendicion->_getOne($where);
                    $data_rendicion[$j] = $data_one;

                    $data_persona = $persona->_getPersona($data_rendicion[$j]['dni']);
                    $data_rendicion[$j]['nombre_persona'] = $data_persona['ape_paterno']. ' ' .$data_persona['ape_materno']. ', ' .$data_persona['nombres']. ' ' .$data_persona['segundo_nombre'];
                }
                $data_proyectos[$i]['data_enviados'] = $data_rendicion;

                $data_rendicion = $rendicion->_getrendicionXestadoXproyecto('R', $data_proyectos[$i]['codigo_prop_proy'], $data_proyectos[$i]['proyectoid']);
                for ($k=0; $k < count($data_rendicion); $k++) { 
                    $where['numero'] = $data_rendicion[$k]['numero'];
                    $data_one = $rendicion->_getOne($where);
                    $data_rendicion[$k] = $data_one;

                    $data_persona = $persona->_getPersona($data_rendicion[$k]['dni']);
                    $data_rendicion[$k]['nombre_persona'] = $data_persona['ape_paterno']. ' ' .$data_persona['ape_materno']. ', ' .$data_persona['nombres']. ' ' .$data_persona['segundo_nombre'];
                }
                $data_proyectos[$i]['data_rechazados'] = $data_rendicion;

                $data_rendicion = $rendicion->_getrendicionXestadoXproyecto('A', $data_proyectos[$i]['codigo_prop_proy'], $data_proyectos[$i]['proyectoid']);
                for ($l=0; $l < count($data_rendicion); $l++) { 
                    $where['numero'] = $data_rendicion[$l]['numero'];
                    $data_one = $rendicion->_getOne($where);
                    $data_rendicion[$l] = $data_one;

                    $data_persona = $persona->_getPersona($data_rendicion[$l]['dni']);
                    $data_rendicion[$l]['nombre_persona'] = $data_persona['ape_paterno']. ' ' .$data_persona['ape_materno']. ', ' .$data_persona['nombres']. ' ' .$data_persona['segundo_nombre'];
                }
                $data_proyectos[$i]['data_aprobados'] = $data_rendicion;
            }
            $this->view->data_equipo = $data_proyectos;

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
            $comentario = $this->_getParam('comentario');
            $rendicion = new Admin_Model_DbTable_Gastorendicion();
            $pk = array();
            $pk = array(
                    'numero'=>$numero,
                    'uid'=>$uid,
                    'dni'=>$dni);
            $data = array();
            $data['estado'] = $state;
            $data['comentario'] = $comentario;
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
                $data['fecha_rendicion'] = date("Y-m-d");
                $gastos->_update($data,$pk);
            }
        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        }
    }

    public function detallesAction(){
        try {
            $this->_helper->layout()->disableLayout();
            $numero = $this->_getParam('numero');
            $uid = $this->_getParam('uid');
            $dni = $this->_getParam('dni');
            $proyectoid = $this->_getParam('proyectoid');
            $codigo_prop_proy = $this->_getParam('codigo_prop_proy');
            $wheretmp = array();
            $wheretmp['numero_rendicion'] = $numero;
            $wheretmp['dni'] = $dni;
            $wheretmp['uid'] = $uid;
            $wheretmp['codigo_prop_proy'] = $codigo_prop_proy;
            $wheretmp['proyectoid'] = $proyectoid;
            $gastos = new Admin_Model_DbTable_Gastopersona();
            $data_gastos = $gastos->_getFilter($wheretmp,$attrib=null,$orders=null);

            $proyecto = new Admin_Model_DbTable_Proyecto();
            $actividad = new Admin_Model_DbTable_Actividad();
            for ($i=0; $i < count($data_gastos); $i++) { 
                if ($data_gastos[$i]['actividadid']) {
                    $data_actividad = $actividad->_getActividadesxActividadid($proyectoid,$codigo_prop_proy,$data_gastos[$i]['actividadid']);
                    $data_gastos[$i]['nombre_final'] = $data_actividad['0']['nombre'];
                } elseif ($data_gastos[$i]['proyectoid']) {
                    $pk = array();
                    $pk['proyectoid'] = $proyectoid;
                    $pk['codigo_prop_proy'] = $codigo_prop_proy;
                    $data_proyecto = $proyecto->_getOne($pk);
                    $data_gastos[$i]['nombre_final'] = $data_proyecto['nombre_proyecto'];
                }
            }

            $this->view->gasto = $data_gastos;

            $gastos = new Admin_Model_DbTable_Listagasto();
            $data_list_gastos = $gastos->_getGastosAll();
            $this->view->list_gastos = $data_list_gastos;
        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        }
    }
}