<?php

/**
 * Fired during plugin deactivation
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Splashmaker
 * @subpackage Splashmaker/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Splashmaker
 * @subpackage Splashmaker/includes
 * @author     MOWWs5 <devteam@watershed5.com>
 */
class Splashmaker_Deactivator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate() {
		ob_start();
		$post_name = "SplashMaker custom template";
		$post = get_page_by_title( $post_name, OBJECT, 'page' );
		$id = $post->ID;     
		wp_delete_post( $id, true);
        
        $custom = "Custom";
		$post1 = get_page_by_title( $custom, OBJECT, 'page' );
		$id1 = $post1->ID;     
		wp_delete_post( $id1, true);
	}
}