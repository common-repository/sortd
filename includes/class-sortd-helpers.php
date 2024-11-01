<?php

/**
 * Fired during plugin activation
 *
 * @link              https://www.sortd.mobi
 * @since             2.0.0
 * @package           Sortd
 */

if ( ! defined( 'ABSPATH' ) ) exit; 
class Sortd_Helper {

	/**
	 * function for curl get request
	 *
	 * @since    2.0.0
	 */
	public static function curl_get($url,$headers) {
	
		$args = array('headers' => $headers,
	 				'timeout'   => 10);

		$response 	  = wp_remote_get($url,$args);
		$response_code = wp_remote_retrieve_response_code($response);

		if ( is_array( $response ) && ! is_wp_error( $response ) ) {
		    $body = wp_remote_retrieve_body($response);
		    $response = $body;
		} else {
			$result =  array();
            $result['status'] = false;
			$result['error']['message'] = $response->get_error_message();
			$result['error']['errorCode'] = 408;
			$response = wp_json_encode($result);
		}
		

		if($response === false || $response_code === 0 || $response_code === 500 || $response_code ===502  || $response_code === 7){
			$fail_response =  array();
			$fail_response['status'] = false;
			$fail_response['error']['message'] = "SORTD Server is not reachable";
			$response = wp_json_encode($fail_response);
		} 
                
		return $response;

	}


        /**
	 * function for curl post request
	 *
	 * @since    2.0.0
	 */
	public static function curl_post($url,$headers,$params) {
	
			
            $args = array('headers' => $headers,
                                    'body'      => $params,
                                    'timeout'   => 10
                            );
            $response      = wp_remote_post($url,$args);
            $response_code = wp_remote_retrieve_response_code($response);

            if(isset($data_article)){
                $data_article = json_decode($params);
            }

            if(isset( $data_article->articleData->guid) && isset( $data_article->articleData->project_id)){
                    if(isset($response->errors) && !empty($response->errors)){
                            $article_id = $data_article->articleData->guid;
                            $project_id = $data_article->articleData->project_id;

                            update_post_meta($article_id,'sortd_'.$project_id.'_sync_error_message', 'SORTD Server is not reachable' );
                    } 
            }

            if ( is_array( $response ) && ! is_wp_error( $response ) ) {
                $body = wp_remote_retrieve_body($response);
                $response = $body;
            } else {
                $fail_response =  array();
                $fail_response['status'] = false;
                $fail_response['error']['message'] = $response->get_error_message();
                $fail_response['error']['errorCode'] = 408;
                $response = wp_json_encode($fail_response);
            }




            if($response === false || $response_code === 0 || $response_code === 500 || $response_code ===502 || $response_code === 7)
            {

                    if($response_code === 500){

                            $result = json_decode($response);


                            if(isset($result->error)){

                                    $failed_response = array();
                                    $failed_response['status']= false;
                                    $failed_response['error']['message'] = $result->error->message;
                                    $failed_response['error']['errorCode'] = $result->error->errorCode;

                                    $response = wp_json_encode($failed_response);

                            }
                    } else {

                            $fail_response =  array();
                            $fail_response['status'] = false;
                            $fail_response['error']['message'] = "SORTD Server is not reachable";
                            $fail_response['error']['errorCode'] = 408;
                            $response = wp_json_encode($fail_response);
                    }

            } 
                
            return $response;

	}

