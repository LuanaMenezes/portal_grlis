$('#operacao').DataTable({
    stateSave: true,
    dom: 'lfrtip',
    "paging": true,
    "ordering": true,
    "info": true,
   // "autoWidth": false,
    'columnDefs': [
    /*{
          "targets": 2, // your case first column
          "className": "text-center",
    },
    {
          "targets": 3,
          "className": "text-center",
    },
    {
          "targets": 4,
          "className": "text-center",
    },*/
    { "orderable": false,
    "targets": 5
    },
    { "orderable": false,
    "targets": 6
    }],
    buttons: [
              {
                  extend: 'excelHtml5',
                  title: 'Lista de Clientes'
              },
              {
                  extend: 'pdfHtml5',
                  title: 'Lista de Clientes'
              }
          ],
    "language": {
    "sEmptyTable":   "Não foi encontrado nenhum registo",
    "sLoadingRecords": "A carregar...",
    "sProcessing":   "A processar...",
    "sLengthMenu":   "Mostrar _MENU_ registos",
    "sZeroRecords":  "Não foram encontrados resultados",
    "sInfo":         "Mostrando de _START_ até _END_ de _TOTAL_ registos",
    "sInfoEmpty":    "Mostrando de 0 até 0 de 0 registos",
    "sInfoFiltered": "(filtrado de _MAX_ registos no total)",
    "sInfoPostFix":  "",
    "sSearch":       "Procurar:",
    "sUrl":          "",
    "oPaginate": {
        "sFirst":    "Primeiro",
        "sPrevious": "Anterior",
        "sNext":     "Próximo",
        "sLast":     "Último"
    },
    "oAria": {
        "sSortAscending":  ": Ordenar colunas de forma ascendente",
        "sSortDescending": ": Ordenar colunas de forma descendente"
    }
  
      },
  });
  $('div.dataTables_filter input').focus()
 