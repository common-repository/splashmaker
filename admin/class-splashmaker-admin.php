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
class Splashmaker_Admin{

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
        add_shortcode( 'bartag',array($this, 'bartag_func') );
        add_shortcode( 'splash_token',array($this, 'splash_field_function') );

        add_shortcode( 'splashmaker_popup',array($this, 'splash_popup') );
        add_filter( 'plugin_row_meta', array($this,'custom_plugin_row_meta'), 10, 2 );
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
        wp_enqueue_style( 'Font_Awesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.6.3/css/font-awesome.min.css' , array(), $this->version, 'all');
        wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/splashmaker-admin.css', array(), $this->version, 'all' );
        wp_enqueue_style( 'spectrum_min_css', plugin_dir_url( __FILE__ ) . 'css/spectrum_min_css.css', array(), $this->version, 'all' ); // commented by kuldeep        
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
            
        // this is included in the order to remove confliction of adding the external files to plugin files/templates manually like /partials/splashmaker-formpop-up.php
        wp_enqueue_script( 'spectrum-colorpicker2', plugin_dir_url( __FILE__ ) . 'js/spectrum.min.js', array( 'jquery' ), $this->version, false );
        wp_enqueue_script( 'sweetalert2', plugin_dir_url( __FILE__ ) . 'js/sweetalert2_all_min.js', array( 'jquery' ), $this->version, false );
        wp_enqueue_script( 'loadingoverlay', plugin_dir_url( __FILE__ ) . 'js/loadingoverlay_min.js', array( 'jquery' ), $this->version, false );
        wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/splashmaker-admin.js', array( 'jquery' ), $this->version, false );
        wp_register_script( 'splashmaker-custom-admin.js', plugin_dir_url( __FILE__ ) . 'js/splashmaker-custom-admin.js', array( 'jquery' ), $this->version, false );
        wp_enqueue_script( 'splashmaker-custom-admin.js' );
        wp_localize_script( 'splashmaker-custom-admin.js', 'frontend_ajax_object',
        array( 
            'ajaxurl' => admin_url( 'admin-ajax.php' ),
            'plugindir' => plugin_dir_url( __FILE__ ),
            'data_var_2' => 'value 2',
        )
    );

    }     
