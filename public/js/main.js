$(function ($) {
    'use strict';

    $('.arrow').click(function (e) {
        if ($(this).closest('li').hasClass('showMenu')) {
        $(this).closest('li').removeClass('showMenu');
        } else {
        $(this).closest('li').addClass('showMenu');
        }
    });

    $('.sidebar')
        .on('mouseover', function () {
        $(this).removeClass('close');
        })
        .on('mouseout', function () {
        $(this).addClass('close');
        });

    $(document).ready(function () {
        function toggleNavbarMethod() {
            if ($(window).width() > 992) {
                $('.navbar .dropdown')
                .on('mouseover', function () {
                    $('.dropdown-toggle', this).trigger('click');
                })
                .on('mouseout', function () {
                    $('.dropdown-toggle', this).trigger('click').blur();
                });
            } else {
                $('.navbar .dropdown').off('mouseover').off('mouseout');
            }
        }
        toggleNavbarMethod();
        $(window).resize(toggleNavbarMethod);
    });

    // Skills
    $('.skills').waypoint(
        function () {
            $('.progress .progress-bar').each(function () {
                $(this).css('width', $(this).attr('aria-valuenow') + '%');
            });
        },
        { offset: '80%' }
    );

    // Back to top button
    $(window).scroll(function () {
        console.log(123);
        if ($(this).scrollTop() > 100) {
            $('.back-to-top').fadeIn('slow');
        } else {
            $('.back-to-top').fadeOut('slow');
        }
    });
    $('.back-to-top').click(function () {
        $('html, body').animate({ scrollTop: 0 }, 1500, 'easeInOutExpo');
        return false;
    });
});
