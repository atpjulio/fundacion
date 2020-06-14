$(document).ready(function() {
  var searchingTag = $('#searching');
  var currentUrlTag = $('#currentUrl');
  searchingTag.on('change', function (e) {
    if (searchingTag.val().length > 0) {
      if (currentUrlTag.val().length > 0) {
        fullAuthorizations(searchingTag.val() + currentUrlTag.val().replace('/', '@'));
      } else {
        fullAuthorizations(searchingTag.val());
      }
      // fullAuthorizations(searchingTag.val());
    }
    if (searchingTag.val().length == 0) {
      window.location.href = '/authorization';
    }
  });
});
