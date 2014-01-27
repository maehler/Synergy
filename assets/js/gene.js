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

	if (expression.length === 0) {
		$('#flot-expression').append($('</p>').html('No expression data available'))
	} else {
		$.plot('#flot-expression', [expression], {
			series: {
				lines: {show: true},
				points: {show: true}
			},
			grid: {
				hoverable: true
			}
		});
	}

	var previousPoint = null;
	$("#flot-expression").bind("plothover", function (event, pos, item) {
		if (item) {
			var x = item.dataIndex;
			if (previousPoint != x) {
				$('#tooltip').fadeOut(200).remove();
				var y = item.datapoint[1].toFixed(3);

				previousPoint = x;

				$('<div id="tooltip"><b>Experiment:</b> ' + expAnnot[x][0] + '<br>' +
						'<b>Sample:</b> ' + expAnnot[x][1] + '<br>' +
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
		}
	});
});
