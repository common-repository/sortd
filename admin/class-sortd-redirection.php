<?php

/**
 * The redirection-specific functionality of the plugin.
 *
 * @link       https://www.sortd.mobi
 * @since      2.0.0
 *
 * @package    Sortd
 * @subpackage Sortd/admin
 */

/**
 * The redirection-specific functionality of the plugin.
 *
 * @package    Sortd
 * @subpackage Sortd/admin
 * @author     Your Name <email@example.com>
 */
class Sortd_Redirection {

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
		$this->loader->add_action('wp_ajax_save_redirection_values', $this, 'save_redirection_values');
		$this->loader->add_action('wp_ajax_show_warning_msg', $this, 'show_warning_msg');
		$this->loader->add_action('wp_ajax_get_sortd_service', $this, 'get_sortd_service_ajax');
	}

	/**
	 *  function to enqueie script form
	 *
	 * @since    2.0.0
	 */

	public function enqueue_scripts(){
		wp_enqueue_script('sortd-domains', SORTD_JS_URL . '/sortd-domains.js', array( 'jquery' ), $this->version, true );
		wp_enqueue_script( 'sweet-alert-message-js', SORTD_JS_URL . '/sweetalert.min.js', array( 'jquery' ), $this->version, true );
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
	 *  function for redirection page form
	 *
	 * @since    2.0.0
	 */
	public function redirection_page() {

		$project_id =Sortd_Helper::get_project_id();
		$redirection_flag = get_option('sortd_'.$project_id.'_redirection_status');
		$sortd_redirection = get_option('sortd_'.$project_id.'_redirection_code');
		$enable_amp_links = get_option('sortd_'.$project_id.'redirectValueAmp');
		$sortd_services = $this->get_sortd_service();
		
		$wp_domain = get_site_url();
        $current_user = wp_get_current_user()->display_name;
        $project_details = Sortd_Helper::get_cached_project_details();
        $project_slug = $project_details->data->slug;

		$view_data = array();
		$plugin_sortd_dashboard = new Sortd_Dashboard($this->sortd, $this->version, $this->loader);
		$chatbot_dashboard_data = $plugin_sortd_dashboard->get_chat_bot();
		$cname = $plugin_sortd_dashboard->get_cname_config();

		if($cname->data->allowPublicHostSetup!==true) { 

			
			$console_url = Sortd_Helper::get_pubconsole_url();
			$slug = Sortd_Helper::get_project_slug();
			$view_data['slug'] = $slug;
			$view_data['console_url'] = $console_url;
			Sortd_Helper::render_partials(array('sortd-contact-us'), $view_data);
		    return false;
		} 
		
		$url = site_url();
		$url_protocol = explode("://",$url);
		$view_data['url_protocol'] = $url_protocol;
		$view_data['project_details'] = Sortd_Helper::get_project_details();
		$view_data['project_id']  = $project_id;
		$view_data['redirection_flag']  = $redirection_flag;
		$view_data['sortd_redirection']  = $sortd_redirection;
		$view_data['enable_amp_links']  = $enable_amp_links;
		$view_data['sortd_services']  = $sortd_services;
		$view_data['chatbot_dashboard_data']  = json_decode($chatbot_dashboard_data);
		$view_data['wp_domain'] = $wp_domain;
        $view_data['current_user']= $current_user;
        $view_data['project_slug'] = $project_slug;
		
		Sortd_Helper::render_partials(array('sortd-domains-redirection'), $view_data);
	}
        
        /**
	 *  function to save redirection page form values
	 *
	 * @since    2.0.0
	 */
	public function save_redirection_values() {
		if(!check_ajax_referer('sortd-ajax-nonce-domains', 'sortd_nonce')) {
			$result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
		   	echo wp_kses_data($result); wp_die();
		}

	    $project_id =Sortd_Helper::get_project_id();

			$old_redirection_code = get_option('sortd_'.$project_id.'_redirection_code');
			$old_redirectValueAmp = get_option('sortd_'.$project_id.'redirectValueAmp');
			$sortd_service = get_option('sortd_'.$project_id.'_services');

			if(isset($_POST['enable_sortd_redirection'])){

			
				 update_option( 'sortd_'.$project_id.'_redirection_code',sanitize_text_field($_POST['enable_sortd_redirection']) );
			}

			if(isset($_POST['enable_amp_links'])){
				update_option( 'sortd_'.$project_id.'redirectValueAmp',sanitize_text_field($_POST['enable_amp_links'])  );
			}

			if(isset($_POST['sortd_service'])){
				update_option('sortd_'.$project_id.'_services',sanitize_text_field($_POST['sortd_service']));
			}

			update_option('sortd_'.$project_id.'_redirection_status',1);

			
			
			

			if(isset($_POST['domain_name'])){
				update_option('sortd_'.$project_id.'_domain_name',sanitize_text_field($_POST['domain_name']));
			}

		    $exclude_urls = array();

		 
		    if (isset($_POST['exclude_url']) && is_array($_POST['exclude_url'])) {
				$array_exclude_urls  = array_map('sanitize_text_field',$_POST['exclude_url']);
		    	foreach (($array_exclude_urls) as $value) {
			      if(!empty($value)) {
			      	$exclude_urls[] = ($value);
			      }
			    }
		    }

		 


		    if(isset($_POST['enable_sortd_redirection'])){
				$redirect_flag_value = sanitize_text_field($_POST['enable_sortd_redirection']);

				
		      if($redirect_flag_value === 1 || $redirect_flag_value === true || $redirect_flag_value === '1' || $redirect_flag_value === 'true'){

		      	
				update_option( 'sortd_'.$project_id.'_exclude_url', $exclude_urls );
			  }
			}
			
			  /* sending api request on redirection update event*/
			  $update_flag = false;
			  if($_POST['enable_sortd_redirection'] !== $old_redirection_code){
				$update_flag = true;
			  }

			  if($_POST['enable_amp_links'] !== $old_redirectValueAmp){
				$update_flag = true;
			  }

			  if($_POST['sortd_service'] !== $sortd_service){
				$update_flag = true;
			  }
			
			
			

			
			
			

			  if($update_flag){

				$pwa_enabled = false;
				$amp_enabled = false;
				$modified_by = '';
				$modified_by = get_bloginfo('admin_email');

				$pwa_enabled = isset($_POST['enable_sortd_redirection'])? sanitize_text_field($_POST['enable_sortd_redirection']) : false;
				$amp_enabled =  isset($_POST['enable_amp_links'])? sanitize_text_field($_POST['enable_amp_links']) : false;
				$service=  isset($_POST['sortd_service'])? sanitize_text_field($_POST['sortd_service']) : false;
				
				$params = '{
						"redirection_enabled" : '.$pwa_enabled.',
						"amp_links_enabled" : '.$amp_enabled.',
						"modified_by" : "'.$modified_by.'",
						"sortd_service": "'.$service.'"

					}';

					$redirection_api_slug = "project/redirection-status";
					$res = Sortd_Helper::sortd_post_api_response($redirection_api_slug,$params,'v2');
					echo wp_json_encode($res);
			  }			
		     
			
		wp_die();

	}

	/* function for showing warning */

	public function show_warning_msg(){
		if(!check_ajax_referer('sortd-ajax-nonce-domains', 'sortd_nonce')) {
			$result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
		   	echo wp_kses_data($result); wp_die();
		}
		$project_id = Sortd_Helper::get_project_id();
		$redirection_code = get_option('sortd_'.$project_id.'_redirection_code');
		$amp_links = get_option('sortd_'.$project_id.'redirectValueAmp');
		$sortd_service = get_option('sortd_'.$project_id.'_services');

		$sortd_services_array = array();
		$sortd_services_array['redirection'] = $redirection_code;
		$sortd_services_array['amp_links'] = $amp_links;
		$sortd_services_array['sortd_service'] = $sortd_service;

		echo wp_json_encode($sortd_services_array);
		wp_die();
	}


	/* Function to get redirection and amp settings */


	public function get_redirection_amp_settings(){
		$project_id = Sortd_Helper::get_project_id();
		$get_redirection = get_option('sortd_'.$project_id.'_redirection_code');
		$get_amp_links = get_option('sortd_'.$project_id.'redirectValueAmp');
		
        
        $get_sortd_services = $this->get_sortd_service();

	    $settings = array();
		$settings['redirection'] = $get_redirection;
		$settings['amp_links_settings'] = $get_amp_links;
		$settings['sortd_services'] = $get_sortd_services;

		return $settings;
		
	}


