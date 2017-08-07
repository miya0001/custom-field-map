<?php

class Custom_Field_Map_Tests extends WP_UnitTestCase
{
	/**
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	public
	function test_enqueue_fires_on_post()
	{
		$map = new Miya\WP\Custom_Field\Map( 'map', 'Map' );
		$map->add( 'post' );

		convert_to_screen( 'post' );
		set_current_screen( 'post-new.php' );
		do_action( 'admin_enqueue_scripts', 'post-new.php' );
		$this->assertTrue( wp_style_is( 'leaflet' ) );
		$this->assertTrue( wp_script_is( 'riot' ) );
		$this->assertTrue( wp_script_is( 'leaflet' ) );
		$this->assertTrue( wp_script_is( 'app' ) );
	}

	/**
	 * @runInSeparateProcess
	 * @preserveGlobalState disabled
	 */
	function test_enqueue_fires_on_page() {
		$map = new Miya\WP\Custom_Field\Map( 'map', 'Map' );
		$map->add( array( 'page', 'post' ) );

		convert_to_screen( 'page' );
		set_current_screen( 'post-new.php' );
		do_action( 'admin_enqueue_scripts', 'post-new.php' );
		$this->assertTrue( wp_style_is( 'leaflet' ) );
		$this->assertTrue( wp_script_is( 'riot' ) );
		$this->assertTrue( wp_script_is( 'leaflet' ) );
		$this->assertTrue( wp_script_is( 'app' ) );
	}
}
