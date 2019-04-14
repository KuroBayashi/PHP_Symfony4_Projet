'use strict';

// CSS
require('../../scss/pages/app_index.scss');

// JS
const $ = require('jquery');
const StreetMap = require('../components/streetmap');


$(document).ready(() => {
    let streetMap = new StreetMap();
    streetMap.init('map');
    streetMap.geolocalise();
});