//----------------------------------------------- Add Shortcode---------------------------------------------

    function splash_field_function($atts = []){
        if(isset($atts)){
            return $_GET[$atts['field']];
        }else{
            return "";
        }
    }   

    function splash_popup($atts = []){ 
        $url = esc_url('http://' . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);
        if (strpos($url,'rest_route') !== false || strpos($url,'wp-json') !== false) {
        return;
        } ?>
        <style>
                body{
                    margin: 0;
                    height: 100%;
                    overflow: hidden;
                }
                .sm_text {
                    width: 100%;
                    margin: 3px 0px 6px 0px;
                }
                .splash_submit_div{
                    text-align: center;
                }
                .splash_submit_div p{
                    font-size: 10px;
                    text-align: left;
                }
                .sm_popup input.button {
                    margin: 1em 0px 0px 0px;
                    padding: 14px;
                    border-radius: 5px;
                    background-color: #cc092f;
                }
                .sm_popup input.button:hover{
                    text-decoration: none;
                }
                .sm_select {
        			padding: 1.5rem 1.8rem;
                    border: 1px solid #ccc;
                    margin-bottom: 13px;
                    -webkit-appearance: auto;
                    appearance: auto;
                }
                .entry-content{
                    pointer-events: none;
                }
                .popup_container {
                    background-color: rgba( 0, 0, 0, 0.60 );
                    padding: 20px 2%;
                    z-index: 999;
                    position: absolute;
                    box-shadow: 0px 2px 10px 0px rgb(0, 0, 0);
                    width: 100%;
                    left: 0;
                    right: 0px;
                    border: 1px solid #5a5a5a;
                    overflow-y: scroll;
                    pointer-events: all;
        /*             top: 0px; */
                    height: 100%;
                }
                #fvpp-close{ display: none;}
                .sm_popup label {
                    margin: 10px 0px 10px 0px;
                    font-size: 18px;
                }
                /* //pop up */
                #pop-up-content {
                    display: none;
                    max-width: 100%;
                    width: 100%;
                    margin: 0px auto;
                }
                .splash_popup {
                    max-width: 500px;
                    margin: 0px auto;
        			padding: 20px;
        			border-radius: 3px;
        			border: 1px solid #000000;
        			box-shadow: 0px 0px 30px 0px rgba( 2, 2, 2, 1.00 );
        			background-color: rgba( 255, 255, 255, 1.00 );
                }

        </style>
    
    
        <div id="pop-up-content" style="display: block;" >
            <div class="popup_container" id="popup">
                <div class="splash_popup">
                    <form method ="get"  >      
                        <div class= "sm_popup">         
                            <?php
                            global $wpdb;       
                            $table_name = $wpdb->prefix .'splashmaker_settings';
                            $sql = $wpdb->get_results("SELECT * FROM $table_name  where name='fields'", ARRAY_A );  
                            foreach($sql as $result){
                                $res = $result['valuess'];
                                $fields = json_decode($res,true);                           
                                $label = $fields['label'];
                                $slug = $fields['slug'];
                                $type = $fields['type'];     
                                $valuess = $fields['values'];                   
                                $options = $fields['selected_option'];
                                $hiddenValues = $fields['value'];  
                                $count=0;
                                $text = 0;
                                $area = 0;
                                $select = 0;
                                foreach($label as $lab){ 
                                    $string = $options[$count];             
                                    $list = explode(',', $string);
                                    $data = $type[$text];        
                                    $val = $valuess[$count];
                                    $avc = explode(',', $val);
                                    $hiddenValue = $hiddenValues[$count];                   
                                if($data == 'text'){
                                    echo '<label> '.esc_attr($lab).' *</label>';
                                    echo "<div><input type = 'text' class='sm_text' name ='".esc_attr($slug[$count])."' value='".esc_attr($_GET[$slug[$count]])."' required></div>";
                                }elseif($data == 'textarea'){
                                    echo '<label> '.esc_attr($lab).' *</label>';
                                    echo "<div><textarea  class='sm_text' name='".esc_attr($slug[$count])."' required></textarea></div>";
                                }elseif($data == 'select'){
                                    echo '<label> '.esc_attr($lab).' *</label>';
                                    $snam=preg_replace('/[^A-Za-z0-9-]+/', '_', $lab);
                                     ?>
                                        <div>
                                            <select class="selected_option_label" name="<?php echo esc_attr(strtolower($snam)); ?>" required>
                                                <?php foreach($list as $lis){
                                                    $option_val = preg_replace('/[^A-Za-z0-9-]+/', ' ', $lis);
                                                    $str = ($option_val);
                                                echo "<option value = '".esc_attr($str)."'> ".esc_attr($lis)."</option>";
                                                }
                                                ?>
                                            </select>
                                         </div>
                                <?php }
                                elseif($data == 'hidden'){
                                    echo "<div><input type='hidden' value='".esc_attr($hiddenValue)."' class='sm_text' name='".esc_html($slug[$count])."' required></div>";
                                }
                                elseif($data == 'dynamic_content' || $data == 'chart' || $data == 'label_submit_button'){
                                    if($data == 'dynamic_content'){
                                        $dynamic_content_label = $lab;
                                        $dynamic_content_slug = $slug[$count];
                                    }
                                    if($data == 'label_submit_button'){
                                        $label_submit_button = $lab;
                                    }
                                    
                                }else{
                                   
                                    echo '<label> '.esc_attr($lab).' *</label>';
                                    echo "<div><select name='".esc_html($slug[$count])."' class='sm_text sm_select' required>";
                                    foreach($avc as $val){
                                        $slug_val=preg_replace('/[^A-Za-z0-9-]+/', '_', $val);
                                            echo "<option value = '".esc_html($slug_val)."'> ".esc_html($val)."</option>";
                                    }
                                    echo "</select></div>";
                                }
                
                                $text++;                                
                                $count++;
                
                        }
                    }       
                    ?>

                    <label> <?php echo esc_attr($dynamic_content_label); ?> *</label>
                    <div>
                        <select class="sm_text sm_select"  name = "<?php echo esc_attr($dynamic_content_slug); ?>" required>   
                            <option value=""> Make a Selection</option>
                            <?php
                            $args = array(
                                'post_type' => 'dynamic_content',
                                'posts_per_page' => -1,
								'post_status' =>'publish',
								'order' => 'ASC',
                                
                            );
                            $loop = new WP_Query( $args ); 
                            if ($loop->have_posts() ) {
                                while ( $loop->have_posts() ) : $loop->the_post();
                                $title11 = get_the_title();
                                $id11  = get_the_ID();

                                $value = str_replace(' ', ' ', $title11);
                                
                                ?>
                                <option value="<?php echo $title11;?>"><?php echo esc_html($title11); ?></option><?php
                                endwhile;
                            } ?>
                        </select>
                        <input type="hidden" name="splash_form_dynamic_type" value="<?php echo esc_attr($dynamic_content_slug); ?>" />
						
                    </div>
                    <div class="splash_submit_div">
                        <p>
                           <?php echo get_option('disclaimer');?>
                        </p>
                        <input type="submit" name ="splash_submit"  value="<?php echo esc_html($label_submit_button); ?>"  class="button button-primary button-large splash-report-click-engagement" style="background-color:<?php echo get_option('button_color');?>" >
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

    <?php
        
    if(isset($_GET['splash_submit'])){
        global $wpdb;
        echo "<style>.popup_container{display:none;}</style>";
        
        foreach($_GET as $key=>$value){
            if(empty($value)){
                $current_rel_uri = add_query_arg( NULL, NULL );
                $parsed = parse_url($current_rel_uri);
                $query = $parsed['query'];
                parse_str($query, $params);
                unset($params['splash_submit']);
                $string = http_build_query($params);        
                $link = home_url();
                $url = $link."?".$string;  
                        
                $string = '<script type="text/javascript">';
                $string .= 'window.location = "' . $url .'"';
                $string .= '</script>';
                echo esc_attr($string);
                die;
            }
        }
        
        $splash_form_dynamic_type = sanitize_text_field($_GET['splash_form_dynamic_type']);
        $splash_form_dynamic_value = sanitize_text_field($_GET[$splash_form_dynamic_type]);
        $splash_form_dynamic_type_slug=sanitize_text_field(strtolower($splash_form_dynamic_type));
        
        unset($_GET['splash_submit']);
        unset($_GET['splash_form_dynamic_type']);
        $json = json_encode($_GET,JSON_FORCE_OBJECT); 
        $date = date("Y-m-d H:i:s"); 
        $email = sanitize_email($_GET['email']);
        $table_name = $wpdb->prefix . 'splash_popup';
        
        $check = $wpdb->get_results("SELECT * FROM `$table_name` WHERE email='$email'",ARRAY_A );   
        $email_list = $check[0]['email'];

        if(!empty($email_list )) { 
            $data = array(  'request' => $json,'date_created' => $date,);
            $where['email'] = sanitize_email($email);
            $wpdb->update($table_name,$data,$where);
        }else{ 
          $savetolocal= get_option( 'splash_save_to_local' );
          if(!empty($savetolocal) && $savetolocal==1){
              $insert= $wpdb->insert( 
                $table_name, 
                array(  
                    'request' => $json, 
                    'date_created' => $date,            
                    'email' => $email,          
            ), 
            array( 
                '%s', 
                '%s', 
                '%s', 
                
            )
            ); 
          }
              
        }
        // hubspot code removed commented by kuldeep
        // hubspot insert entries into hubspotlog table removed Commented by kuldeep
        
        $current_rel_uri = add_query_arg( NULL, NULL );
        $parsed = parse_url($current_rel_uri);
        $query = $parsed['query'];
        parse_str($query, $params);
        unset($params['splash_submit']);
        unset($params['splash_form_dynamic_type']);
       
        $string = http_build_query($params); 
        $link = home_url()."/custom";
        $url = $link."?".$string;  
        $url = $link."?".urldecode($string);  
        $url=str_replace("%2B"," ",$url);
        $url=str_replace("+","%2B",$url);
        ?>
        <script type="text/javascript">
        // $string = '<script type="text/javascript">';
        window.location = '<?php echo $url; ?>';
        // echo esc_attr($string); -->
        </script>
        <?php
    }
    
