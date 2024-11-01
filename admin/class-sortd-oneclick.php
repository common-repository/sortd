<?php

/**
 * The oneclick-specific functionality of the plugin.
 *
 * @link       https://www.sortd.mobi
 * @since      2.0.0
 *
 * @package    Sortd
 * @subpackage Sortd/admin
 */

/**
 * The oneclick-specific functionality of the plugin.
 *
 * @package    Sortd
 * @subpackage Sortd/admin
 * @author     Your Name <email@example.com>
 */
class Sortd_Oneclick {

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

		$this->sortd    = $sortd;
		$this->version  = $version;
        $this->loader   = $loader;

	}
        
        /**
	 * function to define module specific hooks
	 *
	 * @since    2.0.0
	 */
	public function define_hooks() {
            
        $this->loader->add_action( 'admin_enqueue_scripts', $this, 'enqueue_scripts' );
        $this->loader->add_action('wp_ajax_sortd_build_default_config', $this, 'build_default_config');
        $this->loader->add_action('wp_ajax_sortd_sync_relevant_categories', $this, 'sync_relevant_categories');
        $this->loader->add_action('wp_ajax_sortd_sync_relevant_articles', $this, 'sync_relevant_articles');
        $this->loader->add_action('wp_ajax_sortd_preview_mobile_website', $this, 'preview_mobile_website');
        

	}

	/**
	 * function for including scripts
	 *
	 * @since    2.0.0
	 */
        public function enqueue_scripts() {

                wp_enqueue_script('sortd-oneclick', SORTD_JS_URL . '/sortd-oneclick.js', array( 'jquery' ), $this->version, true );
                wp_enqueue_script( 'sweet-alert-message-js', SORTD_JS_URL . '/sweetalert.min.js', array( 'jquery' ), $this->version, true );
                wp_localize_script(
                    'sortd-oneclick',
                    'sortd_ajax_obj_oneclick',
                    array(
                        'ajax_url' => admin_url( 'admin-ajax.php' ),
                        'nonce'    => wp_create_nonce( 'sortd-ajax-nonce-oneclick' ),
                    )
                );
                
	}
        
        /**
	 *  function for one_click_setup
	 *
	 * @since    2.0.0
	 */
	public function one_click_setup() {
           
            $project_details    = Sortd_Helper::get_project_details();

            $credentials        = Sortd_Helper::get_credentials_values();

            if(!$credentials){
                //get started page
                $view_data = array('project_details'=>$project_details);
                Sortd_Helper::render_partials(array('sortd-oneclick-get-started'), $view_data);
                
            }else if($project_details && $project_details->status !== true && $project_details->error->errorCode === 1004){
                // credentials verification page
                $view_data = array('project_details'=>$project_details);
                Sortd_Helper::render_partials(array('sortd-project-verify-credentials','sortd-project-details'), $view_data);
               
            }else if($project_details && $project_details->status !== true && $project_details->error->errorCode === 1010){
                //version support page
                Sortd_Helper::render_partials(array('sortd-version-support'), array('project_details'=>$project_details));
            
            } else {

                //call new API to get No. of Categories to sync, No. of Articles, Last 30/15/10 days 	
               
                $api_slug       = 'project/onboard-quota';

                $quota_response = Sortd_Helper::sortd_get_api_response($api_slug);
                
                $response       = json_decode($quota_response,TRUE);

                //set defaults
                $article_quota  = 100;
                $category_quota = 10;
                $date_till      = 1628417169;

                if($response['status'] === "true" || $response['status'] === 1 || $response['status'] === true || $response['status'] === '1'){
                    $quota          = $response['data'];
                    $article_quota  = $quota['onboard_article_quota'];
                    $category_quota = $quota['onboard_category_quota'];
                    $date_till      = $quota['date_till'];
                }
                
                $view_data = array();
                $view_data['project']       = $project_details;
                $view_data['article_quota'] = $article_quota;
                $view_data['category_quota']= $category_quota;
                $view_data['date_till']     = $date_till;
                Sortd_Helper::render_partials(array('sortd-oneclick-setup'), $view_data);

            }
           

	}
        
        /**
	 *  function to auto build config
	 *
	 * @since    2.0.0
	 */
	public function build_default_config() {
            
            if(!check_ajax_referer('sortd-ajax-nonce-oneclick', 'sortd_nonce')) {
                    $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
                    echo wp_kses_data($result); wp_die();
            }

	   	if(isset($_POST['site_title'])){
            $site_title = sanitize_text_field($_POST['site_title']);
        }


	   	if(isset($_POST['site_description'])){
            $site_description = sanitize_textarea_field($_POST['site_description']);
        }
      
	  

	    if ( has_custom_logo() ) {
	       $logo_image_url    = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' )[0];
            } else {
                if(function_exists( 'tie_logo_args' ) ) {
                    $logo_args          = tie_logo_args();
                    $logo_image_url     = $logo_args['logo_img'];
                } else if (class_exists('td_util')){
                    if(method_exists('td_util','get_option')) {
                       $logo_image_url  = td_util::get_option('tds_logo_upload');
                    }
                }
            }

            $favicon = get_site_icon_url();
		
            $image_upload_slug = 'config/upload-image';
            $uploaded_logo_url = '';
            $logo_encoded_data = '';

            if( isset($logo_image_url) && !empty($logo_image_url)){

                $logo_data = file_get_contents($logo_image_url);
                if ($logo_data !== false){
                    $logo_encoded_data = base64_encode($logo_data);
                }	

                $logo_data_array = array();
		        $logo_data_array['imageData'] = $logo_encoded_data;

		        $logo_params = wp_json_encode($logo_data_array);

                $upload_response_logo = Sortd_Helper::sortd_post_api_response($image_upload_slug, $logo_params);

                $logo_response = json_decode($upload_response_logo);

                if(isset($logo_response->data->imageUrl) && !empty($logo_response->data->imageUrl)){
                    $uploaded_logo_url = $logo_response->data->imageUrl;
                } 
            }

            if(!empty($favicon)){

                $favicon_image_data = file_get_contents($favicon);
                $favicon_array = array();
                if ($favicon_image_data !== false){
                    $favicon_image_encoded_data = base64_encode($favicon_image_data);
                    $favicon_array['imageData'] = $favicon_image_encoded_data;
                } else {
                    $favicon_array['imageData'] = '';
                }

                
                

                $favicon_params = (wp_json_encode($favicon_array));
                
                $upload_response_favicon = Sortd_Helper::sortd_post_api_response($image_upload_slug, $favicon_params);
               
                $favicon_response = json_decode($upload_response_favicon);
                if(isset($favicon_response->data->imageUrl)){
                    $uploaded_favicon_url = $favicon_response->data->imageUrl;
                }
                else{
                    $uploaded_favicon_url="";
                }
            } else {
                $uploaded_favicon_url = "";
            }


            $canonical_url = get_home_url();

            $project_title = $site_title;

            $project_meta = $site_description;

            $general_config = $logo_config = array();
            $general_config['groupName'] = 'general_settings';
            $general_config['formData']['project_meta']['title']                    =  $project_title;
            $general_config['formData']['project_meta']['desc']                     =  $project_meta;
            $general_config['formData']['project_meta']['keywords']                 =  "";
            $general_config['formData']['project_meta']['canonical_url']            =  $canonical_url;
            $general_config['formData']['data_settings']['analytics_id']            =  "";
            $general_config['formData']['data_settings']['google_site_verification']=  "";
            $general_config['formData']['design']['font']                           =  "Mukta";
            $general_config['formData']['design']['notification']                   =  true;
            $general_config['formData']['design']['app_name']                       =  "";
            $general_config['formData']['design']['favicon']                        =  $uploaded_favicon_url;
            $general_config['formData']['design']['appicon']                        =  "";
            $general_config['formData']['design']['add_to_homescreen']              =  true;
            $general_config['formData']['design']['add_to_homescreen_bg']           =  "#0005bf";
            $general_config['formData']['design']['add_to_homescreen_color']        =  "#ffffff";
            $general_config['formData']['design']['scroll_to_top']                  =  true;
            $general_config['formData']['design']['scroll_to_top_bg']               =  "#0005bf";
            $general_config['formData']['design']['scroll_to_top_color']            =  "#ffffff";
            $general_config['formData']['design']['theme_color']                    =  "#005bf0";
            $general_config['formData']['design']['amp_600_logo']                   =  "";
            $general_config['formData']['social']['fb_appid']                       =  "";
            $general_config['formData']['social']['facebook_url']                   =  "";
            $general_config['formData']['social']['twitter_handle']                 =  "";
            $general_config['formData']['social']['twitter_url']                    =  "";
            $general_config['formData']['social']['youtube_url']                    =  "";
            $general_config['formData']['social']['pinterest_url']                  =  "";
            $general_config['formData']['app_links']['android_app_url']             =  "";
            $general_config['formData']['app_links']['ios_app_url']                 =  "";

            $logo_config['groupName']                                               = 'header';
            $logo_config['formData']['header_branding']['header_background']        =  "#ffffff";
            $logo_config['formData']['header_branding']['brand_logo']               =  $uploaded_logo_url;
            $logo_config['formData']['header_branding']['hamburger_color']          =  "#000000";
            $logo_config['formData']['header_branding']['custom_css']               =  "";
            $logo_config['formData']['header_branding']['custom_css_amp']           =  "";
            $logo_config['formData']['components']['header_template']['name']       =  "headerLayout2";
            $logo_config['formData']['components']['header_template']['position']   =  "relative";
            $logo_config['formData']['components']['header_template']['external_icon1']  =  "";
            $logo_config['formData']['components']['header_template']['external_link1']  =  "";
            $logo_config['formData']['components']['header_template']['external_icon2']  =  "";
            $logo_config['formData']['components']['header_template']['external_link2']  =  "";
            $logo_config['formData']['components']['sideMenu']['name']              =  "sideMenu";
            $logo_config['formData']['components']['sideMenu']['search']            =  true;
            $logo_config['formData']['components']['sideMenu']['social_icons']      =  true;
            $logo_config['formData']['components']['sideMenu']['sidebar_background']=  "#ba0406";
            $logo_config['formData']['components']['sideMenu']['sidebar_color']     =  "#ffffff";
            $logo_config['formData']['components']['sideMenu']['custom_css']        =  "";


            $params_general_config = (wp_json_encode($general_config));
        
            $params_logo_config = (wp_json_encode($logo_config));
		   		
            $config_api_slug = "config/storeconfig";
            
            Sortd_Helper::sortd_post_api_response($config_api_slug, $params_general_config);

            if(isset($uploaded_logo_url)){
                Sortd_Helper::sortd_post_api_response($config_api_slug, $params_logo_config);	
            }

            $params = '{
                             "site_title" : "'.$site_title.'",
                             "site_description" : "'.$site_description.'"
                                  }';

            $build_api_slug = 'project/build-pwa';

	    $response = json_decode(Sortd_Helper::sortd_post_api_response($build_api_slug,$params));

	    $project_id = get_option('sortd_projectid');

            update_option('sortd_oneclick_flag'.$project_id,1);
            
	    echo (wp_json_encode($response));
            
            wp_die();
	}
        
        /**
	 * function to  sync categories
	 *
	 * @since    2.0.0
	 */
	public function sync_relevant_categories() {
            
        if(!check_ajax_referer('sortd-ajax-nonce-oneclick', 'sortd_nonce')) {
            $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
            echo wp_kses_data($result); wp_die();
        }

        if(isset($_POST['category_quota'])){
            $category_quota = sanitize_text_field($_POST['category_quota']);
        }

        if(isset($_POST['article_quota'])){
            $article_quota = sanitize_text_field($_POST['article_quota']);
        }

        $cat_array = array();

	    $credentials = Sortd_Helper::get_credentials_values();
            $project_id = Sortd_Helper::get_project_id('sortd_projectid');
            $content_type = 'application/json';

            if(!empty($credentials)){
                Sortd_Helper::post_headers_for_ajax($credentials['access_key'],$credentials['secret_key'],$content_type);
            } else {
                Sortd_Helper::post_headers_for_ajax("","",$content_type);
            }

            // Fetch all posts max by article_quota
            $args = array(
                    'post_type'    => 'post',
                    'orderby'      => 'date',
                    'order'        => 'DESC',
                    'numberposts'  => $article_quota,
                    'post_status'  => 'publish' ,
                    'suppress_filters' => false      
            );

            $posts = get_posts($args);
            $total_posts = count($posts);
           
            //then iterate over them and find unique category ids
            foreach($posts as $post_object){
                    $categories = get_the_category($post_object->ID);
                    if(isset($categories) && !empty($categories)) {
                        foreach ($categories as $category) {
                            $cat_array[] = $category;
                        }	
                        
                    }		
            }
            
            //remove-duplicate-values-from-a-multi-dimensional-array-in-php
            $cat_array = array_map("unserialize", array_unique(array_map("serialize", $cat_array)));
       
            // limit all these categories by category_quota
            $cat_count = count($cat_array);

            if($cat_count <= $category_quota){
                    $all_categories = $cat_array;
            } else {
                    $all_categories = array_slice($cat_array,0,$category_quota);
            }
	
            $count = 0;
            $flag = 'false';
          
            foreach($all_categories as $ck => $cv){
                $cv->ancestors_size = sizeof(get_ancestors( $cv->cat_ID, 'category' )); 
                if( $cv->ancestors_size !== 0){
                    $cv->ancestors = (get_ancestors( $cv->cat_ID, 'category' ));
                   
                }                
            }
           
           
            $cat_to_sync = array();

            foreach($all_categories as $ck){
                
                if( $ck->ancestors_size !== 0){
                  
                    for($i=$ck->ancestors_size - 1; $i >=0 ;$i--){
                        if(!in_array($ck->ancestors[$i],$cat_to_sync,true)){
                            array_push($cat_to_sync,$ck->ancestors[$i]);
                        }  
                    }
                } 
                if(!in_array($ck->cat_ID,$cat_to_sync, true)){
                    array_push($cat_to_sync,$ck->cat_ID);
                }
                
            }

            if (!empty($cat_to_sync)) {
                $sortd_category = new Sortd_Categories($this->sortd, $this->version, $this->loader);
                $taxonomy_name = 'Categories';
                $taxonomy_slug = 'category';
                $post_slug = 'post';
                $post_name = 'Posts';
                $response =  $sortd_category->sync_taxonomy($taxonomy_name,$taxonomy_slug,$post_slug,$post_name);
                if ($response->status !== true) {
                    echo (wp_json_encode($response));
                    wp_die();
                }                
            }

            foreach ($cat_to_sync as $all_values) {
                $cat = get_category($all_values);
                $cat_id = $all_values;
               
                $parent = $cat->parent;
                $cat_url = get_category_link($cat_id);
                $category_desc = wp_strip_all_tags(trim(category_description($cat_id)));

                if(is_plugin_active('wordpress-seo/wp-seo.php')) {
                    $yoast_data = get_option( 'wpseo_taxonomy_meta');
                    if(isset($yoast_data['category']) && !empty($yoast_data['category'])) {
                        foreach($yoast_data['category'] as $cid => $data) {
                            if($cid === $cat_id) {
                                if(isset($data['wpseo_title'])) {
                                    $cat_title = $data['wpseo_title'];
                                }
                            }
                        }
                    } else {

                          $cat_title = $cat->name;
                    }
                } else {
                    $cat_title = $cat->name;
                }

                $params = '{
                    "cat_guid" : "'.$cat_id.'",
                    "name" : "'.$cat->name.'",
                    "alias" : "'.urldecode($cat->slug).'",
                    "parent_guid" : '.$parent.',
                    "after_cat_id": "",
                    "cat_desc" : '.wp_json_encode($category_desc).',
                    "cat_url" : "'.$cat_url.'",
                    "cat_title" : "'.$cat_title.'",
                    "taxonomy_type_slug" :"category"
                }';
                $cat_sync_api_slug = "contentsettings/categorysync";

               $cat_response = Sortd_Helper::sortd_post_api_response($cat_sync_api_slug,$params,'v2');
               $response = json_decode($cat_response);
                if($response->status === true){
                        Sortd_Helper::create_options_for_category($project_id,$cat_id,1,$response->data->cat_id);	

                        $flag = 'true';
                        $count++;	
                } else {
                        $flag = 'false';								
                }

            } // end foreach	

            update_option('sortd_catsynconeclick_'.$project_id,$count);	
            $result = array('count'=>$count,'response'=>$response,'flag'=>$flag,'total_posts'=>$total_posts,"decode_response"=>"");
            echo (wp_json_encode($result));
            wp_die();
	}
        
        /**
	 * function to sync articles
	 *
	 * @since    2.0.0
	 */
	public function sync_relevant_articles() {
            if(!check_ajax_referer('sortd-ajax-nonce-oneclick', 'sortd_nonce')) {
                    $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
                    echo wp_kses_data($result); wp_die();
            }
            $project_id = Sortd_Helper::get_project_id();

            if(isset($_POST['post_count'])){
               
                $post_count = sanitize_text_field($_POST['post_count']);
            }

          
            
            $count =0;	
            $flag = 'false';

            if(isset($_POST['page'])){
                $page = sanitize_text_field($_POST['page']);
            }

           

            $post_per_page = $post_count;
            $post_offset = $page * $post_per_page;

            $args = array(
                'post_type'    => 'post',
                'orderby'      => 'date',
                'order'        => 'DESC',
                'numberposts'  => $post_count,
                'offset'       => $post_offset,
                'post_status'  => 'publish',
                'suppress_filters' => false
            );

            $cat_posts = get_posts($args);

            if(! empty( $cat_posts ) ){		   		
                 foreach ($cat_posts as $post) {
                    $author_id = get_post_field( 'post_author', $post->ID );
                    $sortd_article = new Sortd_Article($this->sortd, $this->version, $this->loader);
                    $response_author = $sortd_article->sync_author_data($author_id);
                    $decode_response_author = json_decode($response_author);
                    if($decode_response_author === true){
                        update_post_meta($post->ID,'sortd_'.$project_id.'_sync_author_',$decode_response_author->data->_id);
                    }
                     $response = Sortd_Article::sync_article($post->ID,$post);
                     if($response->status === true){
                             $count++;
                             $flag = 'true';
                     }
                }
            }				

         
            update_option('sortd_one_click_setup_'.$project_id,1);
            update_option('sortd_one_click_manual_sync'.$project_id,0);


            $json_array = array('count'=>$count,'response'=>$response,'flag'=>$flag);

            echo (wp_json_encode($json_array));

            wp_die();
		
	}
        
        /**
	 *  function to preview site
	 *
	 * @since    2.0.0
	 */
	public function preview_mobile_website() {
            
            if(!check_ajax_referer('sortd-ajax-nonce-oneclick', 'sortd_nonce')) {
                $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
                echo wp_kses_data($result); wp_die();
            }
            
	    $project_id = Sortd_Helper::get_project_id();
            update_option('sortd_'.$project_id.'_demo_preview',0);
            wp_die();
	}

        /**
	 *  function to preview site
	 *
	 * @since    2.2.2
     */

     public function oneclick_page_insights(){
        $project_details = Sortd_Helper::get_project_details();
        
        $view_data = array();
        $view_data['project'] = $project_details;

        $api_slug = 'project/actions';

		$cname_api_response = Sortd_Helper::sortd_get_api_response($api_slug);

        $response =  json_decode($cname_api_response);
        $view_data['contact_us'] = $response; 
 

        Sortd_Helper::render_partials(array('sortd-page-insights'), $view_data);
     }
        
       

}
