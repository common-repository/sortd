<?php

/**
 * Provide a admin settings area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://www.sortd.mobi
 * @since      2.0.0
 *
 * @package    Sortd
 * @subpackage Sortd/admin/partials
 */

$category_list_slug = 'contentsettings/listcategories';
$response = json_decode(Sortd_Helper::sortd_get_api_response($category_list_slug, 'v2'),true);
$category_data = $response['data']['categories'];
$synced_cat = array();
foreach($category_data as $cat_d) {
	if(empty($cat_d['parent_id'])) {
		$synced_cat[$cat_d['cat_guid']] = $cat_d['name'];
	}
}

$project_id = Sortd_Helper::get_project_id();
$shorts_cat_id = get_option('sortd_shorts_catid_'.$project_id);

$wp_domain = get_site_url();
$sortd_current_user = wp_get_current_user()->display_name;
$project_details = Sortd_Helper::get_cached_project_details();
$project_slug = $project_details->data->slug;
$category_option_value = get_option('sortd_'.$project_id.'_category_url_redirection_flag');
$article_option_value = get_option('sortd_'.$project_id.'_article_url_redirection_flag');
$canonical_option_value = get_option('sortd_'.$project_id.'_canonical_url_redirection_flag');

if($category_option_value === '1') {
	$cat_checked = 'checked';
} else{
	$cat_checked = '';
}

if($article_option_value === '1') {
	$article_checked = 'checked';
} else{
	$article_checked = '';
}

if($canonical_option_value === '1') {
	$canonical_checked = 'checked';
} else{
	$canonical_checked = '';
}


if ( ! defined( 'ABSPATH' ) ) exit; 
?>

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

  span::before {
	margin-right: 4px;
  }

  .shortsSettings .subName {
    width: 100px !important;
	min-width:100px
}
.shortsSettings .pure-material-textfield-outlined .short-spn{
    position: absolute;
    top: 0;
    left: 0;
    display: flex;
    border-color: rgba(0, 0, 0, 0.3);
    width: 100%;
    max-height: 100%;
    color: rgba(0, 0, 0, 0.6);
    font-size: 85%;
    line-height: 15px;
    cursor: text;
    transition: color 0.2s, font-size 0.2s, line-height 0.2s;
    font-family: 'Barlow', sans-serif;
}
  

.shortsSettings .pure-material-textfield-outlined .short-spn:before{
    content: "";
    display: block;
    box-sizing: border-box;
    margin-top: 6px;
    border-top: solid 1px;
    border-top-color: rgba(0, 0, 0, 0.3);
    min-width: 10px;
    height: 8px;
    pointer-events: none;
    box-shadow: inset 0 1px transparent;
    transition: border-color 0.2s, box-shadow 0.2s;
    font-family: 'Barlow', sans-serif;
}
 
.shortsSettings .pure-material-textfield-outlined .short-spn:after {
    content: "";
    display: block;
    box-sizing: border-box;
    margin-top: 6px;
    border-top: solid 1px;
    border-top-color: rgba(0, 0, 0, 0.3);
    min-width: 10px;
    height: 8px;
    pointer-events: none;
    box-shadow: inset 0 1px transparent;
    transition: border-color 0.2s, box-shadow 0.2s;
    font-family: 'Barlow', sans-serif;
    width: 100%;
    border-radius: 0px 5px;
    margin-left: 3px;
}
.shortsSettings .pure-material-textfield-outlined .short-spn::before {
    margin-right: 4px;
    border-left: solid 1px transparent;
    border-radius: 4px 0;
}
.shortsSettings .pure-material-textfield-outlined > select + span::after {
    flex-grow: 1;
    margin-left: 4px;
    border-right: solid 1px transparent;
    border-radius: 0 4px;
}
.shortsSettings .pure-material-textfield-outlined select:focus + .short-spn:after{
    border-top-color: #2196f3 !important;
    box-shadow: inset 0 1px #2196f3;
}
.shortsSettings .inputMsg{
    width: 180px;
}
.manag-mob-togl .switch-tog {
    margin-top: 10px;
}

.default-dv {
    width: 100%;
    float: left;
    margin-top: 10px;
}
.default-dv .cncl{
    color: #ffffff !important;
    padding: 5px 20px !important;
    border-radius: 4px !important;
    font-size: 16px !important;
    margin-right: 5px;
    font-family: 'Barlow', sans-serif;
    height: auto;
    font-weight: 500 !important;
    background-color: #6c757d !important;
}

.manag-mob-togl ul {
    margin-bottom: 0px;
}
.manag-mob-togl .inlineLi .switch-tog {
    float: right;
}

.singl-section {
    border-bottom: unset;
    padding-bottom: unset;
}
</style>

