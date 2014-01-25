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
	var gids = getSelection();
	if (gids.length == 0) {
		alert('No genes selected');
		return;
	}
	$('#go-table_processing').css('visibility', 'visible');
	$.ajax({
		url: 'api/goenrichment',
		type: 'POST',
		data: {
			genes: gids,
			pth: $('#go-p-th').val()
		},
		success: function (json) {
			$('#go-table').dataTable().fnClearTable();
			$('#go-table').dataTable().fnAddData(json);
		},
		error: function (jqXHR, textStatus, errorThrown) {
			$('#go-table').dataTable().fnClearTable();
			$('#go-table tbody td').eq(0).empty().append('The server responded with the status ' + 
				jqXHR.status + ' (' + errorThrown + ')');
		},
		complete: function () {
			$('#go-table_processing').css('visibility', 'hidden');
		}
	});
}

function motifEnrichment() {
	var gids = getSelection();
	if (gids.length == 0) {
		alert('No genes selected');
		return;
	}
	$('#motif-table_processing').css('visibility', 'visible');
	$.ajax({
		url: 'api/motifenrichment',
		type: 'POST',
		data: {
			genes: gids,
			pth: $('#motif-p-th').val(),
			qth: $('#motif-q-th').val(),
			central: $('#central-motifs').prop('checked')
		},
		success: function (json) {
			$('#motif-table').dataTable().fnClearTable();
			$('#motif-table').dataTable().fnAddData(json);
		},
		error: function (jqXHR, textStatus, errorThrown) {
			$('#motif-table').dataTable().fnClearTable();
			$('#motif-table tbody td').eq(0).empty().append('The server responded with the status ' +
				jqXHR.status + ' (' + errorThrown + ')');
		},
		complete: function () {
			$('#motif-table_processing').css('visibility', 'hidden');
		}
	});
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

	// GO enrichment
	$('[name="enrichment-radio"]').change(enrichmentToggle);
	$('#go-table').dataTable({
		bProcessing: true,
		oLanguage: {sProcessing: '<span class="loading"></span>Calculating...'},
		aoColumnDefs: [{sType: 'scientific', aTargets: [3]}],
		aaSorting: [[3, 'asc']],
		fnRowCallback: function (nRow, aData, iDisplayIndex) {
			var roundp = Number(aData[3].toPrecision(3)).toExponential();
			$('td:eq(3)', nRow).html(roundp);
			return nRow;
		}
	});

	// Motif enrichment
	$('#motif-table').dataTable({
		bProcessing: true,
		oLanguage: {sProcessing: '<span class="loading"></span>Calculating...'},
		aoColumnDefs: [{sType: 'scientific', aTargets: [3]}],
		aaSorting: [[3, 'asc']],
		fnRowCallback: function (nRow, aData, iDisplayIndex) {
			var roundp = Number(aData[3].toPrecision(3)).toExponential();
			$('td:eq(3)', nRow).html(roundp);
			return nRow;
		}
	});

	// Enrichment start buttons
	$('#start-go-enrichment').click(goEnrichment);
	$('#start-motif-enrichment').click(motifEnrichment);
});
