<?php
/*
 Plugin Name: WP JQuery Timelinr
 Plugin URI: broobe.com/plugins/wp-jquery-timelinr
 Description: This simple plugin helps you to give more life to the boring timelines. Supports horizontal and vertical layouts, and you can specify parameters for most attributes: speed, transparency, etc.
 Version: 1.2
 Author: Broobe
 Author URI: http://www.broobe.com
 Text Domain: wp-jquery-timelinr

 Copyright 2012  broobe  (email : dev@broobe.com)

 This program is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License, version 2, as
 published by the Free Software Foundation.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with this program; if not, write to the Free Software
 Broobe, Nicolas Avellaneda 953, Castelar, Buenos Aires (011) 5555-5952 AR
 */

if (!class_exists('jqueryTimelinrLoad')) {
	
	require_once plugin_dir_path( __FILE__ ) . 'includes/timelinr_tools.php';

	class jqueryTimelinrLoad extends Broobe_TL_Plugin_Admin {
        public function __construct() {
			$this -> loadConstants();
			if ( !is_admin() ) $this -> loadDependencies();
			
			register_activation_hook(__FILE__, array(&$this, 'timelinr_activated'));
            register_activation_hook( __FILE__, 'flush_rewrite_rules' );
			
			// Start this plug-in once all other plugins are fully loaded
			add_action('plugins_loaded', array(&$this, 'start'));
		}

        public function start() {
			load_plugin_textdomain('wp-jquery-timelinr', false, JQTL_DIR_NAME . '/lang');
			
			// Register Common scripts
			add_action( 'init', array(&$this, 'registerScripts' ) );
			add_action( 'init', array(&$this, 'adminActions') );

			if ( is_admin() ){
				if ( ! current_user_can('edit_posts') && ! current_user_can('edit_pages') ) {
			      	return;
			   	}
				if ( get_user_option('rich_editing') == 'true' && current_user_can('edit_pages')) {
			      	add_filter( 'mce_external_plugins', array( $this, 'add_plugin' )  );
			      	add_filter( 'mce_buttons', array( $this, 'register_button' ) );
			   	}
			   	add_filter('plugin_action_links_' . JQTL_BASE_NAME, array($this, 'timelinr_plugin_action_links'));
			   	add_filter('plugin_row_meta', array(&$this, 'timelinr_plugin_meta_links'), 10, 2);
				add_action('admin_menu', array(&$this,'timelinr_admin_menu'));
                add_action('admin_print_scripts', array(&$this,'config_page_scripts'));
				add_action('admin_print_styles', array(&$this,'config_page_styles'));
			}
			else{
				add_action('wp_print_scripts', array(&$this, 'loadScripts'));
				add_action('wp_print_styles', array(&$this, 'loadStyles'));
			}
		}

        public function loadConstants() {
			define('JQTL_CURRENT_VERSION', '1.2');
			define('JQTL_DIR_NAME', plugin_basename(dirname(__FILE__)));
			define('JQTL_BASE_NAME', plugin_basename(__FILE__));
			define('JQTL_BASE_PATH', WP_PLUGIN_DIR . '/' . JQTL_DIR_NAME);
			define('JQTL_BASE_URL', WP_PLUGIN_URL . '/' . JQTL_DIR_NAME);
		}
		
		/**
		 * Called when activating WP JQuery Timelinr via the activation hook.
		 * @return null
		 */
        public function timelinr_activated(){
			/**
			* Timelinr options
			* Plugin will use this options if user not made custom setting via settings page.
			*/
            $timelinr_options = get_option('timelinr_general_options');
	        if (!isset($timelinr_options['orientation']))
                $timelinr_options['orientation'] = 'Y';
	         
	        if (!isset($timelinr_options['arrowkeys']))
                $timelinr_options['arrowkeys'] = 'false';
	               
	        if (!isset($timelinr_options['autoplay']))
                $timelinr_options['autoplay'] = 'no';
	        
	        if (!isset($timelinr_options['autoplaydirection']))
                $timelinr_options['autoplaydirection'] = 'backward';
	        
			if (!isset($timelinr_options['autoplaypause']))
                $timelinr_options['autoplaypause'] = '2000';
	                
	        if (!isset($timelinr_options['startat']))
                $timelinr_options['startat'] = '1';
				
			if (!isset($timelinr_options['order']))
                $timelinr_options['order'] = 'asc';

            update_option('timelinr_general_options', $timelinr_options);


            /*Desing Options*/
            $design_options = get_option('timelinr_design_options');
            if (!isset($design_options['dateformat']))
                $design_options['dateformat'] = 'yy';

            if (!isset($design_options['permalink']))
                $design_options['permalink'] = 0;

            if (!isset($design_options['postexcerpt']))
                $design_options['postexcerpt'] = 0;
	            
	        update_option('timelinr_design_options', $design_options);

            /* Update Custom Fields*/
            $this->update_date_field(JQTL_CURRENT_VERSION);
		}

		/**
		 * Called when deactivating WP JQuery Timelinr via the deactivation hook.
		 * @return null
		 */
        public function timelinr_desactivated()
		{	
		}

        public function loadDependencies(){
			add_shortcode('timelinr', array(&$this, 'shortcode'));
		}	
		
		/**
		 * Register the external JS libraries that may be enqueued in either the frontend or admin.
		 * 
		 * @return NULL
		 */
        public function registerScripts()
		{
			if (!wp_script_is( 'jquery', 'registered' )) wp_register_script( 'jquery' );

			wp_deregister_script('jquery.timelinr');
			wp_register_script('jquery.timelinr', JQTL_BASE_URL . '/assets/js/jquery.timelinr-1.0.js', array( 'jquery' ));
		}

		/**
		 * Called when running the wp_print_scripts action.
		 * @return null
		 */
        public function loadScripts() {
		    if (!is_admin()) {
				if (!wp_script_is( 'jquery', 'queue' ))	wp_enqueue_script( 'jquery' );
	
				wp_enqueue_script('jquery.timelinr', JQTL_BASE_URL . '/assets/js/jquery.timelinr-1.0.js', array( 'jquery' ));
			}
		}

		/**
		 * Called when running the wp_print_styles action.
		 * @return null
		 */
        public function loadStyles() {
 		}

        public function adminActions() {
			//Custom Post Type Timelinr
			$labels = array(
			  'name' => _x('Timelinr', 'post type general name', 'wp-jquery-timelinr'),
			  'singular_name' => _x('Timelinr', 'post type singular name', 'wp-jquery-timelinr'),
			  'add_new' => _x('Add New', 'slide', 'wp-jquery-timelinr'),
			  'add_new_item' => __('Add New Event', 'wp-jquery-timelinr'),
			  'edit_item' => __('Edit Event', 'wp-jquery-timelinr'),
			  'new_item' => __('New Event', 'wp-jquery-timelinr'),
			  'view_item' => __('View Event', 'wp-jquery-timelinr'),
			  'search_items' => __('Search Event', 'wp-jquery-timelinr'),
			  'not_found' =>  __('No Timelinr items found.', 'wp-jquery-timelinr'),
			  'not_found_in_trash' => __('No Timelinr items found in Trash.', 'wp-jquery-timelinr'), 
			  'parent_item_colon' => ''
			);
			$args = array(
			  'labels' => $labels,
			  'public' => true,
			  'publicly_queryable' => true,
              'menu_icon' => JQTL_BASE_URL . '/assets/images/clock-icon.png',
			  'show_ui' => true, 
			  'query_var' => true, 
			  'capability_type' => 'post', 
			  'menu_position' => null,
			  'rewrite' => array('slug'=>'timelinr','with_front'=>true),
			  'supports' => array('title','editor','thumbnail', 'excerpt')
			); 
			register_post_type('timelinr',$args);
			
			//Custom Category Timelinr
			$cat_labels = array(
				'name' => __( 'Categories', 'wp-jquery-timelinr' ),
				'singular_name' => __( 'Category', 'wp-jquery-timelinr' ),
				'search_items' =>  __( 'Search Categories' , 'wp-jquery-timelinr'),
				'all_items' => __( 'All Categories', 'wp-jquery-timelinr' ),
				'parent_item' => __( 'Parent Category', 'wp-jquery-timelinr' ),
				'parent_item_colon' => __( 'Parent Category:', 'wp-jquery-timelinr' ),
				'edit_item' => __( 'Edit Category', 'wp-jquery-timelinr' ),
				'update_item' => __( 'Update Category', 'wp-jquery-timelinr' ),
				'add_new_item' => __( 'Add New Category', 'wp-jquery-timelinr' ),
				'new_item_name' => __( 'New Category Name', 'wp-jquery-timelinr' ),
				'choose_from_most_used'	=> __( 'Choose from the most used categories', 'wp-jquery-timelinr' )
			);
			register_taxonomy('timelinr_cats', 'timelinr', array(
				'hierarchical' => false,
				'show_ui' => true,
				'labels' => $cat_labels,
				'query_var' => true,
				'rewrite' => array( 'slug' => 'timelinr-category' ),
			));	
			
			//Custom Field Date
			add_action("admin_menu", "timelinr_meta_box");
			function timelinr_meta_box(){
			    add_meta_box("timelineInfo-meta", __('Date', 'wp-jquery-timelinr'), "timelinr_meta_options", "timelinr", "side", "low");
			}    
			  
			function timelinr_meta_options(){
		        global $post;
		        if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) return $post->ID;
		        $custom = get_post_custom($post->ID);
                $currentDate = (substr($custom["timelineDate"][0], 0, 4) != "") ? substr($custom["timelineDate"][0], 0, 4) : "";
                $currentDate .= (substr($custom["timelineDate"][0], 4, 5) != "") ? "-".substr($custom["timelineDate"][0], 4, 5) : "";
                ?>
				<script type="text/javascript">
					jQuery(document).ready(function($) {
						var queryDate = '<?php echo $currentDate;?>';
						
						if(queryDate != ''){
							var dateParts = queryDate.match(/(\d+)/g),
							    month = dateParts[1] != undefined ? dateParts[1] - 1 : '0',
							    year = dateParts[0];
							jQuery('#timelineDate').val(jQuery.datepicker.formatDate('yy-mm', new Date(year, month, 1)));
						}
					});
				</script>
				<input type="text" id="timelineDate" name="timelineDate" class="monthPicker" /><?php
			}
			
			add_action('save_post', 'save_timelinr_date');   
			function save_timelinr_date(){
			    global $post;    
			    if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){   
			        return $post->ID;
			    }else{
                    list($year, $month) = explode('-', $_POST["timelineDate"]);
			        update_post_meta($post->ID, "timelineDate", $year.$month);
			    }   
			} 
			
			// Datepicker Jquery UI
			add_action( 'admin_init', 'broobe_date_picker' );
			function broobe_date_picker() {			
			    wp_enqueue_script( 'jquery-datepicker', 'http://code.jquery.com/ui/1.10.1/jquery-ui.js', array('jquery', 'jquery-ui-core' ) );
				wp_enqueue_style( 'style-datepicker', 'http://code.jquery.com/ui/1.10.1/themes/base/jquery-ui.css');
				
				wp_enqueue_script( 'custom.timelinr', JQTL_BASE_URL . '/assets/js/custom.js', array( 'jquery-datepicker' ));
				wp_localize_script( 'custom.timelinr', 'obj', array( 'image_url' => JQTL_BASE_URL . "/assets/images/") );
			}
			
			//Customizing Admin Columns
			add_filter("manage_edit-timelinr_columns", "set_custom_edit_timelinr_columns");     
			function set_custom_edit_timelinr_columns($columns){    
				$columns = array(    
					"cb" => "<input type=\"checkbox\" />",    
					"title" => __('Event', 'wp-jquery-timelinr'),
					"category" => __('Category', 'wp-jquery-timelinr'),    
					"timelinr-date" => __('Timelinr Date', 'wp-jquery-timelinr')
				);    
				return $columns;    
			}    
			  
			add_action("manage_timelinr_posts_custom_column",  "timelinr_custom_column");   
			    
			function timelinr_custom_column($column){    
			        global $post;    
			        switch ($column)    
			        {      
			            case "timelinr-date":    
			                $custom = get_post_custom();

                            $date = (substr($custom["timelineDate"][0], 0, 4) != "") ? substr($custom["timelineDate"][0], 0, 4) : "";
                            $date .= (substr($custom["timelineDate"][0], 4, 5) != "") ? "-".substr($custom["timelineDate"][0], 4, 5) : "";
                            echo $date;
			                break;
						case "category":    
			                echo  strip_tags(get_the_term_list( $post->ID, 'timelinr_cats', '', ' - ', '' ));    
			                break; 
			        }    
			}
			
			add_filter( 'manage_edit-timelinr_sortable_columns', 'timelinr_sortable_columns' );
			function timelinr_sortable_columns( $columns ) {
				$columns['category'] = 'category';
				$columns['timelinr-date'] = 'timelinr-date';
				
				return $columns;
			}  
		}

        public function register_button($buttons) {
            array_push( $buttons, "|", "timelinr" );
            return $buttons;
		}

        public function add_plugin($plugin_array) {
		   $plugin_array['timelinr'] = plugin_dir_url( __FILE__ ) . '/assets/js/shortcode_popup.js';  
		   return $plugin_array;  
		}
		
		/* 
		 * Register our stylesheet. 
		 * */
        public function config_page_scripts (){
			if (isset($_GET['page']) && $_GET['page'] == 'wp-jquery-timelinr') {
				wp_enqueue_script('postbox');
				wp_enqueue_script('dashboard');
			}
		}

        public function config_page_styles() {
			if (isset($_GET['page']) && $_GET['page'] == 'wp-jquery-timelinr' || isset($_GET['post']) ) {
				wp_enqueue_style('dashboard');
				wp_enqueue_style('global');
				wp_enqueue_style('wp-admin');
				wp_register_style( 'timelinr-admin-style', JQTL_BASE_URL . '/assets/css/custom.css' );
	   			wp_enqueue_style( 'timelinr-admin-style' );
			}
		}

        public function timelinr_plugin_action_links($links) {
			$settings_link = '<a href="' . menu_page_url( 'wp-jquery-timelinr', false ) . '">'
				. esc_html( __( 'Settings', 'wp-jquery-timelinr' ) ) . '</a>';
		
			array_unshift( $links, $settings_link );
		
			return $links;
		}

        public function timelinr_plugin_meta_links( $links, $file ) {
			$plugin = plugin_basename(__FILE__);
		 
			if ( $file == $plugin ) {
				$donate_link = array('<a href="https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=LCT5LX6S9JNSJ">' 
				. esc_html( __( 'Donate', 'wp-jquery-timelinr' ) ) . '</a>');

				$links = array_merge($links, $donate_link);
			}
			return $links;
		}
		
		/*
		 *  Add timelinr menu 
		 * */
        public function timelinr_admin_menu() {
			include (JQTL_BASE_PATH . '/includes/options.php');
			add_options_page('Timelinr', 'Timelinr', 'manage_options', JQTL_DIR_NAME, 'timelinr_page');
        }

        public function shortcode($atts, $content = NULL) {
			global $post, $wpdb;
			STATIC $i = 1;

            $timelinr_options = get_option('timelinr_general_options');
            $desing_options = get_option('timelinr_desing_options');
			$pairs = array(
					'orientation' => $timelinr_options['orientation'],
					'startat' => intval($timelinr_options['startat']),
					'arrowkeys' => $timelinr_options['arrowkeys'],
					'autoplay' => $timelinr_options['autoplay'],
					'autoplaydirection' => $timelinr_options['autoplaydirection'],
					'autoplaypause' => $timelinr_options['autoplaypause'],
					'order' => $timelinr_options['order'],
					'containerdiv' => 'timelinr-'.$i,
					'category' => '',
					'dateformat' => $desing_options['dateformat'],
			);
			$atts = shortcode_atts($pairs, $atts );

			if ( strcmp ( $atts['orientation'] , 'horizontal' ) == 0){
				wp_enqueue_style('timelinr-style', JQTL_BASE_URL . '/assets/css/style.css', '', JQTL_CURRENT_VERSION );
			} elseif ( strcmp ( $atts['orientation'] , 'vertical' ) == 0) {
				wp_enqueue_style('timelinr-style_v', JQTL_BASE_URL . '/assets/css/style_v.css', '', JQTL_CURRENT_VERSION );
			}

			ob_start();
			include (JQTL_BASE_PATH . '/includes/template.php');
			$out = ob_get_contents();
			ob_end_clean();
			
			$i++;
			return $out;
		}
		
	}

	/*
	 * Initiate the plug-in.
	 */
	global $jqueryTimelinrLoad;
	$jqueryTimelinrLoad = new jqueryTimelinrLoad();
}
?>