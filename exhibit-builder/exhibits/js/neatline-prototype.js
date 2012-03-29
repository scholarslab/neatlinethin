if(typeof(Omeka) == 'undefined') {
	Omeka = new Object();
}

/**
* The NeatlineController class is an Omeka extension that allows integration of the OpenLayers
* api and the Simile Timeline api. See online documentation at http://scholarslab.org/
*
* @author Sam Eberspacher, Scholars' Lab
* @version 1.0a
*/

Omeka.NeatlineController = Class.create({
/**
* Default options for the NeatlineController
* 
* @param {int}		band_index			the index of the band containing interactive events
* @param {int}		overview_index		the index of the band where map ranges should be painted
* @param {boolean}	paint_map_ranges	Specifies whether or not map ranges should be painted.
* @param {hash}		controls			Defines the controls which will appear when in the event dialog when
* 										an event is clicked. The key/pair combination should be
* 										"display name":	function(event). If the function is defined in the
* 										class or in a sub-class, it should be wrapped in an anonymous function
* 										to preserve context. See online documentation for further examples.
* @param {function}	scroll_callback		callback function that is called when the main band is scrolled	
*/
	opts: {
		band_index: 0,
		overview_index: 1,
		paint_map_ranges: true,
		controls: {
			'Zoom to this': function(evt) { this.zoom_only(evt); },
			'Add to view': function(evt) { this.zoom_include(evt); }
		},
		scroll_callback: function(band) { this.pick_base(band); },
		layer_switching: true,
		colors: ['#ffffaa', '#ffaaaa', '#ff00000', '#aaffaa', '#aaaaff']
	},
/**
* initialize(map, timeline[, options])
* Creates the object and defines class variables.
* 
* @param {OpenLayers.Map}	map			the map to be controlled
* @param {Simile Timeline}	timeline	the timeline to be controlled
* @param {hash}				options		Optional. The custom options to be used for this Controller
*/
	initialize: function(map, timeline, options) {
		Object.extend(this.opts, options || {});
		this.opts.controls = $H(this.opts.controls);

		this.wkt = new OpenLayers.Format.WKT();

		this.layers = $H();
		this.map = map;
		var ls = this.map.getControlsByClass('OpenLayers.Control.LayerSwitcher');
		if(ls.size() == 1)
			this.append_ls_controls(ls[0]);
		this.select_control = this.create_select_control();
		this.map.addControl(this.select_control);
		this.select_control.activate();
		this.map.events.register('addlayer', this, this.layer_added);
		this.map.getLayersBy('isBaseLayer', true).each(function(layer) {
			this.parse_dates(layer);
		}, this);

		this.timeline = timeline;
		this.timeline._bands.each(function(band) {
			var painter = band.getEventPainter();
			painter._showBubble = this.show_event.bind(this, painter);
			
			if(band.getIndex() == this.opts.band_index) {
				band.addOnScrollListener(function() {
					this.opts.scroll_callback.apply(this, [band]);
				}.bind(this));
			}
		}, this);
		
		if(this.opts.paint_map_ranges)
			this.paint_ranges(this.timeline, this.map.getLayersBy('isBaseLayer', true));
	},
	append_ls_controls: function(control) {
		var title_div = new Element('div', { 'class' : 'dataLbl'}).update('Neatline');
		var control_div = new Element('div', { 'class' : 'dataLayersDiv', 'id' : 'neatline-group'});
		var layer_switch = new Element('input', { 'type' : 'checkbox', 'id' : 'neatline-ls'})
			.observe('click', function(evt) {
				OpenLayers.Event.stop(evt, true);
				if(evt.target.checked)
					this.opts.layer_switching = true;
				else
					this.opts.layer_switching = false;
			}.bind(this));
		layer_switch.checked = this.opts.layer_switching;
		var layer_switch_label = new Element('span', {'class' : 'labelSpan'}).setStyle({verticalAlign: 'baseline'}).update('Automatic Layer Switching');
		var add_image = new Element('div', { 'id' : 'add-layer' });
		var add_base_layer = new Element('a', {'href': '#', 'title': 'Adds a map from the Omeka database to the current list of base layers.'})
			.update('Add a base layer')
			.observe('click', function(evt) {
				OpenLayers.Event.stop(evt);
				jQuery('#addlayerdialog').dialog('open');
			});

		var insert_here = $(control.id + '_layersDiv');
		insert_here.insert(title_div);
		control_div.insert(layer_switch);
		control_div.insert(layer_switch_label);
		control_div.insert(add_image);
		add_image.insert(add_base_layer);
		add_base_layer.wrap('span');
		insert_here.insert(control_div);
	},
/**
* layer_added(evt)
* Callback function executed when a layer is added to the map.
* 
* @param {OpenLayers.Events}	evt		the event object dispactched from the OpenLayers map. Contains
* 										the added layer in a property titled "layer"
*/
	layer_added: function(evt) {
		var layer = evt.layer;
		if(layer.isBaseLayer)
			this.parse_dates(layer);
	},
/**
* Convenience function to override default options in the SelectFeature Object.
* 
* @return New OpenLayers.Control.SelectFeature
*/
	create_select_control: function() {
		return new OpenLayers.Control.SelectFeature([], {
			toggle: true,
			onSelect: function(feature) { this.feature_selected(feature); }.bind(this)
		});
	},
/**
* feature_selected(feature)
* Convenience function to override the default onSelect callback from the SelectFeature Object.
* This functions finds the corresponding Timeline event and displays the event dialog
* 
* @param {OpenLayers.Feature}	feature		the feature that was selected
*/
	feature_selected: function(feature) {
		var evt_id = this.layers.index(feature.layer.id);
		this.timeline._bands.each(function(band) {
			var evt = band.getEventSource().getEvent(evt_id);
			if(evt) {
				band.getEventPainter().showBubble(evt);
				throw $break;
			}
		});
	},
/**
* show_event(painter, x, y, evt)
* Creates the wrapping HTML to display the content created by Timeline.Event.fillInfoBubble.
* 
* @param {Timeline.EventPainter}	painter		the painter used in the Timeline object. This allows the
* 												styling of the event window content to be the same as it
* 												would have been rendered using just a Timeline.
* @param {int}						x			the horizontal pixel coordinate for the dialog. Ignored
* @param {int}						y			the vertical pixel coordinate for the dialog. Ignored
* @param {Timeline.Event}			evt			the Timeline event to be displayed
*/
	show_event: function(painter, x, y, evt) {
		if(!$('neatline-event-' + evt.getEventID()))
			this.new_dialog(evt);
		var div = $('neatline-event-' + evt.getEventID()).update();
		evt.fillInfoBubble(div, painter._params.theme, painter._band.getLabeller());
		jQuery('#neatline-event-'+evt.getEventID()).dialog('open');
	},
/**
* new_dialog(evt)
* Creates a new dialog window using the jQuery UI
* 
* @param {Timeline.Event}	evt		the Timeline event that should be displayed
*/
	new_dialog: function(evt) {
		id = evt.getEventID();
		var wrap = new Element('div', { 'id': 'neatline-event-' + id });
		jQuery(wrap).dialog({
			autoOpen: false,
			buttons: (function(nc) {
                        var buttons = {};
                        nc.opts.controls.each(function(pair) {
                            buttons[pair.key] = function() {};
                        });
                        return buttons;
                    })(this),
			dialogClass: 'neatline-dialog-'+id,
			zIndex: 1500
		});
		this.bind_dialog_events(evt);
	},
/**
* bind_dialog_events(evt)
* Binds the controls defined in {NeatlineController.opts.controls} to the buttons in the jQuery dialog.
* Necessary because of context issues.
* 
* @see #new_dialog
* @param {Timeline.Event}	evt		the Timeline event that is to be displayed
*/
	bind_dialog_events: function(evt) {
		var id = evt.getEventID();
		$$('.neatline-dialog-' + id + ' > .ui-dialog-buttonpane button')
			.each(function(button) {
				var key = button.innerHTML;
				button.observe('click', function() {
					this.opts.controls.get(key).apply(this, [evt]);
				}.bind(this));
		}, this);
	},
/**
* register_control(display, func)
* Sets the control text and callback to be rendered in the event dialog. Stored in 
* "Omeka.NeatlineController.opts.controls" as { "display": "func" }
* 
* @see #render_controls
* @param {String}	display		the text to display on the dialog button
* @param {function}	func		the function to be executed when the dialog button is clicked
*/
	register_control: function(display, func) {
		this.opts.controls.set(display, func);
	},
/**
* get_event_layer(evt)
* Retrieves the {OpenLayers.Layer} associated with "evt". If the Layer has already been added to the
* map then the layer is pulled from "this.layers" via the associative key "evt.getEventID()". Otherwise,
* an AJAX request is made and the layer is added to the map before it is returned.
* 
* @param {Timeline.Event}	evt		the Timline event to be displayed
* @return the layer that was added to the map
* @type {OpenLayers.Layer}
*/
	get_event_layer: function(evt) {
		var event_id = evt.getID();
		var layer = null;
		if(this.layers.get(event_id)) {
			var layer_id = this.layers.get(event_id);
			layer = this.map.getLayer(layer_id);
		} else {
			var url_base_index = window.location.pathname.indexOf('exhibits/show');
			var url_base = window.location.pathname.substr(0, url_base_index);
			new Ajax.Request(url_base + 'features/wkt/' + evt.getEventID(), {
				method: "get",
				asynchronous: false,
				onSuccess: function(response) {
					layer = this.draw_vector(evt, response.responseText);
				}.bind(this)
			});
		}
		return layer;
	},
/**
* draw_vector(evt, text)
* Draws a vector layer on the map using WKT
* 
* @see #get_event_layer
* @param {Timeline.Event}	evt		the Timeline event to be displayed. The title of the event is used as
* 									the layer title.
* @param {String}			text	the WKT formatted text to be rendered on the layer
* @return the rendered layer
* @type {OpenLayers.Layer.Vector}
*/
	draw_vector: function(evt, text) {
		var vector = new OpenLayers.Layer.GML(evt.getText());
		this.layers.set(evt.getID(), vector.id);
		this.map.addLayer(vector);
		var features = this.wkt.read(text);
		if(features.constructor != Array) {
			features = [features];
		}
		vector.addFeatures(features);
		this.make_layer_selectable(vector);
		return vector;
	},
/**
* make_layer_selectable(layer)
* Makes the added layer selectable from the {OpenLayers.Control.SelectFeature} attached to the Map
* 
* @param {OpenLayers.Layer.Vector}	layer	the layer to be made selectable
*/
	make_layer_selectable: function(layer) {
		var layers = this.map.getLayersByClass('OpenLayers.Layer.Vector');
		layers.push(layer);
		this.select_control.setLayer(layers);
	},
/**
* select_features(layer[, clear_selected])
* Select all features in "layer" and optionally unselects the currently selected features
* 
* @param {OpenLayers.Layer.Vector}	layer			the layer whose features should be selected
* @param {boolean}					clear_selected	whether or not currently selected deatures should
*													be unselected
*/
	select_features: function(layer, clear_selected) {
		if(clear_selected)
			this.select_control.unselectAll();
		layer.features.each(function(feature) {
			this.select_control.select(feature);
		}, this);
	},
/**
* zoom_only(evt)
* Zooms the map to include only the features within the "layer" argument. Ignores the current viewport
* 
* @param {Timeline.Event}	evt		the Timeline event to be displayed
*/
	zoom_only: function(evt) {
		var layer = this.get_event_layer(evt);
		this.select_features(layer, true);
		this.map.zoomToExtent(layer.getDataExtent());
	},
/**
* zoom_include(evt)
* Zooms the map to include the features within "layer", keeping all items in the current viewport visible.
* 
* @param {Timeline.Event}	evt		the Timeline event to be displayed
*/
	zoom_include: function(evt) {
		var layer = this.get_event_layer(evt);
		this.select_features(layer, true);
		var bounds = this.map.calculateBounds();
		console.log(bounds);
		bounds.extend(layer.getDataExtent());
		console.log(bounds);
		this.map.zoomToExtent(bounds, true);
	},
/**
* pick_base(band)
* Callback function that attempts to choose the most relevant base layer for the current view of time
* 
* @param {Timeline.Band}	band	the band whose view of time should be looked at
*/
	pick_base: function(band) {
		if(!this.opts.layer_switching)
			return;
		var current = this.map.baseLayer;
		var choice = this.base_callbacks.filter(this.map.getLayersBy('isBaseLayer', true), band, this.map);
		if(choice && current.id != choice.id) {
			this.map.setBaseLayer(choice)
		} else {
			//for dev purposes
			choice = current;
		}
	},
/** This namespace stores all of the functions that can be used as a callback for changing the base layer. */
	base_callbacks: {
/**
* An array of functions that are used to rank base layers. Each functions evaluates the layer and returns a
* score for comparision to the other layers. Layers are then filtered by top score and the next level is
* executed. The functions should be ordered taking in to account complexity and priority in order to minimize
* run time.
* 
* @param {OpenLayers.Layer}	layer	the layer to be scored
* @param {Timeline.Band}	band	the band whose view of time should be considered
* @param {OpenLayers.Map}	map		the map that the layer belongs to
* @return a score for the layer
* @type {int}
*/
		filters: $A([
			function(layer, band, map) {
				var h = 0;
				var max = band.getMaxVisibleDate();
				var min = band.getMinVisibleDate();
				var start = layer.Neatline.startdate;
				var end = layer.Neatline.enddate;
				if(start instanceof Date && start < max)
					h++;
				if(end instanceof Date && end > min)
					h++;
				if(start instanceof Date && end instanceof Date) {
					if((start < min && end < min) || (start > max && end > max))
						h--;
				} else if(!(start instanceof Date) && !(end instanceof Date))
					h--;
				return h;
			},
			function(layer, band, map) {
				var center = band.getCenterVisibleDate().getTime() / 1000;
				if(layer.Neatline.startdate && layer.Neatline.enddate)
					date = new Date(layer.Neatline.startdate.getTime() + (layer.Neatline.enddate.getTime() - layer.Neatline.startdate.getTime()) / 2);
				else
					date = layer.Neatline.startdate ? layer.Neatline.startdate : layer.Neatline.enddate;
				date = date.getTime() / 1000;
				return ((center - date) != 0) ? Math.pow(1000000 / (center - date), 2) : 1;
			}
		]),
/**
* filter(layers, band, map)
* Brokers the filtering process, keeping track of the layers and removing layers below the top score
* 
* @param {Array}			layers	the layers to be ranked
* @param {Timeline.Band}	band	the band whose view of time should be considered
* @param {OpenLayers.Map}	map		the map to which the layers belong
* @return the layer with the highest score
*/
		filter: function(layers, band, map) {
			if(layers.size() <= 1)
				return null;

			var choice = null;
			var debug = '';
			var stack = null;
			this.filters.each(function(f, i) {
				stack = $H();
				layers.each(function(layer) {
					var h = f.apply(this, [layer, band, map]);
					if(stack.get(h))
						stack.get(h).push(layer);
					else
						stack.set(h, $A([layer]));
				}, this);
				
				var max = stack.keys().collect(function(n) {
						return parseFloat(n);
					}).max();
				if(i == 0 && max < 0)
					throw $break;
				
				debug += stack.inspect().escapeHTML() + '<br>';
				
				layers = stack.get(max);
				if(layers.size() == 1) {
					choice = layers[0];
					throw $break;
				}
			}, this);
			$('hotchkiss-text').update(debug);
			return choice;
		}
	},
/**
* parse_dates(layer)
* Parses certain entries in the Neatline properpy of an {OpenLayers.Layer} into Date objects.
*
* @see #layer_added
* @param {OpenLayer.Layer}	layer	the layer that contains dates to be parsed
*/
	parse_dates: function(layer) {
		if(layer.Neatline.date) {
			var range = this.parse_range(layer.Neatline.date);
			layer.Neatline.startdate = range.start;
			layer.Neatline.enddate = range.end;
		} else {
			if(layer.Neatline.startdate) {
				var date = Timeline.DateTime.parseIso8601DateTime(layer.Neatline.startdate);
				if(!isNaN(date))
					layer.Neatline.startdate = date;
			}
			if(layer.Neatline.enddate) {
				var date = Timeline.DateTime.parseIso8601DateTime(layer.Neatline.enddate);
				if(!isNaN(date))
					layer.Neatline.enddate = date;
			}
		}
	},
/**
* parse_range(date)
* Parses a string into a range of dates. The range is for one unit the most specific unit of time given.
* Examples:
* "date" 		=> { "start", "end" }
* 1900			=> { 1900-01-01 00:00:00, 1900-12-31 23:59:59 }
* 2010-10		=> { 2010-10-01 00:00:00, 2010-10-31 23:59:59 }
* 2008-02		=> { 2008-02-01 00:00:00, 2008-02-29 23:59:59 } //Leap year
* 1956-01-14	=> { 1956-01-14 00:00:00, 1956-01-14 23:59:59 }
* 1987-04-12T12	=> { 1987-04-12 12:00:00, 1987-04-12 12:59:59 }
* 
* @param {String}	date	the date to be parsed into a range
*/
	parse_range: function(date) {
		var start_date = Timeline.DateTime.parseIso8601DateTime(date.trim());
		if(!start_date)
			return null;
		var days = $H({ '01': 31, '02': 28, '03': 31, '04': 30, '05': 31, '06': 30, '07': 31, '08': 31, '09': 30, '10': 31, '11': 30, '12': 31 });
		var defaults = $H({ year: '0', month: '12', day: '31', hour: '23', minute: '59', second: '59' });
		var end_str = '';
		split = date.split(/[^0-9]/);
		defaults.each(function(pair, i) {
			if(i < split.length)
				defaults.set(pair.key, split[i]);
			else if(i == split.length && pair.key == 'day') {
				var end_day = days.get(defaults.get('month'));
				if(end_day == 28 && this.is_leap_year(defaults.get('year')))
					end_day = 29;
				defaults.set(pair.key, end_day);
			}
			switch(pair.key) {
				case 'month':
				case 'day':
					end_str += '-';
					break;
				case 'hour':
					end_str += 'T';
					break;
				case 'minute':
				case 'second':
					end_str += ':';
					break;
			}
			end_str += defaults.get(pair.key);
		}, this);
		return { start: start_date, end: Timeline.DateTime.parseIso8601DateTime(end_str) };
	},
/**
* is_leap_year(year)
* Gregorian leap year test
* 
* @param {int}	year	the year to be tested
* @return whether or not "year" is a leap year in the Gregorian calendar
* @type {boolean}
**/
	is_leap_year: function(year) {
		if(year < 0)
			return (year + 1) % 4 == 0;
		if(year < 1582)
			return year % 4 == 0;
		if(year % 4 != 0)
			return false;
		if(year % 100 != 0)
			return true;
		if(year % 400 != 0) 
			return false;
		return true;

	},
/**
* @return A string that represents this object
*/
	toString: function() {
		return "NeatlineController";
	},
/**
* paint_ranges(timeline, layers)
* Paints the explicitly defined ranges of eligibility for an {OpenLayers.Layer} on an overview band of
* the timeline.
* 
* @param {Timeline}	timeline	the Timeline object that the band belongs to
* @param {Array}	layers		the layers to be painted on the band
*/
	paint_ranges: function(timeline, layers) {		
		var band = timeline.getBand(this.opts.overview_index);
		var evt_src = band.getEventSource();
		var add = [];
		layers.each(function(layer) {
			band_color = this.opts.colors.shift();
			this.opts.colors.push(band_color);
			startdate = (layer.Neatline.startdate && layer.Neatline.startdate instanceof Date) ? layer.Neatline.startdate : null;
			enddate = (layer.Neatline.enddate && layer.Neatline.enddate instanceof Date) ? layer.Neatline.enddate : null;
			if(startdate && enddate) {
				add.push(new Timeline.DefaultEventSource.Event({
					start: startdate,
					end: enddate,
					instant: false,
					color: band_color,
					title: layer.name,
					description: null
				}));
			}
		}, this);
		if(evt_src)
			evt_src.addMany(add);
	}
});