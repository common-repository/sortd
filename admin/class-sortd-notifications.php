<?php

/**
 * The notifications-specific functionality of the plugin.
 *
 * @link       https://www.sortd.mobi
 * @since      2.0.0
 *
 * @package    Sortd
 * @subpackage Sortd/admin
 */

/**
 * The notifications-specific functionality of the plugin.
 *
 * @package    Sortd
 * @subpackage Sortd/admin
 * @author     Your Name <email@example.com>
 */
class Sortd_Notifications {

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
        $this->loader->add_action('wp_ajax_sortd_send_notification', $this, 'send_notification');
        $this->loader->add_action('wp_ajax_sortd_get_notifications', $this, 'get_ajax_notifications');
        $this->loader->add_action('wp_ajax_get_notification_stats', $this, 'get_notifications_stats_data');

	}
        
        /**
	 * function for including scripts
	 *
	 * @since    2.0.0
	 */
        public function enqueue_scripts() {
                wp_enqueue_script('sortd-chartjs', SORTD_CSS_URL . '/assets/js/chart.js', array( 'jquery' ), $this->version, true );
                wp_enqueue_script('sortd-notifications', SORTD_JS_URL . '/sortd-notifications.js', array( 'jquery' ), $this->version, true );
                wp_enqueue_script( 'sweet-alert-message-js', SORTD_JS_URL . '/sweetalert.min.js', array( 'jquery' ), $this->version, true );
                wp_localize_script(
                    'sortd-notifications',
                    'sortd_ajax_obj_notifications',
                    array(
                        'ajax_url' => admin_url( 'admin-ajax.php' ),
                        'nonce'    => wp_create_nonce( 'sortd_ajax_nonce_notifications' ),
                    )
                );
                
	}

	/**
	 * function for  notifications dashboard screen
	 *
	 * @since    2.0.0
	 */
	public function notifications_dashboard() {
           
		$credentials = Sortd_Helper::get_credentials_values();

		if(!$credentials){
                    Sortd_Helper::render_partials(array('sortd-oneclick-get-started'), array());
		} else {
                    $view_data = array();
                    
                    $platforms = array();
                    
                    $platform_api_slug = 'notification/platforms';
                    
                    $platform_response = Sortd_Helper::sortd_get_api_response($platform_api_slug);
                    
                    if($platform_response){
                        $platforms     = json_decode($platform_response);
                    }

                    $recent_notifications = array();
                    
                    $recent_notifications_slug      = "notification/recent-notifications/page/1/records/10";
                    
                    $recent_notifications_response  = Sortd_Helper::sortd_get_api_response($recent_notifications_slug);
                    
                    if($recent_notifications_response){
                        $recent_notifications       = json_decode($recent_notifications_response);
                    }

                    $notification_type = 'General';
                    if(isset($_GET['post'])){
                        $nonce = isset($_REQUEST['_wpnonce']) ? sanitize_text_field($_REQUEST['_wpnonce']) :"";
		                if( $nonce && wp_verify_nonce( $nonce, SORTD_NONCE ) )
                            $post_id =  sanitize_text_field($_GET['post']);
                    }

                    if (isset($post_id) && $post_id) {	
                        $title              = get_the_title($post_id);
                        $notification_type  = 'Article';
                        $view_data['title'] = $title;
                        $view_data['post_id'] = $post_id;
                    }
                    
                    $notifications_stats    = array();
                    
                    $notifications_stats_data = $this->get_notifications_stats();
                    
                    if($notifications_stats_data && isset($notifications_stats_data->data)){
                        foreach($notifications_stats_data->data as $key => $notification){
                               if($key === 'thisMonth' || $key === 'today' || $key === 'total'){
                                   foreach($notification as $details){
                                       if($details->_id === 'article_promotion'){
                                           $notifications_stats[$key]['article_promotion'] = $details->count;
                                       }
                                       if($details->_id === 'general'){
                                           $notifications_stats[$key]['general'] = $details->count;
                                       }
                                   }
                               }
                           }
                    }
                    
                    $flagpublic = 1;
                    if($recent_notifications && isset($recent_notifications->data->isPublicHostSet) && empty($recent_notifications->data->isPublicHostSet) ){
                        $flagpublic = 0;
                    } 

                    if(isset($platforms->error) && $platforms->error->errorCode === 403 ){
                        $view_data['error'] = $platforms;
                        Sortd_Helper::render_partials(array('sortd-oneclick-get-started'), $view_data);
                       
                    } else {
                        array_push($platforms->data, 6);
                        $view_data['notifications_stats']       = $notifications_stats;
                        $view_data['notification_type']         = $notification_type;
                        $view_data['platforms']                 = $platforms;
                        $view_data['recent_notifications']      = $recent_notifications;
                        $view_data['flagpublic']                = $flagpublic;

                        Sortd_Helper::render_partials(array('sortd-notifications'), $view_data);
                    }
 
                
		}

	}
        
        /**
	 *  function to get notifications from api
	 *
	 * @since    2.0.0
	 */
	public function get_notifications_stats() {

            $stats_api_slug = "notification/stats";

            $stats_response = Sortd_Helper::sortd_get_api_response($stats_api_slug);

            if($stats_response){
                $stats_response = json_decode($stats_response);
            }
            return $stats_response;

	}

    public function get_notification_dashboard_data(){
        $notifications_stats_data = $this->get_notifications_stats();

        $notifications_stats = array();
        
        if($notifications_stats_data && isset($notifications_stats_data->data)){
            foreach($notifications_stats_data->data as $key => $notification){
                   if($key === 'thisMonth' || $key === 'today' || $key === 'total'){
                       foreach($notification as $details){
                           if($details->_id === 'article_promotion'){
                               $notifications_stats[$key]['article_promotion'] = $details->count;
                           }
                           if($details->_id === 'general'){
                               $notifications_stats[$key]['general'] = $details->count;
                           }
                       }
                   }
               }
        }

        return $notifications_stats;
    }
        
         /**
	 *  function to get notifications for ajax
	 *
	 * @since    2.0.0
	 */
	public function get_ajax_notifications() {
            
            if(!check_ajax_referer('sortd_ajax_nonce_notifications', 'sortd_nonce')) {
			$result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
		   	echo wp_kses_data($result); wp_die();
		}

        if(isset($_POST['page'])){
            $page = sanitize_text_field($_POST['page']);
        }
	  

            $notifications_api_slug = "notification/recent-notifications/page/".$page."/records/10";
            

            $notifications_response = Sortd_Helper::sortd_get_api_response($notifications_api_slug);
            
            if($notifications_response){
                $notifications_response = json_decode($notifications_response);
            }

            if($notifications_response && !empty($notifications_response->data->notificationList )) {
                $date_format = get_option('date_format').' '.get_option('time_format');
                
                foreach($notifications_response->data->notificationList as $notf_key => $notf_value){

                    if(function_exists('wp_timezone_string')){
                        $timezone_name_to = wp_timezone_string();
                        $date = date_create($notf_value->sent_on, new DateTimeZone('UTC'))->setTimezone(new DateTimeZone($timezone_name_to))->format($date_format);  
                    } else {
                        $date = gmdate( $date_format, $notifications_response->data->notifications[0]->createdAt);
                           
                    }
                    $notifications_response->data->notificationList[$notf_key]->message_type = ucfirst(($notf_value->message_type==='article_promotion') ? 'Article': $notf_value->message_type);
                    $notifications_response->data->notificationList[$notf_key]->sent_on =  $date;                      
                }
            }

            $result = (wp_json_encode($notifications_response,JSON_UNESCAPED_UNICODE));

            echo wp_kses_data($result);

            wp_die();

	}
        
         /**
	 *  function to get notifications stats for ajax
	 *
	 * @since    2.0.0
	 */
	public function _get_ajax_notifications_stats() {


		$api_slug       =   'notification/stats';
		$response  =   json_decode( Sortd_Helper::sortd_get_api_response($api_slug));

		return $response;
		
    }
        
        /**
	 * function to send notification
	 *
	 * @since    2.0.0
	 */
	public function send_notification() {
        
            if(!check_ajax_referer('sortd_ajax_nonce_notifications', 'sortd_nonce')) {
                $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
                echo wp_kses_data($result); wp_die();
            }

            $data = array();

            if(isset($_POST['post_id'])){
                $post_id = sanitize_text_field($_POST['post_id']);
            }
            $data['post_id'] =  $post_id;

            if(isset($_POST['title'])){
                $post_title = sanitize_text_field($_POST['title']);
            }
            
            $data['title'] =  $post_title;
            if(isset($_POST['message'])){
                $post_message = sanitize_text_field($_POST['message']);
            }
            $data['message'] = $post_message;
            if(isset( $_POST['platform']) && !empty($_POST['platform'])){
                $data['platform'] = sanitize_text_field($_POST['platform']);
            } else {
                $data['platform'] = '';
            }
        

            

            if(isset($data['post_id']) && !empty($data['post_id'])){
                $data['type'] = 'article_promotion';
                $postdata = get_post( $data['post_id']); 
                $postslug = $postdata->post_name;
                $data['slug'] = $postslug;
            } else {
                $data['type'] = 'general';
                $data['slug'] = '';
            }

            $params = '{
                "title" : "'. get_bloginfo('name') .'",
                "articleGuid" : "'.$data['post_id'].'",
                "message" : "'.$data['message'].'",
                "platform" : "'. $data['platform'].'",
                "notificationType" : "'.$data['type'].'",
                "slug" : "'.$data['slug'].'"

            }';

       

        $send_notification_api_slug = "notification/send-firebase-notification";  
	    $response = Sortd_Helper::sortd_post_api_response($send_notification_api_slug, $params);
	    echo wp_kses_data($response);
            wp_die();
	}

     /**
	 * function to send notification
	 *
	 * @since    2.0.0
	 */
    public function get_notifications_stats_data() {
        if(!check_ajax_referer('sortd_ajax_nonce_notifications', 'sortd_nonce')) {
            $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
            echo wp_kses_data($result); wp_die();
        }
       
		$response = $this->_get_ajax_notifications_stats();

		echo (wp_json_encode($response));

        wp_die();
		
        }


    public function get_notifications_data_recent(){
        $recent_notifications = array();
                    
        $recent_notifications_slug      = "notification/recent-notifications/page/1/records/10";
        
        $recent_notifications_response  = Sortd_Helper::sortd_get_api_response($recent_notifications_slug);
        
        if($recent_notifications_response){
            $recent_notifications       = json_decode($recent_notifications_response);
        }

        return $recent_notifications;
    }
        
       

}
