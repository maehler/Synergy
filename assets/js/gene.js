"use strict";

$(function () {
	$('#motif-table').dataTable({
		aoColumnDefs: [{sType: 'scientific', aTargets: [5]}],
		aaSorting: [[5, 'asc']],
		fnRowCallback: function (nRow, aData, iDisplayIndex) {
			if (aData[5] > 0.001) {
				var roundp = Number(aData[5]).toPrecision(2);
			} else {
				var roundp = Number(Number(aData[5]).toPrecision(3)).toExponential();
			}
			$('td:eq(5)', nRow).html(roundp);
			$('td:eq(4)', nRow).html(Number(aData[4]).toPrecision(4));
			return nRow;
		}
	});
});
