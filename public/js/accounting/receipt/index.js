$(document).ready(function() {
    var debit = 0;
    var credit = 0;
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
    $('#entity_id').on('change', function (e) {
        if ($('#entity_id').val() != "0") {            
            fillEntityFields($('#entity_id').val());
        } else {
            $('#name').val('');
            $('#doc').val('');
            $('#address').val('');
            $('#phone').val('');
        }
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
    $(".addRow").click(function() {
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
