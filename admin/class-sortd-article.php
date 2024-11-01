<?php

include_once(ABSPATH . 'wp-admin/includes/plugin.php');

/**
 * The article-specific functionality of the plugin.
 *
 * @link       https://www.sortd.mobi
 * @since      2.0.0
 *
 * @package    Sortd
 * @subpackage Sortd/admin
 */

/**
 * The article-specific functionality of the plugin.
 *
 * @package    Sortd
 * @subpackage Sortd/admin
 * @author     Your Name <email@example.com>
 */
class Sortd_Article {

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
        $this->loader->add_action('wp_ajax_sortd_ajax_manual_sync', $this, 'manual_sync_article');
        $this->loader->add_action('wp_ajax_unsync_article', $this, 'unsync_article');
        $this->loader->add_action('wp_ajax_sync_articles_in_bulk', $this, 'bulk_sync_articles');
        $this->loader->add_action('wp_ajax_sortd_update_bulk_count', $this, 'update_bulk_count');
        $this->loader->add_action('wp_ajax_sortd_update_bulk_flag', $this, 'update_bulk_flag');
        $this->loader->add_action('wp_ajax_unsync_articles_in_bulk', $this, 'unsync_articles_in_bulk');
        $this->loader->add_action('wp_ajax_update_bulk_unsync_count', $this, 'update_bulk_unsync_count');
        $this->loader->add_action('wp_ajax_sortd_update_bulk_unsync_flag', $this, 'sortd_update_bulk_unsync_flag');
        $this->loader->add_action('wp_ajax_sync_webstory',$this,'manual_sync_webstory');
        $this->loader->add_action('wp_ajax_unsync_webstory',$this,'manual_unsync_webstory');
        $this->loader->add_action('wp_ajax_bulk_sync_webstories', $this, 'bulk_sync_webstories');
        $this->loader->add_action('wp_ajax_update_bulk_count_webstory', $this, 'update_bulk_count_webstory');
        $this->loader->add_action('wp_ajax_update_bulk_flag_webstory', $this, 'update_bulk_flag_webstory');
        $this->loader->add_action('wp_ajax_bulk_unsync_webstories', $this, 'bulk_unsync_webstories');
        $this->loader->add_action('wp_ajax_update_bulk_count_webstory_unsync', $this, 'update_bulk_count_webstory_unsync');
        $this->loader->add_action('wp_ajax_update_bulk_flag_webstory_unsync', $this, 'update_bulk_flag_webstory_unsync');
        $this->loader->add_action('wp_ajax_get_data_article', $this, 'get_data_article');
        $this->loader->add_action('wp_ajax_rate_later', $this, 'rate_later');
        $this->loader->add_action('wp_ajax_sortd_sync_tag',$this,'sync_tag_ajax');
        $this->loader->add_action('wp_ajax_show_not_again', $this, 'show_not_again');
        $this->loader->add_action('wp_ajax_sortd_unsync_tag',$this,'unsync_tag_ajax');
        $this->loader->add_action('wp_ajax_list_tags',$this,'get_list_ajax_tags');

