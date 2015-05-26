<?php

class Expense_IndexController extends Zend_Controller_Action {

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

    }
    
    public function registroAction(){
    try {
        $uid = $this->sesion->uid;
        $dni = $this->sesion->dni;
        $equipo = new Admin_Model_DbTable_Equipo();
        $data_equipo = $equipo->_getProyectosXuidXEstado($uid,'A');
        $data_clientes = $equipo ->_getClienteXuidXEstado($uid,'A');
        $this->view->clientes = $data_clientes;
        $this->view->equipo = $data_equipo;
    } catch (Exception $e) {
        print "Error: ".$e->getMessage();
    }
    }

    public function proyectosAction(){
        try {
            $this->_helper->layout()->disableLayout();
            $uid = $this->sesion->uid;
            $dni = $this->sesion->dni;
            $clienteid = $this->_getParam('clienteid');
            $unidadid = $this->_getParam('unidad_mineraid');
            $equipo = new Admin_Model_DbTable_Equipo();
            $data_equipo = $equipo->_getProyectosxUidXEstadoxCliente($uid,'A',$clienteid,$unidadid);
            $this->view->equipo = $data_equipo;
            $this->view->clienteid = $clienteid;
            $this->view->unidad_mineraid = $unidadid;
        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        }
    }

    public function disciplinaAction(){
    try {
        $this->_helper->layout()->disableLayout();
        $uid = $this->sesion->uid;
        $dni = $this->sesion->dni;
        $proyectoid = $this->_getParam('proyectoid');
        $codigo_prop_proy = $this->_getParam('codigo_prop_proy');
        $categoriaid = $this->_getParam('categoriaid');
        $cargo = $this->_getParam('cargo');
        $clienteid = $this->_getParam('clienteid');
        $unidadid = $this->_getParam('unidad_mineraid');
        $actividad = new Admin_Model_DbTable_Actividad();
        $data_actividad = $actividad->_getActividadesPadresXproyectoXcodigo($proyectoid, $codigo_prop_proy);
        $actividades_padre=$actividad->_getActividadesPadresXProyectoXCategoria($proyectoid,$categoriaid,$codigo_prop_proy);
        $i=0;
        $array = [];
        foreach ($actividades_padre as $act_padre) {
            $dato_padre=$actividad->_getActividadesxActividadid($proyectoid,$codigo_prop_proy,$act_padre['padre']);
            if ($dato_padre[0]['isgasto'] == 'S') {
                $array[$i]=$dato_padre[0];
                $i++;
            }
        }
        if ($i != 0) {
            $this->view->actividades = $array;
        } else {
            $equipo = new Admin_Model_DbTable_Equipo();
            $data_equipo = $equipo->_getProyectosxUidXEstadoxCliente($uid,'A',$clienteid,$unidadid);
            $this->view->equipo = $data_equipo;
        }
        $this->view->proyectoid = $proyectoid;
        $this->view->codigo_prop_proy = $codigo_prop_proy;
        $this->view->categoriaid = $categoriaid;
        $this->view->cargo = $cargo;
        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        } 
    }

    public function actividadespadresAction(){
    try {
        $this->_helper->layout()->disableLayout();
        $uid = $this->sesion->uid;
        $dni = $this->sesion->dni;
        $proyectoid = $this->_getParam('proyectoid');
        $codigo_prop_proy = $this->_getParam('codigo_prop_proy');
        $categoriaid = $this->_getParam('categoriaid');
        $actividadid = $this->_getParam('actividadid');
        $revision = $this->_getParam('revision');
        $propuestaid = $this->_getParam('propuestaid');
        $cargo = $this->_getParam('cargo');
        $tareo = new Admin_Model_DbTable_Actividad();
        $data_tareo_hijos = $tareo->_getActividadesHijas($proyectoid,$codigo_prop_proy,$propuestaid,$revision,$actividadid);
        $codigo_act_padres_hijas = $tareo->_getActividadesHijasxActividadesPadresXCategoria($proyectoid,$codigo_prop_proy,$propuestaid,$revision,$actividadid,$categoriaid);
        $j=0;
        $array = [];
        foreach ($codigo_act_padres_hijas as $act_padre) {
            $actividadespadres = explode(".",$act_padre['actividad_padre']);
            if (count($actividadespadres)=='1'){
                $dato_tarea=$tareo->_getTareasxActividadPadrexCategoria($proyectoid,$codigo_prop_proy,$propuestaid,$revision,$act_padre['actividad_padre'],$categoriaid);
            }
            if (count($actividadespadres)=='2'){
                $dato_padre=$tareo->_getActividadesxActividadid($proyectoid,$codigo_prop_proy,$act_padre['actividad_padre']);
                if ($dato_padre[0]['isgasto'] == 'S') {
                    $array[$j]=$dato_padre[0];
                    $j++;
                }
            }
        }
        $this->view->datos_tarea = $dato_tarea;
        $this->view->datos_disciplina = $array;
        $this->view->actividades_hijos = $data_tareo_hijos;
        $this->view->proyectoid = $proyectoid;
        $this->view->codigo_prop_proy = $codigo_prop_proy;
        $this->view->categoriaid = $categoriaid;
        $this->view->actividadid = $actividadid;
        $this->view->cargo = $cargo;
        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        }
    }

    public function tareasAction(){
    try {
        $this->_helper->layout()->disableLayout();
        $uid = $this->sesion->uid;
        $dni = $this->sesion->dni;
        $proyectoid = $this->_getParam('proyectoid');
        $codigo_prop_proy = $this->_getParam('codigo_prop_proy');
        $categoriaid = $this->_getParam('categoriaid');
        $actividadid = $this->_getParam('actividadid');
        $revision = $this->_getParam('revision');
        $propuestaid = $this->_getParam('propuestaid');
        $cargo = $this->_getParam('cargo');
        $tareo = new Admin_Model_DbTable_Actividad();
        $data_tareas = $tareo->_getActividadesHijas($proyectoid,$codigo_prop_proy,$propuestaid,$revision,$actividadid);
        $dato_tarea=$tareo->_getTareasxActividadPadrexCategoriaXisgasto($proyectoid,$codigo_prop_proy,$propuestaid,$revision,$actividadid,$categoriaid);
        $this->view->tareas = $dato_tarea;
        $this->view->proyectoid = $proyectoid;
        $this->view->codigo_prop_proy = $codigo_prop_proy;
        $this->view->categoriaid = $categoriaid;
        $this->view->actividadid = $actividadid;
        $this->view->cargo = $cargo;
        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        }
    }

    public function ingresoAction(){
    try {
        $this->_helper->layout()->disableLayout();
        $uid = $this->sesion->uid;
        $dni = $this->sesion->dni;
        $gasto = new Admin_Model_DbTable_Gastopersona();
        $data_gasto = $gasto->_getgastoProyectosXfecha(date("Y-m-d"), $uid, $dni);
        for ($i=0; $i < count($data_gasto); $i++) { 
            $order = array('gasto_persona_id ASC');
            $wheretmp ['uid'] = $uid;
            $wheretmp ['dni'] = $dni;
            $wheretmp ['fecha_gasto'] = date("Y-m-d");
            $wheretmp ['proyectoid'] = $data_gasto[$i]['proyectoid'];
            $data_gasto_final = $gasto->_getFilter($wheretmp,$attrib=null,$order);

            $pk ['proyectoid'] = $data_gasto[$i]['proyectoid'];
            $pk ['codigo_prop_proy'] = $data_gasto_final[0]['codigo_prop_proy'];
            $proyecto = new Admin_Model_DbTable_Proyecto();
            $data_proyecto = $proyecto->_getOne($pk);
            for ($n=0; $n < count($data_gasto_final); $n++) { 
                $data_gasto_final[$n]['nombre_proyecto'] = $data_proyecto['nombre_proyecto'];
                $data_gasto_final[$n]['tipo_proyecto'] = $data_proyecto['tipo_proyecto'];
            }
            $data_gasto[$i] = $data_gasto_final[0];

            /*$temp_gasto = $gasto->_getgastoProyectoXfechaXactividad($wheretmp);
            if ($temp_gasto) {
                $actividad = new Admin_Model_DbTable_Actividad();
                for ($j=0; $j < count($temp_gasto); $j++) { 
                    $data_actividad = $actividad->_getActividadesxActividadid($data_gasto[$i]['proyectoid'],$data_gasto_final[0]['codigo_prop_proy'],$temp_gasto[$j]['actividadid']);
                    $temp_gasto[$j]['nombre'] = $data_actividad[0]['nombre'];
                }
            }
            $data_gasto[$i]['actividades'] = ($temp_gasto)? $temp_gasto : $data_gasto_final;*/
            $data_gasto[$i]['actividades'] = $data_gasto_final;
        }
        $this->view->gasto = $data_gasto;
        
        $where_tmp = array();
        $where_tmp['uid'] = $uid;
        $where_tmp['dni'] = $dni;
        $where_tmp['fecha'] = date("Y-m-d");
        $rendicion = new Admin_Model_DbTable_Gastorendicion();
        $data_rendicion = $rendicion->_getOneXfecha($where_tmp);
        $this->view->data_rendicion = $data_rendicion;

        $gastos = new Admin_Model_DbTable_Listagasto();
        $data_list_gastos = $gastos->_getGastosHijos();
        $this->view->list_gastos = $data_list_gastos;

        $data_all_gastos = $gastos->_getGastosAll();
        $this->view->all_gastos = $data_all_gastos;
        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        }
    }

    public function gastohijoAction(){
        try {
            $this->_helper->layout()->disableLayout();
            $tmp = $this->_getParam('gastoid');
            list($gastoid, $tipo_gasto) = split('[-]', $tmp);
            $gastos = new Admin_Model_DbTable_Listagasto();
            $data_hijos = $gastos->_getGastosXgastopadre($gastoid, $tipo_gasto);
            $this->view->hijos = $data_hijos;
        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        }
    }

    public function guardargastopersonaAction(){
        try {
            $this->_helper->layout()->disableLayout();
            $uid = $this->sesion->uid;
            $dni = $this->sesion->dni;
            $proyectoid = $this->_getParam('proyectoid');
            $codigo_prop_proy = $this->_getParam('codigo-prop-proy');
            $categoriaid = $this->_getParam('categoriaid');
            $areaid = $this->_getParam('areaid');
            $cargo = $this->_getParam('cargo');
            $revision = $this->_getParam('revision');
            $actividadid = $this->_getParam('actividadid');
            $data ['proyectoid'] = $proyectoid;
            $data ['codigo_prop_proy'] = $codigo_prop_proy;
            $data ['categoriaid'] = $categoriaid;
            $data ['areaid'] = $areaid;
            $data ['uid'] = $uid;
            $data ['dni'] = $dni;
            $data ['cargo'] = $cargo;
            $data ['uid_ingreso'] = $uid;
            $data ['asignado'] = $uid;
            $data ['fecha_gasto'] = date("Y-m-d");
            $data ['gastoid'] = 1;
            $data ['revision'] = $revision;
            $data ['estado_rendicion'] = 'B';
            if ($actividadid) {
                $data ['actividadid'] = $actividadid;
            }
            $where = array();
            $where['fecha'] = date("Y-m-d");
            $where['uid'] = $uid;
            $where['dni'] = $dni;
            $rendicion = new Admin_Model_DbTable_Gastorendicion();
            $data_exist = $rendicion->_getOneXfecha($where);
            if (!$data_exist) {
                $where['estado'] = 'B';
                $rendicion->_save($where);
            }
            $data_guard = $rendicion->_getOneXfecha($where);

            $data['numero_rendicion'] = $data_guard['numero'];
            $gasto = new Admin_Model_DbTable_Gastopersona();
            $gasto->_save($data);

        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        }
    }

    public function duplicargastopersonaAction(){
        try {
            $this->_helper->layout()->disableLayout();
            $uid = $this->sesion->uid;
            $dni = $this->sesion->dni;
            $proyectoid = $this->_getParam('proyectoid');
            $codigo_prop_proy = $this->_getParam('codigo_prop_proy');
            $gasto_persona_id = $this->_getParam('gasto_persona_id');
            $where = array();
            $where['proyectoid'] = $proyectoid;
            $where['codigo_prop_proy'] = $codigo_prop_proy;
            $where['gasto_persona_id'] = $gasto_persona_id;
            $where['uid'] = $uid;
            $where['dni'] = $dni;

            $gasto = new Admin_Model_DbTable_Gastopersona();
            $data_dupli = $gasto->_getFilter($where,$attrib=null,$orders=null);
            
            $data = array();
            $data = $data_dupli[0];
            unset($data['gasto_persona_id']);
            $gasto->_save($data);

        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        }
    }

    public function updategastorendicionAction(){
        try {
            $this->_helper->layout()->disableLayout();
            $uid = $this->sesion->uid;
            $dni = $this->sesion->dni;
            $numero = $this->_getParam('numero');
            $nombre = $this->_getParam('nombre');
            $monto_total = $this->_getParam('monto_total');
            $monto_reembolso = $this->_getParam('monto_reembolso');
            $monto_cliente = $this->_getParam('monto_cliente');
            $pk = array();
            $pk = array(
                    'numero'=>$numero,
                    'uid'=>$uid,
                    'dni'=>$dni);
            $data = array();
            $data['numero'] = $numero;
            $data['nombre'] = $nombre;
            $data['monto_total'] = $monto_total;
            $data['monto_reembolso'] = $monto_reembolso;
            $data['monto_cliente'] = $monto_cliente;
            $rendicion = new Admin_Model_DbTable_Gastorendicion();
            $rendicion->_update($data,$pk);
        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        }
    }

    public function updategastopersonaAction(){
        try {
            $this->_helper->layout()->disableLayout();
            $gasto_persona_id = $this->_getParam('gasto_persona_id');
            $proyectoid = $this->_getParam('proyectoid');
            $codigo_prop_proy = $this->_getParam('codigo_prop_proy');
            $description = $this->_getParam('description');
            $gasto_padre = $this->_getParam('gasto_padre');
            $gasto_hijo = $this->_getParam('gasto_hijo');
            $lab_cantidad = $this->_getParam('lab_cantidad');
            $lab_pu = $this->_getParam('lab_pu');
            $cliente = $this->_getParam('cliente');
            $reembolsable = $this->_getParam('reembolsable');
            $documento = $this->_getParam('documento');
            $fecha = $this->_getParam('fecha');
            $moneda = $this->_getParam('moneda');
            $proveedor = $this->_getParam('proveedor');
            $monto = $this->_getParam('monto');
            $otro_impuesto = $this->_getParam('otro_impuesto');
            $igv = $this->_getParam('igv');
            $monto_total = $this->_getParam('monto_total');

            print_r($monto_total);
            print_r($description);
            print_r($gasto_persona_id);
            print_r($gasto_padre);
            
            $gasto = new Admin_Model_DbTable_Gastopersona();
            for ($i=0; $i < count($gasto_persona_id); $i++) { 
                $pk = array();
                $pk = array(
                    'gasto_persona_id'=>$gasto_persona_id[$i],
                    'proyectoid'=>$proyectoid[$i],
                    'codigo_prop_proy'=>$codigo_prop_proy[$i]);
                $data = array();
                $data['descripcion'] = $description[$i];
                if ($gasto_hijo[$i]!='') {
                    $data['gastoid'] = $gasto_hijo[$i];
                } else {
                    $data['gastoid'] = $gasto_padre[$i];
                }
                $data['laboratorio_cantidad'] = $lab_cantidad[$i];
                $data['laboratorio_PU'] = $lab_pu[$i];
                $data['bill_cliente'] = $cliente[$i];
                $data['reembolsable'] = $reembolsable[$i];
                if ($fecha[$i]) $data['fecha_factura'] = $fecha[$i];
                $data['moneda'] = $moneda[$i];
                $data['num_factura'] = $documento[$i];
                $data['proveedor'] = $proveedor[$i];
                $data['monto_igv'] = $monto[$i];
                $data['otro_impuesto'] = $otro_impuesto[$i];
                $data['igv'] = $igv[$i];
                $data['monto_total'] = $monto_total[$i];
                $gasto->_update($data, $pk);
            }
        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        }
    }

    public function updateenviargastopersonaAction(){
        try {
            $this->_helper->layout()->disableLayout();
            $gasto_persona_id = $this->_getParam('gasto_persona_id');
            $proyectoid = $this->_getParam('proyectoid');
            $codigo_prop_proy = $this->_getParam('codigo_prop_proy');
            $gasto = new Admin_Model_DbTable_Gastopersona();
            for ($i=0; $i < count($gasto_persona_id); $i++) { 
                $pk = array();
                $pk = array(
                    'gasto_persona_id'=>$gasto_persona_id[$i],
                    'proyectoid'=>$proyectoid[$i],
                    'codigo_prop_proy'=>$codigo_prop_proy[$i]);
                $data = array();
                $data['estado_rendicion'] ='E';
                $gasto->_update($data, $pk);
            }
        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        }
    }

    public function updateenviargastorendicionAction(){
        try {
            $this->_helper->layout()->disableLayout();
            $uid = $this->sesion->uid;
            $dni = $this->sesion->dni;
            $numero = $this->_getParam('numero');
            $pk = array();
            $pk = array(
                    'numero'=>$numero,
                    'uid'=>$uid,
                    'uid'=>$uid,
                    'dni'=>$dni);
            $data = array();
            $data['estado'] = 'E';
            $rendicion = new Admin_Model_DbTable_Gastorendicion();
            $rendicion->_update($data,$pk);
        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        }
    }

    public function eliminargastopersonaAction(){
        try {
            $this->_helper->layout()->disableLayout();
            $gasto_persona_id = $this->_getParam('gasto_persona_id');
            $gasto = new Admin_Model_DbTable_Gastopersona();
            $gasto->_delete($gasto_persona_id);
        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        }
    }
}
