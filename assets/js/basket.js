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

function plotExpression(data, annot) {
	$('#flot-expression, .flot-overview').css('display', 'block');
	var plotOptions = {
		series: {
			lines: { show: true, lineWidth: 1 },
			shadowSize: 0
			// points: { show: true, radius: 2 }
		},
		grid: { hoverable: true },
		selection: { mode: 'x' },
		xaxis: { tickDecimals: 0 },
		yaxis: { axisLabel: 'log<sub>2</sub> expression' },
		legend: {
			show: true,
			container: '.flot-legend',
			noColumns: 2
		}
	}

	var plot = $.plot('#flot-expression', data, plotOptions);

	var overview = $.plot('.flot-overview', data, {
		series: { 
			lines: { show: true, lineWidth: 1 },
			shadowSize: 0
		},
		legend: { show: false },
		selection: { mode: 'x' }
	});

	// Range selection
	$('#flot-expression').bind('plotselected', function (event, ranges) {
		if (ranges.xaxis.to - ranges.xaxis.from < 0.00001)
			ranges.xaxis.to = ranges.xaxis.from + 0.00001;
		if (ranges.yaxis.to - ranges.yaxis.from < 0.00001)
			ranges.yaxis.to = ranges.yaxis.from + 0.00001;

		ranges.xaxis.to = Math.floor(ranges.xaxis.to);
		ranges.xaxis.from = Math.floor(ranges.xaxis.from);

		// Get the relevant slice of data
		var dataSlice = [];
		$(data).each(function() {
			var newData = {label: this.label};
			newData.data = this.data.slice(ranges.xaxis.from, ranges.xaxis.to + 2);
			dataSlice.push(newData);
		})

		plot = $.plot('#flot-expression', dataSlice,
			$.extend(true, {}, plotOptions, {
				xaxis: {
					min: ranges.xaxis.from + 1 - 0.5,
					max: ranges.xaxis.to + 0.5
				},
				yaxis: {
					min: ranges.yaxis.from,
					max: ranges.yaxis.to
				}
			})
		);

		overview.setSelection(ranges, true);
	});

	// Range selection in overview
	$('.flot-overview').bind('plotselected', function (event, ranges) {
		plot.setSelection(ranges);
	});

	// Tooltip
	var previousPoint = null;
	$("#flot-expression").bind("plothover", function (event, pos, item) {
		if (item) {
			var x = item;
			if (previousPoint == null ||
					previousPoint.dataIndex != x.dataIndex ||
					previousPoint.series.label != x.series.label) {
				$('.legendLabel').css({'border': 'none'});
				$('.legendColorBox').css({'border': 'none'});
				$('#tooltip').fadeOut(200).remove();
				var y = item.datapoint[1].toFixed(3);

				$('.legendLabel:contains("' + item.series.label + '")').css({
					'border-right': '1px solid #F00',
					'border-top': '1px solid #F00',
					'border-bottom': '1px solid #F00'
				}).prevAll('.legendColorBox:first').css({
					'border-left': '1px solid #F00',
					'border-top': '1px solid #F00',
					'border-bottom': '1px solid #F00'
				});

				previousPoint = x;

				$('<div id="tooltip"><b>Experiment:</b> ' + annot[x.dataIndex][0] + '<br>' +
						'<b>Sample:</b> ' + annot[x.dataIndex][1] + '<br>' +
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
			$('.legendLabel').css({'border': 'none'});
			$('.legendColorBox').css({'border': 'none'});
		}
	});
}

function drawExpressionProfile() {
	var sel = getSelection();
	if (sel.length === 0) {
		alert('No genes selected');
		return;
	} else if (sel.length > 30) {
		alert("Don't select more than 30 genes. You won't see anything anyway.")
		return;
	}
	$.ajax({
		url: 'api/get_multi_flot',
		type: 'POST',
		dataType: 'json',
		data: { genes: sel },
		success: function(json) {
			if ($.isArray(json)) {
				alert(json[0]);
				return;
			}
			plotExpression(json.data, json.annot);
		}
	})
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

	// GO enrichment
	$('[name="enrichment-radio"]').change(enrichmentToggle);
	$('#go-table').dataTable({
		bProcessing: true,
		oLanguage: {sProcessing: '<span class="loading"></span>Calculating...'},
		aoColumnDefs: [{sType: 'scientific', aTargets: [3]}],
		aaSorting: [[3, 'asc']],
		fnRowCallback: function (nRow, aData, iDisplayIndex) {
			if (aData[3] > 0.001) {
				var roundp = aData[3].toPrecision(2);
			} else {
				var roundp = Number(aData[3].toPrecision(3)).toExponential();
			}
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
			if (aData[3] > 0.001) {
				var roundp = aData[3].toPrecision(2);
			} else {
				var roundp = Number(aData[3].toPrecision(3)).toExponential();
			}
			$('td:eq(3)', nRow).html(roundp);
			$('td:eq(0)', nRow).empty().append($('<a/>')
				.attr('href', baseURL + 'motif/details/' + aData[0])
				.html(aData[0])
			);
			return nRow;
		}
	});

	// Enrichment start buttons
	$('#start-go-enrichment').click(goEnrichment);
	$('#start-motif-enrichment').click(motifEnrichment);

	// Expression profile draw button
	$('#flot-expression, .flot-overview').css('display', 'none');
	$('#draw-expression').click(drawExpressionProfile);
});
