<?php
/**
 * Provide a config display form for the plugin
 *
 * This file is used to markup the config aspects of the plugin.
 *
 * @link       https://www.sortd.mobi
 * @since      2.0.0
 *
 * @package    Sortd
 * @subpackage Sortd/admin/partials
 */

 $wp_domain = get_site_url();
 $sortd_current_user = wp_get_current_user()->display_name;
 $project_details = Sortd_Helper::get_cached_project_details();
 $project_slug = $project_details->data->slug;
?>
 <div id="tabcontent_id_<?php echo wp_kses_data($config_schema_group_key); ?>"  class="tabcontent" style="display:block">
								
    <div class="inerContent-body">
        <form id="form<?php echo wp_kses_data($config_schema_group_key); ?>" method="post" action="" >

                            <!-- main heading tabing start -->
            <div class="heading-main">
                    <div class="headingName">
                            <h2><?php echo wp_kses_data($config_schema_group_value->label); ?></h2>
                    </div>
            </div>

                <!-- main heading tabing end -->
            <div class="second-heding">
                        <h5>Change the basic site settings</h5>
             </div>

            <!-- content-section-inner start -->
            <div class="inner-sectn-body">
                    <!-- left menu start -->

                    <div class="leftMenu stickkey<?php echo wp_kses_data($config_schema_group_key);?>" id="sticky-menu-box">

                            <nav class="navigation_sectn" >

                            <?php 
                                $menu_count = 0;
                                foreach ($config_schema_group_value->items as $vertical_menu_key => $vertical_menu_details) { 
                                    $vertical_menu_active_class = '';
                                    if($menu_count===0){
                                        $vertical_menu_active_class = 'active';
                                    }
                                    $menu_count++;
                                    ?>
                                <a class="navigation__link nav_<?php echo wp_kses_data($vertical_menu_key);?> <?php echo wp_kses_data($vertical_menu_active_class);?>" href="#" id="navlink_<?php echo wp_kses_data($vertical_menu_key);?>" data-nav-id="<?php echo wp_kses_data($vertical_menu_key);?>"><?php echo wp_kses_data($vertical_menu_details->label);?></a>

                            <?php } ?>

                          </nav>

                     </div>

                   <div class="contentMenu-left contentMenu_<?php echo wp_kses_data($config_schema_group_key);?>" id="stickContnt" data-div = "<?php echo wp_kses_data($config_schema_group_key);?>">

                        <?php 
                        
                            foreach ($config_schema_group_value->items as $first_group_level => $first_group_fields_details) { 
                                
                                   
                                    if(!isset($first_group_fields_details->items)){
                                        //first level
                    
                                        ?>
                                        <div class="page-section-a hero" id="page_section_<?php echo wp_kses_data($first_group_level); ?>" data-pageDiv="<?php echo wp_kses_data($first_group_level);?>">
                                        <h2 class="card-titl ssss"><?php echo  wp_kses_data($first_group_fields_details->label); ?></h2>
                                        <div class="content-card" id="div_content_card_<?php echo  wp_kses_data(str_replace(' ','_',$first_group_fields_details->label));?>"> 
                                            <?php
                                            
                                        $view_data = array();
                                        $view_data['categories'] = $categories;
                                        $view_data['field_details'] = $first_group_fields_details;
                                        $view_data['field_parent'] = '';
                                        $view_data['field_sub_parent'] = '';
                                        $view_data['field_child'] = $first_group_level;
                                        $view_data['current_group_saved_config'] = $current_group_saved_config;
                                        $view_data['current_group'] = $config_schema_group_key;

                                        Sortd_Helper::render_partials(array('field-details'), array('view_data'=>$view_data), 'config');
                                        
                                    }else{
                                        ?>
                                        <div class="page-section-a hero" id="page_section_<?php echo wp_kses_data($first_group_level); ?>">
                                        <h2 class="card-titl"><?php echo  wp_kses_data($first_group_fields_details->label); ?><?php if(isset($first_group_fields_details->helptext) && !empty($first_group_fields_details->helptext)) { ?>
                                            <span data-icon="eva-question-mark-circle-outline" data-inline="false" data-toggle="tooltip"><label class="tooltiplabel">
                                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24" class="iconify" data-icon="eva-question-mark-circle-outline" data-inline="false" style="transform: rotate(360deg);">
                                                            <title><?php echo wp_kses_data($first_group_fields_details->helptext);?></title>
                                                            <g fill="currentColor">
                                                                    <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 8-8a8 8 0 0 1-8 8z"></path>
                                                                    <path d="M12 6a3.5 3.5 0 0 0-3.5 3.5a1 1 0 0 0 2 0A1.5 1.5 0 1 1 12 11a1 1 0 0 0-1 1v2a1 1 0 0 0 2 0v-1.16A3.49 3.49 0 0 0 12 6z"></path>
                                                                    <circle cx="12" cy="17" r="1"></circle></g></svg></label></span>

                                             <?php } ?>
                                        </h2>
                                        <div class="content-card" id="div_content_card_<?php echo  wp_kses_data($first_group_level);?>">
                                        <?php
                                        
                                        foreach($first_group_fields_details->items as $second_group_level => $second_group_fields_details){
                                            if(!isset($second_group_fields_details->items)){
                                                //second level
                                               
                                                $view_data = array();
                                                $view_data['categories'] = $categories;
                                                $view_data['field_details'] = $second_group_fields_details;
                                                $view_data['field_parent'] = '';
                                                $view_data['field_sub_parent'] = $first_group_level;
                                                $view_data['field_child'] = $second_group_level;
                                                $view_data['current_group_saved_config'] = $current_group_saved_config;
                                                $view_data['current_group'] = $config_schema_group_key;
                                                
                                                Sortd_Helper::render_partials(array('field-details'), array('view_data'=>$view_data), 'config');
                                                
                                            }else{
                                                ?>
                                                <h2 class='card-titl'><?php echo wp_kses_data($second_group_fields_details->label); ?><?php if(isset($second_group_fields_details->helptext) && !empty($second_group_fields_details->helptext)) { ?>
                                                    <span data-icon="eva-question-mark-circle-outline" data-inline="false" data-toggle="tooltip"><label class="tooltiplabel">
                                                            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" aria-hidden="true" focusable="false" width="1em" height="1em" preserveAspectRatio="xMidYMid meet" viewBox="0 0 24 24" class="iconify" data-icon="eva-question-mark-circle-outline" data-inline="false" style="transform: rotate(360deg);">
                                                                    <title><?php echo wp_kses_data($second_group_fields_details->helptext);?></title>
                                                                    <g fill="currentColor">
                                                                            <path d="M12 2a10 10 0 1 0 10 10A10 10 0 0 0 12 2zm0 18a8 8 0 1 1 8-8a8 8 0 0 1-8 8z"></path>
                                                                            <path d="M12 6a3.5 3.5 0 0 0-3.5 3.5a1 1 0 0 0 2 0A1.5 1.5 0 1 1 12 11a1 1 0 0 0-1 1v2a1 1 0 0 0 2 0v-1.16A3.49 3.49 0 0 0 12 6z"></path>
                                                                            <circle cx="12" cy="17" r="1"></circle></g></svg></label></span>

                                                     <?php } ?>
                                                </h2>
                                                <?php
                                                
                                                foreach($second_group_fields_details->items as $third_group_level => $third_group_fields_details){
                                                    //third level
                                                    $view_data = array();
                                                    $view_data['categories'] = $categories;
                                                    $view_data['field_details'] = $third_group_fields_details;
                                                    $view_data['field_parent'] = $first_group_level;
                                                    $view_data['field_sub_parent'] = $second_group_level;
                                                    $view_data['field_child'] = $third_group_level;
                                                    $view_data['current_group_saved_config'] = $current_group_saved_config;
                                                    $view_data['current_group'] = $config_schema_group_key;
                                                    
                                                    Sortd_Helper::render_partials(array('field-details'), array('view_data'=>$view_data), 'config');
                                                }
                                            }
                                        }
                                    }
                                    ?>
                                                </div>
                                             </div>
                                    <?php
                            }

                           ?>

                    </div>
                                        
                                        
                <div class="butn-area savefixed_btndivs">
                        <img class="ldGf" src="<?php echo wp_kses_data(SORTD_CSS_URL);?>/load.gif" style="display:none">
                        <h1><?php site_url()  ?></h1>
                        <div class="alert alert-danger is-dismissible config_not_saved" style="display:none;"></div>
                            <a class="btn btn-ad" data-nonce="<?php echo esc_attr(wp_create_nonce(SORTD_NONCE)) ?>" data-schema="<?php echo esc_attr($config_schema_group_key) ?>" id="cancelbtnid">Cancel</a>
                        <button  class="btn btn-ad saveBtn saveConfigBtn" data-nonce="<?php echo wp_kses_data(wp_create_nonce(-1)); ?>" data-btn="<?php echo wp_kses_data($config_schema_group_key); ?>" data-current_user="<?php echo esc_attr($sortd_current_user); ?>" data-wp_domain="<?php echo esc_attr($wp_domain); ?>" data-project_slug="<?php echo esc_attr($project_slug); ?>"> Publish </button>
                        
                </div>                        
        </form>
        </div>
        <script>
            jQuery(document).on('click', '#cancelbtnid', function(event) {
                let nonceValue = $(this).data('nonce');
                let siteurl = $("#site_url").val();
                let currentConfigGroup = $(this).data('schema');
                location.href = siteurl + "/wp-admin/admin.php?page=sortd-manage-settings&section=sortd_config&parameter=" + currentConfigGroup + "&_wpnonce=" + nonceValue;
            });
        </script>

    


        <!-- </div> -->

    </div>


