<?php
    global $wpdb;
    if($_POST) {
        $standard_field_set=0;
        if(isset($_POST['label'])){
            $labels = array_map("sanitize_text_field",$_POST['label']);
        }
        if(isset($_POST['type'])){
            $types  = array_map("sanitize_text_field", $_POST['type']); 
        }
        if (isset($_POST['values'])) {
            $values = array_map("sanitize_text_field", $_POST['values']); 
        }
        
        $count=0;
        foreach($labels as $label){
            if($label == "Email"){
                $standard_field_set=1;
            }
            if($label == "HS Buying Role" || $label == "HS Persona"){
                $slug=preg_replace('/[^A-Za-z0-9-]+/', '_', $label);
            }else{
               $slug=preg_replace('/[^A-Za-z0-9-]+/', '', $label);
            }
            if($types[$count] == "dynamic_content"){
                 $slug=preg_replace('/[^A-Za-z0-9-]+/', '_', $label);
            }
            $_POST['slug'][]=sanitize_text_field(strtolower($slug));
            $count++;
        }

        $newarray = array();

        foreach($types as $k=> $type){        
        if($_POST['label'][$k] == "Email" || $type == "dynamic_content"){
            $newarray['label'][]           = sanitize_text_field($_POST['label'][$k]);
            $newarray['type'][]            = sanitize_text_field($_POST['type'][$k]);
            $newarray['slug'][]            = sanitize_text_field($_POST['slug'][$k]);
            $newarray['value'][]           = sanitize_text_field($_POST['values'][$k]);
            $newarray['selected_option'][] = sanitize_text_field($_POST['selected_option'][$k]);
        }
        }

        $i=0; 
        $_POST['selected_option']=array_filter($_POST['selected_option']);
        foreach($types as $k=>$type){
            if($_POST['label'][$k] != "Email" && $type != "dynamic_content" && $type !="label_submit_button"){
                $newarray['label'][] = sanitize_text_field($_POST['label'][$k]);
                $newarray['type'][]  = sanitize_text_field($_POST['type'][$k]);
                $newarray['slug'][]  = sanitize_text_field($_POST['slug'][$k]);
                $newarray['value'][] = sanitize_text_field($_POST['values'][$k]);
               

               if(isset($_POST['Save_color']) && $type == "select"){
                    $index=array_keys($_POST['selected_option']);
                    $newarray['selected_option'][] = sanitize_text_field($_POST['selected_option'][$index[$i]]);
                    $i++;
                }else{
                    $newarray['selected_option'][] = sanitize_text_field($_POST['selected_option'][$k]);
                }
            }
        }

            //check option array is empty or not
            $types1=$types;    
            $pos = array_search('label_submit_button', $types1);             // Remove from array
            unset($types1[$pos]);
            $pos = array_search('dynamic_content', $types1);             // Remove from array
            unset($types1[$pos]);
            //reindex array
            $types1=array_values(array_filter($types1));
             foreach($types1 as $k=>$type){
                //hidden
                if(isset($_POST['Save_color']) && $type == "hidden"){
                    $newarray['value'][$k+1] = sanitize_text_field($_POST['values'][$k]);
                }else{
                    $newarray['value'][] = sanitize_text_field($_POST['values'][$k]);
                }
             }

            foreach($types as $k=>$type){
                if($type == "dynamic_content" || $type =="label_submit_button"){
                    $newarray['label'][]            = sanitize_text_field($_POST['label'][$k]);
                    $newarray['type'][]             = sanitize_text_field($_POST['type'][$k]);
                    $newarray['slug'][]             = sanitize_text_field($_POST['slug'][$k]);
                    $newarray['value'][]            = sanitize_text_field($_POST['values'][$k]);
                    $newarray['selected_option'][]  = sanitize_text_field($_POST['selected_option'][$k]);
                }
            }

        if($standard_field_set==0){
            $json = "";
        }else{
            $json = json_encode($newarray,JSON_FORCE_OBJECT);
        }
        
        
        $date = date("Y-m-d H:i:s"); 
        $table_name = $wpdb->prefix . 'splashmaker_settings';
        $delete = $wpdb->query("DELETE from `$table_name` where name='fields'");

        $insert= $wpdb->insert( 
        $table_name, 
            array(  
                'name' => 'fields',
                'valuess' => $json, 
                'date_created' => $date,            
            ), 
            array( 
                '%s', 
                '%s', 
                '%s', 
                
        ));
        if($insert == true ){
            echo "<div class='notice notice-success settings-error is-dismissible' ><p>Settings saved </p></div>";
        }
        unset($_POST['Save']);
        unset($_POST['Save_color']);

        //update color in option table 
        sanitize_text_field(update_option( 'button_color', $_POST['color_code']));       
        //update disclaimer in opruion table 
        sanitize_text_field(update_option( 'disclaimer', wp_kses_post( stripslashes(($_POST['desired_id_of_textarea'])))));  
    }
