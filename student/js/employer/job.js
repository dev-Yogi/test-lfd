var occupations = [];
$(document).ready(function() {

    $('#city_st').selectize({
        valueField: 'city',
        labelField: 'city',
        searchField: 'city',
        options: [],
        persist: false,
        loadThrottle: 600,
        create: false,
        allowEmptyOption: true,
        load: function(query, callback) {
            if (!query.length) return callback();
            $.ajax({
                url: base_url + 'ajax/get_locations_autocomplete',
                type: 'POST',
                dataType: 'json',
                data: {
                    location: query
                },
                error: function() {
                    callback();
                },
                success: function(res) {
                    callback(res);
                }
            });
        }
    });

    // Description WSIWYG
    ckeditor = CKEDITOR.replace( 'description_ckeditor', {
        customConfig: base_url + 'js/ckeditor/careerlink-config.js',
        toolbar: 'ClinkToolbarSource',
        height: 320,
        resize_minWidth: 620,
        resize_maxWidth: 873
    });
    ckeditor.on('change',function(){
        $('[name=desc]').val(ckeditor.getData());
        $('[name=desc]').trigger('change');
    });

    $('#skills_select').selectize({
        valueField: 'id',
        labelField: 'title',
        searchField: 'title',
        options: [],
        persist: false,
        loadThrottle: 200,
        create: false,
        maxItems: 10,
        allowEmptyOption: true,
        load: function(query, callback) {
            if (!query.length) return callback();``
            $.ajax({
                url: base_url + 'ajax/get_skills_autocomplete',
                type: 'POST',
                dataType: 'json',
                data: {
                    skill: query
                },
                error: function() {
                    callback();
                },
                success: function(res) {
                    callback(res);
                }
            });
        }
    });
    
    // Initialize occupations drop-down
    occupations = $("#occupations > option").map(function() {
        var opt = {
            pathway: $(this).data('pathway'),
            occupation_name: $(this).text().trim(),
            occupation_code: $(this).val().trim(),
            career_code: $(this).data('career-code'),
            career_name: $(this).data('career-name')
        };
        return opt;
    }).get();
    $('#occupations').selectize({
        labelField: 'occupation_name',
        valueField: 'occupation_code',
        searchField: ['occupation_name'],
        options: occupations,
        create: false,
        maxItems: 8,
        render: {
            option: function(item, escape) {
                return '<div>' + '<div><strong>' + item.occupation_name + '</strong></div> ' + '<small class="text-muted">' + item.pathway + '</small> ' + '</div>';
            }
        }
    });
    
    // Coming from form edit
    if ($('#occupation_set').val()) {
        populateSpecialtiesDropdown();
    }

    // Coming from form edit
    if ($('#skills').val()) {
        populateSkillsDropdown();
    }

    populateSkillCodesField();
    // populateSpecialtyCodesField();
    populateCityState();
});

$('select#occupations').change(function() {
    populateSpecialtyCodesField();
});
$('select#skills_select').change(function() {
    populateSkillCodesField();
});
$('[name=zip]').change(function() {
    populateCityState();
});

// Coming from form edit - Put in specalties
function populateSpecialtiesDropdown() {
    var selected = $('#occupation_set').val().split(',');
    for (var i = 0; i < selected.length; i++) {
        $('#occupations')[0].selectize.addItem(selected[i]);
    }
}

// Read selectize's entries and write them into JSON, since that's what the job post function wants
function populateSkillCodesField() {
    var skills = $('#skills_select').val();
    var comma_delimited_skills = "";
    for (var i = 0; i < skills.length; i++) {
        comma_delimited_skills += skills[i] + ",";
    }
    $('#skills').val(comma_delimited_skills);
}

// Coming from form edit - Put in skills
function populateSkillsDropdown() {
    var skill_ids = $('#skills').val();
    skill_ids = skill_ids.split(',');

    $.ajax({
        url: base_url + 'ajax/get_skills_by_ids',
        type: 'POST',
        dataType: 'json',
        data: {
            skill_ids: skill_ids
        },
        success: function(response) {
            for (var i = 0; i < response.length; i++) {
                $('#skills_select')[0].selectize.addOption(response[i]);
                $('#skills_select')[0].selectize.addItem(response[i].id);
            }
        }
    });
}

// Read selectize's entries and write them into JSON, since that's what the job post function wants
function populateSpecialtyCodesField() {
    var occupation_codes = $('#occupations').val();
    var comma_delimited_occupations = "";
    for (var i = 0; i < occupation_codes.length; i++) {
        var occupation = occupations.find(function(element) {
            return element.occupation_code == occupation_codes[i];
        });
        comma_delimited_occupations += occupation.occupation_code + ",";
    }
    if(comma_delimited_occupations.length) {
        $('#occupation_set').val(comma_delimited_occupations);
    } else {
        $('#occupation_set').val('');
    }
    $('#occupation_set').trigger('change');
}

// Loop up the zipcode and populate the city state
function populateCityState() {
    var zipcode = $('[name=zip]').val();
    zipcode = zipcode.replace(/\D/g,'');
    if (zipcode.length > 4) {
        $.get(base_url + 'ajax/get_city_state_by_zip/' + zipcode, function(data) {
            $('#city_st')[0].selectize.addOption({city: data});
            $('#city_st')[0].selectize.addItem(data);
            $('[name=location]').trigger('change');
        });
    }
}

// Validate form
function validate(scroll_up) {
    var valid = true;
    $('.is-invalid').removeClass('is-invalid');
    $('.invalid-feedback').remove();
    var required = ['title', 'zip', 'location', 'job_type', 'desc', 'occupation_set'];
    // IDs of inputs that use a library e.g. CKEditor, Selectize
    var input_utility_ids = {'location': 'city_st', 'occupation_set': 'occupations', 'description': 'description_ckeditor'};
    for (var i = 0; i < required.length; i++) {
        var input = $('[name=' + required[i] + ']');
        var label = $('[for=' + required[i] + ']').text().replace('*', '');
        // Is the answer blank?
        if (!input.val() || input.val() == '') {
            valid = false;
            input.addClass('is-invalid');
            // Does this field use a js library?
            if(!input_utility_ids[required[i]]) {
                // If it doesn't, just throw the error below the input
                $('<div class="invalid-feedback">' + label + ' is required.</div>').insertAfter(input);
            } else {
                // If it does, put the error below the generated field
                input = $('#' + input_utility_ids[required[i]] + ' + div');
                if(input) {
                    valid = false;
                    input.addClass('form-control is-invalid');
                    $('<div class="invalid-feedback">' + label + ' is required.</div>').insertAfter(input);
                } else {
                    console.log('Failed to display error for ' + required[i]);
                }
            }
        }
    }
    if (!valid) {
        var button = $('[type=submit]');
        button.addClass('is-invalid');
        $('<div class="invalid-feedback d-block mb-3">There are errors in the posting form. Please complete the fields marked in red.</div>').insertAfter(button);
        
        if(scroll_up) {
            $('html, body').animate({
                scrollTop: $('.invalid-feedback').offset().top - 100
            }, 1000);
        }
    }
    return valid;
}