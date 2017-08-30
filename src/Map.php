<?php
/**
 * Custom Field Map
 *
 * @package   miya/custom-field-map
 * @author    Takayuki Miyauchi
 * @license   GPL v2
 * @link      https://github.com/miya0001/custom-field-map
 */

namespace Miya\WP\Custom_Field;

class Map extends \Miya\WP\Custom_Field
{
	/**
	 * Fires at the `admin_enqueue_scripts` hook.
	 *
	 * @param none
	 * @return none
	 */
	public function admin_enqueue_scripts( $hook )
	{
		wp_enqueue_script(
			'riot',
			'https://cdn.jsdelivr.net/npm/riot@3.6/riot+compiler.min.js',
			array(),
			false,
			true
		);
		wp_enqueue_script(
			'leaflet',
			'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.1.0/leaflet.js',
			array(),
			false,
			true
		);
		wp_enqueue_script(
			'app',
			plugins_url( 'js/app.js', dirname( __FILE__ ) ),
			array( 'jquery', 'riot', 'leaflet' ),
			false,
			true
		);
		wp_enqueue_style(
			'leaflet',
			'https://cdnjs.cloudflare.com/ajax/libs/leaflet/1.1.0/leaflet.css',
			array(),
			false
		);
	}

	/**
	 * Displays the form for the metabox. The nonce will be added automatically.
	 *
	 * @param object $post The object of the post.
	 * @param array $args The argumets passed from `add_meta_box()`.
	 * @return none
	 */
	public function form( $post, $args )
	{
		$tag = plugins_url( 'tags/map.tag', dirname( __FILE__ ) );
		$meta = get_post_meta( get_the_ID(), $this->id, true );

		if ( empty( $meta ) || empty( $meta['lat'] ) || empty( $meta['lng'] ) ) {
			$meta = array( 'lat' => '', 'lng' => '' );
		}

		?>
			<div id="custom-field-map" style="width=100%; height:300px;"><map></map></div>
			<input id="custom-field-map-lat" type="hidden"
				name="<?php echo esc_attr( $this->id ); ?>-latlng[lat]"
				value="<?php echo esc_attr( @$meta['lat'] ); ?>">
			<input id="custom-field-map-lng" type="hidden"
				name="<?php echo esc_attr( $this->id ); ?>-latlng[lng]"
				value="<?php echo esc_attr( @$meta['lng'] ); ?>">
			<input id="custom-field-map-zoom" type="hidden"
				name="<?php echo esc_attr( $this->id ); ?>-latlng[zoom]"
				value="<?php echo esc_attr( @$meta['zoom'] ); ?>">
			<script src="<?php echo esc_url( $tag ); ?>" type="riot/tag"></script>
		<?php
	}

	/**
	 * Save the metadata from the `form()`. The nonce will be verified automatically.
	 *
	 * @param int $post_id The ID of the post.
	 * @return none
	 */
	public function save( $post_id )
	{
		if ( isset( $_POST[ $this->id . '-latlng' ] ) ) {
			update_post_meta( $post_id, $this->id, $_POST[ $this->id . '-latlng' ] );
		}
	}
}