?>

<div class="splash-outter-box">
    <div class="optionBox splash-box ">
        <?php 
            settings_errors(); 
            $standard_field = array(
              "firstname" => "First Name",
              "lastname" => "Last Name",
              "email" => "Email",
              "jobtitle" => "Job Title",
              "company" => "Company",
            );
        ?>  
        <h2 class="splash-center">Form Fields</h2>    
        <hr>
        
        
    <form method="post" id="splash_savee"  >
            <?php            
            $table_name = $wpdb->prefix .'splashmaker_settings';
            $result = $wpdb->get_results("SELECT * FROM $table_name where name='fields' limit 1", ARRAY_A );
            $fields_json =  $result[0]["valuess"];            
            $fields = json_decode($fields_json,JSON_FORCE_OBJECT);
  
            ?>
            
            <div class="block dynamic_section field-section-margin">
                <div class="block1 field-label-margin-b">
                <div class="section1 col1_width">Fields/Labels </div>
                <div class="section2 col1_width">Field Types</div>
                
                <div class="section3 col1_width">Personalization Tokens
                    <div class="section3 tooltip">
                        <i class="fa fa-question-circle" aria-hidden="true"></i>
                        <span class="tooltiptext tooltip-top" >
                        Use these tokens in your Dynamic Content headings, paragraph copy, etc. to personalize that text for the Buyer. For example if you enter “Hi [splash_token field=first_name]!” The Buyer’s first name will be used in that spot. 
                        To copy a token for use in your content, simply click in its blue field and it is automatically copied to your clipboard.
                        </span>
                    </div>
                </div>           
                </div>    
           <?php 
           $count=0;
           if(!empty($fields['label'])){
               echo ' <div class=" holder field-section-margin" >';
               $fields_used=array();
                foreach($fields['label'] as $key => $label){
                      $slug=preg_replace('/[^A-Za-z0-9-]+/', '', $label);
                      $field_name=sanitize_text_field(strtolower($slug));
                      $shortcode_text = "[splash_token field=".$field_name."]"; 
                        if($fields['type'][$key] == "dynamic_content"){
                            $dynamic_content_value= sanitize_text_field($label);
                            continue;
                        }
                        if($fields['type'][$key] == "label_submit_button"){
                            $label_submit_button= sanitize_text_field($label);
                            continue;
                        }
                   
             ?>
                
                <div class="block <?php echo esc_attr($field_name); ?> holder-block-margin"> 
                    <?php
                        // if standard field then make it readonly else if not standard then show it as normal
                        $flag=0;
                        foreach($standard_field as $keys => $value) {
                            if(strtolower($label) == strtolower($value)){
                                $fields_used[]=sanitize_text_field($value);
                                $flag=1;
                                $readonly = "disabled";
                                echo '<input class="col1_width" placeholder="Name" name="label[]" type="text" value="'.esc_attr($label).'" readonly/>';
                                echo '<input class="col1_width col2_width" placeholder="Name" name="type[]" type="text" value="'.esc_attr($fields['type'][$key]).'" readonly/>';
                            }
                            
                        }
                        
                        if($flag == 0){
                            $readonly = "";
                            echo '<input class="col1_width" placeholder="Name" name="label[]" type="text" value="'.esc_attr($label).'"/>';
                            if($label !== 'Email'){
                                
                         ?>   
                            <select class="<?php echo esc_attr($field_name); ?>-neww col1_width col2_width" placeholder="Name" name="type[]">
                                <option value="text" <?php if($fields['type'][$key] == "text"){echo "selected";}?>>Text</option>
                                <option value="textarea" <?php if($fields['type'][$key] =="textarea"){echo "selected";}?>>TextArea</option>
                                <option value="select" <?php if($fields['type'][$key] =="select"){echo "selected";}?>>Select</option>
                                <option value="dynamic_content" <?php if($fields['type'][$key] =="dynamic_content"){echo "selected";}?>>Dynamic Content</option>
                                <option value="hidden" <?php if($fields['type'][$key] =="hidden"){echo "selected";}?>>Hidden</option>
                            </select>  
                        <?php    
                        }else{
                            echo '<input class="col1_width col2_width" placeholder="Name" name="type[]" type="text" value="text" readonly/>';
                            echo '<input placeholder=", separated values" name="selected_option[]" value="" type="hidden"  class="col1_width seprated_optiont"/>';
                        }
                    }
                    ?>    

                    <?php 
                        // If hidden field then ask value not shortcode    
                        if($fields['type'][$key] == "hidden"){ 
                    ?>
                     <input title="Click to Copy" data-id="<?php echo esc_attr($count); ?>"  class="col3_width" name="values[]" value="<?php echo esc_attr($fields['value'][$key]); ?>" type="text" class="token_field" <?php if(!empty($fields['value'][$key])){ echo 'readonly';}?>/>                    
                    <?php 
                        }else{                   
                    ?>
                    
                                
                    <div class="outer-mask">
                             <input tooltip1="tooltip1" title="Click to Copy" id="clickToCopy_<?php echo esc_attr($count); ?>" data-id="<?php echo esc_attr($count); ?>"  class="clickToCopy col3_width" name="values[]" value="<?php echo esc_textarea($shortcode_text);?>" type="text" readonly/>
                    </div>     
                    <?php }?>


                    <?php 
                    $count++;
                     if($label == 'Email') { ?>
                        <span class="remove re-disabled"><img src="<?php echo plugin_dir_url( __FILE__ ) . '../img/remove.png' ;?>" class="reorder1" ></span>
                        <div class="section3 tooltip" >
                            <i class="fa fa-question-circle remove-tip"  aria-hidden="true" ></i>
                            <span class="tooltiptext tooltip-top">
                                Most MA/CRM platforms require an email address for all contacts in order to sync Buyer information.
                                Therefore, this field is always provided by default in SplashMaker for those integrations. However, if you are not integrating with these platforms with this particular Smart Content asset and you prefer very minimal inputs for personalization, you may remove this field from the form.
                            </span>
                        </div>
                    <?php }else{?>
                        <span class="remove"><img src="<?php echo plugin_dir_url( __FILE__ ) . '../img/remove.png' ;?>" class="reorder1"></span>
                                            <img src="<?php echo plugin_dir_url( __FILE__ ) . '../img/drag-reorder.png' ;?>" class="reorder ">     
    <?php 
                              if($fields['type'][$key] == "select"){
                                ?><br>                                 
                                  <div class="seprat"><input placeholder=", separated values" name="selected_option[]" value="<?php echo esc_attr($fields['selected_option'][$key]); ?>" type="text"  class="col1_width seprated_optiont"/></div>
                             <?php }else{
                                ?>
                                   <input placeholder=", separated values" name="selected_option[]" value="" type="hidden"  class="col1_width seprated_optiont" />
                              <?php 
                             } 
                                ?>          
                    <?php
                    }?>
                </div>
             <?php                 
                }
                echo '</div>';
            }else{
                
                echo '<div class="default_block holder ui-sortable field-section-margin">';
                $fields['label'] = array('Email','First Name' , 'Last Name' , 'Job Title','Company');
                
                foreach($fields['label'] as $key => $label){    
                    $slug=preg_replace('/[^A-Za-z0-9-]+/', '', $label);
                    $field_name=sanitize_text_field(strtolower($slug)) ;
                    $shortcode_text = "[splash_token field=".$field_name."]";
                    $rdonly = '';
                    if($field_name == 'firstname' || $field_name == 'lastname' || $field_name == 'jobtitle' || $field_name == 'company'){
                       $rdonly = 'readonly';
                    }
                
        ?>
                <div class="block <?php echo esc_attr($field_name); ?> holder-block-margin when_user_first_time_vist">
                    <input class="col1_width" placeholder="<?php echo esc_attr($label); ?>" name="label[]" <?php echo esc_attr($rdonly); ?> type="text" value="<?php echo esc_attr($label); ?>"/>
                    <select class="col1_width col2_width" placeholder="Name" name="type[]" <?php echo esc_attr($rdonly); ?>>
                        <option value="text" selected>Text</option>    
                        <option value="textarea">TextArea</option>
                        <option value="dynamic_content">Dynamic Content</option>
                        <option value="hidden">Hidden</option>                       
                    </select>
                   
                    <input  class="col3_width" name="values[]" value="<?php echo esc_textarea($shortcode_text); ?>"  class="token_field" type="text" readonly/>

               <?php  if($label == 'Email') {   ?>
                    <span class="remove re-disabled"><img src="<?php echo plugin_dir_url( __FILE__ ) . '../img/remove.png'; ?>" class="reorder1"></span>
                    <div class="section3 tooltip" >
                        <i class="fa fa-question-circle  " aria-hidden="true"></i>
                        <span class="tooltiptext tooltip-top" >
                            Most MA/CRM platforms require an email address for all contacts in order to sync Buyer information.
                            Therefore, this field is always provided by default in SplashMaker for those integrations. However, if you are not integrating with these platforms with this particular Smart Content asset and you prefer very minimal inputs for personalization, you may remove this field from the form.
                        </span>
                    </div> 
                <?php }else{?>
                    <span class="remove"><img src="<?php echo plugin_dir_url( __FILE__ ) . '../img/remove.png'; ?>" class="reorder1"></span>
                    <img src="<?php echo plugin_dir_url( __FILE__ ) . '../img/drag-reorder.png' ;?>" class="reorder removemargin" >          
        <?php
               }
                echo "</div>";
                 }
                echo "</div>";
            }
        ?>
        </div>
        <div class="block dynamic_section field-section-margin">
            <div class="section3 field-label-margin-b">Dynamic Content Driver
                <div class="section3 tooltip">
                    <i class="fa fa-question-circle  " aria-hidden="true"></i>
                    <span class="tooltiptext tooltip-top" >
                    This is a default item in the form. It determines which Dynamic Content page is served to the user based on their selection. The dropdown values are automatically generated based on the Dynamic Content page titles you assign. So just create and title the pages/versions that you want to offer, enter a label for this dropdown to match the context of those titles, and SplashMaker takes care of the rest.</span>
                </div>
            </div>
            <input class="col1_width" type="text" name="label[]" placeholder="Field label" class="dynamic_content_label" id="dynamic_content_label" value="<?php echo esc_attr($dynamic_content_value); ?>"/>
            <select class="col1_width col2_width"  placeholder="Name" name="type[]" id="field_type"  disabled>
                        <option value="text">Text</option>
                        <option value="select">Select</option>
                        <option value="textarea">TextArea</option>
                        <option value="dynamic_content" selected="selected">Dynamic Content</option>
                        <option value="hidden">Hidden</option> 
            </select>
            <input type="hidden" name="type[]" value="dynamic_content"/>
            <select class="col3_width">
                <?php        
                $args = array(
                    'post_type' => 'dynamic_content',
                    'posts_per_page' => -1,
                    'post_status'     => 'publish',
                    'order' => 'ASC',
                    
                );
                $loop = new WP_Query( $args ); 
                if ($loop->have_posts() ) {
                    while ( $loop->have_posts() ) : $loop->the_post();
                        $title11 = get_the_title();
                        $id11  = get_the_ID();
                        echo '<option value='.esc_html($id11).'>'.esc_attr($title11).'</option>';
                    endwhile;
                } ?>         
            </select>   
        </div>

        <div class="block dynamic_section field-section-margin">
            <div class="section3 field-label-margin-b">Submit Button & Info <div class="section3 tooltip">
                    <i class="fa fa-question-circle  " aria-hidden="true"></i>
                    <span class="tooltiptext tooltip-top" >
                    Here you can customize the form’s submit button and the disclaimer info. The font color for the button label is white. Use the color picker to adjust the background color of the button as you see fit. In the disclaimer text field you can use simple HTML code for links, etc. Be careful to keep this text to a minimum due to space limitations!
          </span>
                </div>
            </div>

            <input class="col1_width dynamic_content_label" type="text" name="label[]" id="label_submit_button" placeholder="Name" value="<?php echo $label_submit_button;?>"/>           
            <input type="hidden" name="type[]" value="label_submit_button" />
            <div class="col3_width" >
                <input type='text' class='basic' value='#ff0000'  />
                <?php $color= sanitize_text_field(get_option('button_color')); ?>
                <input type='hidden' name="color_code" id="color_name" value="<?php echo esc_attr($color); ?>" />
                
                <script>
                    jQuery(".basic").spectrum({
                        type: "color",
                        color: '<?php echo esc_attr($color);?>',
                        change: function(color) {
                        color.toHexString(); // #ff0000
                        jQuery("#color_name").val(color.toHexString());
                        jQuery('#submit_color').css("background", "#007cba");
                        jQuery('#submit_color').removeAttr("disabled");
                        jQuery('#unsave').show();
                        jQuery("#unsave").css({"display": "inline-block"});
                        }
                    });

                    jQuery('[tooltip1="tooltip1"]').mouseenter(function(){
                        let text =  jQuery(this).attr('text');
                    });
                    jQuery('[tooltip1="tooltip1"]').mouseleave(function(){
                        if(jQuery(this).prev().hasClass('tooltip123')){
                        }
                    });     
                    jQuery('.block input[type="text"]').keyup(function(){
                        jQuery('#submit_color').removeAttr("disabled");
                        jQuery('#unsave').show();
                        jQuery("#unsave").css({"display": "inline-block"});
                    });
                </script>
            </div>    
            <div class="col3_width shortcode" >
               <div class="section3 field-label-margin-b">Form Pop-Up Shortcode  
                    <div class="section3 tooltip">
                        <i class="fa fa-question-circle  " aria-hidden="true"></i>
                        <span class="tooltiptext tooltip-top" >
                        Place this shortcode into the landing page for this Smart Content asset. The pop-up will automatically present this form to allow the user to drive the Dynamic Content Experiences you’ve defined for this asset. To copy the shortcode, simply click in its blue field and it is automatically copied to your clipboard.                        </span>
                    </div>
                </div>
                    <input id="clickToCopy_showoff" data-id="showoff" class="clickToCopy bg_blue" type="text" title="Click to Copy" name="showoff" placeholder="Name" value="[splashmaker_popup]" readonly/>
            </div> 
            
        </div>
                    
                    
                    <?php $disclaimer=sanitize_text_field(get_option('disclaimer')); 
                    if($disclaimer ==""){
                        $disclaimer ="By submitting this form, I acknowledge that my use of XYZ’s <a href='#'>website</a> is subject to XYZ’s Terms and Conditions, and that my personal data is processed according to XYZ’s Privacy Policy."; 
                    }
                    $settings = array(
                        'quicktags' => array('buttons' => 'em,strong,link',),
                        'quicktags' => true,
                        'tinymce' => false,
                        'editor_height' => 100,
                        'textarea_rows' => 20,
                        'media_buttons'=>false,
                        'wpautop' => false,
                         'classes' => 'yourclass',
                         );
                    ?>
                    <div class="block dynamic_section field-section-margin">
                        <div class="disclaimer-section">
                        <?php wp_editor( $disclaimer , 'desired_id_of_textarea', $settings ); ?>
                        </div><!-- disclaimer-section-->
                    </div>
        <hr>
    <div class="block dynamic_section field-section-margin">    
        <div class="custom-fields in-condotional field-section-margin">
            <div class="col1_width Add_standard_fields">
                <div class ="splash-center field-label-margin-b label-heading">Add Standard Field</div>
                <div class="dynamic_section margin-bottom-12px">
                    <div class="sec ">
                        <div class="block-new">
                            <select class="dynamic_content" id="field_label">
                                <option value ="">Select Field</option>
                                <?php
                                    $all_added = "true";
                                    foreach($standard_field as $key => $value) {
                                        if (!in_array($value, $fields_used)){
                                          $all_added = "false"; 
                                            if($key!='phone'){
                                                echo '<option value="'.esc_attr($key).'">'.esc_attr($value).'</option>';
                                            }
                                        }
                                    }
                                ?>
                            </select>
                            <div class="block">
                              <span class="add_existing button button-default"  >Add Field</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php
            if($all_added == "true"){
                ?>  
                <style>  .in-condotional .Add_standard_fields{display:none;}              </style>
                <?php       
            }?>
        <div class="col1_width col2_width"> <!-- class commented by kuldeep: margin-left-zero -->
        <div class="section3 "><div class="splash-center field-label-margin-b label-heading">Add Custom Field</div></div>
                <div class="block_new block margin-top-zero">
                    <input class="col1_width0 add-new-field-by-js dynamic_content_label" placeholder="Enter Field Name" name="" id="custom_field_label" type="text">
                </div>
                <div class="block block_new margin-bottom-8px">
                    <span class="add button button-default" >Add Field</span>
                </div>
        </div>
        </div>
    </div>    
    <hr>
    <div class="block dynamic_section field-section-margin">
        <div class="custom-fields Added-Fields field-section-margin">
            <div class="splash-center label-heading when-user-add-new-field field-label-margin-b">Added Fields</div>
            <div class="block_added_field"></div>
        </div>
    </div>
