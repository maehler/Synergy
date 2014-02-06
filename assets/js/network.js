"use strict";

var cy;

function plotExpression(data, annot) {
	$('#flot-expression, .flot-overview').css('display', 'block');
	var plotOptions = {
		series: {
			lines: { show: true, lineWidth: 1 },
			shadowSize: 0
			// points: { show: true, radius: 2 }
		},
		grid: { hoverable: true },
		selection: { mode: 'x' },
		xaxis: { tickDecimals: 0 },
		yaxis: { axisLabel: 'log<sub>2</sub> expression' },
		legend: {
			show: true,
			container: '.flot-legend',
			noColumns: 2
		}
	}

	var plot = $.plot('#flot-expression', data, plotOptions);

	var overview = $.plot('.flot-overview', data, {
		series: { 
			lines: { show: true, lineWidth: 1 },
			shadowSize: 0
		},
		legend: { show: false },
		selection: { mode: 'x' }
	});

	// Range selection
	$('#flot-expression').bind('plotselected', function (event, ranges) {
		if (ranges.xaxis.to - ranges.xaxis.from < 0.00001)
			ranges.xaxis.to = ranges.xaxis.from + 0.00001;
		if (ranges.yaxis.to - ranges.yaxis.from < 0.00001)
			ranges.yaxis.to = ranges.yaxis.from + 0.00001;

		ranges.xaxis.to = Math.floor(ranges.xaxis.to);
		ranges.xaxis.from = Math.floor(ranges.xaxis.from);

		// Get the relevant slice of data
		var dataSlice = [];
		$(data).each(function() {
			var newData = {label: this.label};
			newData.data = this.data.slice(ranges.xaxis.from, ranges.xaxis.to + 2);
			dataSlice.push(newData);
		})

		plot = $.plot('#flot-expression', dataSlice,
			$.extend(true, {}, plotOptions, {
				xaxis: {
					min: ranges.xaxis.from + 1 - 0.5,
					max: ranges.xaxis.to + 0.5
				},
				yaxis: {
					min: ranges.yaxis.from,
					max: ranges.yaxis.to
				}
			})
		);

		overview.setSelection(ranges, true);
	});

	// Range selection in overview
	$('.flot-overview').bind('plotselected', function (event, ranges) {
		plot.setSelection(ranges);
	});

	// Tooltip
	var previousPoint = null;
	$("#flot-expression").bind("plothover", function (event, pos, item) {
		if (item) {
			var x = item;
			if (previousPoint == null ||
					previousPoint.dataIndex != x.dataIndex ||
					previousPoint.series.label != x.series.label) {
				$('.legendLabel').css({'border': 'none'});
				$('.legendColorBox').css({'border': 'none'});
				$('#tooltip').fadeOut(200).remove();
				var y = item.datapoint[1].toFixed(3);

				$('.legendLabel:contains("' + item.series.label + '")').css({
					'border-right': '1px solid #F00',
					'border-top': '1px solid #F00',
					'border-bottom': '1px solid #F00'
				}).prevAll('.legendColorBox:first').css({
					'border-left': '1px solid #F00',
					'border-top': '1px solid #F00',
					'border-bottom': '1px solid #F00'
				});

				previousPoint = x;

				$('<div id="tooltip"><b>Experiment:</b> ' + annot[x.dataIndex][0] + '<br>' +
						'<b>Sample:</b> ' + annot[x.dataIndex][1] + '<br>' +
						'<b>log<sub>2</sub> expression:</b> ' + y + '<br>' +
						'</div>').css({
	                position: 'absolute',
	                'max-width': '300px',
	                display: 'none',
	                top: item.pageY + 10,
	                left: item.pageX + 10,
	                border: '1px solid #CCC',
	                'font-size': '10pt',
	                'border-radius': '5px',
	                'box-shadow': '2px 2px 8px 0px #555',
	                padding: '2px',
	                'background-color': '#EEE',
	                opacity: 0.8
	            }).appendTo('body').fadeIn(200);
	    	}
		} else {
			$('#tooltip').fadeOut(200).remove();
			previousPoint = null;
			$('.legendLabel').css({'border': 'none'});
			$('.legendColorBox').css({'border': 'none'});
		}
	});
}

function drawExpressionProfile() {
	var sel = getSelection();
	if (sel.length === 0) {
		alert('No genes selected');
		return;
	} else if (sel.length > 30) {
		alert("Don't select more than 30 genes. You won't see anything anyway.")
		return;
	}
	$.ajax({
		url: 'api/get_multi_flot',
		type: 'POST',
		dataType: 'json',
		data: { genes: sel },
		success: function(json) {
			if ($.isArray(json)) {
				alert(json[0]);
				return;
			}
			plotExpression(json.data, json.annot);
		}
	})
}

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

	// Expression profile draw button
	$('#flot-expression, .flot-overview').css('display', 'none');
	$('#draw-expression').click(drawExpressionProfile);
});
