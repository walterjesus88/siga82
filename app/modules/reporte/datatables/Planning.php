<?php
 class Reporte_DataTable_Planning
 {

    // var $params;
    public function as_json($params)
    {
      $tb_planning =  new Admin_Model_DbTable_Planificacion();
      $count_all = $tb_planning ->_count_datatable($params);
      return array(
        'sEcho' => intval($params["sEcho"]),
        'iTotalRecords' => $count_all[0]['total'],
        'iTotalDisplayRecords' => $count_all[0]['total'],
        'aaData'=> $this->data($params)
      );
    }

    public function data($params)
    {
      $data = array();
      if ($this->plamings($params)) {
        foreach ($this->plamings($params) as $key => $plaming) {
          $data[$key][0] = $plaming['nombre'];
          $data[$key][1] = $plaming["uid"];
          $data[$key][2] = $plaming["semanaid"];
          $data[$key][3] = $plaming["semanaid"];
          $data[$key][4] = $this->estado_name($plaming["estado"]);
          $data[$key][5] = $plaming["billable"];
          $data[$key][6] = $plaming["nonbillable"];
        }
      }
      return $data;
    }

    public function plamings($params)
    {
      $tb_planning =  new Admin_Model_DbTable_Planificacion();
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
      $column = ["ar.nombre", "uid", "semanaid", "estado", "estado", "billable", "nonbillable"];
      return $column[intval($params["iSortCol_0"])];
    }

    public function sort_direction($params)
    {
      return $params["sSortDir_0"] = ($params["sSortDir_0"] == "desc" ? "desc" : "asc");
    }

    private function estado_name($estado){
      switch ($estado) {
        case 'E':
          return "Enviado para aprobacion jefe inmediato";
          break;
        case 'A':
          return "Aprobado Jefe Inmediato";
          break;
        case 'AGP':
          return "Aprobado Gerente Proyecto";
          break;
        case 'RGP':
          return "Rechazado Gerente Proyecto";
          break;
        case 'R':
          return "Rechazado por Jefe inmediato";
          break;
        default:
          return "No llena hoja de tiempo";
          break;
      }
    }
 }