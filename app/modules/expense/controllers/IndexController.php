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

        $where = array();
            $where['fecha'] = date("Y-m-d");
            $where['uid'] = $uid;
            $where['dni'] = $dni;
            $where['estado'] = 'B';
            $rendicion = new Admin_Model_DbTable_Gastorendicion();
            $result = $rendicion->_save($where);
            $ceros = '10 - ';
            for ($h=0; $h < (8-strlen($result['numero'])); $h++) { 
                $ceros = $ceros.'0';
            }
            $data2['numero_completo'] = $ceros.$result['numero'];
            $rendicion->_update($data2,$result);
            $this->view->numero = $result['numero'];
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
            $numero = $this->_getParam('numero');
            $equipo = new Admin_Model_DbTable_Equipo();
            $data_equipo = $equipo->_getProyectosxUidXEstadoxCliente($uid,'A',$clienteid,$unidadid);
            $this->view->equipo = $data_equipo;
            $this->view->clienteid = $clienteid;
            $this->view->unidad_mineraid = $unidadid;
            $this->view->numero = $numero;
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

        $estado='A';
        $equipo=new Admin_Model_DbTable_Equipo();
        $ekip=$equipo->_getProyectosXuidXEstado($uid,$estado);
        //print_r($ekip);
        $this->view->ekip = $ekip;

                        

        $numero = $this->_getParam('numero');
        $gasto = new Admin_Model_DbTable_Gastopersona();
        $data_gasto = $gasto->_getgastoProyectosXnumero($numero, $uid, $dni);
        
        for ($i=0; $i < count($data_gasto); $i++) { 
            $order = array('gasto_persona_id ASC');
            $wheretmp ['uid'] = $uid;
            $wheretmp ['dni'] = $dni;
            $wheretmp ['numero_rendicion'] = $numero;
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
            $data_gasto[$i]['actividades'] = $data_gasto_final;
        }
        $this->view->gasto = $data_gasto;
        $this->view->numero = $numero;
        
        $where_tmp = array();
        $where_tmp['uid'] = $uid;
        $where_tmp['dni'] = $dni;
        $where_tmp['numero'] = $numero;
        $rendicion = new Admin_Model_DbTable_Gastorendicion();
        $data_rendicion = $rendicion->_getOneXnumero($where_tmp);
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
            $gasto_persona_id = $this->_getParam('gasto_persona_id');
            if ($gasto_persona_id) {
                $where['gasto_persona_id'] = $gasto_persona_id;
                $gasto_persona = new Admin_Model_DbTable_Gastopersona();
                $data_gasto_persona = $gasto_persona->_getFilter($where,$attrib=null,$orders=null);
                print_r($this->view->gasto_persona = $data_gasto_persona[0]);
            }
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
            $numero = $this->_getParam('numero');
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
            $data['numero_rendicion'] = $numero;
            $data ['estado_rendicion'] = 'B';
            if ($actividadid) {
                $data ['actividadid'] = $actividadid;
            }
            $gasto = new Admin_Model_DbTable_Gastopersona();
            $gasto->_save($data);
            $this->view->numero = $numero;

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
            
            $gasto = new Admin_Model_DbTable_Gastopersona();
            for ($i=0; $i < count($gasto_persona_id); $i++) { 
                $pk = array();
                $pk = array(
                    'gasto_persona_id'=>$gasto_persona_id[$i],
                    'proyectoid'=>$proyectoid[$i],
                    'codigo_prop_proy'=>$codigo_prop_proy[$i]);
                $data = array();
                $data['descripcion'] = $description[$i];
                $data['gasto_padre'] = $gasto_padre[$i];
                if ($gasto_hijo[$i]!='') {
                    list($gastoid, $tipo_gasto, $gasto_unitario) = split('[-]', $gasto_hijo[$i]);
                    $data['gastoid'] = $gastoid.'-'.$tipo_gasto;
                } else {
                    $data['gastoid'] = $gasto_hijo[$i];
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
                
                $tipo_cambio = new Admin_Model_DbTable_Tipogasto();
                $where_cambio['fecha'] = $fecha[$i];
                $where_cambio['tipo_moneda'] = 'Dolar Americano';
                $dolar_tipo_cambio = $tipo_cambio->_getOne($where_cambio);
                $where_cambio['tipo_moneda'] = 'Dolar Canadiense';
                $dolar_canadiense_tipo_cambio = $tipo_cambio->_getOne($where_cambio);
                $where_cambio['tipo_moneda'] = 'Real';
                $real_tipo_cambio = $tipo_cambio->_getOne($where_cambio);
                $where_cambio['tipo_moneda'] = 'Peso Chileno';
                $peso_chileno_tipo_cambio = $tipo_cambio->_getOne($where_cambio);
                $where_cambio['tipo_moneda'] = 'Peso Argentino';
                $peso_argentino_tipo_cambio = $tipo_cambio->_getOne($where_cambio);
                
                if ($moneda[$i] == 'Soles') {
                    $data['soles_monto_total'] = $monto_total[$i];

                    $tot_dolar = $monto_total[$i] / $dolar_tipo_cambio['compra'];
                    $data['dolar_monto_total'] = round($tot_dolar, 2);
                    //$tot_dolar_canadiense = $monto_total[$i] / $dolar_canadiense_tipo_cambio['compra'];
                    //$data['dolar_canadiense_monto_total'] = round($tot_dolar_canadiense, 2);
                    //$tot_real = $monto_total[$i] / $real_tipo_cambio['compra'];
                    //$data['real_monto_total'] = round($tot_real, 2);
                    //$tot_peso_chileno = $monto_total[$i] * $peso_chileno_tipo_cambio['compra'];
                    //$data['peso_chileno_monto_total'] = round($tot_peso_chileno, 2);
                    //$tot_peso_argentino = $monto_total[$i] * $peso_argentino_tipo_cambio['compra'];
                    //$data['peso_argentino_monto_total'] = round($tot_peso_argentino, 2);
                    
                }

                if ($moneda[$i] == 'Dolar Americano') {
                    $data['dolar_monto_total'] = $monto_total[$i];

                    $tot_soles = $monto_total[$i] * $dolar_tipo_cambio['compra'];
                    $data['soles_monto_total'] = round($tot_soles, 2);

                    //$tot_dolar_canadiense = $data['soles_monto_total'] / $dolar_canadiense_tipo_cambio['compra'];
                    //$data['dolar_canadiense_monto_total'] = round($tot_dolar_canadiense, 2);
                    //$tot_real = $data['soles_monto_total'] / $real_tipo_cambio['compra'];
                    //$data['real_monto_total'] = round($tot_real, 2);
                    //$tot_peso_chileno = $data['soles_monto_total'] * $peso_chileno_tipo_cambio['compra'];
                    //$data['peso_chileno_monto_total'] = round($tot_peso_chileno, 2);
                    //$tot_peso_argentino = $data['soles_monto_total'] * $peso_argentino_tipo_cambio['compra'];
                    //$data['peso_argentino_monto_total'] = round($tot_peso_argentino, 2);
                    
                }

                if ($moneda[$i] == 'Dolar Canadiense') {
                    $data['dolar_canadiense_monto_total'] = $monto_total[$i];

                    $tot_soles = $monto_total[$i] * $dolar_canadiense_tipo_cambio['compra'];
                    $data['soles_monto_total'] = round($tot_soles, 2);
                    
                    $tot_dolar = $data['soles_monto_total'] / $dolar_tipo_cambio['compra'];
                    $data['dolar_monto_total'] = round($tot_dolar, 2);
                    $tot_real = $data['soles_monto_total'] / $real_tipo_cambio['compra'];
                    $data['real_monto_total'] = round($tot_real, 2);
                    $tot_peso_chileno = $data['soles_monto_total'] * $peso_chileno_tipo_cambio['compra'];
                    $data['peso_chileno_monto_total'] = round($tot_peso_chileno, 2);
                    $tot_peso_argentino = $data['soles_monto_total'] * $peso_argentino_tipo_cambio['compra'];
                    $data['peso_argentino_monto_total'] = round($tot_peso_argentino, 2);
                }


                if ($moneda[$i] == 'Real') {
                    $data['real_monto_total'] = $monto_total[$i];

                    $tot_soles = $monto_total[$i] * $real_tipo_cambio['compra'];
                    $data['soles_monto_total'] = round($tot_soles, 2);
                    
                    $tot_dolar = $data['soles_monto_total'] / $dolar_tipo_cambio['compra'];
                    $data['dolar_monto_total'] = round($tot_dolar, 2);
                    $tot_dolar_canadiense = $data['soles_monto_total'] / $dolar_canadiense_tipo_cambio['compra'];
                    $data['dolar_canadiense_monto_total'] = round($tot_dolar_canadiense, 2);
                    $tot_peso_chileno = $data['soles_monto_total'] * $peso_chileno_tipo_cambio['compra'];
                    $data['peso_chileno_monto_total'] = round($tot_peso_chileno, 2);
                    $tot_peso_argentino = $data['soles_monto_total'] * $peso_argentino_tipo_cambio['compra'];
                    $data['peso_argentino_monto_total'] = round($tot_peso_argentino, 2);
                }

                if ($moneda[$i] == 'Peso Chileno') {
                    $data['peso_chileno_monto_total'] = $monto_total[$i];

                    $tot_soles = $monto_total[$i] / $peso_chileno_tipo_cambio['compra'];
                    $data['soles_monto_total'] = round($tot_soles, 2);
                    
                    $tot_dolar = $data['soles_monto_total'] / $dolar_tipo_cambio['compra'];
                    $data['dolar_monto_total'] = round($tot_dolar, 2);
                    $tot_dolar_canadiense = $data['soles_monto_total'] / $dolar_canadiense_tipo_cambio['compra'];
                    $data['dolar_canadiense_monto_total'] = round($tot_dolar_canadiense, 2);
                    $tot_real = $data['soles_monto_total'] / $real_tipo_cambio['compra'];
                    $data['real_monto_total'] = round($tot_real, 2);
                    $tot_peso_argentino = $data['soles_monto_total'] * $peso_argentino_tipo_cambio['compra'];
                    $data['peso_argentino_monto_total'] = round($tot_peso_argentino, 2);
                }

                if ($moneda[$i] == 'Peso Argentino') {
                    $data['peso_argentino_monto_total'] = $monto_total[$i];

                    $tot_soles = $monto_total[$i] / $peso_argentino_tipo_cambio['compra'];
                    $data['soles_monto_total'] = round($tot_soles, 2);
                    
                    $tot_dolar = $data['soles_monto_total'] / $dolar_tipo_cambio['compra'];
                    $data['dolar_monto_total'] = round($tot_dolar, 2);
                    $tot_dolar_canadiense = $data['soles_monto_total'] / $dolar_canadiense_tipo_cambio['compra'];
                    $data['dolar_canadiense_monto_total'] = round($tot_dolar_canadiense, 2);
                    $tot_real = $data['soles_monto_total'] / $real_tipo_cambio['compra'];
                    $data['real_monto_total'] = round($tot_real, 2);
                    $tot_peso_chileno = $data['soles_monto_total'] * $peso_chileno_tipo_cambio['compra'];
                    $data['peso_chileno_monto_total'] = round($tot_peso_chileno, 2);
                }

                print_r($data);
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

    public function historicoAction(){
        try {
            $uid = $this->sesion->uid;
            $dni = $this->sesion->dni;
            $where = array();
            $where['uid'] = $uid;
            $where['dni'] = $dni;
            $where['estado'] = 'B';
            $rendicion = new Admin_Model_DbTable_Gastorendicion();
            $data_rendicion = $rendicion->_getAllXuidXestado($where);
            $this->view->pendiente = $data_rendicion;

            $where['estado'] = 'E';
            $data_enviado = $rendicion->_getAllXuidXestado($where);
            $this->view->enviado = $data_enviado;

            $where['estado'] = 'R';
            $data_rechazado = $rendicion->_getAllXuidXestado($where);
            $this->view->rechazado = $data_rechazado;

            $where['estado'] = 'A';
            $data_aprobado = $rendicion->_getAllXuidXestado($where);
            $this->view->aprobado = $data_aprobado;
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
            $wheretmp = array();
            $wheretmp['numero_rendicion'] = $numero;
            $wheretmp['dni'] = $dni;
            $wheretmp['uid'] = $uid;
            $gastos = new Admin_Model_DbTable_Gastopersona();
            $data_gastos = $gastos->_getFilter($wheretmp,$attrib=null,$orders=null);
            $proyecto = new Admin_Model_DbTable_Proyecto();
            for ($i=0; $i < count($data_gastos); $i++) { 
                    $pk = array();
                    $pk['proyectoid'] = $data_gastos[$i]['proyectoid'];
                    $pk['codigo_prop_proy'] = $data_gastos[$i]['codigo_prop_proy'];
                    $data_proyecto = $proyecto->_getOne($pk);
                    $data_gastos[$i]['nombre_final'] = $data_proyecto['nombre_proyecto'];
            }

            $this->view->gasto = $data_gastos;

            $gastos = new Admin_Model_DbTable_Listagasto();
            $data_list_gastos = $gastos->_getGastosAll();
            $this->view->list_gastos = $data_list_gastos;
        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        }
    }

    public function editardetallesAction(){
        try {
            $this->_helper->layout()->disableLayout();
            $numero = $this->_getParam('numero');
            $uid = $this->_getParam('uid');
            $dni = $this->_getParam('dni');

            $estado='A';
            $equipo=new Admin_Model_DbTable_Equipo();
            $ekip=$equipo->_getProyectosXuidXEstado($uid,$estado);
            //print_r($ekip);
            $this->view->ekip = $ekip;

            $gasto = new Admin_Model_DbTable_Gastopersona();
            $data_gasto = $gasto->_getgastoProyectosXnumero($numero, $uid, $dni);
            for ($i=0; $i < count($data_gasto); $i++) { 
                $order = array('gasto_persona_id ASC');
                $wheretmp ['uid'] = $uid;
                $wheretmp ['dni'] = $dni;
                $wheretmp ['numero_rendicion'] = $numero;
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
                $data_gasto[$i]['actividades'] = $data_gasto_final;
            }
        $this->view->gasto = $data_gasto;
        
        $where_tmp = array();
        $where_tmp['uid'] = $uid;
        $where_tmp['dni'] = $dni;
        $where_tmp['numero'] = $numero;
        $rendicion = new Admin_Model_DbTable_Gastorendicion();
        $data_rendicion = $rendicion->_getOneXnumero($where_tmp);
        $this->view->data_rendicion = $data_rendicion;

        $gastos = new Admin_Model_DbTable_Listagasto();
        $data_list_gastos = $gastos->_getGastosHijos();
        $this->view->list_gastos = $data_list_gastos;

        $data_all_gastos = $gastos->_getGastosAll();
        $this->view->all_gastos = $data_all_gastos;
        
        $this->view->numero = $numero;
        $this->view->uid = $uid;
        $this->view->dni = $dni;
        } catch (Exception $e) {
            print "Error: ".$e->getMessage();
        }
    }

    public function imprimirrendicionAction(){
        try {
            $this->_helper->layout()->disableLayout();
            $numero = $this->_getParam('numero');
            $uid = $this->_getParam('uid');
            $dni = $this->_getParam('dni');
            $gasto = new Admin_Model_DbTable_Gastopersona();
            $data_gasto = $gasto->_getgastoProyectosXnumero($numero, $uid, $dni);
            
            for ($i=0; $i < count($data_gasto); $i++) { 
                $order = array('gasto_persona_id ASC');
                $wheretmp ['uid'] = $uid;
                $wheretmp ['dni'] = $dni;
                $wheretmp ['numero_rendicion'] = $numero;
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
                $data_gasto[$i]['actividades'] = $data_gasto_final;
            }
            $this->view->gasto = $data_gasto;
            $this->view->numero = $numero;
            
            $where_tmp = array();
            $where_tmp['uid'] = $uid;
            $where_tmp['dni'] = $dni;
            $where_tmp['numero'] = $numero;
            $rendicion = new Admin_Model_DbTable_Gastorendicion();
            $data_rendicion = $rendicion->_getOneXnumero($where_tmp);
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


    public function eliminarrendicionAction(){
        try {

        $this->_helper->layout()->disableLayout();
        $numero = $this->_getParam('numero');
        $uid = $this->_getParam('uid');
        $dni = $this->_getParam('dni');

        $gasto = new Admin_Model_DbTable_Gastopersona();
        $egasto=$gasto->_deleteX($numero,$uid,$dni);

      

        $rendicion = new Admin_Model_DbTable_Gastorendicion();
        $erendicion=$rendicion->_delete($numero,$uid,$dni);


        }
        catch (Exception $e) {
            print "Error: ".$e->getMessage();
        }
    }
    
    public function cambiarproyectAction(){
        try {
            $proyectoid = $this->_getParam('proyectoid');
            $codigo_prop_proy = $this->_getParam('codigo_prop_proy');
            $revision = $this->_getParam('revision');
            $numero_rendicion = $this->_getParam('numero_rendicion');
            $uid = $this->_getParam('uid');
            $dni = $this->_getParam('dni');
            $codigo_prop_proy_anterior = $this->_getParam('codigo_prop_proy_anterior');
            $proyectoid_anterior = $this->_getParam('proyectoid_anterior');
            $areaid = $this->_getParam('areaid');
            $categoriaid = $this->_getParam('categoriaid');
            $revision_nueva = $this->_getParam('revision_nueva');

            //$gasto_persona_id = $this->_getParam('gasto_persona_id');

            $wherex=array('codigo_prop_proy'=>$codigo_prop_proy_anterior,'proyectoid'=>$proyectoid_anterior,'revision'=>$revision,
                          'uid'=>$uid,'dni'=>$dni,'numero_rendicion'=>$numero_rendicion);
                         // ,'areaid'=>$areaid,'categoriaid'=>$categoriaid);
         
            // codigo_prop_proy, proyectoid, revision, gastoid, uid, dni, categoriaid, cargo, areaid, gasto_persona_id

            // , "categoriaid", "gastoid", , "gasto_persona_id"
            // array("codigo_prop_proy", "proyectoid", "revision", "categoriaid", "gastoid", "uid", "dni", "gasto_persona_id");
            $gpe= new Admin_Model_DbTable_Gastopersona();
            //$gp=$gpe->_getFilter($wherex);

            $data['proyectoid']=$proyectoid;
            $data['codigo_prop_proy']=$codigo_prop_proy;
            $data['revision']=$revision_nueva;
           
            $upgasto=$gpe->_updateX($data,$wherex);

            print_r($data);

            print_r($wherex);

            if($upgasto)
            {
                print_r($upgasto);
                
            }          
            //exit();

        }

        catch (Exception $e) {
            print "Error: ".$e->getMessage();
        }
    }

    
}
