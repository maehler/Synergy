"use strict";

var cy;

function getSelection() {
	var sel = [];
	cy.nodes(':selected').each(function() {
		sel.push(this.id());
	});
	return sel;
}

function nodeIds(selector) {
	selector = typeof(selector) !== 'undefined' ? selector : '';
	var ids = [];
	$.each(cy.nodes(selector), function (i, element) {
		ids.push(element.id());
	});
	return ids;
}

function redraw() {
	cy.forceRender();
	window.setTimeout(updateCount, 50);
	cy.layout({
		name: 'arbor',
		liveUpdate: true
	});
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

function addToBasket() {
	var selected = nodeIds(':selected');
	$.ajax({
		url: 'api/update_basket',
		type: 'GET',
		data: { gene: selected },
		success: function () {
			cy.nodes(':selected').addClass('basket');
		}
	})
}

function removeFromBasket() {
	var selected = nodeIds(':selected');
	$.ajax({
		url: 'api/remove_from_basket',
		type: 'GET',
		data: { genes: selected },
		success: function () {
			cy.nodes(':selected').removeClass('basket');
		}
	})
}

function exportGML() {
	var jsonNetwork = JSON.stringify(cy.json().elements);
	$.download('api/export_gml_network', {'json': encodeURI(jsonNetwork)}, 'POST');
}

function exportPNG() {
	var jsonNetwork = JSON.stringify(cy.json().elements);
	$.download('api/export_png_network', {'json': encodeURI(jsonNetwork)}, 'POST');
}

function exportPDF() {
	var jsonNetwork = JSON.stringify(cy.json().elements);
	$.download('api/export_pdf_network', {'json': encodeURI(jsonNetwork)}, 'POST');
}

function searchNetwork() {
	cy.nodes().unselect();
	var sstring = $('#network-search-input').val();
	if (sstring == '') {
		return;
	}
	cy.nodes('[orf="'+sstring+'"]').select();
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
			// Don't do anything if there aren't any new edges
			if (edges.edges.length === cy.edges("[weight>"+expand_threshold+"]").length) {
				return;
			}
			cy.remove(cy.edges("[weight>"+expand_threshold+"]"));
			cy.add(edges);
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
					'border-color': '#CA281D',
					'border-width': 3,
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
			$('#load-message .message').html('Calculating layout...');
		},
		done: function () {
			console.log('layout done');
			$('#load-message, #network-loading').fadeOut(300, function () {
				$(this).remove();
			});
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

	// Search listener
	$('#network-search-button').click(searchNetwork);
	$('#network-search-input').keyup(function (e) {
		if (e.which === 13) {
			searchNetwork();
		}
	})

	// Button listeners
	$('#select-all').click(selectAll);
	$('#select-none').click(selectNone);
	$('#select-invert').click(invertSelection);
	$('#remove-selection').click(removeSelected);
	$('#select-neighbors').click(selectNeighbors);

	$('#basket-add').click(addToBasket);
	$('#basket-remove').click(removeFromBasket);

	$('#export-gml').click(exportGML);
	$('#export-png').click(exportPNG);
	$('#export-pdf').click(exportPDF);

	// Initiate enrichment tools
	enrichmentTools(getSelection);

	// Initiate expression plot
	expressionPlot(getSelection);
});
