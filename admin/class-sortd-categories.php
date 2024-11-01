<?php

/**
 * The categories-specific functionality of the plugin.
 *
 * @link       https://www.sortd.mobi
 * @since      2.0.0
 *
 * @package    Sortd
 * @subpackage Sortd/admin
 */

/**
 * The categories-specific functionality of the plugin.
 *
 * @package    Sortd
 * @subpackage Sortd/admin
 * @author     Your Name <email@example.com>
 */
class Sortd_Categories {
        
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

    private $html;

    private $all_categories;

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
        $this->loader->add_action('wp_ajax_sortd_sync_unsync_category', $this, 'sync_category');
        $this->loader->add_action('wp_ajax_sortd_ajax_reorder_rename_category', $this, 'reorder_rename_category_ajax');
        $this->loader->add_action('wp_ajax_sortd_ajax_rename_category', $this, 'save_renamed_category');
        $this->loader->add_action('wp_ajax_sortd_ajax_save_reorder_category', $this, 'save_reordered_categories');
        $this->loader->add_action('wp_ajax_get_cat_children', $this, 'get_cat_children');
        $this->loader->add_action('wp_ajax_get_all_heirarchy_cat_children', $this, 'get_all_heirarchy_cat_children');
        $this->loader->add_action('wp_ajax_check_for_synced', $this, 'check_for_synced');
        $this->loader->add_action('wp_ajax_get_categories', $this, 'sortd_get_categories');
        $this->loader->add_action('wp_ajax_sortd_category_url_redirection', $this, 'update_category_url_redirection');
        $this->loader->add_action('wp_ajax_sortd_article_url_redirection', $this, 'update_article_url_redirection');
        $this->loader->add_action('wp_ajax_sync_web_cat', $this, 'sync_web_cat');
        $this->loader->add_action('wp_ajax_unsync_web_cat', $this, 'unsync_web_cat');
        $this->loader->add_action('wp_ajax_list_web_cats', $this, 'list_web_cats');
        $this->loader->add_action('wp_ajax_refresh_custom_column', $this, 'refresh_custom_column');
        $this->loader->add_action('wp_ajax_refresh_custom_column_for_tag',$this,'refresh_custom_column_for_tag');
        $this->loader->add_action('wp_ajax_check_parent_cat_sync',$this,'check_parent_cat_sync');
        $this->loader->add_action('wp_ajax_sortd_canonical_url_redirection', $this, 'update_canonical_url_redirection');

        $this->loader->add_action('wp_ajax_sortd_sync_taxonomy_type',$this,'sortd_sync_taxonomy_type');

        $this->loader->add_action('wp_ajax_sortd_get_taxonomy_view',$this,'sortd_get_taxonomy_view');

        $this->loader->add_action('wp_ajax_sortd_get_synced_taxonomytype_list',$this,'sortd_get_synced_taxonomytype_list');

