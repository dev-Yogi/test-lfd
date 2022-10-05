var ckeditorContact;

$(document).ready(function () {

    // Initialize the copy-to-job modal's fancy job list
    var items = $("#destination > option").map(function () {
        var opt = {
            title: $(this).text().trim(),
            job_id: $(this).data('job-id'),
            requisition: $(this).data('requisition'),
            date_posted: $(this).data('date-posted'),
            closed: $(this).data('closed'),
            responses: $(this).data('responses'),
        };
        return opt;
    }).get();
    $('#destination').selectize({
        valueField: 'id',
        labelField: 'title',
        searchField: ['title'],
        sortField: [{field: 'id', direction: 'desc'}],
        maxItems: 1,
        options: items,
        create: false,
        render: {
            option: function (item, escape) {
                var requisition = '';
                if (item.requisition) {
                    requisition = ' &middot; Requisition: ' + item.requisition;
                }
                return '<div>' +
                    '<div><strong>' + item.title + '</strong></div> ' +
                    '<div class="selectize-job-info">' +
                    '<div>Job ID: ' + item.id + requisition + '</div> ' +
                    '<div class="float-right">' + item.date_posted + ' - ' + item.closed + '</div>' +
                    '<div class="text-muted">' + item.responses + ' applicants</div>' +
                    '</div>' +
                    '</div>';
            }
        }
    });

    // Initialize the forward modal's fancy colleagues list
    var items = $("#colleagues > option").map(function () {
        var opt = {
            name: $(this).text().trim(),
            email: $(this).data('email')
        };
        return opt;
    }).get();
    $('#colleagues').selectize({
        valueField: 'email',
        labelField: 'name',
        searchField: ['name'],
        sortField: [{field: 'name', direction: 'asc'}],
        options: items,
        create: false,
        render: {
            option: function (item, escape) {
                return '<div>' +
                    '<div><strong>' + item.name + '</strong></div> ' +
                    '<div>' + item.email + '</div> ' +
                    '</div>';
            }
        }
    });

    // Contact modal WSIWYG
    ckeditorContact = CKEDITOR.replace( 'ckeditor-message', {
        toolbar: [
            { name: 'styles', items: [ 'Styles', 'Format' ] },
            { name: 'basicstyles', items: [ 'Bold', 'Underline', 'Italic', 'Strike', '-', 'RemoveFormat' ] },
            { name: 'paragraph', items: [ 'NumberedList', 'BulletedList' ] },
            { name: 'links', items: [ 'Link', 'Unlink' ] },
            { name: 'insert', items: [ 'Image', 'Table' ] },
            { name:"styles","groups":["styles"]},
        ],
        removeButtons: 'Strike,Subscript,Superscript,Styles,Specialchar',
        height: 190,
        removePlugins: 'elementspath' 
    });
    ckeditorContact.on('change',function(){
        $('#contact-message').val(ckeditorContact.getData());
        $('#contact-message').trigger('change');
    });

});

// "Set Status" button clicked
function submitStatus() {
    // Fetch entered values from the modal
    var status = $('#status-modal #status').val();
    var note = $('#status-modal #note').val();

    var application_ids = getSelectedApplications();

    // Set payload
    var data = {
        application_ids: application_ids,
        status: status,
        note: note,
        extra: {}
    }
    // Set extra interview data
    var interview = {
        date: $('#status-modal #date').val(),
        start: $('#status-modal #start').val(),
        end: $('#status-modal #end').val(),
        location: $('#status-modal #location').val(),
        forward_to: $('#status-modal #forward_to').val(),
        email_invite: $('#status-modal #email_invite').is(':checked') ? 'Y' : ''
    }
    if (interview.date) {
        data.extra.interview = interview;
    }

    if ($('#status-modal #do_not_email').is(':checked')) {
        data.extra.do_not_email = "Y";
    }

    // AJAX it away
    startLoad();
    $.post(base_url + "employer/application/ajax_set_status", data, function (response) {
        console.log(response);
        stopLoad();
        if (response.type == 'success') {
            showModalAlert('success', 'Status has been updated');
            addActivityRow('status', statuses[status].label);
            addActivityStatusRow(statuses[status].label);
            $('.current-status .status-label').text(statuses[status].label);
            if (statuses[status].color) {
            	$('.current-status .status-icon').hide();
            	$("<span class='status-icon bg-" + statuses[status].color + "'></span>").insertBefore(".current-status .status-label");

            	// Update HTML tables
	            for (var i = 0; i < application_ids.length; i++) {
	            	var application_id = application_ids[i];
	            	var app_tr = $('[data-app-id=' + application_id + ']');
	            	if (app_tr) {
	            		var html_status = '<span class="status-icon bg-' + statuses[status].color + '"></span>' + statuses[status].label;
	            		app_tr.find('.status').html(html_status);
	            		app_tr.find('.modified').html(today);
	            		app_tr.find('.modifier').html('By ' + my_name);

	            		// For job application listing
	            		app_tr.find('.modifier-name').html(my_name);
	            		app_tr.find('.modified-time').html('Just now');
	            	}
	            }
            }
        } else {
            showModalAlert('error', response.message ? response.message : 'Sorry, an unknown error occured');
        }
    });
}


