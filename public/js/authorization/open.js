$(document).ready(function() {
    $('#myTable').DataTable({
        "pageLength": 50,
        "language": {
            "lengthMenu": "Mostrando _MENU_ registros por página",
            "zeroRecords": "No se encontró ningún resultado",
            "info": "Mostrando página _PAGE_ de _PAGES_",
            "infoEmpty": "No hay información disponible",
            "infoFiltered": "(filtrando de un total de _MAX_ registros)",
            "search":         "Buscar:",
            "paginate": {
                "first":      "Primera",
                "last":       "Última",
                "next":       "Siguiente",
                "previous":   "Anterior"
            }
        },
        "order": []
    });
} );
