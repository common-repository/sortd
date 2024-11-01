<?php

/**
 * Provide a categories - sync view for the plugin
 *
 * This file is used to markup the categories - sync aspects of the plugin.
 *
 * @link       https://www.sortd.mobi
 * @since      2.0.0
 *
 * @package    Sortd
 * @subpackage Sortd/admin/partials
 */
?>
<?php
if ( ! defined( 'ABSPATH' ) ) exit; 
 
?>

<?php if(isset($response->error->errorCode)){ if($response->error->errorCode === 408){ ?>
<div class="notice notice-error is-dismissible curlErrorDiv">
   <p><?php echo wp_kses_data($response->error->message);?></p>
</div>
<?php } } else { ?>

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
   .synCatg .bgHed {
    width: 100%;
    float: left;
    border-radius: 0px;
    padding: 10px 15px;
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
   .synCatg {
    width: 100%;
    float: left;
    display: block;
}
.synCatg .bgHed th {
    padding: 3px;
}
.synCatg .bgHed tr{
   width: 100%;
   float: left;
}
.synCatg .bgHed {
    width: 100%;
    float: left;
}
.synCatg .categorytbody {
    width: 100%;
    float: left;
}
.synCatg .categorytbody tr {
    width: 100%;
    float: left;
    border-bottom: 1px solid #DEE2E6;
}
.synCatg .categorytbody tr td{
   border-bottom: none;
}
.synCatg .bgHed tr th:first-child {
    width: 60%;
    box-sizing: border-box;
}
#mi-modal .modal-footer {
    border: none;
    text-align: center;
    justify-content: center;
    padding-top: 0px;
}
#mi-modal .modal-body {
    text-align: center;
    justify-content: center;
    padding-top: 30px;
}
#mi-modal .modal-md {
    margin-top: 10%;
}
#modal-btn-si {
    background: #eee;
    font-size: 14px;
    font-weight: 500;
    padding: 5px 10px;
}
#modal-btn-no {
    font-size: 14px;
    font-weight: 500;
    padding: 5px 10px;
}
.synCatg .categorytbody tr .inputMsg {
    width: auto;
    text-align: left;
    min-width: 140px;
}
#modal-btn-si:focus , #modal-btn-no:focus{
   box-shadow: none;
}
.syncparentnotif {
  font-size: 12px;
  margin-top: 5px;
  color: #090909;
  font-style: italic;
  font-weight: 350;
  font-family: 'Barlow', sans-serif;


  
}
.urlRight {
   width: 50%;
   float: right;
   text-align: right;
}

.urlRight ul {
   margin-top: 13px;
   margin-bottom: 0px;
}

input:checked + .url-tog:before {
    transform: translateX(19px);
  }

