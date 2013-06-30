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
		$('#cbp-editor-options').slideUp();
		$('#cbp-results').html($(this).contents().find('body').clone().html());
		$('#cbp-results').slideDown();
	});
	
	$(document).on('click', '#cbp-start-new', function() {
		$('#cbp-results').slideUp();
		$('#cbp-editor-options').slideDown();
	});
	
	// TODO (06/28/2013) Make sure the user actually selects something before clicking on Edit Image!
	$(document).on('click', '#cbp-edit-painting', function() {
		$('#cbp-results').slideUp();
		postID = $('input[name=paintings]:checked').attr('id')
		$(this).initializeCanvas($('#cbp-canvas'),
								$('#cbp-post-id').val(),
								$('#cbp-editor-options'), true,
								{	'png' : $('input[id=cbp-png-' + postID + ']').val(),
									'chi' : $('input[id=cbp-chi-' + postID + ']').val(), 
									'pid' : $('input[name=paintings]:checked').val(),
									'edit' : true,
									'name' : $('input[id=cbp-title-' + postID + ']').val(),
									'slug' : $('input[id=cbp-slug-' + postID + ']').val(),
								} );
		$('#cbp-iframe-results').html('');
	});
	
	$(document).on('click', '#cbp-canvas-cancel', function() {
		$('#cbp-canvas').slideUp();
		$('#cbp-results').slideDown();
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
	canvasDiv.slideDown();
	divHide.slideUp();
	var width = 250;
	var height = 250;
	var chi = "";
	// Applet params
	if (opts['width']) width = 'canvasWidth="'  + opts['width'] + '"\n';
	if (opts['height']) height = 'canvasHeight="' + opts['height'] + '"\n';
	if (opts['chi']) var chi = 'loadChibiFile = "' + opts['chi'] + '"\n';
	if (opts['png']) var png = 'loadImage = "' + opts['png'] + '"\n';
	
	// postURL parameters
	// if (opts['name']) var name = '&name=' + opts['name'].replace(/[<>:;?@&=+$,\s\/]/ig, "");
	if (opts['name']) var name = '&name=' + encodeURIComponent(opts['name']);
	else name = "";
	if (opts['slug']) var slug = '&slug=' + encodeURIComponent(opts['slug']);
	else slug = "";
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
		archive="../wp-content/plugins/chibipaint-for-wordpress/inc/chibipaint.jar"\n\
		code="chibipaint.ChibiPaint.class"\n\
		pluginspage="http://java.com/download/"\n' + params +
		'postURL="../wp-content/plugins/chibipaint-for-wordpress/inc/cbp_save.php?post=' + post + name + slug + pid + edit + '"\n\
		exitURL="../wp-content/plugins/chibipaint-for-wordpress/inc/cbp_results.php?post=' + post + '"\n\
		exitURLTarget="cbp-iframe-results" />\n\
		<input type="button" name="cbp-canvas-start" id="cbp-canvas-cancel" class="button button-primary button-large" value="Cancel" />');
};