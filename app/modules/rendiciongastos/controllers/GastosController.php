<?php
class Rendiciongastos_GastosController extends Zend_Controller_Action {

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

  /*GASTO RENDICION*/

//Devuelve la lista de proyectos por estado
  public function gastosAction()
  {
    $estado = $this->_getParam('estado');
    $proyecto = new Admin_Model_DbTable_Gastorendicion();
    $proyectos = $proyecto->_getFilter($estado);
    $respuesta = [];
    $data = [];
    $i = 0;
    foreach ($proyectos as $item) {
      $data['numero'] = $item['numero'];
      $data['numero_completo'] = $item['numero_completo'];
      $data['nombre'] = $item['nombre'];
      $data['fechas'] = $item['fechas'];
      $data['monto_total'] = $item['monto_total'];
        // $data['control_documentario'] = $item['control_documentario'];
      $data['estado'] = $item['estado'];
        // $data['unidad_red'] = $item['unidad_red'];
      $respuesta[$i] = $data;
      $i++;
    }
    $this->_helper->json->sendJson($respuesta);
  }


  public function gastosxestadoAction()
  {


    $uid = $this->sesion->uid;
    $dni = $this->sesion->dni;
    $estado_gasto = $this->_getParam('estado');
    $where = array();
    $where['uid'] = $uid;
    $where['dni'] = $dni;
    $where['estado'] = $estado_gasto;
    $rendicion = new Admin_Model_DbTable_Gastorendicion();
    $data_rendicion = $rendicion->_getAllXuidXestado($where);
    $this->_helper->json->sendJson($data_rendicion);

  }

  public function guardarrendicionAction()
  {

    $where = array();
    $where['fecha'] =$fecha = $this->_getParam("fecha");
    $where['uid'] = $uid = $this->sesion->uid;
    $where['dni'] = $dni = $this->sesion->dni;
    $where['estado'] = $estado = $this->_getParam("estado");
    $where['nombre'] = $nombre = $this->_getParam("nombre");
    $rendicion = new Admin_Model_DbTable_Gastorendicion();
    $result = $rendicion->_save($where);
    $ceros = '10 - ';
    for ($h=0; $h < (8-strlen($result['numero'])); $h++) { 
      $ceros = $ceros.'0';
    }
    $data2['numero_completo'] = $ceros.$result['numero'];
    $rendicion->_update($data2,$result);
            // $this->view->numero = $result['numero'];

    $this->_helper->json->sendJson($result['numero']);

  }


    //Devuelve los datos de una rendicion en particular
  public function rendirAction()
  {
   try{

    $data['numero'] = $this->_getParam('numero');
    $rendir = new Admin_Model_DbTable_Gastorendicion();
      // $datos = $rendir->_getOne($where);
    $datos = $rendir->_getOne($data);
    $respuesta['numero'] = $datos['numero'];
    $respuesta['numero_completo'] = $datos['numero_completo'];
    $respuesta['fecha'] = $datos['fecha'];
    $respuesta['nombre'] = $datos['nombre'];
    $respuesta['monto_total'] = $datos['monto_total'];
    $respuesta['estado'] = $datos['estado'];


    $gp = new Admin_Model_DbTable_Gastopersona();
    $datos = $gp->_getOneXnumero($data);

    $respuesta=$gp->_getlistagastosxNumero($data['numero']);

      /*$respuesta['descripcion'] = $datos['descripcion'];
      $respuesta['gastoid'] = $datos['gastoid'];
      $respuesta['bill_cliente'] = $datos['bill_cliente'];
      $respuesta['reembolsable'] = $datos['reembolsable'];
      $respuesta['fecha_factura'] = $datos['fecha_factura'];
      $respuesta['num_factura'] = $datos['num_factura'];
      $respuesta['proveedor'] = $datos['proveedor'];
      $respuesta['monto_igv'] = $datos['monto_igv'];
      $respuesta['otro_impuesto'] = $datos['otro_impuesto'];*/


      // $numero = $this->_getParam('numero');
      // $where = array('numero' =>$numero);
      // $gp = new Admin_Model_DbTable_Gastopersona();
      // $r = $gp->_getOneXnumero($where);


      // $this->_helper->json->sendJson($gastos);
      $this->_helper->json->sendJson($respuesta);
      echo "========";
      print_r($respuesta);
      echo "========";
    }

    catch (Exception $ex){
      print $ex->getMessage();
    }
}


