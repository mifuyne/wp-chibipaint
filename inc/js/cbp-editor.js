/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

jQuery(document).ready(function ($) {
	$('#cbp-canvas-start').click(function () {
		width = $('#cbp-canvas-width').val();
		height = $('#cbp-canvas-height').val();
		$('#cbp-editor-options').slideUp();
		$('#cbp-canvas').html('<embed id="chibipaint"\n\
type="application/x-java-applet;version=1.7"\n\
width="100%" height="600"\n\
archive="../wp-content/plugins/wp-chibipaint/inc/js/chibipaint.jar"\n\
code="chibipaint.ChibiPaint.class"\n\
pluginspage="http://java.com/download/"\n\
canvasWidth="' + width + '"\n\
canvasHeight="' + height + '" />');
	});
});