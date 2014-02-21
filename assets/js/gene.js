"use strict";

$(function () {
	$('#motif-table').dataTable({
		aoColumnDefs: [{sType: 'scientific', aTargets: [6]}],
		aaSorting: [[6, 'asc']],
		fnRowCallback: function (nRow, aData, iDisplayIndex) {
			if (aData[6] > 0.001) {
				var roundp = Number(aData[6]).toPrecision(2);
			} else {
				var roundp = Number(Number(aData[6]).toPrecision(3)).toExponential();
			}
			$('td:eq(6)', nRow).html(roundp);
			$('td:eq(5)', nRow).html(Number(aData[5]).toPrecision(5));
			return nRow;
		}
	});

	expressionPlot(function () { return [geneID]; }, baseURL, true);
});
