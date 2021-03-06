$(document).ready(function() {
    var days = 0;
    $('#total').val($('#selected_price').val() * $('#total_days').val());
    $('#myTable').DataTable({
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
        }
    });
    $('#myTable').on('click','.btn-success', function() {
        // console.log($(this).parent().parent().find('td')[5].outerText);
        days = parseInt($(this).parent().parent().find('td')[5].outerText);
        $('#total_days').val(days);
        $('#selected_price').val($(this).parent().find('input')[0].value);
        $('#total').val($('#selected_price').val() * $('#total_days').val());
        //$('#total').val($('#selected_price').val() * $('#total_days').val() + parseInt($('#total').val()));
        $('#authorization_code').val($(this).parent().parent().find('td').first()[0].outerText.trim());
        //$('#authorization_code').val($(this).parent().parent().find('td').first()[0].outerText.trim() + "," + $('#authorization_code').val());
        document.querySelector('#beginning').scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
        history.pushState(null, null, '#beginning');
        e.preventDefault();
    });
    $('#total_days').on('change', function (e) {
        $('#total').val($('#selected_price').val() * $('#total_days').val());
    });

} );