// "Send" button clicked
function submitContact() {
    // Fetch entered values from the modal
    var subject = $('#contact-modal #subject').val();
    var from = $('#contact-modal #from').val();
    var message = $('#contact-modal #contact-message').val();
    // var template = $('#contact-modal #template').val();
    // var template_base = $('#contact-modal #template_base').val();

    var application_ids = getSelectedApplications();

    // Set payload
    var data = {
        application_ids: application_ids,
        subject: subject,
        from: from,
        message: message,
        // template: template,
        // template_base: template_base
    }

    // AJAX it away
    startLoad();
    $.post(base_url + "employer/application/ajax_contact", data, function (response) {
        console.log(response);
        if (response.type == 'success') {
            if(application_ids.length > 1) {
                success_message = 'Applicants have been contacted';
            } else {
                success_message = 'Applicant has been contacted';
            }
            showModalAlert('success', success_message);
            addActivityRow('contact', subject);
            addActivityContactRow(from, subject, message);
        } else {
            showModalAlert('error', response.message ? response.message : 'Sorry, an unknown error occured');
        }
        stopLoad();
    });
}

// "Forward" button clicked
function submitForward() {
    // Fetch entered values from the modal
    var colleagues = $('#forward-modal #colleagues').val();
    var to = $('#forward-modal #to').val();
    var message = $('#forward-modal #message').val();

    colleagues.map( function(email) {
        to += ';' + email;
    });

    var application_ids = getSelectedApplications();

    // Set payload
    var data = {
        application_ids: application_ids,
        to: to,
        message: message
    }

    // AJAX it away
    startLoad();
    $.post(base_url + "employer/application/ajax_forward", data, function (response) {
        console.log(response);
        stopLoad();
        if (response.type == 'success') {
            showModalAlert('success', 'Applications have been forwarded');
        } else {
            showModalAlert('error', response.message ? response.message : 'Sorry, an unknown error occured');
        }
    });
}

// "Copy" button clicked
function submitCopy() {
    var destination = $('#copy-modal #destination').val();
    var application_ids = getSelectedApplications();

    // Set payload
    var data = {
        application_ids: application_ids,
        destination: destination
    }

    // AJAX it away
    startLoad();
    $.post("/member/applications/ajax_copy/" + employer_id, data, function (response) {
        console.log(response);
        if (response.type == 'success') {
            showModalAlert('success', 'Applications have been copied');
        } else {
            showModalAlert('error', response.message ? response.message : 'Sorry, an unknown error occured');
        }
        stopLoad();
    });
}

// "Add Note" button clicked
function submitNote() {
    var note = $('#note').val();

    var application_ids = getSelectedApplications();

    // Set payload
    var data = {
        application_ids: application_ids,
        note: note
    };

    // AJAX it away
    $('#add-note-button').addClass('btn-loading');
    $.post(base_url + "employer/application/ajax_note", data, function (response) {
        $('#add-note-button').removeClass('btn-loading');
        if (response.type == 'success') {
            var target = '.notes-container';
            var html = '' +
                '<div class="card mb-3">' +
                '<div class="card-status card-status-left bg-blue"></div>' +
                '<div class="card-body">' +
                '<p class="h5 mb-2">' + my_name +
                '<small class="float-right text-muted">Just then</small>' +
                '</p>' + note +
                '</div>' +
                '</div>';
            $(target).prepend(html);
            $('#note').val('');
            $('#add-note-button').text('Added!');

            addActivityRow('note', note);
            addActivityNoteRow(note);
        } else {
            showModalAlert('error', response.message ? response.message : 'Sorry, an unknown error occured');
        }
    });
}

function openStatusModal() {
    $('#status-modal').modal();
    showApplicationCount();
}

function openForwardModal() {
    $('#forward-modal').modal();
    showApplicationCount();
}

function openCopyModal() {
    $('#copy-modal').modal();
    showApplicationCount();
}

function openContactModal() {
    $('#contact-modal').modal();
    showApplicationCount();
}

function openPrint() {
    params = '';

    var application_ids = getSelectedApplications();
    for (var i = 0; i < application_ids.length; i++) {
        params += 'application_ids[]='+application_ids[i]+'&';
    }

    window.open(base_url + "employer/application/print/" + employer_id + '?' + params);
}

// Show number of selected applicants in modals header
function showApplicationCount() {
    $('.modal-header small').remove();
    var application_ids = getSelectedApplications();
    if (application_ids.length > 1) {
        $('.modal-header h5').append(' <small class="text-muted">' + application_ids.length + ' selected</small>');
    }
}

