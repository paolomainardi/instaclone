// Don't break on browsers without console.log();
if (typeof(console) === 'undefined') { console = { log: function() {}, assert: function() {} }; }
jQuery(function($) {

	$('#vintage-button').click(function() {
		Caman("#image-canvas", function () {
			this.revert();
			this.jarques();
			this.render();
		});
	});

	$('#vignette-button').click(function() {
		Caman("#image-canvas", function () {
			this.revert();
			this.lomo();
			this.render();
		});
	});

	$('#normal-button').click(function() {
		Caman("#image-canvas", function () {
			this.revert();
		});
	});

	$('#save-button').click(function() {
		Caman("#image-canvas", function () {
			this.save('png');
		});
	});
});