<input name="Save" id= "submit_color" disabled="disabled" class="button-primary splash-report-click-nurture" value="Save Changes" type="submit"/> 
<p id="unsave">You have unsaved changes…</p>                    
</form>   
</div>
<script>

jQuery('select').change(function(){
   jQuery('#submit_color').removeAttr('disabled');
          jQuery('#unsave').show(); 
    jQuery("#unsave").css({"display": "inline-block"});
});
jQuery(document).ready(function(){
    jQuery( ".holder" ).sortable({
        change: function(event, ui) {
          jQuery('#submit_color').removeAttr('disabled');
          jQuery('#unsave').show();
          jQuery("#unsave").css({"display": "inline-block"});
        }
    });
    jQuery( ".holder" ).disableSelection();
})
    jQuery('.email .col1_width').prop('readonly', true);
    jQuery('.remove').click(function() {
   
        jQuery('input#submit_color').removeAttr('disabled');
        jQuery('#unsave').show();
          jQuery("#unsave").css({"display": "inline-block"});
    });
    
    jQuery('.add_existing').click(function() {
        jQuery('.when-user-add-new-field').show();
        console.log("Working");
        var id = jQuery( "#field_label" ).val();
        var label = jQuery('#field_label option:selected').text();
        var value = jQuery( "#field_type" ).val();      
        var sperated_field = ' <input class="col3_width seprated_option" name="selected_option[]" value="" type="text" />';
        jQuery('input#submit_color').removeAttr('disabled');
        jQuery('.block_added_field:last').after('<div class="block_added_field block"><input class="col1_width"  name="label[]" value="'+label+'" type="text" readonly/>'+
        '<input class="col1_width col2_width" placeholder="Name" name="type[]" type="text" value="text" readonly="">&nbsp;&nbsp;&nbsp;'+
        '<span class="remove"><img src="<?php echo plugin_dir_url( __FILE__ ) . '../img/remove.png' ;?>" class="reorder1"></span></div>');
    });

    jQuery('.add').click(function() {
        jQuery('.when-user-add-new-field').show();
        jQuery('#submit_color').removeAttr("name");
        jQuery('#submit_color').attr("name",'Save_color');
        var value = jQuery( "#field_type" ).val();      
        var custom_field_label = jQuery( "#custom_field_label" ).val(); 
        var sperated_field = ' <input class="col3_width seprated_option" id="select_option" name="selected_option[]" value="" placeholder=", separated values" type="text" /> <input class="col3_width" id="hidee" name="selected_option[]" value="" placeholder=", separated values" type="text" style="display: none;background-color: #fff;"/><input class="col3_width" id="hideen" name="values[]" value="" placeholder="Enter hidden value" type="text"  style="display: none;background-color: #fff;"/>';
        jQuery('.block_added_field:last').after('<div class="block_added_field block_new block "><input class="col1_width" value="'+custom_field_label+'" placeholder="Name" name="label[]" type="text" required />'+
        '<select class="col2_width onchangetype" id="new-block-selected" placeholder="Name" name="type[]"><option value="text">Text</option><option value="hidden">Hidden</option>'+
        '<option value="textarea">TextArea</option><option value="select">Select</option>'+
        '</select>'+sperated_field+'&nbsp;&nbsp;&nbsp;<span class="remove"><img src="<?php echo plugin_dir_url( __FILE__ ) . '../img/remove.png' ;?>" class="reorder1"></span></div>');
        jQuery('#unsave').show();
        jQuery("#unsave").css({"display": "inline-block"});
        
            if(jQuery( "#new-block-selected" ).val() != 'select')
            {
                jQuery( "#select_option" ).hide();
            }

         jQuery('.onchangetype').on('change', function() {
            // debugger
            if(this.value=="select"){
                jQuery("#hidee").show();
            }else if(this.value=="hidden"){
                jQuery("#hidee").hide();
                //show the hidden part
                jQuery("#hideen").show();                 
            }else{
                jQuery("#hidee").hide();
            }
        });
    

    });

    jQuery('.optionBox').on('click','.remove',function() {
        if(jQuery('body .block_added_field.block_new.block').length == 1){
            jQuery('.when-user-add-new-field').hide();
        }
        jQuery('input#submit_color').removeAttr('disabled');
        jQuery(this).parent().remove();
        jQuery('#unsave').hidden();
    });
    jQuery('body .add-new-field-by-js').on('keyup keypress change', function(e) {
        recheck();
    });          
    jQuery('body input#label_submit_button').on('keyup keypress change', function(e) {
        recheck();
    }); 
    jQuery('#dynamic_content_label').on('keyup keypress change', function(e) {
        recheck();
    }); 
    jQuery('#desired_id_of_textarea').on('keyup keypress change',function(){
        recheck();
    });
    
    let previous_dynamic_content_label = jQuery('#dynamic_content_label').val();
    let desired_id = jQuery('#desired_id_of_textarea').val();       
    let custom_field_label  = jQuery('body input#custom_field_label').val();
    let desired_id_of_textarea  = jQuery('body input#label_submit_button').val();
    
    function recheck(){
        let flag = true;
        let previous_dynamic_content_label1 = jQuery('#dynamic_content_label').val();
        let desired_id1 = jQuery('#desired_id_of_textarea').val();      
        let custom_field_label1  = jQuery('body input#custom_field_label').val();
        let desired_id_of_textarea1  = jQuery('body input#label_submit_button').val();
        
        if(previous_dynamic_content_label == previous_dynamic_content_label1){
         flag = false;
        } 
        else if(desired_id == desired_id1){
         flag = false;
        } 
        else if(custom_field_label == custom_field_label1){
         flag = false;
        }     
        else if(desired_id_of_textarea == desired_id_of_textarea1){
         flag = false;
        } 
        if(flag == true){
            
                jQuery('#unsave').css({'display':'none'});
        }else{
                jQuery('#unsave').css({'display':'inline-block'});
                jQuery('input#submit_color').removeAttr('disabled');
        }
        
    }   
    if(jQuery('.when_user_first_time_vist').length > 0){ 
        jQuery('.custom-fields.in-condotional .Add_standard_fields').hide();
    }
    
</script>
        
<?php 

?>