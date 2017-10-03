var config = custom_field_map_options;

var defaults = {
	"lat": 0,
	"lng": 0,
	"zoom": 1,
	"tiles": [ {
	"name": "Open Street Map",
	"tile": "https://tile.openstreetmap.org/{z}/{x}/{y}.png",
		"attribution": "OpenStreetMap Contributers",
		"attribution_url": "http://osm.org/copyright"
	} ]
}

for ( var prop in defaults ) {
    if ( prop in config ) { continue; }
    config[prop] = defaults[prop];
}

const latlng = localStorage.getItem( 'location' )
if ( latlng ) {
  [ config.zoom, config.lat, config.lng ] = latlng.split( ',' )
}

// Override the lat and lng from post_meta
if ( jQuery( '#' + custom_field_map_id + ' .lat' ).val() || jQuery( '#' + custom_field_map_id + ' .lng' ).val() ) {
  config.lat  = jQuery( '#' + custom_field_map_id + ' .lat' ).val();
  config.lng  = jQuery( '#' + custom_field_map_id + ' .lng' ).val();
  config.zoom = jQuery( '#' + custom_field_map_id + ' .zoom' ).val();
}

var div = document.createElement( 'div' )
div.style.width = '100%'
div.style.height = '100%'

var map_root = document.querySelector( '#map-' + custom_field_map_id );
map_root.appendChild( div );

if ( ! config.zoom ) {
	config.zoom = 14
}

if ( isNaN( parseInt( config.zoom ) ) ) {
	config.zoom = 0
}

if ( isNaN( parseFloat( config.lat ) ) ) {
	config.lat = 0
}

if ( isNaN( parseFloat( config.lng ) ) ) {
	config.lng = 0
}

var map = L.map( div, {
		scrollWheelZoom: false,
		dragging: !L.Browser.mobile,
		tap: false
	} )
	.setView( new L.LatLng( config.lat, config.lng ), config.zoom )

var basemaps = {}
for ( var i = 0; i < config.tiles.length; i++ ) {
	var layer = L.tileLayer( config.tiles[ i ].tile, {
		id: i,
		attribution: '<a href="' + config.tiles[ i ].attribution_url + '" target="_blank">' + config.tiles[ i ].attribution + '</a>'
	} )
	basemaps[ config.tiles[ i ].name ] = layer
	if ( 0 === i ) {
		map.addLayer( layer )
	}
}

if ( config.tiles.length > 1 ) {
	L.control.layers( basemaps, {}, { position: 'bottomright' } ).addTo( map )
}

var marker = L.marker()
marker.setLatLng( [ jQuery( '#' + custom_field_map_id + ' .lat' ).val(), jQuery( '#' + custom_field_map_id + ' .lng' ).val() ] ).addTo( map )

map.on( 'click', function( e ) {
	var lat = e.latlng.lat
	var lng = e.latlng.lng
	if ( lng > 180 ) {
		while( lng > 180 ) {
		lng = lng - 360
		}
	} else if ( lng < -180 ) {
		while( lng < -180 ) {
		lng = lng + 360
		}
	}

	jQuery( '#' + custom_field_map_id + ' .lat' ).val( lat )
	jQuery( '#' + custom_field_map_id + ' .lng' ).val( lng )
	jQuery( '#' + custom_field_map_id + ' .zoom' ).val( e.target._zoom )

	marker.setLatLng( [ e.latlng.lat, e.latlng.lng ] ).addTo( map )
} )

map.on( 'moveend', function( e ) {
	var zoom = e.target._zoom
	var center = map.getCenter()
	var lat = center.lat
	var lng = center.lng
	if ( lng > 180 ) {
		while( lng > 180 ) {
			lng = lng - 360
		}
	} else if ( lng < -180 ) {
		while( lng < -180 ) {
			lng = lng + 360
		}
	}
	jQuery( '#' + custom_field_map_id + ' .zoom' ).val( zoom )
	window.localStorage.setItem( 'location', zoom + ',' + lat + ',' + lng )
} )
