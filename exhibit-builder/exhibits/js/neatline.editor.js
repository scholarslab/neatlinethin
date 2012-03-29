var tl = null;
jQuery(document).ready(function($) {
	
	// NAV
	
	$('.exhibits-nav').click(function() {
		$('.exhibits-nav').toggleClass('exhibits-on');
		if($('.exhibits-nav').hasClass('exhibits-on')) {
			$('#exhibits-sub-nav').slideDown();
		} else {
			$('#exhibits-sub-nav').slideUp();
		}
	});
	
	$('.collections-nav').click(function() {
		$('.collections-nav').toggleClass('collections-on');
		if($('.collections-nav').hasClass('collections-on')) {
			$('#collections-sub-nav').slideDown();
		} else {
			$('#collections-sub-nav').slideUp();
		}
	});
	
	// TOOLTIPS
	
	// tooltip style
	
	$.fn.qtip.styles.neatline = { // Last part is the name of the style
		width: 250,
		padding: 10,
		background: '#F5F5F5',
		color: '#222',
		border: {
		   width: 2,
		   radius: 7,
		   color: '#888'
		},
		tip: {
			corner: 'bottomRight',
			size: {
				x: 15,
				y: 10
			}
		},
		name: 'light'
	}
	
	// add tips to items	
	
	$('.neatline-timeless-item').each(function() {
		$(this).qtip({
			content: $(this).children('.neatline-timeless-tip').html(),
			show: 'click',
			hide: 'unfocus',
			position: {
				corner: {
					target: 'leftTop',
					tooltip: 'rightBottom'
				}
			},
			style: 'neatline'
		}); // end qtip
	}); // end each

	
	// BUTTONS
	
	// locate button
	
	$('.neatline-locate-item').live('click', function() {
		alert('zoom to layer');
	});
	
	// MAP
	
	var cloudmade = new OpenLayers.Layer.CloudMade("CloudMade", {
		key: 'BC9A493B41014CAABB98F0471D759707',
		styleId: 25474
		//Hotchkiss id 27759
	});
	
  var gphy = new OpenLayers.Layer.Google(
      "Google Physical",
      {type: google.maps.MapTypeId.TERRAIN}
  );

	var blank = new OpenLayers.Layer("No Base Layer", {
								isBaseLayer: true, 
								'displayInLayerSwitcher': true
							});


  map.addLayers([cloudmade, gphy, blank]);

  // Google.v3 uses EPSG:900913 as projection, so we have to
  // transform our coordinates
  map.setCenter(new OpenLayers.LonLat(-71.43, 41.83).transform(
      new OpenLayers.Projection("EPSG:4326"),
      map.getProjectionObject()
  ), 14);


	// TIMELINE
	
	var neatline_events = new Timeline.DefaultEventSource();
	var url = '.'; // The base url for image, icon and background image
                 // references in the data
  neatline_events.loadJSON(timeline_data, url); // The data was stored into the 
                                             // timeline_data variable.

	var start_date = Timeline.DateTime.parseGregorianDateTime("2003");
	
	
	var theme1 = Timeline.ClassicTheme.create();
	theme1.highlightOpacity								= 40;
	theme1.highlightColor									= '#5A4A44';
	theme1.event.bubble.width 						= 350;
	theme1.event.bubble.height 						= 300;
	theme1.event.track.height							= 15;
	theme1.event.track.gap								= 5;
	theme1.event.tape.height							= 15;
	theme1.event.instant.icon							= 'images/nl-tl-icon.png';
	theme1.event.instant.iconWidth				= 15;
	theme1.event.instant.iconHeight				= 15;
	theme1.event.instant.impreciseColor		= '#554F44';
	theme1.event.instant.impreciseOpacity	= 40;
	
	var theme2 = Timeline.ClassicTheme.create();
	theme2.ether.highlightColor						= '#fffafa';
	theme2.ether.highlightOpacity					= 40;
  
	
  var bandInfos = [
    Timeline.createBandInfo({
        width:          "70%",
        intervalUnit:   Timeline.DateTime.MONTH, 
				date: 					start_date, 
        intervalPixels: 50,
				eventSource:    neatline_events,
				theme: 					theme1 
				 
    }),
    Timeline.createBandInfo({
        width:          "30%", 
        intervalUnit:   Timeline.DateTime.DECADE, 
				date: 					start_date, 
        intervalPixels: 100,
				eventSource:    neatline_events,
				theme: 					theme2,
				layout: 				'overview'
    })
  ];
	bandInfos[1].syncWith = 0;
  bandInfos[1].highlight = true;

  tl = Timeline.create(document.getElementById("neatline-timeline"), bandInfos);

	tl.layout();

	var resizeTimerID = null;
	$(document).resize(function() {
		 if (resizeTimerID == null) {
	       resizeTimerID = window.setTimeout(function() {
	           resizeTimerID = null;
	           tl.layout();
	       }, 500);
	   }
	});

	
	
});
