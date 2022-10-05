
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

function openSaveSearchModal() {
    if (member_id) {
        $('#savesearch-modal').modal();
    } else {
        $('#please-log-in-modal').modal();
    }
}

function openShareJobModal(job_id) {
    $('#sharejob-modal').modal();
    var title = $('[data-job-id=' + job_id + '] h1').text() ? $('[data-job-id=' + job_id + '] h1:first').text() : $('[data-job-id=' + job_id + '] h3').text();
    $('#sharejob-modal b').text(title);
    var url = base_url + 'job/' + job_id; //'https://www.google.com/search?q=' + job_id;
    $('#share-zone').html('<iframe src="https://www.facebook.com/plugins/share_button.php?href=' + encodeURIComponent(url) + '&layout=button&size=large&width=77&height=28&appId" width="77" height="28" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true" allow="encrypted-media"></iframe>');
}

function saveJob(job_id) {
    if (!member_id) {
        $('#please-log-in-modal').modal();
        return;
    }
    var save_element = $('[data-job-id=' + job_id + '] .save-job');
    var save_text = $('[data-job-id=' + job_id + '] .save-job').text();
    var toggle = !save_text.includes('Saved');

    // Set payload
    var data = {
        job_id: job_id,
    }

    // AJAX it away
    if (toggle) {
        save_element.html('<i class="fe fe-star"></i> Saving ...');
        $.post(base_url + "search/ajax_save_job", data, function (response) {
            console.log(response);
            if (response.type == 'success') {
                save_element.html('<i class="fe fe-star"></i> Saved!');
            } else {
                save_element.html('<i class="fe fe-star"></i> Save');
            }
        });
    } else {

        save_element.html('<i class="fe fe-star"></i> Removing ...');
        $.post(base_url + "search/ajax_unsave_job", data, function (response) {
            console.log(response);
            if (response.type == 'success') {
                save_element.html('<i class="fe fe-star"></i> Save');
            } else {
                save_element.html('<i class="fe fe-star"></i> Saved');
            }
        });
    }
}

// "Save" button clicked
function submitSaveSearch() {
    // Fetch entered values from the modal
    var params = $('#save-search-params').val();
    var title = $('#search-title').val();

    // Set payload
    var data = {
        params: params,
        title: title,
    }

    // AJAX it away
    startLoad();
    $.post(base_url + "search/ajax_save_search", data, function (response) {
        console.log(response);
        if (response.type == 'success') {
            success_message = 'Search has been saved';
            showModalAlert('success', success_message);
        } else {
            showModalAlert('error', response.message ? response.message : 'Sorry, an unknown error occured');
        }
        stopLoad();
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
            if (application_ids.length > 1) {
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