"use strict";

$.fn.dataTableExt.afnFiltering.push(
    function( oSettings, aData, iDataIndex ) {
        var qMax = $('#motif-q-filter').val() * 1;
        var qValue = aData[6];
        if ( qMax == "" ) {
            return true;
        } else if ( qMax > qValue ) {
            return true;
        }
        return false;
    }
);

$.fn.dataTableExt.afnFiltering.push(
    function( oSettings, aData, iDataIndex ) {
        var onlyCentral = $('#motif-only-central').prop('checked');
        var isCentral = aData[4].length > 0;
        if ( !onlyCentral ) {
        	return true;
        } else if ( onlyCentral && isCentral ) {
            return true;
        }
        return false;
    }
);

function addToBasket() {
    $.ajax({
        url: baseURL + 'api/update_basket',
        type: 'GET',
        data: { gene: geneID },
        success: function() {
            $('#add-remove-basket').removeClass('add-to-basket')
                .addClass('remove-from-basket');
        }
    });
}

function removeFromBasket() {
    $.ajax({
        url: baseURL + 'api/remove_from_basket',
        type: 'GET',
        data: { genes: [geneID] },
        success: function () {
            $('#add-remove-basket').removeClass('remove-from-basket')
                .addClass('add-to-basket');
        }
    });
}

$(function () {
	var motifTable = $('#motif-table').dataTable({
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

	$('#motif-q-filter').change(function() {
		motifTable.fnDraw();
	});
	$('#motif-only-central').change(function() {
		motifTable.fnDraw();
	});

    $('#add-remove-basket').click(function() {
        if ($(this).hasClass('add-to-basket')) {
            addToBasket();
        } else {
            removeFromBasket();
        }
    });
});
