var config = {
	"lat": 0,
	"lng": 0,
	"zoom": 1,
	"layers": [ {
	"name": "Open Street Map",
	"tile": "https://tile.openstreetmap.org/{z}/{x}/{y}.png",
		"attribution": "OpenStreetMap Contributers",
		"attribution_url": "http://osm.org/copyright"
	} ]
}

const latlng = localStorage.getItem( 'location' )
if ( latlng ) {
  [ config.zoom, config.lat, config.lng ] = latlng.split( ',' )
}

// Override the lat and lng from post_meta
if ( jQuery( '#geonic-lat' ).val() && jQuery( '#geonic-lng' ).val() ) {
	config.lat = jQuery( '#geonic-lat' ).val();
	config.lng = jQuery( '#geonic-lng' ).val();
}

riot.mount( "map", config )
