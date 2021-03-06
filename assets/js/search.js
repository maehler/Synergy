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

function loadList() {
	console.log('Loading ' + this.id);
	$.ajax({
		url: 'api/load_list/' + this.id,
		type: 'GET',
		success: function () {
			window.location = 'basket';
		}
	});
}

$(function () {
	updateCount(selected.length);

	// Initialize gene table
	var geneTable = $('#gene-table').dataTable({
		aoColumnDefs: [
			{ 
				sSortDataType: 'dom-checkbox',
				bSortable: false,
				bSearchable: false,
				aTargets: [0]
			},
		],
		aaSorting: [[1, 'asc']],
		bProcessing: true,
		bServerSide: true,
		sAjaxSource: 'api/genetable',
		fnServerSideParams: function (aoData) {
			aoData.push({
				'name': 'selgenes',
				'value': selected
			})
		},
		sServerMethod: 'POST',
		fnRowCallback: function (nRow, aData, iDisplayIndex) {
			var id = aData[0].match(/id="(\d+)"/)[1];
			var $checkbox = $(nRow).find('input[type="checkbox"]');
			if (jQuery.inArray(id, selected) !== -1) {
				$checkbox.attr('checked', true);
			}
			$checkbox.parent('td').addClass('align-center');
			$('td:last', nRow).addClass('align-center');
		},
		fnServerData: function (sSource, aoData, fnCallback) {
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

	var listTableDefaults = {
		aoColumnDefs: [
			{bSortable: false, bSearchable: false, aTargets: [-1]}
		],
		bProcessing: true,
		bServerSide: true,
		sServerMethod: 'POST'
	};

	$('#go-list-table').dataTable($.extend({}, listTableDefaults, {
		sAjaxSource: 'api/genelist/go',
		fnDrawCallback: function() {
			$('#go-list-table .load-list').click(loadList);
		},
		fnServerParams: function(aoData) {
			aoData.push({
                'name': 'gofilter',
                'value': $('#golist-gofilter').prop('checked') ? 1 : 0
            });
            aoData.push({
                'name': 'motiffilter',
                'value': $('#golist-motiffilter').prop('checked') ? 1 : 0
            });
		},
		fnRowCallback: function(nRow, aData, iDisplayIndex) {
			if (Number(aData[4]) > 0.001) {
				var goRoundP = Number(aData[4]).toPrecision(2);
			} else {
				var goRoundP = Number(Number(aData[4]).toPrecision(3)).toExponential();
			}
			$('td:eq(4)', nRow).html(goRoundP);

			if (Number(aData[6]) > 0.001) {
				var motifRoundP = Number(aData[6]).toPrecision(2);
			} else {
				var motifRoundP = Number(Number(aData[6]).toPrecision(3)).toExponential();
			}
			$('td:eq(6)', nRow).html(motifRoundP);

			return nRow;
		}
	}));

	$('#motif-list-table').dataTable($.extend({}, listTableDefaults, {
		sAjaxSource: 'api/genelist/motif',
		fnDrawCallback: function() {
			$('#motif-list-table .load-list').click(loadList);
		},
		fnServerParams: function(aoData) {
			aoData.push({
                'name': 'gofilter',
                'value': $('#motiflist-gofilter').prop('checked') ? 1 : 0
            });
            aoData.push({
                'name': 'motiffilter',
                'value': $('#motiflist-motiffilter').prop('checked') ? 1 : 0
            });
		},
		fnRowCallback: function(nRow, aData, iDisplayIndex) {
			if (Number(aData[3]) > 0.001) {
				var goRoundP = Number(aData[3]).toPrecision(2);
			} else {
				var goRoundP = Number(Number(aData[3]).toPrecision(3)).toExponential();
			}
			$('td:eq(3)', nRow).html(goRoundP);

			if (Number(aData[5]) > 0.001) {
				var motifRoundP = Number(aData[5]).toPrecision(2);
			} else {
				var motifRoundP = Number(Number(aData[5]).toPrecision(3)).toExponential();
			}
			$('td:eq(5)', nRow).html(motifRoundP);

			$('td:eq(0)', nRow).empty().append($('<a/>')
				.attr('href', 'motif/details/' + aData[0])
				.html(aData[0]));

			return nRow;
		}
	}));

	$('#coexp-list-table').dataTable($.extend({}, listTableDefaults, {
		sAjaxSource: 'api/genelist/coexp',
		fnDrawCallback: function() {
			$('#coexp-list-table .load-list').click(loadList);
		},
		fnServerParams: function(aoData) {
			aoData.push({
                'name': 'gofilter',
                'value': $('#coexplist-gofilter').prop('checked') ? 1 : 0
            });
            aoData.push({
                'name': 'motiffilter',
                'value': $('#coexplist-motiffilter').prop('checked') ? 1 : 0
            });
		},
		fnRowCallback: function(nRow, aData, iDisplayIndex) {
			if (Number(aData[3]) > 0.001) {
				var goRoundP = Number(aData[3]).toPrecision(2);
			} else {
				var goRoundP = Number(Number(aData[3]).toPrecision(3)).toExponential();
			}
			$('td:eq(3)', nRow).html(goRoundP);

			if (Number(aData[5]) > 0.001) {
				var motifRoundP = Number(aData[5]).toPrecision(2);
			} else {
				var motifRoundP = Number(Number(aData[5]).toPrecision(3)).toExponential();
			}
			$('td:eq(5)', nRow).html(motifRoundP);

			$('td:eq(0)', nRow).empty().html('Cluster ' + aData[0].match(/\d+/));

			return nRow;
		}
	}));

	$('#tf-list-table').dataTable($.extend({}, listTableDefaults, {
		sAjaxSource: 'api/genelist/regulatory',
		fnDrawCallback: function() {
			$('#tf-list-table .load-list').click(loadList);
		},
		fnServerParams: function(aoData) {
			aoData.push({
                'name': 'gofilter',
                'value': $('#tflist-gofilter').prop('checked') ? 1 : 0
            });
            aoData.push({
                'name': 'motiffilter',
                'value': $('#tflist-motiffilter').prop('checked') ? 1 : 0
            });
		},
		fnRowCallback: function(nRow, aData, iDisplayIndex) {
			if (Number(aData[3]) > 0.001) {
				var goRoundP = Number(aData[3]).toPrecision(2);
			} else {
				var goRoundP = Number(Number(aData[3]).toPrecision(3)).toExponential();
			}
			$('td:eq(3)', nRow).html(goRoundP);

			if (Number(aData[5]) > 0.001) {
				var motifRoundP = Number(aData[5]).toPrecision(2);
			} else {
				var motifRoundP = Number(Number(aData[5]).toPrecision(3)).toExponential();
			}
			$('td:eq(5)', nRow).html(motifRoundP);

			$('td:eq(0)', nRow).empty().append($('<a/>')
				.attr('href', 'gene/details/' + aData[0])
				.html(aData[0]));

			return nRow;
		}
	}));

	// List filters
	$('.list-filter').change(function() {
		$(this).parent('div').find('.list-table').dataTable().fnDraw();
	});

	// Gene list upload
	$('#file-upload').submit(function(e) {
		if ($('#gene-file').val().length == 0) {
			alert('No file specified');
			return false;
		}
		return true;
	})
});
