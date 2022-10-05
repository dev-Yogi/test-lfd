$('.categories-menu-button').click(function () {
    var menu = $('.categories-menu');
    var icon = $('.categories-menu-button .fe');
    if ($(menu).is(':visible')) {
        $(menu).slideUp();
        $(icon).removeClass('fe-chevron-up').addClass('fe-chevron-down');
    } else {
        $(menu).slideDown();
        $(icon).removeClass('fe-chevron-down').addClass('fe-chevron-up');
    }
});

function mark_all_as_read() {
    console.log("mark'em");
    $.get(base_url + "inbox/mark_all_as_read", function() {
        $(".nav-unread").addClass('d-none');
        console.log("Done");
    });
}

$(document).ready(function(){
    $('#assignment input[type=submit]').click(function(){
        var fd = new FormData();    
        fd.append( 'userfile', $('#assignment input[type=file]')[0].files[0]);

        $.ajax({
            xhr: function() {
                var xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function(evt) {
                    if ($('#assignment input[type=file]')[0].files.length) {
                        if (evt.lengthComputable) {
                            $('#assignment .progress').removeClass('d-none');
                            var percentComplete = evt.loaded / evt.total;
                            percentComplete = parseInt(percentComplete * 100);
                            $('#upload_progress').css('width', percentComplete + '%');
                        }
                    }
                }, false);

                return xhr;
            },
            url: '/student/lesson/submit_assignment',
            data: fd,
            processData: false,
            contentType: false,
            type: 'POST',
            success: function(data) {
            }
        });
    });
});
