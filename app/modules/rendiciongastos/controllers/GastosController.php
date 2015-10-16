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

      // echo "_____xxx_____";
      // print_r($this->_getParam('fecha'));
      // echo "_____xxx_____";

      $formdata['uid'] = $uid = $this->sesion->uid;
      $formdata['dni'] = $dni = $this->sesion->dni;
      $formdata['codigo_prop_proy']=$codigo_prop_proy = $this->_getParam("codigo_prop_proy");
      $formdata['proyectoid']= $this->_getParam("proyectoid");
      $formdata['revision']= $this->_getParam("revision");
      $formdata['categoriaid']=$categoriaid = $this->sesion->categoriaid;
      $formdata['cargo']=$ucatcargo = $this->sesion->personal->ucatcargo;
      $formdata['areaid']=$ucatareaid = $this->sesion->personal->ucatareaid;
      $formdata['estado_rendicion']="B";
      $formdata['fecha_gasto']=$this->_getParam('fecha');
      $formdata['numero_rendicion']= $this->_getParam('numero');
      $formdata['asignado']=$uid = $this->sesion->uid;
      $formdata['uid_ingreso']=$uid = $this->sesion->uid;
      // $formdata['descripcion']=$descripcion = $this->_getParam("descripcion");
      $formdata['gastoid']=$gastoid = $this->_getParam("gastoid");
      $formdata['gasto_padre']=$gastoid = $this->_getParam("gastoid");
      $formdata['bill_cliente']=$bill_cliente = $this->_getParam("bill_cliente");
      $formdata['reembolsable']=$reembolsable = $this->_getParam("reembolsable");
      $formdata['fecha_factura']=$fecha_factura = $this->_getParam("fecha_factura");
      // $formdata['num_factura']=$num_factura = $this->_getParam("num_factura");
      // $formdata['moneda']=$moneda = $this->_getParam("moneda");
      // $formdata['proveedor']=$proveedor = $this->_getParam("proveedor");
      // $formdata['monto_igv']=$monto_igv = $this->_getParam("monto_igv");
      // $formdata['otro_impuesto']=$otro_impuesto = $this->_getParam("otro_impuesto");
      // $formdata['monto_total']=$monto_total = $this->_getParam("monto_total");
      $guardargastos=new Admin_Model_DbTable_Gastopersona();
      $ggastos=$guardargastos->_save2($formdata);

      // echo "_____<br>_____";
      // echo "_____PROYECTO_____";
      // print_r($proyectoid);
      //       echo "_____<br>_____";
      // echo "_____GASTO_____";
      // print_r($gastoid);
      //       echo "_____<br>_____";
      // echo "_____FECHA FACTURA_____";
      // print_r($fecha_factura);
      //       echo "_____<br>_____";
      // echo "_____MONTO TOTAL_____";
      // print_r($monto_total);
      //       echo "_____<br>_____";
      // echo "_____xxx_____";

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
    { $clienteid=$this->_getParam("clienteid");
       $where=array('clienteid' => $clienteid );
      $proyecto = new Admin_Model_DbTable_Proyecto();
      $tipospro = $proyecto->_getFilter($where);
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

}