
<?php
    global $wpdb;
    $table_name = $wpdb->prefix . 'splash_popup';
    $sql = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id DESC", ARRAY_A );
    $latest_entry =  $wpdb->get_results("SELECT * FROM $table_name  ORDER BY id DESC  ",ARRAY_A );
	$result = $latest_entry[0]['request']; 
    $json = json_decode($result,JSON_FORCE_OBJECT);
	$savetolocal= sanitize_text_field(get_option( 'splash_save_to_local' ));
    echo '<div class="splash-outter-box">
    <div class="optionBox splash-box splash_save_to_local">
    <h2>Form Entries</h2><hr> 
    <span id="success"></span>
    <span id="error"></span>
    <div id="wrapper " class="splash-list ">';
    if(!empty($savetolocal)){
      $checked='checked';
    }else{
      $checked='';
    };
    echo '<div>
    <label><input type="checkbox" '.esc_attr($checked).' id="myCheckbox"/> Save Form Entries Locally</label><div class="section3 tooltip">
                                <i class="fa fa-question-circle  " aria-hidden="true"></i>
                                <span class="tooltiptext tooltip-top">
                                   This feature is enabled by default to ensure you don’t lose any data from your forms. However, due to GDPR concerns or other MA/CRM integration considerations, you might not want to save these submissions here. In that case, simply uncheck this box. And remember, doing so only affects the saving function from that point forward. It will not affect past submissions either way.
                                </span>
                            </div>';?>
<?php 
if ( ! class_exists( 'WP_List_Table' ) ) {
	require_once( ABSPATH . 'wp-admin/includes/class-wp-list-table.php' );
}

class HubSpot_Table extends WP_List_Table
{
   
	public function prepare_items()
    {
        $columns = $this->get_columns();
        $hidden = $this->get_hidden_columns();
        $sortable = $this->get_sortable_columns();
        $this->process_bulk_action();
		$data = $this->table_data();	
   
        usort( $data, array( &$this, 'sort_data' ) );
	
        $perPage = 10;
        $currentPage = $this->get_pagenum();
        $totalItems = count($data);

        $this->set_pagination_args( array(
            'total_items' => sanitize_text_field($totalItems),
            'per_page'    => sanitize_text_field($perPage) 
        ) );

		$data = array_slice($data,(($currentPage-1)*$perPage),$perPage);
		
        $this->_column_headers = array($columns, $hidden, $sortable);
        $this->items = $data;
    }

    /**
     * Override the parent columns method. Defines the columns to use in your listing table
     *
     * @return Array
     */
    public function get_columns()
    {
        $columns = array(
			 'cb'       => '<input type="checkbox" />',
			'id'           => 'ID',
            'firstname'    => 'Firstname',
            'lastname'     => 'Lastname',
            'company'      => 'Company',
            'jobtitle'     => 'Jobtitle',
            // 'inpoint'      => 'Inpoint',
			'your_role'     => 'Your Role'
           
        );

        return $columns;
    }

    /**
     * Define which columns are hidden
     *
     * @return Array
     */
    public function get_hidden_columns()
    {
        return array();
    }

    /**
     * Define the sortable columns
     *
     * @return Array
     */
    public function get_sortable_columns()
    {
       // return array('title' => array('title', false));
        return $sortable = array(
        'id'=>array('id',true),
		'firstname'=>array('firstname',true),
		'lastname'=>array('lastname',true),
		'company'=>array('company',true),
		'jobtitle'=>array('jobtitle',true),
		// 'inpoint'=>array('Inpoint',true),
		'your_role'=>array('your_role',true),
		
    );
    }

    /**
     * Get the table data
     *
     * @return Array
     */

