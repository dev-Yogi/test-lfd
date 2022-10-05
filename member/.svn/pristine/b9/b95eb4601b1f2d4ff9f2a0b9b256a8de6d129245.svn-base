var subcategories = {
    "science": [
        "Agriculture",
        "Animal",
        "Environmental",
        "Chemistry",
        "Health",
        "Biology",
        "Physical",
        "Social",
        "Aeronautics & Aviation",
        "Astronomy",
    ],
    "technology": [
        "Coding",
        "Design",
        "Data",
        "Operations/Leadership",
        "Infrastructure",
    ],
    "engineering": [
        "Civil",
        "Electric",
        "Physical",
        "Mechanical",
        "Computer",
        "Environmental",
        "Industrial",
    ],
    "mathematics": [
        "Math",
        "Probability & Statistics",
        "Geometry",
        "Algebra",
        "Calculus",
        "Financial",
    ]
}
var organization_selectize;
var department_selectize;
$(document).ready(function() {

    bsCustomFileInput.init();
    
    var first_run_through_checked_names = [];
    var first_run_through = true;

    // $('[name=location]').selectize();
    organization_selectize = $('[name=organization]').selectize({
        create: true
    });
    if (previous_input_organization) {
        organization_selectize[0].selectize.registerOption({text:previous_input_organization, value:previous_input_organization});
        organization_selectize[0].selectize.addItem(previous_input_organization);
    }
    department_selectize = $('[name=department]').selectize({
        create: true
    });
    if (previous_input_department) {
        department_selectize[0].selectize.registerOption({text:previous_input_department, value:previous_input_department});
        department_selectize[0].selectize.addItem(previous_input_department);
    }
    $('.date-picker').datepicker();


    $("[name=category]").change(function() {
        $("[name=subcategory]").empty();
        var category = $("[name=category]").val();
        if (category) {
            for (var i = subcategories[category].length - 1; i >= 0; i--) {
                var option = new Option(subcategories[category][i]);
                $(option).html(subcategories[category][i]);
                $("[name=subcategory]").append(option);
            }
        }

    });

    $(":input").change(function() {
        var name = $(this).attr("name");
        var elements = $("[data-expand='" + name + "']");
        // Need to store checked input names when we go over radio selections

        if (elements) {
            for (var i = elements.length - 1; i >= 0; i--) {
                var conditions = $(elements[i]).data("expandIf").toString().split(",");
                var matches_condition = false
                var input_name = $(this).attr('name');
                for (var j = conditions.length - 1; j >= 0; j--) {
                    if ($(this).is(':checkbox') || $(this).is(':radio')) {
                        var value = $('[name=' + input_name + ']:checked').val();
                        if ((first_run_through && !first_run_through_checked_names.includes($(this).attr('name'))) || !first_run_through) {
                            if (value == conditions[j] && $(this).is(":checked")) {
                                matches_condition = true
                                first_run_through_checked_names.push($(this).attr('name'));
                            }
                        } else {
                            if (value == conditions[j]) {
                                matches_condition = true;
                                continue;
                            }
                        }
                    } else {
                        var value = $(this).val().toString();
                        if (value == conditions[j]) {
                            matches_condition = true
                        }
                    }
                }
                if (matches_condition) {
                    $(elements[i]).slideDown('fast');
                } else {
                    $(elements[i]).slideUp('fast');
                }
            }

        }
    });

    var inputs = $(":input");
    for (var i = inputs.length - 1; i >= 0; i--) {
        $(inputs[i]).trigger("change");
    }
    first_run_through = false

    if (subcategory) {
        $("[name=subcategory]").val(subcategory);
    }
});