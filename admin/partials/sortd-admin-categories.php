<?php
if ( ! defined( 'ABSPATH' ) ) exit; 
   $taxonomy     = 'category';
   $orderby      = 'name';  
   $show_count   = 0;      // 1 for yes, 0 for no
   $pad_counts   = 0;      // 1 for yes, 0 for no
   $hierarchical = 1;      // 1 for yes, 0 for no  
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
   $all_categories = get_categories( $args );
   //echo "<pre>";print_r((object)$response);die;
   $projectId = Sortd_Helper::getProjectId();
   
   
   ?>

<?php if(isset($response->error->errorCode)){ if($response->error->errorCode == 408){ ?>
<div class="notice notice-error is-dismissible curlErrorDiv">
   <p><?php echo esc_attr($response->error->message);?></p>
</div>
<?php } } else { ?>
<!-- <div class="update-nag notice notice-warning inline">Go 00to all post section and sync the post  - <a href="<?php //echo admin_url().'edit.php';?>">Sync Posts to SORTD</a>.</div> -->
<style type="text/css">
   .second-heading h5{
   margin: 25px 0 0 0;
   display: inline-block;
   font-size: 17px;
   font-family: 'Barlow', sans-serif;
   }
   .mt-25{
   margin-top: 25px;
   }
   .bgHed{
   background: #ff00cc;
   background: -webkit-linear-gradient(to left, #ff00cc, #005BF0);
   background: linear-gradient(to left, #ff00cc, #005BF0);
   border-radius: 10px;
   overflow: hidden;
   font-family: 'Barlow', sans-serif;
   }
   .content-card{
   font-family: 'Barlow', sans-serif;
   }
   .headingNameTop{
   float: right;
   margin-top: 20px;
   }
   .headingNameTop .nextStep .goLnk{
   background: #005BF0;
   padding: 16px 60px;
   border-radius: 4px;
   width: 100%;
   font-size: 1em;
   line-height: 1em;
   height: auto;
   border-bottom: 4px solid rgba(0, 0, 0,0.2);
   }
</style>


<div class="content-section categorysyncmanage" style="display:none">
   <input type="hidden" id="pluginurlpath" value="<?php echo  plugin_dir_url( __DIR__ );?>">
   <div class="container-pj ">
      <div class="menuContent-area">
         <div id="General" class="tabcontent" style="display:block">
            <div class="inerContent-body">
               <!-- main heading tabing start -->
               <div class="heading-main">
                  <div class="logoLft">
                     <img src="<?php echo plugin_dir_url( __DIR__ );?>css/logo.png">
                     <h5> Select Categories </h5>
                  </div>
               </div>
               <!-- main heading tabing end -->
               <div class="second-heading">
                  <h5>Select which category you want to display on mobile</h5>
               </div>
               <!-- content-section-inner start -->
               <div class="inner-sectn-body mt-25">
                  <!-- left menu start -->
                  <!-- <div class="leftMenu" id="sticky-menu-box">
                     <nav class="navigation_sectn" >
                                         <a class="navigation__link active" href="#1">Select Categories</a>
                                         
                                     
                                       </nav>
                     
                                    
                     	</div> -->
                  <!-- <h2 class="card-titl">Wordpress Categories</h2> -->
                  <div class="content-card">
                     <div class="form-box">
                        <input type ="hidden" id="siteurl" value="<?php echo site_url();?>">
                        <table class="table ">
                           <thead class="bgHed">
                              <tr>
                                 <th class="headth" scope="col">Wordpress Categories</th>
                                 <th class="headth" scope="col">Sync</th>
                                 <th class="headth" scope="col">Sortd Categories (PWA/AMP)</th>
                                 <th class="thtick" scope="col" style="display:none"></th>
                              </tr>
                           </thead>
                           <tbody class="categorytbody">
                              <?php foreach ($all_categories as $cat) {
                                 $category_id = $cat->term_id;
                                 
                                 
                                 //$valueopt = get_option('sortd_'.$project_id.'_category_sync_'.$category_id);
                                 
                                 $valueopt = Sortd_Helper::getOptionsForCategory($projectId,$category_id);
                                 
                                 if($valueopt == 1){
                                 	$valueChecked='checked';
                                 } else{
                                 	$valueChecked = '';
                                 }
                                 
                                 $valueoptSortdId = Sortd_Helper::getOptionsForCategoryId($projectId,$category_id);
                                 
                                 
                                 ?>
                              <tr>
                                 <td class="inputMsg catDynamic_<?php echo esc_attr($cat->term_id);?>" data-sortdindex="<?php echo esc_attr($valueoptSortdId);?>" id="<?php echo esc_attr($cat->term_id) ?>"><?php echo esc_attr($cat->name);?>
                                 </td>
                                 <td>
                                    <label class="switch-tog">
                                    <input type="checkbox" name="checkbox" <?php echo esc_attr($valueChecked); ?> class="sortCatCheck sortcheckclass<?php echo esc_attr($cat->term_id); ?>" id="<?php echo esc_attr($cat->term_id); ?>" data-nonce="<?php echo wp_create_nonce('rw-sortd-cat-sync-'.$cat->term_id);?>">
                                    <span class="slider-tog round"></span>
                                    </label><!-- <input type="checkbox" name="checkbox" <?php //echo $valueChecked ?> class="sortCatCheck sortcheckclass<?php //echo $cat->term_id ?>" id="<?php //echo $cat->term_id ?>"> -->
                                 </td>
                                 <td>
                                    <?php foreach ($response['data'] as  $valueC) {
                                       if($valueC['cat_guid'] == $cat->term_id){ 
                                       	//echo $cat->term_id;
                                       	?>
                                    <h6 class = "catSyncHead_<?php echo esc_attr($cat->term_id); ?>"><?php echo esc_attr($valueC['name']);?></h6>
                                    <?php }  else { 
                                       if($valueC['sub_categories']){ 
                                       
                                       	foreach ($valueC['sub_categories'] as $valueSub) {
                                       		if($valueSub['cat_guid'] == $cat->term_id){
                                       
                                       			echo '<h6 class = "catSyncHead_'.esc_attr($cat->term_id).'">'.esc_attr($valueSub['name']).'</h6>	';
                                       		}
                                       	}
                                       }
                                       } ?>
                                    <?php  } ?>
                                    <span id="catSpan_<?php echo esc_attr($cat->term_id);?>" style="display:none"><?php echo esc_attr($cat->name);?></span>
                                 </td>
                                 <td class="succmsg" style="display:none;"><img style="display:none;" class="img<?php echo esc_attr($cat->term_id);?>" src="<?php echo plugin_dir_url( __DIR__ );?>css/check.png"></td>
                              </tr>
                              <?php 
                                 }
                                 ?>
                           </tbody>
                        </table>
                     </div>
                  </div>

                  <div style="width:100%;float: left; margin-top: 15px">
	                  <a class="btn btn-ad-dflt" href="<?php echo admin_url().'edit.php';?>">Sync Older Posts</a>
	               </div>

               </div>
            </div>
         </div>
      </div>
   </div>
   <?php  } ?>
</div>
