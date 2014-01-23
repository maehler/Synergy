"use strict";

var cy;

function redraw() {
	cy.forceRender();
}

function selectAll() {
	cy.nodes().select();
}

function selectNone() {
	cy.nodes().unselect();
}

function selectNeighbors() {
	cy.nodes(':selected').nodes().neighborhood().nodes().select();
}

function removeSelected() {
	cy.remove(cy.nodes(':selected'));
	updateCount();
}

function invertSelection() {
	var selected = cy.nodes(':selected');
	selectAll();
	selected.unselect();
}

function updateCount() {
	var no_nodes = cy.nodes().length;
	var no_edges = cy.edges().length;
	$('#node-count').html(no_nodes);
	$('#edge-count').html(no_edges);
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
	cy.add(json);
	// Nodes are now added, but not all edges. Do that now.
	var ids = [];
	$.each(cy.nodes(), function (i, element) {
		ids.push(element.id());
	});
	$.ajax({
		url: 'api/network_edges',
		type: 'POST',
		datatype: 'json',
		data: {
			genes: ids,
			th: expand_threshold,
			ntype: $('#network-type').val()
		},
		success: function (edges) {
			cy.remove(cy.edges("[weight>"+expand_threshold+"]"));
			cy.add(edges);
			updateCount();
			// For some reason the edges aren't drawn straight away,
			// and can't seem to force a redraw directly, but with
			// a small delay it works...
			window.setTimeout(redraw, 1);
		}
	});
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
					'background-color': '#AAA',
					'cursor': 'pointer',
					'shape': 'ellipse',
					'width': 20,
					'height': 20,
					'font-size': 15
				})
			.selector('node.basket')
				.css({
					'background-color': '#12AA12'
				})
			.selector('node.tf')
				.css({
					'shape': 'rectangle'
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
				})
		,

		ready: function () {
			console.log('network is ready');
			cy = this;
			updateCount();
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
	            content: 'Delete',
	            select: function () {
	            	cy.remove(this);
	            }
	        }, {
	            content: 'Expand',
	            select: function () {
	                console.log('Expanding neighborhood...');
	                $.ajax({
	                	url: 'api/network_neighbors/' + 
	                		this.data().orf + '/' + 
	                		$('#expand-threshold').val() + '/' + 
	                		$('#network-type').val(),
	                	type: 'GET',
	                	datatype: 'json',
	                	context: this,
	                	success: expandNode
	                });
	            }
	        }, {
	        	content: 'Toggle basket',
	        	select: function () {
	        		$.ajax({
	        			url: 'api/update_basket',
	        			type: 'GET',
	        			context: this,
	        			data: { gene: this.id() },
		        		success: function () {
		        			this.toggleClass('basket');
		        		}
	        		});
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
	    zIndex: 100000
	});

	// Button listeners
	$('#select-all').click(selectAll);
	$('#select-none').click(selectNone);
	$('#select-invert').click(invertSelection);
	$('#remove-selection').click(removeSelected);
	$('#select-neighbors').click(selectNeighbors);
});