         $this->loader->add_action('wp_ajax_get_data_webstory', $this, 'get_data_webstory'); 

     
        $this->loader->add_action('wp_ajax_filter_article_array', $this, 'filter_article_array');
    }

        /**
	 * function to enqueue script file
	 *
	 * @since    2.0.0
	 */

    public function enqueue_scripts(){
        wp_enqueue_script('sortd-articles', SORTD_JS_URL . '/sortd-article.js', array( 'jquery' ), $this->version, true );
        wp_enqueue_script( 'sweet-alert-message-js', SORTD_JS_URL . '/sweetalert.min.js', array( 'jquery' ), $this->version, true );
        wp_localize_script(
			'sortd-articles',
			'sortd_ajax_obj_article',
			array(
				'ajax_url' => admin_url( 'admin-ajax.php' ),
				'nonce'    => wp_create_nonce( 'sortd-ajax-nonce-article' ),
			)
		);
    }

	/**
	 *  function triggered on delete wppost and sortd post delete work
	 *
	 * @since    2.0.0
	 */
	public function trash_post_function($post) {

        $post_id = $post->ID;
	
        $project_id = Sortd_Helper::get_project_id();
	   	
	   	$val = get_post_meta($post_id, 'sortd_'.$project_id.'_post_article_id',true);

		if(!empty($val)){

            $article_api_slug = "article/delete/".$post_id;
      
            $params = '';
            $article_response = Sortd_Helper::sortd_delete_api_response($article_api_slug, $params);
            $response_array = json_decode($article_response);

			if($response_array->status === true){
		      
				delete_post_meta($post_id,'sortd_'.$project_id.'_post_sync');
				delete_post_meta($post_id,'sortd_'.$project_id.'_post_article_id');
            }
		
        }
		

	}
        


          /**
	 *  function for getting article object
	 *
	 * @since    2.0.0
	 */

    public static function getInstance() {
        // Return the instance
        return new self('','','');
    }
   
    /**
	 *  function for sync article
	 *
	 * @since    2.0.0
	 */
	public static function sync_article($post_id, $post,$review_data=array()) {
       
            $project_id = Sortd_Helper::get_project_id();
            
            try{	                                                 
                        $is_sortd_post = false;

                        $post_categories = array();
                        $post_type = $post->post_type;
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
			                        $is_sortd_post = true;
			                        break;
			                    }
			                }
			            }

                        
                        if($is_sortd_post) {
                         
                            $article_object = self::get_article_object_for_sortd($post_id,$post_categories,$review_data);
                            $params =  (wp_json_encode($article_object, JSON_UNESCAPED_SLASHES));

                            $article_api_slug = 'article/create';
                            $article_response = Sortd_Helper::sortd_post_api_response($article_api_slug, $params);
                            $response = json_decode($article_response);
                         
                            if($response->status === true || $response->status === 1){
                                
                                $article_id = ($response->data->article_id);
                                Sortd_Helper::update_post_option_sync_flag($project_id,$post_id,1);
                                Sortd_Helper::update_post_option_article_id($project_id,$post_id,$article_id);
                                update_post_meta($post_id,'sortd_'.$project_id.'_sync_error_message', '' );
                                return $response;
                            } else if($response->status === false || $response->status !== 1 ||  $response->status === "false"){
                                $error = $response->error->message;
                                if($response->error->errorCode === 503) {
                                    update_option('sortd_'.$project_id.'_maintenance_message_sync', $response->error->message);
                                } else {
                                    delete_option('sortd_'.$project_id.'_maintenance_message_sync');
                                }
                                if(!($response->error->errorCode === 503)) {
                                    Sortd_Helper::update_post_option_sync_flag($project_id,$post_id,3);
                                    update_post_meta($post_id,'sortd_'.$project_id.'_sync_error_message', $error );
                                }
                                
                                return $response;
                            }	
                        }else {
                            
                            $response = new stdClass();
                            $response->status = "false";
                            $response->error = new stdClass();
                            $response->error->errorCode = 2001;
                            $response->error->message = "Post category was not synced. Please try again.";
                            return $response;
                        }
	

            } catch (Exception $e){

                    $error =  $e->getMessage();
                    return false;
            }                      
		
	}
        
    
        
        /**
	 *  function for manual sync
	 *
	 * @since    2.0.0
	 */
	public function manual_sync_article() {

        if(!check_ajax_referer('sortd-ajax-nonce-article', 'sortd_nonce')) {
            $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
               echo wp_kses_data($result); wp_die();
        }
        $project_id = Sortd_Helper::get_project_id();

        if(isset($_POST['post_id'])){
            $post_id = sanitize_text_field($_POST['post_id']);
        } 

        $post = get_post($post_id);
		$response = self::sync_article($post_id,$post);

        if($response -> status === true) {
            if(is_plugin_active('sortd_post_reorder/sortd-post-order.php')){
                include_once WP_PLUGIN_DIR.'/sortd_post_reorder/sortd-post-order.php';
                if(class_exists('SCPO_Engine')){
                    $scpobj=new SCPO_Engine();
                }
                if(method_exists($scpobj,'send_posts_menu_order')){
                    $scpobj->send_posts_menu_order();
                }
            }
        }

        $project_details = Sortd_Helper::get_cached_project_details();
        if($project_details->data->paidarticle_enabled === true){
            $paid_article_price = get_post_meta($post_id, "sortd-paid-price".$project_id,true);
        }
       
        if(!isset($paid_article_price) || !$paid_article_price){
            $price = '';
        } else {
            $price = $paid_article_price;
        }
       $response->paid_value =$price;
        $response->is_paid = $project_details->data->paidarticle_enabled;
      
        echo wp_json_encode($response);

       
        wp_die();

	}
        
        /**
	 *  function for unsync article
	 *
	 * @since    2.0.0
	 */
	public function unsync_article() {
        if(!check_ajax_referer('sortd-ajax-nonce-article', 'sortd_nonce')) {
            $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
               echo wp_kses_data($result); wp_die();
        }
        $response = self::unsync_article_response();

        return $response;
       
	}

	public static function unsync_article_response($post_id=''){
        if(SORTD_ADMIN::nonce_check()) {

         $project_id = Sortd_Helper::get_project_id(); 
            if(isset($_POST['guid']) && empty($post_id)){
                $nonce = isset($_REQUEST['_wpnonce']) ? sanitize_text_field($_REQUEST['_wpnonce']) :"";
		        if( $nonce && wp_verify_nonce( $nonce, SORTD_NONCE ) )
                {
                    $guid = (sanitize_text_field($_POST['guid']));
                }
            } else {
                $guid = $post_id;
            }
            
            $article_api_slug = "article/delete/".$guid;
        
            $params = '';
            $article_response = Sortd_Helper::sortd_delete_api_response($article_api_slug, $params);
            $response_array = json_decode($article_response);
        
            if($response_array->status === true){
            
                Sortd_Helper::update_post_option_sync_flag($project_id,$guid,0);
                Sortd_Helper::update_post_option_article_id($project_id,$guid,'');

                update_post_meta($guid,'sortd_'.$project_id.'_sync_error_message','' );

            } elseif($response_array->status === false) {
                if(isset($response_array->error) && isset($response_array->error->errorCode) && $response_array->error->errorCode === 503) {
                    
                    update_option('sortd_'.$project_id.'_maintenance_message_unsync', $response_array->error->message);
                } else {
                    delete_option('sortd_'.$project_id.'_maintenance_message_unsync');
                }
            }

            echo wp_json_encode($response_array);
            wp_die();
        }

	}
        
    
        
        /**
	 *  function for filter content
	 *
	 * @since    2.0.0
	 */
	public static function filter_content($content) {

		includes_url('class-wp-embed.php');

		$obj_embed = new WP_Embed();

		$content = $obj_embed->run_shortcode($content);
		$content = $obj_embed->autoembed($content);

		if(function_exists('do_blocks')){
			$content = do_blocks($content);
		}
        
		if(function_exists('capital_P_dangit')){
			$content = capital_P_dangit($content);
		}

		if(function_exists('convert_smilies')){
			$content = convert_smilies($content);
		}

		if(function_exists('wpautop')){
			$content = wpautop($content);
		}

		if(function_exists('shortcode_unautop')){
			$content = shortcode_unautop($content );
		}

		if(function_exists('prepend_attachment')){
			$content = prepend_attachment($content );
		}

		if(function_exists('wp_filter_content_tags')){
			$content = wp_filter_content_tags($content );
		}

		if(function_exists('wp_replace_insecure_home_url')){
			$content = wp_replace_insecure_home_url($content );
		}

		if(function_exists('do_shortcode')){
			$content = do_shortcode($content );
		}

		if(has_filter('as3cf_filter_post_local_to_provider')){
			$content = apply_filters('as3cf_filter_post_local_to_provider', $content);
		}

		return $content;

	}
        
            
   
        /**
	 *  function for bulk unsync articles
	 *
	 * @since    2.0.0
	 */

	public function unsync_articles_in_bulk() {
        if(!check_ajax_referer('sortd-ajax-nonce-article', 'sortd_nonce')) {
            $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
               echo wp_kses_data($result); wp_die();
        }
        $post_id = array();

        
        if(isset($_POST['postids'])){
            $post_id = sanitize_text_field($_POST['postids']);
        }
   
        $project_id = Sortd_Helper::get_project_id();

        $response = new stdClass();
        $response->status = false;

            if(! empty( $post_id ) ){		   		
        
                    $post = get_post($post_id);

                    $sync_flag = get_post_meta($post_id,'sortd_'.$project_id.'_post_article_id',true);
                 
                     if(isset($sync_flag) && !empty($sync_flag)){
                        
                        if ($post->post_status === 'publish') { // check for bulk sync only published posts
                        
                            $response = self::unsync_article_response($post_id);

                            update_post_meta($post_id,'bulk_unsync_'.$project_id,2);
                            echo wp_json_encode($response);	
                            
                        }
                    }   else {
                        $response = new stdClass();
                        $response->status = false;
                        echo wp_json_encode($response);	
                    }
                    
                                
            }		

        wp_die();

	}
        /**
	 *  function for bulk sync articles
	 *
	 * @since    2.0.0
	 */
	public function bulk_sync_articles() {
        if(!check_ajax_referer('sortd-ajax-nonce-article', 'sortd_nonce')) {
            $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
               echo wp_kses_data($result); wp_die();
        }
        $post_id = array();

        
        if(isset($_POST['postids'])){
            $post_id = sanitize_text_field($_POST['postids']);
        }

    
        $project_id = Sortd_Helper::get_project_id();

        $response = new stdClass();
        $response->status = false;

            if(! empty( $post_id ) ){		   		
        
                    $post = get_post($post_id);

                    if ($post->post_status === 'publish') { // check for bulk sync only published posts

                        $response = self::sync_article($post_id,$post);

                        update_post_meta($post_id,'bulk_sync_'.$project_id,2);	
                    }
           
            }
            
        if(is_plugin_active('sortd_post_reorder/sortd-post-order.php')){
            include_once WP_PLUGIN_DIR.'/sortd_post_reorder/sortd-post-order.php';
            if(class_exists('SCPO_Engine')){
                $scpobj=new SCPO_Engine();
            }
            if(method_exists($scpobj,'send_posts_menu_order')){
                $scpobj->send_posts_menu_order();
            }
        }
       
        echo wp_json_encode($response);			

        wp_die();

	}

    public function filter_article_array(){

        if(!check_ajax_referer('sortd-ajax-nonce-article', 'sortd_nonce')) {
            $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
               echo wp_kses_data($result); wp_die();
        }

        if(isset($_POST['articles_to_sync']) && !empty($_POST['articles_to_sync'])) {
            $article_id_array = array_map( 'sanitize_text_field', $_POST['articles_to_sync'] );
            $project_id = Sortd_Helper::get_project_id();
            $unsynced_articles=array();
            foreach($article_id_array as $id ){
                $synced_flag =  get_post_meta( $id,'sortd_'.$project_id.'_post_sync', true);
                $post_status = get_post_status($id);
                if($synced_flag!=='1' && $post_status === 'publish'){
                    array_push($unsynced_articles,$id);
                }
            }
            echo wp_json_encode($unsynced_articles);
            wp_die(); 
        }
    }
        
        /**
	 *  function for update bulk count
	 *
	 * @since    2.0.0
	 */
	public function update_bulk_count() {
        if(!check_ajax_referer('sortd-ajax-nonce-article', 'sortd_nonce')) {
            $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
               echo wp_kses_data($result); wp_die();
        }

        $post_count = 0;
        if(isset($_POST['post_count_1'])){
            $post_count = sanitize_text_field($_POST['post_count_1']);
        }
     
        
        echo wp_kses_data($post_count);

        wp_die();
        
	}

        /**
	 *  function for update bulk unsync count
	 *
	 * @since    2.0.0
	 */
	public function update_bulk_unsync_count() {
        if(!check_ajax_referer('sortd-ajax-nonce-article', 'sortd_nonce')) {
            $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
               echo wp_kses($result); wp_die();
        }
        
        $post_count = 0;
        if(isset($_POST['post_count_unsync'])){
            $post_count = sanitize_text_field($_POST['post_count_unsync']);
        }


        echo wp_kses_data ($post_count);

        wp_die();

	}
        
         /**
	 *  function for update bulk flag
	 *
	 * @since    2.0.0
	 */
	public function update_bulk_flag() {
        if(!check_ajax_referer('sortd-ajax-nonce-article', 'sortd_nonce')) {
            $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
               echo wp_kses_data($result); wp_die();
        }
        $project_id = Sortd_Helper::get_project_id();
        $post_count = 0;
        if(isset($_POST['post_count_1'])){
            $post_count = sanitize_text_field($_POST['post_count_1']);
        }
        
        update_option('bulk_sync_article_count'.$project_id,$post_count);
		update_option('bulk_action_'.$project_id,1);

		wp_die();
		

	}

         /**
	 *  function for update bulk unsync  flag
	 *
	 * @since    2.0.0
	 */
	public function sortd_update_bulk_unsync_flag() {
        if(!check_ajax_referer('sortd-ajax-nonce-article', 'sortd_nonce')) {
            $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
               echo wp_kses_data($result); wp_die();
        }
        $project_id = Sortd_Helper::get_project_id();
        $post_count = 0;
        if(isset($_POST['post_count_unsync'])){
            $post_count = sanitize_text_field($_POST['post_count_unsync']);
        }
     
        update_option('bulk_sync_article_unsync_count'.$project_id,$post_count);
		update_option('bulk_action_unsync_'.$project_id,1);

		wp_die();
		

	}
        
        /**
	 *  function for getting article object to sync with sortd
	 *
	 * @since    2.0.0
	 */
        public static function get_article_object_for_sortd($post_id,$post_categories,$review_data){
            $project_id = Sortd_Helper::get_project_id();
            $post_data = get_post($post_id);
            $post_categories_data = array();                      
            $article_details = array();
                            
            foreach ($post_categories as $key => $wp_category_details) {
                $post_categories_data[$key]['category_id'] = $wp_category_details->term_id;
                $post_categories_data[$key]['category_name'] = $wp_category_details->name;
                $post_categories_data[$key]['category_slug'] = $wp_category_details->slug;
            }


            $article_details['guid'] = $post_id;
            if(is_plugin_active('wordpress-seo/wp-seo.php')) {
				$primary_cat_id = get_post_meta($post_id, '_yoast_wpseo_primary_category', true);
                if(isset($primary_cat_id) && !empty($primary_cat_id)) {
                    $cat = get_category($primary_cat_id);
                    $primary_cat_slug = $cat->slug;
                    $article_details['primary_cat_id'] = $primary_cat_id;
                    $article_details['primary_cat_slug'] = $primary_cat_slug;
                } else {
                    $cat_array = get_the_category($post_id);
                    $primary_cat_id = $cat_array[0]->term_id;
                    $primary_cat_slug = $cat_array[0]->slug;
                    $article_details['primary_cat_id'] = $primary_cat_id;
                    $article_details['primary_cat_slug'] = $primary_cat_slug;
                } 
            } else {
                $cat_array = get_the_category($post_id);
                $primary_cat_id = $cat_array[0]->term_id;
                $primary_cat_slug = $cat_array[0]->slug;
                $article_details['primary_cat_id'] = $primary_cat_id;
                $article_details['primary_cat_slug'] = $primary_cat_slug;
            }
            $author_data = array();
            $user = get_userdata($post_data->post_author);
            $author_data['author_id'] = $post_data->post_author;
            
            $author_data['name'] = $user->display_name;
            
       
            $tags = get_the_tags($post_id);
            $tag_data = array();
            if(isset($tags) && !empty($tags)){
                foreach ($tags as $key => $value) {
                    $tag_data[] = $value->name;
                }
            }					
            $args = array( 
                'post_type' => 'attachment', 
                'numberposts' => -1, 
                'post_status' => null, 
                'post_parent' => $post_id ,
                'suppress_filters' => false
            ); 
         
            $featured_image_data = self::_get_images_for_post($post_id);
         
            $video_count = 0;$audio_count = 0;
            
            $video_data = $audio_data = array();

            $attached_images = get_posts( $args );           

            
            if(!empty($attached_images)){
                foreach ($attached_images as $key => $value) {

                        $mimeType = explode('/',$value->post_mime_type);

                                if($mimeType[0] === 'video'){
                                        $post_data_video = get_post($value->ID);
                                        $video_data[$video_count]['video_type'] = 'video';
                                        $video_data[$video_count]['caption'] = $post_data_video->post_excerpt;
                                        $video_data[$video_count]['original_url'] = $post_data_video->guid;

                                        $video_count++;
                                } elseif($mimeType[0] === 'audio'){

                                        $post_data_audio= get_post($value->ID);
                                        $audio_data[$audio_count]['audio_type'] = 'audio';
                                        $audio_data[$audio_count]['caption'] = $post_data_audio->post_excerpt;
                                        $audio_data[$audio_count]['original_url'] = $post_data_audio->guid;

                                        $audio_count++;
                                }
                }
            }
            

								
            $article_object = array();

            $article_details['project_id'] = get_option('sortd_projectid');            
            $article_details['article_published_on'] = $post_data->post_date_gmt.':UTC';
            $article_details['article_modified_on'] = $post_data->post_modified_gmt.':UTC';
            $display_box_content = $post_data->post_content;

            $content = self::filter_content($display_box_content);
            $content = html_entity_decode($content);

            $polls_response = get_option('sortd_'.$project_id.'_enable_polls');
            if(isset($polls_response) && !empty($polls_response) && $polls_response === '1'){
                if(is_plugin_active('crowdsignal-forms/crowdsignal-forms.php') || is_plugin_active('polldaddy/polldaddy.php')) {
                    $poll_data = "/<script.*?<\/script><br \/>\s*<noscript>.*?<\/noscript>/";
                    if(preg_match($poll_data, $content, $matches)) {
                        $dom = new DOMDocument();
                        $dom->loadHTML($matches[0]);
                        $scripts = $dom->getElementsByTagName('script');
                        foreach ($scripts as $script) {
                            $src = $script->getAttribute('src');
                            $poll_id = explode('/', $src);
                            $poll_id = end($poll_id);
                            $poll_id = trim($poll_id, '.js');
                        }
                        $append_poll_div = "<div id='PDI_container". $poll_id ."'></div>\n$0";
                        $content = preg_replace($poll_data, $append_poll_div, $content);
                    }
                }
            }
            
            if(is_plugin_active('sortd_live_blog/sortd_live_blog.php') && $post_data->post_type === 'live-blog' && function_exists('get_live_blog_data')) {
                
                $end_time=get_post_meta( $post_id, 'blog_end_time', true );
                
                
                $end_blog_time = strtotime($end_time);
                $article_details['end_blog_time'] = $end_blog_time;
                $timezone_name_to = wp_timezone_string();
                
                $end_time = new DateTime($end_time, new DateTimeZone($timezone_name_to));
                
                $end_time = $end_time->format('Y-m-d H:i:s');
                
                $results = get_live_blog_data($post_id);
                
                foreach ($results as $result) {
                    $blog_count = $result->editor_box_count;
                    $title = $result->title;
                    $description = $result->description;
                    $image = $result->image;
                    $createdTime = $result->created_at;
                    $date_format = get_option('date_format').' '.get_option('time_format');
                    $timezone_name_to = wp_timezone_string();
                    $createdTime = date_create($createdTime, new DateTimeZone('UTC'))->setTimezone(new DateTimeZone($timezone_name_to))->format($date_format);
                        
                    $content .= '<div class=blog-content-card id=blog-container-'.$blog_count.' style="border: 1px solid #ddd; padding: 10px 10px 10px 20px; width: 95%; float: left; position: relative; margin: 10px 0 10px 0;">';
                    $content .= '<span class="sortd-liveblog-indicator-incontent" style="width: 16px; height: 10px; display: inline-block; position: absolute; border-radius: 32px; left: -6px; top: 23px;"></span>';
                    $content .= '<h2 class="entry-title" style="font-size: 1.2em;font-weight: bold;border-bottom: 1px dashed #ddd;padding:5px 0 10px;margin: 0;">'.$title.'</h2>';
                    $content .= '<p class="indiv-card-date" style="    font-size: 0.7em !important; opacity: 0.7; padding: 5px 0; margin-bottom: 0px;">'.$createdTime.'</p>';
                    if(isset($image) && !empty($image)) {
                        $content .= '<img class="liveblog-imgcls" src='.$image.'>';
                    }
                    $content .= '<p class="entry-liveblog-content" style="font-size: 1em !important;">'.$description.'</p>';
                    $content .= '</div>';
                }
            }
            
            $article_details['body'] = $content;

            $yoast_meta_title = get_post_meta($post_id, '_yoast_wpseo_title', true);
            $yoast_meta_desc = get_post_meta($post_id, '_yoast_wpseo_metadesc', true);
            $yoast_meta_keywords = get_post_meta($post_id, '_yoast_wpseo_focuskw', true);
            $seopressmetatitle = get_post_meta($post_id, '_seopress_titles_title', true);
            $seopressmetadesc = get_post_meta($post_id, '_seopress_titles_desc', true);            
            $seopresskeywords = get_post_meta($post_id, '_seopress_analysis_target_kw', true);   
            $mathrank_meta_title = get_post_meta($post_id, 'rank_math_title', true);
            $mathrank_meta_desc = get_post_meta($post_id, 'rank_math_description', true);
            $mathrank_meta_keywords = get_post_meta($post_id, 'rank_math_focus_keyword', true);            
            
            $yoast_meta_data = array();
            $yoast_meta_data['title'] = $yoast_meta_title;
            $yoast_meta_data['description'] = $yoast_meta_desc;
            $yoast_meta_data['keywords'] = $yoast_meta_keywords;

            $seopress_meta_data = array();
            $seopress_meta_data['title'] = $seopressmetatitle;
            $seopress_meta_data['description'] = $seopressmetadesc;
            $seopress_meta_data['keywords'] = $seopresskeywords;

            $mathrank_meta_data = array();
            $mathrank_meta_data['title'] = $mathrank_meta_title;
            $mathrank_meta_data['description'] = $mathrank_meta_desc;
            $mathrank_meta_data['keywords'] = $mathrank_meta_keywords;

            if(is_plugin_active('wordpress-seo/wp-seo.php')) {
                $meta_title = get_post_meta($post_id, '_yoast_wpseo_title', true);
               
             
                if(class_exists('WPSEO_Option_Titles')){
                    $meta_ti = wpseo_replace_vars($meta_title, $post_data );
                    $meta_ti = apply_filters( 'wpseo_title', $meta_ti );
                }

                if(isset($yoast_meta_desc) && !empty($yoast_meta_desc)){
                
                    global $post;

                    if(class_exists('WPSEO_Option_Titles')){
                
                    $meta_description =  wpseo_replace_vars( $yoast_meta_desc, $post );
        
                
                    $meta_description = apply_filters( 'wpseo_metadesc', $meta_description ,10,1);
                    }
                }


                if(isset($yoast_meta_keywords) && !empty($yoast_meta_keywords)){
                    $meta_keywords = get_post_meta($post_id, '_yoast_wpseo_focuskw', true);
                }

            }

            if(is_plugin_active('wp-seopress/seopress.php')) {
                if(isset($seopressmetatitle) && !empty($seopressmetatitle)){
                    $meta_ti = (get_post_meta($post_id, '_seopress_titles_title', true));
        
                }
        
                if(isset($seopressmetadesc) && !empty($seopressmetadesc)){
                
                    $meta_description = (get_post_meta($post_id, '_seopress_titles_desc', true));
                  
                }
    
                if(isset($seopresskeywords) && !empty($seopresskeywords)){
                    $meta_keywords = (get_post_meta($post_id, '_seopress_analysis_target_kw', true));
                }
            }

            if(is_plugin_active('seo-by-rank-math/rank-math.php')) {
                if(isset($mathrank_meta_title) && !empty($mathrank_meta_title)){
                    $meta_ti = get_post_meta($post_id, 'rank_math_title', true);
                }
        
                if(isset($mathrank_meta_desc) && !empty($mathrank_meta_desc)){
                    $meta_description = get_post_meta($post_id, 'rank_math_description', true);
                }
        
                if(isset($mathrank_meta_keywords) && !empty($mathrank_meta_keywords)){
                    $meta_keywords = get_post_meta($post_id, 'rank_math_focus_keyword', true);
                }
            }

            $meta_data = array();            

            if(isset($meta_ti) && !empty($meta_ti)){
                $meta_data['title'] = $meta_ti;  
            }

            if(isset($meta_description) && !empty($meta_description)){
                $meta_data['description'] = $meta_description;  
            }

            if(isset($meta_keywords) && !empty($meta_keywords)){
                $meta_data['keywords'] = $meta_keywords;  
            }
            $article_details['meta_data'] = $meta_data;

            $post_type =  get_post_format($post_data);

            if($post_type === 'audio'){
                $article_details['type'] = 'audio';
            } else if($post_type === 'video'){
                $article_details['type'] = 'video';
            } else if($post_data->post_type === 'live-blog'){
                $article_details['type'] = 'live-blog';
            } else {
                $article_details['type'] = $post_type;
            }
        
            $article_details['title'] = $post_data->post_title;
            $article_details['post_excerpt'] = str_replace('"',"'",$post_data->post_excerpt);
            $article_details['slug'] = $post_data->post_name;
            if(isset($tag_data)){
                $article_details['tags'] = $tag_data;
            }
            
			$article_details['post_type'] = $post_data->post_type;

            $project_details = Sortd_Helper::get_cached_project_details();
         
            $paid_article_price = '';
            $currency = get_post_meta($post_id, "sortd-paid-currency".$project_id,true);


            if(empty( $currency )){
                $currency = 'inr';
            }
            $article_details['is_paid'] =false;
            $currency = 'inr';
            if($project_details->data->paidarticle_enabled === true){
                $paid_article_price = get_post_meta($post_id, "sortd-paid-price".$project_id,true);
                $currency = 'inr';
                $article_details['is_paid'] =true;
            }

           
            $article_details['price'] = (int)$paid_article_price;

            $author_ids_array = array();
            
            $user = get_userdata($post_data->post_author);           
			$article_details['author'] = $user->display_name;
			$article_details['author_slug'] = $user->user_nicename;
        
	        $author_ids_array[] = $post_data->post_author;

	        $multiple_author_data = get_post_meta($post_id, '_molongui_author');

	        if(is_plugin_active('molongui-authorship/molongui-authorship.php') && isset($multiple_author_data) && !empty($multiple_author_data)) {    
	            $multiple_author = array_map(function($value) {
	                return str_replace('user-', '', $value);
	            }, $multiple_author_data);
	            $article_details['authors'] =   $multiple_author;
	        } else {
	            $article_details['authors'] =   $author_ids_array;
	        }
            
            $article_details['categories'] = $post_categories_data;
            $article_details['share_url'] = get_permalink($post_id);        
          
     
            // !Plugins rating  code start
            $customer_reviews = array();
            $user_reviews_data = array();
            if(is_plugin_active('wp-customer-reviews/wp-customer-reviews-3.php')){

                if(empty($reviews_data)){

                    $review_posts = get_posts([
                        'post_type' => 'wpcr3_review',
                        'post_status' => 'publish',
                        'numberposts' => 50,
                        'suppress_filters' => false
                    ]);


                    foreach($review_posts as $rk => $rv){
                        $r_id = get_post_meta($rv->ID, 'wpcr3_review_post',true);

                        if((int)$r_id === $post_id){
                            $customer_reviews[$rk]['title'] =  get_post_meta( $rv->ID,'wpcr3_review_title', true);
                            $customer_reviews[$rk]['rating'] = get_post_meta( $rv->ID,'wpcr3_review_rating', true);
                            $customer_reviews[$rk]['plugin'] = "wp-customer-reviews";
                        }
                    }

                } else {
                    $customer_reviews = $review_data;
                }
            }
            if(is_plugin_active('wp-ultimate-review/wp-ultimate-review.php')){
                $user_reviews_data = self:: _get_review_data($post_id);
                if(!empty($user_reviews_data)){
                   $reviews_data =$user_reviews_data;
                }
            }
           

            if(!empty($customer_reviews) && !empty($reviews_data)){
                $article_details['review'] = array_merge($reviews_data,$customer_reviews);
            } else if(!empty($customer_reviews) && empty($reviews_data)){
                $article_details['review'] = $customer_reviews;
            } else if(empty($customer_reviews) && !empty($reviews_data)){
                $article_details['review'] = $reviews_data;
            } else {
                $article_details['review'] = array();
            }


              
            $article_details['custom_meta_data'] = array();
            


            $article_object['articleData'] = $article_details;
        
            
            //!Plugins rating  code ends
            
            $all_image_merge = self::_get_gallery_and_content_images_for_post($post_id,$post_data,$featured_image_data);
            $article_media = self::_get_audio_data_for_article_object($content,$audio_data);


            if($article_object['articleData']['type'] === 'video'){

                $article_media = self::_get_video_data_for_article_object($content,$video_data,$post_id);

               
            } else {

                $article_media['mediaData']['video_media'] = array();
            }
      
            
            if(!empty($all_image_merge)){
                $article_media['mediaData']['image_media'] = $all_image_merge;
            } else {
                $article_media['mediaData']['image_media'] = array();
            }
            
            if(!empty($article_media)){
                $article_object = array_merge($article_media,$article_object);
            } 
            return $article_object;                                        
        }

        public static function get_dta_meta($html){
            return $html;
        }

    /**
	 *  function for getting images for  article object to sync with sortd
	 *
	 * @since    2.0.0
	 */

        private static function _get_images_for_post($post_id){
            $get_featured_image_data = get_post_meta($post_id,'_thumbnail_id');

            $featured_image_data = array();
            
            if(sizeof($get_featured_image_data)>0){

                $post_data_featured_image = get_post($get_featured_image_data[0]);

                if(isset($post_data_featured_image->guid)){
                $post_data_featured_image->guid = wp_get_attachment_url($get_featured_image_data[0]);
                if(has_filter('as3cf_filter_post_local_to_provider')){
                    $post_data_featured_image->guid = apply_filters('as3cf_filter_post_local_to_provider', $post_data_featured_image->guid);
                }
                

                if(strstr($post_data_featured_image->guid, "\r\n")) {
                    $post_data_featured_image->guid = str_replace("\r\n",'', $post_data_featured_image->guid);
                }

                $featured_image_caption_data = get_post_meta($post_id, "_cc_featured_image_caption", true);
                if(is_plugin_active("featured-image-caption/featured-image-caption.php") && !empty($featured_image_caption_data['caption_text'])) {
                    $post_excerpt = $featured_image_caption_data['caption_text'];
                } else {
                    $post_excerpt = $post_data_featured_image->post_excerpt;
                }

                $featured_image_data[0]['caption'] = $post_excerpt;
                $featured_image_data[0]['original_url'] = $post_data_featured_image->guid;
                $featured_image_data[0]['media_type'] = "thumbimage";
            }
            }
            return $featured_image_data;
        }

        /**
	 *  function for getting content images for  article object to sync with sortd
	 *
	 * @since    2.0.0
	 */

    private static function _get_gallery_and_content_images_for_post($post_id,$post_data,$featured_image_data){
        $attachment_data = array();
        preg_match_all('/<img\s+[^>]*src="([^"]*)"[^>]*>\s*(?:<figcaption[^>]*>(.*?)<\/figcaption>\s*)?/i',$post_data->post_content, $result);
        libxml_use_internal_errors(true); // Enable error handling

        $doc = new DOMDocument();
        
        if(!empty($result)){
            foreach ($result[0] as $content_attachment_key => $content_attachment_value) {

                $doc->loadHTML($content_attachment_value);

                libxml_clear_errors(); // Clear any accumulated errors

                $xpath = new DOMXPath($doc);
                $src = $xpath->evaluate("string(//img/@src)");
                
                $class = $xpath->evaluate("string(//img/@class)");
                $explode = explode(' ',$class);

                $explodesize = sizeof($explode);

                $image_id_explode = explode('-',$explode[$explodesize-1]);

                if(isset($image_id_explode[2])){
                        $image_id = $image_id_explode[2];
                        $post_data_image = get_post($image_id);
                }
                if(isset($post_data_image) && isset($post_data_image->post_excerpt) && !empty($post_data_image->post_excerpt)){
                        $attachment_data[$content_attachment_key]['caption'] = $post_data_image->post_excerpt;
                } else {
                    if(!empty($result[2][$content_attachment_key])) {
                        $attachment_data[$content_attachment_key]['caption'] = $result[2][$content_attachment_key];
                    } else {
                        $attachment_data[$content_attachment_key]['caption'] = "";
                    }
                        
                }

                if(has_filter('as3cf_filter_post_local_to_provider')){
                    $src = apply_filters('as3cf_filter_post_local_to_provider', $src);
                }
                if(strstr($src, "\r\n")) {

                   
                    $src = str_replace("\r\n",'', $src);
                }

                $attachment_data[$content_attachment_key]['original_url'] = $src;
                $attachment_data[$content_attachment_key]['media_type'] = 'gallery';
                unset($content_attachment_value);	
            }  
        }

        $gallery = self::na_get_gallery_image_urls($post_id);
       
        $attachment_data_gallery = array();

            if(isset($gallery) && !empty($gallery)){
             
                foreach ($gallery as $kG =>  $value_gallery) {
                    if(has_filter('as3cf_filter_post_local_to_provider')){
                        $value_gallery = apply_filters('as3cf_filter_post_local_to_provider', $value_gallery);
                    }

                    if(strstr($value_gallery, "\r\n")) {
                        $value_gallery = str_replace("\r\n",'', $value_gallery);
                    }
                    $attachment_data_gallery[$kG]['caption'] = '';
                    $attachment_data_gallery[$kG]['original_url'] = $value_gallery;
                    $attachment_data_gallery[$kG]['media_type'] = 'gallery';
                
                }
            }
      
        $all_image_merge = $article_image_merge = array();

        if($attachment_data_gallery && $attachment_data){
            $article_image_merge = array_merge($attachment_data_gallery,$attachment_data);
        }


        if($attachment_data_gallery && !$attachment_data){
            $article_image_merge = $attachment_data_gallery;
        }

        if(!$attachment_data_gallery && isset($attachment_data)){
            $article_image_merge = $attachment_data;
        }


        if(!$featured_image_data && $article_image_merge){
            $all_image_merge = $article_image_merge;
        }

        if($featured_image_data && !$article_image_merge){
            $all_image_merge = $featured_image_data;
        }

        if($featured_image_data && $article_image_merge){
            $all_image_merge = array_merge($featured_image_data,$article_image_merge);
        } 

        return $all_image_merge;


    }

    public static function na_get_gallery_image_urls( $post_id ) {

        $arra = array();
    
        $post = get_post($post_id);
    
        // Make sure the post has a gallery in it
        if( ! has_shortcode( $post->post_content, 'gallery' ) )
            return;
    
        // Retrieve all galleries of this post
        $galleries = get_post_galleries_images( $post );

        // Loop through all galleries found
        foreach( $galleries as $gallery) {
    
            // Loop through each image in each gallery
            foreach( $gallery as $image ) {
    
               $arra[] = $image;
    
            }
    
        }

        return $arra;
    
     }

      /**
	 *  function for getting audio for  article object to sync with sortd
	 *
	 * @since    2.0.0
	 */


    private static function _get_audio_data_for_article_object($content,$audio_data){
        preg_match_all('/\[playlist.*ids=.(.*).\]/', $content,$playlist_data);

            $docplaylist = new DOMDocument();

            $playlist_audio_data = array();
            if(!empty($playlist_data)){
                
                $playlist_audio_count = 0;
                
                foreach ($playlist_data[0] as $value_playlist) {

                    $docplaylist->loadHTML($value_playlist);
                    $arr1 = array('[','"',']');
                    $arr2 = array("","","=");
                    $rep = str_replace($arr1,$arr2,$docplaylist->textContent);
                    $split = explode('=',$rep);
                    $split_array = explode(',',$split[1]);


                    foreach($split_array as $sv){

                        $playlist_post_data_audio= get_post($sv);
                        if(isset($playlist_post_data_audio)){
                            $playlist_audio_data[$playlist_audio_count]['audio_type'] = 'audio';
                            $playlist_audio_data[$playlist_audio_count]['caption'] = $playlist_post_data_audio->post_excerpt;
                            $playlist_audio_data[$playlist_audio_count]['original_url'] = $playlist_post_data_audio->guid;
                        }

                        $playlist_audio_count++;
                    }


                }  
            }
            preg_match_all('/\[audio.*mp3=.(.*).\]/', $content,$content_audio_details);
            $docaudio = new DOMDocument();

            $content_audio_data = array();
            if(!empty($content_audio_details)){
                $content_audio_count = 0;
                
                foreach ($content_audio_details[0] as $value_content_audio) {

                    $docaudio->loadHTML($value_content_audio);
                    $arr1 = array('[','"',']');
                    $arr2 = array("","","=");
                    $rep = str_replace($arr1,$arr2,$docaudio->textContent);
                    $split = explode('=',$rep);
                    $split_array = explode(',',$split[1]);
                    
                    foreach($split_array as $value_split_array_audio){

                        $content_audio_data[$content_audio_count]['audio_type'] = 'audio';
                        $content_audio_data[$content_audio_count]['caption'] = "";
                        $content_audio_data[$content_audio_count]['original_url'] = $value_split_array_audio;
                        $content_audio_count++;
                    }
                }  
            }
					       

            $other_audio_data = array();
            if($playlist_audio_data && $content_audio_data){
                $other_audio_data = array_merge($playlist_audio_data,$content_audio_data);
            } else if(!$playlist_audio_data && $content_audio_data){
                $other_audio_data = $content_audio_data;
            } else if($playlist_audio_data && !$content_audio_data){
                $other_audio_data = $playlist_audio_data;
            }

            // find all iframes generated by php or that are in html    
            preg_match_all('/<iframe[^>]+src="([^"]+)"/', $content, $match);

            $iframe_urls = $match[1];

            $iframe_audio_data = $iframe_youtube_data = array();
            $spotify_count = $youtube_count = 0;
            foreach($iframe_urls as $audio_iframe_url_details){

                    $spliturl = explode('/',$audio_iframe_url_details);
                    // Test if string contains the word 
                    if(strpos($spliturl[2], 'spotify') !== false){
                        $iframe_audio_data[$spotify_count]['audio_type'] = 'spotify';
                        $iframe_audio_data[$spotify_count]['caption'] = "";
                        $iframe_audio_data[$spotify_count]['original_url'] =$audio_iframe_url_details;
                        $spotify_count++;
                    } else if(strpos($spliturl[2], 'youtube') !== false){
                        $iframe_youtube_data[$youtube_count]['video_type'] = 'youtube';
                        $iframe_youtube_data[$youtube_count]['caption'] = "";
                        $iframe_youtube_data[$youtube_count]['original_url'] =$audio_iframe_url_details;
                        $youtube_count++;
                    }

            }

            $all_audio_data = array();
            if($other_audio_data && !$iframe_audio_data){
                $all_audio_data = $other_audio_data;
            } else if(!$other_audio_data && $iframe_audio_data){
                $all_audio_data = $iframe_audio_data;
            } else if($other_audio_data && $iframe_audio_data){
                array_merge($other_audio_data,$iframe_audio_data);
            }

		
            $article_media = array();
            if($audio_data && $all_audio_data){
                $article_media['mediaData']['audio_media'] = array_merge($audio_data,$all_audio_data);
            } else if(!$audio_data && $all_audio_data){
                $article_media['mediaData']['audio_media'] = $all_audio_data;
            } else if($audio_data && !$all_audio_data){
                $article_media['mediaData']['audio_media'] = $audio_data;
            }

            return $article_media;

    }

    /**
	 *  function for getting content videos for  article object to sync with sortd
	 *
	 * @since    2.0.0
	 */

    private static function _get_video_data_for_article_object($content,$video_data,$post_id){
            preg_match_all('/\[playlist.*type=.*ids=.(.*).\]/', $content,$video_playlist_details);
            $iframe_youtube_data = array();
            $playlist_video_detail = new DOMDocument();

            $playlist_video_data = array();
            if(!empty($video_playlist_details)){
                $playlist_video_count = 0;
                
                foreach ($video_playlist_details[1] as $value_pv) {

                    $playlist_video_detail->loadHTML($value_pv);
                    $arr1 = array('[','"',']');
                    $arr2 = array("","","=");
                    $rep = str_replace($arr1,$arr2,$playlist_video_detail->textContent);
                    $split = explode('=',$rep);

                    $sizeof = sizeof($split);
                    $split_array = explode(',',$split[$sizeof-1]);

                    foreach($split_array as $svv){

                        $playlist_post_video_data= get_post($svv);
                        $playlist_video_data[$playlist_video_count]['video_type'] = 'video';
                        $playlist_video_data[$playlist_video_count]['caption'] = $playlist_post_video_data->post_excerpt;
                        $playlist_video_data[$playlist_video_count]['original_url'] = $playlist_post_video_data->guid;

                        $playlist_video_count++;

                    }

                }   
            }

            $other_video_data = array();
            if($playlist_video_data && $video_data){
                $other_video_data = array_merge($playlist_video_data,$video_data);
            } else if(!$playlist_video_data && $video_data){
                $other_video_data = $video_data;
            } else if($playlist_video_data && !$video_data){
                $other_video_data = $playlist_video_data;
            }   
            preg_match_all('/\[video.*width=.(.*).\]/', $content,$content_video_details);
            $docvideo = new DOMDocument();

            $content_video_data = array();
            if(!empty($content_video_details)){

                $content_video_count=0;
                
                foreach ($content_video_details[0] as $value_video) {

                    $docvideo->loadHTML($value_video);
                    $arr1 = array('[','"',']');
                    $arr2 = array("","","=");
                    $rep = str_replace($arr1,$arr2,$docvideo->textContent);
                    $split = explode('/video',$rep);
                    $splite = explode('=',$split[0]);
                    $sof = sizeof($splite);
                    $split_array = explode(',',$splite[$sof-2]);

                    foreach($split_array as $cvideo_url){
                        $content_video_data[$content_video_count]['video_type'] = 'video';
                        $content_video_data[$content_video_count]['caption'] = "";
                        $content_video_data[$content_video_count]['original_url'] = $cvideo_url;
                        
                    }

                }
            }

            $all_video_data = $article_media =array();
            if($iframe_youtube_data && $content_video_data){
                $all_video_data = array_merge($iframe_youtube_data,$content_video_data);
            } else if(!$iframe_youtube_data && $content_video_data){
                $all_video_data = $content_video_data;
            } else if($iframe_youtube_data && !$content_video_data){
                $all_video_data = $iframe_youtube_data;
            }   

			$featured_video = get_post_meta($post_id, 'td_post_video', true);
			if(isset($featured_video) && !empty($featured_video)){
                if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/\s]{11})%i', $featured_video['td_video'])) {
                    $video_type= 'youtube';
                } else {
                $video_type= 'video';
                }
                $featured_video_data = array();
                $featured_video_data['video_type'] = $video_type;

                $featured_video_data['caption'] = "";
                $featured_video_data['original_url'] = $featured_video['td_video'];
				
			}

			$project_id = Sortd_Helper::get_project_id();

			$jwplayer_response = get_option('sortd_'.$project_id.'_jw_player');

        	if(isset($jwplayer_response) && !empty($jwplayer_response) && (string)$jwplayer_response === '1'){

	        	$j=0;
	        	
	        	for($i=1;$i<=3;$i++){
	        		
	        		$jwplayer_videoid = get_post_meta($post_id, '_jwppp-video-url-'.$i, true);

						if(isset($jwplayer_videoid) && !empty($jwplayer_videoid)){
			              $video_type = 'jwplayer';
                          $jwplayer_video_data = array();
			              $jwplayer_video_data[$j]['video_type'] = $video_type;

			                  $jwplayer_video_data[$j]['caption'] = "";
			                  $jwplayer_video_data[$j]['original_url'] = $jwplayer_videoid;

			                  $j++;
							
						} else {
							break;
						}
	        	}

	        	
	        } 


	   

	        $new_array = array();

	        if(!empty($other_video_data)){

	        	$new_array = array_merge($new_array, $other_video_data);
	        
	        } 

	        if(!empty($all_video_data)){
	        	
	        	$new_array = array_merge($new_array, $all_video_data);
	        
	        } 

	        if(!empty($featured_video)){

                array_push($new_array, $featured_video_data);
	        
	        } 

	        if(!empty($jwplayer_video_data)){

	        
	        	$new_array = array_merge($new_array, $jwplayer_video_data);
	        
	        }

	 		$article_media['mediaData']['video_media'] = $new_array;

            return $article_media;

    }

    /**
	 *  function for syncing article meta data
	 *
	 * @since    2.0.0
	 */

     public static function send_meta_data_for_article($post_id){
        $post = get_post($post_id);
        $project_id = Sortd_Helper::get_project_id();
        $sync_status = get_post_meta($post_id, 'sortd_'.$project_id.'_post_sync', true);
		$yoast_meta_desc = get_post_meta($post_id, '_yoast_wpseo_metadesc', true);
		$yoast_meta_keywords = get_post_meta($post_id, '_yoast_wpseo_focuskw', true);
		$seopressmetatitle = get_post_meta($post_id, '_seopress_titles_title', true);
		$seopressmetadesc = get_post_meta($post_id, '_seopress_titles_desc', true);            
		$seopresskeywords = get_post_meta($post_id, '_seopress_analysis_target_kw', true);
        $mathrank_meta_title = get_post_meta($post_id, 'rank_math_title', true);
        $mathrank_meta_desc = get_post_meta($post_id, 'rank_math_description', true);
        $mathrank_meta_keywords = get_post_meta($post_id, 'rank_math_focus_keyword', true);
        $meta_ti = '';
        $meta_description = '';
        $meta_keywords = '';

        if(class_exists('WPSEO_Option_Titles')){
          
            $meta_title = get_post_meta( $post_id, '_yoast_wpseo_title', true );
            $meta_ti = wpseo_replace_vars($meta_title, $post );
            $meta_ti = apply_filters( 'wpseo_title', $meta_ti );
        }
        
		if(isset($yoast_meta_desc) && !empty($yoast_meta_desc)){

            if(class_exists('WPSEO_Option_Titles')){
        
            $meta_description =  wpseo_replace_vars( $yoast_meta_desc, $post );

           
			$meta_description = apply_filters( 'wpseo_metadesc', $meta_description ,10,1);
            }
           
		}

		if(isset($yoast_meta_keywords) && !empty($yoast_meta_keywords)){
			$meta_keywords = get_post_meta($post_id, '_yoast_wpseo_focuskw', true);
		}

	

        if(isset($seopressmetatitle) && !empty($seopressmetatitle)){
           
            $meta_ti = (get_post_meta($post_id, '_seopress_titles_title', true));

		}

        if(isset($seopressmetadesc) && !empty($seopressmetadesc)){
           $meta_description = (get_post_meta($post_id, '_seopress_titles_desc', true));
          
        }


		if(isset($seopresskeywords) && !empty($seopresskeywords)){
            $meta_keywords = (get_post_meta($post_id, '_seopress_analysis_target_kw', true));
        }


        if(isset($mathrank_meta_title) && !empty($mathrank_meta_title)){
            $meta_ti = get_post_meta($post_id, 'rank_math_title', true);
        }

        if(isset($mathrank_meta_desc) && !empty($mathrank_meta_desc)){
            $meta_description = get_post_meta($post_id, 'rank_math_description', true);
        }

        if(isset($mathrank_meta_keywords) && !empty($mathrank_meta_keywords)){
            $meta_keywords = get_post_meta($post_id, 'rank_math_focus_keyword', true);
        }

        $params = '{
            "guid": "'.$post_id.'",
            "meta_data": {
                "title": "'.$meta_ti.'",
                "description": "'.$meta_description.'",
                "keywords": "'.$meta_keywords.'"
            }
        }';

        if($sync_status === 1 || $sync_status === "1" || $sync_status === true){
            $post_meta_api_slug = "article/sync-meta-data";
            Sortd_Helper::sortd_post_api_response($post_meta_api_slug,$params);
        }
       
     
    }



    public static function sync_webstories($post_id,$post){
        $featured_image_data = self::_get_images_for_post($post_id);

        if(isset($post_id) && !empty($post_id)){
            $post_id = $post_id;
        } else {
            $post_id = get_the_ID();
        }

        if(isset($post->post_title) && !empty($post->post_title)){
            $post->post_title = $post->post_title;
        } else {
            $post->post_title = "";
        }

        if($post->post_type === 'web-story'){
            if(isset($featured_image_data) && !empty($featured_image_data)){
                $featured_image_data = $featured_image_data[0]['original_url'];
            } else {
                $featured_image_data = get_post_meta($post_id,'web_stories_poster',true);
                if (empty($featured_image_data)) {
                    $featured_image_data = '';
                } else {
                    $featured_image_data = $featured_image_data['url'];
                }
            }
        } else if($post->post_type === 'makestories_story') {
            if(isset($featured_image_data) && !empty($featured_image_data)){
                $featured_image_data = $featured_image_data[0]['original_url'];
            } else {
                $featured_image_data_object= json_decode(get_post_meta($post_id,'publisher_details',true));
                $featured_image_data = $featured_image_data_object->{'poster-portrait-src'};
            }
        }


      
       // list_web_cats

        $project_id = get_option('sortd_projectid');
        
        $api_slug = 'contentsettings/listwpstorycategories';
        $response = Sortd_Helper::sortd_get_api_response($api_slug,'v2');
        $sorted_categories = json_decode($response);

       $categories = get_the_terms($post_id, 'web_story_category');

        if(isset($categories) && !empty($categories)){

            $cat_array = array();
            if(sizeof($categories) > 1){

       		    foreach($categories as $k => $v){

       			    $cat_array[] = $v->term_id;
       		    }
            } else {
        	    if(isset($categories) && sizeof($categories) === 1){
        		    $cat_array[] = $categories[0]->term_id;
        	    } else {
        		    $cat_array = array();
        	    }

            }
            

        
            $list_array = $cat_final_array = array();

            if($sorted_categories->status === true){

                if(!empty($sorted_categories->data->categories)){

                    foreach($sorted_categories->data->categories as $c_cat){

                        array_push($list_array,$c_cat->cat_guid);
                    }
                }
            }
            foreach($cat_array as $k => $v){

                if(in_array($v, $list_array, true )){

                    $cat_final_array[$k]['category_id'] = $v;

                }


            }

            if(isset($cat_final_array) && !empty($cat_final_array)){
                $cat_final = wp_json_encode($cat_final_array);
            } else {
                $cat_final = wp_json_encode([]);
            }
        }else{
            $cat_final=wp_json_encode([]);
        }
     
       
        $project_id = Sortd_Helper::get_project_id();
      
		$params =  '{
                        "guid":"'.$post_id.'",
                        "webstory_published_on":"'.$post->post_date_gmt.':UTC",
                        "title":"'.$post->post_title.'",
                        "image":"'.$featured_image_data.'",
                        "share_url":"'.get_permalink($post_id).'",
                        "categories":'.$cat_final.'
                     }';

                   
		
        $article_api_slug = 'webstory/ingest';
		$article_response = Sortd_Helper::sortd_post_api_response($article_api_slug, $params);
		$response = json_decode($article_response);
        if($response->status === true){
            update_post_meta($post_id,'sortd_sync_web_story'.$project_id,1);
        } elseif($response->status === false) {
            if($response->error->errorCode === 503) {
                update_option('sortd_'.$project_id.'_maintenance_message_wbsync', $response->error->message);
            } else {
                delete_option('sortd_'.$project_id.'_maintenance_message_wbsync');
            }
        }


        return $response;
	}



    public static function unsync_webstory($post_id){

     
       $article_api_slug = "webstory/delete/".$post_id;
     
       $params = '';
       $article_response = Sortd_Helper::sortd_delete_api_response($article_api_slug, $params);
       $response_array = json_decode($article_response);
       $project_id = Sortd_Helper::get_project_id();

       if($response_array->status === true){

         update_post_meta($post_id,'sortd_sync_web_story'.$project_id,0);
        } elseif($response_array->status === false) {
            if($response_array->error->errorCode === 503) {
                update_option('sortd_'.$project_id.'_maintenance_message_wbunsync', $response_array->error->message);
            } else {
                delete_option('sortd_'.$project_id.'_maintenance_message_wbunsync');
            }
        }
      
        return $response_array;
   }

   public static function manual_unsync_webstory(){
    if(!check_ajax_referer('sortd-ajax-nonce-article', 'sortd_nonce')) {
        $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
           echo wp_kses($result); wp_die();
    }
        if(isset($_POST['guid'])){
            $post_id = sanitize_text_field($_POST['guid']);
            $result =  self::unsync_webstory($post_id);
          
            echo wp_json_encode($result);
        }
       

        wp_die();
   }

   public  function manual_sync_webstory(){
        if(!check_ajax_referer('sortd-ajax-nonce-article', 'sortd_nonce')) {
            $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
            echo wp_kses($result); wp_die();
        }
        if(isset($_POST['post_id'])){
            $post_id = sanitize_text_field($_POST['post_id']);
            $post = get_post( $post_id);
            $result = self::sync_webstories($post_id, $post);
            echo wp_json_encode($result);
        }
       
        wp_die();
    }





   public static function get_seopress_data($post_id){
    $context = seopress_get_service('ContextPage')->buildContextWithCurrentId($post_id)->getContext();

    $title = seopress_get_service('TitleMeta')->getValue($context);
    $description = seopress_get_service('DescriptionMeta')->getValue($context);
    $social = seopress_get_service('SocialMeta')->getValue($context);
    $robots = seopress_get_service('RobotMeta')->getValue($context);

    $canonical =  '';
    if(isset($robots['canonical'])){
        $canonical = $robots['canonical'];
        unset($robots['canonical']);
    }

    $data = [
        "title" => $title,
        "description" => $description,
        "canonical" => $canonical,
        "og" => $social['og'],
        "twitter" => $social['twitter'],
        "robots" => $robots
    ];
    apply_filters('seopress_get_tag_post_excerpt_value', $post_id, $context);
    $dataff =  apply_filters('seopress_headless_get_post', $data, $post_id, $context);
    return $dataff;
}

        /**
	 *  function for bulk sync webstories
	 *
	 * @since    2.0.0
	 */
	public function bulk_sync_webstories() {
        if(!check_ajax_referer('sortd-ajax-nonce-article', 'sortd_nonce')) {
            $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
               echo wp_kses($result); wp_die();
        }


        $post_id = array();

        if(isset($_POST['postids'])){
            $post_id = sanitize_text_field($_POST['postids']);
        }

        $project_id = Sortd_Helper::get_project_id();

        $response = new stdClass();
        $response->status = false;


            if(! empty( $post_id ) ){	   		
                    
                    $post = get_post($post_id);

                    if ($post->post_status === 'publish') { // check for bulk sync only published posts
                        $response = self::sync_webstories($post_id, $post);


                        update_post_meta($post_id,'bulk_sync_webstory'.$project_id,2);	
                    }   
            }		
       
        echo wp_json_encode($response);			

        wp_die();

		

	}
        
        /**
	 *  function for update bulk count webstory
	 *
	 * @since    2.0.0
	 */
	public function update_bulk_count_webstory() {
        if(!check_ajax_referer('sortd-ajax-nonce-article', 'sortd_nonce')) {
            $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
               echo wp_kses($result); wp_die();
        }

        $project_id = Sortd_Helper::get_project_id();

        $post_count = 0;
        
        if(isset($_POST['post_count_Wb'])){
            $post_count = sanitize_text_field($_POST['post_count_Wb']);
        }
       
       
        update_option('bulk_sync_webstory_count'.$project_id,$post_count);

        echo wp_kses_data ($post_count);

        wp_die();

	}

  
        
    /**
	 *  function for update bulk flag for sync webstory
	 *
	 * @since    2.0.0
	 */
	public function update_bulk_flag_webstory() {
        if(!check_ajax_referer('sortd-ajax-nonce-article', 'sortd_nonce')) {
            $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
               echo wp_kses_data($result); wp_die();
        }
        $project_id = Sortd_Helper::get_project_id();
        if(isset($_POST['post_count_Wb'])){
            $post_count = sanitize_text_field($_POST['post_count_Wb']);
        }
       
        update_option('bulk_sync_webstory_count'.$project_id,$post_count);
		update_option('bulk_action_webstory'.$project_id,1);

		wp_die();
		

	}


            /**
	 *  function for bulk unsync webstories
	 *
	 * @since    2.0.0
	 */
	public function bulk_unsync_webstories() {
        if(!check_ajax_referer('sortd-ajax-nonce-article', 'sortd_nonce')) {
            $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
               echo wp_kses($result); wp_die();
        }

        if(isset($_POST['postids'])){
            $post_id = sanitize_text_field($_POST['postids']);
        }

        $project_id = Sortd_Helper::get_project_id();

        $response = new stdClass();
        $response->status = false;


        if(! empty( $post_id ) ){		   		
            $unsync_wb_flag = get_post_meta($post_id,'sortd_sync_web_story'.$project_id,true);
             
            if(isset($unsync_wb_flag) && !empty($unsync_wb_flag) && ($unsync_wb_flag === "1" || $unsync_wb_flag === 1 || $unsync_wb_flag === 'true' || $unsync_wb_flag === true)){

                $post = get_post($post_id);

                if ($post->post_status === 'publish') { // check for bulk sync only published posts
                    $response = self::unsync_webstory($post_id);

                    update_post_meta($post_id,'bulk_unsync_webstory'.$project_id,2);	
                }

            }
               
        }		
       
        echo wp_json_encode($response);			

        wp_die();

		

	}
        
        /**
	 *  function for update bulk count webstory
	 *
	 * @since    2.0.0
	 */
	public function update_bulk_count_webstory_unsync() {
        if(!check_ajax_referer('sortd-ajax-nonce-article', 'sortd_nonce')) {
            $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
               echo wp_kses($result); wp_die();
        }

        $project_id = Sortd_Helper::get_project_id();
        $post_count = 0;

        if(isset($_POST['post_count_unsync'])){
            $post_count = sanitize_text_field($_POST['post_count_unsync']);
        }

        update_option('bulk_webstory_count_unsync'.$project_id,$post_count);

        echo wp_kses_data ($post_count);

        wp_die();

	}

  
        
    /**
	 *  function for update bulk flag for sync webstory
	 *
	 * @since    2.0.0
	 */
	public function update_bulk_flag_webstory_unsync() {
        if(!check_ajax_referer('sortd-ajax-nonce-article', 'sortd_nonce')) {
            $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
               echo wp_kses_data($result); wp_die();
        }
        $project_id = Sortd_Helper::get_project_id();

        $post_count = 0;

        if(isset($_POST['post_count_wb_unsync'])){
            $post_count = sanitize_text_field($_POST['post_count_wb_unsync']);
        }

        update_option('bulk_webstory_count_unsync'.$project_id,$post_count);
		update_option('bulk_action_webstory_unsync'.$project_id,1);

		wp_die();
		

	}

    public function get_data_article(){
        if(!check_ajax_referer('sortd-ajax-nonce-article', 'sortd_nonce')) {
            $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
               echo wp_kses_data($result); wp_die();
        }
       
        $post_id = 1;

        if(isset($_POST['post_id'])){
            $post_id = sanitize_text_field($_POST['post_id']);
        }

        $project_id = Sortd_Helper::get_project_id();
        
        $article_details_slug =  "article/details/".$post_id;

        $details_response = Sortd_Helper::sortd_get_api_response($article_details_slug);

        $response = json_decode($details_response);

        $old_price =0;
        $new_price = 0;

        $old_price = get_post_meta($post_id,'sortd_'.$project_id.'_old_price');
        $new_price = get_post_meta($post_id,'sortd_'.$project_id.'_new_price',true);
        if(sizeof($old_price) !== 0){
           $old_price = $old_price[0][0];
        
        } else {
            $old_price =0;
        }
        
        if(!empty($response->data->notifications)){
            echo wp_kses_data ($response->data->createdAt);
            $date_format = get_option('date_format').' '.get_option('time_format');
            if(function_exists('wp_timezone_string')){
                $timezone_name_to = wp_timezone_string();
                $date = date_create($response->data->notifications[0]->createdAt, new DateTimeZone('UTC'))->setTimezone(new DateTimeZone($timezone_name_to))->format($date_format);
            } else {
                $date = gmdate( $date_format, $response->data->notifications[0]->createdAt);
            }
          
        } else {
            $date = "";
        }

        $val = get_post_meta($post_id, 'sortd_'.$project_id.'_post_sync',true);
        if($val === 1 || $val === '1' || $val === 'true' || $val === true){
            $status = 'synced';
        } else {
            $status = 'unsynced';
        }

        $paid_article_price = get_post_meta($post_id, "sortd-paid-price".$project_id,true);
        if(!$paid_article_price){
            $price = '';
        } else {
            $price = $paid_article_price;
        }
        $is_paid = 1;

        $project_details = Sortd_Helper::get_cached_project_details();
        if($project_details->data->paidarticle_enabled !== true){
            $price = '';
            $is_paid = 0;
        } 
        $array = array();
        $array['date'] = $date;
        $array['status'] = $status;
        $array['price'] = $price;
        $array['is_paid_flag'] = $is_paid;
        $array['old_price'] = $old_price;
        $array['new_price'] = $new_price;
       echo wp_json_encode($array);

        wp_die();
    }


      /**
	 *  function for author data sync on adding new user and update user profile
	 *
	 * @since    2.2.1
	 */

    public function sync_author_data($user_id){

        $project_id = Sortd_Helper::get_project_id();
        $author_data = array();

        $author_info = get_userdata($user_id);
        $author_data['author_id'] = $author_info->data->ID;
        $author_data['display_name'] = $author_info->data->display_name;
        $author_data['user_email'] = $author_info->data->user_email;
        $author_data['user_nicename'] = $author_info->data->user_nicename;
        $author_data['author_page_url'] = get_author_posts_url($user_id);
        $author_data['author_id'] = $author_info->data->ID;
        $author_data['description'] = get_the_author_meta('description',$user_id);

        $user = new WP_User($user_id);
        if ( !empty( $user->roles ) && is_array( $user->roles ) ) {
           $type = $user->roles[0];
        } else {
            $type = '';
        }

        $neeon_author_designation = get_user_meta($user_id, 'neeon_author_designation');
        if(isset($neeon_author_designation) && !empty($neeon_author_designation)) {
            $author_data['type'] = $neeon_author_designation[0];
        } else {
            $author_data['type'] = $type;
        }
        

        if(function_exists('get_avatar_url')){
            $author_image = get_avatar_url($user_id);
        }

        $author_image = isset($author_image) ? $author_image  : '';
      
        $author_data['image'] = $author_image;

        $params = '{
            "author_id" : "'. $author_data['author_id'].'",
            "display_name" : "'.$author_data['display_name'].'",
            "user_email" : "'.$author_data['user_email'].'",
            "user_nicename" : "'. $author_data['user_nicename'].'",
            "author_page_url" : "'.$author_data['author_page_url'].'",
            "type" : "'.$author_data['type'].'",
            "image" : "'.$author_data['image'].'",
            "description" : "'.$author_data['description'].'"
        }'; 

    $author_api_slug = "author/sync";   
    $response = Sortd_Helper::sortd_post_api_response($author_api_slug, $params);
    $decode_response = json_decode($response);


    if($decode_response -> status === true){
        update_user_meta($user_id,'sortd_sync_author_'.$project_id,$decode_response->data->_id);
    }
    return $response;
    }

      /**
	 *  function for remove author data 
	 *
	 * @since    2.2.1
	 */

    public function remove_author($author_id){
        $project_id = Sortd_Helper::get_project_id();
        $delete_author_api_slug =  "author/delete/".$author_id;
        $response = Sortd_Helper::sortd_delete_api_response($delete_author_api_slug,'');
        $decode_response = json_decode($response);
        if($decode_response -> status === true){
            delete_user_meta($author_id,'sortd_sync_author_'.$project_id);
        }
        return $response;
    }

            /**
     * function for getting wp_customer_reviews plugin data sync with sortd
     * @since 2.3.2
     * 
     */
    


    public static function _get_wp_customer_reviews($post_id){

        $review_ID_array = array();
        $project_id = Sortd_Helper::get_project_id();

     
        $review_post_linked_post_id = get_post_meta( $post_id,'wpcr3_review_post', true);
        $post_data = get_post($review_post_linked_post_id);

        $review_ID_array[0]['title'] =  get_post_meta( $post_id,'wpcr3_review_title', true);
        $review_ID_array[0]['rating'] = get_post_meta( $post_id,'wpcr3_review_rating', true);
        $review_ID_array[0]['plugin'] = "wp-customer-reviews";


        $synced_flag =  get_post_meta( $review_post_linked_post_id,'sortd_'.$project_id.'_post_sync', true);

        if(!empty($review_post_linked_post_id) && ($synced_flag === 1 || $synced_flag === '1' || $synced_flag === true || $synced_flag === 'true')){
          self::sync_article($review_post_linked_post_id, $post_data,$review_ID_array);
       }
      
        
       return $review_ID_array;


    }



    /**
     * function for getting wp_ultimate_reviews plugin data sync with sortd
     * @since 2.3.2
     * 
     */

    public static function _get_review_data($post_id){

        $review_ID_array = array();

        if(metadata_exists( 'post', $post_id, 'xs_review_overview_settings' )){
            
            $get_post_meta_reviews = get_post_meta($post_id,'xs_review_overview_settings',true);
         
            $review_ID_array = array();
            if(!empty($get_post_meta_reviews)){
               
                $decode_reviews = json_decode($get_post_meta_reviews);
                foreach($decode_reviews->overview->item as $key => $value) {
                    $review_ID_array[$key]['title'] = $value->name;
                    $review_ID_array[$key]['rating'] = $value->ratting;
                    $review_ID_array[$key]['plugin'] = "wp_ultimate_reviews";

                }
            }
        
        }

        return $review_ID_array;                
                
    }
    public function get_image_data_for_live_blog($blog_id, $blog_data) {
        $featured_image_data = self::_get_images_for_post($blog_id);
        $blog_media = self::_get_gallery_and_content_images_for_post($blog_id,$blog_data,$featured_image_data);
        return $blog_media;
    }

    public function get_video_data_for_live_blog($content,$video_data,$blog_id) {
        $blog_media = self::_get_video_data_for_article_object($content,$video_data,$blog_id);
        return $blog_media;
    }

    public function rate_later(){

    	 if(!check_ajax_referer('sortd-ajax-nonce-article', 'sortd_nonce')) {
            $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
               echo wp_kses_data($result); wp_die();
        }


       $curr_date = strtotime("+3 days");
        

       $project_id = Sortd_Helper::get_project_id();
       update_option('sortd_rate_remind_later'.$project_id,$curr_date);


        echo wp_json_encode($curr_date);

        wp_die();
    }


    public function show_not_again(){
    	if(!check_ajax_referer('sortd-ajax-nonce-article', 'sortd_nonce')) {
            $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
               echo wp_kses_data($result); wp_die();
        }
        $project_id = Sortd_Helper::get_project_id();
        update_option("sortd_show_again_flag".$project_id,1);

        wp_die();



    }



    public function sync_tag($term_id, $taxonomy_id, $taxonomy,$tag){

        $tag_name = $tag->name;
        $tag_slug = $tag->slug;
        $tag_description = $tag->description;

       $project_id = get_option('sortd_projectid');

        $params = '{
            "guid" : "'.$term_id.'",
            "name" : "'.$tag_name.'",
            "slug" : "'.$tag_slug.'",
            "desc" : '.wp_json_encode($tag_description).'
         
        }';


          $tag_sync_api_slug = "tags/tagsync";
          $response = Sortd_Helper::sortd_post_api_response($tag_sync_api_slug,$params);
          $result =  json_decode($response);



          if(isset($result->status) && $result->status === true){

            update_option('sortd_'.$project_id.'sync_tag_'.$term_id,1);
          } 
          return $response;

    }

    public function unsync_tag($term_id){
       
        $project_id = get_option('sortd_projectid');

        $params = '{
            "guid" : "'.$term_id.'"
         
        }';

          $tag_sync_api_slug = "tags/tagremove";
          $response = Sortd_Helper::sortd_post_api_response($tag_sync_api_slug,$params);
          $result =  json_decode($response);

          if($result->status === true){
            update_option('sortd_'.$project_id.'sync_tag_'.$term_id,0);
          }

          return $response;

    }

    public function sync_tag_ajax(){
    	
        if(!check_ajax_referer('sortd-ajax-nonce-tags', 'sortd_nonce')) {
            $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
               echo wp_kses_data($result); wp_die();
        }
        if(isset($_POST['id']) && !empty($_POST['id'])) {
            $term_id = sanitize_text_field($_POST['id']);
            $tag = get_term($term_id, 'post_tag');
            if(!isset($taxonomy)) {
                $taxonomy = "";
            }
            if(!isset($taxonomy_id)) {
                $taxonomy_id = "";
            }
            $response = $this->sync_tag($term_id, $taxonomy_id, $taxonomy,$tag);
            echo wp_kses_data($response);
            wp_die();
        }
    }


    public function unsync_tag_ajax(){
    	if(!check_ajax_referer('sortd-ajax-nonce-tags', 'sortd_nonce')) {
            $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
               echo wp_kses_data($result); wp_die();
        }

        if(isset($_POST['id']) && !empty($_POST['id'])) {
            $term_id = sanitize_text_field($_POST['id']);
        
            $response = $this->unsync_tag($term_id);
            echo wp_kses_data($response);
            wp_die();

        }

    }


    function getTagsList(){


          $tag_sync_api_slug = "tags/listtags";
          $response = Sortd_Helper::sortd_get_api_response($tag_sync_api_slug);
          return wp_kses_data($response);
        
    }

      
      
    function sync_tags_on_publish_post($post_id){

        $project_id = Sortd_Helper::get_project_id();
        $tags = wp_get_post_terms($post_id, 'post_tag');

        foreach($tags as $vt){

       		$valtag = Sortd_Helper::get_options_for_tag($project_id,$vt->term_id);

            if($valtag === false || $valtag === ''){
                $this->sync_tag($vt->term_id,$vt->term_taxonomy_id,$vt->taxonomy,$vt);
            }

        }
    }

    public function get_list_ajax_tags(){
        if(!check_ajax_referer('sortd-ajax-nonce-tags', 'sortd_nonce')) {
            $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
               echo wp_kses_data($result); wp_die();
        } 

        $response = $this->getTagsList();

        echo wp_kses_data($response);

        wp_die();

    } 


    public function get_data_webstory(){

    	if(!check_ajax_referer('sortd-ajax-nonce-article', 'sortd_nonce')) {
            $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
               echo wp_kses_data($result); wp_die();
        }
       
        $post_id = 1;

        if(isset($_POST['post_id'])){
            $post_id = sanitize_text_field($_POST['post_id']);
        }

        $project_id = Sortd_Helper::get_project_id();
        $article_details_slug =  "article/details/".$post_id;

        $details_response = Sortd_Helper::sortd_get_api_response($article_details_slug);

        $response = json_decode($details_response);

        $old_price =0;
        $new_price = 0;

        $old_price = get_post_meta($post_id,'sortd_'.$project_id.'_old_price');
        $new_price = get_post_meta($post_id,'sortd_'.$project_id.'_new_price',true);
        if(sizeof($old_price) !== 0){
           $old_price = $old_price[0][0];
        
        } else {
            $old_price =0;
        }
        
        if(!empty($response->data->notifications)){
            echo wp_kses_data ($response->data->createdAt);
            $date_format = get_option('date_format').' '.get_option('time_format');
            if(function_exists('wp_timezone_string')){
                $timezone_name_to = wp_timezone_string();
                $date = date_create($response->data->notifications[0]->createdAt, new DateTimeZone('UTC'))->setTimezone(new DateTimeZone($timezone_name_to))->format($date_format);
            } else {
                $date = gmdate( $date_format, $response->data->notifications[0]->createdAt);
            }
          
        } else {
            $date = "";
        }

        $val = get_post_meta($post_id, 'sortd_sync_web_story'.$project_id,true);
        if($val === 1 || $val === '1' || $val === 'true' || $val === true){
            $status = 'synced';
        } else {
            $status = 'unsynced';
        }

        $paid_article_price = get_post_meta($post_id, "sortd-paid-price".$project_id,true);
        if(!$paid_article_price){
            $price = '';
        } else {
            $price = $paid_article_price;
        }
        $is_paid = 1;

        $project_details = Sortd_Helper::get_cached_project_details();
        if($project_details->data->paidarticle_enabled !== true){
            $price = '';
            $is_paid = 0;
        } 
        $array = array();
        $array['date'] = $date;
        $array['status'] = $status;
        $array['price'] = $price;
        $array['is_paid_flag'] = $is_paid;
        $array['old_price'] = $old_price;
        $array['new_price'] = $new_price;
       echo wp_json_encode($array);

        wp_die();
    }


}