$(document).ready(function() {
  $('#searching').on('change', function (e) {
    if ($('#searching').val().length > 0) {
      fullAuthorizations($('#searching').val());
    }
    if ($('#searching').val().length == 0) {
      window.location.href = '/authorization';
    }
  });
});
