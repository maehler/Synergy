"use strict";

$.fn.dataTableExt.afnFiltering.push(
    function( oSettings, aData, iDataIndex ) {
        var qMax = $('#q-value-filter').val() * 1;
        var qValue = aData[5];
        if ( qMax == "" ) {
            return true;
        } else if ( qMax > qValue ) {
            return true;
        }
        return false;
    }
);

function replaceBasket() {
	var rows = $('#gene-table').dataTable().$('tr', {
		filter: 'applied',
		page: 'all'
	});
	var sel = [];
	$.each(rows, function() {
		sel.push($(this).find('input').val());
	});
	$.ajax({
		url: baseURL + 'api/replace_basket',
		type: 'POST',
		data: { genes: sel },
		success: function(res) {
			$('#basket-load-indicator').remove();
			$('#replace-basket').after($('<p/>')
				.html(res + ' genes added to basket')
				.prop('id', 'basket-load-indicator')
				.css({
					'display': 'inline',
					'margin-left': '10px'
				})
			);
		}
	});
}

function runTOMTOM() {
	this.disabled = true;
	var $tomtomRunning = $('#tomtom-running').toggleClass('hidden');
	var $tomtomResults = $('#tomtom-results').empty();
	$.ajax({
		url: baseURL + 'api/run_tomtom',
		type: 'POST',
		dataType: 'json',
		data: {
			matrix: JSON.stringify(pspm),
			db: $('#tomtom-db').val(),
			th: $('#tomtom-th').val(),
			thtype: $('#tomtom-thtype').val(),
			minovlp: $('#tomtom-minovlp').val()
		},
		context: this,
		success: function(json) {
			if (!json.file) {
				$tomtomResults.html(json.name);
			} else {
				$tomtomResults
					.append($('<a/>', {
						href: baseURL + json.file,
						html: json.name
					}));
			}
			$tomtomRunning.toggleClass('hidden');
			this.disabled = false;
		}
	});
}

function exportLogo(format) {
	var strData = JSON.stringify(pspm);
	$.download(baseURL + 'api/export_motif_logo', {
		'pspm': encodeURI(strData),
		'format': format
	}, 'POST');
}

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
			return nRow;
		}
	});

	// TOMTOM
	$('#run-tomtom').click(runTOMTOM);

	$('#eps-logo').click(function() {
		exportLogo('eps');
	});
	$('#png-logo').click(function() {
		exportLogo('png');
	});
	$('#q-value-filter').change(function() {
		$('#gene-table').dataTable().fnDraw();
	});
	$('#replace-basket').click(replaceBasket);
});