public function add_redirection_script($redirect_uri,$get_paid_price){

		?>
		<script type='text/javascript' >
			var sortd_redirect_uri = '<?php echo wp_kses_data($redirect_uri); ?>';
			var paid_article = '<?php echo wp_kses_data($get_paid_price);?>';
			if(sortd_redirect_uri !== undefined &&  sortd_redirect_uri.length !== 0){

				
				const substring = "/shorts/";

				if(sortd_redirect_uri.includes(substring) && !(sortd_redirect_uri.includes('/amp'))){
					

					var request_uri = '';
					request_uri = sortd_redirect_uri;
					top.location.href= request_uri;  
						
				} else if ((navigator.userAgent.match(/(iphone)|(ipod)|(android)|(blackberry)|(windows phone)|(symbian)/i))) {

					console.log("no");
					var request_uri = '';
					request_uri = sortd_redirect_uri;
					top.location.href= request_uri;  
				} else {

					
					/* Storing user's device details in a variable*/
					let details = navigator.userAgent;
			
					/* Creating a regular expression 
					containing some mobile devices keywords 
					to search it in details string*/
					let regexp = /android|iphone|kindle|ipad/i;
			
					/* Using test() method to search regexp in details
					it returns boolean value*/
					let isMobileDevice = regexp.test(details);
			
					if (isMobileDevice) {
						console.log("You are using a Mobile Device");
					} else {
						
						if(paid_article !== 0 && paid_article !== "0" && paid_article !== ''){
							var request_uri = '';
							request_uri = sortd_redirect_uri;
							top.location.href= request_uri;
						} else {
							console.log("You are using Desktop");
							console.log('redirect failed because of navigator');
						}
						
						
					}
  
					
				}
			}
		</script>
		<?php
	}

	/*  function for redirection and amp links */

	public function add_redirection_amp_code(){


		
		$redirect_uri = '';
		$project_id     =   Sortd_Helper::get_project_id();
		$settings       =   $this->get_redirection_amp_settings();
        $redirection_enabled = filter_var($settings['redirection'], FILTER_VALIDATE_BOOLEAN);
        $amp_links_enabled  =  filter_var($settings['amp_links_settings'], FILTER_VALIDATE_BOOLEAN) ;
		$sortd_services = $settings['sortd_services'];
		$canonical_url_redirect = get_option('sortd_'.$project_id.'_canonical_url_redirection_flag');
        $show_alternate_url = false;
		$show_canonical_url = false;
		$post_type_slug = 'article';

		if($canonical_url_redirect === 1 || $canonical_url_redirect === '1' || $canonical_url_redirect === true || $canonical_url_redirect === 'true'){
			$show_canonical_url = true;
		}
        if($sortd_services === 'pwa_only' || $sortd_services === 'pwa_and_amp_both' && $redirection_enabled){
             $show_alternate_url = true;
        }
		
		$domain_name = get_option( 'sortd_'.$project_id.'_domain_name'); 
		$host_flag = $this->parse_host_url($domain_name);
		if($host_flag === 1 || $host_flag === '1' || $host_flag === true || $host_flag === 'true'){
			$sortd_redirect_url = $domain_name;
		}else {
			$sortd_redirect_url = 'https://'.$domain_name;
		}
        
        if($redirection_enabled || $amp_links_enabled){
            
            $amp_links_postfix = '/amp';
            if($sortd_services==='amp_only'  ){
                $amp_links_postfix = '';
            }
            
            $redirect_uri_postfix = '';
            if($sortd_services==='amp_default'){
                $redirect_uri_postfix = '/amp';
				$amp_links_postfix = '';
            }
         
            $get_paid_price = 0;
            global $post;
            if(isset($post->ID)){
                $article_id = $post->ID; 
                $get_post_meta = get_post_meta($article_id,'sortd_'.$project_id.'_post_article_id',true);
            }

            if(!empty($post) && is_single() && !empty($get_post_meta) && $post->post_type !== 'live-blog'){

            	 


            	
                $get_paid_price = get_post_meta($article_id,'sortd-paid-price'.$project_id ,true);

                $slug = $post->post_name;  
				$post_type = $post->post_type;
				$article_url_redirect = get_option('sortd_'.$project_id.'_article_url_redirection_flag');
				$shorts_cat = get_option('sortd_shorts_catid_'.$project_id);


				if($article_url_redirect === 1 || $article_url_redirect === '1' || $article_url_redirect === true || $article_url_redirect === 'true') {



					$primary_category_id = get_post_meta($post->ID, '_yoast_wpseo_primary_category', true);
					if (isset($primary_category_id) && !empty($primary_category_id)) {
						$first_cslug = get_term($primary_category_id)->slug; 
					} else {


						$article_cat = get_the_category($post->ID);
					
						

						if (empty($article_cat)) {	

					              	
						    $post_taxonomies = get_object_taxonomies($post_type);	

						    
						    $exclude = array( 'post_tag', 'post_format' );
						    $post_taxonomy = array();
						    if (!empty($post_taxonomies)) {
						    	foreach ( $post_taxonomies  as $taxonomy ) {
							        if( !in_array( $taxonomy, $exclude, true ) ) {
							            $post_taxonomy[]=$taxonomy;
							        }
							    }
					    		// Get the terms related to post.
								$article_cat = get_the_terms( $post->ID, $post_taxonomy[0] );					         						    	
						    }
						} 

						$first_cslug = $article_cat[0]->slug;		

					}

					if(isset($shorts_cat) && !empty($shorts_cat)) {
						$shorts_cat_term = get_term($shorts_cat); 
						if (has_term($shorts_cat,$shorts_cat_term->taxonomy, $post->ID)) { 

							

							$redirect_uri =  $sortd_redirect_url."/shorts/".$slug."/".$post->ID;
						} else { 

							
							$redirect_uri =  $sortd_redirect_url."/".$post_type_slug."/".$first_cslug."/".$slug."/".$post->ID.$redirect_uri_postfix;
						} 
					} else {
						$redirect_uri =  $sortd_redirect_url."/".$post_type_slug."/".$first_cslug."/".$slug."/".$post->ID.$redirect_uri_postfix;
					}

					
				} else {

					if(isset($shorts_cat) && !empty($shorts_cat)) {
							$shorts_cat_term = get_term($shorts_cat); 

						if ((has_term($shorts_cat,$shorts_cat_term->taxonomy, $post->ID))) {

						
							$redirect_uri =  $sortd_redirect_url."/shorts/".$slug."/".$post->ID;
						} else {

							
							$redirect_uri =  $sortd_redirect_url."/".$post_type_slug."/".$slug."/".$post->ID.$redirect_uri_postfix;
						} 
					} else {
						$redirect_uri =  $sortd_redirect_url."/".$post_type_slug."/".$slug."/".$post->ID.$redirect_uri_postfix;
					}
					
				}

                echo "\n";
            ?>

            	<?php if($amp_links_enabled && $sortd_services !== 'pwa_only'){ 
            			$shorts_cat_term = get_term($shorts_cat); 
            		if((isset($shorts_cat) && empty($shorts_cat)) || !(has_term($shorts_cat,$shorts_cat_term->taxonomy, $post->ID))){ ?>
            			<link rel="amphtml" href="<?php echo wp_kses_data($redirect_uri.$amp_links_postfix);?>" >
            		<?php }
                

                 } 
              ?>
                
                <?php if($show_alternate_url){ ?>
                <link rel="alternate" media="only screen and (max-width: 640px)" href="<?php echo wp_kses_data($redirect_uri); ?>" />  
                <?php } ?>

				<?php if($show_canonical_url){ ?>
                <link rel="canonical"  href="<?php echo wp_kses_data($redirect_uri); ?>" />  
                <?php } ?>

				
                
            <?php      
            } elseif(is_front_page() || is_home()) {

                $redirect_uri =  $sortd_redirect_url.$redirect_uri_postfix;

                echo "\n";  ?> 

                <?php if($amp_links_enabled  && $sortd_services !== 'pwa_only'){ ?>
                <link rel="amphtml" href= "<?php echo  wp_kses_data($redirect_uri.$amp_links_postfix);?>">
                <?php } ?>               
                
                <?php if($show_alternate_url){ ?>
                <link rel="alternate" media="only screen and (max-width: 640px)" href="<?php echo wp_kses_data($redirect_uri); ?>" />  
                <?php } ?>

				<?php if($show_canonical_url){ ?>
                <link rel="canonical" href="<?php echo wp_kses_data($redirect_uri); ?>" />  
                <?php } ?>
                
            <?php 	
            } elseif(is_category() || is_tax()){

				
                $catArray = get_queried_object();
                $cat_sync_option = get_option('sortd_'.$project_id.'_category_sync_'.$catArray->term_id);
                   if((int)$cat_sync_option === 1){
                $cat_id = $catArray->term_id;
				$cat_slug = $catArray->slug;

				$category_url_redirect = get_option('sortd_'.$project_id.'_category_url_redirection_flag');
				$shorts_cat = get_option('sortd_shorts_catid_'.$project_id);

				if((isset($shorts_cat) && !empty($shorts_cat)) && ((int)$shorts_cat === $cat_id)){

					$redirect_uri =  $sortd_redirect_url."/shorts";
				
				} else {
					if($category_url_redirect === '1') {
						$redirect_uri =  $sortd_redirect_url."/$cat_slug$redirect_uri_postfix";
					} else {
						$redirect_uri =  $sortd_redirect_url."/category/$cat_id/$cat_slug$redirect_uri_postfix";
					}
				}

				
                echo "\n";  ?>
                
                <?php if($amp_links_enabled  && $sortd_services !== 'pwa_only'){ 

                	if(!((int)$shorts_cat === $cat_id)){ ?>

                			<link rel="amphtml" href= "<?php echo  wp_kses_data($redirect_uri.$amp_links_postfix);?>">
                	<?php } 

                    }
                ?>
                
                <?php if($show_alternate_url){ ?>
                <link rel="alternate" media="only screen and (max-width: 640px)" href="<?php echo wp_kses_data($redirect_uri); ?>" />  
                <?php } ?>

				<?php if($show_canonical_url){ ?>
                <link rel="canonical"  href="<?php echo wp_kses_data($redirect_uri); ?>" />  
                <?php } ?>
                
                
                <?php 
                }
            } 

			elseif(is_author()){

				$author_name = get_the_author_meta('user_nicename');
				$author_guid = get_the_author_meta("id");
				$redirect_uri =  $sortd_redirect_url."/author/".$author_name."/".$author_guid;

            }  

			elseif(is_tag()) {
				$tag_object = get_queried_object();
				$tag = $tag_object->slug;
				$tag_redirect_constant = get_option('sortd_'.$project_id.'_tag_redirection');
				if(isset($tag_redirect_constant) && !empty($tag_redirect_constant)) {
					$redirect_uri =  $sortd_redirect_url."/".$tag_redirect_constant."/".$tag;
				} else {
					$redirect_uri =  $sortd_redirect_url."/topic/".$tag;
				}
				$desktop_url = get_tag_link($tag_object->term_id);     

				echo "\n";  ?>
                
                <?php if($show_alternate_url){ ?>
                <link rel="alternate" href="<?php echo wp_kses_data($redirect_uri); ?>" />  
                <?php } ?>

				<?php if($show_canonical_url){ ?>
                <link rel="canonical" href="<?php echo wp_kses_data($desktop_url); ?>" />  
                <?php } ?>
                
                <?php 
			}

			

		

            $ignore_redirection = false;
            //logic for ignore url 
            /* case for exclude url */
            $exclude_urls = get_option( 'sortd_'.$project_id.'_exclude_url' );  

            if(isset($_SERVER['REQUEST_URI'])){
                $request_uri = sanitize_text_field($_SERVER['REQUEST_URI']);
            }

            $get_uri_path = wp_parse_url($request_uri,PHP_URL_PATH);


            if(!empty( $exclude_urls)){
                foreach($exclude_urls as $v){
                    $pos = strpos($get_uri_path, $v);
                    if(($pos) !== false){
                        $ignore_redirection = true;
                        break;
                    }
                }
            }


            if($ignore_redirection === true){
                $redirect_uri = '';
            }

            /*case for if article /post is not synced */

            if(isset($get_post_meta) && empty($get_post_meta) && !is_front_page() && !is_home() && !is_category() && !(is_tag())){
                $redirect_uri = '';
            }

            if(isset($redirection_enabled) && $redirection_enabled){
                $this->add_redirection_script($redirect_uri,$get_paid_price);
            }

        }	


	}


    /**
	 * function to get redirection enable settings
	 *
	 * @since    2.0.0
	 */
	public function get_redirection_enable_settings(){
		$project_id = Sortd_Helper::get_project_id();
		$get_option = get_option('sortd_'.$project_id.'_redirection_code');
		$get_option_amp = get_option('sortd_'.$project_id.'redirectValueAmp');
	
		if($get_option === 'true' || $get_option_amp === 'true'){
			$redirection_enabled = 1;
		 } else{
			$redirection_enabled = 0;
		 }

		return $redirection_enabled;
	}

	 /**
	 * function to check whether url is secured
	 *
	 * @since    2.0.0
	 */

	public function parse_host_url($host){
		$url = wp_parse_url($host);

		if(isset($url['scheme']) && $url['scheme'] === 'https'){
			$host_flag = 1;
		  } else {
			  $host_flag = 0;
		  }

		return $host_flag;
	}

    
    /**
	 *  function to get sortd service
	 *
	 * @since    2.2.0
	 */
    public function get_sortd_service() {
        
		$array_redirection_status = array();
        $project_id = get_option('sortd_projectid');
        $service = get_option('sortd_'.$project_id.'_services');
        $old_redirection_code = get_option('sortd_'.$project_id.'_redirection_code');
        $old_redirectValueAmp = get_option('sortd_'.$project_id.'redirectValueAmp');
		$redirection_enabled = filter_var($old_redirection_code, FILTER_VALIDATE_BOOLEAN);
		$amp_links_enabled = filter_var($old_redirectValueAmp, FILTER_VALIDATE_BOOLEAN);
        $service_array = array("pwa_and_amp_both", "pwa_only", "amp_default", "amp_only");
		if(!$old_redirection_code){
			$redirection_enabled = false;
		}

		if(!$old_redirectValueAmp){
			$amp_links_enabled = false;
		}
		
		if(!in_array($service, $service_array, true)){
            
            $service = 'pwa_and_amp_both';
            if($redirection_enabled === true && $amp_links_enabled === false){
               $service = 'pwa_only';
            } else if($redirection_enabled === false && $amp_links_enabled === true){
                $service = 'amp_default'; 
            }
            
            $group_name =   "general_settings";
            $group_config_api_slug = "config/project/".$project_id."/group/".$group_name;
			
            $response = json_decode(Sortd_Helper::sortd_get_api_response($group_config_api_slug),true);
            if($response['status'] === true){
                
                $general_settngs_data = json_decode($response['data']['general_settings']);
                if(isset($general_settngs_data->project_meta->amp_only_site) && $general_settngs_data->project_meta->amp_only_site === true){
                  $service = 'amp_only';
                } 
               
            }
            
           $option_flag =  update_option('sortd_'.$project_id.'_services',$service);
          
            $modified_by = 'auto';
	

			$array_redirection_status['redirection_enabled'] = $redirection_enabled;
			$array_redirection_status['amp_links_enabled'] = $amp_links_enabled;
			$array_redirection_status['modified_by'] = $modified_by;
			$array_redirection_status['sortd_service'] = $service;

			
			$params = wp_json_encode($array_redirection_status);

			if($option_flag === true){
				$redirection_api_slug = "project/redirection-status";
                Sortd_Helper::sortd_post_api_response($redirection_api_slug,$params,'v2');
            

			}

            
        }
        return $service;

    }

	public function get_sortd_service_ajax(){
		if(!check_ajax_referer('sortd-ajax-nonce-domains', 'sortd_nonce')) {
			$result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
		   	echo wp_kses_data($result); wp_die();
		}

		$service = $this->get_sortd_service();
		echo wp_json_encode($service);

		wp_die();
	} 
}
