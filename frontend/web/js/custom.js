var _days = 'Days';
var _hours = 'Hours';
var _minutes = 'Minutes';
var _seconds = 'Seconds';
var _messageAfterCount = 'The course has Started!';

var $ = jQuery.noConflict();
$(document).ready(function($) {
    "use strict";

    if (location.hash) {
        window.scrollTo(0, 0);
        setTimeout(function() {
            window.scrollTo(0, 0);
        }, 1);
    }

//  Homepage Slider (Flex Slider)

    if ($('.flexslider').length > 0) {
        $('.flexslider').flexslider({
            controlNav: false,
            prevText: "",
            nextText: ""
        });
    }

//  Open tab from another page

    function showBootstrapTab(hash) {
        if (!hash || hash === '#') {
            return;
        }

        var tabTrigger;

        try {
            tabTrigger = document.querySelector('#tabs a[href="' + hash + '"], #tabs a[data-bs-target="' + hash + '"]');
        } catch (e) {
            return;
        }

        if (!tabTrigger || typeof bootstrap === 'undefined' || !bootstrap.Tab) {
            return;
        }

        bootstrap.Tab.getOrCreateInstance(tabTrigger).show();
    }

    $('a[data-bs-toggle="tab"], a[data-toggle="tab"]').on('show.bs.tab', function(e) {});

    showBootstrapTab(location.hash);

    $('.secondary-navigation li a').on('click',function (e) {
        showBootstrapTab(this.hash);
    });

//  Table Sorter
    if ($('.tablesorter').length > 0) {
        $(".course-list-table").tablesorter();
    }

//  Rating

    if ($('.rating-individual').length > 0) {
        $('.rating-individual').raty({
            path: 'assets/img',
            readOnly: true,
            score: function() {
                return $(this).attr('data-score');
            }
        });
    }

    if ($('.rating-user').length > 0) {
        $('.rating-user .inner').raty({
            path: 'assets/img',
            starOff : 'big-star-off.png',
            starOn  : 'big-star-on.png',
            width: 180,
            target : '#hint',
            targetType : 'number',
            targetFormat : 'Rating: {score}',
            click: function(score, evt) {
                alert("Your Rating: " + score + "\nThank You!");
            }
        });
    }

//  Checkbox styling

    if ($('.checkbox').length > 0) {
        $('input').iCheck();
    }

// Disable input on count down

    $('.knob').prop("disabled", true);


//  Count Down - Landing Page

    if ($('.count-down').length > 0) {
        $(".count-down").ccountdown(2014,12,24,'18:00');
    }

//  Selectize

    if ($.fn.selectize) {
        $('select').selectize();
    }

//  Center Slide Vertically

    $('.flexslider').each(function () {
        var slideHeight = $(this).height();
        var contentHeight = $('.flexslider .slides li .slide-wrapper').height();
        var padTop = (slideHeight / 2) - (contentHeight / 2);
        $('.flexslider .slides li .slide-wrapper').css('padding-top', padTop);
    });

//  Slider height on small screens

    if (document.documentElement.clientWidth < 991) {
        $('#landing-page-head-image').css('height', $(window).height());
        $('.flexslider').css('height', $(window).height());
    }

//  Homepage Banner Slider (bundled Owl Carousel 1.x with Owl 2.x-compatible options)

    if ($('.homepage-banner-carousel').length > 0) {
        $('.homepage-banner-carousel').owlCarousel({
            items: 1,
            loop: $('.homepage-banner-carousel .homepage-banner-slide').length > 1,
            margin: 0,
            autoplay: true,
            autoplayTimeout: 6000,
            autoplayHoverPause: true,
            smartSpeed: 700,
            nav: true,
            dots: true,
            navText: ['‹', '›'],
            // Owl Carousel 1.x fallbacks kept for older bundled assets.
            singleItem: true,
            autoPlay: 6000,
            stopOnHover: true,
            navigation: true,
            pagination: true,
            navigationText: ['‹', '›']
        });
    }

    if ($('.meros-comments-carousel').length > 0) {
        $('.meros-comments-carousel').each(function () {
            var $carousel = $(this);
            var hasMultipleSlides = $carousel.children('.meros-comment-slide').length > 1;

            $carousel.owlCarousel({
                items: 1,
                singleItem: true,
                autoPlay: hasMultipleSlides ? 7000 : false,
                stopOnHover: true,
                navigation: false,
                pagination: hasMultipleSlides,
                rewindNav: true,
                itemsDesktop: [1199, 1],
                itemsDesktopSmall: [991, 1],
                itemsTablet: [767, 1],
                itemsMobile: [479, 1]
            });
        });
    }

//  Homepage Carousel

    $(".image-carousel").owlCarousel({
        items: 1,
        loop: true,
        autoplay: true,
        autoplayHoverPause: true,
        nav: true,
        dots: true,
        navText: ['‹', '›'],
        // Owl Carousel 1.x fallbacks kept for older bundled assets.
        autoPlay: true,
        stopOnHover: true,
        navigation: true,
        navigationText: ['‹', '›'],
        responsiveBaseWidth: ".image-carousel-slide"
        //responsiveBaseWidth: ".author"
    });

//  Subscription Plans Carousel

    if ($('.subscription-plans-carousel').length > 0) {
        $('.subscription-plans-carousel').owlCarousel({
            items: 3,
            loop: true,
            margin: 24,
            autoplay: true,
            autoplayTimeout: 4500,
            autoplayHoverPause: true,
            smartSpeed: 650,
            nav: true,
            dots: true,
            navText: ['‹', '›'],
            responsive: {
                0: { items: 1 },
                768: { items: 2 },
                992: { items: 3 }
            },
            // Owl Carousel 1.x fallbacks kept for older bundled assets.
            autoPlay: 4500,
            stopOnHover: true,
            navigation: true,
            pagination: true,
            navigationText: ['‹', '›'],
            itemsDesktop: [1199, 3],
            itemsDesktopSmall: [991, 2],
            itemsTablet: [767, 1],
            itemsMobile: [479, 1]
        });
    }

//  Smooth Scroll

    $('.navigation-wrapper .nav a[href^="#"], a[href^="#"].roll').on('click',function (e) {
        var target = this.hash;

        if (!target || target === '#') {
            return;
        }

        var $target = $(target);

        if (!$target.length) {
            return;
        }

        e.preventDefault();
        $('html, body').stop().animate({
            'scrollTop': $target.offset().top
        }, 2000, 'swing', function () {
            window.location.hash = target;
        });
    });

//  Fixed Navigation After Scroll

//    if (document.documentElement.clientWidth > 768) {
//        $(window).scroll(function () {
//            if ($(window).scrollTop() > 50) {
//                $('.page-landing-page .primary-navigation-wrapper').addClass('navigation-fixed');
//            } else {
//                $('.page-landing-page .primary-navigation-wrapper').removeClass('navigation-fixed');
//            }
//        });
//    }


//  author Carousel (Owl Carousel)

    $(".author-carousel").owlCarousel({
        items: 1,
        loop: false,
        autoplay: false,
        autoplayHoverPause: true,
        dots: true,
        // Owl Carousel 1.x fallbacks kept for older bundled assets.
        autoPlay: false,
        stopOnHover: true,
        responsiveBaseWidth: ".author"
    });

//  Equal Rows

    if(document.documentElement.clientWidth > 991) {
        //$('.row').equalHeights();
    }

    $( document.body ).on( 'click', '.dropdown-menu li', function( event ) {
        var $target = $( event.currentTarget ),
            dropdownToggle = $target.closest( '.btn-group' ).children( '.dropdown-toggle' )[0];

        $target.closest( '.btn-group' )
            .find( '[data-bind="label"]' ).text( $target.text() );

        if (dropdownToggle && typeof bootstrap !== 'undefined' && bootstrap.Dropdown) {
            bootstrap.Dropdown.getOrCreateInstance(dropdownToggle).toggle();
        }

        return false;
    });

//  Slider Subscription Form

    $("#slider-submit").bind("click", function(event){
        $("#slider-form").validate({
            submitHandler: function() {
                $.post("slider-form.php", $("#slider-form").serialize(),  function(response) {
                    $('#form-status').html(response);
                    $('#submit').attr('disabled','true');
                });
                return false;
            }
        });
    });

//  Contact Form with validation

    $("#submit").bind("click", function(event){
        $("#contactform").validate({
            submitHandler: function() {
                $.post("contact.php", $("#contactform").serialize(),  function(response) {
                    $('#form-status').html(response);
                    $('#submit').attr('disabled','true');
                });
                return false;
            }
        });
    });

//  Landing Page Form

    $("#landing-page-submit").bind("click", function(event){
        $("#form-landing-page").validate({
            submitHandler: function() {
                $.post("landing-page-form.php", $("#form-landing-page").serialize(),  function(response) {
                    $('#form-status').html(response);
                    $('#submit').attr('disabled','true');
                });
                return false;
            }
        });
    });

//  Vanilla Box

    if ($('.image-popup').length > 0) {
        $('a.image-popup').vanillabox({
            animation: 'default',
            type: 'image',
            closeButton: true,
            repositionOnScroll: true
        });
    }

//  Calendar

    if ($('.calendar').length > 0) {
        $('.calendar').fullCalendar({
            firstDay: 1,
            weekMode: 'variable',
            contentHeight: 700,
            header: {
                right: 'month,basicWeek,basicDay prev,next'
            },

            events: "events.php"

        });
    }

//  Event title shorting

    $('.fc-view-month .fc-event-title').each(function(){
        $(this).text($(this).text().substring(0,25));
    });

});


