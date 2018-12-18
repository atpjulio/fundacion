$(document).ready(function() {
    var debit = 0;
    var credit = 0;
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
        },
        "order": []
    });
    $('.btn-success').click(function() {
        // console.log($(this).parent().parent().find('td')[5].outerText);
        $('#invoice_id').val($(this).parent().find('input')[0].value);
        $('#invoice_number').val($(this).parent().parent().find('td').first()[0].outerText);
        document.querySelector('#beginning').scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    });
    $('#total_days').on('change', function (e) {
        $('#total').val($('#selected_price').val() * $('#total_days').val());
    });
    $('#pucs').on('change', function (e) {
        $('#puc_code').val($('#pucs').val());
        var fullDescription = $("#pucs").children("option").filter(":selected").text().split('-');
        var description = '';
        var counter = 0;

        fullDescription.forEach(function(element) {
            if (counter > 0) {
                description += element.trim();
            }
            counter++;
        });

        $('#puc_description').val(description);
    });
    $(".addRow").click(function(){
        if ($('#puc_debit').val() > 0) {
            debit += parseFloat($('#puc_debit').val());
            $('#debits').html("Débitos $ " + debit.toLocaleString('co-CO'));
        }
        if ($('#puc_credit').val() > 0) {
            credit += parseFloat($('#puc_credit').val());
            $('#credits').html("Créditos $ " + credit.toLocaleString('co-CO'));
        }
        $("#pucsTable").append('<tr>' +
            '<td><input type="text" id="notePucs" name="notePucs[]" value="' + $('#puc_code').val() + '" class="form-control" placeholder="Código PUC"/></td>' +
            '<td><input type="text" name="pucDescription[]" value="' + $('#puc_description').val()+ '" placeholder="Descripción" class="form-control"></td>' +
            '<td><input type="text" name="pucDebit[]" value="' + $('#puc_debit').val() + '" placeholder="Débitos" class="form-control"></td>' +
            '<td><input type="text" name="pucCredit[]" value="' + $('#puc_credit').val() + '"placeholder="Créditos" class="form-control"></td>' +
            '<td><a href="javascript:void(0);" class="removeRow btn btn-oval btn-danger">Quitar</a></td><tr>');
        $('#puc_code').val('');
        $('#puc_description').val('');
        $('#puc_debit').val('');
        $('#puc_credit').val('');
    });
    $("#pucsTable").on('click','.removeRow',function(){
        $(this).parent().parent().remove();
    });

} );
