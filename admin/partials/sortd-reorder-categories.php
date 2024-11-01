<?php
   if ( ! defined( 'ABSPATH' ) ) exit; 
   ?>
<style type="text/css">
   .contentMenu-ful{
   font-family: 'Google Sans', sans-serif !important;
   }
   .editmode {display:none;}
   #sortable { list-style-type: none; margin: 0; padding: 0; width: 60%; }
   #sortable_list li,.sortable_list li { font-size: 12px;margin: 0 3px 4px;padding: 5px 0.4em 5px 1.5em;cursor: move;}
   #sortable_list li span,.sortable_list li span { position: absolute; margin-left: -1.3em; }
   .mjs-nestedSortable-error {
   background: #fbe3e4;
   border-color: transparent;
   }
   #tree {
   width: 550px;
   margin: 0;
   }
   /* ol {
   width: 450px;
   padding-left: 25px;
   margin-bottom: 1em !important;
   }*/
   ol li ol{
   padding-left: 35px;
   }
   ol.sortable,ol.sortable ol {
   list-style-type: none;
   }
   .sortable li div {
   border: 1px solid #d4d4d4;
   -webkit-border-radius: 3px;
   -moz-border-radius: 3px;
   border-radius: 3px;
   /*cursor: move;*/
   border-color: #D4D4D4 #D4D4D4 #BCBCBC;
   margin: 0;
   padding: 6px;
   margin-top: 6px;
   background: #fff
   }
   li.mjs-nestedSortable-collapsed.mjs-nestedSortable-hovering div {
   border-color: #999;
   }
   .disclose, .expandEditor {
   cursor: pointer;
   /* width: 20px;*/
   display: inline-block;
   }
   .sortable li.mjs-nestedSortable-collapsed > ol {
   display: none;
   }
   .sortable li.mjs-nestedSortable-branch > div > .disclose {
   display: inline-block;
   }
   .sortable span.ui-icon {
   display: inline-block;
   margin: 0;
   padding: 0;
   }
   .headingspanclass {
   width: 37%;
   display: inline-block;
   font-size: 15px;
   font-family: 'Barlow', sans-serif;
   }
   .editclickicon {
   text-align: right;
   padding-right: 19px !important;
   width: 22%;
   }
   .editclassspan{
   display: inline-block;
   width: 30%;
   margin-right: 10px;
   }
   .spansubhead{
   display: inline-block;
   width: 32.5%;
   font-size: 15px;
   font-family: 'Barlow', sans-serif;
   }
   .ui-sortable {
   margin-left: 0px;
   float: left;
   width: 100%;
   }
   .crossicon {
   float: right;
   padding-right: 16px;
   }
   .renameandreorderheadings {
   width: 100%;
   float: left;
   margin-bottom: 10px;
   padding: 10px 15px;
   /*border-radius: 5px;*/
   background: #9a69e0; /* Old browsers */
   background: -moz-linear-gradient(left,  #9a69e0 0%, #317fec 100%); /* FF3.6-15 */
   background: -webkit-linear-gradient(left,  #9a69e0 0%,#317fec 100%); /* Chrome10-25,Safari5.1-6 */
   background: linear-gradient(to right,  #9a69e0 0%,#317fec 100%);
   }
   .renameandreorderheadings span:first-child {
   width: 40%;
   }
   .renameandreorderheadings span{
   width: 29.8%;
   float: left;
   color: #fff;
   font-size: 16px;
   font-weight: 600;
   }
   .renameandreorderheadings span:last-child {
   text-align: right;
   }
   .editclickicon.spansubhead {
   /* padding-right: 16px !important;*/
   }
   .editsubcat {
   width: 29%;
   padding-right: 0px !important;
   }
   .renameandreorderheadings{
   background: #ff00cc;
   background: -webkit-linear-gradient(to left, #ff00cc, #005BF0);
   background: linear-gradient(to left, #ff00cc, #005BF0);
   }
   .headingNameTop {
   float: right;
   margin-top: 4px;
   }
   /*.saveBtn{
   background: #005BF0;
   border-bottom: 4px solid rgba(0, 0, 0,0.2);
   }*/
   /*.btn-cancl{
   color: #005BF0 !important;
   background: transparent !important;
   }*/
</style>
<div class="content-section categoryreordermanage" style="display:none">
  <div class="container-pj">
        <?php if(isset($response['error']['errorCode']) && $response['error']['errorCode'] == 408){ ?>
      <!--   <div class="notice notice-error is-dismissible curlErrorDiv">
           <p><?php //echo esc_attr($response['error']['message']);?></p>
        </div> -->
        <?php } else { if(get_option('sortd_sync_reorder_status') == 1) { ?>
        <div class="notice notice-success is-dismissible configPopup">
           <p>Category Reorder Successfully</p>
        </div>
        <?php } else if(get_option('sortd_sync_reorder_status') == 2 ){ $decode = $response['status']; ?>
        <!-- <div class="notice notice-error is-dismissible sd">
           <p><?php //echo esc_attr($decode['error']['message']);?> </p>
        </div> -->
        <?php }update_option('sortd_sync_reorder_status',0);?>
     <div class="menuContent-area">
        <div id="General" class="tabcontent" style="display:block">
          <input type="hidden" id="pluginurlpath" value="<?php echo  plugin_dir_url( __DIR__ );?>">
           <div class="inerContent-body">
              <!-- main heading tabing start -->
              <div class="heading-main">
                 <div class="logoLft">
                    <img src="<?php echo plugin_dir_url( __DIR__ );?>css/logo.png">
                    <h5> Reorder / Rename Categories</h5>
                 </div>
                 <div class="headingNameTop">
                    <div class="nextStep">Design Config Setup - <a class="goLnk" href="<?php echo admin_url().'admin.php?page=sortd-manage-settings&section=sortd_config';?>"><i class="bi bi-box-arrow-up-right" ></i></a>
                    </div>
                 </div>
              </div>
              <!-- main heading tabing end -->
              <!--   <div class="second-heding">
                 <h5>You can reorder and rename the categories from this page</h5>
                 </div>
                 -->
              <!-- content-section-inner start -->
              <div class="inner-sectn-body mt-30">
                
                <!-- content menu left start -->
                <div class="contentMenu-ful" id="stickContnt">
                  <div class="page-section-a hero" id="1">
                                 <!--  <h2 class="navigation__link active">SORTD Categories</h2> -->
                                  <div class="content-card">

                                    <?php if(empty($categories)) { ?> 

                                        <h5 class="">No categories synced</h5>

                                    <?php } ?>
                                   
                                    <div class="form-box">
                                      <?php wp_nonce_field('rw-sortd-reorder-cat', 'sortd-hidden-nonce'); ?>
                                     <!--  <input type="hidden" id="param" name="param" value="categoryReorder"/> -->
                                      <input type="hidden" id="category_order_old" name="category_order_old" value='<?php echo esc_attr($category_order); ?>'/>
                                      <input type="hidden" id="category_order" name="category_order" value='<?php echo esc_attr($category_order); ?>'/> 
                                       <input type="hidden" id="hiddenurl" name="urlhiddeb" value='<?php echo admin_url(); ?>'/>                                       

          <div class="category_list">
            <div class="renameandreorderheadings">
              <span class="renameandreorderspan">Category Name</span>
              <span class="renameandreorderspan">Category Alias</span>
              <span class="renameandreorderspan">Edit</span>
            </div>

        
            <ol class="sortable ui-sortable mjs-nestedSortable-branch mjs-nestedSortable-expanded  catreorderrenameol">
             
            </ol>
            </div>                                      

                                    </div>
                              <div class="butn-area-1">
                      <?php if(!empty($categories)) { ?> 

                    <button title="Click here to save reordered categories"  class="btn btn-ad saveBtn reorderClick">Save</button>
                    <button type="button"  class="btn btn-cancl cancelRedorder">Cancel</button>


                  <?php } ?>
                  </div>
           
                              </div>
   
                    
                  </div>
                  <!-- content menu left end -->
                </div>
                <!-- content section inner end -->

              </div>
           </div>
        </div>
        <?php } ?>
     </div>
  </div>
</div>


