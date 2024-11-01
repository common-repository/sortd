<?php

/**
 * The file that defines the core plugin class
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @link       https://www.sortd.mobi
 * @since      1.0.0
 *
 * @package    Sortd
 * @subpackage Sortd/includes
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 * @package    Sortd
 * @subpackage Sortd/includes
 * @author     Your Name <email@example.com>
 */
class Sortd {

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
	 * The unique identifier of this plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $sortd    The string used to uniquely identify this plugin.
	 */
	protected $sortd;

	/**
	 * The current version of the plugin.
	 *
	 * @since    1.0.0
	 * @access   protected
	 * @var      string    $version    The current version of the plugin.
	 */
	protected $version;

	/**
	 * Define the core functionality of the plugin.
	 *
	 * Set the plugin name and the plugin version that can be used throughout the plugin.
	 * Load the dependencies, define the locale, and set the hooks for the admin area and
	 * the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function __construct() {
		if ( defined( 'SORTD_VERSION' ) ) {
			$this->version = SORTD_VERSION;
		} else {
			$this->version = '1.0.0';
		}
		$this->sortd = 'sortd';

		$this->load_dependencies();
		$this->set_locale();
		$this->define_admin_hooks();
		$this->define_public_hooks();

	}

	/**
	 * Load the required dependencies for this plugin.
	 *
	 * Include the following files that make up the plugin:
	 *
	 * - Sortd_Loader. Orchestrates the hooks of the plugin.
	 * - Sortd_i18n. Defines internationalization functionality.
	 * - Sortd_Admin. Defines all hooks for the admin area.
	 * - Sortd_Public. Defines all hooks for the public side of the site.
	 *
	 * Create an instance of the loader which will be used to register the hooks
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function load_dependencies() {

		/**
		 * The class responsible for orchestrating the actions and filters of the
		 * core plugin.
		 */
		require_once SORTD_INCLUDES_PATH . '/class-sortd-loader.php';

		/**
		 * The class responsible for defining internationalization functionality
		 * of the plugin.
		 */
		require_once SORTD_INCLUDES_PATH . '/class-sortd-i18n.php';
                
                /**
		 * The class responsible for basic requirements of the
		 * core plugin.
		 */
		require_once SORTD_INCLUDES_PATH . '/class-sortd-helpers.php';

		/**
		 * The class responsible for defining all actions that occur in the admin area.
		 */
		require_once SORTD_ADMIN_PATH . '/class-sortd-admin.php';
        
        /**
		 * The class responsible for defining all actions of articles related functionality.
		 */
		require_once SORTD_ADMIN_PATH . '/class-sortd-article.php';
        
        /**
		 * The class responsible for defining all actions of categories related functionality.
		 */
		require_once SORTD_ADMIN_PATH . '/class-sortd-categories.php';
        
        /**
		 * The class responsible for defining all actions of config related functionality.
		 */
		require_once SORTD_ADMIN_PATH . '/class-sortd-config.php';
        
        /**
		 * The class responsible for defining all actions of dashboard related functionality.
		 */
		require_once SORTD_ADMIN_PATH . '/class-sortd-dashboard.php';
        
        /**
		 * The class responsible for defining all actions of domains related functionality.
		 */
		require_once SORTD_ADMIN_PATH . '/class-sortd-domains.php';
        
        /**
		 * The class responsible for defining all actions of notifications related functionality.
		 */
		require_once SORTD_ADMIN_PATH . '/class-sortd-notifications.php';
        
        /**
		 * The class responsible for defining all actions of oneclick related functionality.
		 */
		require_once SORTD_ADMIN_PATH . '/class-sortd-oneclick.php';
        
        /**
		 * The class responsible for defining all actions of redirection related functionality.
		 */
		require_once SORTD_ADMIN_PATH . '/class-sortd-redirection.php';
        
        /**
		 * The class responsible for defining all actions of stats related functionality.
		 */
		require_once SORTD_ADMIN_PATH . '/class-sortd-stats.php';
        
        /**
		 * The class responsible for defining all actions of templates related functionality.
		 */
		require_once SORTD_ADMIN_PATH . '/class-sortd-templates.php';
        