input:checked + .url-tog {
   background: linear-gradient(to right, #9a69e0 0%,#317fec 100%) !important
}

.url-tog {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color:#b7b7b7;
    transition: .4s;
  }
  .url-tog:before {
      position: absolute;
      content: "";
      height: 16px;
      width: 16px;
      left: 4px;
      bottom: 3px;
      background-color: white;
      transition: .4s;
  }

  .url-tog.round {
    border-radius: 34px;
  }
  
  .url-tog.round:before {
    border-radius: 50%;
  }
  .second-heading.url {
   font-size: 14px;
   font-weight: 400;
  }

.taxonomies_synced {
    float: left;
    width: 100%;
    margin-top: 20px;
}
.taxCat-Crd, .catCrd-Tax {
    width: 25%;
    float: left;
    padding-right: 12px;
}
.taxCat-Crd .taxonomyclass {
    width: 100%;
    float: left;
    background: #f9f9f9;
    padding: 10px 15px;
    border: 1px solid #eee;
    border-radius: 5px;
    font-size: 15px;
    font-family: 'Barlow';
    font-weight: 500;
}
.taxCat-Crd .taxonomyclass b {
    float: right;
    font-weight: 300;
    color: #005bf0;
}

.catCrd-Tax .taxonomyclass_reorder {
    width: 100%;
    float: left;
    background: #fff;
    padding: 10px 12px;
    border: 1px solid #eee;
    border-radius: 5px;
    font-size: 15px;
    font-family: 'Barlow';
    font-weight: 500;
}
.catCrd-Tax .taxonomyclass_reorder b {
    float: right;
    font-weight: 300;
    color: #005bf0;
}
.taxCat-Crd:hover, .catCrd-Tax:hover {
    cursor: pointer;
}
.card_group {
  width: 100%;
  float: left;
  margin-bottom: 10px;
  display: flex;
}
.category_list .mjs-nestedSortable-branch li {
  width: 100%;
  float: left;
}

</style>


<div class="content-section categorysyncmanage" <?php if($action!=='sync'){ ?>style="display:none" <?php } ?>>
   <input type="hidden" id="pluginurlpath" value="<?php echo wp_kses_data(plugin_dir_url( __DIR__ ));?>">
   <div class="container-pj ">
      <div class="menuContent-area">

         <div id="General" class="tabcontent" style="display:block">
            <div class="inerContent-body">
               <!-- main heading tabing start -->
               <div class="heading-main">
                  <div class="logoLft">
                     <img src="<?php echo wp_kses_data(SORTD_CSS_URL);?>/logo.png">
                     <h5> Select Categories </h5>
                  </div>

                  <div class="urlRight">
                    
                  </div>
               </div>

         <div class="taxonomies_synced">
            <div class="row">
              
               <?php 

                  foreach($taxonomy_data as $k => $v){ 
      
                        if($v['taxonomy_type']['taxonomy_slug'] === 'category'){

                            $class =  "newClassHighlight";
                        } else {
                            $class = '';
                        }

                    ?>

                      <div class="taxCat-Crd">
                           
                           <span id="taxid_<?php echo wp_kses_data($v['taxonomy_type']['taxonomy_slug'])?>" data-name = "<?php echo wp_kses_data($v['taxonomy_type']['taxonomy_name']) ?>"class="taxonomyclass <?php echo wp_kses_data($class);?> " data-taxonomySlug="<?php  echo wp_kses_data($v['taxonomy_type']['taxonomy_slug']);?> "><?php echo wp_kses_data($v['taxonomy_type']['taxonomy_name']).'<b id='.wp_kses_data($v['taxonomy_type']['taxonomy_slug']).'_count value='.wp_kses_data($v['taxonomy_type']['count']).'>'.wp_kses_data($v['taxonomy_type']['count']).'</b>';?></span>
                        </div>


               <?php   }




               ?>
            </div>

         </div>
               <!-- main heading tabing end -->
               <div class="second-heading">
                  <h5>Select category based on above taxonomy type you want to display on mobile</h5>
                  <div class="syncparentnotif">To Sync child category, Please Sync its parent Category first.</div>
               </div>
               <!-- content-section-inner start -->
               <div class="inner-sectn-body mt-25">
               
                  <div class="content-card">
                     <div class="form-box">
                        <input type ="hidden" id="siteurl" value="<?php echo wp_kses_data(site_url());?>">
                        <table class="table synCatg">
                           <thead class="bgHed">
                              <tr>
                                 <th class="headth" id="categoryHeading" scope="col">Wordpress Categories</th>
                                 <!-- <th class="headth" scope="col">Sync</th> -->
                                 <!-- <th class="headth" scope="col">Sortd Categories (PWA/AMP)</th> -->
                                 <th class="thtick" scope="col" style="display:none"></th>
                              </tr>
                           </thead>
                           <tbody class="categorytbody">
                              <?php 
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
                                 echo wp_kses($html_data, $arr);
                              ?>

                              <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true" id="mi-modal">
                              <div class="modal-dialog modal-md">
                                 <div class="modal-content">
                                   
                                    <div class="modal-body">
                                      <h5>Do You want unsync all child categories?</h5>
                                    </div>
                                    <div class="modal-footer">
                                    <button type="button" class="btn btn-default" id="modal-btn-si">Yes</button>
                                    <button type="button" class="btn btn-primary" id="modal-btn-no">No</button>
                                    </div>
                                 </div>
                              </div>
                              </div>

                           </tbody>
                        </table>
                     </div>
                  </div>

                  <div style="width:100%;float: left; margin-top: 15px">
	                  <a class="btn btn-ad-dflt" href="<?php echo esc_url(wp_nonce_url(wp_kses_data(admin_url()).'admin.php?page=sortd-settings&section=sortd_plans',SORTD_NONCE));?>">Sync Older Posts</a>
	               </div>

               </div>
            </div>
         </div>
      </div>
   </div>
   <?php  } ?>
</div>
