"use strict";

$(function () {
	$('#basket-table').dataTable();

	$('#empty-basket').click(function () {
		$.ajax({
			url: 'api/empty_basket',
			type: 'GET',
			success: function () {
				location.reload();
			}
		});
	});
});