        /**
		 * The class responsible for defining all actions of utils functionality.
		 */
		require_once SORTD_ADMIN_PATH . '/class-sortd-utils.php';

		/**
		 * The class responsible for defining all actions that occur in the public-facing
		 * side of the site.
		 */
		require_once SORTD_PUBLIC_PATH . '/class-sortd-public.php';

		$this->loader = new Sortd_Loader();

	}

	/**
	 * Define the locale for this plugin for internationalization.
	 *
	 * Uses the Sortd_i18n class in order to set the domain and to register the hook
	 * with WordPress.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function set_locale() {

		$plugin_i18n = new Sortd_i18n();

		$this->loader->add_action( 'plugins_loaded', $plugin_i18n, 'load_plugin_textdomain' );

	}

	/**
	 * Register all of the hooks related to the admin area functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_admin_hooks() {

		$plugin_sortd_admin = new Sortd_Admin( $this->get_sortd(), $this->get_version(),  $this->loader );
                $plugin_sortd_admin->define_hooks();
		
                $current_sortd_module = $this->get_current_sortd_module();

                switch($current_sortd_module){
                    
                    case 'article':
                        $plugin_sortd_article = new Sortd_Article( $this->get_sortd(), $this->get_version(), $this->loader );
                        $plugin_sortd_article->define_hooks();
                        break;
                    case 'category':
                        $plugin_sortd_category = new Sortd_Categories( $this->get_sortd(), $this->get_version(), $this->loader );
                        $plugin_sortd_category->define_hooks();
                        break;
                    case 'config':
                        $plugin_sortd_config = new Sortd_Config( $this->get_sortd(), $this->get_version(), $this->loader );
                        $plugin_sortd_config->define_hooks();
                        break;
                    case 'dashboard':
                        $plugin_sortd_dashboard = new Sortd_Dashboard( $this->get_sortd(), $this->get_version(), $this->loader );
                        $plugin_sortd_dashboard->define_hooks();
                        break;
                    case 'domains':
                        $plugin_sortd_domains = new Sortd_Domains( $this->get_sortd(), $this->get_version(), $this->loader );
                        $plugin_sortd_domains->define_hooks();
                        break;
                    case 'notifications':
                        $plugin_sortd_notifications = new Sortd_Notifications( $this->get_sortd(), $this->get_version(), $this->loader );
                        $plugin_sortd_notifications->define_hooks();
                        break;
                    case 'oneclick':
                        $plugin_sortd_oneclick = new Sortd_Oneclick( $this->get_sortd(), $this->get_version(), $this->loader );
                        $plugin_sortd_oneclick->define_hooks();
                        break;
                    case 'templates':
                        $plugin_sortd_templates = new Sortd_Templates( $this->get_sortd(), $this->get_version(), $this->loader );
                        $plugin_sortd_templates->define_hooks();
                        break;
                    case 'utils':
                        $plugin_sortd_utils = new Sortd_Utils( $this->get_sortd(), $this->get_version(), $this->get_loader() );
                        $plugin_sortd_utils->define_hooks();
                        break;
					case 'redirection':
						$plugin_sortd_redirection = new Sortd_Redirection( $this->get_sortd(), $this->get_version(), $this->get_loader() );
						$plugin_sortd_redirection->define_hooks();
						break;
					
                }
                
	}

	/**
	 * Register all of the hooks related to the public-facing functionality
	 * of the plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 */
	private function define_public_hooks() {

		$plugin_public = new Sortd_Public( $this->get_sortd(), $this->get_version() );

		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_styles' );
		$this->loader->add_action( 'wp_enqueue_scripts', $plugin_public, 'enqueue_scripts' );

	}

	/**
	 * Run the loader to execute all of the hooks with WordPress.
	 *
	 * @since    1.0.0
	 */
	public function run() {
		$this->loader->run();
	}

	/**
	 * The name of the plugin used to uniquely identify it within the context of
	 * WordPress and to define internationalization functionality.
	 *
	 * @since     1.0.0
	 * @return    string    The name of the plugin.
	 */
	public function get_sortd() {
		return $this->sortd;
	}

	/**
	 * The reference to the class that orchestrates the hooks with the plugin.
	 *
	 * @since     1.0.0
	 * @return    Sortd_Loader    Orchestrates the hooks of the plugin.
	 */
	public function get_loader() {
		return $this->loader;
	}

	/**
	 * Retrieve the version number of the plugin.
	 *
	 * @since     1.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_version() {
		return $this->version;
	}
        
        /**
	 * Retrieve the current module of the plugin based on current screen.
	 *
	 * @since     2.0.0
	 * @return    string    The version number of the plugin.
	 */
	public function get_current_sortd_module() {
            $current_module = 'admin';

			if(function_exists('wp_verify_nonce')){
				if(!wp_verify_nonce('sortd_nonce')){
					return false;
				}
			}	
			
          
            $page       = isset($_GET['page']) ? sanitize_text_field($_GET['page']):'';
           
            $section    = isset($_GET['section']) ? sanitize_text_field($_GET['section']):'';
            
            $ajax_action    = isset($_POST['action']) ? sanitize_text_field($_POST['action']):'';

			$post_page_action   = isset($_GET['action']) ? sanitize_text_field($_GET['action']) :'';

			$taxonomy_action   = isset($_GET['taxonomy']) ? sanitize_text_field($_GET['taxonomy']) :'';

            if($page === 'sortd_credential_settings' || $section === 'sortd_credential_settings' || $section === 'sortd_paid_articles'  ){
                $current_module = 'utils';
            }  
            
            if($ajax_action === 'sortd_verify_credentials' || $ajax_action === 'sortd_get_contractdetailsafter_verify' || $post_page_action === 'edit' || $ajax_action === 'sortd_get_paid_articles'
			 || $ajax_action === 'mark_free_sortd_action' || $ajax_action === 'search_based_on_filters' || $ajax_action === 'get_count_after_reset'){
                $current_module = 'utils';
            } 
            
            if($page === 'sortd-settings'){
				$credentials = Sortd_Helper::get_credentials_values();
				$project_id = Sortd_Helper::get_project_id();
				$template_oneclick_flag = get_option('sortd_saved_template_and_oneclick'.$project_id);
	    		$sortd_oneclick_flag  = get_option('sortd_oneclick_flag'.$project_id);

				if(!empty($credentials) && $template_oneclick_flag === '1' && $sortd_oneclick_flag === false){
					$current_module = 'oneclick';
				} if(!empty($credentials) && empty($template_oneclick_flag)){
					$current_module = 'templates';
				} if(isset($credentials) && !empty($credentials) && ($sortd_oneclick_flag === 1 || $sortd_oneclick_flag === '1' || $sortd_oneclick_flag === true || $sortd_oneclick_flag === 'true' )){
					$current_module = 'dashboard';
				}
               
            }  
            
            if($page === 'sortd_manage_templates' || $section === 'sortd_manage_templates'){
                $current_module = 'templates';
            } 
            
            if($ajax_action === 'sortd_save_template'){
                $current_module = 'templates';
            } 
            
            if($ajax_action === 'sortd_build_default_config' || $ajax_action === 'sortd_sync_relevant_categories' 
			|| $ajax_action === 'sortd_sync_relevant_articles' || $ajax_action === 'sortd_preview_mobile_website' 
			 || $ajax_action === 'oneclick_page_insights'){
                $current_module = 'oneclick';
            } 
            
            if($page === 'sortd_setup'){
                $current_module = 'oneclick';
            }  
            
            if($page === 'sortd_notification' || $ajax_action === 'sortd_send_notification' || $ajax_action === 'sortd_get_notifications' || $ajax_action === 'get_notification_stats'){
                $current_module = 'notifications';
            } 
            
            if($section === 'sortd_manage_categories' || $ajax_action === 'sortd_sync_unsync_category' || $ajax_action === 'sortd_ajax_reorder_rename_category' || $ajax_action==='sortd_ajax_rename_category' || $ajax_action==='sortd_ajax_save_reorder_category'
				|| $ajax_action === 'get_cat_children' || $ajax_action === 'get_all_heirarchy_cat_children' || $ajax_action === 'check_for_synced'
				|| $taxonomy_action === 'category' || $ajax_action === 'get_categories' || $ajax_action === 'sortd_category_url_redirection' || $ajax_action === 'sortd_article_url_redirection' || $ajax_action === 'sync_web_cat' || $ajax_action === 'unsync_web_cat' || $ajax_action === 'list_web_cats' || $ajax_action==='refresh_custom_column' || $ajax_action==='check_parent_cat_sync' || $section === 'sortd_manage_taxonomies' || $ajax_action === 'sortd_sync_taxonomy_type' || $ajax_action === 'sortd_get_taxonomy_view' || $ajax_action === 'sortd_get_synced_taxonomytype_list' || $ajax_action === 'sortd_get_synced_taxonomomies'
			||$ajax_action === 'sortd_canonical_url_redirection' || $ajax_action === 'refresh_custom_column_for_tag'){
                $current_module = 'category';
            } 

			if($page === 'sortd-manage-settings' && $section === 'sortd_redirection' || $ajax_action === 'save_redirection_values' || $ajax_action === 'show_warning_msg' || $ajax_action === 'get_sortd_service'){
                $current_module = 'redirection';
            } 

			if($page === 'sortd-manage-settings' && $section === 'sortd_manage_domains' 
			 || $ajax_action === 'sortd_create_domain' || $ajax_action === 'sortd_update_public_host' || $ajax_action === 'generate_ssl' || $ajax_action === 'validate_ssl'
			 || $ajax_action === 'deploy_cdn' || $ajax_action === 'verify_cname' || $page === 'sortd-manage-settings' && $section === 'contact-us'
			){
			
				$current_module = 'domains';
            } 

			if( ($ajax_action === 'sortd_sync_authors') || ($page === 'sortd-manage-settings' && empty($section))
			|| $ajax_action === 'save_shors_cat' || $ajax_action === 'get_shors_cat'
			){
				
				$current_module = 'domains';
			}

			
            

            if($ajax_action === 'sortd_ajax_manual_sync' || $ajax_action === 'unsync_article' || $ajax_action === 'sync_articles_in_bulk' || $ajax_action === 'sortd_update_bulk_count' || $ajax_action === 'sortd_update_bulk_flag' ||
			 $ajax_action === 'sync_webstory' || $ajax_action === 'unsync_webstory' || $ajax_action === 'unsync_articles_in_bulk' || 
			 $ajax_action === 'update_bulk_unsync_count' || $ajax_action === 'sortd_update_bulk_unsync_flag'
			 ||  $ajax_action === 'bulk_sync_webstories' ||  $ajax_action === 'update_bulk_flag_webstory' ||  $ajax_action === 'update_bulk_count_webstory'
			 ||  $ajax_action === 'bulk_unsync_webstories' ||  $ajax_action === 'update_bulk_count_webstory_unsync' ||  $ajax_action === 'update_bulk_flag_webstory_unsync'
			 || $ajax_action === 'get_data_article' || $ajax_action === 'rate_later' || $ajax_action === 'show_not_again' || $ajax_action ==='filter_article_array'
			 || $ajax_action === 'sortd_sync_tag' || $ajax_action === 'sortd_unsync_tag' || $ajax_action === 'list_tags' || $ajax_action === 'get_data_webstory'){
                $current_module = 'article';
            }
            
            if(($page === 'sortd-manage-settings' && $section === 'sortd_config') || $ajax_action === 'sortd_ajax_config_file_upload' || $ajax_action === 'sortd_ajax_save_config' || $ajax_action === 'sortd_ajax_display_group_config'){
                $current_module = 'config';
            } 

			if($ajax_action === 'sortd_unsync_article' || $ajax_action === 'sortd_restore' || $ajax_action === 'sortd_dailyingestedarticles' || $ajax_action === 'article-type-count' || $ajax_action === 'get_notification_stats_dashboard'
			 || $page === 'sortd-paid-articles'|| $ajax_action === 'get_config_data' || $ajax_action === 'webstories_count' ){
				$current_module = 'dashboard';
			}
            
            
            return $current_module;
	}
        
        

}