    /*GASTO PERSONA*/
    public function guardargastosAction()
    {

      // print_r($pk);
      // print_r($ggastos);

      // exit();

      // $data['numero'] = $this->_getParam('numero');
      // $rendir = new Admin_Model_DbTable_Gastorendicion();
      // $datos = $rendir->_getOne($data);

      echo "_____xxx_____";
      print_r($this->_getParam('fecha'));
      echo "_____xxx_____";

      $formdata['uid'] = $uid = $this->sesion->uid;
      $formdata['dni'] = $dni = $this->sesion->dni;
      $formdata['codigo_prop_proy']="15.10.128-1204.10.08-A";
      $formdata['proyectoid']="1204.10.08";
      $formdata['revision']="A";
      $formdata['categoriaid']=$categoriaid = $this->sesion->categoriaid;
      $formdata['cargo']=$ucatcargo = $this->sesion->personal->ucatcargo;
      $formdata['areaid']=$ucatareaid = $this->sesion->personal->ucatareaid;
      $formdata['estado_rendicion']="B";
      $formdata['fecha_gasto']=$this->_getParam('fecha');
      $formdata['numero_rendicion']= $this->_getParam('numero');
      $formdata['asignado']=$uid = $this->sesion->uid;
      $formdata['uid_ingreso']=$uid = $this->sesion->uid;
      $formdata['descripcion']=$descripcion = $this->_getParam("descripcion");
      $formdata['gastoid']=$gastoid = $this->_getParam("gastoid");
      $formdata['gasto_padre']=$gastoid = $this->_getParam("gastoid");
      $formdata['bill_cliente']=$bill_cliente = $this->_getParam("bill_cliente");
      $formdata['reembolsable']=$reembolsable = $this->_getParam("reembolsable");
      $formdata['fecha_factura']=$fecha_factura = $this->_getParam("fecha_factura");
      $formdata['num_factura']=$num_factura = $this->_getParam("num_factura");
      $formdata['moneda']=$moneda = $this->_getParam("moneda");
      $formdata['proveedor']=$proveedor = $this->_getParam("proveedor");
      $formdata['monto_igv']=$monto_igv = $this->_getParam("monto_igv");
      $formdata['otro_impuesto']=$otro_impuesto = $this->_getParam("otro_impuesto");
      $formdata['monto_total']=$monto_total = $this->_getParam("monto_total");
      $guardargastos=new Admin_Model_DbTable_Gastopersona();
      $ggastos=$guardargastos->_save2($formdata);

      echo "_____<br>_____";
      echo "_____PROYECTO_____";
      print_r($proyectoid);
            echo "_____<br>_____";
      echo "_____GASTO_____";
      print_r($gastoid);
            echo "_____<br>_____";
      echo "_____FECHA FACTURA_____";
      print_r($fecha_factura);
            echo "_____<br>_____";
      echo "_____MONTO TOTAL_____";
      print_r($monto_total);
            echo "_____<br>_____";
      echo "_____xxx_____";

      exit();

      $this->_helper->json->sendJson($ggastos);



  }

      //Devuelve la lista de tipos de gastos de la tabla listagasto
    public function clienteAction()
    {
      $cliente = new Admin_Model_DbTable_Cliente();
      $tiposcli = $cliente->_getClienteAll();
      $this->_helper->json->sendJson($tiposcli);
      print_r("GastosController cliente --->" + $tiposcli);
    }

      //Devuelve la lista de tipos de gastos de la tabla listagasto
    public function proyectoAction()
    {
      $proyecto = new Admin_Model_DbTable_Proyecto();
      $tipospro = $proyecto->_getProyectoAll();
      $this->_helper->json->sendJson($tipospro);
      print_r("GastosController proyecto --->" + $tipospro);
    }

      //Devuelve la lista de tipos de gastos de la tabla listagasto
    public function tipogastosAction()
    {
      $gasto = new Admin_Model_DbTable_Listagasto();
      $tipos = $gasto->_getGastosAll();
      $this->_helper->json->sendJson($tipos);
      print_r("GastosController --->" + $tipos);
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
                //print_r($data_gasto_final);
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

/*=======================*/
}