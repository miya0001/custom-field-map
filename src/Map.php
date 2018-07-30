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
	public function __construct( $id, $title, $options = array() )
	{
		parent::__construct( $id, $title, $options );
		add_action( 'wp_enqueue_scripts', array( $this, 'register_scripts' ) );
		add_action( 'admin_enqueue_scripts', array( $this, 'register_scripts' ), 9 );
	}

	public function register_scripts()
	{
		wp_register_script(
			'leaflet',
			plugins_url( 'lib/leaflet/dist/leaflet.js', dirname( __FILE__ ) ),
			array(),
			false,
			true
		);
		wp_register_style(
			'leaflet',
			plugins_url( 'lib/leaflet/dist/leaflet.css', dirname( __FILE__ ) ),
			array(),
			false
		);

		if ( ! is_admin() ) {
			wp_enqueue_script(
				'custom-field-map',
				plugins_url( 'js/custom-field-map.js', dirname( __FILE__ ) ),
				array( 'leaflet' ),
				false,
				true
			);
		}
	}

	public function get_map( $post_id )
	{
		$meta = get_post_meta( $post_id, $this->id, true );
		if ( ! $meta ) {
			return;
		}

		$atts = array(
			'class' => 'cf-map',
			'data-id' => $this->id,
			'data-post-id' => $post_id,
			'data-lat' => $meta['lat'],
			'data-lng' => $meta['lng'],
			'data-zoom' => $meta['zoom'],
		);

		$html = '<div';
		foreach ( $atts as $name => $value ) {
			$html .= ' ' . $name . '="' . esc_attr( $value ) . '"';
		}
		$html .= '></div>';

		return $html;
	}

	/**
	 * Fires at the `admin_enqueue_scripts` hook.
	 *
	 * @param string $hook
	 */
	public function admin_enqueue_scripts( $hook )
	{
		wp_enqueue_script(
			'custom-field-map-admin',
			plugins_url( 'js/admin.js', dirname( __FILE__ ) ),
			array( 'leaflet' ),
			false,
			true
		);

		wp_enqueue_style( 'leaflet' );

		wp_enqueue_style(
            "custom-field-map-admin-css",
			plugins_url( 'css/admin.css', dirname( __FILE__ ) ),
            false,
            false
        );
	}

	/**
	 * Displays the form for the metabox. The nonce will be added automatically.
	 *
	 * @param \WP_Post $post The object of the post.
	 * @param array $args The argumets passed from `add_meta_box()`.
	 */
	public function form( $post, $args )
	{
		$meta = get_post_meta( get_the_ID(), $this->id, true );

		if ( empty( $meta ) ) {
			$meta = array();
		}

		?>
			<div id="map-<?php echo esc_attr( $this->id ); ?>" style="width=100%; height:300px;"><map></map></div>

			<table class="custom-field-map-table">
				<tr>
					<th>Lattitude</th>
					<td><input class="lat" type="text"
						name="<?php echo esc_attr( $this->id ); ?>[lat]"
                               value="<?php echo @esc_attr( $meta['lat'] ); ?>"></td>
				</tr>
                <tr>
                    <th>Longitude</th>
                    <td><input class="lng" type="text"
                        name="<?php echo esc_attr( $this->id ); ?>[lng]"
                               value="<?php echo @esc_attr( $meta['lng'] ); ?>"></td>
                </tr>
			</table>
            <input class="zoom" type="hidden"
                   name="<?php echo esc_attr( $this->id ); ?>[zoom]"
                   value="<?php echo @esc_attr( $meta['zoom'] ); ?>">
			<script>
				var custom_field_map_id = '<?php echo esc_js( $this->id ); ?>';
				var custom_field_map_options = <?php echo json_encode( $this->options ); ?>;
			</script>
		<?php
	}

	/**
	 * Save the metadata from the `form()`. The nonce will be verified automatically.
	 *
	 * @param int $post_id The ID of the post.
	 */
	public function save( $post_id )
	{
		if ( isset( $_POST[ $this->id ] ) ) {
			update_post_meta( $post_id, $this->id, $_POST[ $this->id ] );
		}
	}
}
