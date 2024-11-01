<?php

/**
 * The config-specific functionality of the plugin.
 *
 * @link       https://www.sortd.mobi
 * @since      2.0.0
 *
 * @package    Sortd
 * @subpackage Sortd/admin
 */

/**
 * The config-specific functionality of the plugin.
 *
 * @package    Sortd
 * @subpackage Sortd/admin
 * @author     Your Name <email@example.com>
 */
class Sortd_Config {

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
		$this->loader->add_action( 'wp_ajax_sortd_ajax_config_file_upload', $this, 'file_upload_in_config' );
        $this->loader->add_action( 'wp_ajax_sortd_ajax_save_config', $this, 'save_config' );
        $this->loader->add_action( 'wp_ajax_sortd_ajax_display_group_config', $this, 'ajax_display_group_config' );
        
	}
    
     /**
	 * function for including scripts
	 *
	 * @since    2.0.0
	 */
    public function enqueue_scripts() {

        wp_enqueue_script('sortd-config', SORTD_JS_URL . '/sortd-config-data.js', array( 'jquery' ), $this->version, true );
        wp_enqueue_script( 'validation-js', SORTD_JS_URL . '/jquery.validate.min.js', array( 'jquery' ), $this->version, true );
        wp_localize_script(
            'sortd-config',
            'sortd_ajax_obj_config',
            array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'nonce'    => wp_create_nonce( 'sortd-ajax-nonce-config' ),
            )
        );
                
	}

	/**
	 *  function for config dashboard screen
	 *
	 * @since    2.0.0
	 */
	public function config_dashboard() {

        if(Sortd_Admin::nonce_check()){
            
            $project_id = Sortd_Helper::get_project_id();
            
         
            $categories_api_slug =  "contentsettings/listcategories";

          
            
            $config_schema_api_slug =  'config/schema/all';

            $category_response = Sortd_Helper::sortd_get_api_response($categories_api_slug,'v2');
          
            $response = json_decode($category_response,TRUE);
           
            $config_schema_response =  Sortd_Helper::sortd_get_api_response($config_schema_api_slug);

            $credentials = Sortd_Helper::get_credentials_values();
            
            $project_details = Sortd_Helper::get_project_details();

            $project_saved_config_api_slug = 'config/project/'.$project_id.'/all';

            $project_saved_config_response = Sortd_Helper::sortd_get_api_response($project_saved_config_api_slug);

            
            $view_data = array();
            $view_data['credentials'] = $credentials;
            $view_data['project_details'] = $project_details;
            $view_data['access_key'] = $credentials['access_key'];
            $view_data['secret_key'] = $credentials['secret_key'];
            
            
            if(!$credentials){
                Sortd_Helper::render_partials(array('sortd-oneclick-get-started'), $view_data);
            } else {
                
                $demo_host = '';
                if($project_details->status){
                      $demo_host = $project_details->data->domain->demo_host;
                }
                
                $current_config_group = 'general_settings';

           
                
                if(isset($_GET['parameter']) && !empty($_GET['parameter'])){
                    $nonce = isset($_REQUEST['_wpnonce']) ? sanitize_text_field($_REQUEST['_wpnonce']) :"";
		            if( $nonce && wp_verify_nonce( $nonce, SORTD_NONCE ) )
                        $current_config_group = sanitize_text_field($_GET['parameter']);
                }

            
                $view_data['categories'] = $response['status']?$response['data']:array();
               
                $view_data['demo_host'] = $demo_host;
                $view_data['project_title'] = get_bloginfo( 'name' ) ;
                $view_data['project_description'] = get_bloginfo( 'description' ); 
                $view_data['config_schema'] = str_replace("'", " ", $config_schema_response);
                $view_data['config_schema_object'] = json_decode($config_schema_response);
                $view_data['saved_config_object']= json_decode($project_saved_config_response);
                $view_data['current_config_group'] = $current_config_group;
                
                $saved_group_config = array();
                $current_group_saved_config = false;
                if($view_data['saved_config_object']->status){
                    foreach($view_data['saved_config_object']->data as $kdata => $vData){
                        $saved_group_config[$kdata] = json_decode($vData);
                    }
                    $current_group_saved_config = json_decode($view_data['saved_config_object']->data->$current_config_group);
                }
                
                $view_data['current_group_saved_config'] = $current_group_saved_config;
                $view_data['saved_group_config'] = $saved_group_config;

                
                
                if($response['status'] === true){
                    Sortd_Helper::render_partials(array('sortd-config-display'), $view_data);
                } else {
                    if(isset($response['error']['errorCode']) && $response['error']['errorCode'] === 408){
                        Sortd_Helper::render_partials(array('sortd-config-display'), $view_data);
                    } else {
                        Sortd_Helper::render_partials(array('sortd-project-verify-credentials'), $view_data);
                    }
                }

            }
        }

	}
        
        /**
	 *  function for save config
	 *
	 * @since    2.0.0
	 */
	public function save_config() {

            if(!check_ajax_referer('sortd-ajax-nonce-config', 'sortd_nonce')) {
				$result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
			   	echo wp_kses_data($result); wp_die();
			}
            
            if(isset($_POST['group_name'])){
                $config_group = sanitize_text_field($_POST['group_name']);
            }
            
            if(empty($config_group)){
                //TODO Send error response
                wp_die();
            }

            if(isset($_POST['items'])){
                $items = sanitize_text_field($_POST['items']);
            }
            
            $project_id = Sortd_Helper::get_project_id();
			
			$config_data = json_decode(base64_decode($items),true);
              
            $config_data_to_be_saved = array();
            foreach($config_data as $field_details){
                $field_name = $field_details['name'];
                $field_value = $field_details['value'];

                if($config_group === 'article' && $field_name === 'design:enable_polls') {
                    update_option('sortd_'.$project_id.'_enable_polls', $field_value);
                } elseif($config_group === 'general_settings' && $field_name === 'design:enable_jw_player') {
                    update_option('sortd_'.$project_id.'_jw_player', $field_value);
                } elseif($config_group === 'category' && $field_name === 'default_settings:topic_page_slug') {
                    update_option('sortd_'.$project_id.'_tag_redirection', $field_value); 
                } elseif($config_group === 'general_settings' && $field_name === 'project_meta:enable_category_post_order') {
                    update_option('sortd_'.$project_id.'_enable_post_priority', $field_value); 
                }
                
                //code for array field save
                if(strpos($field_name, '#multivaluearray') !== false){
                    $field_value = array();
                    
                    foreach($config_data as $array_field_details){
                        if($array_field_details['name'] === $field_details['name']){
                            $field_value[] = $array_field_details['value'];
                        }
                    }
                    
                    $field_name = str_replace('#multivaluearray', '', $field_name);
                }
                
                if(!$field_name || $field_name===NULL){
                    continue;
                }
                
                $field_name_keys = explode(':',$field_name);
                
                $config_data_to_be_saved = $this->_insert_using_keys($config_data_to_be_saved, $field_name_keys, $field_value);
            }
           
            $data = array();
            $data['groupName'] = $config_group;
            $data['formData']  = $config_data_to_be_saved;
         
		   	$params = (wp_json_encode($data,JSON_UNESCAPED_UNICODE));
			$store_config_api_slug = "config/storeconfig";
			
			$response = Sortd_Helper::sortd_post_api_response($store_config_api_slug, $params);	
			$config_save_response = json_decode($response);
           
			if(isset($config_save_response->updatedConfig->status) && $config_save_response->updatedConfig->status === true ){

				update_option('sortd_config_save_status',1);
			}
		  
			echo wp_kses_data($response);
            wp_die();

	}

        
        /**
	 *  function for upload file
	 *
	 * @since    2.0.0
	 */
	public function file_upload_in_config() {

            if(!check_ajax_referer('sortd-ajax-nonce-config', 'sortd_nonce')) {
				$result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
			   	echo wp_kses_data($result); wp_die();
			}
			
            if(isset($_POST['data'])){
                $data= array_map( 'sanitize_text_field' , $_POST['data']);
            }

          

			$data_arr = $this->_array_map_r('sanitize_text_field', $data);

         
		   	$data = (wp_json_encode($data_arr));

		   	
			$upload_api_slug =  "config/upload-image";
			
			$params = $data;

			$response = Sortd_Helper::sortd_post_api_response($upload_api_slug, $params);
		  
			echo wp_kses_data($response);

			wp_die();

	}
        
   
     /**
	 * util function
	 *
	 * @since    2.0.0
	 */
	private function _array_map_r( $func, $arr ) {

		$result = array();

	    foreach( $arr as $key => $value )
	    {
	        $result[ $key ] = ( is_array( $value ) ? $this->_array_map_r( $func, $value ) : ( is_array($func) ? call_user_func_array($func, $value) : $func( $value ) ) );
	    }

	    return $result;

	}
    
    private function _insert_using_keys($arr, $keys, $value){
        // we're modifying a copy of $arr, but here
        // we obtain a reference to it. we move the
        // reference in order to set the values.
        $a = &$arr;

        while( count($keys) > 0 ){
            // get next first key
            $k = array_shift($keys);

            // if $a isn't an array already, make it one
            if(!is_array($a)){
                $a = array();
            }

            // move the reference deeper
            $a = &$a[$k];
        }
        $a = $value;

        // return a copy of $arr with the value set
        return $arr;
    }
        
    /**
	 *  function for config dashboard screen using ajax
	 *
	 * @since    2.0.0
	 */
	public function ajax_display_group_config() {

        if(!check_ajax_referer('sortd-ajax-nonce-config', 'sortd_nonce')) {
            $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
               echo wp_kses_data($result); wp_die();
        }
            
            $project_id = Sortd_Helper::get_project_id();
            
           
            $categories_api_slug =  "contentsettings/listcategories";

          
            $config_schema_api_slug =  'config/schema/all';

            $category_response = Sortd_Helper::sortd_get_api_response($categories_api_slug,'v2');

            $response = json_decode($category_response,TRUE);

            $config_schema_response =  Sortd_Helper::sortd_get_api_response($config_schema_api_slug);

            $project_saved_config_api_slug = 'config/project/'.$project_id.'/all';

            $project_saved_config_response = Sortd_Helper::sortd_get_api_response($project_saved_config_api_slug);

            $view_data = array();
           
            $current_config_group = 'general_settings';

            if(isset($_POST['group_name']) && !empty($_POST['group_name'])){
                $current_config_group =  sanitize_text_field($_POST['group_name']);
            }

            $view_data['categories'] = $response['status']?$response['data']:array();
            $view_data['project_title'] = get_bloginfo( 'name' ) ;
            $view_data['project_description'] = get_bloginfo( 'description' ); 
            $view_data['config_schema'] = str_replace("'", " ", $config_schema_response);
            $view_data['config_schema_object'] = json_decode($config_schema_response);
            $view_data['saved_config_object']= json_decode($project_saved_config_response);
            $view_data['current_config_group'] = $current_config_group;
            
            
            $config_schema_object = json_decode($config_schema_response);

            $saved_group_config = array();
            $current_group_saved_config = false;
            if($view_data['saved_config_object']->status){
                foreach($view_data['saved_config_object']->data as $kdata => $vData){
                    $saved_group_config[$kdata] = json_decode($vData);
                }
                $current_group_saved_config = json_decode($view_data['saved_config_object']->data->$current_config_group);
            }

            $view_data['current_group_saved_config'] = $current_group_saved_config;
            $view_data['saved_group_config'] = $saved_group_config;
            
            $view_data['config_schema_group_value'] = $config_schema_object->data->$current_config_group;
            $view_data['config_schema_group_key'] = $current_config_group;
            $view_data['current_group_saved_config'] = $current_group_saved_config;
                
            if($response['status'] && $config_schema_object->status && $view_data['saved_config_object']->status){
                Sortd_Helper::render_partials(array('display-form'), $view_data, 'config');
            }else{
                echo ('false');
            }
            
            wp_die();
	}
       

}
