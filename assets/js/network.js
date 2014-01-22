"use strict";

var cy;

function selectAll() {
	cy.nodes().select();
}

function selectNone() {
	cy.nodes().unselect();
}

function expandNode(json) {
	// Calculate the positions of the objects
	var n_nodes = json.nodes.length;
	var r = 100;
	var x = this.position().x;
	var y = this.position().y;
	var angle_incr = (2 * Math.PI) / n_nodes;
	for (var i = 0; i < n_nodes; i++) {
		var angle = angle_incr * i;
		var x_offset = Math.cos(angle) * r;
		var y_offset = Math.sin(angle) * r;
		json.nodes[i].position = { 'x': x + x_offset, 'y': y + y_offset };
	}
	// Remove duplicate edges
	var expand_threshold = $('#expand-threshold').val();
	cy.remove(this.neighborhood().edges("[weight>"+expand_threshold+"]"));
	cy.add(json);
}

$(function () {
	$('#network-container').cytoscape({
		elements: network_data,

		layout: {
			name: 'arbor',
			liveUpdate: false,
			maxSimulationTime: 2000,
			fit: true,
			padding: [50, 50, 50, 50],
			ungrabifyWhileSimulating: true,
			stableEnergy: function (energy) {
				var e = energy;
				return (e.max <= 0.5) || (e.mean <= 0.3);
			}
		},

		zoomingEnabled: true,

		style: cytoscape.stylesheet()
			.selector('node')
				.css({
					'content': 'data(orf)',
					'text_align': 'center',
					'font-family': 'helvetica, arial, sans-serif',
					'border-width': 1,
					'background-color': '#12AA12',
					'cursor': 'pointer',
					'shape': 'ellipse',
					'width': 20,
					'height': 20,
					'font-size': 15
				})
			.selector('node.basket')
				.css({
					'background-color': '#3995FF'
				})
			.selector('node.tf')
				.css({
					'shape': 'rectangle',
					'background-color': '#B2A72C'
				})
			.selector('node:selected')
				.css({
					'border-color': '#D72A1D',
					'border-width': 2,
					'box-shadow': '2px 2px 2px 2px black'
				})
			.selector('edge')
				.css({
					'opacity': 0.5,
					'width': 'mapData(weight, 0, 20, 0.5, 8)',
					'line-color': '#666'
					// 'line-color': 'mapData(weight, 0, 20, blue, red)'
				})
		,

		ready: function () {
			console.log('network is ready');
			cy = this;
		},
		done: function () {
			console.log('layout done');
		}
	});

	$('#network-container').cytoscapePanzoom({
		autodisableForMobile: true
	});

	$('#network-container').cytoscapeCxtmenu({
	    menuRadius: 80,
	    selector: 'node',
	    commands: [
	        {
	            content: 'Log ID',
	            select: function () {
	                console.log(this.id());
	            }
	        }, {
	            content: 'Expand',
	            select: function () {
	                console.log('Expanding neighborhood...');
	                $.ajax({
	                	url: 'api/network_neighbors/' + this.data().orf + '/' + $('#expand-threshold').val() + '/' + $('#network-type').val(),
	                	type: 'GET',
	                	datatype: 'json',
	                	context: this,
	                	success: expandNode
	                });
	            }
	        }, {
	        	content: 'Select',
	        	select: function () {
	        		if (this.selected()) {
	        			this.unselect();
	        		} else {
	        			this.select();
	        		}
	        	}
	        }, {
	        	content: 'Annotation',
	        	select: function () {
	        		console.log('Annotation');
	        	}
	        }
	    ], 
	    fillColor: 'rgba(0, 0, 0, 0.75)',
	    activeFillColor: 'rgba(40, 187, 91, 0.75)',
	    activePadding: 5,
	    indicatorSize: 18,
	    separatorWidth: 3,
	    spotlightPadding: 3,
	    minSpotlightRadius: 24,
	    maxSpotlightRadius: 38,
	    itemColor: 'white',
	    itemTextShadowColor: 'black',
	    zIndex: 9999
	});

	// Button listeners
	$('#select-all').click(selectAll);
	$('#select-none').click(selectNone);
});
