<?php
error_reporting(E_ERROR | E_PARSE);

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              #
 * @since             1.0.0
 * @package           Splashmaker
 *
 * @wordpress-plugin
 * Plugin Name:       SplashMaker
 * Plugin URI:        https://splashmetrics.io/splashmaker/
 * Description:       The Smart Content Engine for WordPress that provides for dynamically personalized content, marketing automation and CRM integration, and Splashmetrics Buyer Journey intelligence and analytics.
 * Version:           1.0.0
 * Author:            Splashmetrics, Inc.
 * Author URI:        https://splashmetrics.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       splashmaker
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die();
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('SPLASHMAKER_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-splashmaker-activator.php
 */
function activate_splashmaker()
{
    require_once plugin_dir_path(__FILE__) .
        'includes/class-splashmaker-activator.php';
    Splashmaker_Activator::activate();
}

require_once plugin_dir_path(__FILE__) .
    'includes/class-splashmaker-activator.php';

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-splashmaker-deactivator.php
 */
function deactivate_splashmaker()
{
    require_once plugin_dir_path(__FILE__) .
        'includes/class-splashmaker-deactivator.php';
    Splashmaker_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_splashmaker');
register_deactivation_hook(__FILE__, 'deactivate_splashmaker');
// registered activation file call and function splashmaker_cronstarter_activation removed by kuldeep
// registered deactivation file call and function splashmaker_cronstarter_deactivate removed by kuldeep

//-----------cron for refresh token------------------

// function splashmaker_cronstarter_deactivate removed by kuldeep
// function splashmaker_cronstarter_activation removed by kuldeep

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-splashmaker.php';

//new shortcode for the import post type in the [splashmaker_landingpage post_id="10"]
add_shortcode('splashmaker_load_landing_page', 'splashmaker_load_landing_page');
function splashmaker_load_landing_page($atts)
{
    $atts = shortcode_atts(
        [
            'post_id' => '',
        ],
        $atts,
        'bartag'
    );
    extract($atts);

    if (empty($post_id)) {
        return 'Empty Post ID is not Allowed';
    }

    $post_data = get_post($post_id);
    // if is admin area
    if (is_admin()) { 
    } else {
        $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI'];
        if (
            strpos($url, 'rest_route') !== false ||
            strpos($url, 'wp-json') !== false
        ) {
            return;
        }
       $output = apply_filters('the_content',$post_data->post_content);
       echo wp_kses_post($output);

    }
}

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_splashmaker()
{
    $plugin = new Splashmaker();
    $plugin->run();
}
run_splashmaker();

add_action('init', 'splashmaker_maybe_rewrite_rules');
function splashmaker_maybe_rewrite_rules()
{
    $ver = filemtime(__FILE__); // Get the file time for this file as the version number
    $defaults = ['version' => 0, 'time' => time()];
    $r = wp_parse_args(get_option(__CLASS__ . '_flush', []), $defaults);

    if ($r['version'] != $ver || $r['time'] + 172800 < time()) {
        // Flush if ver changes or if 48hrs has passed.
        flush_rewrite_rules();
        // trace( 'flushed' );
        $args = ['version' => $ver, 'time' => time()];
        if (!update_option(__CLASS__ . '_flush', $args)) {
            add_option(__CLASS__ . '_flush', $args);
        }
    }
}


add_action('wp_ajax_splashmaker_save_local', 'splashmaker_save_local');
add_action('wp_ajax_nopriv_splashmaker_save_local', 'splashmaker_save_local');
function splashmaker_save_local()
{
    $status = sanitize_text_field($_POST['status']);
    update_option('splash_save_to_local', $status, true);
}

/*****************************************************************/
 
// cron_schedules  $schedules['every_six_hours'] removed by kuldeep
// Schedule an action if it's not already scheduled 
// splashmaker_cron_hook if condition removed by kuldeep

///Hook into that action that'll fire every six hours
// splashmaker_cron_function function, splashmaker_cron_hook removed by kuldeep


/***************************************************************/
add_filter( 'plugin_action_links', 'splash_maker_plugin_link', 10, 2 );
function splash_maker_plugin_link( $links, $file ) 
{
    if ( $file == plugin_basename(dirname(__FILE__) . '/splashmaker.php') ) 
    {
        /*
         * Insert the link at the beginning
         */
        $in = '<a href="https://splashmetrics.io/splashmaker/" target="_blank">' . __('Go Premium!','mtt') . '</a>';
        array_unshift($links, $in);

        /*
         * Insert at the end
         */
        // $links[] = '<a href="options-general.php?page=many-tips-together">'.__('Settings','mtt').'</a>';
    }
    return $links;
}


function splashmaker_options_instructions_example() {
    global $my_admin_page;
    $screen = get_current_screen();
   
    if ( is_admin() && ($screen->post_type == 'dynamic_content' && $screen->is_block_editor =='') ) {    
       ?>
        <div class="wrap">
            <header>
                <div class="container">
                    <div class="header__inner header__inner__page">
                        <div class="header__text">
                            <h2 class="clr-white basic-plugin-h2">Go Premium to add powerful 1-click automation, analytics, and integration with leading MA/CRM platforms!</h2>
                        </div>
                    </div>
                </div>
            </header>
        </div>
       <?php 
    }
}
add_action( 'admin_notices', 'splashmaker_options_instructions_example' );