        /**
	 * function for curl delete request
	 *
	 * @since    2.0.0
	 */
	public static function curl_delete($url,$headers) {
	
            $args = array('headers' => $headers,
                        'method' 	=> 'DELETE',
                        'timeout'	=> 10
                            );

            $response = wp_remote_request($url, $args);

            $response_code = wp_remote_retrieve_response_code($response);
            if ( is_array( $response ) && ! is_wp_error( $response ) ) {
                $body = wp_remote_retrieve_body($response);
                $response = $body;
            } else {
                    $error_response =  array();
                    $error_response['status'] = $response;
                    $error_response['error']['message'] = $response->get_error_message();
                    $error_response['error']['errorCode'] = 408;
                    $response = wp_json_encode($error_response);
            }


            if($response === false || $response_code === 0 || $response_code === 500 || $response_code ===502 || $response_code === 7)
            {
                    $error_data = json_decode($response);
                    $error_response =  array();
                    $error_response['status'] = false;
                    $error_response['error']['message'] = $error_data->error->message;//"SORTD Server is not reachable";
                    $error_response['error']['errorCode'] = 408;

                    $response = wp_json_encode($error_response);



            } 

            return $response;

	}
        
        /**
	 * function for encrypting string
	 *
	 * @since    2.0.0
	 */

	public static function sortd_encrypt($string) {

            $ciphering = "AES-128-CTR";
            $options = 0;
            $encryption_iv = '7832988438191746';
            $encryption_key = "pNSDIgEuxgWf";	  
            $encrypted_string = openssl_encrypt($string, $ciphering, $encryption_key, $options, $encryption_iv);
            return $encrypted_string;
	}

        /**
	 * function for curl decrypting string
	 *
	 * @since    2.0.0
	 */
        
	public static function sortd_decrypt($encrypted_string) {

            $ciphering = "AES-128-CTR";	 
            $options = 0; 
            $decryption_iv = '7832988438191746';
            $decryption_key = "pNSDIgEuxgWf";
            $decrypted_string = openssl_decrypt($encrypted_string, $ciphering, $decryption_key, $options, $decryption_iv);
            return $decrypted_string;
	}

        /**
	 * function for getting credentials
	 *
	 * @since    2.0.0
	 */
	public static function get_credentials_values(){

            $credentials_values = get_option('sortd_credentials');

            if($credentials_values) {
                    $credentials_values['access_key'] = self::sortd_decrypt($credentials_values['access_key']);
                    $credentials_values['secret_key'] = self::sortd_decrypt($credentials_values['secret_key']);
            }

            return $credentials_values;
	}

        /**
	 * function for getting project slug
	 *
	 * @since    2.0.0
	 */
	public static function get_project_slug(){
            $project_slug = get_option('sortd_project_slug');
            return $project_slug;
	}

        /**
	 * function for getting authentication api url
	 *
	 * @since    2.0.0
	 */
	public static function get_api_base_url(){
            $api_base = SORTD_API_BASE;
            if(SORTD_ENVIRONMENT !== 'PRODUCTION'){
                $api_base = str_replace('mobi','dev',$api_base);
            }
          
            $api_base_url = 'https://'.$api_base.'/v1/';
            return $api_base_url;
	}

        /**
	 * function for getting project specific api url
	 *
	 * @since    2.0.0
	 */
	public static function get_api_url($project_slug){
            $url = self::get_api_base_url().$project_slug.'/';
            return $url;
	}

        /**
	 * function for getting pubconsole url
	 *
	 * @since    2.0.0
	 */
	public static function get_pubconsole_url(){
            $console_base = SORTD_CONSOLE_BASE;
            if(SORTD_ENVIRONMENT !== 'PRODUCTION'){
                $console_base = str_replace('mobi','dev',$console_base);
            }
            $console_url = 'https://'.$console_base;
            return $console_url;
	}
	

        /**
	 * function for getting plugin version
	 *
	 * @since    2.0.0
	 */
	public static function get_plugin_version(){
            $plugin_version = SORTD_VERSION;
            return $plugin_version;
	}

        /**
	 * function for getting plugin version code
	 *
	 * @since    2.0.0
	 */
	public static function get_plugin_version_code(){
            $plugin_version_code = SORTD_VERSION_CODE;
            return $plugin_version_code;
	}


