$(document).ready(function() {
  $("#export_excel").click(function(){
    t_b = $('#tb_sheet_time').dataTable();
    oSettings = t_b.dataTable().fnSettings();
    params = t_b.oApi._fnAjaxParameters(oSettings);
    url = "/reporte/hojatiempo/index/format/excel?";
    url = url + $.param(params);
    $(this).attr("href", url);
  });

  filter = $.parseJSON($.ajax({
      url: "/reporte/hojatiempo/filter",
      async: false,
      dataType: 'json'}).responseText);

  t_b = $('#tb_sheet_time').dataTable({
      sAjaxSource: $("#tb_sheet_time").data("source"),
      aoColumns: [null, null, null, null],
      aaSorting: [[0,"asc"]],
      bServerSide: true,
      iDisplayLength: 10}
    );
  t_b.yadcf([
      {column_number: 0, filter_type: "range_date", filter_container_id: 'tb_sheet_time_filter_surrender', date_format: "dd/mm/yyyy", filter_default_label: ['Fecha de Inicio', 'Fecha Final']},
      {column_number: 1, select_type: 'select', filter_container_id: "tb_sheet_time_filter_state", filter_default_label: "Todos los Estados", data: [{label: "No Enviado", value: "A"}, {label: "Enviado", value: "C"}] },
      {column_number: 2, select_type: 'chosen', filter_container_id: "tb_sheet_time_filter_area", filter_default_label: "Todos las Areas", data: filter },
    ]);
});