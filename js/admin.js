(function ( $ ) {
	"use strict";

	$(function () {
		// Place your administration-specific JavaScript here
		$(".toggle_table").click(function(){
			$(this).next().fadeToggle();
		});
	});

}(jQuery));