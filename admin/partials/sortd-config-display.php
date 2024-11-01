<?php
/**
 * Provide a config display view for the plugin
 *
 * This file is used to markup the config aspects of the plugin.
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
    update_option('sortd_config_save_status',0);

     $info_url = "https://supportadlabs.rwadx.com/support/mediology/ShowHomePage.do#Solutions";
    if(isset($_GET['parameter']) && $_GET['parameter'] === 'general_settings'){
		$nonce = isset($_REQUEST['_wpnonce']) ? sanitize_text_field($_REQUEST['_wpnonce']) :"";
		if( $nonce && wp_verify_nonce( $nonce, SORTD_NONCE ) ) 
    	 	$info_url = "https://support.sortd.mobi/portal/en/kb/gni-adlabs/general";
    } else if(isset($_GET['parameter']) && $_GET['parameter'] === 'header'){
		$nonce = isset($_REQUEST['_wpnonce']) ? sanitize_text_field($_REQUEST['_wpnonce']) :"";
		if( $nonce && wp_verify_nonce( $nonce, SORTD_NONCE ) )
    	 	$info_url = "https://support.sortd.mobi/portal/en/kb/gni-adlabs/general";
    } else if(isset($_GET['parameter']) && $_GET['parameter'] === 'footer'){
		$nonce = isset($_REQUEST['_wpnonce']) ? sanitize_text_field($_REQUEST['_wpnonce']) :"";
		if( $nonce && wp_verify_nonce( $nonce, SORTD_NONCE ) )
    	 	$info_url = "https://support.sortd.mobi/portal/en/kb/gni-adlabs/general";
    } else if(isset($_GET['parameter']) && $_GET['parameter'] === 'top_menu'){
    	$nonce = isset($_REQUEST['_wpnonce']) ? sanitize_text_field($_REQUEST['_wpnonce']) :"";
		if( $nonce && wp_verify_nonce( $nonce, SORTD_NONCE ) )
			$info_url = "https://supportadlabs.rwadx.com/support/mediology/ShowHomePage.do#Solutions";
    } else if(isset($_GET['parameter']) && $_GET['parameter'] === 'manifest'){
		$nonce = isset($_REQUEST['_wpnonce']) ? sanitize_text_field($_REQUEST['_wpnonce']) :"";
		if( $nonce && wp_verify_nonce( $nonce, SORTD_NONCE ) )
    	 	$info_url = "https://supportadlabs.rwadx.com/support/mediology/ShowHomePage.do#Solutions";
    } else if(isset($_GET['parameter']) && $_GET['parameter'] === 'home'){
		$nonce = isset($_REQUEST['_wpnonce']) ? sanitize_text_field($_REQUEST['_wpnonce']) :"";
		if( $nonce && wp_verify_nonce( $nonce, SORTD_NONCE ) )
    	 	$info_url = "https://support.sortd.mobi/portal/en/kb/gni-adlabs/general";
    } else if(isset($_GET['parameter']) && $_GET['parameter'] === 'category'){
		$nonce = isset($_REQUEST['_wpnonce']) ? sanitize_text_field($_REQUEST['_wpnonce']) :"";
		if( $nonce && wp_verify_nonce( $nonce, SORTD_NONCE ) )
    	 	$info_url = "https://supportadlabs.rwadx.com/support/mediology/ShowHomePage.do#Solutions";
    } else if(isset($_GET['parameter']) && $_GET['parameter'] === 'article'){
		$nonce = isset($_REQUEST['_wpnonce']) ? sanitize_text_field($_REQUEST['_wpnonce']) :"";
		if( $nonce && wp_verify_nonce( $nonce, SORTD_NONCE ) )
    	 	$info_url = "https://supportadlabs.rwadx.com/support/mediology/ShowHomePage.do#Solutions";
    } else if(isset($_GET['parameter']) && $_GET['parameter'] === 'widgets'){
		$nonce = isset($_REQUEST['_wpnonce']) ? sanitize_text_field($_REQUEST['_wpnonce']) :"";
		if( $nonce && wp_verify_nonce( $nonce, SORTD_NONCE ) )
    	 	$info_url = "https://supportadlabs.rwadx.com/support/mediology/ShowHomePage.do#Solutions";
    } 

 
?>

<style type="text/css">
.saveBtn{
  	background: transparent;
    border: 1px solid #fff !important;
}
.saveBtn:hover{
	background: #0b4ec1 !important
}

.btn-cancl {
    color: rgb(255 255 255) !important;
    background: #333 !important;
    font-size: 16px !important;
    border-radius: 4px !important;
    border: 1px solid #f1f1f1 !important;
    margin-right: 6px;
    font-family: 'Barlow', sans-serif;
    font-size: 16px !important;
}
.headingNameTop .nextStep .goLnk {
    background: #005BF0;
    padding: 16px 60px;
    border-radius: 4px;
    width: 100%;
    font-size: 1em;
    line-height: 1em;
    height: auto;
    border-bottom: 4px solid rgba(0, 0, 0,0.2);
}
.btn.btn-info {
    background: #0660f0 !important;
    color: #fff !important;
    border: none !important;
    padding: 3px 10px 4px !important;
    font-size: 12px !important;
}
.spanincdec {
    background: #ebebeb;
    width: 100%;
    position: relative;
    float: left;
    margin-bottom: 20px;
    color: #747373;
    padding: 5px 10px;
    border-radius: 5px;
    font-size: 14px;
    font-weight: 500;
    border: 1px solid #cbcbcb;
}

.btn-info.btn-ad.removeBtn {
    background: #dc3545 !important;
    border-color: #dc3545 !important;
}
.sepRater {
    width: 100%;
    float: left;
    background: #ebebeb;
    height: 10px;
    margin-bottom: 20px;
    border-radius: 10px;
}
.rcAdbdr {
    border-bottom: 1px solid #e0e0e0;
    padding-bottom: 30px;
}
.form-box .smBtnArea {
    display: inline-block;
}
</style>

<div class="notice notice-error is-dismissible curlErrorDivConfig" style="display:none"><p id="curlErrorp"></p><span class="closeicon" aria-hidden="true">&times;</span></div>
	<!-- header start -->
	<div class="header pdT">
		<div class="head-top">
			<div class="container-pj">
				<div class="heading-main">
		  			<div class="logoLft ">
		               <img src="<?php echo wp_kses_data(SORTD_CSS_URL);?>/logo.png">
		               <h5>Manage all aspects of your mobile</h5>
		            </div>
		            <div class="headingNameTop">
					<button type="button"  class="btn infoIcn infourl icPd0-l"><i class="bi bi-info-circle"></i></button>
		            	<button class="butn-df" onclick="window.open('https://www.sortd.mobi/contact/','_blank')">Contact Support <i class="bi bi-headset"></i></button>
						
		            </div>
		  		</div>
				
			</div>
		</div>

		<div id="view_changes_inqr"  style="display:none;">
			
			<h3> Congratulations! Site Published. </h3>

			<div class="scan_QRcode_steps_container">

				<h5> How to Scan a QR Code ? </h5>

				<ul class="scan_QRcode_steps_container_ul">
					<li> Open the Camera / QR Code reader on your smartphone. </li>
					<li> Hold your device over a QR Code so that it’s clearly visible within your smartphone’s screen. </li>
					<li> Presto! Your smartphone reads the code and navigates to the intended destination </li>
				</ul>

			</div>

			<div class="scan_QRcode_container">

				<h6> Scan QR code on your smartphone to view changes. </h6>

				<img src="<?php echo wp_kses_data($demo_host); ?>/sortd-service/qrcode/v22-01/medium?url=<?php echo wp_kses_data(base64_encode($demo_host)); ?>"></img>
				
				<input type="hidden" id="nonce_input" value="<?php echo esc_attr(wp_create_nonce(SORTD_NONCE)); ?>">

				<div class="view_changes_inqr_close">
					<button type="button" class="close scanpopup-close view_changes_inqr_closebtn" aria-label="Close">
		  				<span aria-hidden="true"> DONE </span>
					</button>
				</div>

			</div>
			

			
		</div>

		<!-- menu tabing start -->
		<div class="menuBx stickyhead config-main-div fixedhead">
			<div class="container-pj">
				<div class="tabMenu">

					<?php 

					if($config_schema_object->status === true){

						foreach ($config_schema_object->data as $horizontol_menu_key => $horizontol_menu_value) { ?>
								
								
							<button class="tablinks" id="<?php echo wp_kses_data($horizontol_menu_key);?>"><?php echo wp_kses_data($horizontol_menu_value->label); ?></button>

						<?php 	


						}  

					} ?>	

					
				 
				</div>
			</div>
		</div>
		<!-- menu tabing end -->
	</div>
	<!-- header end -->
	
	<div class="content-section config-main-div">
		<div class="container-pj">
			<div class="menuContent-area">
				<input type="hidden" id="site_url" value="<?php echo wp_kses_data(site_url());?>">
				<input type="hidden" id="blogname" value="<?php echo wp_kses_data($project_title);?>">
				<input type="hidden" id="blogdescription" value="<?php echo wp_kses_data($project_description);?>">
				<input type="hidden" class="getschema" value='<?php echo wp_kses_data($config_schema);?>'>
				<input type="hidden" class="getcategoriesAll" value='<?php echo (wp_json_encode($categories, JSON_HEX_APOS));?>'>
				<input type="hidden" class="getSavedConfig" value='<?php echo (wp_json_encode($saved_config_object , JSON_HEX_APOS));?>'>
				
                <div id="configgroup_tabdata">
                <?php 

				if($config_schema_object->status === true){
					
					foreach ($config_schema_object->data  as $config_schema_group_key => $config_schema_group_value) {
                                            
                            if($config_schema_group_key !== $current_config_group){

                                continue;
                            }
                            $view_data = array();
                            $view_data['categories'] = $categories;
                            $view_data['config_schema_group_value'] = $config_schema_group_value;
                            $view_data['config_schema_group_key'] = $config_schema_group_key;
                            $view_data['current_group_saved_config'] = $current_group_saved_config;
                            
                            Sortd_Helper::render_partials(array('display-form'), $view_data, 'config');
                          
                                            
                    }	
				
			} ?>
                </div>
			</div>	
			</div>
		</div>
	
<script type="text/javascript">
	

	function getEvent(event){

	event.value==="true" ? event.value="false" : event.value="true" ;
		//event.value="hi"


	}


</script>



<script>


function isValidColor(str) {
    return str.match(/^#[a-f0-9]{6}$/i) !== null;
}



function isUrl(s) {
    var regexp = /(ftp|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
    return regexp.test(s);
}



function handleOnchangeEvent(data,dependent_field_id){

	var id = data.id;


	var nameattribute = $("#"+id).attr('name');

	if (nameattribute.indexOf('/') > -1)
	{
	 var namestr =  nameattribute.split('/');

		if(namestr[0] === 'components:special_widgets:name' ){
			if($("#"+id).val() !== 'not_applicable'){

				console.log("namestr")
				$("#components-special_widgets-"+namestr[1]+"-category_id option[value='not_applicable']").hide();
				$("#components-special_widgets-"+namestr[1]+"-category_id option[value='not_applicable']").attr('selected',false);
			} else {
					$("#components-special_widgets-"+namestr[1]+"-category_id option[value='not_applicable']").show();
			}
		}
	}
    
    if(dependent_field_id !== ''){
        $('#'+dependent_field_id).val(data.options[data.selectedIndex].text);
    }

	

	
}

</script>
