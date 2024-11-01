<?php

/**
 * The stats-specific functionality of the plugin.
 *
 * @link       https://www.sortd.mobi
 * @since      2.0.0
 *
 * @package    Sortd
 * @subpackage Sortd/admin
 */

/**
 * The stats-specific functionality of the plugin.
 *
 * @package    Sortd
 * @subpackage Sortd/admin
 * @author     Your Name <email@example.com>
 */
class Sortd_Stats {

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
	 * function to get the SORTD stats from sortd apis.
	 *
	 * @since    2.0.0
	 */
	public function get_sortd_stats_data() {

		

		$project_details = (Sortd_Helper::get_cached_project_details());
     	$console_url = Sortd_Helper::get_pubconsole_url();
		$project_id = Sortd_Helper::get_project_id();


		$api_slug = "stats/article-count";
        $response = Sortd_Helper::sortd_get_api_response($api_slug);
	    $response_article =json_decode($response);

	   $api_category_slug = 'stats/categories-count';
	   $response_categories =json_decode( Sortd_Helper::sortd_get_api_response($api_category_slug,'v2'));

	   $url_project_users_slug =  'stats/project-users-count';
	   $response_project_users =json_decode( Sortd_Helper::sortd_get_api_response($url_project_users_slug));

	   $url_media_storage_slug = 'stats/project-media-storage';
	   $response_media_storage =json_decode(Sortd_Helper::sortd_get_api_response($url_media_storage_slug));

	   $url_recent_articles_slug =  'stats/recent-articles'; 
	   $response_recent_articles =json_decode(Sortd_Helper::sortd_get_api_response($url_recent_articles_slug));
	   $result = array();
		if(isset($response_article->data->articleCount)){
			$result['article'] = $response_article->data->articleCount;
		}
		if(isset($response_categories->data)){
			$result['category'] = $response_categories->data;
		}
		if(isset($response_project_users->data)){
			$result['project_users'] = count($response_project_users->data);
		}
		if(isset($response_media_storage->data->mediaStorage)){
			$result['media_storage'] = $this->_convert_from_bytes($response_media_storage->data->mediaStorage);
		}
	   if(!isset($response_recent_articles->data)){
		   $result['recent_articles'] = "";
	   } else {
		   $result['recent_articles'] = $response_recent_articles->data;
	   }
	   $args = array(
		'meta_query' => array(
			array(
				'key' => 'sortd-paid-price'.$project_id,
				'value' => 0,
				'compare' => '!=',
			),array(
				'key' => 'sortd-paid-price'.$project_id,
				'value' => '',
				'compare' => '!=',
			),
			array(
				'key' => 'sortd_'.$project_id.'_post_sync',
				'value' => 1,
				'compare' => '==',
			
			)
		),
		'post_type' => 'post',
		'posts_per_page' => -1,
		'suppress_filters' => false
	);
	$postscount = get_posts($args);

	$count_posts = count($postscount);
	   $result['paid_article_count'] =$count_posts;
	   $result['projectDetails'] = $project_details;
	   $result['console_url'] = $console_url;

	   return $result;

	}
        
        /**
	 * Function for getting the alerts stats from sortd apis.
	 *
	 * @since    2.0.0
	 */
	public function get_alerts_details() {

				
		$api_slug = 'alert/get-alerts';
		$response =json_decode( Sortd_Helper::sortd_get_api_response($api_slug));
	
		return $response;

	}
        
   
        /**
	 * Function for converting media size
	 *
	 * @since    2.0.0
	 */
	private function _convert_from_bytes($bytes){
		if($bytes >= 1024 && $bytes < 1048576){
			$res_size = number_format($bytes/1024) .' MB';
		 } else if($bytes >= 1048576){
			 $res_size = number_format($bytes/(1024 * 1024)) .' GB';
		 } else{
			  $res_size = number_format($bytes) .' KB';
		 }
		
		 return $res_size;
	}
        
        
        
        

}
