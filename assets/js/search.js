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
			var rows = $('#gene-table').dataTable().fnGetNodes();
			$.each(rows, function () {
				$(this).find('input:checked').attr('checked', false);
			})
			selected = [];
			updateCount(0);
			console.log('Selection cleared!');
		}
	})
}

function selectAll() {
	// Select all genes with the current filters applied
	$.ajax({
		url: 'api/genetable',
		dataType: 'json',
		type: 'POST',
		data: {
			'selgenes': 'all',
			'sQuery': $('.dataTables_filter input').val()
		},
		success: function (json) {
			if (json.selected_genes) {
				if (selected == undefined || selected.length === 0) {
					selected = json.selected_genes;
				} else {
					$.each(json.selected_genes, function (index, g) {
						if (jQuery.inArray(g, selected) == -1) {
							selected.push(g);
						}
					});
				}
			}
			updateBasket();
			$('#gene-table').dataTable()._fnAjaxUpdate();
		}
	});
}

function updateBasket() {
	$.ajax({
		url: 'api/replace_basket',
		type: 'POST',
		data: { 'genes': selected },
		success: function () {
			updateCount(selected.length);
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
	$('#select-all').click(selectAll);

	// Gene lists
	$('#list-wrapper').tabs();

	$('#go-list-table').dataTable({
		aoColumnDefs: [
			{bSortable: false, bSearchable: false, aTargets: [-1]}
		],
		bProcessing: true,
		bServerSide: true,
		sAjaxSource: 'api/genelist/go',
		sServerMethod: 'POST'
	});

	$('#motif-list-table').dataTable({
		aoColumnDefs: [
			{bSortable: false, bSearchable: false, aTargets: [-1]}
		],
		bProcessing: true,
		bServerSide: true,
		sAjaxSource: 'api/genelist/motif',
		sServerMethod: 'POST'
	});

	$('#coexp-list-table').dataTable({
		aoColumnDefs: [
			{bSortable: false, bSearchable: false, aTargets: [-1]}
		],
		bProcessing: true,
		bServerSide: true,
		sAjaxSource: 'api/genelist/coexp',
		sServerMethod: 'POST'
	});

	$('#tf-list-table').dataTable({
		aoColumnDefs: [
			{bSortable: false, bSearchable: false, aTargets: [-1]}
		],
		bProcessing: true,
		bServerSide: true,
		sAjaxSource: 'api/genelist/regulatory',
		sServerMethod: 'POST'
	});
});
