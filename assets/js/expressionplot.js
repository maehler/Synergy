var expressionPlot = (function() {

	var selFun;
	var url;

	var init = function (fun, siteURL, singleDraw) {
		selFun = fun;
		url = siteURL;

		if (singleDraw !== undefined && singleDraw) {
			drawExpressionProfile();
			$('#draw-expression').remove();
		} else {
			// Expression profile draw button
			$('#flot-expression, .flot-overview').hide();
			$('#draw-expression').click(drawExpressionProfile);
		}
	}

	function plotExpression(data, annot) {
		$('#flot-expression, .flot-overview').show();
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
					$('.legendLabel').css('background-color', 'none');
					$('.legendColorBox').css('background-color', 'none');
					$('#tooltip').fadeOut(200).remove();
					var y = item.datapoint[1].toFixed(3);

					$('.legendLabel:contains("' + item.series.label + '")')
						.css('background-color', '#DDD')
						.prevAll('.legendColorBox:first').css('background-color', '#DDD');

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
				$('.legendLabel').css('background-color', 'none');
				$('.legendColorBox').css('background-color', 'none');
			}
		});
	}

	function drawExpressionProfile() {
		var sel = selFun();
		if (sel.length === 0) {
			alert('No genes selected');
			return;
		} else if (sel.length > 30) {
			alert("Don't select more than 30 genes. You won't see anything anyway.")
			return;
		}
		$('#loading-plot').toggleClass('hidden');
		$('#draw-expression').prop('disabled', true);
		$.ajax({
			url: url + 'api/get_multi_flot',
			type: 'POST',
			dataType: 'json',
			data: { genes: sel },
			success: function(json) {
				if ($.isArray(json)) {
					alert(json[0]);
					return;
				}
				plotExpression(json.data, json.annot);
				$('#loading-plot').toggleClass('hidden');
				$('#draw-expression').prop('disabled', false);
			}
		})
	}

	return init;
})();
