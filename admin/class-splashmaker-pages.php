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
class Splashmaker_Pages {

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
        add_action( 'admin_menu', array( $this, 'menu_pages' )); 
		add_action( 'admin_init', array( $this, 'splash_initialize_options' ) );
		
	}



    function menu_pages(){
	$status=get_option('activatation_status');
		add_menu_page(__('SplashMaker', 'splashmaker'), __('SplashMaker', 'splashmaker'), 'manage_options', 'splashmaker', array($this, 'splash_settings_menu'), plugins_url( 'splashmaker/admin/img/logo_sm_icon_white.svg' ));
		add_submenu_page('splashmaker', __('SplashMaker - Dashboard ', 'splashmaker'), __('Dashboard ', 'splashmaker'), 'manage_options',  'splash_activation_dashboard',  array($this, 'splash_activation_dashboard')  );
		add_submenu_page('splashmaker', __('SplashMaker - Personalization Form ', 'splashmaker'), __('Personalization Form', 'splashmaker'), 'manage_options',  'splash_form_gate',  array($this, 'splash_form_gate')  );
		add_submenu_page('splashmaker', __('Dynamic Content', 'splashmaker'), __('Dynamic Content', 'splashmaker'), 'manage_options', 'edit.php?post_type=dynamic_content', NULL );
        add_submenu_page('splashmaker', __('Help', 'splashmaker'), __('Help', 'splashmaker'), 'manage_options','https://lifering.splashmetrics.com/knowledge-base/splashmaker-overview/'  );
		add_submenu_page('splashmaker', __('Go Premium!', 'splashmaker'), __('Go Premium!', 'splashmaker'), 'manage_options','https://splashmetrics.io/splashmaker/'  );
		remove_submenu_page('splashmaker','splashmaker');
	}
	function splash_initialize_options(){
		register_setting( 'splash_script_settings', 'splash_script_settings' );
		register_setting( 'splash_hubspot_settings', 'splash_hubspot_settings' );
		register_setting( 'splash_hubspot_feed', 'splash_hubspot_feed' );
		register_setting( 'splash_setting_fxn', 'splash_setting_fxn' );
		 
			
	}

	function splash_setting_callback(){

	}

    // function splash_setting_field_callback() is removed by kuldeep
	
	function splash_pop_callback(){
	}
	
	

	function splash_settingcall(){
		?>
		<div class="wrap">
		<div class="admin_notices_container"><h2><?php echo settings_errors(); ?></h2></div>
		<div class="splash-outter-box">
			<div class="splash-box">                    
				<h3 class="splash-center">Settings</h3><hr>
				<form method="post" action="options.php">
				<?php
					settings_fields( 'splash_setting_fxn' );
					do_settings_sections( 'splash_setting_fxn' );
					submit_button();
				?>
				</form>
			</div>
		</div>
		</div>
		<?php
	}

	// function splash_feed_callback(){  is removed by kuldeep 
	// function splash_hubspot_object_callback() is removed by kuldeep
	// function splash_script_callback() 	is removed by kuldeep
	// function splash_hubspot_callback()  is removed by kuldeep
	// function splash_header_script_callback()	is removed by kuldeep
	// function splash_body_script_callback() is removed by kuldeep
	// function splash_footer_script_callback() is removed by kuldeep
	// function splashmetrics() is removed by kuldeep
	// function splash_hubspot_text_callback() removed by kuldeep
	// function splash_data_points() is removed by kuldeep
	// function splash_help() removed by kuldeep
	
	function splash_settings_menu(){
	}
	// function settings_form() is removed by kuldeep

	/*
		Premium banner show at Dynamic content post type
	*/ 
	function splash_gate_form(){
	}


	function splash_form_popup(){
		?>
		<div class="splash-outter-box">
			<div class="optionBox splash-box ">
				<h2 class="splash-center">SplashMaker - Form Pop-Up</h2>
			</div>
		</div>
		<?php
		
	}

	function splash_activation_dashboard(){		// Dashboard callback
		$default_tab = null;
		$tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : sanitize_text_field($default_tab); ?>
		<div class="wrap">
		<div class="splash_header">
			<h1></h1>
			</div>
			    <style>
				<?php require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/css/style.css'; ?>
				</style>
				<?php 
				require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/splashmaker-activation-dashboard.php';   
				?>
			</div>
		<?php
	}
	
	function splash_form_gate(){		 // Personalization Form Callback 
		$default_tab = null;
		$tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : sanitize_text_field($default_tab); ?>
		<div class="wrap">
		<div class="admin_notices_container"><h2><?php echo settings_errors(); ?></h2></div> <!-- Commented by kuldeep -->
		<header>    
			<div class="container">
				<div class="header__inner header__inner__page">
					<div class="header__text">
						<h2 class="clr-white basic-plugin-h2">Go Premium to add powerful 1-click automation, analytics, and integration with leading MA/CRM platforms!			
						</h2>
					</div>
				</div>
			</div>
		</header>
		<div class="splash_header">
			<div id="icon-users" class="icon">
				<img src="<?php echo plugin_dir_url( __FILE__ ) . 'img/logo_sm_icon.svg' ;?>"></div> 
				<h1 class="personalization-form-label">Personalization Form</h1>
			</div>
				<nav class="nav-tab-wrapper">
					<a href="?page=splash_form_gate" class="nav-tab <?php if($tab===null):?>nav-tab-active<?php endif; ?>">Form Build</a>
					<a href="?page=splash_form_gate&tab=form_entry" class="nav-tab <?php if($tab==='form_entry'):?>nav-tab-active<?php endif; ?>">Form Entries</a>
				</nav>
				<div class="tab-content">
					<?php switch($tab) :
					case 'form_entry': 				
						require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/splashmaker-form-entries.php';     				
					break;      
					default:
						require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/partials/splashmaker-formpop-up.php';     
					break;
					endswitch; ?>
				</div>
			</div>
		<?php
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

}