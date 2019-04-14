'use strict';

// CSS
require('../../scss/pages/defibrillator_index.scss');

// JS
const $ = require('jquery');
require('mdbootstrap/js/addons/datatables.min.js');

const StreetMap = require('../components/streetmap');


$(document).ready(() => {
    let streetMap = new StreetMap();
    streetMap.init('map', StreetMap.TYPES.DEFIBRILLATOR_EDIT);
    streetMap.geolocalise();

    $('#defibrillator-table').DataTable();
    $('.dataTables_length').addClass('bs-select');
});
