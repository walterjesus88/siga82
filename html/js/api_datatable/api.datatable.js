var intVal = function ( i ) {
            return typeof i === 'string' ?
                i.replace(/[\$,]/g, '')*1 :
                typeof i === 'number' ?
                    i : 0;
    };
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
      language: config_dataTable.oLanguage(),
      // sDom: '<"header-actions"ip>f<"table"t>l>',
      // sPaginationType: "full_numbers",
      sAjaxSource: $("#tb_sheet_time").data("source"),
      aoColumns: [null, null, null, null],
      aaSorting: [[0,"asc"]],
      bServerSide: true,
      iDisplayLength: 10}
    );

  t_b.yadcf([
      {column_number: 0, filter_type: "range_date", filter_container_id: 'tb_sheet_time_filter_surrender', date_format: "dd/mm/yyyy", filter_default_label: ['Fecha de Inicio', 'Fecha Final']},
      {column_number: 1, select_type: 'chosen', filter_container_id: "tb_sheet_time_filter_state", filter_default_label: "Todos los Estados", data: [{label: "No Enviado", value: "A"}, {label: "Enviado", value: "C"}] },
      {column_number: 2, select_type: 'chosen', filter_container_id: "tb_sheet_time_filter_area", filter_default_label: "Todos las Areas", data: filter.areas },
      {column_number: 3, select_type: 'chosen', filter_container_id: "tb_sheet_time_filter_user", filter_default_label: "Todos los Usuarios", data: filter.users },
    ]);

  tb_planning = $("#datatable_planning").dataTable({
    language: config_dataTable.oLanguage(),
      sAjaxSource: $("#datatable_planning").data("source"),
      aoColumns: [null, null, null, null, null, null, null],
      aaSorting: [[0,"asc"]],
      bServerSide: true,
      iDisplayLength: 10
    });

  tb_planning.yadcf([
      {column_number: 0,
        filter_type: "range_date",
        filter_container_id: 'tb_planning_filter_date_range',
        date_format: "dd/mm/yyyy",
        filter_default_label: ['Fecha de Inicio', 'Fecha Final']},
      {column_number: 1,
        select_type: 'chosen',
        filter_container_id: "tb_planning_filter_date_state",
        filter_default_label: "Todos los Estados",
        data: [{label: "Enviado para aprobacion jefe inmediato", value: "E"},
          {label: "Aprobado Jefe Inmediato", value: "A"},
          {label: "Aprobado Gerente Proyecto", value: "AGP"},
          {label: "Rechazado por Jefe inmediato", value: "R"},]}
    ]);

  $("#export_excel_planning").click(function(){
    t_b = $('#datatable_planning').dataTable();
    oSettings = t_b.dataTable().fnSettings();
    params = t_b.oApi._fnAjaxParameters(oSettings);
    url = "/reporte/planning/index/format/excel?";
    url = url + $.param(params);
    $(this).attr("href", url);
  });

  tb_PersoCharges = $("#datatable_personcharges").dataTable({
      language: config_dataTable.oLanguage(),
      sAjaxSource: $("#datatable_personcharges").data("source"),
      aoColumns: [null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null],
      aaSorting: [[0,"asc"]],
      bServerSide: true,
      iDisplayLength: 10,
      footerCallback: function (row, data, start, end, display){
        var api = this.api(), data;
        TotalNet = api
                .column( 9, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        TotalOther = api
                .column( 10, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        TotalIgv = api
                .column( 11, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        Total = api
                .column( 12, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
        $( api.column( 9 ).footer() ).html( TotalNet );
        $( api.column( 10 ).footer() ).html( TotalOther );
        $( api.column( 11 ).footer() ).html( TotalIgv );
        $( api.column( 12 ).footer() ).html( Total );
      }
    });
});

config_dataTable = {
  oLanguage: function() {
    values = {
      "sLengthMenu": "Mostrar _MENU_ filas por pagina",
      "sZeroRecords": "No se encontró nada - lo sentimos",
      "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ filas",
      "sInfoEmpty": "Mostrando 0 to 0 of 0 filas",
      "sInfoFiltered": "",
      "sSearch": "Buscar: ",
      "oPaginate":{
        "sFirst": '<span class="left-left-icon"></span>',
        "sPrevious": '<span class="glyphicon glyphicon-chevron-left"></span>',
        "sNext": '<span class="glyphicon glyphicon-chevron-right"></span>',
        "sLast": '<span class="right-right-icon"></span>'}
    }
    return values;
  },
};


