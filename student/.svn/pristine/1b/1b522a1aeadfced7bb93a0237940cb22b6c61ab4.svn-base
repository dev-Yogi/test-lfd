$(document).ready(function () {
    $('.date-picker').datepicker();
    $('[data-toggle="tooltip"]').tooltip();
})

$('.modal').on('hidden.bs.modal', function () {
    clearModals();
})

$('.modal').on('shown.bs.modal', function () {
    $(document).off('focusin.bs.modal');
})

function startLoad() {
    // Global AJAX settings - catches PHP errors
    $(document).ajaxError(function () {
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
    if (type == 'danger') {
        $('.modal-footer .btn-primary').removeClass('disabled');
        $('.modal-footer .btn-primary').attr('disabled', false);
    }

    if ($('.modal-alert.alert-' + type)) {
        $('.modal-alert.alert-' + type).show();
        $('.modal-message-' + type).html(message)
    }
}

function hideModalAlert(type) {
    if (!type) {
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
}