?>
    <script>
        jQuery('#pop-up-content').firstVisitPopup({
        cookieName : 'homepage',
        showAgainSelector: '#show-message'
        });
    </script>
<?php
}

    function popup_script(){
        $url=str_replace("%2B"," ",$_SERVER['QUERY_STRING']);
        $url=str_replace("+","%2B",$_SERVER['QUERY_STRING']);
        ?>

        <script>
            jQuery(document).ready(function(){
            
                jQuery(".splash_smart_cta a").each(function() {
                var href = jQuery(this).attr('href');
                jQuery(this).attr('href',href+"?<?php echo esc_attr($url); ?>");
          
            });
                jQuery(".firstname").each(function() {
                    jQuery(this).html("<?php echo esc_attr($_GET['first_name']); ?>");
                });
                
            });
        // chart.js code is removed commented by kuldeep    
        // there are no smartcharts and smartquiz so the ripplewidget code is removed.    
        </script>

    
    <?php
    }

    function custom_plugin_row_meta( $links, $file ) {
        if ( strpos( $file, 'splashmaker.php' ) !== false ) {
        
                    foreach($links as  $link){
                        if('Visit plugin site'){
                            $abc[] = str_replace("Visit plugin site","View details", $link);
                        }
                                    }
                    
            $links = array_replace( $links,$abc );
        }
        return $links;
    }

    // creating template for custom blank page {cutom url}
    function custom_page_template( $page_template ){
        if (is_page('custom')){
            $page_template = plugin_dir_path( dirname( __FILE__ ) ) . 'admin/template/sm_custom_template.php'; 
        }
        return $page_template;
    }
}