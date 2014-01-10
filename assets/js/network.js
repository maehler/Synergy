"use strict";

var cy;

function selectAll() {
	cy.nodes().select();
}

function selectNone() {
	cy.nodes().unselect();
}

$(function () {
	$('#network-container').cytoscape({
		elements: network_data,

		layout: {
			name: 'arbor',
			liveUpdate: true,
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
			$('#network-container').cytoscapePanzoom();
			cy = this;
		},
		done: function () {
			console.log('layout done');
		}
	});

	// Button listeners
	$('#select-all').click(selectAll);
	$('#select-none').click(selectNone);
});
