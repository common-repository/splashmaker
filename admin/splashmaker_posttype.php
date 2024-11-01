<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Splashmaker
 * @subpackage Splashmaker/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Splashmaker
 * @subpackage Splashmaker/admin
 * @author     MOWWs5 <devteam@watershed5.com>
 */
class Splashmaker_Posttype {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
	
	}

	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Splashmaker_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Splashmaker_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/splashmaker-admin.css', array(), $this->version, 'all' );

	}
	
	
	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Splashmaker_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Splashmaker_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */
		// no need to enqueue -core, because dependancies are set
        wp_enqueue_script('jquery');
	    wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-effects-core');
        wp_enqueue_script('jquery-ui-sortable');
		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/splashmaker-admin.js', array( 'jquery' ), $this->version, false );

	}

	function splash_dynamic_init() {
		$labels = array(
			'name'                  => _x( 	' Dynamic Content', 'post type general name', 'splashmaker' ),
			'singular_name'         => _x( 'Dynamic Content', 'post type singular name', 'splashmaker' ),
			'menu_name'             => _x( 'Dynamic Content', 'admin menu', 'splashmaker' ),
			'name_admin_bar'        => _x( 'Dynamic Content', 'add new on admin bar', 'splashmaker' ),
			'add_new'               => _x( 'Add New Dynamic Content', 'Dynamic Content', 'splashmaker' ),
			'add_new_item'          => __( 'Add New Dynamic Content', 'splashmaker' ),
			'new_item'              => __( 'New Dynamic Content', 'splashmaker' ),
			'edit_item'             => __( 'Edit Dynamic Content', 'splashmaker' ),
			'view_item'             => __( 'View Dynamic Content', 'splashmaker' ),
			'view_items'            => __('View %s', 'splashmaker'),
			'all_items'             => __( 'All Dynamic Content', 'splashmaker' ),
			'search_items'          => __( 'Search Dynamic Content', 'splashmaker' ),
			'parent_item_colon'     => __( 'Parent Dynamic Content:', 'splashmaker' ),
			'not_found'             => __( 'No Dynamic Content found.', 'splashmaker' ),
			'not_found_in_trash'    => __( 'No Dynamic Content found in Trash.', 'splashmaker' ),
			'archives'              =>  __('%s Archives', 'splashmaker'),
			'attributes'            =>  __('Post Attributes', 'splashmaker'),
			'update_item'           =>  __('Update %s', 'splashmaker'),
			'featured_image'        =>  __( 'Featured image', 'splashmaker' ),
			'set_featured_image'    =>  __( 'Set featured image', 'splashmaker' ),
			'remove_featured_image' =>  __( 'Remove featured image', 'splashmaker' ),
			'use_featured_image'    =>  __( 'Use as featured image', 'splashmaker' ),
			'items_list'            =>  __('%s list', 'splashmaker'),
			'items_list_navigation' =>  __('%s list navigation', 'splashmaker'),
			'description'           => __( 'Demo', 'splashmaker' ),
			'filter_items_list'     =>  __('Filter %s list', 'splashmaker')
		);
	
	 
		$args = array(
			'labels'             => $labels,
			'public'             => true,
			'has_archive' => true,
			'publicly_queryable' => true,
			'show_ui'            => true,
			'show_in_menu'       => false, //<--- HERE
			'query_var'          => true,
			'rewrite'            => array( 'slug' => 'dynamic_content' ),
			'capability_type'    => 'post',
			'has_archive'        => true,
			'hierarchical'       => true,
			'show_in_rest' => true,
			'menu_icon' => 'dashicons-admin-multisite',
			'menu_position'      => null,
			'supports'           => array('title','editor','excerpt','trackbacks','custom-fields','comments','revisions','thumbnail','author','page-attributes')
		);
	 
		register_post_type( 'dynamic_content', $args );

		$tax_labels = array(
			'name'              => _x( 'Dynamic Content Type Category', 'taxonomy general name','splashmaker' ),
			'singular_name'     => _x( 'Dynamic Content Type Category', 'taxonomy singular name','splashmaker' ),
			'search_items'      => __( 'Search Dynamic Content Type Category','splashmaker' ),
			'all_items'         => __( 'All Dynamic Content Type Category','splashmaker' ),
			'parent_item'       => __( 'Parent Dynamic Content Type Category','splashmaker' ),
			'parent_item_colon' => __( 'Parent Dynamic Content Type Category:' ),
			'edit_item'         => __( 'Edit Dynamic Content Type Category','splashmaker' ), 
			'update_item'       => __( 'Update Dynamic Content Type Category','splashmaker' ),
			'add_new_item'      => __( 'Add New Dynamic Content Type Category','splashmaker' ),
			'new_item_name'     => __( 'New Dynamic Content Type Category Name','splashmaker' ),
			'menu_name'         => __( 'Dynamic Content Type Categories','splashmaker' ),
		  );

		  
	}


	function splash_gate_form() {
		
	}

		
	 
}