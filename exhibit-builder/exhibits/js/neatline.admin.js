jQuery(document).ready(function($) {
	
	// MAP
	
	var neatline_vector = new OpenLayers.Layer.Vector('Editing Layer');
	map.addLayer(neatline_vector);
	var switcher = new OpenLayers.Control.LayerSwitcher({'roundedCorner': false});
	toolbar = new OpenLayers.Control.EditingToolbar(neatline_vector);
	map.addControl(switcher);
	map.addControl(toolbar);
	$('.olControlEditingToolbar').hide();
	
	// Hacking the toolbar a bit.  Could create a new panel instead.
	$('<span class="neatline-ol-label">Navigate</span>').appendTo($('.olControlNavigationItemInactive'));
	$('<span class="neatline-ol-label">Point</span>').appendTo($('.olControlDrawFeaturePointItemInactive'));
	$('<span class="neatline-ol-label">Line</span>').appendTo($('.olControlDrawFeaturePathItemInactive'));
	$('<span class="neatline-ol-label">Shape</span>').appendTo($('.olControlDrawFeaturePolygonItemInactive'));
	$('<span class="neatline-ol-title">Edit Map</span>').prependTo($('.olControlEditingToolbar'));
	
	// TOOLTIPS
	
	// edit button
	
	$('<span class="neatline-edit-item neatline-button">Edit</span>').appendTo('.neatline-timeless-utils');
	
	$('.neatline-edit-item').live('click', function() {
		$('.olControlEditingToolbar').slideDown();
		$('#neatline-select-panel').slideUp();
		$('#neatline-edit-panel').slideDown();
	});
	

	// METADATA EDITOR
	
	// inline text edit
	
	$('.neatline-line-editable').inlineEdit();
	$('.neatline-area-editable').inlineEdit({control: 'textarea'});
	
	// styles for inline editing
	
	$('.neatline-metadata-def').mouseover(function () {
		$(this).css('background-color','#E0EAFF');
	});
	
	$('.neatline-metadata-def').mouseout(function () {
		$(this).css('background-color','transparent');
	});
	
	// editing notice
	
	$('<div class="metadata-edit-notice">Click any field to edit</div>').prependTo('#neatline-metadata-container');
	
	// metadata buttons
	
	$('.neatline-cancel-item').live('click', function() {
		$('.olControlEditingToolbar').slideUp();
		$('#neatline-edit-panel').slideUp();
		$('#neatline-select-panel').slideDown();
	});
	
	$('.neatline-save-item').live('click', function() {
		alert('POST this info, why don\'tcha? Then create a timed popup with qTip.');
		$('.olControlEditingToolbar').slideUp();
		$('#neatline-edit-panel').slideUp();
		$('#neatline-select-panel').slideDown();
	});
	
});
