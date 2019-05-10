'use strict';

require('../../scss/pages/statistics_index.scss');
const $ = require('jquery');
const StreetMap = require('../components/streetmap');

// Map
$(document).ready(() => {
    let streetMap = new StreetMap();
streetMap.init('map', StreetMap.TYPES.DEFAULT);
streetMap.geolocalise();

// Graph

let formatedData = [];
$.getJSON("/api/defibrillator/maintenances", (data) => {
    $.each(data, function(index, value) {
         formatedData.push({ defibrillator: value[0] , frequency_maintenance: parseInt(value.maintenance_count)});
     });
    console.log(data);
}).then(() => {
    console.log(formatedData);
    populate(formatedData);
});


var margin = {top: 40, right: 20, bottom: 30, left: 40},
    width = 960 - margin.left - margin.right,
    height = 500 - margin.top - margin.bottom;

var formatPercent = d3.format(".0f");

var x = d3.scale.ordinal()
    .rangeRoundBands([0, width], .1);

var y = d3.scale.linear()
    .range([height, 0]);

var xAxis = d3.svg.axis()
    .scale(x)
    .orient("bottom");

var yAxis = d3.svg.axis()
    .scale(y)
    .orient("left")
    .tickFormat(formatPercent);

var tip = d3.tip()
    .attr('class', 'd3-tip')
    .offset([-10, 0])
    .html(function(d) {
        if (!d.defibrillator.reported){
            return "<strong>" + d.frequency_maintenance + "</strong> <span style='color:#00d6ff'>" + 'OK' + "</span>";
        }else{
            return "<strong>" + d.frequency_maintenance + "</strong> <span style='color:#fff500'>" + 'Signalé' + "</span>";
        }


    });

var svg = d3.select("#graph").append("svg")
    .attr("width", width + margin.left + margin.right)
    .attr("height", height + margin.top + margin.bottom)
    .append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

svg.call(tip);

function populate(data) {

    x.domain(data.map(function (d) {
        return d.defibrillator.id;
    }));
    y.domain([0, d3.max(data, function (d) {
        return d.frequency_maintenance;
    })]);

    svg.append("g")
        .attr("class", "x axis")
        .attr("transform", "translate(0," + height + ")")
        .call(xAxis);

    svg.append("g")
        .attr("class", "y axis")
        .call(yAxis)
        .append("text")
        .attr("style", "transform: translateX(-45px) rotate(-90deg);")
        .attr("y", 6)
        .attr("dy", ".71em")
        .style("text-anchor", "end")
        .text("Maintenances");

    svg.selectAll(".bar")
        .data(data)
        .enter().append("rect")
        .attr("class", (d) => {return !d.defibrillator.reported ? "bar--blue" : "bar--yellow";})
.attr("x", function (d) {
        return x(d.defibrillator.id);
    })
        .attr("width", x.rangeBand())
        .attr("y", function (d) {
            return y(d.frequency_maintenance);
        })
        .attr("height", function (d) {
            return height - y(d.frequency_maintenance);
        })
        .on('mouseover', tip.show)
        .on('mouseout', tip.hide)
        .on('click', (e) => {
        streetMap.goTo(e.defibrillator.latitude, e.defibrillator.longitude);
})
}

});

