"use strict";

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
});