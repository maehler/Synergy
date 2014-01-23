"use strict";

function emptyBasket() {
	$.ajax({
		url: 'api/empty_basket',
		type: 'GET',
		success: function () {
			location.reload();
		}
	});
}

function enrichmentToggle() {
	var panes = ['go-pane', 'motif-pane'];
	for (var i = 0; i < panes.length; i++) {
		if ($(this).val() == panes[i]) {
			$('#'+panes[i]).removeClass('hidden');
		} else {
			$('#'+panes[i]).addClass('hidden');
		}
	}
}

function goEnrichment() {

}

function motifEnrichment() {

}

function selectAll() {
	var rows = $('#basket-table').dataTable().fnGetNodes();
	$.each(rows, function () {
		$(this).find('input[type="checkbox"]').prop('checked', true);
	});
}

function selectNone() {
	var rows = $('#basket-table').dataTable().fnGetNodes();
	$.each(rows, function () {
		$(this).find('input[type="checkbox"]').prop('checked', false);
	});
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
	$('#basket-table').dataTable();

	// Table button listeners
	$('#empty-basket').click(emptyBasket);
	$('#select-all').click(selectAll);
	$('#select-none').click(selectNone);

	// Enrichment
	$('[name="enrichment-radio"]').change(enrichmentToggle);
	$('#go-table').dataTable();
	$('#motif-table').dataTable();

	$('#start-go-enrichment').click(goEnrichment);
	$('#start-motif-enrichment').click(motifEnrichment);
});
