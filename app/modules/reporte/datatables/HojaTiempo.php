<?php
 class Reporte_DataTable_HojaTiempo
 {

    // var $params;
    public function as_json($params)
    {
      $tb_sheet_time =  new Admin_Model_DbTable_Tareopersona();
      $count_all = $tb_sheet_time ->_count_all($this->sort_column($params), $params);
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
      $tb_area = new Admin_Model_DbTable_Area();
      $tb_sheet_time =  new Admin_Model_DbTable_Tareopersona();
      if ($this->sheet_times($params)) {
        foreach ($this->sheet_times($params) as $key => $sheet_time) {
          $name_area = $tb_area->_getName($sheet_time["areaid"]);
          $data[$key][0] = $name_area['nombre'];
          $data[$key][1] = $sheet_time["uid"];
          $data[$key][2] = $sheet_time["semanaid"];
          $data[$key][3] = $tb_sheet_time->convert_number_of_week_to_date($sheet_time['semanaid']);
          $data[$key][4] = $tb_sheet_time->_getNameStatus($sheet_time["estado"]);
        }
      }
      return $data;
    }

    public function sheet_times($params)
    {
      $tb_sheet_time =  new Admin_Model_DbTable_Tareopersona();
      $sheet_times = $tb_sheet_time =
        $tb_sheet_time->_dataTable(
          $this->page($params),
          $this->per_page($params),
          $this->sort_column($params),
          $this->sort_direction($params),
          $params);
      return $sheet_times;
    }

    public function page($params)
    {
      if ($params["iDisplayStart"] > 0) {
        return $params["iDisplayStart"] / $this->per_page($params) + 1;
      } else {
        return 0;
      }
      
    }

    public function per_page($params)
    {
      return (intval($params["iDisplayLength"]) > 0 ? intval($params["iDisplayLength"]) : 10);
    }

    public function sort_column($params)
    {
      $column = ["areaid", "uid", "semanaid", "semanaid", "estado"];
      return $column[intval($params["iSortCol_0"])];
    }

    public function sort_direction($params)
    {
      return $params["sSortDir_0"];
    }

 }