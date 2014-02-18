"use strict";

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
});
