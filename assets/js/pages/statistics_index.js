'use strict';

require('../../scss/pages/statistics_index.scss');
const $ = require('jquery');



	var datar = [];
	function getUtilNb(){
	
		$.getJSON("/api/defibrillator/utilizations", function(vals)
		{
		    //console.log(vals);
            $.each(vals,function(index,value){
                datar.push({ defib_id: value[0].id , frequency_util: parseInt(value.utilization_count), available: value[0].available});
            });
		});
		console.log(datar);
		populate(data_old);

    }


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
        return "<strong>Utilisations :</strong> <span style='color:#ff0000'>" + d.frequency_util + "</span>";
    })

var svg = d3.select("body").append("svg")
    .attr("width", width + margin.left + margin.right)
    .attr("height", height + margin.top + margin.bottom)
    .append("g")
    .attr("transform", "translate(" + margin.left + "," + margin.top + ")");

svg.call(tip);

var data_old = [
    {defib_id: 35, frequency_util: 13, available: true},
    {defib_id: 47, frequency_util: 11, available: true},
    {defib_id: 80, frequency_util: 11, available: true},
    {defib_id: 83, frequency_util: 10, available: true},
    {defib_id: 41, frequency_util: 9, available: true},
    {defib_id: 79, frequency_util: 9, available: true},
    {defib_id: 3, frequency_util: 8, available: false},
    {defib_id: 20, frequency_util: 8, available: true},
    {defib_id: 72, frequency_util: 8, available: false},
    {defib_id: 21, frequency_util: 7, available: true},
    {defib_id: 22, frequency_util: 7, available: true},
    {defib_id: 28, frequency_util: 7, available: false},
    {defib_id: 36, frequency_util: 7, available: false},
    {defib_id: 46, frequency_util: 7, available: true},
    {defib_id: 50, frequency_util: 7, available: true},
    {defib_id: 65, frequency_util: 7, available: true},
    {defib_id: 75, frequency_util: 7, available: true},
    {defib_id: 78, frequency_util: 7, available: true},
    {defib_id: 25, frequency_util: 6, available: true},
    {defib_id: 27, frequency_util: 6, available: true}
];

console.log(data_old);
function populate(data) {

    x.domain(data.map(function (d) {
        return d.defib_id;
    }));
    y.domain([0, d3.max(data, function (d) {
        return d.frequency_util;
    })]);

    svg.append("g")
        .attr("class", "x axis")
        .attr("transform", "translate(0," + height + ")")
        .call(xAxis);

    svg.append("g")
        .attr("class", "y axis")
        .call(yAxis)
        .append("text")
        .attr("transform", "rotate(-90)")
        .attr("y", 6)
        .attr("dy", ".71em")
        .style("text-anchor", "end")
        .text("Utilisations");

    svg.selectAll(".bar")
        .data(data)
        .enter().append("rect")
        .attr("class", "bar")
        .attr("x", function (d) {
            return x(d.defib_id);
        })
        .attr("width", x.rangeBand())
        .attr("y", function (d) {
            return y(d.frequency_util);
        })
        .attr("height", function (d) {
            return height - y(d.frequency_util);
        })
        .attr("disp",function(d){
            return isDispo(d);
        })
        .on('mouseover', tip.show)
        .on('mouseout', tip.hide)

}

function type(d) {
    d.frequency_util =+ d.frequency_util;
    return d;
}

function isDispo(d){
    if (d.available){
        return "isDispo";
    }
    else{
        return "isNotDispo";
    }
}


getUtilNb();








