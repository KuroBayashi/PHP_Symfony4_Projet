'use strict';

const $ = require('jquery');

$(document).ready(function () {
    let sidebar = $('#slide-out');
    let hamburger = $('#js-hamburger');

    // Show / Hide
    hamburger.on('click', function() {
        sidebar.toggleClass('is-show');

        if (sidebar.hasClass('is-show')) {
            $('body').append("<div id='sidebar-overlay'></div>");

            $('#sidebar-overlay').on('click', function() {
                sidebar.removeClass('is-show');
                $('#sidebar-overlay').remove();
            });
        }
        else {
            $('#sidebar-overlay').remove();
        }
    });

    // Accordion
    $('#slide-out .collapsible-accordion > li').on('click', function() {
        $(this).toggleClass('active');
        $(this).find('> .collapsible-header').toggleClass('active');
        $(this).find('> .collapsible-body').toggleClass('is-show');
    });
});