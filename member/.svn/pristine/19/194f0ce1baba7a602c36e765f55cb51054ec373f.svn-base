$( function() {
    $( ".links" ).sortable({
        revert: true,
        stop: update_input
    });
    $( "#draggable" ).draggable({
        connectToSortable: ".links",
        helper: "clone",
        revert: "invalid"
    });

    update_input();


} );

function update_input() {
    var items = $(".links li");
    var input = $("[name=links_order]");
    var order_text = "";
    console.log(items);
    for (var i = 0; i < items.length; i++) {
        console.log($(items[i]).data('id'));
        order_text += $(items[i]).data('id') + ",";
    }
    $(input).val(order_text);
}