<div class="content-section">
	<div class="container-pj">
	   <div class="row">
	      <div class="col-md-12">
	         <div class="heading-main">
	            <div class="logoLft">
	               <img src="<?php echo wp_kses_data(SORTD_CSS_URL); ?>/logo.png">
	              <h5>Settings</h5>
	            </div>
	            <div class="headingNameTop df_bTn">
				<button type="button" onclick="window.open('https://support.sortd.mobi/portal/en/kb/gni-adlabs/general','_blank')" class="btn infoIcn icPd0" title="AdLabs Support"><i class="bi bi-info-circle"></i></button>
	               <a href="<?php echo wp_kses_data($console_url);?>" target="_blank"><button class="butn-df">Go To Console  <i class="bi bi-box-arrow-up-right"></i></button></a>
	            </div>
	         </div>
	      </div>
	   </div>

	   <div class="row">
	   		<div class="col-md-12">
	   			<div class="designSeting">
	   				<span>
	   					<i class="bi bi-palette"></i>
	   				</span>
	   				<div class="ddBox">
		   				<h1>Design and Develop</h1>
		   				<p>Customise your entire mobile web user interface and functionalities. Engage your end users with best in class mobile experience.</p>
		   			</div>
		   			<div class="mngBtns">
		   				<a href="<?php echo wp_kses_data(wp_nonce_url(admin_url().'admin.php?page=sortd-manage-settings&section=sortd_config&parameter=general_settings',SORTD_NONCE));?>">
			   				<button class="butn-df btn-md">Manage Design  <i class="bi bi-arrow-right-circle-fill"></i>
			   					
			   				</button>
		   				</a>
		   				<a href="<?php echo wp_kses_data(wp_nonce_url(admin_url().'admin.php?page=sortd-manage-settings&section=sortd_manage_templates',SORTD_NONCE));?>">
		   				<button class="butn-df btn-Thm">Themes <i class="bi bi-arrow-right-circle-fill"></i></button></a>
		   			</div>
	   			</div>
	   		</div>

	   		<div class="col-md-6">
	   			<div class="designCatag_oder">
	   				<h3>Manage Categories</h3>
	   				<p>Manage sync / unsync of the categories in mobile.</p>
	   				<span>
	   					<a href="<?php echo wp_kses_data(wp_nonce_url(admin_url().'admin.php?page=sortd-manage-settings&section=sortd_manage_categories',SORTD_NONCE));?>"><i class="bi bi-arrow-right-circle-fill"></i></a>
	   				</span>
	   			</div>

	   			<div class="designCatag_oder">
	   				<h3>Credentials <span class="verifyAlt">Already Verified</span></h3>
	   				<p>Validate your unique token & key to keep your account secure.</p>
	   				<span>
	   					<a href="<?php echo wp_kses_data(wp_nonce_url(admin_url().'admin.php?page=sortd-manage-settings&section=sortd_credential_settings',SORTD_NONCE));?>"><i class="bi bi-arrow-right-circle-fill"></i></a>
	   				</span>
	   			</div>

	   			<div class="designCatag_oder">
					   <h3> Set Mobile Search Console  </h3>
					   <p> Create search visibilty for advanced mobile setup. </p>
					   <span>
					   <a target="_blank" href="https://search.google.com/search-console/about"><i class="bi bi-arrow-right-circle-fill"></i></a>
					   </span>
				   </div>

				<?php 	$project_id = Sortd_Helper::get_project_id(); 
						$value = get_option('sortd_author_sync_success_'.$project_id);
						$notoptions = wp_cache_get( 'notoptions', 'options' );
						if ( isset( $notoptions['sortd_author_sync_success_'.$project_id] ) ) {
							$value = 0;
						} else {
							$value = $value;
						}

				if($value === 0) { ?>
				<div class="designCatag_oder authorsSettings">
	   				<h3>Manage Authors</h3>
	   				<p>Sync old authors data.</p>
	   				<span>
					   <span class="dataSuccess"></span>
					    <span class="dataloader" style="display:none" >
                        <span class="">Please Wait...</span>
                        <img width="35px" src="<?php echo wp_kses_data(SORTD_CSS_URL);?>/load.gif" id="loader">
						</span> 
	   					<a href="javascript:void(0)" class="manageAuthors"><i class="bi bi-arrow-right-circle-fill"></i></a>
	   				</span>
	   			</div>
				<?php } ?>

				
				<div class="designCatag_oder">
	   				<h3>Manage Mobile s URLs</h3>
					<div class="row">
					<div class="col-md-12">
						
						<div class="manag-mob-togl">
							<ul>
								<li class="inlineLi" ><p class='second-heading url'>Enable category alias url</p></li>
								<li class="inlineLi" ><label class="switch-tog"><input id="urlToggleValue" type="checkbox" data-current_user="<?php echo esc_attr($sortd_current_user); ?>" data-wp_domain="<?php echo esc_attr($wp_domain); ?>" data-project_slug="<?php echo esc_attr($project_slug); ?>" data-update_flag="<?php  echo wp_kses_data($category_option_value) ?>" <?php echo wp_kses_data($cat_checked); ?> class="categoryUrlRedirection"><span class="url-tog round"></span></label></li>
							</ul>
						</div>
				
					</div>
					<div class="col-md-12">
							<div class="manag-mob-togl">
							<ul>
							<li class="inlineLi" ><p class='second-heading url'>Enable category in article url</p></li>
                        <li class="inlineLi"><label class="switch-tog"><input id="urlToggleValue" type="checkbox" data-current_user="<?php echo esc_attr($sortd_current_user); ?>" data-wp_domain="<?php echo esc_attr($wp_domain); ?>" data-project_slug="<?php echo esc_attr($project_slug); ?>" data-update_flag="<?php  echo wp_kses_data($article_option_value) ?>" <?php echo wp_kses_data($article_checked); ?> class="articleUrlRedirection"><span class="url-tog round"></span></label></li>	
						</ul>
						</div>
					</div>
					<div class="col-md-12">
					<div class="manag-mob-togl">
							<ul>
								<li class="inlineLi"><p class="second-heading url">Enable self canonical</p></li>
								<li class="inlineLi"><label class="switch-tog"><input id="urlToggleValue" type="checkbox" data-current_user="<?php echo esc_attr($sortd_current_user); ?>" data-wp_domain="<?php echo esc_attr($wp_domain); ?>" data-project_slug="<?php echo esc_attr($project_slug); ?>" data-update_flag="0" <?php echo wp_kses_data($canonical_checked); ?> class="categoryUrlCanonical"><span class="url-tog round"></span></label></li>
							</ul>
						</div>
					</div>

					</div>
	   				
	   				
	   			</div>
	   		</div>
	   		<?php 
                            if($redirection_enabled === 1){ 

                                    $status_btn ="Active";
                                    $class_name = "setactvBtn";

                            } else if($redirection_enabled === 0){

                                    $status_btn ="Inactive";
                                    $class_name = "setdeactvBtn";


                            }
                        ?>
	   		<div class="col-md-6">
	   			<div class="designCatag_oder">
	   				<h3>Mobile Subdomain Status</h3>
	   				<p style="width:44%;">Control your website redirection to mobile urls. </p>
	   				<a href="<?php echo wp_kses_data(wp_nonce_url(admin_url().'admin.php?page=sortd-manage-settings&section=sortd_redirection',SORTD_NONCE));?>" class="<?php echo wp_kses_data($class_name);?>"><?php echo wp_kses_data($status_btn);?></a>
	   				<hr class="bdr2">

	   				<h4>Mobile Url: </h4> <span class="mobDom"><?php echo wp_kses_data($host);?></span>
	   				<a href="<?php echo wp_kses_data(wp_nonce_url(admin_url().'admin.php?page=sortd-manage-settings&section=sortd_manage_domains',SORTD_NONCE));?>" class="mngDom" style="margin-top:23px;margin-bottom:12px;">Manage Mobile Domain <i class="bi bi-arrow-right-circle-fill"></i></a>
					   
				</div>
				<div class="designCatag_oder">
	   				<h3>Manage Advanced Settings</h3>
	   				<p>Click here to manage your extended feature settings.</p>
	   				<span>
	   					<a target="_blank" href="<?php echo wp_kses_data($console_url);?>/features/manager?project=<?php echo wp_kses_data($project_slug);?>"><i class="bi bi-arrow-right-circle-fill"></i></a>
	   				</span>
	   			</div>

				   <div class="designCatag_oder shortsSettings">
	   				<h3>Manage Shorts</h3>


					   <div class="singl-section">
							<div class="inputMsg">Select the shorts</div></h5>

						<label class="pure-material-textfield-outlined">
							<span style="color:red;display:none;" id="spanshorts">Select category</span>
							<select class="form-control" id="mySelectShortsCat">
								<optgroup label=" Select Font">

								<option value="">Select Shorts Category</option>
								
									<?php foreach($synced_cat as $k => $v){ 
                                        
										if($k === (int)$shorts_cat_id){
											$selected = 'selected';

										} else { 
											$selected = '';

										} ?>
										<option value="<?php echo wp_kses_data($k);?>" <?php echo wp_kses_data($selected);?>><?php echo wp_kses_data($v);?></option>	

									<?php } ?>
								</optgroup>

							</select>
							<img width="35px" id="successimgshorts" style="display:none;" src="<?php echo wp_kses_data(SORTD_CSS_URL); ?>/check.png">
							<span class="short-spn">Shorts</span>

							<div class="text-center">
								<button class="btn btn-primary mr-2" id="saveBtnShorts" data-current_user="<?php echo esc_attr($sortd_current_user); ?>" data-wp_domain="<?php echo esc_attr($wp_domain); ?>" data-project_slug="<?php echo esc_attr($project_slug); ?>" style="display:none">Save</button>
								<button class="btn btn-secondary" id="cancelBtnShorts" style="display:none">Cancel</button>
							</div>

						</label>


					</div>
	  
					
	   			</div>


	   				<div class="designCatag_oder Managetaxonomies">
	   				<h3>Manage Taxonomies</h3>
	   				<p>Sync Custom Taxonomies.</p>
	   				<span>
	   					<a href="<?php echo wp_kses_data(wp_nonce_url(admin_url().'admin.php?page=sortd-manage-settings&section=sortd_manage_taxonomies',SORTD_NONCE));?>" class="manageTaxonmomies"><i class="bi bi-arrow-right-circle-fill"></i></a>
	   				</span>
	   			</div>
	   		</div>
		
	   </div>
	</div>
</div>

