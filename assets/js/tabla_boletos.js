function cargar_tabla_boletos(datos) {
  try {
    var table = $(datos["id_tabla"]).DataTable({
      data: datos["data"],
      columns: datos["columns"],
      destroy: true,
      language: {
        search: `${i18n.t("datatable_show")}: _INPUT_`,
        searchPlaceholder: `${i18n.t("datatable_search_placeholder")}`,
        lengthMenu: `${i18n.t("datatable_show")} _MENU_ ${i18n.t("datatable_entries_per_page")}`,
        info: `${i18n.t("datatable_showing")} _START_ ${i18n.t("datatable_to")} _END_ ${i18n.t("datatable_of")} _TOTAL_ ${i18n.t("datatable_entries")}`,
        infoEmpty: `${i18n.t("datatable_showing")} 0 ${i18n.t("datatable_to")} 0 ${i18n.t("datatable_of")} 0 ${i18n.t("datatable_entries")}`,
        infoFiltered: `(${i18n.t("datatable_filter")} _MAX_ ${i18n.t("datatable_entries_total")})`,
        zeroRecords: `${i18n.t("datatable_no_records")}`,
        emptyTable: `${i18n.t("datatable_no_data")}`,
        paginate: {
          previous: `${i18n.t("datatable_previous")}`,
          first: `${i18n.t("datatable_first")}`,
          last: `${i18n.t("datatable_last")}`,
          next: `${i18n.t("datatable_next")}`,
        },
      },
      order: [],
    });
    $(table.table().body()).addClass("align-middle");
    // $(table.table().node()).addClass("table-striped");
  } catch (error) {
    console.error(error);
    console.log(datos);
    table = $(datos["id_tabla"]).DataTable({
      columns: datos["columns"],
    });
  }
}
