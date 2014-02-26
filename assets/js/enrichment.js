"use strict";

var enrichmentTools = (function() {

	var selFun;

	var init = function (fun) {
		selFun = fun;

		// Motif enrichment
		$('#motif-table').dataTable({
			bProcessing: true,
			oLanguage: {sProcessing: '<span class="loading"></span>Calculating...'},
			aoColumnDefs: [{sType: 'scientific', aTargets: [4]}],
			aaSorting: [[4, 'asc']],
			fnRowCallback: function (nRow, aData, iDisplayIndex) {
				if (aData[4] > 0.001) {
					var roundp = aData[4].toPrecision(2);
				} else {
					var roundp = Number(aData[4].toPrecision(3)).toExponential();
				}
				$('td:eq(4)', nRow).html(roundp);
				$('td:eq(0)', nRow).empty().append($('<a/>')
					.attr('href', baseURL + 'motif/details/' + aData[0])
					.html(aData[0])
				);
				return nRow;
			}
		});

		// GO enrichment
		$('[name="enrichment-radio"]').change(enrichmentToggle);
		$('#go-table').dataTable({
			bProcessing: true,
			oLanguage: {sProcessing: '<span class="loading"></span>Calculating...'},
			aoColumnDefs: [{sType: 'scientific', aTargets: [4]}],
			aaSorting: [[4, 'asc']],
			fnRowCallback: function (nRow, aData, iDisplayIndex) {
				if (aData[4] > 0.001) {
					var roundp = aData[4].toPrecision(2);
				} else {
					var roundp = Number(aData[4].toPrecision(3)).toExponential();
				}
				$('td:eq(4)', nRow).html(roundp);
				$('td:eq(0)', nRow).empty().append($('<a/>')
					.attr('href',
						'http://amigo.geneontology.org/cgi-bin/amigo/term_details?term=' + aData[0])
					.html(aData[0])
				);
				return nRow;
			},
			fnInitComplete: filterGO
		});

		// Enrichment start buttons
		$('#start-go-enrichment').click(goEnrichment);
		$('#start-motif-enrichment').click(motifEnrichment);

		// GO filtering
		$('#go-category-filter').change(filterGO);
	}

	var enrichmentToggle = function() {
		var panes = ['go-pane', 'motif-pane'];
		for (var i = 0; i < panes.length; i++) {
			if ($(this).val() == panes[i]) {
				$('#'+panes[i]).removeClass('hidden');
			} else {
				$('#'+panes[i]).addClass('hidden');
			}
		}
	}

	var filterGO = function() {
		var cat = $('#go-category-filter').val();
		$('#go-table').dataTable().fnFilter(cat, 1);
	}

	var goEnrichment = function() {
		var gids = selFun();
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

	var motifEnrichment = function() {
		var gids = selFun();
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

	return init;
})();