// Return array of applications with their checkboxes ticked
// Single application view has a hidden checkbox too!
function getSelectedApplications() {
    var checked = $('.application-checkbox:checked');
    var application_ids = [];
    for (var i = 0; i < checked.length; i++) {
        var checkbox_id = $(checked[i]).attr('id');
        var application_id = checkbox_id.replace('checkbox_', '');
        application_ids.push(application_id);
    }

    return application_ids;
}

// Status modal - check if the status comes with an interview form
// or if it has the option to email the user
$('select#status').change(function () {
    var options = $('select#status')[0].selectize.options;
    var value = $('select#status').val();

    console.log(options[value]);
    if (value) {
        if (options[value].interview != '') {
            $('#status-modal .interview-form').fadeIn('fast');
        } else {
            $('#status-modal .interview-form').fadeOut('fast');
        }
        if (options[value].email != '') {
            $('#status-modal .email-form').fadeIn('fast');
        } else {
            $('#status-modal .email-form').fadeOut('fast');
        }
    }
})

// Contact modal - Load the template
$('select#template').change(function () {
    // Fetch entered values from the modal
    var template_id = $('#contact-modal #template').val();

    if(template_id) {
        var application_ids = getSelectedApplications();
        
        showModalAlert('loading', 'Loading the template ...');
        $.get('/member/applications/ajax_load_template/' + employer_id + '/' + application_ids[0] + '/' + template_id, function (response) {
            hideModalAlert('loading');
            $('#contact-modal #subject').val(response.subject);
            $('#contact-modal #from').val(response.from);
            $('#contact-modal #contact-message').val(response.message);
            $('#contact-modal #template_base').val(response.template_base);
            ckeditorContact.setData(response.message);
            if (application_ids.length > 1) {
            	ckeditorContact.setReadOnly(true);
            	$("<p class='text-muted hide-on-close small'>Template editing is disabled for batch communication.</p>").insertBefore("#contact-modal .cke");
            }
        });
    }
})

$('.application-checkbox').click(function () {
    var count = $('.application-checkbox:checked').length;
    $('.selected-count').text(count);
});

// Insert row into activity history HTML table
function addActivityRow(type, description) {
    var description_length = description.length;
    var description = description.substr(0, 30);
    if(description_length > 30) {
        description = description + " ...";
    }
    var icons = {
        'status' : '<i class="fe fe-tag text-muted"></i>',
        'contact' : '<i class="fe fe-mail text-muted"></i>',
        'note' : '<i class="fe fe-file text-muted"></i>',
    }
    var titles = {
        'status' : 'Status Changed',
        'contact' : 'Email Sent',
        'note' : 'Note Added',
    }
    var html =
    '<tr> \
        <td class="pr-0 align-top">' + icons[type] + '</td> \
        <td>' + titles[type] + '<div class="small text-muted">' + description + '</div> \</td> \
        <td><span data-toggle="tooltip" data-placement="bottom" title="Just now">Just Now</span></td> \
        <td>' + my_name + '</td> \
    </tr>';
    $('.table-all-activity > tbody').prepend(html);
    $('.table-recent-activity > tbody').prepend(html);
}

// Insert row into status history HTML table
function addActivityStatusRow(status) {
    var html =
    '<tr> \
        <td>' + status + '</td> \
        <td>' + my_name + '</td> \
        <td>Just Now</td> \
    </tr>';
    $('.table-status-history > tbody').prepend(html);
}

// Insert row into contact history HTML table
function addActivityContactRow(from, subject, message) {
    var html =
    '<div class="card"> \
        <div class="card-status card-status-left bg-blue"></div> \
        <table class="table card-table table-contact-history"> \
            <tbody> \
            <tr> \
                <th scope="row">Date</th> \
                <td>Just Now</td> \
            </tr> \
            <tr> \
                <th scope="row">Reply To</th> \
                <td>' + from + '</td> \
            </tr> \
            <tr> \
                <th scope="row">Subject</th> \
                <td>' + subject + '</td> \
            </tr> \
            <tr> \
                <th scope="row">Message</th> \
                <td> \
                    <div class="contact-history-message">' + message + '</div> \
                </td> \
            </tr> \
            </tbody> \
        </table> \
    </div>';
    $('.contact-history .no-result').hide();
    $('.contact-history').prepend(html);
}

// Insert card into notes history
function addActivityNoteRow(note) {
    var html =
    '<div class="card mb-3"> \
        <div class="card-status card-status-left bg-blue"></div> \
        <div class="card-body"> \
            <p class="h5 mb-2">' + my_name + ' \
                <small class="float-right text-muted">Just Now</small> \
            </p> \
            ' + note + ' \
        </div> \
    </div>';
    $('.notes-history .no-result').hide();
    $('.notes-history').prepend(html);
}

//select all checkbox
function selectAll(application_ids) {
    checkboxes = document.getElementsByClassName('application-checkbox');
    for (var i = 0, n = checkboxes.length; i < n; i++) {
        checkboxes[i].checked = application_ids.checked;
    }
}