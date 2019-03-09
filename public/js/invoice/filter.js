$(document).ready(function() {
  $('#searching').on('change', function (e) {
    if ($('#searching').val().length > 0) {
      fillFilteredInvoices($('#searching').val());
    }
    if ($('#searching').val().length == 0) {
      window.location.href = '/invoice';
    }
  });
});
