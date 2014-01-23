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

$(function () {
	$('#basket-table').dataTable();

	// Table button listeners
	$('#empty-basket').click(emptyBasket);

	// Enrichment
	$('[name="enrichment-radio"]').change(enrichmentToggle);
	$('#go-table').dataTable();
	$('#motif-table').dataTable();

	$('#start-go-enrichment').click(goEnrichment);
	$('#start-motif-enrichment').click(motifEnrichment);
});
