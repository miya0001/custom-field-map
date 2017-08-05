<map class="map" style="width: 100%; height: 100%;">
  <script>
    // var Clipboard = require( 'clipboard' )
    var div = document.createElement( 'div' )
    this.root.appendChild( div )
    div.style.width = '100%'
    div.style.height = '100%'

    if ( ! opts.zoom ) {
      opts.zoom = 14
    }

    if ( isNaN( parseInt( opts.zoom ) ) ) {
      opts.zoom = 0
    }

    if ( isNaN( parseFloat( opts.lat ) ) ) {
      opts.lat = 0
    }

    if ( isNaN( parseFloat( opts.lng ) ) ) {
      opts.lng = 0
    }

    var map = L.map( div, { scrollWheelZoom: false } )
		.setView( new L.LatLng( opts.lat, opts.lng ), opts.zoom )

    var layers = opts.layers

    var basemaps = {}
    for ( var i = 0; i < layers.length; i++ ) {
      var layer = L.tileLayer( layers[ i ].tile, {
        id: i,
        attribution: '<a href="' + layers[ i ].attribution_url + '" target="_blank">' + layers[ i ].attribution + '</a>'
      } )
      basemaps[ layers[ i ].name ] = layer
      if ( 0 === i ) {
        map.addLayer( layer )
      }
    }

    if ( layers.length > 1 ) {
      L.control.layers( basemaps, {}, { position: 'bottomleft' } ).addTo( map )
    }

    var marker = L.marker()
	marker.setLatLng( [ jQuery( '#geonic-lat' ).val(), jQuery( '#geonic-lng' ).val() ] ).addTo( map )

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

	  jQuery( '#geonic-lat' ).val( lat )
	  jQuery( '#geonic-lng' ).val( lng )

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
	  window.localStorage.setItem( 'location', zoom + ',' + lat + ',' + lng )
    } )
  </script>
</map>
