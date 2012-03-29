if (Omeka.Neatline) { 
	Omeka.Neatline.jQuery = jQuery.noConflict();
}
else {
	Omeka.Neatline = new Object();
	Omeka.Neatline.jQuery = jQuery.noConflict();
}
	
Omeka.Neatline.jQuery(document).ready(function() {
	(function(jq) {
		jq.extend({
			escapeSelector: function(selector) {
				return selector.replace(/([#;&,\.\+\*~':"!\^\$\[\]\(\)=>|\/\\])/g, '\\$1');
			}
		});
		jq.widget('ui.neatline', {
			options: {
				map: null,
				timeline: null,
				bandIndex: 0,
				overviewIndex: 1,
				paintMapRanges: true,
				layerSwitching: true,
				colors: ['#ffffaa', '#ffaaaa', '#ff00000', '#aaffaa', '#aaaaff']
			},
			config: function() {return this.options; },
			parser: new OpenLayers.Format.GML(),
			_init: function() {
				if(!this.options.map || !this.options.timeline) {
					return;
				}
				
				var self = this;

				var ls = this.options.map.getControlsByClass('OpenLayers.Control.LayerSwitcher');
				if(ls.size == 1)
					this._appendLayerSwitcherControls(ls[0]);
				this.options.selectControl = new OpenLayers.Control.SelectFeature([], {
													toggle: true,
													onSelect: function(feature) { this.feature_selected(feature); }
												});
				this.options.map.addControl(this.options.selectControl);
				this.options.selectControl.activate();
				this.options.map.events.register('addlayer', this, this._layerAdded);	//CHECK CALLBACKS IN jq
				jq.each(this.options.map.getLayersBy('isBaseLayer', true), function() { //CONTEXT
					//self._parseDates(this);
				});
		
				jq.each(this.options.timeline._bands, function() {
					var painter = this.getEventPainter();
					painter._showBubble = function(x, y, event) { self._showEvent(x, y, event, painter) };	//CONTEXT IN jq
					
					if(this.getIndex() == self.options.bandIndex) {
						this.addOnScrollListener(function() {
							self.pickBase(this); //Scroll_callback
						});
					}
				});
				
				if(this.options.paintMapRanges)
					this._paintRanges(this.options.timeline, this.options.map.getLayersBy('isBaseLayer', true));
			},
			_appendLayerSwitcherControls: function(control) {
				var title_div = jq('<div/>', {class : 'dataLbl', text: 'Neatline'});
				var control_div = jq('<div/>', {class : 'dataLayersDiv', id : 'neatline-group'});
				var layer_switch = jq('<input/>', {type : 'checkbox', id : 'neatline-ls', checked: this.options.layerSwitching})
					.bind('click', { 'controller': this }, function(evt) {
						OpenLayers.Event.stop(evt, true);
						if(evt.target.checked)
							evt.data.controller.option('layerSwitching', true);
						else
							evt.data.controller.option('layerSwitching', false);
					});
				var layer_switch_label = jq('<span/>', {class : 'labelSpan', text: 'Automatic Layer Switching'}).css({verticalAlign: 'baseline'});
				var add_image = jq('<div/>', { id : 'add-layer' });
				var add_base_layer = jq('<a/>', {href: '#', title: 'Adds a map from the Omeka database to the current list of base layers.'})
					.html('Add a base layer')
					.bind('click', function(evt) {
						OpenLayers.Event.stop(evt);
						jq('#addlayerdialog').dialog('open');
					});
		
				var insert_here = jq('#' + jq.escapeSelector(control.id) + '_layersDiv');
				insert_here.append(title_div);
				control_div.append(layer_switch);
				control_div.append(layer_switch_label);
				control_div.append(add_image);
				add_image.append(add_base_layer);
				//add_base_layer.wrap('span');
				insert_here.append(control_div);
			},
			_layerAdded: function(evt) {
				console.log('Layer Added');
				var layer = evt.layer;
				if(layer.isBaseLayer)
					this._parseDates(layer);
			},
			_featureSelected: function(feature) {
				var evt_id = this.options.layers.index(feature.layer.id);			//BROKEN -- DO SEARCH
				jq.each(this.options.timeline._bands, function() {
					var evt = this.getEventSource().getEvent(evt_id);
					if(evt) {
						this.getEventPainter().showBubble(evt);
						return false;
					}
				});
			},
			_showEvent: function(x, y, evt, painter) {
				if(!jq('#neatline-event-' + evt.getEventID()))
					this._newDialog(evt);
				var div = jq('#neatline-event-' + evt.getEventID());
				evt.fillInfoBubble(div, painter._params.theme, painter._band.getLabeller());
				jq('#neatline-event-'+evt.getEventID()).dialog('open');
			},
			_newDialog: function(evt) {						//AJAX HERE!!!
				id = evt.getEventID();
				var wrap = jq('<div/>', { 'id': 'neatline-event-' + id });
				var url_base_index = window.location.pathname.indexOf('exhibits/show');
				var url_base = window.location.pathname.substr(0, url_base_index);
				jq.get(url_base + 'timelines/panel/' + evt.getEventID(), function(response) {
					wrap.html(response)
						.dialog({
							autoOpen: false,
							dialogClass: 'neatline-dialog-'+id,
							zIndex: 1500
						});
				});
				jq(document).append(wrap);
					
				//this._bindDialogEvents(evt);
			},
			_bindDialogEvents: function(evt) {				//PARTIALS?
				var self = this;
				var id = evt.getEventID();
				jq('.neatline-dialog-' + id + ' > .ui-dialog-buttonpane button')
					.each(function() {
						var key = this.innerHTML;
						this.bind('click', function() {
							self.options.controls.get(key).apply(this, [evt]);
						}.bind(this));
				}, this);
			},
			
			illustrate: function(id,inputNameStem) {
				var featurelayer = this._get_item_layer(id);
				jq("<form></form>").appendTo();
				var panel = Omeka.NeatlineFeatures.createDrawingControlPanel(
						featurelayer,inputNameStem, document.getElementById("mappanel"));
			    this.map.addControl(panel);
			    
			},
			/**
			* zoom_only(id)
			* Zooms the map to include only the features attached to Item id. Ignores the current viewport
			* 
			* @param {Integer}	id		the Item event that owns the features to be displayed
			*/
			zoom_only: function(id) {
				var layer = this._get_item_layer(id);
				//this.select_features(layer, true);
				this.options.map.zoomToExtent(layer.getDataExtent());
			},
			
			/**
			 * _get_item_layer(id)
			 * Returns an <code>OpenLayers.Layer.Vector</code> containing the features
			 * in Omeka Item with id {id}. This may be a pre-existing layer if this layer
			 * has already been drawn, or a freshly-created one if it hasn't.
			 * 
			 * @param {int} Omeka Item ID
			 * @return {OpenLayers.Layer.Vector} Vector layer loaded with features
			 */
			_get_item_layer: function(id) {
				console.group("_get_item_layer("+id+"):");
				var layer = null;
				var possible_layers = this.options.map.getLayersBy("omekaitemid",id);
				console.log("Found these possible layers : ");
				console.log(possible_layers);
				if (possible_layers.length > 0) {
					console.log("Layer with Omeka Item id "+ id + " was found in map");
					layer = possible_layers[0];
				} else {
					console.log("Trying to load layer with Omeka Item id "+ id);	
					layer = this._newVectorLayer(id);
				}
				console.groupEnd();
				return layer;
			},
			
			/**
			 * _newVectorLayer(id)
			 * 
			 * Creates a new <code>OpenLayers.Layer.Vector</code> using the features
			 * in the Omeka Item with id {id}. We leave {id} as a field on the new 
			 * layer called omekaitemid for later reference and do a synchronous
			 * HTTP request to get the GML of the features to add.
			 * 
			 * @param {integer}     id     The Omeka Item ID of the wanted item-features
			 * @return {OpenLayers.Layer.Vector}  A new vector layer with the appropriate features and omekaitemid
			 */
			_newVectorLayer: function(id) {
				console.group("_newVectorLayer("+id+"):");
				var vector = new OpenLayers.Layer.Vector("Features from Omeka Item " + id);
				console.log("New layer created: ");
				console.log(vector);
				vector.omekaitemid=id;
				var url_base_index = window.location.pathname.indexOf('exhibits/show');
				var url_base = window.location.pathname.substr(0, url_base_index);
				var gml = jq.ajax({
					async: false,
					url: url_base + 'features/gml/' + id
					}).responseText;
				
				this.options.map.addLayer(vector);
				var features = this.parser.read(gml);
				//if(features.constructor != Array) {
				//	features = [features];
				//}
				console.log("Features retrieved: ");
				console.log(features);
				vector.addFeatures(features);
				console.log("Now new layer is: ");
				console.log(vector);
				this._makeLayerSelectable(vector);
				console.groupEnd();
				return vector;
			},
			
			_makeLayerSelectable: function(layer) {
				//console.group("_makeLayerSelectable:");
				//var layers = this.map.getLayersByClass('OpenLayers.Layer.Vector');
				//layers.push(layer);
				this.options.selectControl.setLayer(layer);
				//console.groupEnd();
			},
			_paintRanges: function(timeline, layers) {
				var band = timeline.getBand(this.options.overviewIndex);
				var evt_src = band.getEventSource();
				var add = [];
				var self = this;
				jq.each(layers, function() {
					band_color = self.options.colors.shift();
					self.options.colors.push(band_color);
					startdate = (this.Neatline.startdate && this.Neatline.startdate instanceof Date) ? this.Neatline.startdate : null;
					enddate = (this.Neatline.enddate && this.Neatline.enddate instanceof Date) ? this.Neatline.enddate : null;
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
				});
				if(evt_src)
					evt_src.addMany(add);
			},
			_parseDates: function(layer) {
				if(layer.Neatline.date) {
					var range = this._parseRange(layer.Neatline.date);
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
			_parseRange: function(date) {
				var start_date = Timeline.DateTime.parseIso8601DateTime(jq.trim(date));
				if(!start_date)
					return null;
				var days = { '01': 31, '02': 28, '03': 31, '04': 30, '05': 31, '06': 30, '07': 31, '08': 31, '09': 30, '10': 31, '11': 30, '12': 31 };
				var defaults = { year: '0', month: '12', day: '31', hour: '23', minute: '59', second: '59' };
				var end_str = '';
				var i = 0;
				split = date.split(/[^0-9]/);
				jq.each(defaults, function(key, value) {
					if(i < split.length)
						defaults[key] = split[i];
					else if(i == split.length && key == 'day') {
						var end_day = days[defaults['month']];
						if(end_day == 28 && this.is_leap_year(defaults['year']))
							end_day = 29;
						defaults[key] = end_day;
					}
					switch(key) {
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
					end_str += defaults[key];
					i++;
				});
				return { start: start_date, end: Timeline.DateTime.parseIso8601DateTime(end_str) };
			},
			pickBase: function(band) {
				if(!this.options.layerSwitching)
					return;
				var current = this.options.map.baseLayer;
				var choice = this.baseCallbacks.filter(this.options.map.getLayersBy('isBaseLayer', true), band, this.options.map);
				if(choice && current.id != choice.id) {
					this.options.map.setBaseLayer(choice)
				} else {
					//for dev purposes
					choice = current;
				}
			},
			/*
			_getEventLayer: function(evt) {
				var event_id = evt.getID();
				var layer = null;
				if(this.options.layers.event_id) {
					var layer_id = this.options.layers.event_id;
					layer = this.options.map.getLayer(layer_id);
				} else {
					var self = this;
					var url_base_index = window.location.pathname.indexOf('exhibits/show');
					var url_base = window.location.pathname.substr(0, url_base_index);
					jq.ajax({
						async: false,
						url: url_base + 'features/gml/' + evt.getEventID(),
						success: function(response) {
							layer = self._drawVector(evt, response);
						}
					});
				}
				return layer;
			}, */
		/** This namespace stores all of the functions that can be used as a callback for changing the base layer. */
			baseCallbacks: {
				filters: [
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
				],
				filter: function(layers, band, map) {
					if(layers.size() <= 1)
						return null;
		
					var choice = null;
					var debug = '';
					var stack = null;
					var self = this;
					var i = 0;
					jq.each(this.filters, function() {
						var f = this;
						stack = {};
						jq.each(layers, function(layer) {
							var h = f.apply(self, [this, band, map]);
							if(stack[h])
								stack[h].push(layer);
							else
								stack[h] = new Array(layer);
						});

						var max = null;
						for(key in stack) {
							var num = parseFloat(key);
							if(max == null || num > max) {
								max = num;
							}
						}

						if(i == 0 && max < 0)
							return false;
						
						layers = stack[max];
						if(layers.size() == 1) {
							choice = layers[0];
							return false;
						}
						i++;
					});
					console.log(debug);
					return choice;
				}
			}
		});
	})(Omeka.Neatline.jQuery);
});