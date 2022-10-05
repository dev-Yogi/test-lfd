
$(function () {
    $("#location").autocomplete({
        source: function (request, response) {
            $.ajax({
                url: base_url + 'ajax/get_locations_autocomplete',
                type: 'POST',
                dataType: 'json',
                data: {
                    location: request.term
                },
                success: function (data) {
                    response($.map(data, function (location) { 
                        return {
                            label: location.city,
                            value: location.city,
                        };
                   }));
                }
            });
        },
        minLength: 3,
        select: function (event, ui) {
        }
    });
});