<?php

/**
 * The domains-specific functionality of the plugin.
 *
 * @link       https://www.sortd.mobi
 * @since      2.0.0
 *
 * @package    Sortd
 * @subpackage Sortd/admin
 */

/**
 * The domains-specific functionality of the plugin.
 *
 * @package    Sortd
 * @subpackage Sortd/admin
 * @author     Your Name <email@example.com>
 */
class Sortd_Domains {

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
		$this->loader->add_action('wp_ajax_sortd_create_domain', $this, 'create_public_domain');
		$this->loader->add_action('wp_ajax_sortd_update_public_host', $this, 'update_public_host');
		$this->loader->add_action('wp_ajax_generate_ssl', $this, 'generate_ssl');
		$this->loader->add_action('wp_ajax_validate_ssl', $this, 'validateC_ssl');
		$this->loader->add_action('wp_ajax_deploy_cdn', $this, 'deploy_cdn');
		$this->loader->add_action('wp_ajax_verify_cname', $this, 'verify_cname');
		$this->loader->add_action('wp_ajax_sortd_sync_authors', $this, 'sortd_sync_authors');
		$this->loader->add_action('wp_ajax_save_shors_cat',$this,'save_shorts_cat');
		$this->loader->add_action('wp_ajax_get_shors_cat',$this,'get_shors_cat');
	}

	/**
	 *  function to enqueue script form
	 *
	 * @since    2.0.0
	 */

	public function enqueue_scripts(){
		wp_enqueue_script( 'sweet-alert-message-js', SORTD_JS_URL . '/sweetalert.min.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script('sortd-domains', SORTD_JS_URL . '/sortd-domains.js', array( 'jquery' ), $this->version, true );
		wp_localize_script(
			'sortd-domains',
			'sortd_ajax_obj_domain',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'sortd-ajax-nonce-domains' ),
			)
		);
	}

	/**
	 *  function for domains screen
	 *
	 * @since    2.0.0
	 */
	public function domains_dashboard() {

		if( Sortd_Admin::nonce_check() ){
		$plugin_sortd_redirection   = new Sortd_Redirection( $this->sortd, $this->version, $this->loader);
		$project_domains            = $this->get_project_domains();
        
		$view_data = array();
		$view_data['project_domains'] = $project_domains;

		$plugin_sortd_dashboard = new Sortd_Dashboard($this->sortd, $this->version, $this->loader);
		$chatbot_dashboard_data = $plugin_sortd_dashboard->get_chat_bot();

		$contract_api_slug = 'saas/contract-details';
        Sortd_Helper::sortd_get_api_response($contract_api_slug);
        
		if(isset($project_domains->data->public_host)){
			$host = $project_domains->data->public_host;
		} else {
			$host = '';
		}


		$cname = $plugin_sortd_dashboard->get_cname_config();
		if($cname->data->allowPublicHostSetup!==true) { 
			$console_url = Sortd_Helper::get_pubconsole_url();
			$slug = Sortd_Helper::get_project_slug();
			$view_data['slug'] = $slug;
			$view_data['console_url'] = $console_url;
			Sortd_Helper::render_partials(array('sortd-contact-us'), $view_data);
		    return false;
		} 
		$host_flag              =   $plugin_sortd_redirection->parse_host_url($host);
		$view_data['host_flag'] =   $host_flag;
		$view_data['chatbot_dashboard_data'] = json_decode($chatbot_dashboard_data);
		
	
		Sortd_Helper::render_partials(array('sortd-domains-manage'), $view_data);
	}

	}
        
        /**
	 *  function to get project domains
	 *
	 * @since    2.0.0
	 */
	public function get_project_domains() {

		$api_slug = 'deployment/get-project-domains';
		$response =json_decode( Sortd_Helper::sortd_get_api_response($api_slug));

		return $response;

	}
        
        /**
	 *  function to create public domain
	 *
	 * @since    2.0.0
	 */
	public function create_public_domain() {


		if(!check_ajax_referer('sortd-ajax-nonce-domains', 'sortd_nonce')) {
			$result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
			   echo wp_kses_data($result); wp_die();
		}

		if(isset($_POST['public_host'])){
			$public_host = sanitize_text_field($_POST['public_host']);
		}
	
		
		$params = '{
			"domain" : "'.$public_host.'"
		 }';  			


		$create_domain_api_slug = "deployment/create-project-domain";

		$response = Sortd_Helper::sortd_post_api_response($create_domain_api_slug,$params); 

		echo wp_kses_data($response);

		wp_die();

	}

	      /**
	 *  function to edit public domain
	 *
	 * @since    2.0.0
	 */

	public function update_public_host(){

	
		if(!check_ajax_referer('sortd-ajax-nonce-domains', 'sortd_nonce')) {
			$result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
			   echo wp_kses_data($result); wp_die();
		}

		if(isset($_POST['domain'])){
			$public_host = sanitize_text_field($_POST['domain']);
		}
	
		
		$params = '{
			"domain" : "'.$public_host.'"
		 }';  			


		$create_domain_api_slug = "deployment/edit-project-domain";

		$response = Sortd_Helper::sortd_post_api_response($create_domain_api_slug,$params); 

		echo wp_kses_data($response);

		wp_die();

	}
        
        /**
	 *  function to generate ssl
	 *
	 * @since    2.0.0
	 */
	public function generate_ssl() {

		if(!check_ajax_referer('sortd-ajax-nonce-domains', 'sortd_nonce')) {
			$result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
			   echo wp_kses_data($result); wp_die();
		}
		$generate_ssl_api_slug = "deployment/generate-ssl";

		$params = '{}';  

		$response = Sortd_Helper::sortd_post_api_response($generate_ssl_api_slug,$params); 

		echo wp_kses_data($response);

		wp_die();

	}
        
        /**
	 *  function to validate ssl
	 *
	 * @since    2.0.0
	 */
	public function validateC_ssl() {
		if(!check_ajax_referer('sortd-ajax-nonce-domains', 'sortd_nonce')) {
			$result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
			   echo wp_kses_data($result); wp_die();
		}
		$validate_ssl_api_slug = "deployment/validate-ssl";

		$params = '{}';  

		$response = Sortd_Helper::sortd_post_api_response($validate_ssl_api_slug,$params); 
		echo wp_kses_data($response);
		wp_die();

	}
        
        /**
	 *  function to deploy cdn
	 *
	 * @since    2.0.0
	 */
	public function deploy_cdn() {

		if(!check_ajax_referer('sortd-ajax-nonce-domains', 'sortd_nonce')) {
			$result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
			   echo wp_kses_data($result); wp_die();
		}

		$deploy_cdn_api_slug = "deployment/deploy-cdn";

		$params = '';  

		$response = Sortd_Helper::sortd_post_api_response($deploy_cdn_api_slug,$params); 

		echo wp_kses_data($response);

		wp_die();

	}
        
        /**
	 *  function to verify cname
	 *
	 * @since    2.0.0
	 */
	public function verify_cname() {

		if(!check_ajax_referer('sortd-ajax-nonce-domains', 'sortd_nonce')) {
			$result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
			   echo wp_kses_data($result); wp_die();
		}
		
		$api_slug = 'deployment/verify-cname';
		$response =Sortd_Helper::sortd_get_api_response($api_slug);

		echo wp_kses_data($response);

		wp_die();

	}

	    /**
	 *  function to sync authors data
	 *
	 * @since    2.2.1
	 */

	 public function sortd_sync_authors(){

		if(!check_ajax_referer('sortd-ajax-nonce-domains', 'sortd_nonce')) {
			$result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
			   echo wp_kses_data($result); wp_die();
		}
		$project_id = Sortd_Helper::get_project_id();

		$users = get_users();
		$size = sizeof($users);

		$counter = 0;
		$counter_success = 0;
		$response_array = array();
	
		foreach($users as $v){
			$meta_key_exists = metadata_exists('user',$v->data->ID,'sortd_sync_author_'.$project_id,true);
			if($meta_key_exists !== true){
				$sortd_article = new Sortd_Article($this->sortd, $this->version, $this->loader);
        		$res = $sortd_article->sync_author_data($v->data->ID);
				$res = json_decode($res);
				if($res->status !== false) {
					$counter_success++;
				} elseif($res->status === false && (isset($res->error->errorCode) && $res->error->errorCode === 503)) {
					$response_array['maintain_error'] = $res->error->message;
				}
			}
			$counter++;
		}
		
		if($size === $counter){
			$flag = true;
			$response_array['flag'] = $flag;
			$response_array['synced_count'] = $counter_success;
			if(!array_key_exists('maintain_error', $response_array)) {
				update_option('sortd_author_sync_success_'.$project_id,1);
			}
		} else{
			$flag = false;
			$response_array['flag'] = $flag;
			$response_array['synced_count'] = 0;
			update_option('sortd_author_sync_success_'.$project_id,0);
		}

		echo wp_json_encode($response_array);

		wp_die();
	}

	public function contact_us(){

		if(!Sortd_Admin::nonce_check()) {
            $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
               echo wp_kses_data($result); wp_die();
        }

		$view_data = array();
		$console_url = Sortd_Helper::get_pubconsole_url();
		$slug = Sortd_Helper::get_project_slug();
		$view_data['slug'] = $slug;
		$view_data['console_url'] = $console_url;

			Sortd_Helper::render_partials(array('sortd-contact-us'), $view_data);
	

		
	}

	public function save_shorts_cat(){

		if(!check_ajax_referer('sortd-ajax-nonce-domains', 'sortd_nonce')) {
			$result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
			   echo wp_kses_data($result); wp_die();
		}
		$project_id = get_option('sortd_projectid');

		$article_url_redirection_flag = get_option('sortd_'.$project_id.'_article_url_redirection_flag');
		$category_url_redirection_flag = get_option('sortd_'.$project_id.'_category_url_redirection_flag');
        $category_url_canonical_url = get_option('sortd_'.$project_id.'_canonical_url_redirection_flag');

		if ($article_url_redirection_flag !== false && $article_url_redirection_flag === '1') {
            // Option exists
            $article_toggle_value = 'true';
        } else {
            // Option does not exist
            $article_toggle_value = 'false';
        }
       
        if ($category_url_redirection_flag !== false && $category_url_redirection_flag === '1') {
            // Option exists
            $category_toggle_value = 'true';
        } else {
            // Option does not exist
            $category_toggle_value = 'false';
        }

		if($category_url_canonical_url !== false && $category_url_canonical_url === '1'){
            $canonical_toggle_value = 'true';
        } else {
            $canonical_toggle_value = 'false';
        }

		if(isset($_POST['id'])){
			$cat_id = sanitize_text_field($_POST['id']);
		}

		
		$params = '{
            "enable_category_in_article" : '.$article_toggle_value.',
            "enable_category_alias_url" : '.$category_toggle_value.' ,
            "self_canonical" : '.$canonical_toggle_value.',
            "shorts_category_id" : "'.$cat_id.'"            
        }';
		$project_id = Sortd_Helper::get_project_id();
		$redirect_api_slug = 'project/update-redirection-settings';
		$redirect_response = Sortd_Helper::sortd_post_api_response($redirect_api_slug, $params);
		$response = json_decode($redirect_response);
		if($response->status !== false) {
			$update_response = update_option('sortd_shorts_catid_'.$project_id,$cat_id);
		} else {
			$update_response = false;
		}

		$response->update_response = $update_response;
		
		
		if($response->status === true){

            update_option('sortd_'.$project_id.'_article_url_redirection_flag',$response->data[0]->enable_category_in_article);
            update_option('sortd_'.$project_id.'_category_url_redirection_flag',$response->data[0]->enable_category_alias_url);
            update_option('sortd_'.$project_id.'_canonical_url_redirection_flag',$response->data[0]->self_canonical);


        }
		echo wp_json_encode($response);

		wp_die();
	


	}

	public function get_shors_cat(){

		if(!check_ajax_referer('sortd-ajax-nonce-domains', 'sortd_nonce')) {
			$result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
			   echo wp_kses_data($result); wp_die();
		}

		$project_id = Sortd_Helper::get_project_id();

		$response = get_option('sortd_shorts_catid_'.$project_id);

		echo wp_json_encode($response);

		wp_die();
	}
        
       

}
