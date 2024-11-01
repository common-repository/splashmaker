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
class Splashmaker_Meta{

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
    
    
	function wpse_add_custom_meta_box_2() {
		add_meta_box(
			'gate-form-shortcode',       // $id
			'shortcode',                  // $title
			array( $this, 'gate_form_text_field') ,  // $callback
			'splash_gate_form',                 // $page
			'normal',                  // $context
			'high'                     // $priority
		);
		add_meta_box(
			'custom_meta_box-2',       // $id
			'Form',                  // $title
			array( $this, 'show_custom_meta_box_2') ,  // $callback
			'splash_gate_form',                 // $page
			'normal',                  // $context
			'high'                     // $priority
		);

		
	 }

	 function show_custom_meta_box_2() {
		global $post;
		// Use nonce for verification to secure data sending
		wp_nonce_field('gate_form_meta_box_id', 'wpse_our_nonce' );
		$value = sanitize_text_field(get_post_meta( $post->ID, 'gate_form_textarea', true )); 
		if(empty($value)){
			$value = 
			'<label> Your Name (required)
			<input type="text" name="first_name"></label>		 
			<label> Your Email (required)
				[email* your-email] </label>
			
			<label> Subject
				[text your-subject] </label>
			
			<label> Your Message
				[textarea your-message] </label>
			
			[submit "Send"]';
		}
		?>	
		<!-- my custom value input -->
		<textarea name="gate-form" class="widefat" rows="20"><?php echo esc_attr( $value ); ?></textarea>	
		<?php
	}

	 function gate_form_text_field() {
		global $post;
		// Use nonce for verification to secure data sending
		wp_nonce_field('gate_form_shortcode', 'wpse_our_nonce_id' );	
		$value = sanitize_text_field(get_post_meta( $post->ID, 'gate_form_input', true )); 		
		?>
		<!-- my custom value input -->
		<input type="text"  class = "widefat" placeholder="shorcode" name="gate-form-input" value="<?php echo esc_attr( $value ); ?>">
		<?php
	}
	
		function wpse_save_meta_fields( $post_id ) {
			if ( ! isset( $_POST['wpse_our_nonce'] ) ) {
				return $post_id;
			}
			$nonce = sanitize_text_field($_POST['wpse_our_nonce']);
			// Verify that the nonce is valid.
			if ( ! wp_verify_nonce( $nonce, 'gate_form_meta_box_id' ) ) {
				return $post_id;
			}
		
			/*
			 * If this is an autosave, our form has not been submitted,
			 * so we don't want to do anything.
			 */
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return $post_id;
			}
		
			// Check the user's permissions.        
			 if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}
		
			// Sanitize the user input.
			$data =  sanitize_text_field($_POST['gate-form']);  
			update_post_meta( $post_id, 'gate_form_textarea', $data );


			/*****************************************/

			if ( ! isset( $_POST['wpse_our_nonce_id'] ) ) {
				return $post_id;
			}
		
			$nonce = sanitize_text_field($_POST['wpse_our_nonce_id']);		
			// Verify that the nonce is valid.
			if ( ! wp_verify_nonce( $nonce, 'gate_form_shortcode' ) ) {
				return $post_id;
			}
		
			/*
			 * If this is an autosave, our form has not been submitted,
			 * so we don't want to do anything.
			 */
			if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
				return $post_id;
			}
		
			// Check the user's permissions.        
			 if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}
		
			// Sanitize the user input.
			$data = sanitize_text_field( $_POST['gate-form-input'] );  
			update_post_meta( $post_id, 'gate_form_input', $data );
		  
		  }
}