        /**
	 * function for getting headers required for api get calls
	 *
	 * @since    2.0.0
	 */
	public static function get_headers(){

            $credentials_values = self::get_credentials_values();

            $plugin_version = self::get_plugin_version();

            $plugin_version_code = self::get_plugin_version_code();		

            if(!empty($credentials_values)){

                    $headers = array(
                            'Access-Key' 	=> $credentials_values['access_key'],
                            'Secret-Key' 	=> $credentials_values['secret_key'],
                            'domain-name' 	=> site_url(),
                            'wp-version' 	=> get_bloginfo('version'),
                            'plugin-version'=> $plugin_version,
                            'plugin-version-code' => $plugin_version_code
                    );

            } else {

                    $headers = array();
            }

            return $headers;
	}



        /**
	 * function for getting headers required for api post calls
	 *
	 * @since    2.0.0
	 */
	public static function post_headers(){

            $credentials_values = self::get_credentials_values();

            $plugin_version = self::get_plugin_version();

            $plugin_version_code = self::get_plugin_version_code();	

            if(!empty($credentials_values)){


                    $headers = array(
                            'Access-Key' 	=> $credentials_values['access_key'],
                            'Secret-Key' 	=> $credentials_values['secret_key'],
                            'Content-Type'	=> 'application/json',
                            'domain-name' 	=> site_url(),
                            'wp-version' 	=> get_bloginfo('version'),
                            'plugin-version'=> $plugin_version,
                            'plugin-version-code' => $plugin_version_code
                    );

            } else {

                    $headers = array();
            }


            return $headers;

	}


        /**
	 * function for getting headers required for api ajax get calls
	 *
	 * @since    2.0.0
	 */
	public static function get_headers_for_ajax($access_key,$secret_key){

            $plugin_version = self::get_plugin_version();

            $plugin_version_code = self::get_plugin_version_code();


            $headers = array(
                            'Access-Key' 	=> $access_key,
                            'Secret-Key' 	=> $secret_key,
                            'domain-name' 	=> site_url(),
                            'wp-version' 	=> get_bloginfo('version'),
                            'plugin-version'=> $plugin_version,
                            'plugin-version-code' => $plugin_version_code
                    );

            return $headers;

	}


        /**
	 * function for getting headers required for api ajax post calls
	 *
	 * @since    2.0.0
	 */
	public static function post_headers_for_ajax($access_key,$secret_key,$content_type){

            $plugin_version = self::get_plugin_version();

            $plugin_version_code = self::get_plugin_version_code();

            $headers = array(
                            'Access-Key' 	=> $access_key,
                            'Secret-Key' 	=> $secret_key,
                            'Content-Type'	=> $content_type,
                            'domain-name' 	=> site_url(),
                            'wp-version' 	=> get_bloginfo('version'),
                            'plugin-version'=> $plugin_version,
                            'plugin-version-code' => $plugin_version_code
                    );

            return $headers;

	}


        /**
	 * function for updating category values in options
	 *
	 * @since    2.0.0
	 */
	public static function create_options_for_category($project_id, $category_id, $flag, $sortd_id){

            update_option('sortd_'.$project_id.'_category_sync_'.$category_id, $flag);
            update_option('sortd_'.$project_id.'_category_sortd_id_'.$category_id, $sortd_id);
	}
	     /**
	 * function for getting category values from options
	 *
	 * @since    2.0.0
	 */
	public static function get_options_for_category($project_id, $category_id){
            $values = get_option('sortd_'.$project_id.'_category_sync_'.$category_id);
            return $values;
	}

        /**
	 * function for getting category id values from options
	 *
	 * @since    2.0.0
	 */
	public static function get_options_for_categoryid($project_id, $category_id){
            $values = get_option('sortd_'.$project_id.'_category_sortd_id_'.$category_id);
            return $values;
	}


        /**
	 * function for getting project id value from options
	 *
	 * @since    2.0.0
	 */
	public static function get_project_id(){
            $project_id = get_option('sortd_projectid');
            return $project_id;
	}
        
        
        /**
	 * function for updating post sync flag in options
	 *
	 * @since    2.0.0
	 */
	public static function update_post_option_sync_flag($project_id,$post_id,$flag){
            $result = update_post_meta($post_id,'sortd_'.$project_id.'_post_sync', $flag);
            return $result;
	}

