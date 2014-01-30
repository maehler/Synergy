"use strict";

$(function () {
	var motifLen = pspm.length;
	var canvasWidth = 50 * motifLen;

	isblogo.makeLogo('motif-logo', {
		alphabet: ['A', 'C', 'G', 'T'],
		values: pspm
	},
	{
		width: canvasWidth,
		height: 200,
		glyphStyle: 'bold 20pt Helvetica'
	});

	$('#gene-table').dataTable({
		aoColumnDefs: [{sType: 'scientific', aTargets: [5]}],
		aaSorting: [[5, 'asc']],
		fnRowCallback: function (nRow, aData, iDisplayIndex) {
			if (aData[5] > 0.001) {
				var roundp = Number(aData[5]).toPrecision(2);
			} else {
				var roundp = Number(Number(aData[5]).toPrecision(3)).toExponential();
			}
			$('td:eq(4)', nRow).html(Number(aData[4]).toFixed(2));
			$('td:eq(5)', nRow).html(roundp);
			$('td:eq(0)', nRow).empty().append($('<a/>')
				.attr('href', baseURL + 'gene/details/' + aData[0])
				.html(aData[0]));
			return nRow;
		}
	});
});