        $this->loader->add_action('wp_ajax_sortd_get_synced_taxonomomies',$this,'sortd_get_synced_taxonomomies');
        

        
	}
        
        /**
	 * function for including scripts
	 *
	 * @since    2.0.0
	 */
        public function enqueue_scripts() {

            $current_page = get_current_screen()->base;
          if($current_page === 'edit_tags'){

            wp_enqueue_script('sortd-taxonomyjs', SORTD_JS_URL . '/sortd-taxonomy.js', array( 'jquery' ), $this->version, true );
            wp_localize_script(
                'sortd-taxonomyjs',
                'sortd_ajax_obj_category',
                array(
                    'ajax_url' => admin_url( 'admin-ajax.php' ),
                    'nonce'    => wp_create_nonce( 'sortd-ajax-nonce-category' ),
                )
            );

          } else if($current_page === 'sortd_page_sortd-manage-settings'){
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
        
         
            wp_enqueue_script( 'sweet-alert-message-js', SORTD_JS_URL . '/sweetalert.min.js', array( 'jquery' ), $this->version, true );
            wp_enqueue_script('jqueryuisort', SORTD_JS_URL . '/jqueryui-sort.min.js',array( ), $this->version, true);
            wp_enqueue_script( 'nestedSortable', SORTD_JS_URL . '/jquery.mjs.nestedSortable.min.js', array( 'jquery' ), $this->version, true );
          
            
            
                
        }

	

	/**
	 * function for manage categories screen
	 *
	 * @since    2.0.0
	 */
	public function manage_categories() {

        $taxonomytype_api_slug = 'custompost/list-taxonomy-types';
           

        $taxonomytype_response = Sortd_Helper::sortd_get_api_response($taxonomytype_api_slug);
        $tt_response = json_decode($taxonomytype_response);

        $tax_array = array();
        foreach($tt_response->data->taxonomy_types as $k => $v){
            
            if(taxonomy_exists($v->slug)) {
                $tax_array[$k]['taxonomy_type']['taxonomy_name'] =  $v->name;
                $tax_array[$k]['taxonomy_type']['taxonomy_slug'] =  $v->slug;
                
                    
                if(sizeof( get_terms(array('taxonomy' => $v->slug,'hide_empty' => false)) ) === 0){
                    $tax_array[$k]['taxonomy_type']['count'] =  0;

                } else {
                    $tax_array[$k]['taxonomy_type']['count'] =  $v->count;

                }
            }
          
        }  
     
           $args = array(
                'public'   => true,  // Show only public post types
                '_builtin' => false, // Exclude built-in post types
                'capability_type'=>'post'
               
            );

                $post_types = get_post_types($args,'objects');
                $posttypes_array = array();
                foreach($post_types as $k => $v){


                    $posttypes_array[$k]['slug'] = $v->name;
                    $posttypes_array[$k]['name'] = $v->label;

                }


                foreach($posttypes_array as $key => $value){

                    $post_type = $value['slug']; // Replace with the post type name
                    $taxonomies = get_object_taxonomies($post_type,'objects');


                    $taxonomy_array = array();
                    foreach($taxonomies as $taxk => $taxv){
    

                         $taxonomy_array[$key]['postype_name'] = $value['name'];
                         $taxonomy_array[$key]['postype_slug'] = $value['slug'];

                      
                        $taxonomy_array[$key]['taxonomy_type'][$taxk]['taxonomy_name'] =  $taxv->label;
                        $taxonomy_array[$key]['taxonomy_type'][$taxk]['taxonomy_slug'] =  $taxv->name;



                    }

                }

         

            $category_order = $categories = array();
            $project_id          = Sortd_Helper::get_project_id();

            $categories_api_slug = 'custompost/list-taxonomies/category';
           

            $response = Sortd_Helper::sortd_get_api_response($categories_api_slug);

            if($response){
                $response   = json_decode($response,TRUE);
                $categories = isset($response['data']) ? $response['data']:array();
            }
		
            if(!empty($categories)){
                foreach ($categories as $category) {
                        $category_order[]['id'] = $category;
                }
            }  
            
           
           $taxonomy     = 'category';
            $orderby      = 'name';  
            $show_count   = 0;      
            $pad_counts   = 0;      
            $hierarchical = 1;     
            $title        = '';  
            $empty        = 0;

            $args = array(
                   'taxonomy'     => $taxonomy,
                   'orderby'      => $orderby,
                   'show_count'   => $show_count,
                   'pad_counts'   => $pad_counts,
                   'hierarchical' => $hierarchical,
                   'title_li'     => $title,
                   'hide_empty'   => $empty
            );
            
            $this->all_categories = get_categories( $args );

            $flag =  $this->getHtml(0,0, $project_id,'category');
            if($flag === true){
                $render_html =  $this->html;
            }

          
            
            
            $action = "sync";
            
            if(isset($_GET['action']) && $_GET['action'] === 'reorder'){
                $nonce = isset($_REQUEST['_wpnonce']) ? sanitize_text_field($_REQUEST['_wpnonce']) :"";
		        if( $nonce && wp_verify_nonce( $nonce, SORTD_NONCE ) )
                    $action = 'reorder';
            }
            
            $view_data = array();
            $view_data['category_order']    = wp_json_encode($category_order);
            $view_data['categories']        = $categories;
            $view_data['all_categories']    = $this->all_categories;
            $view_data['project_id']        = $project_id;
            $view_data['response']          = $response;
            $view_data['action']            = $action;
            $view_data['html_data']         = $render_html;
            $view_data['taxonomy_data']     = $tax_array;

            Sortd_Helper::render_partials(array('sortd-categories-manage', 'sortd-categories-sync', 'sortd-categories-reorder'), $view_data);

	}

    public function getHtml($pid, $level, $project_id,$taxonomy_slug ){

        $wp_domain = get_site_url();
        $current_user = wp_get_current_user()->display_name;
        $project_details = Sortd_Helper::get_cached_project_details();
        $project_slug = $project_details->data->slug;

        if($level>=20){
            return false;
        }

        if(!empty($this->all_categories)){
            foreach($this->all_categories as $catg){


                
                $category_id = $catg->term_id;
                
                $cat_option_value = Sortd_Helper::get_options_for_category($project_id,$category_id);
                if($cat_option_value === '1'){
                    $value_checked='checked';
                } else{
                    $value_checked = '';
                }


                
                $cat_sync_sortd = Sortd_Helper::get_options_for_categoryid($project_id,$category_id);
                if($catg->parent === $pid){


                    

                    $this->html  .= '<tr id="tr-'.$catg->term_id.'" data-parent="'.$catg->parent.'"  data-cat_id="'.$catg->term_id.'" data-sync_flag="'.$cat_option_value.'" >';
                    for($i=0;$i< $level;$i++){
                        $this->html .= '<td></td><td></td><td></td>';
                    }
                    $cat_sync_sortd_parent = Sortd_Helper::get_options_for_category($project_id,$catg->parent);

                
                    if( (bool)$cat_sync_sortd_parent === true && $catg->parent !== 0){
                        $prop = "";
  
                      } else {
                          if($catg->parent === 0){
                              $prop="";
                              
                          } else {
                              $prop="disabled";
                              
                          }
                       
                      }

              

                    $this->html  .= '<td class="inputMsg catDynamic_'.wp_kses_data($catg->term_id).'" data-sortdindex="'.wp_kses_data($cat_sync_sortd).'" id="'.wp_kses_data($catg->term_id).'">'.wp_kses_data($catg->name). ' 
                                    </td>
                                    <td>
                                    <label class="switch-tog " >
                                    <input  data-parent="'.$catg->parent.'" data-current_user="'.esc_attr($current_user).'" data-wp_domain="'.esc_attr($wp_domain).'" data-project_slug="'.esc_attr($project_slug).'" type="checkbox" '.$prop.' name="checkbox" '.esc_attr($value_checked).' class="sortCatCheck sortcheckclass'.esc_attr($catg->term_id).'" id="'.esc_attr($catg->term_id).'" data-nonce="'.wp_kses_data(wp_create_nonce(`rw-sortd-cat-sync-`.$catg->term_id)).'" data-taxonomytypeslug="'.$taxonomy_slug.'">
                                    <span class="slider-tog round"></span>
                                    </label>
                                    </td>
                                   
                                    <td class="succmsg'.$catg->term_id.'" style="display:none;"></td>
                                    
                                </tr>
                                ';

                   $this->getHtml($catg->cat_ID, ++$level,$project_id,$taxonomy_slug);
         
                    $level--;
                }
            }
            

            return true;

        } else {
            return false;
        }

      
        
    }
        
        /**
	 *  function for save rename category 
	 *
	 * @since    2.0.0
	 */
	public function save_renamed_category() {

            if(!check_ajax_referer('sortd-ajax-nonce-category', 'sortd_nonce')) {
                    $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
                    echo wp_kses_data($result); wp_die();
            }

            if(isset($_POST['id'])){
                $cat_id_new = sanitize_text_field($_POST['id']);
            }

            if(isset($_POST['name'])){
                $cat_name =sanitize_text_field($_POST['name']);
            }

            if(isset($_POST['alias'])){
                $cat_alias = sanitize_text_field($_POST['alias']);
            }

            $wp_domain = get_site_url();
            $current_user = wp_get_current_user()->display_name;
            $project_details = Sortd_Helper::get_cached_project_details();
            $project_slug = $project_details->data->slug;

            $paramsrename = '{
                   "cat_id" : "'.$cat_id_new.'",
                   "name" : "'.$cat_name.'",
                   "alias" : "'.$cat_alias.'"            
             }';

            $category_rename_api_slug = "contentsettings/categoryrename";

            $response = Sortd_Helper::sortd_post_api_response($category_rename_api_slug,$paramsrename); 

            if(isset($_POST['taxonomy_slug'])){
                $taxonomy_slug = sanitize_text_field($_POST['taxonomy_slug']);
            }

            $categories_api_slug = 'custompost/list-taxonomies/'.$taxonomy_slug;
    
            $response_cat = Sortd_Helper::sortd_get_api_response($categories_api_slug);
             $data = array();
            $data['response'] = json_decode($response);
            $data['responseCat'] = json_decode($response_cat);
            $data['wp_domain'] = $wp_domain;
	        $data['current_user'] = $current_user;
	        $data['project_slug'] = $project_slug;

            echo (wp_json_encode($data));

            wp_die();

	}
        
        /**
	 *  function for save reorder categories 
	 *
	 * @since    2.0.0
	 */
	public function save_reordered_categories() {

            if(!check_ajax_referer('sortd-ajax-nonce-category', 'sortd_nonce')) {
                $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
                echo wp_kses_data($result); wp_die();
            }
            if(isset($_POST['category_order'])){
                $category_order_new = sanitize_text_field($_POST['category_order']);
            }

            if(isset($_POST['cat_id'])){
                $cat_id = sanitize_text_field($_POST['cat_id']);
            }
            if(isset($_POST['after_cat_id'])){
                $after_cat_id = sanitize_text_field($_POST['after_cat_id']);
            }

                $json_data = stripslashes(html_entity_decode($category_order_new));
                $data = json_decode($json_data,true);
                $new_categories_order = array();
                $cat_data_param = array();

                foreach ($data as $order => $category) {
	            $cat_data = array();
	            $cat_data['_id'] = $category['id'];
	            $cat_data['cat_type'] = 1;
	            $cat_data['order'] = $order+1;
	            $cat_data['parent_id'] = null;
	            $new_categories_order[] = $cat_data;

	            if (isset($category['children'])) {
	              foreach ($category['children'] as $sub_order => $sub_cat) {
	                $cat_data = array();
	                $cat_data['_id'] = $sub_cat['id'];
	                $cat_data['cat_type'] = 2;
	                $cat_data['order'] = $sub_order+1;
	                $cat_data['parent_id'] = $category['id'];
	                $new_categories_order[] = $cat_data;                  
	                }         
	            }         
                }

            $cat_data_param['cat_guid'] = $cat_id;
            $cat_data_param['after_cat_id'] = $after_cat_id;
	        $params = wp_json_encode($cat_data_param);
	        $category_reorder_api_slug = "contentsettings/categoryreorder";
	        
	        $response =Sortd_Helper::sortd_post_api_response($category_reorder_api_slug, $params,'v2');
            $wp_domain = get_site_url();
            $current_user = wp_get_current_user()->display_name;
            $project_details = Sortd_Helper::get_cached_project_details();
            $project_slug = $project_details->data->slug;

	        $decode = json_decode($response);
            $decode->wp_domain = $wp_domain;
            $decode->current_user = $current_user;
            $decode->project_slug = $project_slug;


                if($decode->status === true){
                       update_option('sortd_sync_reorder_status',0);
                }

            echo wp_json_encode($decode);
            wp_die();
	}
        
        
        /**
	 *  function for sync category
	 *
	 * @since    2.0.0
	 */
	public function sync_category() {
            
            if(!check_ajax_referer('sortd-ajax-nonce-category', 'sortd_nonce')) {
                $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
                echo wp_kses_data($result); wp_die();
            }
			

            try {

               
                if(isset($_POST['id'])){
                    $cat_id = sanitize_text_field($_POST['id']);
                }

                if(isset($_POST['flag'])){
                    $flag = sanitize_text_field($_POST['flag']);
                }


                if(isset($_POST['after_cat_id'])){
                    $after_cat_id = sanitize_text_field($_POST['after_cat_id']);
                }

                 if(isset($_POST['taxonomytypeslug'])){
                    $taxonomytype_slug = sanitize_text_field($_POST['taxonomytypeslug']);
                }

                $catinfo = get_term($cat_id);
                $cat_url = get_term_link($catinfo);
                $category_desc = '';
                
                if(is_plugin_active('wordpress-seo/wp-seo.php')) {
                    $yoast_data = get_option( 'wpseo_taxonomy_meta');
                    if(isset($yoast_data) && !empty($yoast_data)) {
                        foreach($yoast_data['category'] as $cid => $data) {
                            if($cid === (int)$cat_id) {
                                if(isset($data['wpseo_desc']) && !empty($yoast_data) ) {
                                    $category_desc = $data['wpseo_desc'];
                                }else{
                                    $category_desc = "";
                                }
                            }
                        }
                    }
                } else {
                    $category_desc = wp_strip_all_tags(trim(category_description($cat_id)));
                }
                
                $cat_title = '';

                if(isset($_POST['parent_id'])){
                    $parent_id = sanitize_text_field($_POST['parent_id']);
                }

                if(is_plugin_active('wordpress-seo/wp-seo.php')) {
                    $yoast_data = get_option( 'wpseo_taxonomy_meta');
                    if(isset($yoast_data) && !empty($yoast_data)) {
                        foreach($yoast_data['category'] as $cid => $data) {
                            if($cid === (int)$cat_id) {
                                if(isset($data['wpseo_title']) && !empty($yoast_data) ) {
                                    $cat_title = $data['wpseo_title'];
                                }else{
                                    $cat_title = "";
                                }
                            }
                        }
                    }
                } else {
                    $cat_title = $catinfo->name;
                }
                
                $project_id = Sortd_Helper::get_project_id();

                $taxonomyterm_image = "";
                $term_image = "";
                $term_image = get_term_meta($cat_id,'topic-image');

                if(isset($term_image[0]) && !empty($term_image[0])){
                    $taxonomyterm_image = $term_image[0];

                }

            
        
                if($flag === 'true'){
                

                     $params = '{
                               "taxonomy_guid" : "'.$cat_id.'",
                               "name" : "'.$catinfo->name.'",
                               "alias" : "'.urldecode($catinfo->slug).'",
                               "parent_guid" : '.$parent_id.',
                               "after_cat_id": "'.$after_cat_id.'",
                               "cat_desc" : '.wp_json_encode($category_desc).',
                               "cat_url": "'.$cat_url.'",
                               "cat_title": "'.$cat_title.'",
                               "taxonomy_type_slug" :"'.$taxonomytype_slug.'",
                               "taxonomy_image":"'.$taxonomyterm_image.'"
                    }';

             

                    $cat_sync_api_slug = "custompost/sync-taxonomy";


                   

                    $response = Sortd_Helper::sortd_post_api_response($cat_sync_api_slug,$params);

                   
                    
                    $response = json_decode($response);
                    if($response->status===true){
                        Sortd_Helper::create_options_for_category($project_id,$cat_id,1,$response->data->taxonomy_id);
                        update_option('sortd_catsynconeclick_'.$project_id,1);
                        update_option('sortd_one_click_manual_sync'.$project_id,1);	
                    }
                    
                } else {
                    
                    $cat_sync_api_slug = "custompost/unsync-taxonomy";
                    $params = '{
                        "taxonomy_guid" : "'.$cat_id.'"
                    }';

                
                    $response_data = Sortd_Helper::sortd_post_api_response($cat_sync_api_slug,$params);
                    $response = json_decode($response_data);

                   
                  
                    if($response->status===true){



                            Sortd_Helper::create_options_for_category($project_id,$cat_id,0,'');
			         

                        update_option('sortd_catsynconeclick_'.$project_id,0);	
                    }

                }

	   		
                $option = Sortd_Helper::get_options_for_category($project_id,$cat_id); 

                $result = array('option'=>$option,'response'=>$response,'flag'=>$flag);

                echo (wp_json_encode($result));

            } catch (Exception $e){

            }

            wp_die();
	}
        
        /**
	 *  function for delete category
	 *
	 * @since    2.0.0
     * 
	 */


	public function delete_category($tt_id) {

        $cats = $this->_get_sortd_categories('category');
        $childcat = array();

        foreach($cats->data->taxonomies as $sortd_cat){

           if(!empty($sortd_cat->parent_id)){

                   if($sortd_cat->parent_id->cat_guid===$tt_id){
                       array_push($childcat,$sortd_cat->cat_guid);
                       
                   }
           }
           

           
        }


        $cat_sync_api_slug = "contentsettings/categoryremove";

        if(!empty($childcat)){
           foreach($childcat as $sortd_childcat_value){
               $params = '{"cat_guid" : "'.$sortd_childcat_value.'"}';
   
               $response = Sortd_Helper::sortd_post_api_response($cat_sync_api_slug,$params,'v2');
               $responseChildUnsync = json_decode($response);
               $project_id = Sortd_Helper::get_project_id();


               if($responseChildUnsync->status === true){
                 Sortd_Helper::create_options_for_category($project_id,$sortd_childcat_value,0,'');
                 
                 
               }
               
   
            }
        }

        
        $params = '{
            "cat_guid" : "'.$tt_id.'"
         }';

     
         $response = Sortd_Helper::sortd_post_api_response($cat_sync_api_slug,$params,'v2');
         $responseparentUnsync = json_decode($response);
         $project_id = Sortd_Helper::get_project_id();

         if($responseparentUnsync->status === true){

           Sortd_Helper::create_options_for_category($project_id,$tt_id,0,'');
           update_option('sortd_catsynconeclick_'.$tt_id,0);

         }
         


   }

   public function delete_child_taxonomies($taxonomy_slug, $tt_id) {

    $cats = $this->_get_sortd_categories($taxonomy_slug);
    $childcat = array();

    foreach($cats->data->taxonomies as $sortd_cat){

       if(!empty($sortd_cat->parent_id)){

               if($sortd_cat->parent_id->cat_guid===$tt_id){
                   array_push($childcat,$sortd_cat->cat_guid);
                   
               }
       }
       

       
    }


    $cat_sync_api_slug = "contentsettings/categoryremove";

        if(!empty($childcat)){
            foreach($childcat as $sortd_childcat_value){
           $params = '{"cat_guid" : "'.$sortd_childcat_value.'"}';

           $response = Sortd_Helper::sortd_post_api_response($cat_sync_api_slug,$params,'v2');
           $responseChildUnsync = json_decode($response);
           $project_id = Sortd_Helper::get_project_id();


           if($responseChildUnsync->status === true){
             Sortd_Helper::create_options_for_category($project_id,$sortd_childcat_value,0,'');
             
             
            }
           

            }
        }

    }
       
        
        /**
	 *  function for reorder,rename via ajax
	 *
	 * @since    2.0.0
	 */
	public function reorder_rename_category_ajax() {

            if(!check_ajax_referer('sortd-ajax-nonce-category', 'sortd_nonce')) {
                    $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
                    echo wp_kses_data($result); wp_die();
            }

	        
            $rename_array = array();
	    
         
             $api_slug = 'custompost/list-taxonomies/category';
            $response = Sortd_Helper::sortd_get_api_response($api_slug);

             $taxonomytype_api_slug = 'custompost/list-taxonomy-types';
           

            $taxonomytype_response = Sortd_Helper::sortd_get_api_response($taxonomytype_api_slug);
            $tt_response = json_decode($taxonomytype_response);

            foreach($tt_response->data->taxonomy_types as $k => $v){
                if (!taxonomy_exists($v->slug)) {
                    unset($tt_response->data->taxonomy_types[$k]);
                } else {
                    if(sizeof(  get_terms( $v->slug )) === 0){
                        $tt_response->data->taxonomy_types[$k]->count =  0;

                    } else {
                        $tt_response->data->taxonomy_types[$k]->count =  $v->count;

                    }
                }
            }

        $encoded_response = wp_json_encode(  $tt_response );

            $rename_array['taxonomy_terms'] = $response;
            $rename_array['taxonomy_types'] = $encoded_response;

         


            echo wp_kses_data(wp_json_encode($rename_array));

            wp_die();

	}


    private function _get_sortd_categories($taxonomy_slug=false){

         $api_slug = 'custompost/list-taxonomies/'.$taxonomy_slug;
        $response = Sortd_Helper::sortd_get_api_response($api_slug);
        $sorted_categories = json_decode($response);

        return $sorted_categories;
    }

    public function get_cat_child($id){

        $cat_id = $id;

        echo wp_kses_data($cat_id);
        $parent_cat_arg = array('hide_empty' => true, 'parent' => $cat_id );
        $children = get_terms('category',$parent_cat_arg);//category name
        return $children;
        
    }



    public function sortd_get_categories(){
        if(!check_ajax_referer('sortd-ajax-nonce-category', 'sortd_nonce')) {
            $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
            echo wp_kses_data($result); wp_die();
        }

        if(isset($_POST['taxonomy_slug'])) {
            $taxonomy_slug = sanitize_text_field($_POST['taxonomy_slug']);
        }

        $cats = $this->_get_sortd_categories($taxonomy_slug);
        $array = array();
        $array['categories'] = $cats;
      
        $get_term_children = array();

        if(isset($cats->data->categories)){
            foreach($cats->data->categories as $v){

                $get_term_children[$v->cat_guid]=  get_categories(
                    array( 'parent' => $v->cat_guid )
                ); 
            }
        }

        $array['children'] = $get_term_children;
     
        echo wp_json_encode($array);

        wp_die();

    }


    public function get_cat_children(){
        if(!check_ajax_referer('sortd-ajax-nonce-category', 'sortd_nonce')) {
            $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
            echo wp_kses_data($result); wp_die();
        }

        $cat_id = "";
        if(isset($_POST['id'])){
            $cat_id = sanitize_text_field($_POST['id']);
        }

        if(isset($_POST['taxonomy_slug'])){
            $taxonomy_slug = sanitize_text_field($_POST['taxonomy_slug']);
        }

        $parent_cat_arg = array('hide_empty' => false, 'parent' => $cat_id );
        $children = get_terms($taxonomy_slug,$parent_cat_arg);//category name
        echo wp_json_encode($children);
        wp_die();

    }


    public function get_all_heirarchy_cat_children(){
        if(!check_ajax_referer('sortd-ajax-nonce-category', 'sortd_nonce')) {
            $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
            echo wp_kses_data($result); wp_die();
        }

        $cat_id = "";
        if(isset($_POST['id'])){
            $cat_id = sanitize_text_field($_POST['id']);
        }

        if(isset($_POST['taxonomytypeslug'])){
            $taxonomytypeslug = sanitize_text_field($_POST['taxonomytypeslug']);
        }

        
        $get_all_children = get_term_children($cat_id,$taxonomytypeslug);
        echo wp_json_encode($get_all_children);
        wp_die();
    }
        

    public function check_for_synced(){
        if(!check_ajax_referer('sortd-ajax-nonce-category', 'sortd_nonce')) {
            $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
            echo wp_kses_data($result); wp_die();
        }

        $cat_flag = false;

        $ids = "";
        if(isset($_POST['id'])){
            $ids = array_map('sanitize_text_field',$_POST['id']);
        }
        $project_id = get_option('sortd_projectid');
        foreach($ids as $v){
            $get_option = get_option('sortd_'.$project_id.'_category_sync_'.$v);
            if($get_option === '1'){
                $cat_flag = true;
            }
        }
        echo wp_json_encode($cat_flag);
        wp_die();
    }

    public static function update_category_url_redirection() {
        if(!check_ajax_referer('sortd-ajax-nonce-category', 'sortd_nonce')) {
            $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
            echo wp_kses_data($result); wp_die();
        }
       

        $project_id = get_option('sortd_projectid');
        $article_url_redirection_flag = get_option('sortd_'.$project_id.'_article_url_redirection_flag');
        $category_url_redirection_flag = get_option('sortd_'.$project_id.'_category_url_redirection_flag');
        $category_url_canonical_url = get_option('sortd_'.$project_id.'_canonical_url_redirection_flag');
        $shorts_id = get_option('sortd_shorts_catid_'.$project_id);
     
        if ($article_url_redirection_flag !== false && ($article_url_redirection_flag === 1 || $article_url_redirection_flag === '1')) {
            // Option exists
            $article_toggle_value = 'true';
        } else {
            // Option does not exist
            $article_toggle_value = 'false';
        }
       
        if(isset($_POST['category_toggle_value'])){
            $category_toggle_value = sanitize_text_field($_POST['category_toggle_value']);
        } else {
            $category_toggle_value = $category_url_redirection_flag;
        }
        

        if($category_url_canonical_url !== false && ($category_url_canonical_url === 1 || $category_url_canonical_url === '1') ){
            $canonical_toggle_value = 'true';
        } else {
            $canonical_toggle_value = 'false';
        }


        $redirect_api_slug = 'project/update-redirection-settings';
      
        $params = '{
            "enable_category_in_article" : '.$article_toggle_value.',
            "enable_category_alias_url" : '.$category_toggle_value.' ,
            "self_canonical" : '.$canonical_toggle_value.',
            "shorts_category_id" : "'.$shorts_id.'"            
        }';
     
        $redirect_response = Sortd_Helper::sortd_post_api_response($redirect_api_slug, $params);
        $response = json_decode($redirect_response);



        if($response->status === true){

            update_option('sortd_'.$project_id.'_article_url_redirection_flag',$response->data[0]->enable_category_in_article);
            update_option('sortd_'.$project_id.'_category_url_redirection_flag',$response->data[0]->enable_category_alias_url);
            update_option('sortd_'.$project_id.'_canonical_url_redirection_flag',$response->data[0]->self_canonical);


        }

       

        echo wp_kses_data($redirect_response); wp_die();
    }   

    public static function update_article_url_redirection() {
        if(!check_ajax_referer('sortd-ajax-nonce-category', 'sortd_nonce')) {
            $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
            echo wp_kses_data($result); wp_die();
        }

        $project_id = get_option('sortd_projectid');
        $category_url_redirection_flag = get_option('sortd_'.$project_id.'_category_url_redirection_flag');
        $category_url_canonical_url = get_option('sortd_'.$project_id.'_canonical_url_redirection_flag');
        $shorts_id = get_option('sortd_shorts_catid_'.$project_id);


        
        if(isset($_POST['article_toggle_value'])){
            $article_toggle_value = sanitize_text_field($_POST['article_toggle_value']);
        } else {
            $article_toggle_value = get_option('article_url_redirection_flag');
        }

       
        if ($category_url_redirection_flag !== false && ($category_url_redirection_flag === 1 || $category_url_redirection_flag === '1' )) {
            // Option exists
            $category_toggle_value = 'true';
        } else {
            // Option does not exist
            $category_toggle_value = 'false';
        }


        if($category_url_canonical_url !== false && ($category_url_canonical_url === 1 || $category_url_canonical_url === '1' )){
            $canonical_toggle_value = 'true';
        } else {
            $canonical_toggle_value = 'false';
        }
        



        $redirect_api_slug = 'project/update-redirection-settings';
      
        $params = '{
            "enable_category_in_article" : '.$article_toggle_value.',
            "enable_category_alias_url" : '.$category_toggle_value.' ,
            "self_canonical" :'.$canonical_toggle_value.',
            "shorts_category_id" : "'.$shorts_id.'" 
        }';

        $redirect_response = Sortd_Helper::sortd_post_api_response($redirect_api_slug, $params);
        $response = json_decode($redirect_response);

        if($response->status === true){

            update_option('sortd_'.$project_id.'_article_url_redirection_flag',$response->data[0]->enable_category_in_article);
            update_option('sortd_'.$project_id.'_category_url_redirection_flag',$response->data[0]->enable_category_alias_url);
            update_option('sortd_'.$project_id.'_canonical_url_redirection_flag',$response->data[0]->self_canonical);


        }

        echo wp_kses_data($redirect_response); wp_die();
    }


    public function sync_web_cat(){

        if(!check_ajax_referer('sortd-ajax-nonce-category', 'sortd_nonce')) {
            $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
            echo wp_kses_data($result); wp_die();
        }
        $project_id = Sortd_Helper::get_project_id();
        if(isset($_POST['id']) && !empty($_POST['id'])) {
            $cat_id = sanitize_text_field($_POST['id']);
            $cat_title = "";

            $cat_data = get_terms(array(
                'taxonomy' => 'web_story_category',
                'hide_empty' => false,
                 'include' => array($cat_id),
            ));
            $cat_url = get_category_link($cat_id);
            $category_desc = wp_strip_all_tags(trim(category_description($cat_id)));

            $catinfo = $cat_data[0];

            if(is_plugin_active('wordpress-seo/wp-seo.php')) {
                $yoast_data = get_option( 'wpseo_taxonomy_meta');
                if(isset($yoast_data) && !empty($yoast_data)) {
                    foreach($yoast_data['category'] as $cid => $data) {
                        if($cid === $cat_id) {
                            if($data['wpseo_title']) {
                                $cat_title = $data['wpseo_title'];
                            }
                        }
                    }
                }
            } else {
               
            }

            if(!isset($cat_title)){
                 $cat_title = $catinfo->name;
            }

            $params = '{
                    "cat_guid" : "'.$cat_id.'",
                    "name" : "'.$catinfo->name.'",
                    "alias" : "'.urldecode($catinfo->slug).'",
                    "parent_guid" : 0,
                    "cat_desc" : "'.$category_desc.'",
                    "cat_url": "'.$cat_url.'",
                    "cat_title": "'.$cat_title.'"
            }';
        
        

            $cat_sync_api_slug = "contentsettings/wpstorycatsync";
            
            $response = Sortd_Helper::sortd_post_api_response($cat_sync_api_slug,$params,'v2');

            $response = json_decode($response);

            if($response->status===true){
                update_option('sortd_web_cat_sync_'.$cat_id.'_'.$project_id,1); 
            }

            echo wp_json_encode($response);
            wp_die();
        }

    }

    public function unsync_web_cat(){

        if(!check_ajax_referer('sortd-ajax-nonce-category', 'sortd_nonce')) {
            $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
            echo wp_kses_data($result); wp_die();
        }
        $project_id = Sortd_Helper::get_project_id();
        if(isset($_POST['id']) && !empty($_POST['id'])) {
            $cat_id = sanitize_text_field($_POST['id']);
            $cat_sync_api_slug = "contentsettings/wpstorycatremove";
                $params = '{
                    "cat_guid" : "'.$cat_id.'"
                }';
          
                $response = Sortd_Helper::sortd_post_api_response($cat_sync_api_slug,$params,'v2');

                $response = json_decode($response);
              
                if($response->status===true){
                    update_option('sortd_web_cat_sync_'.$cat_id.'_'.$project_id,1); 
                }

            echo wp_json_encode($response);

          wp_die();

        }
    }


    public function list_web_cats(){

        if(!check_ajax_referer('sortd-ajax-nonce-category', 'sortd_nonce')) {
            $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
            echo wp_kses_data($result); wp_die();
        }
        
        $api_slug = 'contentsettings/listwpstorycategories';
        $response = Sortd_Helper::sortd_get_api_response($api_slug,'v2');
        $sorted_categories = json_decode($response);


       echo wp_json_encode($sorted_categories);

       wp_die();
    }
       

    public function refresh_custom_column(){
        if(!check_ajax_referer('sortd-ajax-nonce-category', 'sortd_nonce')) {
            $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
            echo wp_kses_data($result); wp_die();
        }

        if(isset($_POST['categoryId']) && !empty($_POST['categoryId'])) {
            $cat_id=sanitize_text_field($_POST['categoryId']);
            $parts=explode('-',$cat_id);
            $category_id=end($parts);

        
            $project_id = get_option('sortd_projectid');
            $cat_option_value = Sortd_Helper::get_options_for_category($project_id,$category_id);
        
            $response= array('status' => $cat_option_value, 'value' => $category_id );
            echo wp_json_encode($response);wp_die();
        }
      
        

    }


    public function refresh_custom_column_for_tag(){

         if(!check_ajax_referer('sortd-ajax-nonce-category', 'sortd_nonce')) {
            $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
            echo wp_kses_data($result); wp_die();
        }

        if(isset($_POST['tagId']) && !empty($_POST['tagId'])) {    
            $tag_id=sanitize_text_field($_POST['tagId']);
            $parts=explode('-',$tag_id);
            $tag_id=end($parts);

            
            $project_id = get_option('sortd_projectid');
            $tag_option_value = Sortd_Helper::get_options_for_tag($project_id,$tag_id);
        
            $response= array('status' => $tag_option_value, 'value' => $tag_id );
            echo wp_json_encode($response);wp_die();
        }

    }

    public function check_parent_cat_sync(){
        if(!check_ajax_referer('sortd-ajax-nonce-category', 'sortd_nonce')) {
            $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
            echo wp_kses_data($result); wp_die();
        }

        $cat_id = '';
        $taxonomy_slug = '';
        if(isset($_POST['category']) && !empty($_POST['category'])) {
            $cat_id=sanitize_text_field($_POST['category']);
            if(isset($_POST['taxonomy_slug']) && !empty($_POST['taxonomy_slug'])) {
                $taxonomy_slug=sanitize_text_field($_POST['taxonomy_slug']);
            }
            $category = get_term($cat_id,  $taxonomy_slug);
        
            $parent_cat=$category->parent;

            if($parent_cat===0){
                $response=1;
                echo wp_json_encode($response);wp_die();
            }
            $project_id = get_option('sortd_projectid');
            
            $get_option = get_option('sortd_'.$project_id.'_category_sync_'.$parent_cat);

                if($get_option === '1'){
                    $response=1;
                }
                else{
                    $response=0;
                }
            echo wp_json_encode($response);wp_die();
        }
    }

    public static function update_canonical_url_redirection() {
        if(!check_ajax_referer('sortd-ajax-nonce-category', 'sortd_nonce')) {
            $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
            echo wp_kses_data($result); wp_die();
        }
        $project_id = get_option('sortd_projectid');
       
        $article_url_redirection_flag = get_option('sortd_'.$project_id.'_article_url_redirection_flag');
        $category_url_redirection_flag = get_option('sortd_'.$project_id.'_category_url_redirection_flag');
        $category_url_canonical_url = get_option('sortd_'.$project_id.'_canonical_url_redirection_flag');
        $shorts_id = get_option('sortd_shorts_catid_'.$project_id);
   
     
        if ($article_url_redirection_flag !== false && ($article_url_redirection_flag === 1 || $article_url_redirection_flag === '1')) {
            // Option exists
            $article_toggle_value = 'true';
        } else {
            // Option does not exist
            $article_toggle_value = 'false';
        }
       
        if ($category_url_redirection_flag !== false && ($category_url_redirection_flag === 1 || $category_url_redirection_flag === '1')) {
            // Option exists
            $category_toggle_value = 'true';
        } else {
            // Option does not exist
            $category_toggle_value = 'false';
        }
        

        if(isset($_POST['canonical_toggle_value'])){
            $canonical_toggle_value = sanitize_text_field($_POST['canonical_toggle_value']);
        } else {
            $canonical_toggle_value = $category_url_canonical_url;
        }

        $redirect_api_slug = 'project/update-redirection-settings';

       
      
        $params = '{
            "enable_category_in_article" : '.$article_toggle_value.',
            "enable_category_alias_url" : '.$category_toggle_value.' ,
            "self_canonical" : '.$canonical_toggle_value.',
            "shorts_category_id" : "'.$shorts_id.'"            
        }';

 
        
        $redirect_response = Sortd_Helper::sortd_post_api_response($redirect_api_slug, $params);
        $response = json_decode($redirect_response);

        if($response->status === true){

            update_option('sortd_'.$project_id.'_article_url_redirection_flag',$response->data[0]->enable_category_in_article);
            update_option('sortd_'.$project_id.'_category_url_redirection_flag',$response->data[0]->enable_category_alias_url);
            update_option('sortd_'.$project_id.'_canonical_url_redirection_flag',$response->data[0]->self_canonical);


        }

        echo wp_kses_data($redirect_response); wp_die();
    }


        /**
     * function for manage taxonomy screen
     *
     * @since    2.0.0
     */
    public function manage_taxonomies() {

                $args = array(
                'public'   => true,  // Show only public post types
                '_builtin' => false, // Exclude built-in post types
                'capability_type'=>'post'
               
            );

                $post_types = get_post_types($args,'objects');

                $wp_domain = get_site_url();
                $current_user = wp_get_current_user()->display_name;
                $project_details = Sortd_Helper::get_cached_project_details();
                $project_slug = $project_details->data->slug;

               
                $posttypes_array = array();
                foreach($post_types as $k => $v){


                    $posttypes_array[$k]['slug'] = $v->name;
                    $posttypes_array[$k]['name'] = $v->label;

                }

                $post_array = array();
                $post_array['post']['slug'] = 'post';
                $post_array['post']['name'] = 'Posts';

                $post_types_array = array_merge($post_array,$posttypes_array);
               
                 $taxonomy_array = array();
                foreach($post_types_array as $key => $value){

                    $post_type = $value['slug']; // Replace with the post type name
                    $taxonomies = get_object_taxonomies($post_type,'objects');


                   
                    foreach($taxonomies as $taxk => $taxv){
    

                         $taxonomy_array[$key]['postype_name'] = $value['name'];
                         $taxonomy_array[$key]['postype_slug'] = $value['slug'];

                        if(!empty($taxv->label)){
                            $taxonomy_array[$key]['taxonomy_type'][$taxk]['taxonomy_name'] =  $taxv->label;
                            $taxonomy_array[$key]['taxonomy_type'][$taxk]['taxonomy_slug'] =  $taxv->name;

                        }

                    }

                }

                

            $view_data = array();
            $view_data['html_data'] =$taxonomy_array;
            $view_data['wp_domain'] = $wp_domain;
            $view_data['current_user']= $current_user;
            $view_data['project_slug'] = $project_slug;





            Sortd_Helper::render_partials(array('sortd-taxonomies-manage'), $view_data);

    }


    public function sortd_sync_taxonomy_type(){
        if(!check_ajax_referer('sortd-ajax-nonce-category', 'sortd_nonce')) {
            $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
            echo wp_kses_data($result); wp_die();
        }

        if(isset($_POST['post_name'])) {
            $post_name = sanitize_text_field($_POST['post_name']);
        }
        if(isset($_POST['taxonomy_name'])) {
            $taxonomy_name = sanitize_text_field($_POST['taxonomy_name']);
        }
        if(isset($_POST['taxonomy_slug'])) {
            $taxonomy_slug = sanitize_text_field($_POST['taxonomy_slug']);
        }
        if(isset($_POST['post_slug'])) {
            $post_slug = sanitize_text_field($_POST['post_slug']);
        }
        if(isset($_POST['check_flag'])) {
            $check_flag = sanitize_text_field($_POST['check_flag']);
        }

      
        if($check_flag === 'true'){
        $result =  $this->sync_taxonomy($taxonomy_name,$taxonomy_slug,$post_slug,$post_name);


        if($result->status === true){

            
           $options_array = get_option('sortd_customposts');

           if(isset( $options_array) && !empty($options_array)){


               array_push($options_array,$post_slug);
           
           } else {

            
              $options_array =array();

               array_push($options_array,$post_slug);


           }

           $array_unique = array_unique($options_array);

    
            update_option('sortd_customposts',$array_unique);

            $option_names = 'sortd_taxonomy_'.$post_slug;
            
            $options_array_taxonomy = get_option($option_names);

            if(isset( $options_array_taxonomy) && !empty($options_array_taxonomy)){


               array_push($options_array_taxonomy,$taxonomy_slug);
           
           } else {

            
              $options_array_taxonomy =array();

               array_push($options_array_taxonomy,$taxonomy_slug);


           }

            $array_unique_taxonomy = array_unique($options_array_taxonomy);

            update_option( $option_names,$array_unique_taxonomy);


        }




        } else {

           $result = $this->unysnc_taxonomy($taxonomy_slug);

          
            if($result->status === true){


                $options_array = get_option('sortd_customposts');

                $taxonomies_option = get_option('sortd_taxonomy_'.$post_slug);

                $count = count($taxonomies_option);

            

                if($count <= 1){

                    delete_option('sortd_taxonomy_'.$post_slug);

                    $key_posttype = array_search($post_slug, $options_array,true);


                    if ($key_posttype !== false) {
                        unset($options_array[$key_posttype]);
                    }

                    update_option('sortd_customposts',$options_array);

                   
                } else {

               
                    $key = array_search($taxonomy_slug, $taxonomies_option,true);


                    if ($key !== false) {
                        unset($taxonomies_option[$key]);
                    }
                    update_option('sortd_taxonomy_'.$post_slug,$taxonomies_option);



                   
                }
           

             


                
            }
         

        }

        echo wp_json_encode($result);

        wp_die();



    }


    public function sync_taxonomy($taxonomy_name,$taxonomy_slug,$post_slug,$post_name){

        $taxonomy_api_slug = 'custompost/sync-taxonomy-type';

       
      
         $params = '{ 

                "taxonomy_type_obj":{
                    "name" : "'.$taxonomy_name.'",
                    "slug" : "'.$taxonomy_slug.'" ,
                    "post_type_slug" : "'.$post_slug.'"          
                },
                "post_type_obj" : {
                        "name" : "'.$post_name.'",
                        "slug" : "'.$post_slug.'"
                    }



        }';

       
        
        $taxonomy_response = Sortd_Helper::sortd_post_api_response($taxonomy_api_slug, $params);
        $response = json_decode($taxonomy_response);

        return $response;

    }


    public function unysnc_taxonomy($taxonomy_slug){

        $taxonomy_api_slug = 'custompost/unsync-taxonomy-type'; 

        $params = '{ 
                
                "taxonomy_type_slug" : "'.$taxonomy_slug.'"
                
            }';

           
        $taxonomy_response = Sortd_Helper::sortd_post_api_response($taxonomy_api_slug, $params);
        $response = json_decode($taxonomy_response);

     
        return $response;


    }


    public function sortd_get_taxonomy_view(){

        if(!check_ajax_referer('sortd-ajax-nonce-category', 'sortd_nonce')) {
            $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
            echo wp_kses_data($result); wp_die();
        }
        if(isset($_POST['taxonomy_slug'])) {
            $taxonomy_slug = sanitize_text_field($_POST['taxonomy_slug']);
        }


            $taxonomy     = $taxonomy_slug;

            $orderby      = 'name';  
            $show_count   = 0;      
            $pad_counts   = 0;      
            $hierarchical = 1;     
            $title        = '';  
            $empty        = 0;

            $args = array(
                   'taxonomy'     => $taxonomy,
                   'orderby'      => $orderby,
                   'show_count'   => $show_count,
                   'pad_counts'   => $pad_counts,
                   'hierarchical' => $hierarchical,
                   'title_li'     => $title,
                   'hide_empty'   => $empty
            );
            
            $this->all_categories = get_categories( $args );
             $project_id = Sortd_Helper::get_project_id();

            $flag =  $this->getHtml(0,0, $project_id,$taxonomy_slug);
            $arr = array(
                'tr' => array(
                    'id' => array(),
                    'data-parent' => array(),
                    'data-cat_id' => array(),
                    'data-sync_flag' => array()
                ),
                'td'=> array(
                    'class' => array(),
                    'data-sortdindex' => array(),
                    'id' => array(), 
                    'style' => array(),
                ),
                'label' => array(
                    'class' => array()
                ),
                'input' => array(
                    'data-parent' => array(),
                    'type' => array(),
                    'name' => array(),
                    'class' => array(),
                    'id' => array(),
                    'data-nonce' => array(),
                    'data-taxonomytypeslug' => array(),
                    'disabled' => array(),
                    'checked' => array()
                ),
                'span' => array(
                    'class' => array()
                ),
                'img' => array(
                    'style' => array(),
                    'class' => array(),
                    'src' => array()
                )
            );
            if($flag === true){
                $render_html =  $this->html;
                echo wp_kses($render_html, $arr);
            }
    }


    public function sortd_get_synced_taxonomytype_list(){

        if(!check_ajax_referer('sortd-ajax-nonce-category', 'sortd_nonce')) {
            $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
            echo wp_kses_data($result); wp_die();
        }

        $taxonomy_api_slug = 'custompost/list-taxonomy-types'; 

           
        $taxonomy_response = Sortd_Helper::sortd_get_api_response($taxonomy_api_slug);
        $response = json_decode($taxonomy_response);

     
       echo wp_json_encode($response);
       wp_die();
    }


    public function sortd_get_synced_taxonomomies(){

        if(!check_ajax_referer('sortd-ajax-nonce-category', 'sortd_nonce')) {
            $result = '{"status":false,"error":{"message":"Sorry, your nonce did not verify","errorCode":403}}';
            echo wp_kses_data($result); wp_die();
        }
        if(isset($_POST['taxonomy_slug'])) {
            $taxonomy_slug = sanitize_text_field($_POST['taxonomy_slug']);
        }


        $taxonomytype_api_slug = 'custompost/list-taxonomy-types';
           

        $taxonomytype_response = Sortd_Helper::sortd_get_api_response($taxonomytype_api_slug);

        $decode_res = json_decode($taxonomytype_response);



         foreach($decode_res->data->taxonomy_types as $k => $v){

            if (!taxonomy_exists($v->slug)) {
                unset($decode_res->data->taxonomy_types[$k]);
            } else {
                if(sizeof( get_terms(array('taxonomy' => $v->slug,'hide_empty' => false)) ) === 0){
                    $decode_res->data->taxonomy_types[$k]->count =  0;
                } else {
                    $decode_res->data->taxonomy_types[$k]->count =  $v->count;
                }
            }
          
        }

        $encoded_response = wp_json_encode(  $decode_res );
        $taxonomy_api_slug = 'custompost/list-taxonomies/'.$taxonomy_slug; 

           
        $taxonomy_response = Sortd_Helper::sortd_get_api_response($taxonomy_api_slug);
        $response = json_decode($taxonomy_response);

        $response_array = array();
        $response_array['taxonomy_terms'] = $response;
        $response_array['taxonomy_types'] = $encoded_response;

     
       echo wp_kses_data(wp_json_encode($response_array));
       wp_die();
    }

    public function unysnc_taxonomy_term($term_id, $taxonomy_id, $taxonomy){
        
        self::delete_child_taxonomies($taxonomy, $term_id);
        $project_id = Sortd_Helper::get_project_id();
        $cat_sync_api_slug = "custompost/unsync-taxonomy";
        $params = '{
            "taxonomy_guid" : "'.$term_id.'"
        }';
    
        $response_data = Sortd_Helper::sortd_post_api_response($cat_sync_api_slug,$params);
        $response = json_decode($response_data);
       
      
        if($response->status===true){

            Sortd_Helper::create_options_for_category($project_id,$term_id,0,''); 
        }

        

    }
}