        /**
	 * function for updating post article id in options
	 *
	 * @since    2.0.0
	 */
	public static function update_post_option_article_id($project_id,$post_id,$value){
            $result = update_post_meta($post_id,'sortd_'.$project_id.'_post_article_id', $value);
            return $result;
	}

        /**
	 * function for getting post sync status in options
	 *
	 * @since    2.0.0
	 */
	public static function get_options_for_article($project_id,$post_id){
            $post_value = get_post_meta($post_id,'sortd_'.$project_id.'_post_sync');
            return $post_value;
	}
        
        
        /**
	 * function for partials view rendering
	 *
	 * @since    2.0.0
	 */
	public static function render_partials($filenames, $data, $folder = ''){
                if (is_array($data)) {
                        foreach ($data as $key => $value) {
                                ${$key} = $value;
                        }
                }
                    

            $partials_dir = SORTD_PARTIALS_PATH;
            if(empty($folder)){
                include "{$partials_dir}/sortd-header.php";
            }
            
            foreach($filenames as $filename){
                if(!empty($folder)){
                    include "{$partials_dir}/{$folder}/{$filename}.php";
                }else{
                    include "{$partials_dir}/{$filename}.php";
                }
            }
            if(empty($folder)){
                include "{$partials_dir}/sortd-footer.php";
            }
	}
        
        /**
	 *  function for getting plan details from api
	 *
	 * @since    2.0.0
	 */
	public static function get_plan_details() {
            $project_slug = self::get_project_slug();

            $url = self::get_api_url($project_slug).'saas/contract-details';

            $headers = self::get_headers();

            $plan_details = self::curl_get($url,$headers);

            return $plan_details;

	}
        
         /**
	 * function to get project details code
	 *
	 * @since    2.0.0
	 */
	public static function get_project_details() {

        $project_slug =  Sortd_Helper::get_project_slug();

        if(isset( $project_slug) && !empty($project_slug)){

        	$url = Sortd_Helper::get_api_url($project_slug).'project/project-details';
  
            $headers = Sortd_Helper::get_headers();

            $project_details = Sortd_Helper::curl_get($url,$headers);

            update_option('sortd_project_details', $project_details);

            return json_decode($project_details);        
        }

	}
        
        /**
	 *  function for getting sortd api response for get requests
	 *
	 * @since    2.0.0
	 */
	public static function sortd_get_api_response($api_slug,$version='v1') {
            $project_slug = self::get_project_slug();

            $url = self::get_api_url($project_slug).$api_slug;
            if($version === 'v2'){
                $url = str_replace('v1','v2',$url);
            }

         
            $headers = self::get_headers();

            $response = self::curl_get($url,$headers);

            return $response;

	}
        
        /**
	 *  function for getting sortd api response for post requests
	 *
	 * @since    2.0.0
	 */
	public static function sortd_post_api_response($api_slug, $params, $version = 'v1',$custom_headers=array()) {
            
            $credentials = Sortd_Helper::get_credentials_values();
            
            $project_slug = self::get_project_slug();

            $url = self::get_api_url($project_slug).$api_slug;

            if($version === 'v2'){
                $url = str_replace('v1','v2',$url);
            }


            $content_type = 'application/json';

	    $headers = Sortd_Helper::post_headers_for_ajax($credentials['access_key'], $credentials['secret_key'], $content_type);
            
            if(!empty($custom_headers)){
                $headers = array_merge($headers, $custom_headers);
            }

            $response = self::curl_post($url, $headers, $params);

            return $response;

	}

