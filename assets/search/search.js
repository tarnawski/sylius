$(document).ready(function () {
  $('#search')
    .autocomplete({
      autoFill: false,
      source: function (request, response) {
        $.getJSON('/api/v2/search', request, function (data, status, xhr) {
          response(data);
        });
      },
      select: function (event, ui) {
        $(location)
          .attr('href', ui.item.url);
      },
    });
});