// Remove button function for "join to course" button after count down is over

function disableJoin() {
    // Find "join to course" button
    var buttonToBeRemoved = document.getElementById("btn-course-join");
    // Find "join to course" button on bottom of course detail
    var buttonToBeRemovedBottom = document.getElementById("btn-course-join-bottom");
    // Remove button
    if (buttonToBeRemoved) {
        buttonToBeRemoved.remove();
    }
    // Remove button on the bottom
    if (buttonToBeRemovedBottom) {
        buttonToBeRemovedBottom.remove();
    }
    // Give the ".course-count-down" element new class to hide date
    var courseCountDown = document.getElementById("course-count-down");
    var courseStart = document.getElementById("course-start");
    if (courseCountDown) {
        courseCountDown.className += " disable-join";
    }
    if (courseStart) {
        courseStart.className += " disable-join";
    }
}

//  Count Down - Course Detail

if (typeof _date != 'undefined') { // run function only if _date is defined
    var Countdown = new Countdown({
        dateEnd: new Date(_date),
        msgAfter: _messageAfterCount,
        onEnd: function() {
            disableJoin(); // Run this function after count down is over
        }
    });
}
//  Modern section reveal animations for redesigned pages
(function () {
    var revealItems = document.querySelectorAll('.reveal-section');

    if (!revealItems.length) {
        return;
    }

    if (!('IntersectionObserver' in window)) {
        revealItems.forEach(function (item) {
            item.classList.add('is-visible');
        });
        return;
    }

    var revealObserver = new IntersectionObserver(function (entries) {
        entries.forEach(function (entry) {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                revealObserver.unobserve(entry.target);
            }
        });
    }, {
        threshold: 0.16,
        rootMargin: '0px 0px -8% 0px'
    });

    revealItems.forEach(function (item) {
        revealObserver.observe(item);
    });
}());
