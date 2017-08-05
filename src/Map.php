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
		$screen = get_current_screen();
		if ( ( 'post-new.php' === $hook || 'post.php' === $hook ) && $this->post_type === $screen->post_type ) {
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
	}

	/**
	 * Fires at the `meta_box_callback` hook.
	 *
	 * @param object $post A object of the post.
	 * @return none
	 */
	public function meta_box_callback( $post )
	{
		$tag = plugins_url( 'tags/map.tag', dirname( __FILE__ ) );
		wp_nonce_field( $this->id, $this->id . '-nonce' );
		?>
			<div id="<?php echo esc_attr( $this->id ); ?>-map" style="width=100%; height:300px;"><map></map></div>
			<p class="">
				Latitude: <input id="<?php echo esc_attr( $this->id ); ?>-lat" type="text"
					name="<?php echo esc_attr( $this->id ); ?>-lat"
					value="<?php echo esc_attr( get_post_meta( get_the_ID(), '_'.$this->id.'-lat', true ) ); ?>">
				Longitude: <input id="<?php echo esc_attr( $this->id ); ?>-lng" type="text"
					name="<?php echo esc_attr( $this->id ); ?>-lng"
					value="<?php echo esc_attr( get_post_meta( get_the_ID(), '_'.$this->id.'-lng', true ) ); ?>">
			</p>
			<script src="<?php echo esc_url( $tag ); ?>" type="riot/tag"></script>
		<?php
	}

	/**
	 * Fires at the `save_post` hook.
	 *
	 * @param int $post_id An ID of the post.
	 * @return none
	 */
	public function save_post( $post_id )
	{
		if ( ! empty( $_POST[ $this->id . '-nonce' ] ) && wp_verify_nonce( $_POST[ $this->id . '-nonce' ], $this->id ) ) {
			update_post_meta( $post_id, '_' . $this->id . '-lat', $_POST[ $this->id . '-lat' ] );
			update_post_meta( $post_id, '_' . $this->id . '-lng', $_POST[ $this->id . '-lng' ] );
		}
	}
}
