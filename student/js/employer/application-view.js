$('.slidey-close').click(function() {
    $('.slidey-bg').fadeOut('fast');
    $('.slidey').addClass('hidden');
});

$('.slidey-open').click(function() {
    $('.slidey-bg').fadeIn('fast');
    $('.slidey').removeClass('hidden');
});

$('.slidey-notes').click(function() {
    $('#pills-notes-tab').tab('show')
});

$('.slidey-activity').click(function() {
    $('#pills-activity-tab').tab('show')
});

$('.slidey-status').click(function() {
    $('#pills-status-tab').tab('show')
});