<?php

/**
 * The templates-specific functionality of the plugin.
 *
 * @link       https://www.sortd.mobi
 * @since      2.0.0
 *
 * @package    Sortd
 * @subpackage Sortd/admin
 */

/**
 * The templates-specific functionality of the plugin.
 *
 * @package    Sortd
 * @subpackage Sortd/admin
 * @author     Your Name <email@example.com>
 */
class Sortd_Templates {

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
            $this->loader->add_action('wp_ajax_sortd_save_template', $this, 'save_template');
            $this->loader->add_action('wp_ajax_get_template_id', $this, 'get_saved_template_id');
	}
        
      
	/**
	 * Register the JavaScript for the templates area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
            wp_enqueue_script('sortd-dashboard', SORTD_JS_URL . '/sortd-dashboard.js', array( 'jquery' ), $this->version, true );	
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
	 *  function for manage templates screen
	 *
	 * @since    2.0.0
	 */
	public function manage_templates() {

            if(isset($_GET['section']) && $_GET['section'] === 'sortd_setup'){
				$nonce = isset($_REQUEST['_wpnonce']) ? sanitize_text_field($_REQUEST['_wpnonce']) :"";
				if( $nonce && wp_verify_nonce( $nonce, SORTD_NONCE ) ){
					$one_click = new Sortd_Oneclick($this->sortd, $this->version, $this->loader);
					$one_click->one_click_setup();
				}
                
            } else {
                $themes_data = array();
                $api_slug = 'config/gettemplates';
                $response = Sortd_Helper::sortd_get_api_response($api_slug);
                if($response){
                    $themes = json_decode($response);
                    $themes_data = $themes->status ? $themes->data:array();
                }

   
                $view_data = array();
                $view_data['themes_data'] = $themes_data;
				
                $view_data['saved_template_id'] = self::get_saved_template_id();
                Sortd_Helper::render_partials(array('sortd-templates'), $view_data);
                
            }
	}
        
        /**
	 *  function to save template code
	 *
	 * @since    2.0.0
	 */
	public function save_template() {
            if(!check_ajax_referer('sortd-ajax-nonce-dashboard', 'sortd_nonce')) {
                $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
                echo wp_kses_data($result); wp_die();
            }

            $project_id = Sortd_Helper::get_project_id(); 

			if(isset($_POST['templateId'])){
				$template_id = sanitize_text_field($_POST['templateId']);
			}
	  
            $params = '{
                   "templateId" : "'.$template_id.'"
                           
             }';
	         
            $api_slug = "config/changetemplate";
            
            $response = Sortd_Helper::sortd_post_api_response($api_slug, $params);

	    $response_data = json_decode($response);
            if($response_data->status === true){
                update_option('sortd_templateId_'.$project_id, $template_id);
                update_option('sortd_saved_template_status_'.$project_id,1);

                if(get_option('sortd_oneclick_flag'.$project_id) === '1'){
                    update_option('sortd_saved_template_and_oneclick'.$project_id,3);
                } else {
                    update_option('sortd_saved_template_and_oneclick'.$project_id,1);
                }
            }

            $saved_flag = get_option('sortd_saved_template_and_oneclick'.$project_id);

            $result = array();
            $result['response'] = $response_data;
            $result['flag'] = $saved_flag;

            $result_json =( wp_json_encode($result));
            echo wp_kses_data($result_json);

            wp_die();
		
	}
        
        /**
	 * function to  get saved template id
	 *
	 * @since    2.0.0
	 */
	public static function get_saved_template_id() {
            $project_details = Sortd_Helper::get_cached_project_details();
			$project_template_saved = get_option('sortd_templateId_'.$project_details->data->id);
		
            $template_id = '';
			if(isset($project_template_saved ) && !empty($project_template_saved) && $project_details->status){
				$template_id = $project_template_saved;
			} else {
				$template_id = '';
			}

            return $template_id;

	}
        
       

}
