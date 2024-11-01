<?php get_header(); ?>
<div  class="entry-content" id="dynamic_custom_content">
<?php 
$table_name = $wpdb->prefix .'splashmaker_settings';
$result = $wpdb->get_results("SELECT * FROM $table_name where name='fields' limit 1", ARRAY_A );
$fields_json =  $result[0]["valuess"];            
$fields = json_decode($fields_json,JSON_FORCE_OBJECT);
	
if(!empty($fields['label'])){
    foreach($fields['label'] as $key => $label){
            if($fields['type'][$key] == "dynamic_content"){
                $dynamic_content_value= sanitize_text_field($fields['slug'][$key]);
                $dynamic_content_slug = sanitize_text_field(strtolower(preg_replace('/\s+/', '_', $dynamic_content_value)));
                break;
            }
    }
}
	//get all get param from the url and check it agained in the database where if lable is label_submit_button then we have to leave it 
	$dbfiled=array();
	foreach($fields['label'] as $key => $label){
	        if($fields['type'][$key] != "label_submit_button"){							
				//if any parnm in db and here in url came empty then we wi;; redirect them back 
				if(empty($_GET[$fields['slug'][$key]])){
					//Commented by Kuldeep
					?>
					<script>
					setTimeout(function(){
					 location.href="<?php echo esc_url(get_site_url().'/?'.$_SERVER['QUERY_STRING']); ?>";
					}, 1000);
					//Commented by Kuldeep
					</script>
				<?php }
				array_push($dbfiled, 1);
	        }
	}

	$page_title = sanitize_text_field($_GET[$dynamic_content_slug]); 
	if($page_title==""){
		$page_title = sanitize_text_field($_GET['inpoint']); 	
	}
	$page_title = str_replace("+", " " , $page_title);
	$mypost = get_page_by_title( $page_title , OBJECT, 'dynamic_content');
	echo apply_filters('the_content', $mypost->post_content);
?>
</div>
<?php get_footer(); ?>