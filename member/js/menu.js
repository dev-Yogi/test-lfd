$(document).click(function(event) {
    if (!$(event.target).closest('.secondary-menu').length && !$(event.target).closest('.primary-menu').length) {
        $('.secondary-menu').hide();
    }
    if (!$(event.target).closest('.top-secondary-menu').length && !$(event.target).closest('.top-menu').length) {
        if (!isMenuMobile()) {
            $('.top-secondary-menu').fadeOut('fast');
        } else if (!$(event.target).closest('.mobile-auth').length) {
            $('.top-secondary-menu').fadeOut('fast');
        }
    }
    if (isMenuMobile()) {
        if (!$(event.target).closest('.primary-menu').length && !$(event.target).closest('.top-menu').length && !$(event.target).closest('.mobile-menu').length) {
            $('.primary-menu').fadeOut('fast');
        }
    }
});
$('.primary-menu > a').on('mouseenter', function(event) {
    if (!isMenuMobile()) {
        $('.secondary-menu').hide();
        $('.top-secondary-menu').hide();
        var id = $(this).attr('id');
        $('#menu-' + id).fadeIn('fast');
        $('.menu-snippet').hide();
        var first_snippet = $('#menu-' + id + ' .menu-snippet')[0];
        $(first_snippet).show();
    }
})
$('.secondary-menu').on('mouseleave', function(event) {
    if (!isMenuMobile()) {
        $('.secondary-menu').fadeOut('fast');
        $('.top-secondary-menu').fadeOut('fast');
    }
})
$('.top-menu > a').on('mouseenter', function(event) {
    if (!isMenuMobile()) {
        $('.secondary-menu').hide();
        $('.top-secondary-menu').hide();
        var id = $(this).attr('id');
        $('#menu-' + id).fadeIn('fast', function(event) {
            $('.secondary-menu').hide();
        });
    }
})
$('.top-secondary-menu').on('mouseleave', function(event) {
    if (!isMenuMobile()) {
        $('.secondary-menu').fadeOut('fast');
        $('.top-secondary-menu').fadeOut('fast');
    }
})
$('.secondary-menu a').on('mouseenter', function(event) {
    if (!isMenuMobile()) {
        var id = $(this).attr('id');
        if (id) {
            $('.menu-snippet').hide();
            $('.menu-' + id).fadeIn('fast');
        }
    }
})
$('.primary-menu > a').on('click', function(event) {
    if (isMenuMobile()) {
        var id = $(this).attr('id');
        $('#menu-' + id).fadeIn('fast', function(event) {
            $('.secondary-menu').hide();
            $('.secondary-menu').css('left', '100%');
            $('#menu-' + id).show();
            $('#menu-' + id).css('left', 0);
        });
    }
})
$('.mobile-back').on('click', function(event) {
    $('.secondary-menu').css('left', '100%');
})
$('.mobile-menu').on('click', function(event) {
    if ($('.primary-menu').is(':visible')) {
        $('.primary-menu').fadeOut('fast');
    } else {
        $('.primary-menu').fadeIn('fast');
    }
})
$('.mobile-auth').on('click', function(event) {
    var id = $(this).attr('id');
    if (id) {
        if ($('.top-secondary-menu').is(':visible')) {
            $('.top-secondary-menu').fadeOut('fast');
        } else {
            $('#menu-' + id).fadeIn('fast');
        }
    }
})

function isMenuMobile() {
    return $('.mobile-menu:visible').length;
}