     function process_bulk_action()
    {
       
        global $wpdb;
		$table_name = $wpdb->prefix . 'splash_popup';
        if(!empty($_POST) && 'delete' === $this->current_action()){

            $records = isset( $_POST['record'] ) ? (array) $_POST['record'] : array();
            $records = array_map( 'sanitize_text_field', $records );
            foreach($records as $record){
                $wpdb->query('DELETE  FROM '.sanitize_text_field($table_name).'   WHERE id = "'.sanitize_text_field($record).'"');
            }   

            
        }

        if ('delete' === $this->current_action()) {
				$vID = sanitize_text_field($_GET['id']);
                if($vID > 0){
                     $wpdb->query('DELETE  FROM '.sanitize_text_field($table_name).'   WHERE id = "'.sanitize_text_field($vID).'"');
                }

				
        }

        
    }
    private function table_data()
    {
	
	global $wpdb;
    $table_name = $wpdb->prefix . 'splash_popup';
    $sql  = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id DESC", ARRAY_A );
    $json = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id DESC", ARRAY_A );
    // echo "<pre>";
    // print_r($json);
    // echo "</pre>";

		 $data = array();
		foreach($json as $res){
			$res1=json_decode($res['request'],JSON_FORCE_OBJECT);
			$data[] = array(
            'id'           => sanitize_text_field($res['id']),
            'firstname'    => sanitize_text_field($res1['firstname']),
            'lastname'     => sanitize_text_field($res1['lastname']),
            'company'      => sanitize_text_field($res1['company']),
            'jobtitle'     => sanitize_text_field($res1['jobtitle']),
            // 'inpoint'      => sanitize_text_field($res1['inpoint']),
			'your_role'     => sanitize_text_field($res1['your_role'])
             );

        
		}
        
		return $data;

        
    }


	function get_bulk_actions() {

        $actions = array(
            'delete'    => 'Delete'
        );
		return $actions;
	  }

	 
      function column_cb($item) {         
        $actions = array(           
            'delete'    => sprintf('<a href="?page=%s&action=%s&id=%s" >Delete</a>','splash_form_gate&tab=form_entry','delete',esc_attr($item['id'])),
        );        
       
        return sprintf('<span class="column_cb_span"><input type="checkbox" name ="record[]" value="'.esc_attr($item['id']).'"/></span>%3$s',esc_attr($item['firstname']), esc_attr($item['id']), $this->row_actions($actions)  );
       
    }
    
    
    public function column_default( $item, $column_name )
    {
        switch( $column_name ) {
            case 'id':
            case 'firstname':					
            case 'lastname':
            case 'company':
            case 'jobtitle':
			// case 'inpoint':
			case 'your_role':
                return $item[ $column_name ];

            default:
                return print_r( $item, true ) ;
        }
    }

    /**
     * Allows you to sort the data by the variables set in the $_GET
     *
     * @return Mixed
     */
    private function sort_data( $a, $b )
    {
        // Set defaults
        $orderby = 'id';
        $order = 'asc';

        // If orderby is set, use this as the sort column
        if(!empty($_GET['orderby']))
        {
            $orderby = sanitize_text_field($_GET['orderby']);
        }

        // If order is set use this as the order
        if(!empty($_GET['order']))
        {
            $order = sanitize_text_field($_GET['order']);
        }
        $result = sanitize_text_field(strcmp( $a[$orderby], $b[$orderby] ));
        if($order === 'asc')
        {
            return $result;
        }

        return -$result;
    }
}


$hubspot_table = new HubSpot_Table;	
					echo '<form method="post">';
					$hubspot_table->prepare_items();
					// $hubspot_table->search_box('Search Records','search_record');
					$hubspot_table->display();

?>
<script>
  
  const checkbox = document.getElementById('myCheckbox')

checkbox.addEventListener('change', (event) => {
  if (event.currentTarget.checked) {
    var status=1;

                jQuery.ajax({
                    url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
                    type: "POST",
                    data: {
                        'action': 'splashmaker_save_local',
                        'status': status,
                    },
                    success: function (data) {
						jQuery("#success").html('Setting Saved Successfully');
						jQuery("#success").fadeOut(1000);
                     location.reload();
                    },
                    error: function (errorThrown) {
						jQuery("#error").html('Something went wrong !');
						jQuery("#success").fadeOut(1000);
						location.reload();
                    }
                });
  } else {
    var status=0;
    jQuery.ajax({
                    url: '<?php echo admin_url( 'admin-ajax.php' ); ?>',
                    type: "POST",
                    data: {
                        'action': 'splashmaker_save_local',
                        'status': status,
                    },
                    success: function (data) {
                      jQuery("#success").html('Setting Saved Successfully');
						jQuery("#success").fadeOut(1000);
						location.reload();
                    },
                    error: function (errorThrown) {
                        jQuery("#error").html('Something went wrong !');
						jQuery("#success").fadeOut(1000);
						location.reload();
                    }
                });
  }
});
</script>