"use strict";

var selected = [];

function updateCount(newCount) {
	$('#select-count').html(newCount);
}

$(function () {
	updateCount(selected.length);

	// Initialize gene table
	var geneTable = $('#gene-table').dataTable({
		'aoColumnDefs': [
			{ 'sSortDataType': 'dom-checkbox', 'aTargets': [0] }
		],
		'bProcessing': true,
		'bServerSide': true,
		'sAjaxSource': 'api/genetable',
		'fnServerSideParams': function (aoData) {
			aoData.push({
				'name': 'selgenes',
				'value': selected
			})
		},
		'sServerMethod': 'POST',
		'fnRowCallback': function (nRow, aData, iDisplayIndex) {
			var id = aData[0].match(/id="(\d+)"/)[1];
			if (jQuery.inArray(id, selected) !== -1) {
				$(nRow).find('input[type="checkbox"]').attr('checked', true);
			}
		},
		'fnServerData': function (sSource, aoData, fnCallback) {
			$.ajax({
				url: sSource,
				dataType: 'json',
				data: aoData,
				type: 'POST',
				success: function (json) {
					if (json.selected_genes) {
						selected = json.selected_genes;
					}
					fnCallback(json);
					updateCount(selected.length);
				}
			})
		}
	});

	// Checkbox listener
	$('#gene-table').delegate('input', 'click', function () {
		var aData = geneTable.fnGetData($(this).closest('tr')[0]);
		var id = aData[0].match(/id="(\d+)"/)[1];
		var index = jQuery.inArray(id, selected);

		if (index === -1) {
			selected.push(id);
			$(this).find('input[type="checkbox"]').attr('checked', true);
		} else {
			selected.splice(index, 1);
			$(this).find('input[type="checkbox"]').attr('checked', false);
		}
		updateCount(selected.length);
	})
});