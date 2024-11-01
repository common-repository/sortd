<?php

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @link       https://www.sortd.mobi
 * @since      2.0.0
 *
 * @package    Sortd
 * @subpackage Sortd/admin
 */

/**
 * The dashboard-specific functionality of the plugin.
 *
 * @package    Sortd
 * @subpackage Sortd/admin
 * @author     Your Name <email@example.com>
 */
class Sortd_Dashboard {

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
		$this->loader->add_action('wp_ajax_sortd_unsync_article', $this, 'delete_article_dashboard');
		$this->loader->add_action('wp_ajax_sortd_restore', $this, 'restore_article');
		$this->loader->add_action('wp_ajax_sortd_dailyingestedarticles', $this, 'daily_ingested_articles');
		$this->loader->add_action('wp_ajax_article-type-count', $this, 'article_type_count');
		$this->loader->add_action('wp_ajax_get_notification_stats_dashboard', $this, 'get_notification_stats');
		$this->loader->add_action('wp_ajax_get_config_data', $this, 'get_config_data');
		$this->loader->add_action('wp_ajax_sortd_sync_authors', $this, 'sortd_sync_authors');
		$this->loader->add_action('wp_ajax_webstories_count',$this,'webstories_count');
	}



	/**
	 * function for including scripts
	 *
	 * @since    2.0.0
	 */
	public function enqueue_scripts() {

	
		
		wp_enqueue_script('sortd-chartjs', SORTD_CSS_URL . '/assets/js/chart.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script('sortd-dashboard',SORTD_JS_URL . '/sortd-dashboard.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script('sortd-utils', SORTD_JS_URL . '/sortd-utils.js', array( 'jquery' ), $this->version, true );	
		wp_enqueue_script( 'sweet-alert-message-js', SORTD_JS_URL . '/sweetalert.min.js', array( 'jquery' ), $this->version, true );
		wp_localize_script(
			'sortd-dashboard',
			'sortd_ajax_obj_dashboard',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'sortd-ajax-nonce-dashboard' ),
			)
		);
	
	}

	/**
	 *  settings dashboard redirections
	 *
	 * @since    2.0.0
	 */
	public function settings_dashboard() {
				if(isset($_GET['section']) && $_GET['section'] === 'sortd_manage_templates'){
					if( Sortd_Admin::nonce_check() ){
							$plugin_sortd_templates = new Sortd_Templates($this->sortd, $this->version, $this->loader);
							$plugin_sortd_templates->manage_templates();
					}
                
					
                }else if(isset($_GET['section']) && $_GET['section'] === 'sortd_manage_categories'){
					$nonce = isset($_REQUEST['_wpnonce']) ? sanitize_text_field($_REQUEST['_wpnonce']) :"";
					if( $nonce && wp_verify_nonce( $nonce, SORTD_NONCE ) ) {
						$plugin_sortd_categories = new Sortd_Categories($this->sortd, $this->version, $this->loader);
						$plugin_sortd_categories->manage_categories();
					}
                    
                }else if(isset($_GET['section']) && $_GET['section'] === 'sortd_redirection'){
                    if( Sortd_Admin::nonce_check() ){
					$plugin_sortd_redirection = new Sortd_Redirection( $this->sortd, $this->version, $this->loader );
					$plugin_sortd_redirection->redirection_page();
					}

                }else if(isset($_GET['section']) && $_GET['section'] === 'sortd_config'){
                    if( Sortd_Admin::nonce_check() ){
                    $plugin_sortd_config = new Sortd_Config($this->sortd, $this->version, $this->loader);
                    $plugin_sortd_config->config_dashboard();
					}

                } else if(isset($_GET['section']) && $_GET['section'] === 'sortd_manage_domains'){
                    if( Sortd_Admin::nonce_check() ){
					$plugin_sortd_domains = new Sortd_Domains( $this->sortd, $this->version, $this->loader );
					$plugin_sortd_domains->domains_dashboard();
					}
                    
                } else if(isset($_GET['section']) && $_GET['section'] === 'sortd_credential_settings'){
                    if( Sortd_Admin::nonce_check() ){
					$plugin_sortd_utils = new Sortd_Utils( $this->sortd, $this->version, $this->loader );
					$plugin_sortd_utils->credentials_page();
					}
                    
                } else if(isset($_GET['section']) && $_GET['section'] === 'sortd_paid_articles'){
                    if( Sortd_Admin::nonce_check() ){
					$plugin_sortd_utils = new Sortd_Utils( $this->sortd, $this->version, $this->loader );
					$plugin_sortd_utils->sortd_paid_articles();
					}

                    
                }else if(isset($_GET['section']) && $_GET['section'] === 'contact-us'){ 
					if( Sortd_Admin::nonce_check() ){
					$plugin_sortd_domains = new Sortd_Domains( $this->sortd, $this->version, $this->loader );
					$plugin_sortd_domains->contact_us();
					}

				} else if(isset($_GET['section']) && $_GET['section'] === 'sortd_manage_taxonomies'){                
					if( Sortd_Admin::nonce_check() ){
					$plugin_sortd_categories = new Sortd_Categories($this->sortd, $this->version, $this->loader);
					$plugin_sortd_categories->manage_taxonomies();
					}
                    
            } else{
						$project_id = get_option('sortd_projectid');
	               $redirection_flag = get_option('sortd_'.$project_id.'_redirection_code');
	               $amp_enabled = get_option('sortd_'.$project_id.'redirectValueAmp');			
						
						if( $redirection_flag ===  "true" ||  $amp_enabled === "true"){
							$redirection_enabled = 1;
						} else if($redirection_flag === "false" || $amp_enabled === "false"){
							$redirection_enabled = 0;
						} else {
							$redirection_enabled = 0;
						}

						$project_details = Sortd_Helper::get_project_details();
						if(isset($project_details->data->domain->public_host)&& !empty($project_details->data->domain->public_host)){
							$host = $project_details->data->domain->public_host;
						} else if(($project_details->data->domain->demo_host) && !empty($project_details->data->domain->demo_host)) {
							$host = $project_details->data->domain->demo_host;
						}
						


						$view_data = array();
						$view_data['console_url'] = Sortd_Helper::get_pubconsole_url();
						$view_data['host'] = $host;
						$view_data['project_slug'] =  Sortd_Helper::get_project_slug();
						$view_data['redirection_enabled'] = $redirection_enabled;
						Sortd_Helper::render_partials(array('sortd-admin-settings'), $view_data);
            }

	}
        
        /**
	 *  function for home dashboard 
	 *
	 * @since    2.0.0
	 */
	public function home_dashboard() {

		

		$this->define_hooks();

      $credentials = Sortd_Helper::get_credentials_values();
		$project_details = Sortd_Helper::get_cached_project_details();		
		
		$date_format = get_option('date_format').' '.get_option('time_format');
		if(function_exists('wp_timezone_string')){
			$timezone_name_to = wp_timezone_string();
		} else {
			$timezone_name_to = date_default_timezone_get();
		}
		$plugin_activation_flag = get_option('activate_sortd');
		global $wp_version;
;
		if($plugin_activation_flag === '1'){
			$plugin_sortd_utils = new Sortd_Utils($this->sortd, $this->version, $this->loader);
			add_action('admin_notices',$plugin_sortd_utils->get_plugin_activate_message());
			update_option('activate_sortd',0);
		}
		if($wp_version < '5.3'){
			$view_data = array();
			
		} else {

			if(!$credentials){
					$view_data = array();
					Sortd_Helper::render_partials(array('sortd-oneclick-get-started'), $view_data);
			} else {

				
				$page = "";
				if(isset($_GET['page'])){
					$nonce = isset($_REQUEST['_wpnonce']) ? sanitize_text_field($_REQUEST['_wpnonce']) :"";
					if( $nonce && wp_verify_nonce( $nonce, SORTD_NONCE ) ) {
						$page=sanitize_text_field($_GET['page']);
					}
				}

				if((isset($_GET['section'])  &&  ($_GET['section'] === 'sortd_plans'))){
					$nonce = isset($_REQUEST['_wpnonce']) ? sanitize_text_field($_REQUEST['_wpnonce']) :"";
					if( $nonce && wp_verify_nonce( $nonce, SORTD_NONCE ) ) {
						$this->_plan_dashboard();
					}
				} else if(isset($_GET['section']) && ($_GET['section'] === 'pageinsights') && $page === "sortd-settings" ){ 
					$nonce = isset($_REQUEST['_wpnonce']) ? sanitize_text_field($_REQUEST['_wpnonce']) :"";
					if( $nonce && wp_verify_nonce( $nonce, SORTD_NONCE ) ) {
						$one_click_obj = new Sortd_Oneclick($this->sortd, $this->version, $this->loader);
						$one_click_obj->oneclick_page_insights();
					}

				} else  {					

					$plugin_sortd_stats = new Sortd_Stats($this->sortd, $this->version, $this->loader);
					$get_stats_data = $plugin_sortd_stats->get_sortd_stats_data();
					$get_alerts_data = $plugin_sortd_stats->get_alerts_details();

					$plugin_sortd_notifications = new Sortd_Notifications($this->sortd, $this->version, $this->loader);
					$stats_notifications_data = $plugin_sortd_notifications->get_notification_dashboard_data();
					
					$plans_data = json_decode( Sortd_Helper::get_plan_details());

					$plugin_sortd_domain = new Sortd_Domains($this->sortd, $this->version, $this->loader);
					$project_domains = ($plugin_sortd_domain->get_project_domains());

					if(isset($project_domains->data->public_host)&& !empty($project_domains->data->public_host)){
						$host = $project_domains->data->public_host;
					} else if(($project_domains->data->demo_host) && !empty($project_domains->data->demo_host)) {
						$host = $project_domains->data->demo_host;
					}

					$plugin_sortd_redirection = new Sortd_Redirection($this->sortd, $this->version, $this->loader);
					$redirection_enabled = $plugin_sortd_redirection->get_redirection_enable_settings();
					$plugin_sortd_redirection->get_sortd_service();
					$host_flag = $plugin_sortd_redirection->parse_host_url($host);

					$chatbot_data = $this->get_chat_bot();
					$banner_api_slug = 'project/alert-html';
					$banner_api_response = Sortd_Helper::sortd_get_api_response($banner_api_slug);
					$banner_json_decode  =json_decode($banner_api_response);

					//banner dashboard starts 
					$banner_html_data = "";
					if(!empty($banner_json_decode)){
						if($banner_json_decode->status===true){
						   if(!empty($banner_json_decode->data)){
							$banner_html_data =  $banner_json_decode->data; 
						   }
						   else{
							$banner_html_data = "";
						   }
						   
						}

					}

					// banner dashboard ends

					// webstory pie chart starts

					$webstory_api_slug = 'stats/project-stats';
					$webstory_api_response = Sortd_Helper::sortd_get_api_response($webstory_api_slug,'v2');
					$webstory_json_decode = json_decode($webstory_api_response);
					
					$webstory_data = "";
					
					
					if(!empty($webstory_json_decode)){
						if($webstory_json_decode->status === true){
							if(!empty($webstory_json_decode->data)){
								if(!empty($webstory_json_decode->data->webstoryCount)){
									$webstory_data = $webstory_json_decode->data->webstoryCount;
								}
								else{
									$webstory_data = "";
								}
							}
						}
					} else {
						$webstory_data = "";
					}
					

					// webstories pie Chart ends

					$cname = $this->get_cname_config();

					$view_data = array();
					$view_data['project_details'] = $project_details;
					$view_data['get_stats_data'] = $get_stats_data;
					$view_data['stats_notifications_data'] = $stats_notifications_data;
					$view_data['plans_data'] = $plans_data;
					$view_data['project_domains'] = $project_domains;
					$view_data['host_flag'] = $host_flag;
					$view_data['host'] = $host;
					$view_data['redirection_enabled'] = $redirection_enabled;
					$view_data['get_alerts_data'] = $get_alerts_data;
					$view_data['date_format'] = $date_format;
					$view_data['timezone_name_to'] = $timezone_name_to;
					$view_data['chatbot_data'] = json_decode($chatbot_data);
					$view_data['banner_html_data'] = $banner_html_data;
					$view_data['webstory_data'] = $webstory_data;
					$view_data['cname_response'] = $cname;
					Sortd_Helper::render_partials(array('sortd-home-dashboard'), $view_data);
				}
			}
		}
	}
        
        /**
	 *  function for plan dashboard
	 *
	 * @since    2.0.0
	 */
	private function _plan_dashboard() {

		    $credentials = Sortd_Helper::get_credentials_values();
			$console_url = Sortd_Helper::get_pubconsole_url();
			$slug = Sortd_Helper::get_project_slug();
			$api_slug = 'saas/contract-details';
            $response = Sortd_Helper::sortd_get_api_response($api_slug);

			$get_plan_html = '/saas/plan-page-html';
			$response_plan_html = Sortd_Helper::sortd_get_api_response($get_plan_html);


			if($response){
                $plans_response = json_decode($response);
            }

			if(!$credentials){
				$view_data = array();
				Sortd_Helper::render_partials(array('sortd-oneclick-get-started'), $view_data);
			} else {
				$view_data = array();
				$view_data['plan_data'] = json_decode($response);
				if(isset($view_data['plan_data']->data->plan_expire_date)){
					$days = floor((strtotime($view_data['plan_data']->data->plan_expire_date) - strtotime(gmdate('Y/m/d'))) / (60 * 60 * 24));
				} else {
					$days = 0;
				}
				if($days === 0){
				$msg = "today";
				} else {
				$msg = $days." day(s)";
				}

			
				$date = new DateTime();
				$view_data = array();
				$view_data['plan_data'] = $plans_response;
				$view_data['console_url'] = $console_url;
				$view_data['slug'] = $slug;
				$view_data['msg'] = $msg;
				$view_data['date'] = $date;
				$html_plan = json_decode($response_plan_html);
				$view_data['html_plan'] = $html_plan;
				
				Sortd_Helper::render_partials(array('sortd-plan-details'), $view_data);
			
			}

	}
	
        
        /**
	 *  function for faqs dashboard
	 *
	 * @since    2.0.0
	 */
        public function faqs_dashboard() {
           
            $faqs = array();
            
            $faqs_api_slug = 'faq/get-all';

            $response = Sortd_Helper::sortd_get_api_response($faqs_api_slug);

            if($response){
                $faqs = json_decode($response);
            }
            
            $view_data = array();
            $view_data['faqs'] = $faqs;
            
            Sortd_Helper::render_partials(array('sortd-help-faqs'), $view_data);
        }

		   /**
	 *  function for delete article dashboard
	 *
	 * @since    2.0.0
	 */

	public function delete_article_dashboard() {

		if(!check_ajax_referer('sortd-ajax-nonce-dashboard', 'sortd_nonce')) {
            $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
               echo wp_kses_data($result); wp_die();
        }
		$response = Sortd_Article::unsync_article_response(); 

		wp_kses($response);

		wp_die();
	}

	/**
	 *  function for restore article dashboard
	 *
	 * @since    2.0.0
	 */

	public function restore_article(){
		if(!check_ajax_referer('sortd-ajax-nonce-dashboard', 'sortd_nonce')) {
            $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
               echo wp_kses_data($result); wp_die();
        }

		if(isset($_POST['post_id'])){
			$data = sanitize_text_field($_POST['post_id']);
		}

		
		$project_id = Sortd_Helper::get_project_id();
		$restore_api_slug = "article/restore";
		$params = '{
			"article_guid" : "'.$data.'"
		  }';
		
		$api_response = Sortd_Helper::sortd_post_api_response($restore_api_slug,$params);
		$response = json_decode($api_response);
		if($response->status === "true" || $response->status === 1 || $response->status === true){
                                
			$article_id = ($response->data->article_id);
			Sortd_Helper::update_post_option_sync_flag($project_id,$data,1);
			Sortd_Helper::update_post_option_article_id($project_id,$data,$article_id);
			update_post_meta($data,'sortd_'.$project_id.'_sync_error_message', '' );
			
			
		} else if($response->status === false || $response->status !== 1 ||  $response->status === "false"){
			$error = $response->error->message;
			Sortd_Helper::update_post_option_sync_flag($project_id,$data,3);
			update_post_meta($data,'sortd_'.$project_id.'_sync_error_message', $error );
			
		
		}	
		
		echo wp_kses_data($api_response);
	
	
		wp_die();
	
	}

	/**
	 *  function for getting daily ingested articles dashboard
	 *
	 * @since    2.0.0
	 */

	public function daily_ingested_articles(){
		if(!check_ajax_referer('sortd-ajax-nonce-dashboard', 'sortd_nonce')) {
			$result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
		   	echo wp_kses_data($result); wp_die();
		}


		$api_slug = 'stats/daily-ingested-article-count';

		$api_response = Sortd_Helper::sortd_get_api_response($api_slug);
		echo wp_kses_data($api_response);
		
		wp_die();
	}

	/**
	 *  function for getting types of articles dashboard
	 *
	 * @since    2.0.0
	 */

	public function article_type_count(){
		if(!check_ajax_referer('sortd-ajax-nonce-dashboard', 'sortd_nonce')) {
			$result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
		   	echo wp_kses_data($result); wp_die();
		}


		$api_slug = 'stats/article-type-count';

		$api_response = Sortd_Helper::sortd_get_api_response($api_slug);
		echo wp_kses_data($api_response);
		
		wp_die();
	
	}

	/**
	 *  function for getting notification stats on dashboard
	 *
	 * @since    2.0.0
	 */


	public function get_notification_stats(){
		if(!check_ajax_referer('sortd-ajax-nonce-dashboard', 'sortd_nonce')) {
			$result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
		   	echo wp_kses_data($result); wp_die();
		}

	
		$plugin_sortd_notifications = new Sortd_Notifications($this->sortd, $this->version, $this->loader);
		$stats_notifications_data = $plugin_sortd_notifications->get_notification_dashboard_data();
		echo (wp_json_encode($stats_notifications_data));
		wp_die();
	}

	public function get_chat_bot(){
		$api_slug = 'plugins/chat';

		$api_response = Sortd_Helper::sortd_get_api_response($api_slug);
		$result =  wp_kses_data($api_response);


		return $result;
		
	}

	/**
	 *  function for getting config completeness data from api
	 *
	 * @since    2.2
	 */

	public function get_config_data(){
		

		$api_slug = 'config/config-completeness-status';

		$api_response = Sortd_Helper::sortd_get_api_response($api_slug);
		$result =  wp_kses_data($api_response);

		echo wp_kses_data($result);

		wp_die();
	}

		    /**
	 *  function to sync authors data
	 *
	 * @since    2.2.1
	 */

	public function sortd_sync_authors(){

		if(!check_ajax_referer('sortd-ajax-nonce-dashboard', 'sortd_nonce')) {
			$result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
		   	echo wp_kses_data($result); wp_die();
		}

		$project_id = Sortd_Helper::get_project_id();

		$users = get_users();
		$size = sizeof($users);

		$counter = 0;
	
		foreach($users as $v){
			$meta_key_exists = metadata_exists('user',$v->data->ID,'sortd_sync_author_'.$project_id,true);
			if($meta_key_exists !== '1' || $meta_key_exists !== 1){
				$sortd_article = new Sortd_Article($this->sortd, $this->version, $this->loader);
        		$sortd_article->sync_author_data($v->data->ID);
			}
			$counter++;
		}

		if($size === $counter){
			$flag = true;
		} else{
			$flag = false;
		}

		echo wp_json_encode($flag);

		wp_die();
	}

	/**
		 * function to get webstories on pichart on dashboard
		 * @since 2.3.1
			 */

		public function webstories_count(){
			if(!check_ajax_referer('sortd-ajax-nonce-dashboard', 'sortd_nonce')) {
				$result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
					echo wp_kses_data($result); wp_die();
			}
			
			$webstory_api_slug = 'stats/project-stats';
			$webstory_api_response = Sortd_Helper::sortd_get_api_response($webstory_api_slug,'v2');
			$result = wp_kses_data($webstory_api_response);
			echo wp_kses_data($result); 
	
			wp_die();
		}


		public function get_cname_config(){
			//public host configurable code
			$api_slug = 'project/actions';

			$cname_api_response = Sortd_Helper::sortd_get_api_response($api_slug);

			return json_decode($cname_api_response);

		
		}
        

}