         /**
	 *  function for getting sortd api response for post requests
	 *
	 * @since    2.0.0
	 */
	public static function sortd_delete_api_response($api_slug, $params, $custom_headers=array()) {
            
                $credentials = Sortd_Helper::get_credentials_values();
                
                $project_slug = self::get_project_slug();
    
                $url = self::get_api_url($project_slug).$api_slug;

                $content_type = 'application/json';
    
                $headers = Sortd_Helper::post_headers_for_ajax($credentials['access_key'], $credentials['secret_key'], $content_type);
                
                if(!empty($custom_headers)){
                    $headers = array_merge($headers, $custom_headers);
                }
    
                $response = self::curl_delete($url, $headers, $params);
    
                return $response;
    
            }
        
        /**
	 *  function for  get time format utils
	 *
	 * @since    2.0.0
	 */
        public function get_updated_time($seconds_ago,$date){

            if ($seconds_ago >= 31536000) {
                $ftime =  $date;

            } elseif ($seconds_ago >= 2419200) {

                    $ftime =  $date;

            } elseif ($seconds_ago >= 86400) {

                    $ftime = intval($seconds_ago / 86400) . " days ago";

            } elseif ($seconds_ago >= 3600) {

                    $ftime =   intval($seconds_ago / 3600) . " hr ago";

            } elseif ($seconds_ago >= 60) {

                    $ftime =  intval($seconds_ago / 60) . "  min ago";

            } else {

                    $ftime = "updated recently";

            }

            return $ftime;
        }
        
        /**
	 *  function for  checking article sortd category
	 *
	 * @since    2.0.0
	 */
	public static function check_article_sortd_category($post_id) {

            $project_id = self::get_project_id();
            $category_synced = 0;            

            $post_categories = array();
            $post_data = get_post($post_id);
        	$post_type = $post_data->post_type;
		    $post_taxonomies = get_object_taxonomies($post_type);

		    if (!empty($post_taxonomies)) {
		    	foreach ($post_taxonomies as $post_taxonomy) {

				    // Get the terms related to post.
					$terms = get_the_terms( $post_id, $post_taxonomy );

					if ( ! empty( $terms ) ) {
						foreach ($terms as $term) {
				        	$post_categories[] = $term;
				    	}
					}
		         	
		    	}
		    }

            if(!empty($post_categories)) {
                foreach ($post_categories as $value) {
                    $valcat = Sortd_Helper::get_options_for_category($project_id,$value->term_id);
                    if($valcat === 1 || $valcat === '1' || $valcat === true || $valcat === 'true'){
                        $category_synced =  1;
                        break;
                    }
                }
            }
            return  $category_synced;
	}
        
        
        /**
	 * function for getting sortd categories 
	 *
	 * @since    2.0.0
	 */
	public static function get_sortd_categories() {

	    $project_id = self::get_project_id();

            $categories_api_slug =  "contentsettings/listcategories/".$project_id;
		
            $categories_response = Sortd_Helper::sortd_get_api_response($categories_api_slug);
            $categories = json_decode($categories_response);
              
            return $categories;

	}

	/**
	 * function to get project details code
	 *
	 * @since    2.0.0
	 */
	public static function get_cached_project_details() {

        $project_data = json_decode(get_option('sortd_project_details'));

        if(!empty($project_data->data->valid_till)){
        	
        	$current_unix_time = time(); 
        	if ($current_unix_time <= $project_data->data->valid_till) {
        		return $project_data;
        	}
        }

        
        $project_slug =  Sortd_Helper::get_project_slug();

        if(isset( $project_slug) && !empty($project_slug)){

            $url = Sortd_Helper::get_api_url($project_slug).'project/project-details';

            $headers = Sortd_Helper::get_headers();

            $project_details = Sortd_Helper::curl_get($url,$headers);

            update_option('sortd_project_details', $project_details);

            return json_decode($project_details);        
    	}

	}


	/**
	* function for getting tags values from options
	*
	* @since    2.0.0
	*/
	public static function get_options_for_tag($project_id, $term_id){
            $values = get_option('sortd_'.$project_id.'sync_tag_'.$term_id);
            return $values;
	}

}