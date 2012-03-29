if (Omeka.Neatline) { 
	Omeka.Neatline.jQuery = jQuery.noConflict();
}
else {
	Omeka.Neatline = new Object();
	Omeka.Neatline.jQuery = jQuery.noConflict();
}

if (!Omeka.NeatlineTheme) {
	Omeka.NeatlineTheme = new Object();
}

Omeka.Neatline.jQuery(document).ready( function() {

	/* listen for the construction of any NeatlineMaps or Timelines
	we assume that maps and timelines come in pairs together for now. this assumption may have to be
	relaxed as people generate more complex presentations
		*/

	Omeka.Neatline.jQuery("body").bind(
		{
		"Omeka.NeatlineMaps.mapcreated": function(evt){
			// check to see if we have a corresponding timeline ready to go
			// and if so, build a controller
				for (var i = 0 ; i < Omeka.NeatlineMaps.length ; i++) { // search maps
					if (Omeka.NeatlineMaps[i].div == evt.targets) {
						if (Omeka.Timeline[i] != undefined) {
							Omeka.Neatline.jQuery('body').neatline({map: Omeka.NeatlineMaps[i], timeline: Omeka.Timeline[i]});
						}
					}
				}
				behaviors();
		},
		"Omeka.Timeline.timelinecreated": function(evt){
			// check to see if we have a corresponding timeline ready to go
			// and if so, build a controller
				for (var i = 0 ; i < Omeka.Timeline.length ; i++) { // search maps
					if (Omeka.Timeline[i]._containerDiv == evt.target) {
						if (Omeka.NeatlineMaps[i] != undefined) {
							Omeka.Neatline.jQuery('body').neatline({map: Omeka.NeatlineMaps[i], timeline: Omeka.Timeline[i]});
							}
					}
				}
				behaviors();
			}
		}
	);
	var behaviors = function() {
		// NAV

		Omeka.Neatline.jQuery('.exhibits-nav').click(function() {
			Omeka.Neatline.jQuery('.exhibits-nav').toggleClass('exhibits-on');
			if(Omeka.Neatline.jQuery('.exhibits-nav').hasClass('exhibits-on')) {
				Omeka.Neatline.jQuery('#exhibits-sub-nav').slideDown();
			} else {
				Omeka.Neatline.jQuery('#exhibits-sub-nav').slideUp();
			}
		});

		Omeka.Neatline.jQuery('.collections-nav').click(function() {
			Omeka.Neatline.jQuery('.collections-nav').toggleClass('collections-on');
			if(Omeka.Neatline.jQuery('.collections-nav').hasClass('collections-on')) {
				Omeka.Neatline.jQuery('#collections-sub-nav').slideDown();
			} else {
				Omeka.Neatline.jQuery('#collections-sub-nav').slideUp();
			}
		});

		// add view/control panel to Timeline items and "timeless" items.
		
		Omeka.Neatline.jQuery('.timeline-event-label,.neatline-timeless-item').each(function() {
			var $this = Omeka.Neatline.jQuery(this);
			var url_base_index = window.location.pathname.indexOf('exhibits/show');
			var url_base = window.location.pathname.substr(0, url_base_index);
			var attr;
			if ($this.is(".timeline-event-label")) {
				attr = $this.attr('class');
			}
			else {
				attr = $this.attr('id');
			}
			/*console.group("Getting panel partial for timeline items");
			console.log("From item HTML class: " + classattr);
			console.log("From item Omeka id: " + classattr.split('neatline-item-')[1]);
			console.groupEnd();*/
			var id = attr.split('neatline-item-')[1];
			Omeka.Neatline.jQuery(this).qtip({
				content: { 
					url: url_base + 'timelines/panel/' + id
						},
				show: { solo:true, when: { event: "click" }, effect: {type: "slide"}
						},
				hide: { fixed:true, when: {event: 'focusout'}, effect:{type: "slide"} },
				/* position: {
					corner: {
						target: 'leftTop',
						tooltip: 'rightBottom'
					}
				}, */
				style: { 
						width: {min:800,max:800},
						padding: 10,
						background: '#F5F5F5',
						color: '#222',
						border: {
						   width: 2,
						   radius: 7,
						   color: '#888'
						} /*,
						tip: {
							corner: 'bottomRight',
							size: {
								x: 15,
								y: 10
							}
						} */
					}
			}); // end qtip
		}); // end each
	};
}
);
