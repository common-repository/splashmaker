<?php
/**
 * Fired during plugin activation
 *
 * @link       #
 * @since      1.0.0
 *
 * @package    Splashmaker
 * @subpackage Splashmaker/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Splashmaker
 * @subpackage Splashmaker/includes
 * @author     MOWWs5 <devteam@watershed5.com>
 */
class Splashmaker_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
		ob_start();
		global $wpdb;
		global $jal_db_version;
		$table_name = $wpdb->prefix . 'splashmaker_settings';
			$charset_collate = $wpdb->get_charset_collate();
		$sql = "CREATE TABLE IF NOT EXISTS $table_name (
			id mediumint(9) NOT NULL AUTO_INCREMENT,	
			name varchar(55) DEFAULT '' NOT NULL,
			valuess longtext DEFAULT NULL,	
			date_created DATETIME NOT NULL,			
			PRIMARY KEY  (id)	
		) $charset_collate;";
		require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
	dbDelta( $sql );
			
	$table_name1 = $wpdb->prefix . 'splash_popup';
		$charset_collate = $wpdb->get_charset_collate();
		$sql11 = "CREATE TABLE IF NOT EXISTS $table_name1 (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			email varchar(55) DEFAULT '' NOT NULL,
			request longtext DEFAULT NULL,
			date_created DATETIME NOT NULL,
			PRIMARY KEY  (id)
		) $charset_collate;";
		dbDelta( $sql11 );

        // making log table 
        $table_name1 = $wpdb->prefix . 'splash_hubspot_logs';
            $charset_collate = $wpdb->get_charset_collate();
        $sql11 = "CREATE TABLE IF NOT EXISTS $table_name1 (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            contact_id varchar(255) DEFAULT '' NOT NULL,
            status varchar(255) DEFAULT '' NOT NULL,
            response varchar(255) DEFAULT '' NOT NULL,
            code varchar(255) DEFAULT '' NOT NULL,       
            date_created DATETIME NOT NULL,           
            PRIMARY KEY  (id)
        ) $charset_collate;";
        dbDelta( $sql11 );
	
	add_option( 'jal_db_version', $jal_db_version );

	//Commented by kuldeep
	// Create a dynmic page Version 1
	//floadting table creation 
	create_flottable();
	maybe_rewrite_rules1();
   	update_option( 'splash_save_to_local', 1, true );
	}
}

function maybe_rewrite_rules1() {

    $ver = filemtime( __FILE__ ); // Get the file time for this file as the version number
    $defaults = array( 'version' => 0, 'time' => time() );
    $r = wp_parse_args( get_option( __CLASS__ . '_flush', array() ), $defaults );

    if( $r['version'] != $ver || $r['time'] + 172800 < time() ) { // Flush if ver changes or if 48hrs has passed.
        flush_rewrite_rules();
        // trace( 'flushed' );
        $args = array( 'version' => $ver, 'time' => time() );
        if ( ! update_option( __CLASS__ . '_flush', $args ) )
            add_option( __CLASS__ . '_flush', $args );
    }

}

function create_flottable(){
	//creating table options 
	global $wpdb;
    $charset_collate = $wpdb->get_charset_collate();
    $table_name = $wpdb->prefix . "wp_floating_menu_details";
    $template_table_name = $wpdb->prefix . "wp_floating_menu_custom_templates";
    $sql = "CREATE TABLE $table_name (
                      id int NOT NULL AUTO_INCREMENT,
                      menu_name varchar(255),
                      menu_details varchar(8000),
                      menu_display_setting_details varchar(1500),
                      menu_status varchar(255) NOT NULL,
                      PRIMARY KEY id (id)
                    ) $charset_collate;";

    /** Creating Database to Save Custom Template Values */
    $template_sql = "CREATE TABLE $template_table_name (
                      id int NOT NULL AUTO_INCREMENT,
                      template_name varchar(255),
                      template_details varchar(1500),
                      PRIMARY KEY template_id (id)
                    ) $charset_collate;";


    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
    dbDelta($sql);
    dbDelta($template_sql);

    // after creating table then try to insert data ti table
    $pdata='a:7:{s:19:"menu_enable_disable";s:1:"1";s:26:"mobile_menu_enable_disable";s:1:"1";s:18:"menu_list_selected";s:7:"default";s:17:"menu_show_hide_on";s:9:"all-pages";s:22:"menu_link_add_nofollow";s:0:"";s:30:"menu_enable_offset_to_position";s:1:"0";s:25:"menu_disable_double_touch";s:1:"0";}'; 
    //$pdata = serialize($pdata);
	update_option('wpfm-settings','');

	global $wpdb;
	$prit_data_table  = $wpdb->prefix.'options';
	$wpdb->query("update $prit_data_table set  option_value='$pdata'  where option_name='wpfm-settings'");

	//now inserting data to table
	// first in temolate 
	$prit_data_table  = $wpdb->prefix.'wp_floating_menu_custom_templates';
	$tempname="SmartDoc";
	$tval='a:1:{s:15:"custom_template";a:19:{s:11:"menu_layout";s:10:"template-1";s:13:"menu_bg_color";s:0:"";s:11:"icon_expand";s:0:"";s:23:"wpfm_stretch_icon_color";s:0:"";s:21:"wpfm_close_icon_color";s:0:"";s:18:"wpfm_icon_bg_color";s:7:"#cc092f";s:9:"icon_size";s:0:"";s:11:"icon_margin";s:0:"";s:22:"wpfm_icon_acthov_color";s:7:"#ffffff";s:21:"icon_title_font_color";s:7:"#ffffff";s:19:"icon_title_bg_color";s:0:"";s:20:"icon_title_text_font";s:7:"default";s:20:"title_text_transform";s:4:"none";s:25:"wpfm_icon_title_font_size";s:1:"8";s:23:"icon_tooltip_font_color";s:0:"";s:21:"icon_tooltip_bg_color";s:0:"";s:22:"icon_tooltip_text_font";s:7:"default";s:23:"tt_title_text_transform";s:9:"uppercase";s:22:"wpfm_tooltip_font_size";s:1:"8";}}';
	$wpdb->query("INSERT into  $prit_data_table ( `template_name`, `template_details`) VALUES ('$tempname','$tval')");
			  		
	$prit_data_table1  = $wpdb->prefix.'wp_floating_menu_details';
	$tempname1="Floating Progression CTA";
	$mval='a:1:{i:1;a:11:{s:20:"wpfm_menu_item_title";s:20:"Progression CTA Here";s:15:"wpfm_title_show";s:1:"1";s:30:"wpfm_menu_item_title_attribute";s:0:"";s:23:"wpfm_menu_tooltip_title";s:0:"";s:24:"wpfm_target_link_address";s:34:"https://xyz.inc.com/custom";s:17:"wpfm_custom_class";s:16:"splash_smart_cta";s:14:"icon_icon_type";s:7:"default";s:20:"icon_picker_settings";s:32:"dashicons|dashicons-location-alt";s:18:"icon_picker_custom";s:0:"";s:10:"item_value";s:14:"Continue to...";s:10:"field_data";s:11:"Custom Link";}}';
	$mval2='a:1:{s:11:"menu_design";a:4:{s:19:"menu_template_style";s:15:"custom-template";s:15:"template_number";s:10:"template-1";s:20:"custom_template_type";s:1:"1";s:14:"menu_placement";s:5:"right";}}';
	$wpdb->query("INSERT into  $prit_data_table1 (`menu_name`, `menu_details`, `menu_display_setting_details`) VALUES ('$tempname1','$mval','$mval2')");
}