$( function() {
    $( ".offering-columns" ).sortable({
        revert: true,
        stop: update_input
    });
    $( "#draggable" ).draggable({
        connectToSortable: ".offering-columns",
        helper: "clone",
        revert: "invalid"
    });

    update_input();


} );

function update_input() {
    var items = $(".offering-columns li");
    var input = $("[name=columns]");
    var order_text = "";
    console.log(items);
    for (var i = 0; i < items.length; i++) {
        console.log($(items[i]).data('id'));
        order_text += $(items[i]).data('id') + ",";
    }
    $(input).val(order_text);
}