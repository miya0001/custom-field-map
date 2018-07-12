function Map() {}

Map.prototype.init = function() {
	var el = document.querySelectorAll( '.cf-map' );
	var maps = [];
	for ( var i = 0; i < el.length; i++ ) {
		var id = el[i].getAttribute( 'data-post-id' );
		var map = this.display( el[i] );
		map.postId = id;
		maps.push( map )
	}
	return maps;
}

Map.prototype.display = function( map_container ) {
	var lat = map_container.getAttribute( 'data-lat' );
	var lng = map_container.getAttribute( 'data-lng' );
	var zoom = map_container.getAttribute( 'data-zoom' );
	var icon_url = map_container.getAttribute( 'data-icon' );

	// Initialize the map.
	var map = L.map( map_container, {
		scrollWheelZoom: false,
		dragging: !L.Browser.mobile,
		tap: false
	} );
	map.setView( [ lat, lng ], zoom );
	L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
		attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
	}).addTo(map);

	var marker = L.marker( [ lat, lng ] ).addTo( map );

	return map;
}

var m = new Map();
window.maps = m.init();
