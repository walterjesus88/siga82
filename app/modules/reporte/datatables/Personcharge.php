<?php
 class Reporte_DataTable_Personcharge
 {

    // var $params;
    public function as_json($params)
    {
      // $tb_planning =  new Admin_Model_DbTable_Gastopersona();
      // $count_all = $tb_planning ->_count_datatable($params);
      return array(
        'sEcho' => intval($params["sEcho"]),
        'iTotalRecords' => 466,//$count_all[0]['total'],
        'iTotalDisplayRecords' => 466,//$count_all[0]['total'],
        'aaData'=> $this->data($params)
      );
    }

    public function data($params)
    {
      $data = array();
      if ($this->person_charges($params)) {
        foreach ($this->person_charges($params) as $key => $plaming) {
          $data[$key][0] = $plaming['proyectoid'];
          $data[$key][1] = $plaming["gastoid"];
          $data[$key][2] = $plaming["uid"];
          $data[$key][3] = $plaming["uid"];
          $data[$key][4] = $plaming["numero_rendicion"];
          $data[$key][5] = $plaming["descripcion"];
          $data[$key][6] = $plaming["num_factura"];
          $data[$key][7] = $plaming["num_factura"];
          $data[$key][8] = $plaming["proveedor"];
          $data[$key][9] = $plaming["monto_total"];
          $data[$key][10] = $plaming["otro_impuesto"];
          $data[$key][11] = $plaming["monto_igv"];
          $data[$key][12] = $plaming["monto_total"];
          $data[$key][13] = $plaming["fecha_gasto"];
          $data[$key][14] = $plaming["estado_rendicion"];
          $data[$key][15] = $plaming["estado_rendicion"];
          $data[$key][16] = $plaming["fecha_rendicion"];
          $data[$key][17] = $plaming["nombre_proyecto"];
        }
      }
      return $data;
    }

    public function person_charges($params)
    {
      $tb_planning =  new Admin_Model_DbTable_Gastopersona();
      $plamings = $tb_planning =
        $tb_planning->_dataTable(
          $this->page($params),
          $this->per_page($params),
          $this->sort_column($params),
          $this->sort_direction($params),
          $params);
      return $plamings;
    }

    public function page($params)
    {
      return $params["iDisplayStart"]/$this->per_page($params) + 1;
    }

    public function per_page($params)
    {
      return (intval($params["iDisplayLength"]) > 0 ? intval($params["iDisplayLength"]) : 10);
    }

    public function sort_column($params)
    {
      $column = ["gp.asignado", "uid", "semanaid", "estado", "estado", "billable", "nonbillable"];
      return $column[intval($params["iSortCol_0"])];
    }

    public function sort_direction($params)
    {
      return $params["sSortDir_0"] = ($params["sSortDir_0"] == "desc" ? "desc" : "asc");
    }

 }