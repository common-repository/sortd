<?php

/**
 * The general utils-specific functionality of the plugin.
 *
 * @link       https://www.sortd.mobi
 * @since      2.0.0
 *
 * @package    Sortd
 * @subpackage Sortd/admin
 */

/**
 * The general utils-specific functionality of the plugin.
 *
 * @package    Sortd
 * @subpackage Sortd/admin
 * @author     Your Name <email@example.com>
 */
class Sortd_Utils {

	/**
	 * The loader that's responsible for maintaining and registering all hooks that power
	 * the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      Sortd_Loader    $loader    Maintains and registers all hooks for the plugin.
	 */
	protected $loader;

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $sortd    The ID of this plugin.
	 */
	private $sortd;

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
	 * @param      string    $sortd       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $sortd, $version, $loader ) {

		$this->sortd = $sortd;
		$this->version = $version;
        $this->loader = $loader;

	}
        
        /**
	 * function to define module specific hooks
	 *
	 * @since    2.0.0
	 */
	public function define_hooks() {
            
            $this->loader->add_action( 'admin_enqueue_scripts', $this, 'enqueue_scripts' );
            $this->loader->add_action('wp_ajax_sortd_verify_credentials', $this, 'verify_credentials');
			$this->loader->add_action('wp_ajax_sortd_get_contractdetailsafter_verify', $this, 'sortd_get_contract_details_after_verify');
			$this->loader->add_action('wp_ajax_sortd_get_paid_articles', $this, 'sortd_get_paid_articles');
            $this->loader->add_action('wp_ajax_mark_free_sortd_action',$this,'mark_free_sortd_action');
			$this->loader->add_action('wp_ajax_search_based_on_filters',$this,'search_based_on_filters');
			$this->loader->add_action('wp_ajax_get_count_after_reset',$this,'get_count_after_reset');
	}
        
        /**
	 * function for including scripts
	 *
	 * @since    2.0.0
	 */
        public function enqueue_scripts() {
			wp_enqueue_style( 'sortd-select-css', SORTD_CSS_URL . '/bootstrap-select.css', array(), $this->version, 'all' );
                wp_enqueue_script('sortd-utils', SORTD_JS_URL . '/sortd-utils.js', array( 'jquery' ), $this->version, true );
				wp_enqueue_script('sortd-utils-bootsrap', SORTD_JS_URL . '/bootstrap-bundle.js', array( 'jquery' ), $this->version, true );		
				wp_enqueue_script('sortd-utils-select', SORTD_JS_URL . '/bootstrap-select-min.js', array( 'jquery' ), $this->version, true );	
			
				wp_localize_script(
                    'sortd-utils',
                    'sortd_ajax_obj_utils',
                    array(
                        'ajax_url' => admin_url( 'admin-ajax.php' ),
                        'nonce'    => wp_create_nonce( 'sortd-ajax-nonce-utils' ),
                    )
                );
                
	}


        
        /**
	 * function for credential screen code
	 *
	 * @since    2.0.0
	 */
	public function credentials_page() {

		
            
            $plan_details       = Sortd_Helper::get_plan_details();
            $sortd_projectid    = Sortd_Helper::get_project_id();
            $credentials        = Sortd_Helper::get_credentials_values();
            $license_data       =  get_option('sortd_'.$sortd_projectid.'_license_data');
			$credentials_json = array('project_name'=> $license_data['project_name'],'project_id'=>$license_data['project_id'],'host'=>$license_data['host']);
			
			global $wp_version;
			if($wp_version < '5.3'){
				$view_data = array();
				Sortd_Helper::render_partials(array('version-not-supported'), $view_data);
			} else {

            if(!empty($credentials)){
                $access_key = Sortd_Helper::sortd_decrypt($credentials['access_key']);
                $secret_key = Sortd_Helper::sortd_decrypt($credentials['secret_key']);
            } else{
                $access_key = '';
                $secret_key = '';
            }

			$view_data = array();
			if(isset($credentials) && !empty($credentials) && isset($credentials_json)){
				$view_data['credentials']       = array_merge($credentials,$credentials_json);
			} else {
				$view_data['credentials']       = $credentials;
			}
            
           
            $view_data['plan_data']         = $plan_details;
           
            $view_data['sortd_projectid']   = $sortd_projectid;
            $view_data['license_data']      = $license_data;
            $view_data['access_key']        = $access_key;
            $view_data['secret_key']        = $secret_key;
			

            if(empty($credentials)){
	 		Sortd_Helper::render_partials(array('sortd-project-verify-credentials'), $view_data);
            } else {
                $project_details = Sortd_Helper::get_cached_project_details();
                $view_data['project_details']  = $project_details;
                if($project_details->status !== true && $project_details->error && $project_details->error->errorCode === 1010){
                    Sortd_Helper::render_partials(array('sortd-version-support'), $view_data);
                } else {
                    Sortd_Helper::render_partials(array('sortd-project-verify-credentials'), $view_data);
                }
            }
		}   
	}
        
   
        /**
	 * function to verify credentials 
	 *
	 * @since    2.0.0
	 */
	public function verify_credentials() {

            if(!check_ajax_referer('sortd-ajax-nonce-utils', 'sortd_nonce')) {
                    $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
                    echo wp_kses_data($result); wp_die();
            }

			if(isset($_POST['access_key'])){
				$access_key = sanitize_text_field($_POST['access_key']);
			}

			if(isset($_POST['secret_key'])){
				$secret_key = sanitize_text_field($_POST['secret_key']);
			}

       
	  

		
		if(isset($_POST['project_name'])){
			$project_name = sanitize_text_field($_POST['project_name']);
		}
		if(isset($_POST['project_id'])){
			$project_id = sanitize_text_field($_POST['project_id']);
		}
		if(isset($_POST['host'])){
			$host = sanitize_text_field($_POST['host']);
		} else{
			$project_name = '';
			$project_id = '';
			$host = '';
		}
	  
	  	
	    $license_data = array('access_key' => $access_key,'secret_key' => $secret_key,'project_name' => $project_name,'project_id'=>$project_id,'host'=>$host);
		
            $params = (wp_json_encode($license_data,JSON_UNESCAPED_UNICODE));
         
	    if(isset($license_data) && !empty( $license_data)){
	    	$license_data['access_key'] = '';
	    	$license_data['secret_key'] = '';
	    }
	  
            $content_type = 'application/json';
            
	    $headers = Sortd_Helper::post_headers_for_ajax($access_key,$secret_key,$content_type);

	    $headers['project_name'] = $project_name;
	    $headers['project_id'] = $project_id;

		
            $url = Sortd_Helper::get_api_base_url().'verify-license';

            $response = Sortd_Helper::curl_post($url,$headers,$params);

			
            $encrypted_access_key = Sortd_Helper::sortd_encrypt($access_key);
            $encrypted_secret_key = Sortd_Helper::sortd_encrypt($secret_key);

            $credentials = array(
                'access_key' => $encrypted_access_key,
                'secret_key' => $encrypted_secret_key
            );

			

            $response_object = json_decode($response);
            
            $result = array();
            $result['verify'] = $response_object;
            if($response_object->status === true){
                
                $project_id = $response_object->data->project_id;
                update_option('sortd_credentials', $credentials);
                update_option('sortd_projectid', $project_id);
                update_option('sortd_project_slug', $response_object->data->project_slug);
                update_option('sortd_'.$project_id.'_license_data',$license_data);

                $api_slug = 'project/project-details';
                $project_response = Sortd_Helper::sortd_get_api_response($api_slug);


          
                
                $contract_api_slug = 'saas/contract-details';
                $contract_response = Sortd_Helper::sortd_get_api_response($contract_api_slug);

				$credentials = Sortd_Helper::get_credentials_values();
			
				$template_oneclick_flag = get_option('sortd_saved_template_and_oneclick'.$project_id);
	    		$sortd_oneclick_flag  = get_option('sortd_oneclick_flag'.$project_id);
				if(!empty($credentials) && $template_oneclick_flag === '1' && $sortd_oneclick_flag=== '0'){
					$screen_status = 2;
				} if(!empty($credentials) && empty($template_oneclick_flag)){
					$screen_status = 1;
				} if(isset($credentials) && !empty($credentials) && $sortd_oneclick_flag === '1'){
					$screen_status = 3;
				}

                $result['verify'] = $response_object;
                $result['project'] = json_decode($project_response);
                $result['contract'] = json_decode($contract_response);
                $result['screenstatus'] = $screen_status ; 
            }

		
            echo (wp_json_encode($result));
            wp_die();

	}
    
 
        /**
	 * function for plugin activate trigger api
	 *
	 * @since    2.0.0
	 */
	public function plugin_activation() {

		$postCount = wp_count_posts();

		$args = array(
		'get' => 'all',
		'hide_empty' => 0
		);
		
		$categories = get_categories( $args );

		$categoryCount = count($categories);

		$publishPostCount = $postCount->publish;

		$wordpressDomain = site_url();

		$getAdminEmail = get_option('admin_email');
		$paramsEncode = array();
		$paramsEncode['posts_count'] = intval($publishPostCount);
		$paramsEncode['category_count'] = $categoryCount;
		$paramsEncode['wordpress_domain'] = $wordpressDomain;
		$paramsEncode['publisherEmail'] = $getAdminEmail;


		$params_array = array("posts_count" => intval($publishPostCount),"category_count" => $categoryCount, "wordpress_domain" => $wordpressDomain ,   "publisherEmail" => $getAdminEmail);
		$params = stripslashes(wp_json_encode($params_array));
	 	$plugin_activation_api_slug = "plugins/plugin-activation";  
 		$response = Sortd_Helper::sortd_post_api_response($plugin_activation_api_slug, $params);
	    $decode = json_decode($response);

	       	if($decode->data === true){
	       		update_option('sortd_activated',3);
	       	}

	   

	}

	    /**
	 *  function to get plugin activate message
	 *
	 * @since    2.0.0
	 */
	public function get_plugin_activate_message() {

		

	}
        
         /**
	 * function for admin notices/flash messages on different actions
	 *
	 * @since    2.0.0
	 */
	public function general_admin_notice() {
		
		$screen = get_current_screen();

		
		$project_id = Sortd_Helper::get_project_id();
	
		if(!empty( $screen->post_type ) && 'edit' === $screen->base){
		
			$bulk_sync = get_option('bulk_action_'.$project_id);
			$bulk_count = get_option('bulk_sync_article_count'.$project_id);
			$maintenance_message_sync = get_option('sortd_'.$project_id.'_maintenance_message_sync');
			$maintenance_message_unsync = get_option('sortd_'.$project_id.'_maintenance_message_unsync');
		
			if(empty($bulk_count)){
				$bulk_count = 0;
			}

				if(isset($maintenance_message_sync) && !empty($maintenance_message_sync)) {
					?>
						<div class="alert alert-danger  is-dismissible bulksortdaction"><p><?php echo wp_kses_data($maintenance_message_sync);?></p><span class="closeicon closeiconsync" aria-hidden="true">&times;</span></div>
					<?php
					delete_option('sortd_'.$project_id.'_maintenance_message_sync');
					update_option('bulk_action_'.$project_id,0);
				} elseif( isset($bulk_sync[0]) && $bulk_sync[0] === "1"){
				?>
				
				<div class="alert alert-success  is-dismissible bulksortdaction"><p>Sortd Sync Bulk Action Completed <?php echo wp_kses_data($bulk_count);?> articles synced</p><span class="closeicon closeiconsync" aria-hidden="true">&times;</span></div>

			<?php    
				  update_option('bulk_action_'.$project_id,0); 

				  unset($bulk_count);
					

				} 
				?>


			<?php $bulk_unsync = get_option('bulk_action_unsync_'.$project_id);
			      $bulk_count_unsync = get_option('bulk_sync_article_unsync_count'.$project_id); 
			
				if(isset($maintenance_message_unsync) && !empty($maintenance_message_unsync)) {
					?>
						<div class="alert alert-danger  is-dismissible bulksortdaction"><p><?php echo wp_kses_data($maintenance_message_unsync);?></p><span class="closeicon closeiconsync" aria-hidden="true">&times;</span></div>
					<?php
					delete_option('sortd_'.$project_id.'_maintenance_message_unsync');
					delete_option('bulk_action_unsync_'.$project_id);
				} elseif( isset($bulk_unsync[0]) && $bulk_unsync[0] === "1"){
					?>
					
					<div class="alert alert-success  is-dismissible bulksortdaction"><p>Sortd Sync Bulk Action Completed <?php echo wp_kses_data($bulk_count_unsync);?> articles unsynced</p><span class="closeicon closeiconunsync" aria-hidden="true">&times;</span></div>

					<?php   

					delete_option('bulk_action_unsync_'.$project_id);

				} ?>

		
			
		<?php 	
			
		}

		if( 'makestories_story' === $screen->post_type && 'edit' === $screen->base && $screen->id === 'edit-makestories_story' ){
		
		
		
				  
			$bulk_sync_wb = get_option('bulk_action_webstory'.$project_id);
			$bulk_count_sync_wb = get_option('bulk_sync_webstory_count'.$project_id); 
	
	  
	  if( isset($bulk_sync_wb[0]) && $bulk_sync_wb[0] === "1"){
  
		  ?>
		  
		  <div class="alert alert-success bulksortdactionunysncwb"><p>Sortd Sync Bulk Action Completed <?php echo wp_kses_data($bulk_count_sync_wb);?> webstories synced</p><span class="closeicon closeiconunsyncwb" aria-hidden="true">&times;</span></div>

		  <?php   
			  update_option('bulk_action_webstory'.$project_id,0); 

		  } 

		  $bulk_unsync_wb = get_option('bulk_action_webstory_unsync'.$project_id);
		  $bulk_count_unsync_wb = get_option('bulk_webstory_count_unsync'.$project_id);
		  if( isset($bulk_unsync_wb[0]) && $bulk_unsync_wb[0] === "1"){
  
			?>
			
			<div class="alert alert-success bulkactionunsync"><p>Sortd Sync Bulk Action Completed <?php echo wp_kses_data($bulk_count_unsync_wb);?> webstories unsynced</p><span class="closeicon closeiconunsync" aria-hidden="true">&times;</span></div>
  
			<?php   
				update_option('bulk_action_webstory_unsync'.$project_id,0); 
  
			} 
		}

		if( 'web-story' === $screen->post_type && 'edit' === $screen->base && $screen->id === 'edit-web-story' ){
		
		
		
				  
			$bulk_sync_wb = get_option('bulk_action_webstory'.$project_id);
			$bulk_count_sync_wb = get_option('bulk_sync_webstory_count'.$project_id);
			$maintenance_message_wbsync = get_option('sortd_'.$project_id.'_maintenance_message_wbsync'); 
	  
			if(isset($maintenance_message_wbsync) && !empty($maintenance_message_wbsync)) {
				?>
					<div class="alert alert-danger  is-dismissible bulksortdaction"><p><?php echo wp_kses_data($maintenance_message_wbsync);?></p><span class="closeicon closeiconsync" aria-hidden="true">&times;</span></div>
				<?php
				delete_option('sortd_'.$project_id.'_maintenance_message_wbsync');
				update_option('bulk_action_webstory'.$project_id,0); 
			} elseif( isset($bulk_sync_wb[0]) && $bulk_sync_wb[0] === "1"){
  
		  ?>
		  
		  <div class="alert alert-success bulksortdactionunysncwb"><p>Sortd Sync Bulk Action Completed <?php echo wp_kses_data($bulk_count_sync_wb);?> webstories synced</p><span class="closeicon closeiconunsyncwb" aria-hidden="true">&times;</span></div>

		  <?php   
			  update_option('bulk_action_webstory'.$project_id,0); 

		  } 

		  $bulk_unsync_wb = get_option('bulk_action_webstory_unsync'.$project_id);
		  $bulk_count_unsync_wb = get_option('bulk_webstory_count_unsync'.$project_id);
		  $maintenance_message_unwbsync = get_option('sortd_'.$project_id.'_maintenance_message_wbunsync'); 
		  if(isset($maintenance_message_unwbsync) && !empty($maintenance_message_unwbsync)) {
			?>
				<div class="alert alert-danger  is-dismissible bulksortdaction"><p><?php echo wp_kses_data($maintenance_message_unwbsync);?></p><span class="closeicon closeiconsync" aria-hidden="true">&times;</span></div>
			<?php
			delete_option('sortd_'.$project_id.'_maintenance_message_wbunsync');
			update_option('bulk_action_webstory_unsync'.$project_id,0); 
		} elseif( isset($bulk_unsync_wb[0]) && $bulk_unsync_wb[0] === "1"){
  
			?>
			
			<div class="alert alert-success bulkactionunsync"><p>Sortd Sync Bulk Action Completed <?php echo wp_kses_data($bulk_count_unsync_wb);?> webstories unsynced</p><span class="closeicon closeiconunsync" aria-hidden="true">&times;</span></div>
  
			<?php   
				update_option('bulk_action_webstory_unsync'.$project_id,0); 
  
			} 
		}
		if((!empty( $screen->post_type ) && 'edit-tags' === $screen->base) || $screen->base === 'sortd_page_sortd-manage-settings'){
			?>
				<div class="alert alert-danger  is-dismissible bulksortdaction taxSyncUnsyncNotice" style="display:none;"></div>
			<?php
		}
	}
        
   
	   /**
	 * function for get contract details
	 *
	 * @since    2.0.0
	 */
	public function sortd_get_contract_details_after_verify() {

		$response = Sortd_Helper::get_plan_details();

		echo wp_kses_data($response);
		wp_die();

	}

	  /**
	 * function for get details on plugin deactivation
	 *
	 * @since    2.0.0
	 */

	 public function get_data_on_plugin_deactivation(){

		$plugin_deactivate_api_slug = "plugins/plugin-deactivate";  
 	
		$projectId =Sortd_Helper::get_project_id(); 
		$current_user = wp_get_current_user();
		$wordpressDomain = site_url();
		$getAdminEmail = get_option('admin_email');

		$params_deactivation = array("deactivate_by" => $current_user->user_email,"project_id" => $projectId, "wordpress_domain" => $wordpressDomain ,   "publisher_email" => $getAdminEmail);
		$params = stripslashes(wp_json_encode( $params_deactivation));
		Sortd_Helper::sortd_post_api_response($plugin_deactivate_api_slug, $params);
		
	
	}

	public function sortd_paid_articles(){

		if(!Sortd_Admin::nonce_check()) {
            $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
               echo wp_kses_data($result); wp_die();
        }
		$projectId =Sortd_Helper::get_project_id();
		$view_data = array();


		 $args = array(
			'meta_query' => array(
				array(
					'key' => 'sortd-paid-price'.$projectId,
					'value' => 0,
					'compare' => '!=',
				),
				array(
					'key' => 'sortd-paid-price'.$projectId,
					'value' => '',
					'compare' => '!=',
				),
				array(
					'key' => 'sortd_'.$projectId.'_post_sync',
					'value' => 1,
					'compare' => '==',
				
				)
			),
			'post_type' => 'post',
			'posts_per_page' => 10,
			'suppress_filters' => false 
		);
		$posts = get_posts($args);

		
		$args = array(
			'meta_query' => array(
				array(
					'key' => 'sortd-paid-price'.$projectId,
					'value' => 0,
					'compare' => '!=',
				),array(
					'key' => 'sortd-paid-price'.$projectId,
					'value' => '',
					'compare' => '!=',
				),
				array(
					'key' => 'sortd_'.$projectId.'_post_sync',
					'value' => 1,
					'compare' => '==',
				
				)
			),
			'post_type' => 'post',
			'posts_per_page' => -1,
			'suppress_filters' => false 
		);
		$postscount = get_posts($args);

		$project_data = json_decode(get_option('sortd_project_details'));
    
   
    
        if(!empty($project_data->data->domain->public_host)){
            $host_name = 'https://'.$project_data->data->domain->public_host;
        } else {
            $host_name = $project_data->data->domain->demo_host;
        }
           
    
        

		$count_posts = count($postscount);
		$posts_data = array();
		$cat_guids_array = array();
		$categories_api_slug = 'contentsettings/listcategories';

		$response = Sortd_Helper::sortd_get_api_response($categories_api_slug,'v2');
		$json_decoded_response = json_decode($response);
		foreach($json_decoded_response->data->categories as $k => $v){
			$cat_guids_array[] = $v->cat_guid;
		}

		$args = array(
			'category__in'         =>($cat_guids_array),
			
			'meta_query' => array(
				
				
				array(
					'relation' => 'AND', 
					'key' => 'sortd_'.$projectId.'_post_sync',
					'value' => 1,
					'compare' => '==',
				
				),
				array(
					'key' => 'sortd-paid-price'.$projectId,
					'value' => 0,
					'compare' => '!=',
				),array(
					'key' => 'sortd-paid-price'.$projectId,
					'value' => '',
					'compare' => '!=',
				),
			),
			
			
			 'orderby'       => 'ID',
    		 'order'         => 'DESC',
			
			 'post_type' => 'post',
			 'post_status' => 'publish',
			 'posts_per_page' => -1,
			
		);
		$posts = new WP_Query($args);

		

		

		foreach($posts->posts as $k => $v){
			$posts_data[$k]['title'] = $v->post_title;
			$posts_data[$k]['paid_price'] = get_post_meta($v->ID,'sortd-paid-price'.$projectId,true);
			foreach(get_the_category($v->ID) as $keyCat => $valueCat){
				if(in_array($valueCat->cat_ID,$cat_guids_array,true)){
					$posts_data[$k]['category']['name'][] = $valueCat->name;
				
					$posts_data[$k]['category']['data'][$keyCat]['id'] = $valueCat->cat_ID;
					$posts_data[$k]['category']['data'][$keyCat]['name'] = $valueCat->name;
				}
			}
			$posts_data[$k]['categories'] = implode(',',$posts_data[$k]['category']['name']);
			$posts_data[$k]['url'] = $host_name.'/article/'.$v->post_name.'/'.$v->ID;
			$posts_data[$k]['post_id'] = $v->ID;
			
		}
		$cat_array = array();
		foreach($posts_data as $vdata){
			foreach($vdata['category']['data'] as $vCategory){

				if(!in_array($vCategory,$cat_array,true)){
					$cat_array[] = ($vCategory);
				}
				
			}
		
		}

		
		
		$view_data['paid_articles_data'] = $posts_data  ;
		$view_data['cat_array'] = $cat_array  ;
		$view_data['count_posts'] = $count_posts  ;

		$project_details = Sortd_Helper::get_cached_project_details();
		$pwa = get_option('sortd_'.$project_details->data->id.'_redirection_code');
		$amp = get_option('sortd_'.$project_details->data->id.'redirectValueAmp');
		$markflag = get_option('sortd_'.$project_details->data->id.'_markfreeflag');
		
	
        if($project_details->data->paidarticle_enabled === true && ($pwa === "false" && $amp === "false")){
         	$msg = "Paid Article Feature is enabled but redirection for your project is off , kindly enable it to prevent rendering of paid articles as free.";
        } else if(!empty($project_details->data->public_host)) {
			$msg = "Public host is not setup";
		} else if($project_details->data->paidarticle_enabled === true && (!$pwa && !$amp)) {
			$msg = "Paid Article Feature is enabled but redirection for your project is off , kindly enable it to prevent rendering of paid articles as free.";
		} 

		if(isset($msg)){
			$view_data['message']  = $msg;
		}

		
		$view_data['markflag'] = $markflag;
		$view_data['project_id'] = $project_details->data->id;
		Sortd_Helper::render_partials(array('paid_article_details'), $view_data);
	}

	public function get_count_after_reset(){

		if(!check_ajax_referer('sortd-ajax-nonce-utils', 'sortd_nonce')) {
			$result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
			echo wp_kses_data($result); wp_die();
		}
		$projectId =Sortd_Helper::get_project_id();
		$args = array(
			'meta_query' => array(
				array(
					'key' => 'sortd-paid-price'.$projectId,
					'value' => 0,
					'compare' => '!=',
				),array(
					'key' => 'sortd-paid-price'.$projectId,
					'value' => '',
					'compare' => '!=',
				),
				array(
					'key' => 'sortd_'.$projectId.'_post_sync',
					'value' => 1,
					'compare' => '==',
				
				)
			),
			'post_type' => 'post',
			'posts_per_page' => -1,
			'suppress_filters' => false 
		);
		$postscount = get_posts($args);
		$count = count($postscount);

		echo wp_json_encode($count);

		wp_die();
	}

	public function sortd_get_paid_articles(){
		if(!check_ajax_referer('sortd-ajax-nonce-utils', 'sortd_nonce')) {
			$result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
			echo wp_kses_data($result); wp_die();
		}
	
		if(isset($_POST['page'])){
            $page = sanitize_text_field($_POST['page']);
        }
	  
		$project_data = json_decode(get_option('sortd_project_details'));
    
   
    
        if(!empty($project_data->data->domain->public_host)){
            $host_name = 'https://'.$project_data->data->domain->public_host;
        } else {
            $host_name = $project_data->data->domain->demo_host;
        }
		$projectId =Sortd_Helper::get_project_id();
		$items_per_page = 10;
		 $offset = ($page * $items_per_page) - $items_per_page;
		 $args = array(
			'meta_query' => array(
				array(
					'key' => 'sortd-paid-price'.$projectId,
					'value' => 0,
					'compare' => '!=',
				
				),array(
					'key' => 'sortd-paid-price'.$projectId,
					'value' => '',
					'compare' => '!=',
				),
				array(
					'key' => 'sortd_'.$projectId.'_post_sync',
					'value' => 1,
					'compare' => '==',
				
				)
			),
			'orderby'       => 'ID',
    		'order'         => 'DESC',
			'post_type' => 'post',
			'offset' =>  $offset,
			'posts_per_page' => 10,
			'suppress_filters' => false 
		);
		$posts = get_posts($args);
		$posts_data = array();

		$categories_api_slug = 'contentsettings/listcategories';

		$response = Sortd_Helper::sortd_get_api_response($categories_api_slug,'v2');
		$json_decoded_response = json_decode($response);
		$cat_guids_array = array();
		foreach($json_decoded_response->data->categories as $k => $v){
			$cat_guids_array[] = $v->cat_guid;
		}
	
		foreach($posts as $k => $v){
			$posts_data[$k]['title'] = $v->post_title;
			$posts_data[$k]['paid_price'] = get_post_meta($v->ID,'sortd-paid-price'.$projectId,true);
			foreach(get_the_category($v->ID) as $keyCat => $valueCat){
				if(in_array($valueCat->cat_ID,$cat_guids_array, true)){
					$posts_data[$k]['category']['name'][] = $valueCat->name;
				
					$posts_data[$k]['category']['data'][$keyCat]['id'] = $valueCat->cat_ID;
					$posts_data[$k]['category']['data'][$keyCat]['name'] = $valueCat->name;
				}
			}
			$posts_data[$k]['categories'] = implode(',',$posts_data[$k]['category']['name']);
			$posts_data[$k]['url'] = $host_name.'/article/'.$v->post_name.'/'.$v->ID;
			$posts_data[$k]['post_id'] = $v->ID;
			$posts_data[$k]['url_admin'] = admin_url().'post.php?post='.$v->ID.'&action=edit';
		}
		$paid_articles_data = array();
		$paid_articles_data['paid_articles_data'] = $posts_data  ;
		echo wp_json_encode($paid_articles_data,JSON_UNESCAPED_UNICODE);

		

        wp_die();
	}


	public function mark_free_sortd_action(){
		if(!check_ajax_referer('sortd-ajax-nonce-utils', 'sortd_nonce')) {
			$result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
			echo wp_kses_data($result); wp_die();
		}
		if(isset($_POST['markFreeArticles'])){
			$markFreeArticles = wp_json_encode(array_map('sanitize_text_field',$_POST['markFreeArticles']));
			$markArticles = array_map('sanitize_text_field',$_POST['markFreeArticles']);
			
		}
		
	
		$params = '{
			"articleIds" : '.$markFreeArticles.'

		}';

		$project_id =Sortd_Helper::get_project_id();

		$markfree_api_slug = "article/mark-free";
		
		$api_response = Sortd_Helper::sortd_post_api_response($markfree_api_slug,$params);
		$response = json_decode($api_response);
	

		if($response->status === true && $response->data->status === true){
			foreach($markArticles as $article_id){
				update_post_meta($article_id,'sortd-paid-price'.$project_id,'');
				update_post_meta($article_id,'sortd_'.$project_id.'_new_price','');
			}

			update_option('sortd_'.$project_id.'_markfreeflag',1);
		}

		echo wp_kses_data($api_response);
		wp_die();
	}

	function title_filter( $where, $wp_query ){
		global $wpdb;
		$search_term = $wp_query->get( 'search_prod_title' );
		if ( $search_term ) {
			$where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'%' . esc_sql( $wpdb->esc_like( $search_term ) ) . '%\'';
		}
		return $where;
	}

	public function search_based_on_filters(){
		if(!check_ajax_referer('sortd-ajax-nonce-utils', 'sortd_nonce')) {
			$result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
			echo wp_kses_data($result); wp_die();
		}

		$category = '';
		$paid_from = '';
		$paid_to = '';
		$title = '';

		$project_id =Sortd_Helper::get_project_id();

		if(isset($_POST['title']) && !empty($_POST['title'])){
			$title = sanitize_text_field($_POST['title']);
		}

		if(isset($_POST['categoryData']) && !empty($_POST['categoryData'])){
			$category = sanitize_text_field($_POST['categoryData']);
		}

		if(isset($_POST['price']) && !empty($_POST['price'])){
			$paid_from =(int)$_POST['price'];
		}

		if(isset($_POST['priceto']) && !empty($_POST['priceto'])){
			$paid_to = (int)$_POST['priceto'];
		}

		if(isset($_POST['page']) && !empty($_POST['page'])){
			$page =sanitize_text_field($_POST['page']);
			$items_per_page = 10;
			$offset = ($page * $items_per_page) - $items_per_page;
		}
		$cat_new = array();
		if(is_array($category)){
			foreach($category as $k => $v){
				$cat_new[$k] = "'".$v."'";
			}
		}

	

		$project_data = json_decode(get_option('sortd_project_details'));
    
   
    
        if(!empty($project_data->data->domain->public_host)){
            $host_name = 'https://'.$project_data->data->domain->public_host;
        } else {
            $host_name = $project_data->data->domain->demo_host;
        }
		wp_reset_query();

		if(!empty($paid_from) && !empty($paid_to)){
			
			$price_meta = array(
				'relation' => 'AND', 
				'key'	 	=> 'sortd-paid-price'.$project_id,
				'value'	  	=> array($paid_from, $paid_to),
				'compare' 	=> 'BETWEEN',
				'type' => 'NUMERIC'
			);
		} else if(!empty($paid_from) && empty($paid_to)){
			
			$price_meta = array(
				'relation' => 'AND', 
				'key'	 	=> 'sortd-paid-price'.$project_id,
				'value'	  	=> $paid_from,
				'compare' 	=> '>=',
				'type' => 'NUMERIC'
			);
		} else if(!empty($paid_to) && empty($paid_from)){
	       
			$price_meta = array(
				'relation' => 'AND', 
				'key'	 	=> 'sortd-paid-price'.$project_id,
				'value'	  	=> array(1, $paid_to),
				'compare' 	=> 'BETWEEN',
				'type' => 'NUMERIC'
			);
		} else {
		    
			$price_meta = array(
				'relation' => 'AND', 
				'key'	 	=> 'sortd-paid-price'.$project_id,
				'value'	  	=> '',
				'compare' 	=> '!=',
			);
		}

		$posts_data = array();
		$cat_guids_array=array();

		$categories_api_slug = 'contentsettings/listcategories';

		$response = Sortd_Helper::sortd_get_api_response($categories_api_slug,'v2');
		$json_decoded_response = json_decode($response);
		foreach($json_decoded_response->data->categories as $k => $v){
			$cat_guids_array[] = $v->cat_guid;
		}

		if(!empty($category)){
			$category_in = $category;
		} else { 
			$category_in = $cat_guids_array;
		}

		
		
		
		$items_per_page = 10;
		if(isset($page) && !empty($page)){
			$offset = ($page * $items_per_page) - $items_per_page;
		}		
		 $args = array(
			'category__in'         =>($category_in),
			
			'search_prod_title' => $title,
	
			'meta_query' => array(
				
				
				array(
					'relation' => 'AND', 
					'key' => 'sortd_'.$project_id.'_post_sync',
					'value' => 1,
					'compare' => '==',
				
				),
				$price_meta
			),
			
			
			 'orderby'       => 'ID',
    		 'order'         => 'DESC',
			
			 'post_type' => 'post',
			 'post_status' => 'publish',
			 'posts_per_page' => -1,
			
		);
		add_filter( 'posts_where',array($this,'title_filter') , 10, 2 );
		$posts = new WP_Query($args);
		$count = sizeof($posts->posts);
	



		
		wp_reset_postdata();

		foreach($posts->posts as $k => $v){
			$posts_data[$k]['title'] = $v->post_title;
			$posts_data[$k]['paid_price'] = get_post_meta($v->ID,'sortd-paid-price'.$project_id,true);
			foreach(get_the_category($v->ID) as $keyCat => $valueCat){
				if(in_array($valueCat->cat_ID,$cat_guids_array,true)){
					$posts_data[$k]['category']['name'][] = $valueCat->name;
					$posts_data[$k]['category']['data'][$keyCat]['id'] = $valueCat->cat_ID;
					$posts_data[$k]['category']['data'][$keyCat]['name'] = $valueCat->name;
				}
			}
			$posts_data[$k]['categories'] = implode(',',$posts_data[$k]['category']['name']);
			$posts_data[$k]['url'] = $host_name.'/article/'.$v->post_name.'/'.$v->ID;
			$posts_data[$k]['post_id'] = $v->ID;
			$posts_data[$k]['url_admin'] = admin_url().'post.php?post='.$v->ID.'&action=edit';
			
		}

		$array_merge = array();
		$array_slice = array_slice($posts_data, $offset, 10);
		$array_merge['count'] = $count;
		$array_merge['chunks'] = $array_slice;
		echo wp_json_encode($array_merge);
	

		wp_die();
	}

}
