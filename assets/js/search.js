"use strict";

function updateCount(newCount) {
	$('#select-count').html(newCount);
}

function loadPhotosynthesisGenes() {
    // An example gene set that can be used.
    var photoGenes = [
        208, 315, 446, 536, 588, 609, 610, 888, 1324, 2048, 2174, 2191, 2481, 
        2638, 2718, 2848, 2849, 3257, 3258, 3259, 3262, 3263, 3265, 3292, 3318, 
        3345, 3348, 3520, 3533
    ];
    var redirect = 'basket';
    $.ajax({
        url: 'api/replace_basket',
        type: 'POST',
        data: {
            'genes': photoGenes
        },
        success: function () {
            console.log('Photosynthesis genes loaded!');
            window.location = redirect;
        }
    });
}

function clearSelection() {
	$.ajax({
		url: 'api/empty_basket',
		type: 'POST',
		success: function () {
			console.log('Selection cleared!');
			updateCount(0);
		}
	})
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
		var index = selected.indexOf(id);

		if (index === -1) {
			selected.push(id);
			$(this).find('input[type="checkbox"]').attr('checked', true);
		} else {
			selected.splice(index, 1);
			$(this).find('input[type="checkbox"]').attr('checked', false);
		}

		// Update the contents of the gene basket
		$.ajax({
			url: 'api/update_basket',
			data: { gene: id },
			type: 'GET',
			success: function () {
				updateCount(selected.length);
			}
		});
	});

	// Button listeners
	$('#load-example').click(loadPhotosynthesisGenes);
	$('#clear-selection').click(clearSelection);
});