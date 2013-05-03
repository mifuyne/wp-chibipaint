/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

jQuery(document).ready(function ($) {
	$('#cbp-canvas-start').click(function () {
		$(this).initializeCanvas($('#cbp-canvas'),
								$('#cbp-post-id').val(),
								$('#cbp-editor-options'), false,
								{ 'width': $('#cbp-canvas-width').val(), 'height' : $('#cbp-canvas-height').val()} );
	});
	
	$('#cbp-iframe-results').load(function() {
		// $(this).slideDown();
		$('#cbp-canvas').slideUp();
		$('#cbp-results').html($(this).contents().find('body').clone().html());
		$('#cbp-results').slideDown();
	});
	
	$(document).on('click', '#edit-painting', function() {
		$('#cbp-results').slideUp();
		$(this).initializeCanvas($('#cbp-canvas'),
								$('#cbp-post-id').val(),
								$('#cbp-editor-options'), true,
								{	'chi' : $('input[name=paintings]:checked').val(), 
									'pid' : $('input[name=paintings]:checked').attr('id'),
									'name' : $('label[for=' + $('input[name=paintings]:checked').attr('id') + '][class=cbp-title]').text()	//convoluted way of finding a post title :(
								} );
		$('#cbp-iframe-results').html('');
		$('#cbp-canvas').slideDown();
	});
});

jQuery.fn.initializeCanvas = function(canvasDiv, post, divHide, isEdit, opts) {	// options Object: width, height, path to png or path to chi
	/* Options:
	 *	width
	 *	height
	 *	png (path url)
	 *	chi (path url)
	 *	name (if editing)
	 */
	divHide.slideUp();
	var width = 250;
	var height = 250;
	var pid = 0;
	if (opts['width']) width = opts['width'];
	if (opts['height']) height = opts['height'];
	if (opts['chi']) chi = opts['chi'];
	if (opts['pid']) pid = '&pid=' + opts['pid'];
	if (opts['name']) name = '&name=' + opts['name'];
	
	if (!isEdit) {
		params =	'canvasWidth="' + width + '"\n\
					canvasHeight="' + height + '"\n';

	} else {
		params =	'loadImage = ""\n\
					loadChibiFile = "' + chi + '"\n';
	}
	// alert(name + "\n" + pid);
	canvasDiv.html('<embed id="chibipaint"\n\
		type="application/x-java-applet;version=1.7"\n\
		width="100%" height="600"\n\
		archive="../wp-content/plugins/wp-chibipaint/inc/chibipaint.jar?"\n\
		code="chibipaint.ChibiPaint.class"\n\
		pluginspage="http://java.com/download/"\n' + params +
		'postURL="../wp-content/plugins/wp-chibipaint/inc/cbp_save.php?post=' + post + name + pid + '"\n\
		exitURL="../wp-content/plugins/wp-chibipaint/inc/cbp_results.php?post=' + post + '"\n\
		exitURLTarget="cbp-iframe-results" />');
};