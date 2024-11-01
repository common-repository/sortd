<?php
   /**
    * The admin-specific functionality of the plugin.
    *
    * @link       https://www.sortd.mobi
    * @since      1.0.0
    *
    * @package    Sortd
    * @subpackage Sortd/admin
    */

   /**
    * The admin-specific functionality of the plugin.
    *
    * Defines the plugin name, version, and two examples hooks for how to
    * enqueue the admin-specific stylesheet and JavaScript.
    *
    * @package    Sortd
    * @subpackage Sortd/admin
    * @author     Your Name <email@example.com>
    */
   class Sortd_Admin {

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

    // static function for nonce check
	public static function nonce_check() {
        $nonce = isset($_REQUEST['_wpnonce']) ? sanitize_text_field($_REQUEST['_wpnonce']) :"";

         if( $nonce && wp_verify_nonce( $nonce, SORTD_NONCE ) )
         {
            return true;         ;
         }
		 else {
            return false;
         }
	}


    /**
     * function to define module specific hooks
     *
     * @since    2.0.0
     */
    public function define_hooks() {

            $this->loader->add_action( 'admin_enqueue_scripts', $this, 'enqueue_styles' );
            $this->loader->add_action( 'admin_enqueue_scripts', $this, 'enqueue_scripts' );
            $this->loader->add_action( 'admin_menu', $this, 'add_menu' );
            $this->loader->add_action( 'wp_head', $this, 'add_sortd_redirection_code',999 );
            $this->loader->add_filter('manage_posts_columns', $this, 'my_columns' );
            $this->loader->add_action('manage_posts_custom_column', $this, 'my_show_columns' );
            $this->loader->add_filter('views_edit-post',$this,'add_bulk_sync_btn',100);
            $this->loader->add_action( 'admin_notices', $this, 'sortd_get_admin_notices' );
            $this->loader->add_action('admin_bar_menu', $this, 'add_link_to_admin_bar', 999);
            $this->loader->add_action('delete_category', $this, 'delete_wordpress_cat' );
            $this->loader->add_action('publish_to_trash', $this, 'my_trash_post_function',10,1);
            $this->loader->add_action('publish_to_draft', $this, 'unsync_wb',10,1);
            $this->loader->add_action('transition_post_status', $this, 'sync_custom_posts', 10, 3);
            $this->loader->add_action('wp_trash_post', $this, 'unsync_webstory', 10, 1);
            $this->loader->add_action('publish_to_draft', $this, 'unsync_webstory', 10, 1);
            $this->loader->add_action('admin_head', $this, 'get_sortd_categories');
            $this->loader->add_action('views_edit-web-story',$this,'add_bulk_sync_btn_webstory');
            $this->loader->add_action('views_edit-makestories_story',$this,'add_bulk_sync_btn_webstory');
            $this->loader->add_action("add_meta_boxes", $this,"add_custom_meta_box");
            $this->loader->add_action("save_post",$this, "save_custom_meta_box", 10, 3);
            $this->loader->add_action( 'edit_user_created_user', $this, 'sync_author_info',10,1 );
            $this->loader->add_action( 'profile_update', $this, 'sync_author_info',10,1 );
            $this->loader->add_action( 'deleted_user', $this, 'remove_author_data',10,1 );
            $this->loader->add_filter('manage_category_custom_column',$this, 'manage_cat_columns', 10, 3);
            $this->loader->add_filter('manage_edit-category_columns', $this,'add_cat_column');
            if(get_option('sortd_activated') === '1'){
                $this->loader->add_action( 'admin_init', $this, 'send_email_on_activation' );
            }
           $this->loader->add_action( 'wp_insert_post', $this, 'sync_wp_customer_reviews',999,1 );
           $this->loader->add_action( 'wp_head', $this, 'add_redirection_blog_link' );

            $this->loader->add_action('delete_post_tag', $this,'tag_deleted', 10, 1);
            $this->loader->add_filter('manage_web_story_category_custom_column',$this, 'manage_cat_columns_webstory', 10, 3);
            $this->loader->add_filter('manage_edit-web_story_category_columns', $this,'add_cat_column_webstory');
            $this->loader->add_action( 'admin_notices', $this, 'sortd_rating' ,999);
            $this->loader->add_action('create_term',$this, 'new_tag_created', 10, 3);
            $this->loader->add_filter('manage_edit-post_tag_columns',$this, 'add_custom_tag_column');
            $this->loader->add_action('manage_post_tag_custom_column',$this, 'populate_custom_tag_column', 10, 3);
            $this->loader->add_action('edit_term',$this, 'new_tag_created', 10, 3);

            $this->loader->add_action('save_post',$this, 'get_article_sync_call', 10, 3);

            $custom_posts = get_option('sortd_customposts');
            if (!empty($custom_posts)) {
                foreach ($custom_posts as $custom_post) {
                    $this->loader->add_filter("views_edit-{$custom_post}",$this,'add_bulk_sync_btn',100);

                    $taxonomies = get_option('sortd_taxonomy_'.$custom_post);
                    if (!empty($taxonomies)) {
                        foreach ($taxonomies as $taxonomy) {
                            $this->loader->add_filter("manage_{$taxonomy}_custom_column",$this, 'manage_cat_columns', 10, 3);
                            $this->loader->add_filter("manage_edit-{$taxonomy}_columns", $this,'add_cat_column');
                            $this->loader->add_action("delete_{$taxonomy}", $this, 'delete_taxonomy', 10, 3 );
                        }
                    }
                }
            }
        }


        function sortd_rating(){



            $messages = array(
                'notice'  => esc_html__( "Hi there! Stoked to see you're using Sortd for a few days now - hope you like it! And if you do, please consider rating it. It would mean the world to us.  Keep on rocking!", 'sortd' ),
                'rate'    => esc_html__( 'Rate the plugin', 'sortd' ),
                'rated'   => esc_html__( 'Remind me later', 'sortd' ),
                'no_rate' => esc_html__( 'Don\'t show again', 'sortd' ),
            );

            $project_id = Sortd_Helper::get_project_id();
         // sortd_rate_remind_later
                    $time = get_option('sortd_rate_remind_later'.$project_id);
                    $show_not_again_flag = get_option('sortd_show_again_flag'.$project_id);




                    $futureDate = strtotime(gmdate('y-m-d h:i:s'));


                    $allowed_html = array(
                        'div' => array(
                            'id' => true,
                            'class' => true,
                            'style' => true,
                        ),
                        'p' => array(),
                        'a' => array(
                            'id' => true,
                            'href' => true,
                            'target' => true,
                            'class' => true,
                            'style'=>true
                        ),
                        'button' => array(
                            'id' => true,
                            'class' => true,
                        ),
                    );





                    $notice = '<div id="sortd-review-notice" class="notice notice-success is-dismissible" style="margin-top:30px;">
                        <p>' . sprintf(esc_html($messages['notice'])) . '</p>
                        <p class="actions">
                            <a id="sortd-rate" href="https://wordpress.org/support/plugin/sortd/reviews/" target="_blank" class="button button-primary epsilon-review-button">' . esc_html($messages['rate']) . '</a>
                            <a id="sortd-later" href="#" style="margin-left:10px" class="sortd-review-button">' . esc_html($messages['rated']) . '</a>
                            <a id="sortd-no-rate" href="#" style="margin-left:10px" class="sortd-review-button">' . esc_html($messages['no_rate']) . '</a>
                        </p>
                    </div>';





            if((isset($time) && !empty($time)) && (isset($show_not_again_flag) && !empty($show_not_again_flag))){



                if(!empty($time) && $show_not_again_flag === '1'){


                } elseif(!empty($time) && $show_not_again_flag !==1 && $time > $futureDate){


                   echo wp_kses($notice, $allowed_html);


                }
            } elseif(isset($time) && empty($time) && !empty($show_not_again_flag) && $show_not_again_flag === '1'){



            } elseif(isset($time) && !empty($time) && !($show_not_again_flag)  && $futureDate > $time){

                echo wp_kses($notice, $allowed_html);

            } elseif(empty($time) && empty($show_not_again_flag)){

                echo wp_kses($notice, $allowed_html);


            } elseif($show_not_again_flag === '0' && !empty($time)  ){

                if($time > $futureDate){

                } else {

                    echo wp_kses($notice, $allowed_html);

                }



            }


        }

   
    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles() {

        $current_page = get_current_screen()->base;


        // absolute current URI (on single site):
        $current_uri = home_url( add_query_arg( NULL, NULL ) );


               $pages = array("edit","post","edit-tags","toplevel_page_sortd-settings", "sortd_page_sortd_notification", "sortd_page_sortd-manage-settings",'sortd_page_sortd-help','sortd_page_sortd_credential_settings','sortd_page_sortd_manage_templates','sortd_page_sortd_setup');
               if(in_array($current_page, $pages, true) || $current_uri === admin_url().'admin.php?page=sortd_credential_settings' ) {
                wp_enqueue_style( $this->sortd, SORTD_CSS_URL . '/sortd-admin.css', array(), $this->version, 'all' );
                wp_enqueue_style( 'bootstrapcss5', SORTD_CSS_URL . '/bootstrap.min.css', array(), $this->version, 'all' );
                wp_enqueue_style( 'bootstrap-font', SORTD_CSS_URL . '/bootstrapicon/bootstrap-icons.css', array(), $this->version, 'all' );
                wp_enqueue_style( 'bootstrap-checkbox', SORTD_CSS_URL . '/checkboxbootstrap.css', array(), $this->version, 'all' );
            }

    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts() {

               $current_page = get_current_screen()->base;
               $project_details = Sortd_Helper::get_cached_project_details();
               $site_url = get_site_url();

               $pages = array("edit","post","edit-tags","toplevel_page_sortd-settings", "sortd_page_sortd_notification", "sortd_page_sortd-manage-settings",'sortd_page_sortd-help','sortd_page_sortd_credential_settings','sortd_page_sortd_manage_templates','sortd_page_sortd_setup');
               $sortd_func_page = array("edit","post","edit-tags");
               
               if(in_array($current_page, $sortd_func_page, true) && isset($project_details) && !empty($project_details) && isset($project_details->data->ga4_key) && !empty($project_details->data->ga4_key) && get_current_screen()->post_type !== 'web-story') {

                $script_url = "https://www.googletagmanager.com/gtag/js?id=" . $project_details->data->ga4_key;

                wp_enqueue_script('google-tag-manager', esc_attr($script_url), array(), null );
                $inline_ga_script = "
                    window.dataLayer = window.dataLayer || [];
                    function gtag(){
                        dataLayer.push(arguments);
                    }
                    gtag('js', new Date());
                    gtag('config', '{$project_details->data->ga4_key}');
                    gtag('event', 'sortd_view', {
                        'sortd_page_title': '" . esc_attr($current_page) . "',
                        'sortd_domain': '" . esc_attr($site_url) . "',
                        'sortd_project_slug': '" . esc_attr($project_details->data->slug) . "',
                        'sortd_user': '" . esc_attr(wp_get_current_user()->display_name) . "'
                    });
                ";
                wp_add_inline_script('google-tag-manager', $inline_ga_script);

               }

               $ga_imp_page = array("toplevel_page_sortd-settings", "sortd_page_sortd_notification", "sortd_page_sortd-manage-settings",'sortd_page_sortd-help','sortd_page_sortd_credential_settings','sortd_page_sortd_manage_templates','sortd_page_sortd_setup');

                if(in_array($current_page, $ga_imp_page, true) && isset($project_details) && !empty($project_details) && isset($project_details->data->ga4_key) && !empty($project_details->data->ga4_key)) {
                    if(isset($_SERVER) && !empty($_SERVER) && isset($_SERVER['QUERY_STRING']) && !empty($_SERVER['QUERY_STRING'])) {
                        $query_string = sanitize_text_field($_SERVER['QUERY_STRING']);
                    
                        if(!empty($query_string)) {
                            parse_str($query_string, $query_string_array);
                    
                            if(isset($query_string_array['section']) && !empty($query_string_array['section'])) {
                                $page_name = $query_string_array['section'];
                            } else {
                                $page_name = $query_string_array['page'];
                            }
                            $script_url = "https://www.googletagmanager.com/gtag/js?id=" . $project_details->data->ga4_key;

                            wp_enqueue_script('google-tag-manager', esc_attr($script_url), array(), null );
                            $inline_ga_script = "
                                window.dataLayer = window.dataLayer || [];
                                function gtag(){
                                    dataLayer.push(arguments);
                                }
                                gtag('js', new Date());
                                gtag('config', '{$project_details->data->ga4_key}');
                                gtag('event', 'sortd_view', {
                                    'sortd_page_title': '" . esc_attr($page_name) . "',
                                    'sortd_domain': '" . esc_attr($site_url) . "',
                                    'sortd_project_slug': '" . esc_attr($project_details->data->slug) . "',
                                    'sortd_user': '" . esc_attr(wp_get_current_user()->display_name) . "'
                                });
                            ";
                            wp_add_inline_script('google-tag-manager', $inline_ga_script);
                            
                        }
                    }   
                }

               if(in_array($current_page, $pages, true)) {

                wp_enqueue_script( 'bootstrap5js', SORTD_JS_URL . '/bootstrap.min.js', array( 'jquery' ), $this->version, true );
                wp_enqueue_script( 'bootstrap-popper', '//cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js', array( 'jquery' ), $this->version, true );
                wp_enqueue_script( 'bootstrap-popper-min', '//stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js', array( 'jquery' ), $this->version, true );
                wp_enqueue_script( 'bootstrapcheckbox-min', SORTD_JS_URL . '/bootstrapcheckbox.js', array( 'jquery' ), $this->version, true );

            }


                   wp_enqueue_script( 'sortd-article', SORTD_JS_URL . '/sortd-article.js', array( 'jquery' ), $this->version, true );
                   wp_localize_script(
                       'sortd-article',
                       'sortd_ajax_obj_article',
                       array(
                           'ajax_url' => admin_url( 'admin-ajax.php' ),
                           'nonce'    => wp_create_nonce( 'sortd-ajax-nonce-article' ),
                       )
                   );
                   if(in_array($current_page, array('edit-tags'), true)) {



                  wp_enqueue_script('sortd-taxonomyjs', SORTD_JS_URL . '/sortd-taxonomy.js', array( 'jquery' ), $this->version, true );
                    wp_localize_script(
                        'sortd-taxonomyjs',
                        'sortd_ajax_obj_category',
                        array(
                            'ajax_url' => admin_url( 'admin-ajax.php' ),
                            'nonce'    => wp_create_nonce( 'sortd-ajax-nonce-category' ),
                        )
                    );
                }

                if($current_page === 'sortd_page_sortd-manage-settings'){

                    wp_enqueue_script('sortd-category', SORTD_JS_URL . '/sortd-category.js', array( 'jquery' ), $this->version, true );
                    wp_localize_script(
                        'sortd-category',
                        'sortd_ajax_obj_category',
                        array(
                            'ajax_url' => admin_url( 'admin-ajax.php' ),
                            'nonce'    => wp_create_nonce( 'sortd-ajax-nonce-category' ),
                        )
                    );

                }


                global $pagenow;

                // Check if the current page is the edit-tags.php page
                if ($pagenow === 'edit-tags.php') {
                    $screen = get_current_screen();

                    // Check if the current screen is the tags screen
                    if ($screen && $screen->base === 'edit-tags' && $screen->taxonomy === 'post_tag') {
                        wp_enqueue_script('sortd-tagsjs', SORTD_JS_URL . '/sortd-tags.js', array( 'jquery' ), $this->version, true );
                        wp_localize_script(
                            'sortd-tagsjs',
                            'sortd_ajax_obj_tags',
                            array(
                                'ajax_url' => admin_url( 'admin-ajax.php' ),
                                'nonce'    => wp_create_nonce( 'sortd-ajax-nonce-tags' ),
                            )
                        );
                    }
                }
    }

           /**
     * function to add the menu code of plugin
     *
     * @since    1.0.0
     */
    public function add_menu() {

         global $wp_version;
               $credentials = Sortd_Helper::get_credentials_values();

               if(!empty($credentials)){
                add_menu_page( "Manage Sortd", "Sortd", 'manage_options', $this->sortd . '-settings', array( $this, 'display_relevant_screen' ), SORTD_CSS_URL .'/sortdlogo.png');

                       $menu_name = "Dashboard";
               } else {
                $count_posts =1;


                add_menu_page( "Manage Sortd", "Sortd".' <span class="update-plugins count-2"><span class="update-count">' . $count_posts . '</span></span>', 'manage_options', $this->sortd . '-settings', array( $this, 'display_relevant_screen' ), SORTD_CSS_URL .'/sortdlogo.png');
                       $menu_name = "Get Started";
               }


               add_submenu_page($this->sortd . '-settings',
                   'Manage Sortd',
                   $menu_name,
                   'manage_options',
                   'sortd-settings',
                   array( $this, 'display_relevant_screen' )
            );

               if(empty($credentials)){

                   $plugin_sortd_utils = new Sortd_Utils($this->sortd, $this->version, $this->loader);

                   add_submenu_page($this->sortd . '-settings',
                       'Manage Sortd',
                       'Credentials',
                       'manage_options',
                       'sortd_credential_settings',
                       array( $plugin_sortd_utils, 'credentials_page' )
                   );
               }else{
                 if($wp_version >= '5.3'){
                   $sortd_project_id       = get_option('sortd_projectid');
                   $template_oneclick_flag = get_option('sortd_saved_template_and_oneclick'.$sortd_project_id);
                   $sortd_oneclick_flag    = get_option('sortd_oneclick_flag'.$sortd_project_id);


                   if(empty($template_oneclick_flag)){

                       $plugin_sortd_templates = new Sortd_Templates($this->sortd, $this->version, $this->loader);

                       add_submenu_page($this->sortd . '-settings',
                           'Manage Sortd',
                           'Themes',
                           'manage_options',
                           'sortd_manage_templates',
                           array( $plugin_sortd_templates, 'manage_templates' )
                       );
                   }
                   if((bool)$template_oneclick_flag === true && (bool)$sortd_oneclick_flag === false){

                       $plugin_sortd_oneclick = new Sortd_Oneclick($this->sortd, $this->version, $this->loader);

                       add_submenu_page($this->sortd . '-settings',
                           'Manage Sortd',
                           'OneClick Setup',
                           'manage_options',
                           'sortd_setup',
                           array( $plugin_sortd_oneclick, 'one_click_setup' )
                       );
                   }

                   if( (bool)$sortd_oneclick_flag === true){

                       $plugin_sortd_notifications = new Sortd_Notifications($this->sortd, $this->version, $this->loader);

                       add_submenu_page($this->sortd . '-settings',
                           'Manage Sortd',
                           'Notifications',
                           'manage_options',
                           'sortd_notification',
                           array( $plugin_sortd_notifications, 'notifications_dashboard' )
                       );

                       $plugin_sortd_dashboard = new Sortd_Dashboard($this->sortd, $this->version, $this->loader);

                       add_submenu_page($this->sortd . '-settings',
                           'Manage Sortd',
                           'Settings',
                           'manage_options',
                           'sortd-manage-settings',
                           array( $plugin_sortd_dashboard, 'settings_dashboard')
                       );

                       add_submenu_page($this->sortd . '-settings',
                           'Manage Sortd',
                           'Help',
                           'manage_options',
                           'sortd-help',
                           array( $plugin_sortd_dashboard, 'faqs_dashboard' )
                       );
                   }
                 }
                }
    }


       /**
     * function for adding sortd notification link to admin bar
     *
     * @since    1.0.0
     */
    public function add_link_to_admin_bar($admin_bar) {
        $credentials = Sortd_Helper::get_credentials_values();


            if(empty($credentials)){

                $count_posts = 1;
                $notification_popup = '<div class="wp-core-ui wp-ui-notification sortd-issue-counter"><span aria-hidden="true">'.$count_posts.'</span><span class="screen-reader-text">2 notifications</span></div>';
            } else {
                $notification_popup = '';
            }


        $title = "Sortd";


         $admin_bar->add_menu( array(
            'id'    => 'my-item',
            'title' => $title .  $notification_popup,
            'href'  => admin_url().'/admin.php?page=sortd-settings',

        ));


    }

           /**
     *  function for my columns wp hook add column
     *
     * @since    2.0.0
     */
    public function my_columns($columns) {


        $post_type = get_post_type();

        if (($post_type !== 'wpcr3_review') && ($post_type !== 'xs_review') && ($post_type !== 'ppma_boxes') && ($post_type !== 'ppmacf_field')){
            $columns['sortd_action'] = 'Sortd Action';
        }
        return $columns;

    }

           /**
     *  function for my show columns wp hook  column
     *
     * @since    2.0.0
     */
    public function my_show_columns($name) {

           global $post;

        $project_id = Sortd_Helper::get_project_id();
        $project_data = json_decode(get_option('sortd_project_details'));
        $post_type = $post->post_type;
   
        if($project_data->data->paidarticle_enabled === true){
            $data_paoid = 1;
        } else {
            $data_paoid = 0;
        }

            if(!empty($project_data->data->domain->public_host) && $project_data->data->domain->status === '4'){
                $host_name = 'https://'.$project_data->data->domain->public_host;
            } else {
                $host_name = $project_data->data->domain->demo_host;
            }
            $normal_demourlwp = $host_name.'/article/'.$post->post_name.'/'.$post->ID;

            $demobase_encoded = base64_encode($normal_demourlwp);
            $qr_code = $host_name.'/sortd-service/qrcode/v22-01/small?url='.$demobase_encoded;
            $desktop_url = get_permalink($post->ID);
            $wp_domain = get_site_url();
            $current_user = wp_get_current_user()->display_name;
            $project_details = Sortd_Helper::get_cached_project_details();
            $project_slug = $project_details->data->slug;
            $slug = $post->post_name; 

            $article_url_redirect = get_option('sortd_'.$project_id.'_article_url_redirection_flag');
			$shorts_cat = get_option('sortd_shorts_catid_'.$project_id);
			if($article_url_redirect === 1 || $article_url_redirect === '1' || $article_url_redirect === true || $article_url_redirect === 'true') {
				$article_cat = get_the_category($post->ID);
				$primary_category_id = get_post_meta($post->ID, '_yoast_wpseo_primary_category', true);
				if (isset($primary_category_id) && !empty($primary_category_id)) {
					$first_cslug = get_category($primary_category_id)->slug; 
				} else {
					$first_cslug = $article_cat[0]->slug;
				}

				if ((isset($shorts_cat) && !empty($shorts_cat)) && has_category($shorts_cat, $post->ID)) {
					$redirect_uri =  $host_name."/shorts/".$slug."/".$post->ID;
				} else {
					$redirect_uri =  $host_name."/article/".$first_cslug."/".$slug."/".$post->ID;
				} 
					
			} else {

				if ((isset($shorts_cat) && !empty($shorts_cat)) && has_category($shorts_cat, $post->ID)) {
					$redirect_uri =  $host_name."/shorts/".$slug."/".$post->ID;
				} else {
					$redirect_uri =  $host_name."/article/".$slug."/".$post->ID;
				} 
					
			}

               switch ($name) {

                   case 'sortd_action':

                       $poststatus = get_post_status($post->ID);
                       if($poststatus === 'publish'){

                    $views =   get_post_meta($post->ID, 'sortd_'.$project_id.'_post_article_id',true);
                    $sync_failed = get_post_meta($post->ID,'sortd_'.$project_id.'_sync_error_message',true);
                    $webstory_status = get_post_meta($post->ID,'sortd_sync_web_story'.$project_id,true);
                    $paid_article_price = 0.00;
                    $paid_price = 0.00;
                    $paid_article_price = get_post_meta($post->ID, "sortd-paid-price".$project_id,true);
                    $paid_price = $paid_article_price;
                    if(empty($views)){
                        $paid_price = 0.00;
                    }


                    if(!empty($paid_article_price)){
                        $str='';
                    } else {
                        $str = "display:none;";
                    }

                    
                    echo '<input type="hidden" id="input_nonce" value="' . esc_attr(wp_create_nonce(SORTD_NONCE)) . '">';
                    $qr_code_wb = rawurlencode(get_permalink($post->ID));
                    $qr_code_webstory = "https://quickchart.io/qr?text={$qr_code_wb}";

                    if(isset($webstory_status)  && $webstory_status === "1" && ($post->post_type === 'web-story' || $post->post_type === 'makestories_story')){

                        echo '<button type="button" data-wbURL="'.esc_url(get_permalink($post->ID)).'"  data-action="synced" class="synImgH def-Btn  unsyncBtnIcnWebstory webstory_action syncwebstory'.esc_attr($post->ID).'"  data-popid="'.esc_attr($post->ID).'"  data-host="'.esc_attr($host_name) .'"  data-site_url ="'.esc_url(site_url()).'" data-admin_url ="'.esc_url(admin_url()).'"   data-popid="'.esc_attr($post->ID).'"  data-post_data="'.esc_attr($post->post_title).'" data-postname="'.esc_attr($post->post_name).'" data-qrcode="'.esc_url($qr_code_webstory).'" data-toggle="modal" data-target="#myModal" data-dynamicpath="'.wp_kses_data(SORTD_CSS_URL).'">
                         <img class= "imgunsync'.esc_attr($post->ID).'" width="58px" src="'.wp_kses_data(SORTD_CSS_URL).'/yellow_sync.png" style="display:none;">
                          <img class= "imgsync'.esc_attr($post->ID).'" width="58px"  src="'.wp_kses_data(SORTD_CSS_URL).'/green_sync.png"></h4>


                            </button>';



                                echo '<div class="modalclassdynamic"></div>
                   <input type="hidden" id="nonce_input" value="' . esc_attr(wp_create_nonce(SORTD_NONCE)) . '">
                   <div class="modal fade" id="server_msg_modal_'.esc_attr($post->ID).'">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body"  style="color:red;">

                        </div>
                        </div>
                    </div>
                    </div>';

                    } else if(isset($webstory_status)  && $webstory_status === "0" && ($post->post_type === 'web-story' || $post->post_type === 'makestories_story')){

                           echo '<button type="button" data-wbURL="'.esc_url(get_permalink($post->ID)).'" class="synImgH webstory_action def-Btn syncBtnWebstory syncwebstory'.esc_attr($post->ID).'" data-action="unsynced"  data-str='.esc_attr($str).'  data-popid="'.esc_attr($post->ID).'" data-host="'.esc_attr($host_name) .'"  data-site_url ="'.esc_url(site_url()).'" data-admin_url ="'.esc_url(admin_url()).'"  data-popid="'.esc_attr($post->ID).'"  data-post_data="'.esc_attr($post->post_title).'" data-postname="'.esc_attr($post->post_name).'" data-qrcode="'.esc_url($qr_code_webstory).'" data-toggle="modal" data-target="#myModal" data-dynamicpath="'.wp_kses_data(SORTD_CSS_URL).'">
                             <img class= "imgunsync'.esc_attr($post->ID).'" width="58px" src="'.wp_kses_data(SORTD_CSS_URL).'/yellow_sync.png" >
                             <img class= "imgsync'.esc_attr($post->ID).'" width="58px" style="display:none;"  src="'.wp_kses_data(SORTD_CSS_URL).'/green_sync.png"></h4>

                                </button>';


                                echo '<div class="modalclassdynamic"></div>
                   <input type="hidden" id="nonce_input" value="' . esc_attr(wp_create_nonce(SORTD_NONCE)) . '">
                   <div class="modal fade" id="server_msg_modal_'.esc_attr($post->ID).'">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body"  style="color:red;">

                        </div>
                        </div>
                    </div>
                    </div>';

                    } else if(isset($webstory_status)  && $webstory_status !== "0"  && $webstory_status !== "1" && ($post->post_type === 'web-story' || $post->post_type === 'makestories_story')){

                        echo '<button type="button" data-wbURL="'.esc_url(get_permalink($post->ID)).'" class="synImgH webstory_action def-Btn syncBtnWebstory syncwebstory'.esc_attr($post->ID).'" data-action="unsynced"  data-str='.esc_attr($str).'  data-popid="'.esc_attr($post->ID).'" data-host="'.esc_attr($host_name) .'"  data-site_url ="'.esc_url(site_url()).'" data-admin_url ="'.esc_url(admin_url()).'"  data-popid="'.esc_attr($post->ID).'"  data-post_data="'.esc_attr($post->post_title).'" data-postname="'.esc_attr($post->post_name).'" data-qrcode="'.esc_url($qr_code_webstory).'" data-toggle="modal" data-target="#myModal" data-dynamicpath="'.wp_kses_data(SORTD_CSS_URL).'">
                             <img class= "imgunsync'.esc_attr($post->ID).'" width="58px" src="'.wp_kses_data(SORTD_CSS_URL).'/yellow_sync.png" >
                             <img class= "imgsync'.esc_attr($post->ID).'" width="58px" style="display:none;"  src="'.wp_kses_data(SORTD_CSS_URL).'/green_sync.png"></h4>

                                </button>';

                        echo '<div class="modalclassdynamic"></div>
                   <input type="hidden" id="nonce_input" value="' . esc_attr(wp_create_nonce(SORTD_NONCE)) . '">
                   <div class="modal fade" id="server_msg_modal_'.esc_attr($post->ID).'">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body"  style="color:red;">

                        </div>
                        </div>
                    </div>
                    </div>';
                    } else {


                    if(isset($sync_failed) && !empty($sync_failed)){
                        $sync_failed_flag = 1;
                    } else {
                        $sync_failed_flag = 0;
                    }

                    echo '<span id="bulk'.esc_attr($post->ID).'"></span>';



                if(!empty($views) && $sync_failed_flag === 0){

                    echo '<button data-current_user="'.esc_attr($current_user).'" data-wp_domain="'.esc_url($wp_domain).'" data-project_slug="'.esc_attr($project_slug).'" data-desktop_url="'.esc_url($desktop_url).'" data-mob_url="'.esc_url($redirect_uri).'" data-post_type="'.esc_attr($post_type).'" type="button" id= "action_sortd_btn'.esc_attr($post->ID).'" data-action="synced" data-str="'.esc_attr($str).'" class="btn action_sortd_btn" data-paid_article_price = "'.esc_attr($paid_article_price).'" data-host="'.esc_attr($host_name) .'" data-paid_price="'.esc_attr($paid_price).'" data-site_url ="'.esc_url(site_url()).'" data-admin_url ="'.esc_url(admin_url()).'"  data-data_paoid="'.esc_attr($data_paoid).'" data-popid="'.esc_attr($post->ID).'"  data-post_data="'.esc_attr($post->post_title).'" data-postname="'.esc_attr($post->post_name).'" data-qrcode="'.esc_url($qr_code).'" data-toggle="modal" data-target="#myModal" data-dynamicpath="'.wp_kses_data(SORTD_CSS_URL).'">
                        <img class= "imgsync'.esc_attr($post->ID).'"  width="58px" src="'.wp_kses_data(SORTD_CSS_URL).'/green_sync.png">
                        <img class= "imgunsync'.esc_attr($post->ID).'" width="58px" style="display:none;"  width=60%" src="'.wp_kses_data(SORTD_CSS_URL).'/yellow_sync.png"></h4>
                        </button>';






                } else {

                        $sortd_cat_flag = Sortd_Helper::check_article_sortd_category($post->ID);

                        if($sortd_cat_flag === 1 && $views !== 1 && $sync_failed_flag === 0){
                            echo '<button type="button" data-current_user="'.esc_attr($current_user).'" data-wp_domain="'.esc_url($wp_domain).'" data-project_slug="'.esc_attr($project_slug).'" data-desktop_url="'.esc_url($desktop_url).'" data-post_type="'.esc_attr($post_type).'" data-mob_url="'.esc_url($redirect_uri).'" id= "action_sortd_btn'.esc_attr($post->ID).'" data-action="unsynced" class="btn action_sortd_btn" data-str='.esc_attr($str).'  data-popid="'.esc_attr($post->ID).'" data-paid_article_price = "'.esc_attr($paid_article_price).'" data-host="'.esc_attr($host_name) .'" data-paid_price="'.esc_attr($paid_price).'" data-site_url ="'.esc_url(site_url()).'" data-admin_url ="'.esc_url(admin_url()).'"  data-data_paoid="'.esc_attr($data_paoid).'" data-popid="'.esc_attr($post->ID).'"  data-post_data="'.esc_attr($post->post_title).'" data-postname="'.esc_attr($post->post_name).'" data-qrcode="'.esc_url($qr_code).'" data-toggle="modal" data-target="#myModal" data-dynamicpath="'.wp_kses_data(SORTD_CSS_URL).'">
                                <img class= "imgunsync'.esc_attr($post->ID).'" width="58px" src="'.wp_kses_data(SORTD_CSS_URL).'/yellow_sync.png">
                                <img class= "imgsync'.esc_attr($post->ID).'" style="display:none;" width="58px"  src="'.wp_kses_data(SORTD_CSS_URL).'/green_sync.png"></h4>
                                </button>';
                        } else {
                            if($sortd_cat_flag === 1){

                                echo '<button type="button" data-current_user="'.esc_attr($current_user).'" data-wp_domain="'.esc_url($wp_domain).'" data-project_slug="'.esc_attr($project_slug).'" data-desktop_url="'.esc_url($desktop_url).'" data-mob_url="'.esc_url($redirect_uri).'" data-post_type="'.esc_attr($post_type).'" id= "action_sortd_btn'.esc_attr($post->ID).'" data-action="failed" class="btn action_sortd_btn" data-popid="'.esc_attr($post->ID).'" data-str='.esc_attr($str).'   data-paid_article_price = "'.esc_attr($paid_article_price).'" data-host="'.esc_attr($host_name) .'" data-paid_price="'.esc_attr($paid_price).'" data-site_url ="'.esc_url(site_url()).'" data-admin_url ="'.esc_url(admin_url()).'"  data-data_paoid="'.esc_attr($data_paoid).'" data-popid="'.esc_attr($post->ID).'"  data-post_data="'.esc_attr($post->post_title).'" data-postname="'.esc_attr($post->post_name).'" data-qrcode="'.esc_url($qr_code).'" data-toggle="modal" data-target="#myModal" data-dynamicpath="'.wp_kses_data(SORTD_CSS_URL).'">
                                <img class= "imgunsync'.esc_attr($post->ID).'" width="58px" src="'.wp_kses_data(SORTD_CSS_URL).'/red_sync.png">
                                <img class= "imgsync'.esc_attr($post->ID).'" style="display:none;" width="58px" src="'.wp_kses_data(SORTD_CSS_URL).'/green_sync.png"></h4>
                                </button>';

                            } else {

                                echo '<button type="button" data-current_user="'.esc_attr($current_user).'" data-wp_domain="'.esc_url($wp_domain).'" data-project_slug="'.esc_attr($project_slug).'" data-post_type="'.esc_attr($post_type).'" data-desktop_url="'.esc_url($desktop_url).'" data-mob_url="'.esc_url($redirect_uri).'" data-cat="cat_synced" id= "action_sortd_btn'.esc_attr($post->ID).'" class="btn action_sortd_btn" data-str='.esc_attr($str).'  data-popid="'.esc_attr($post->ID).'"  data-paid_article_price = "'.esc_attr($paid_article_price).'" data-host="'.esc_attr($host_name).'" data-paid_price="'.esc_attr($paid_price).'" data-site_url ="'.esc_url(site_url()).'" data-admin_url ="'.esc_url(admin_url()).'"  data-data_paoid="'.esc_attr($data_paoid).'" data-popid="'.esc_attr($post->ID).'"  data-post_data="'.esc_attr($post->post_title).'" data-postname="'.esc_attr($post->post_name).'" data-qrcode="'.esc_url($qr_code).'" data-toggle="modal" data-target="#myModal" data-dynamicpath="'.wp_kses_data(SORTD_CSS_URL).'">
                                <img class= "imgunsync'.esc_attr($post->ID).'" width="58px" src="'.wp_kses_data(SORTD_CSS_URL).'/grey.png">
                                <img class= "imgsync'.esc_attr($post->ID).'" style="display:none;"  width="58px" src="'.wp_kses_data(SORTD_CSS_URL).'/green_sync.png"></h4>
                                </button>';

                            }
                        }
                }
                   echo '<div class="modalclassdynamic"></div>
                   <input type="hidden" id="nonce_input" value="' . esc_attr(wp_create_nonce(SORTD_NONCE)) . '">
                   <div class="modal fade" id="server_msg_modal_'.esc_attr($post->ID).'">
                    <div class="modal-dialog">
                        <div class="modal-content">
                        <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                        </div>
                        <div class="modal-body"  style="color:red;">

                        </div>
                        </div>
                    </div>
                    </div>';
            }


                }
                break;

            }

    }

           /**
     *  function to display relevant screen as per sortd setup
     *
     * @since    2.0.0
     */
    public function display_relevant_screen() {



        $credentials = Sortd_Helper::get_credentials_values();
        $project_id = Sortd_Helper::get_project_id();

        $template_oneclick_flag = get_option('sortd_saved_template_and_oneclick'.$project_id);
     $sortd_oneclick_flag  = get_option('sortd_oneclick_flag'.$project_id);
        if(!empty($credentials) && $template_oneclick_flag === '1' && (bool)$sortd_oneclick_flag === false){
            //Oneclick Setup
            $plugin_sortd_oneclick = new Sortd_Oneclick($this->sortd, $this->version, $this->loader);
            $plugin_sortd_oneclick->one_click_setup();

        } else if(!empty($credentials) && empty($template_oneclick_flag)){
            // manage templates

            $plugin_sortd_templates = new Sortd_Templates($this->sortd, $this->version, $this->loader);
            $plugin_sortd_templates->manage_templates();

        }else if(!empty($credentials) && (bool)$sortd_oneclick_flag === true){
            //home dashboard
            $plugin_sortd_dashboard = new Sortd_Dashboard($this->sortd, $this->version, $this->loader);
            $plugin_sortd_dashboard->home_dashboard();

        } else {
             global $wp_version;
             if($wp_version < '5.3'){
                 $view_data = array();
                 Sortd_Helper::render_partials(array('version-not-supported'), $view_data);
             } else {
                 $pluginactivationflag = get_option('activate_sortd');
                 if($pluginactivationflag === '1'){
                     update_option('activate_sortd',0);
                 }
                 $console_url = Sortd_Helper::get_pubconsole_url();
                 $view_data = array();
                 $view_data['console_url'] = $console_url;
                 Sortd_Helper::render_partials(array('sortd-oneclick-get-started'), $view_data);
             }


        }

}

     /**
     *  function to add redirection code on enable redirection
     *
     * @since    2.0.0
     */

    public function add_sortd_redirection_code(){
            $plugin_sortd_redirection = new Sortd_Redirection($this->sortd, $this->version, $this->loader);
            $plugin_sortd_redirection->add_redirection_amp_code();
    }

     /**
     *  function to sync article with claasic editor
     *
     * @since    2.0.0
     */

    public function sync_with_classic_editor($post_id,$post){
        $project_id = Sortd_Helper::get_project_id();
        $synced_flag =  get_post_meta( $post_id,'sortd_'.$project_id.'_post_sync', true);


       if( !metadata_exists('post', $post_id, 'sortd_'.$project_id.'_post_sync')){

            if($post->post_type !== 'web-story'){
                Sortd_Article::sync_article($post_id,$post);
            }
       } else if($post->post_type !== 'web-story' && ($synced_flag === '1')){
            Sortd_Article::sync_article($post_id,$post);
        }

    }
     /**
     *  function to sync article with gutenberg editor
     *
     * @since    2.0.0
     */

     public function sync_with_gutenberg_editor($post_id,$post){
        $project_id = Sortd_Helper::get_project_id();
        $synced_flag =  get_post_meta( $post_id,'sortd_'.$project_id.'_post_sync', true);
        if($post->post_type !== 'web-story' && $post->post_status === 'publish' && ($synced_flag === '1')){
            Sortd_Article::sync_article($post_id,$post);
        }

    }


       /**
     *  function to show bulk sync btn
     *
     * @since    2.0.0
     */

    public function add_bulk_sync_btn($views){

        $wp_domain = get_site_url();
        $current_user = wp_get_current_user()->display_name;
        $project_details = Sortd_Helper::get_cached_project_details();
        $project_slug = $project_details->data->slug;

        $views['sortd_bulk_sync'] = '<button class="sortdbulkaction btn-primary" data-current_user="'.esc_attr($current_user).'" data-wp_domain="'.esc_attr($wp_domain).'" data-project_slug="'.esc_attr($project_slug).'">Sortd Sync</button><img class="bulkactionloader" src="'.plugin_dir_url( __DIR__ ).'admin/css/load.gif" width="30px" style="margin-left: 20px;display:none"><input type="hidden" class="hiddenadminur" value="'.admin_url().'"><span class="bulk_validation" style="color:red;display:none;margin-left:20px">Select Posts to sortd sync</span>';
        $views['sortd_bulk_unsync'] = '<button class="sortdbulkactionunsync btn-warning" data-current_user="'.esc_attr($current_user).'" data-wp_domain="'.esc_attr($wp_domain).'" data-project_slug="'.esc_attr($project_slug).'">Sortd UnSync</button><img class="bulkactionloaderunysnc" src="'.plugin_dir_url( __DIR__ ).'admin/css/load.gif" width="30px" style="margin-left: 20px;display:none"><input type="hidden" class="hiddenadminur" value="'.admin_url().'"><span class="bulk_validation_unsync" style="color:red;display:none;margin-left:20px">Select Posts to sortd unsync</span>';

            
           return $views;

    }


    public function get_sortd_categories(){

        $screen = get_current_screen();



        if( 'post' === $screen->post_type && 'edit' === $screen->base && $screen->id === 'edit-post' ){

            $project_details = Sortd_Helper::get_cached_project_details();
            $project_id       = get_option('sortd_projectid');
            $value_op = $project_details->data->paidarticle_enabled;

            update_option('sortd_is_paid'.$project_id,$value_op);


        }

    }


      /**
     *  function to show bulk sync btn for webstory
     *
     * @since    2.0.0
     */

    public function add_bulk_sync_btn_webstory($views){

        $views['sortd_bulk_sync_webstory'] = '<button class="bulksyncwb btn-primary">Sortd Sync</button><img class="bulkactionloaderwb" src="'.plugin_dir_url( __DIR__ ).'admin/css/load.gif" width="30px" style="margin-left: 20px;display:none"><input type="hidden" class="hiddenadminur" value="'.admin_url().'"><span class="bulk_validation_wb" style="color:red;display:none;margin-left:20px">Select Posts to sortd sync</span>';
        $views['sortd_bulk_unsync_webstory'] = '<button class="bulkunsyncwb btn-warning">Sortd UnSync</button><img class="bulkactionloaderunysncwb" src="'.plugin_dir_url( __DIR__ ).'admin/css/load.gif" width="30px" style="margin-left: 20px;display:none"><input type="hidden" class="hiddenadminur" value="'.admin_url().'"><span class="bulk_validation_unsync_wb" style="color:red;display:none;margin-left:20px">Select Posts to sortd unsync</span>';
           return $views;

    }

         /**
     *  function to get wordpress notice message
     *
     * @since    2.0.0
     */


    public function sortd_get_admin_notices(){
        $plugin_sortd_utils = new Sortd_Utils($this->sortd, $this->version, $this->loader);
        $plugin_sortd_utils->general_admin_notice();

    }

    public function delete_wordpress_cat($tt_id){
        $plugin_sortd_category = new Sortd_Categories($this->sortd, $this->version, $this->loader);
        $plugin_sortd_category->delete_category($tt_id);
    }

    public function send_email_on_activation(){
        $plugin_sortd_utils = new Sortd_Utils($this->sortd, $this->version, $this->loader);
        $plugin_sortd_utils->plugin_activation();
    }

    public function my_trash_post_function($post){
        $plugin_sortd_article = new Sortd_Article($this->sortd, $this->version, $this->loader);
        $plugin_sortd_article->trash_post_function($post);
    }



    public function update_post_meta_thumbnail_data( $meta_id, $post_id, $meta_key, $meta_value ){

        $project_id = Sortd_Helper::get_project_id();

        $synced_flag =  get_post_meta( $post_id,'sortd_'.$project_id.'_post_sync', true);



            if($meta_key === '_thumbnail_id' ){
                $post = get_post($post_id);
                if($post->post_status === 'publish' && $post->post_type === 'web-story'){
                    Sortd_Article::sync_webstories($post_id,$post);
                }
            }

            if ($meta_key === 'web_stories_poster') {
                $post = get_post($post_id);
                if($post->post_status === 'publish' && $post->post_type === 'web-story') {
                    Sortd_Article::sync_webstories($post_id,$post);
                }
            }

        if($synced_flag === '1' || (!metadata_exists('post', $post_id, 'sortd_'.$project_id.'_post_sync'))){
            if($meta_key === '_thumbnail_id' ){
                $post = get_post($post_id);
                if($post->post_status === 'publish' && $post->post_type !== 'web-story'){
                    Sortd_Article::sync_article($post_id,$post);
                }
            }
            if($meta_key === 'sortd-paid-price'.$project_id ){
                $post = get_post($post_id);
                if($post->post_status === 'publish' && $post->post_type !== 'web-story'){
                    Sortd_Article::sync_article($post_id,$post);
                }
            }

            if($meta_key === 'td_post_video' ){
                $post = get_post($post_id);
                if($post->post_status === 'publish'){
                    Sortd_Article::sync_article($post_id,$post);
                }
            }

            if($meta_key === '_yoast_wpseo_metadesc' ){
                $post = get_post($post_id);
                if($post->post_status === 'publish'){

                    Sortd_Article::send_meta_data_for_article($post_id);
                }
            }

            if($meta_key === '_yoast_wpseo_title' ){
                $post = get_post($post_id);
                if($post->post_status === 'publish'){

                    Sortd_Article::send_meta_data_for_article($post_id);
                }
            }

            if($meta_key === '_seopress_titles_title' ){
                $post = get_post($post_id);
                if($post->post_status === 'publish'){

                    Sortd_Article::send_meta_data_for_article($post_id);
                }
            }

            if($meta_key === '_seopress_titles_desc' ){
                $post = get_post($post_id);
                if($post->post_status === 'publish'){

                    Sortd_Article::send_meta_data_for_article($post_id);
                }
            }

            if($meta_key === '_seopress_analysis_target_kw' ){
                $post = get_post($post_id);
                if($post->post_status === 'publish'){
                    Sortd_Article::send_meta_data_for_article($post_id);
                }
            }

            if($meta_key === '_seopress_titles_title' ){
                $post = get_post($post_id);
                if($post->post_status === 'publish'){

                    Sortd_Article::send_meta_data_for_article($post_id);
                }
            }

            if($meta_key === '_seopress_titles_desc' ){
                $post = get_post($post_id);
                if($post->post_status === 'publish'){

                    Sortd_Article::send_meta_data_for_article($post_id);
                }
            }

            if($meta_key === '_seopress_analysis_target_kw' ){
                $post = get_post($post_id);
                if($post->post_status === 'publish'){

                    Sortd_Article::send_meta_data_for_article($post_id);
                }
            }

            if($meta_key === 'publisher_details'){
                $post = get_post($post_id);
                if($post->post_status === 'publish'){
                    Sortd_Article::sync_webstories($post_id,$post);
                }
            }

            if($meta_key === 'xs_review_overview_settings'){

                $post = get_post($post_id);
                if($post->post_status === 'publish'){
                    Sortd_Article::sync_article($post_id,$post);
                }
            }


           if($meta_key === '_jwppp-video-url-1' || $meta_key === '_jwppp-video-url-2' || $meta_key === '_jwppp-video-url-3' ){

                $post = get_post($post_id);
                if($post->post_status === 'publish'){
                    Sortd_Article::sync_article($post_id,$post);
                }
            }

            if($meta_key === '_molongui_author') {
                $post = get_post($post_id);
                if($post->post_status === 'publish'){
                    Sortd_Article::sync_article($post_id,$post);
                }
            }

            if($meta_key === '_cc_featured_image_caption') {
                $post = get_post($post_id);
                if($post->post_status === 'publish'){
                    Sortd_Article::sync_article($post_id,$post);
                }
            }

            if ($meta_key === 'web_stories_poster') {
                $post = get_post($post_id);
                if($post->post_status === 'publish' && $post->post_type === 'web-story') {
                    Sortd_Article::sync_webstories($post_id,$post);
                }
            }

            if($meta_key === 'rank_math_title' ){
                $post = get_post($post_id);
                if($post->post_status === 'publish'){
                    Sortd_Article::send_meta_data_for_article($post_id);
                }
            }
            if($meta_key === 'rank_math_description' ){
                $post = get_post($post_id);
                if($post->post_status === 'publish'){
                    Sortd_Article::send_meta_data_for_article($post_id);
                }
            }
            if($meta_key === 'rank_math_focus_keyword' ){
                $post = get_post($post_id);
                if($post->post_status === 'publish'){
                    Sortd_Article::send_meta_data_for_article($post_id);
                }
            }

            if($meta_key === 'blog_end_time' ){
                $post = get_post($post_id);
                if($post->post_status === 'publish'){
                    Sortd_Article::sync_article($post_id,$post);
                }
            }

            if($meta_key === '_yoast_wpseo_primary_category' ){
                $post = get_post($post_id);
                if($post->post_status === 'publish'){
                    Sortd_Article::sync_article($post_id,$post);
                }
            }

        }

    }

    public function update_post_meta_seo_data( $meta_id, $post_id, $meta_key, $meta_value ){

        $project_id = Sortd_Helper::get_project_id();

        $synced_flag =  get_post_meta($post_id,'sortd_'.$project_id.'_post_sync', true);

        $webstory_sync_flag = get_post_meta($post_id, "sortd_sync_web_story".$project_id, true);

        if($webstory_sync_flag === '1' || (!metadata_exists('post', $post_id,"sortd_sync_web_story".$project_id))) {
            if($meta_key === '_thumbnail_id' ){
                $post = get_post($post_id);
                if($post->post_status === 'publish' && $post->post_type === 'web-story'){
                    Sortd_Article::sync_webstories($post_id,$post);
                }
            }

            if ($meta_key === 'web_stories_poster') {
                $post = get_post($post_id);
                if($post->post_status === 'publish' && $post->post_type === 'web-story') {
                    Sortd_Article::sync_webstories($post_id,$post);
                }
            }
        }

        if($synced_flag === '1' || (!metadata_exists('post', $post_id, 'sortd_'.$project_id.'_post_sync'))){
            if($meta_key === '_thumbnail_id' ){
                $post = get_post($post_id);
                if($post->post_status === 'publish' && $post->post_type !== 'web-story'){
                    Sortd_Article::sync_article($post_id,$post);
                }
            }



            $project_id = Sortd_Helper::get_project_id();
            if($meta_key === 'sortd-paid-price'.$project_id ){
                $post = get_post($post_id);
                if($post->post_status === 'publish' && $post->post_type !== 'web-story'){
                    Sortd_Article::sync_article($post_id,$post);
                }
            }

            if($meta_key === 'td_post_video' ){
                $post = get_post($post_id);
                if($post->post_status === 'publish'){
                    Sortd_Article::sync_article($post_id,$post);
                }
            }

            if($meta_key === '_yoast_wpseo_title' ){
                $post = get_post($post_id);
                if($post->post_status === 'publish'){
                    Sortd_Article::send_meta_data_for_article($post_id);
                }
            }

            if($meta_key === '_yoast_wpseo_metadesc' ){
                $post = get_post($post_id);
                if($post->post_status === 'publish'){
                    Sortd_Article::send_meta_data_for_article($post_id);
                }
            }

            if($meta_key === '_yoast_wpseo_focuskw' ){
                $post = get_post($post_id);
                if($post->post_status === 'publish'){
                    Sortd_Article::send_meta_data_for_article($post_id);
                }
            }


            if($meta_key === '_seopress_titles_title' ){
                $post = get_post($post_id);
                if($post->post_status === 'publish'){

                    Sortd_Article::send_meta_data_for_article($post_id);
                }
            }

            if($meta_key === '_seopress_titles_desc' ){
                $post = get_post($post_id);
                if($post->post_status === 'publish'){

                    Sortd_Article::send_meta_data_for_article($post_id);
                }
            }

            if($meta_key === '_seopress_analysis_target_kw' ){
                $post = get_post($post_id);
                if($post->post_status === 'publish'){

                    Sortd_Article::send_meta_data_for_article($post_id);
                }
            }

            if($meta_key === '_seopress_titles_desc' ){
                $post = get_post($post_id);
                if($post->post_status === 'publish'){

                    Sortd_Article::send_meta_data_for_article($post_id);
                }
            }

            if($meta_key === '_seopress_analysis_target_kw' ){
                $post = get_post($post_id);
                if($post->post_status === 'publish'){

                    Sortd_Article::send_meta_data_for_article($post_id);
                }
            }

            if($meta_key === 'xs_review_overview_settings'){

                $post = get_post($post_id);
                if($post->post_status === 'publish'){
                    Sortd_Article::sync_article($post_id,$post);
                }
            }

             if($meta_key === '_jwppp-video-url-1' || $meta_key === '_jwppp-video-url-2' || $meta_key === '_jwppp-video-url-3' ){

                $post = get_post($post_id);
                if($post->post_status === 'publish'){
                    Sortd_Article::sync_article($post_id,$post);
                }
            }

            if(is_plugin_active('seo-by-rank-math/rank-math.php')) {
                if($meta_key === 'rank_math_title' ){
                    $post = get_post($post_id);
                    if($post->post_status === 'publish'){
                        Sortd_Article::send_meta_data_for_article($post_id);
                    }
                }
                if($meta_key === 'rank_math_description' ){
                    $post = get_post($post_id);
                    if($post->post_status === 'publish'){
                        Sortd_Article::send_meta_data_for_article($post_id);
                    }
                }
                if($meta_key === 'rank_math_focus_keyword' ){
                    $post = get_post($post_id);
                    if($post->post_status === 'publish'){
                        Sortd_Article::send_meta_data_for_article($post_id);
                    }
                }
            }

            if($meta_key === '_cc_featured_image_caption') {
                $post = get_post($post_id);
                if($post->post_status === 'publish'){
                    Sortd_Article::sync_article($post_id,$post);
                }
            }

            if ($meta_key === 'web_stories_poster') {
                $post = get_post($post_id);
                if($post->post_status === 'publish' && $post->post_type === 'web-story') {
                    Sortd_Article::sync_webstories($post_id,$post);
                }
            }

            if($meta_key === 'blog_end_time' ){
                $post = get_post($post_id);
                if($post->post_status === 'publish'){
                    Sortd_Article::sync_article($post_id,$post);
                }
            }
        }

    }

    public function sync_custom_posts($new_status, $old_status, $post){

            if('publish' === $new_status){

                if(isset($post) && empty($post)){
                    $post_id  = get_the_ID();
                } else {
                    $post_id  = $post->ID;
                }

                $project_id = Sortd_Helper::get_project_id();
                $synced_flag =  get_post_meta( $post_id,'sortd_'.$project_id.'_post_sync', true);

                if( !metadata_exists('post', $post_id, 'sortd_'.$project_id.'_post_sync')){
                    if($post->post_type !== 'web-story' && $post->post_type !== 'makestories_story'){
                        $this->tag_synced_post_publish($post_id);
                    }
                } else if($post->post_type !== 'web-story'  && $post->post_type !== 'makestories_story' && ($synced_flag === '1')){
                    $this->tag_synced_post_publish($post_id);
                }

                if(($post->post_type === 'web-story' ||  $post->post_type === 'makestories_story')) {
                    Sortd_Article::sync_webstories($post_id,$post);
                }
            }
    }



    public function unsync_webstory($post_id){
        global $post;
        if($post->post_status === 'publish' && ($post->post_type === 'web-story' ||  $post->post_type === 'makestories_story')) {
            Sortd_Article::unsync_webstory($post_id);
        } else if($post->post_status === 'publish' && ($post->post_type === 'post')){
            $this->my_trash_post_function($post);
        }
    }

    public function get_data($post_id){
        global $post;
        if($post->post_status === 'publish'){
            Sortd_Article::send_meta_data_for_article($post_id);
        }
    }



    public function get_all_term_children( $term, $taxonomy ){
        if ( is_wp_error( get_term_children( $term->term_id, $taxonomy ) ) ) {
            return;
        }

        get_term_children( $term->term_id, $taxonomy );


    }


    public function get_cat_data(){
        $all_terms = array();
        $taxonomy = 'category';
        $parent_args = [
            'taxonomy'     => $taxonomy,
            'parent'        => 0,
            'hide_empty'    => false
        ];
        $parent_terms = get_terms( $parent_args );

        foreach ( $parent_terms as $parent_term ) {
            $all_terms[ $parent_term->term_id ] = $this->get_all_term_children( $parent_term, $taxonomy );
        }

    }


    function custom_meta_box_markup($object)
    {
        wp_nonce_field(basename(__FILE__), "meta-box-nonce");
        $project_id = Sortd_Helper::get_project_id();
        $curr = get_post_meta($object->ID, "sortd-paid-currency".$project_id, true);

        $curr = 'INR';

        ?>
    <div class="sortd_rup">
    <div class="paid-price-currency">
        <span><?php echo wp_kses_data($curr); ?></span>
    </div>
    <div class="paid-price">
        <label for="meta-box-text">Enter Price</label>
        <input id="meta-box-text" name="meta-box-text" type="number" value="<?php echo wp_kses_data(get_post_meta($object->ID, "sortd-paid-price".$project_id, true)); ?>">
        <span id="lblError" style="color:#8B0000;"></span>
    </div>
    </div>
    <?php
    }

    function save_custom_meta_box($post_id, $post, $update)
    {
            if (!isset($_POST["meta-box-nonce"]) || !wp_verify_nonce(sanitize_text_field($_POST["meta-box-nonce"]), basename(__FILE__)))
                return $post_id;
            if(!current_user_can("edit_post", $post_id))
                return $post_id;
            if(defined("DOING_AUTOSAVE") && DOING_AUTOSAVE)
                return $post_id;

            $meta_box_text_value = "";
            $project_id = Sortd_Helper::get_project_id();
            if(isset($_POST["meta-box-text"]))
            {
                if($_POST["meta-box-text"] === "0"){
                    $meta_box_text_value = "";
                } else{
                    $meta_box_text_value = sanitize_text_field($_POST["meta-box-text"]);
                }
            }
            update_post_meta($post_id, "sortd-paid-price".$project_id, $meta_box_text_value);

            $old_price =  get_post_meta($post_id,'sortd_'.$project_id.'_new_price');

            if(empty($old_price) ){
                $old_price = 0;
            }

            if( empty($new_price)){
                $new_price = 0;
            }

            $new_price = $meta_box_text_value;
            update_post_meta($post_id,'sortd_'.$project_id.'_old_price',$old_price);
            update_post_meta($post_id,'sortd_'.$project_id.'_new_price',$new_price);


            if(isset($_POST["meta-box-dropdown"]))
            {
                $meta_box_dropdown_value = sanitize_text_field($_POST["meta-box-dropdown"]);
            }
            update_post_meta($post_id, "sortd-paid-currency".$project_id, $meta_box_dropdown_value);
    }

    function add_custom_meta_box()
    {
        $project_details = Sortd_Helper::get_cached_project_details();

        if($project_details->data->paidarticle_enabled === true){
            add_meta_box("demo-meta-box", "Sortd Paid Subscription", array($this,"custom_meta_box_markup"), "post", "side", "high", null);

            $custom_posts = get_option('sortd_customposts');
            if (!empty($custom_posts)) {
                foreach ($custom_posts as $custom_post) {
                     add_meta_box("demo-meta-box", "Sortd Paid Subscription", array($this,"custom_meta_box_markup"), $custom_post, "side", "high", null);
                }
            }
        }
    }

    /**
     *  function to get author info
     *
     * @since    2.2.1
     */

    public function sync_author_info($user_id){
        $sortd_article = new Sortd_Article($this->sortd, $this->version, $this->loader);
        $sortd_article->sync_author_data($user_id);
    }


     /**
     *  function to remove author info
     *
     * @since    2.2.1
     */

    public function remove_author_data($user_id){
        $sortd_article = new Sortd_Article($this->sortd, $this->version, $this->loader);
        $sortd_article->remove_author($user_id);

    }


    /**
     *  function to remove author info
     *
     * @since    2.2.1
     */

    public function add_cat_column($columns)
    {
        $columns['sortd_category_action'] = 'Sortd Sync';
        return $columns;
    }


    public function manage_cat_columns($string,$column_name,$term_id){

        $term_data = get_term($term_id);
        $parent = $term_data->parent;

        $wp_domain = get_site_url();
            $current_user = wp_get_current_user()->display_name;
            $project_details = Sortd_Helper::get_cached_project_details();
            $project_slug = $project_details->data->slug;


        $project_id = Sortd_Helper::get_project_id();
        $synced_cat_flag = get_option('sortd_'.$project_id.'_category_sync_'.$parent);

        if($parent) {


            $disable = "disabled";

            if($synced_cat_flag === "1"){

                $disable = "";
            }


        } else {
            $disable = "";
        }

        if($column_name === 'sortd_category_action'){

            $html = '
        <label class="switch-tog">
            <input type="checkbox" name="catsortdcheckbox" data-current_user="'.esc_attr($current_user).'" data-wp_domain="'.esc_attr($wp_domain).'" data-project_slug="'.esc_attr($project_slug).'" data-cat_id="'.$term_id.'" data-sync_flag="0" data-parent="'.$parent.'" id="'.$term_id.'" class="categorysync sortCatCheck sortcheckclass'.$term_id.'" data-size="xs" data-on="Synced" data-off="Unsynced"  data-onstyle="primary" data-taxonomytypeslug="'.$term_data->taxonomy.'" '.$disable.'>
            <span class="slider-tog round"></span>

         </label>
         <img style="display:none;" class=" img'.$term_id.'" src="'.SORTD_CSS_URL.'/check.png">
          ';



         $html .= '<div class="modal fade modalcat'.$term_id.'" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="mi-modal" data-catdata="'.$term_id.'">
                <div class="modal-dialog modal-md">
                   <div class="modal-content">

                      <div class="modal-body">
                        <h5>Do you want to unsync all child categories?</h5>
                      </div>
                      <div class="modal-footer">
                      <button type="button" class="btn btn-default" id="modal-btn-si">Yes</button>
                      <button type="button" class="btn btn-primary" id="modal-btn-no">No</button>
                      </div>
                   </div>
                </div>
                </div>';


            return $html;
        }

    }


    public function sync_wp_customer_reviews($post_id){

        $post = get_post($post_id);
        if($post->post_type === 'wpcr3_review'){
            Sortd_Article::_get_wp_customer_reviews($post_id);
        }

    }




    public function add_redirection_blog_link(){
        global $wp;
        $current_url = home_url( $wp->request );
        if($current_url === 'https://www.sortd.mobi/blog/'){
            echo "<script type='text/javascript'>
			var sortd_redirect_uri = 'https://blog.sortd.mobi/';
			if(sortd_redirect_uri != undefined &&  sortd_redirect_uri.length != 0){
				if ((navigator.userAgent.match(/(iphone)|(ipod)|(android)|(blackberry)|(windows phone)|(symbian)/i))) {
					var request_uri = '';
					request_uri = sortd_redirect_uri;
					top.location.href= request_uri;
				} else {

				}
			}
		</script>";
        }

    }


    public function unsync_wb($post){

        $id = $post->ID;
        Sortd_Article::unsync_webstory($id);

    }

    public function get_posts_by_menu_order($reorder_arr) {

        $menu_arr=wp_json_encode($reorder_arr);
        $reorder_api_slug = "article/update-posts-order";
        Sortd_Helper::sortd_post_api_response($reorder_api_slug,$menu_arr);
    }

      /**
     *  function to remove author info
     *
     * @since    2.2.1
     */

    public function add_cat_column_webstory($columns)
    {
        $columns['sortd_webstory_cat_action'] = 'Sortd Sync';
        return $columns;
    }


    public function manage_cat_columns_webstory($string,$column_name,$term_id){

        $term_data = get_term($term_id);
        $parent = $term_data->parent;


        if($column_name === 'sortd_webstory_cat_action'){

            $html = '
        <label class="switch-tog">
            <input type="checkbox" name="webcatcheckname"  data-cat_id="'.$term_id.'" data-sync_flag="0" data-parent="'.$parent.'" id="'.$term_id.'" class="webcatsync  sortcheckclass'.$term_id.'" data-size="xs" data-on="Synced" data-off="Unsynced"  data-onstyle="primary">
            <span class="slider-tog round"></span>

         </label>
         <span style="color:green;display:none;" id="catwbflag_'.$term_id.'">Unsynced</span>
         <img style="display:none;" class=" img'.$term_id.'" src="'.SORTD_CSS_URL.'/check.png">
          ';

            return $html;
        }

    }

    public function new_tag_created($term_id, $taxonomy_id, $taxonomy) {

        if ($taxonomy === 'post_tag') {
            $tag = get_term($term_id, 'post_tag');

            if (!is_wp_error($tag) && $tag !== null) {

              $sortd_article = new Sortd_Article($this->sortd, $this->version, $this->loader);
             $sortd_article->sync_tag($term_id, $taxonomy_id, $taxonomy,$tag);

            }

        }


    }


    // Add custom column to the tag page
    function add_custom_tag_column($columns) {
        $columns['sortd_tag_action'] = 'Sortd Sync';
        return $columns;
    }


    // Populate the custom column with data
    function populate_custom_tag_column($string,$column_name,$term_id) {

            $project_id = get_option('sortd_projectid');

            $wp_domain = get_site_url();
            $current_user = wp_get_current_user()->display_name;
            $project_details = Sortd_Helper::get_cached_project_details();
            $project_slug = $project_details->data->slug;

            $option = get_option('sortd_'.$project_id.'sync_tag_'.$term_id);
            if($option === true){
                $checked = 'checked';
            } else {
                $checked = '';
            }

            if($column_name === 'sortd_tag_action'){

                $html = '
            <label class="switch-tog">
                <input type="checkbox" name="tagsyncname"  '.$checked.' data-current_user="'.esc_attr($current_user).'" data-wp_domain="'.esc_attr($wp_domain).'" data-project_slug="'.esc_attr($project_slug).'" data-cat_id="'.$term_id.'" data-sync_flag="0"  id="'.$term_id.'" class="tagcatsync  sorttagcheckclass'.$term_id.'" data-size="xs" data-on="Synced" data-off="Unsynced"  data-onstyle="primary">
                <span class="slider-tog round"></span>

                </label>
                <span style="color:green;display:none;" id="tagsyncflag_'.$term_id.'">Unsynced</span>
                <img style="display:none;" class=" img'.$term_id.'" src="'.SORTD_CSS_URL.'/check.png">
                ';
                return $html;
            }



    }

    function tag_deleted($tag_id) {
        // Perform actions when a tag is deleted
        $sortd_article = new Sortd_Article($this->sortd, $this->version, $this->loader);
        $sortd_article->unsync_tag($tag_id);
    }


    function tag_synced_post_publish($post_id) {
        // Perform actions when a tag is deleted
        $sortd_article = new Sortd_Article($this->sortd, $this->version, $this->loader);
        $sortd_article->sync_tags_on_publish_post($post_id);
    }

    function delete_taxonomy($term_id, $taxonomy_id, $taxonomy) {
        $taxonomy_slug = $taxonomy->taxonomy;
        // Perform actions when a taxonomy term is deleted
        $plugin_sortd_category = new Sortd_Categories($this->sortd, $this->version, $this->loader);
        $plugin_sortd_category->unysnc_taxonomy_term($term_id, $taxonomy_id, $taxonomy_slug);
    }

    public function set_order_for_posts($reorder_arr) {
        $menu_arr=wp_json_encode($reorder_arr);
        $reorder_api_slug = "contentsettings/update-category-postsorder";
        Sortd_Helper::sortd_post_api_response($reorder_api_slug,$menu_arr);
    }

    public function post_reorder_actDeact_call($params) {
        $params=wp_json_encode($params);
        $act_deact_api_slug = "contentsettings/postsorder-plugin";
        Sortd_Helper::sortd_post_api_response($act_deact_api_slug,$params);
    }

    function get_article_sync_call( $post_id, $post, $update ) {

        if (wp_is_post_autosave($post_id) || wp_is_post_revision($post_id)) {
            return;
        }
        if ($post->post_status !== 'publish') {
            return;
        }


        add_action('shutdown', function() use ($post_id, $post) {
            $counter = 0;
            $sync_flag = false;

            while(true){

                  // Get all post meta data associated with the post
                $post_meta_before = get_post_meta( $post_id );

                // Count the number of meta keys before update
                $num_meta_keys_before = count( $post_meta_before );

                if($counter === $num_meta_keys_before) {

                    $sync_flag = true;
                    break;
                }

                if($counter < $num_meta_keys_before){
                    $counter = $num_meta_keys_before;
                }

            }
            if($sync_flag === true) {
                Sortd_Article::sync_article($post_id,$post);
            }
        });

    }

   
}
