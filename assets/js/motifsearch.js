"use strict";

var iupacExample = 'G[AG][AG]T[TA]TCG';
var matrixExample = '0    0    1    0\n0.78 0    0.22 0\n0.78 0    0.22 0\n0    0    0    1\n0.22 0    0    0.78\n0    0.11 0    0.89\n0    1    0    0\n0    0    1    0';

var paneToggle = function() {
	var panes = ['search-iupac-pane', 'search-matrix-pane', 'search-id-pane'];
	for (var i = 0; i < panes.length; i++) {
		if ($(this).val() == panes[i]) {
			$('#'+panes[i]).removeClass('hidden');
			if ($(this).val() == 'search-id-pane') {
				$('#search-options').addClass('hidden');
			}
		} else {
			$('#'+panes[i]).addClass('hidden');
			$('#search-options').removeClass('hidden');
		}
	}
}

$(function() {
	$('input[name="motif-search-radio"]').change(paneToggle);
	$('#load-iupac-example').click(function () {
		$('#motif-iupac').val(iupacExample);
		return false;
	});
	$('#load-matrix-example').click(function() {
		$('#motif-matrix').html(matrixExample);
		return false;
	});
});
