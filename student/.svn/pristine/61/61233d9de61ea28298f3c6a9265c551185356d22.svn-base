// $(document).ready(function () {
//     $('.date-picker').datepicker();

//     $('[data-toggle="tooltip"]').tooltip();
//     bsCustomFileInput.init();
    
//     // Multi-select
//     $('.selectize').selectize({
//         // Needed to carry over HTML data attributed like "data-interview" for status dropdown
//         // from https://github.com/selectize/selectize.js/issues/239#issuecomment-73681922
//         onInitialize: function () {
//             var s = this;
//             this.revertSettings.$children.each(function () {
//                 $.extend(s.options[this.value], $(this).data());
//             });
//         }
//     });
// });

// $('.open-date-picker').click(function (event) {
//     var input = $(event.target).closest('.input-group').find('.date-picker');
//     input.datepicker('show');
// });

$(document).ready(function() {

    // General WSIWYG Editor
    if($('#ckeditor').length) {
        CKEDITOR.replace( 'ckeditor', {
            customConfig: '/js/member/ckeditor/careerlink-config.js',
            toolbar: 'ClinkToolbarSource',
            height: 320,
            resize_minWidth: 620,
            resize_maxWidth: 873
        } );
    }

    // Lesson WSIWYG Editor
    if($('#lesson_ckeditor').length) {
        // CKEDITOR.addCss('.cke_editable { font-size: 15px; padding: 2em; }');
        CKEDITOR.replace('lesson_ckeditor', {
          toolbar: [
            {
              name: 'clipboard',
              items: ['Undo', 'Redo']
            },
            {
              name: 'styles',
              items: ['Format', 'Font', 'FontSize']
            },
            {
              name: 'colors',
              items: ['TextColor', 'BGColor']
            },
            {
              name: 'align',
              items: ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock']
            },
            '/',
            {
              name: 'basicstyles',
              items: ['Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat', 'CopyFormatting']
            },
            {
              name: 'links',
              items: ['Link', 'Unlink']
            },
            {
              name: 'paragraph',
              items: ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote']
            },
            {
              name: 'insert',
              items: ['Image', 'Table']
            },
            {
              name: 'tools',
              items: ['Maximize']
            },
            { name: 'document', items: [ 'Source' ] },
          ],

          extraAllowedContent: 'h3{clear};h2{line-height};h2 h3{margin-left,margin-top}',

          // Adding drag and drop image upload.
          extraPlugins: 'format,font,colorbutton,justify,uploadimage',
          uploadUrl: '/student/ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json',

          // Configure your file manager integration. This example uses CKFinder 3 for PHP.
          // filebrowserBrowseUrl: '/student/ckfinder/ckfinder.html',
          // filebrowserImageBrowseUrl: '/student/ckfinder/ckfinder.html?type=Images',
          // filebrowserUploadUrl: '/student/ckfinder/core/connector/php/uploader.php?command=QuickUpload&type=Files',
          filebrowserImageUploadUrl: '/student/ckfinder/core/connector/php/uploader.php?command=QuickUpload&type=Images',

          height: 560,

          removeDialogTabs: 'image:advanced;link:advanced'
        });
    }


    // Multi-select
    $('.selectize').selectize({
        // Needed to carry over HTML data attributed like "data-interview" for status dropdown
        // from https://github.com/selectize/selectize.js/issues/239#issuecomment-73681922
        onInitialize: function() {
            var s = this;
            this.revertSettings.$children.each(function() {
                $.extend(s.options[this.value], $(this).data());
            });
        }
    });

    // $('.date-picker').datepicker({ minDate: 0 });
    $('.date-picker').datepicker();
});

function openDatePicker() {
    var input = $(event.target).closest('.input-group').find('.date-picker');
    input.datepicker('show');
}

// Popovers
$(function() {
    $('[data-toggle="popover"]').popover()
})

$(function() {
    $('[data-toggle="tooltip"]').tooltip()
})

$('.modal').on('hidden.bs.modal', function() {
    clearModals();
})

$('.modal').on('shown.bs.modal', function() {
    $(document).off('focusin.bs.modal');
})

$('.open-date-picker').click(function (event) {
    var input = $(event.target).closest('.input-group').find('.date-picker');
    input.datepicker('show');
});

function startLoad() {
    // Global AJAX settings - catches PHP errors
    $(document).ajaxError(function() {
        showModalAlert('error', 'Sorry, an unknown error occured');
        stopLoad();
    });

    hideModalAlert();
    showModalAlert('loading', 'Working on it ...');
    $('.modal-footer .btn-primary').addClass('disabled');
    $('.modal-footer .btn-primary').attr('disabled', true);
}

function stopLoad() {
    hideModalAlert('loading');
}

function showModalAlert(type, message) {
    if (type == 'error') {
        type = 'danger';
    }
    if(type == 'danger') {
        $('.modal-footer .btn-primary').removeClass('disabled');
        $('.modal-footer .btn-primary').attr('disabled', false);
    }

    if ($('.modal-alert.alert-' + type)) {
        $('.modal-alert.alert-' + type).show();
        $('.modal-message-' + type).html(message)
    }
}

function hideModalAlert(type) {
    if(!type) {
        $('.modal-alert').hide();
    }
    if (type == 'error') {
        type = 'danger';
    }
    if ($('.modal-alert.alert-' + type)) {
        $('.modal-alert.alert-' + type).hide();
    }
}

function clearModals() {
    $('.modal .alert').hide();
    // Clear modal input elements
    var inputs = $('.modal :input');
    for (var i = 0; i < inputs.length; i++) {
        if ($(inputs[i]).is('[type=radio]') || $(inputs[i]).is('[type=checkbox]')) {
            // If it's a radio or checkbox, uncheck
            $(inputs[i]).prop('checked', false);
        } else {
            // Else just clear the value
            $(inputs[i]).val('');
        }
    }
    $('.modal-footer .btn-primary').removeClass('disabled');
    $('.modal-footer .btn-primary').attr('disabled', false);

    var selectizes = $('.modal .selectized');
    for (var i = 0; i < selectizes.length; i++) {
        selectizes[i].selectize.clear();
    }

    // CKEditor clear
    if (typeof ckeditorContact != "undefined") {
        ckeditorContact.setData('');
        ckeditorContact.setReadOnly(false);
    }

    $('.hide-on-close').hide();
    $('.modal #from').val(my_email);
}

// Staff top bar - recent account selected
function switchAccount() {
    var emp_id = $('#recent-accounts').val();
    window.location.href = window.location.origin + "/member/jobs/dashboard/" + emp_id;
}

// Staff top bar - mobile device accordion
function toggleStaffBar() {
    var form = $('.staff-bar-mobile-accordion');
    var icon = $('.staff-bar-expand .fe');

    if ($(form).is(':visible')) {
        $(form).slideUp();
        $(icon).removeClass('fe-chevron-up').addClass('fe-chevron-down');
    } else {
        $(form).slideDown();
        $(icon).removeClass('fe-chevron-down').addClass('fe-chevron-up');
    }
}