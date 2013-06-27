/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

jQuery(document).ready(function ($) {
	$('#cbp-canvas-start').click(function () {
		$(this).initializeCanvas($('#cbp-canvas'),
								$('#cbp-post-id').val(),
								$('#cbp-editor-options'), false,
								{ 'width': $('#cbp-canvas-width').val(), 'height' : $('#cbp-canvas-height').val(), 'name': $('#cbp-name').val()} );
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
								{	'png' : $('input[name=pngfile]').val(),
									'chi' : $('input[name=paintings]:checked').val(), 
									'pid' : $('input[name=paintings]:checked').attr('id'),
									'edit' : true,
									'name' : $('label[for=' + $('input[name=paintings]:checked').attr('id') + '][class=cbp-title] span').text()	//convoluted way of finding a post title :(
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
	// Applet params
	if (opts['width']) width = 'canvasWidth="'  + opts['width'] + '"\n';
	if (opts['height']) height = 'canvasHeight="' + opts['height'] + '"\n';
	if (opts['chi']) var chi = 'loadChibiFile = "' + opts['chi'] + '"\n';
	if (opts['png']) var png = 'loadImage = "' + opts['png'] + '"\n';
	
	// postURL parameters
	if (opts['name']) var name = '&name=' + opts['name'].replace(/[<>:;?@&=+$,\s\/]/ig, "");
	else name = "";
	if (opts['pid']) var pid = '&pid=' + opts['pid'];
	else pid = "";
	if (opts['edit']) var edit = '&edit=true';
	else edit = "";
	
	if (!isEdit) {
		params =	width + height;

	} else {
		params =	png + chi;
	}
	// alert(decodeURI(name));
	canvasDiv.html('<embed id="chibipaint"\n\
		type="application/x-java-applet;version=1.7"\n\
		width="100%" height="600"\n\
		archive="../wp-content/plugins/wp-chibipaint/inc/chibipaint.jar"\n\
		code="chibipaint.ChibiPaint.class"\n\
		pluginspage="http://java.com/download/"\n' + params +
		'postURL="../wp-content/plugins/wp-chibipaint/inc/cbp_save.php?post=' + post + name + pid + edit + '"\n\
		exitURL="../wp-content/plugins/wp-chibipaint/inc/cbp_results.php?post=' + post + '"\n\
		exitURLTarget="cbp-iframe-results" />');
};