"use strict";

function updateCount() {
	$('#select-count').html(getSelection().length);
}

function emptyBasket() {
	$.ajax({
		url: 'api/empty_basket',
		type: 'GET',
		success: function () {
			location.reload();
		}
	});
}

function selectAll() {
	selectNone();
	var rows = $('#basket-table').dataTable().$('tr', {
		filter: 'applied',
		page: 'all'
	}).find('input[type="checkbox"]').prop('checked', true);
	updateCount();
}

function selectNone() {
	var rows = $('#basket-table').dataTable().fnGetNodes();
	$.each(rows, function () {
		$(this).find('input[type="checkbox"]').prop('checked', false);
	});
	updateCount();
}

function exportSelection() {
	var sel = getSelection();
	if (sel.length === 0) {
		alert('No genes selected');
		return;
	}
	$.download('api/export_selection', {'genes': sel}, 'POST');
}

function getSelection() {
	var sel = [];
	var rows = $('#basket-table').dataTable().fnGetNodes();
	$.each(rows, function () {
		if ($(this).find('input[type="checkbox"]').prop('checked')) {
			sel.push($(this).find('input').val());
		}
	});
	return sel;
}

$(function () {
	$('#basket-table').dataTable({
		aoColumnDefs: [{sSortDataType: 'dom-checkbox', aTargets: [0]}],
		aaSorting: [[1, 'asc']]
	});
	$('#basket-table input').click(updateCount);
	updateCount();

	// Table button listeners
	$('#empty-basket').click(emptyBasket);
	$('#select-all').click(selectAll);
	$('#select-none').click(selectNone);
	$('#export-selection').click(exportSelection);

	// Initiate enrichment tools
	enrichmentTools(getSelection);
	expressionPlot(getSelection, baseURL);
});
