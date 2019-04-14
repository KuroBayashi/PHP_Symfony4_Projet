'use strict';

const $ = require("jquery");

$(document).ready(function () {
    $('#js-layout-header-user-link').on('click', (e) => {
        e.preventDefault();

        $('#js-layout-header-user-card').toggleClass('is-show');
    });
});