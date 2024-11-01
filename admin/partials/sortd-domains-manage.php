<?php

/**
 * Provide a manage domains view for the plugin
 *
 * This file is used to markup the manage domains aspects of the plugin.
 *
 * @link       https://www.sortd.mobi
 * @since      2.0.0
 *
 * @package    Sortd
 * @subpackage Sortd/admin/partials
 */




?>


<style type="text/css">
  .onClik_lft{
  padding:30px;
    border-radius: 10px;
    background: #f9f9f9;
    border:1px solid #eee;
    margin-top: 30px
}
.onclick_setup_demodv{
   /*background: linear-gradient(to left, #ff00cc, #005BF0);*/
    width: 100%;
    float: left;
}
.onclick_setup_dv{
  width: 70%;
  float: left;
}
.onclick_setup_dv h1 {
    font-size: 22px;
    font-family: 'Barlow';
    font-weight: 500;
}
.onclick_setup_dv .syHead{
   color:#5f5d5d;
   font-size: 17px
   /*font-weight: 300;*/
}
.domnSetp.mb20.domSt {
    background: #fff;
    width: 85%;
    padding: 10px;
}
.domnSetp.domSt1 {
    background: #fff;
    width: 50%;
    padding: 10px;
}
.txtFlw {
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    position: relative;
    width: 46%
}
.copyTxt {
    background: -moz-linear-gradient(left, rgba(255,255,255,0.65) 26%, rgba(255,255,255,1) 98%, rgba(255,255,255,1) 100%); /* FF3.6-15 */
background: -webkit-linear-gradient(left, rgba(255,255,255,0.65) 26%,rgba(255,255,255,1) 98%,rgba(255,255,255,1) 100%); /* Chrome10-25,Safari5.1-6 */
background: linear-gradient(to right, rgba(255,255,255,0.65) 26%,rgba(255,255,255,1) 98%,rgba(255,255,255,1) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
    position: absolute;
    top: 0;
    right: 0px;
    height: 50px;
    width: 30px;
    text-align: center;
    padding-top: 9px;
}
.copied {
    position: absolute;
    left: 0px;
    top: 0px;
    background: #333;
    color: #fff !important;
    text-transform: capitalize;
    padding: 5px 10px;
    border-radius: 3px;
    font-size: 13px !important;
}
.copied1 {
    position: absolute;
    left: 0px;
    top: 0px;
    background: #333;
    color: #fff !important;
    text-transform: capitalize;
    padding: 5px 10px;
    border-radius: 3px;
    font-size: 13px !important;
}
.txtFlw p {
    margin-bottom: 0px;
}



</style>
<div class="content-section">
  <div class="container-pj">
   <div class="message"></div>
    <div class="row">
        <div class="col-md-12">
           <div class="heading-main">
              <div class="logoLft">
                 <img src="<?php echo wp_kses_data(SORTD_CSS_URL);?>/logo.png">
                 <h5>Manage Domain Deployment </h5>
              </div>
              <div class="headingNameTop">
              <button type="button" onclick="window.open('https://support.sortd.mobi/portal/en/kb/gni-adlabs/general','_blank')" class="btn infoIcn icPd0" title="AdLabs Support"><i class="bi bi-info-circle"></i></button>
              </div>
           </div>
        </div>
    </div>
    <input type = "hidden" id="hiddendomain" value="<?php echo wp_kses_data(wp_nonce_url(admin_url()."admin.php?page=sortd-manage-settings&section=sortd_manage_domains", SORTD_NONCE));?>">
                    
                    
        <div class="row">
          <div class="col-md-12 ">
            <div  id="sortd_configContainer">
       
                 <?php wp_nonce_field('rw-sortd-manage-domains', 'sortd-domains-nonce'); ?>
                
        
                 <?php if(!isset($project_domains->data->public_host)) : ?>

                <div class="sortCard onClik_lft heigtDv">

                  <div class="onclick_setup_dv">
                    <div class="form-group">
                     
                      <span class="hostRequired" style="display: none;color:red">This field is required</span>
                      <span class="validdomain" style="display: none;color:red">Enter valid domain</span>
                    </div>
                
                  </div>

                  
                  <div class="onclick_setup_dv">
                    <div class="form-group">
                   
                     <label class="pure-material-textfield-outlined" for="sortdAccessKeyName" style="position: relative;">
                       <span id="demo_httpstext" class="demo_httpstext"> https:// </span>
                        <input type="text" style="padding-left: 60px;" class="form-control" id="public_host" name = "public_host" value="" required>
                        <span>Public Host URL</span>
                        <p class="inputMsg">Enter the public host domain without protocol i.e <span style="color:#000;"> m.wordpress.com, amp.wordpress.com </span> etc</p>
                      </label>
                      <span class="hostRequired" style="display: none;color:red">This field is required</span>
                      <span class="validdomain" style="display: none;color:red">Enter valid domain</span>
                    </div>
                    <div class="fulW">
                      <button type="button" class="btn btn-ad-blu create_domain" data-siteurl="<?php echo wp_kses_data(site_url());?>">Create Domain <i class="bi bi-arrow-right-circle-fill"></i></button>
                    </div>
                   
                  </div>
                  
                  <!-- mob Device -->

                  <div class="mobOnclick">
                    <div class="mobWt rds">
                      <div class="logMobBar">
                         <p><i class="bi bi-lock-fill"></i><span class="domaintype">m.sortd.com</span></p>
                      </div>
                      <span class="strip-line mt40"></span>
                      <span class="strip-lineHalf"></span>
                      <span class="strip-img"></span>
                      <span class="strip-text"></span>
                      <span class="strip-text"></span>
                      <span class="strip-textHalf"></span>
                      <span class="space"></span>
                      <span class="strip-text"></span>
                      <span class="strip-text"></span>
                       <span class="strip-text"></span>
                      <span class="strip-textHalf"></span>

                    </div>
                  </div>
                  <!-- mob Device end -->
                </div>

                 <?php elseif(isset($project_domains->data->public_host) && $project_domains->data->status==='0') : ?>


                <div class="col-md-12 ">
                  <div class="sortCard ">
                    <ul class="step_3 stepMenu">
                      <li class="disbl">Setup Public Host</li>
                      <li>Generate & Verify SSL</li>
                      <li>Deploy CDN & Go Live !</li>
                    </ul>
                  </div>
                </div>


                <div class="sortCard onClik_lft heigtDv">
                  <div class="onclick_setup_dv">
                    <h5 class="sortHed form_dedicated_heading">Domain Setup</h5>
                    <div class="domnSetp mb20 domSt">

                        
                        <span class="thead"> Domain URL :</span>
                          <span>
                              <span class="spanhost"><?php echo wp_kses_data($project_domains->data->public_host); ?></span><input type="text" class="editpublichostinput" value="<?php echo esc_attr($project_domains->data->public_host); ?>" style="display: none;" />
                              <span class="btn  editdomaintick" id="" title="Save" style="display: none;"><i class="bi bi-check"></i></span>
                              <span class=" editpublic" title="Edit public host" id=""><i class="bi bi-pencil"></i>
                                  </span>
                              <span class="btn  crosspublichosticon" id="" title="Cancel" style="display: none;"><i class="bi bi-x"></i></span>
                              <span class="succmsgdomain" id="" style="display:none;font-size:12px;color:green">Successfully saved</span>
                               <span class="failmsgdomain" id="" style="display:none;font-size:12px;color:red">Failed to save</span>
                               <span class="validdomain" style="display: none;color:red">Enter valid domain</span>
                          </span>

                     
                    </div>

                    <div class="fulW">
                        <div class="keypairtable"></div>
                       <button type="button" class="btn btn-ad-blu generate_ssl" data-siteurl="<?php echo wp_kses_data(site_url());?>">Generate SSL Certificate <i class="bi bi-arrow-right-circle-fill"></i></button>
                       <button type="button" class="btn btn-ad-blu refresh_caa" style="display:none;" data-siteurl="<?php echo wp_kses_data(site_url());?>">Refresh <i class="bi bi-arrow-right-circle-fill"></i></button>
                    </div>
                  </div>

                  <!-- mob Device -->
                  <div class="mobOnclick">
                    <div class="mobWt rds">
                      <div class="logMobBar">
                        <p><i class="bi bi-lock-fill"></i><span class="domaintypespan"><?php echo wp_kses_data($project_domains->data->public_host); ?></span></p>
                      </div>
                      <span class="strip-line mt40"></span>
                      <span class="strip-lineHalf"></span>
                      <span class="strip-img"></span>
                      <span class="strip-text"></span>
                      <span class="strip-text"></span>
                      <span class="strip-textHalf"></span>
                      <span class="space"></span>
                      <span class="strip-text"></span>
                      <span class="strip-text"></span>
                       <span class="strip-text"></span>
                      <span class="strip-textHalf"></span>
                    </div>
                  </div>
                  <!-- mob Device end -->
                </div>


                 <?php elseif(isset($project_domains->data->public_host) && $project_domains->data->status==='1') : ?>

                  <div class="sortCard content-card mt30">
                    <h5 class="sortHed form_dedicated_heading">SSL Setup</h5>
                 
                    <div class="domnSetp">
                       
                      
                      <?php if(isset($project_domains->data->acm_validation_data) && !empty($project_domains->data->acm_validation_data[0]->ResourceRecord->Name) && !empty($project_domains->data->acm_validation_data[0]->ResourceRecord->Value)){ ?> 

                      <table class="table cNmtable">
                         <thead class="bgHed-2">
                            <tr>
                               <th class="headth" scope="col">Name</th>
                               <th class="headth" scope="col">Type</th>
                               <th class="headth" scope="col">Value</th>
                            </tr>
                         </thead>
                         <tbody>  
                            <tr>
                               <td class="txtFlw"><p class="copyname"><?php echo wp_kses_data($project_domains->data->acm_validation_data[0]->ResourceRecord->Name); ?></p> <span class="copied1" style="display:none">copied</span><span class="copyTxt coname tooltiptext" title="Copy" ><i class="bi bi-back"></i></span></td>
                               <td>CNAME</td>
                               <td class="txtFlw"><p class="copyvalue"><?php echo wp_kses_data($project_domains->data->acm_validation_data[0]->ResourceRecord->Value); ?></p><span class="copied" style="display:none">copied</span> <span class="copyTxt covalue tooltiptext" title="Copy"><i class="bi bi-back"></i></span></td>
                            </tr>
                         </tbody>
                      </table>

                      <p class="dnsInf1">Please click on below button after adding above records in your DNS Panel. Please refer to help documentation if you use <a class="clranc" target="_blank" href="https://support.sortd.mobi/portal/en/kb/gni-adlabs/general">Cloudflare</a> or  <a class="clranc" target="_blank" href="https://support.sortd.mobi/portal/en/kb/gni-adlabs/general">Godaddy.</a></p>
                  
                    </div>
                    <div class="fulW notMob">
                       <button type="button" class="btn btn-ad-blu validate_ssl" data-siteurl="<?php echo esc_url(site_url()); ?>" data-nonce="<?php echo esc_attr(wp_create_nonce('sortd_nonce')); ?>">Validate SSL Certificate <i class="bi bi-arrow-right-circle-fill"></i></button>

                      </div>
                  </div>

                  <?php } else { ?> 
                         <!-- <div class="sortCard content-card mt30"> -->
                  
                 
                    <div class="domnSetp">
                        <div class="trow">
                          <span class="thead">Domain URL :</span> <span><?php echo wp_kses_data($project_domains->data->public_host); ?></span>
                        </div>
                    </div> 
                    <img class="imgload" src="<?php echo wp_kses_data(SORTD_CSS_URL);?>/load.gif" width="30px" style="margin-right: 20px;display: none;">
                    <div class="fulw notMob db1">
                       <button type="button" class="btn btn-ad-blu refresh" >Refresh after sometime<i class="bi bi-arrow-clockwise"></i></button>
                     </div>


                  <?php } ?>
                

                 <?php elseif(isset($project_domains->data->public_host) && $project_domains->data->status==='2') : ?>
                  
                  <div class="col-md-12 ">
                    <div class="sortCard ">
                      <ul class="step_3 stepMenu">
                        <li class="disbl">Setup Public Host</li>
                        <li class="disbl">Generate & Verify SSL</li>
                        <li>Deploy CDN & Go Live !</li>
                      </ul>
                    </div>
                  </div>

                  <div class="sortCard onClik_lft heigtDv">
                    <div class="onclick_setup_dv">
                      <h5 class="sortHed form_dedicated_heading">SSL Certification Completed !</h5>
                      <div class="domnSetp mb20 domSt">
                        <span class="thead">Domain URL : </span><span><?php echo wp_kses_data($project_domains->data->public_host); ?></span>
                      </div>
                      <div class="fulW ">
                         <button type="button" class="btn btn-ad-blu deploy_cdn" data-siteurl="<?php echo wp_kses_data(site_url());?>">Deploy on CDN <i class="bi bi-arrow-right-circle-fill"></i></button>
                      </div>
                    </div>
                    <!-- mob Device -->

                    <div class="mobOnclick">
                      <div class="mobWt rds">
                        <div class="logMobBar">
                          <p><i class="bi bi-lock-fill"></i><?php echo wp_kses_data($project_domains->data->public_host);?></p>
                        </div>
                        <span class="strip-line mt40"></span>
                        <span class="strip-lineHalf"></span>
                        <span class="strip-img"></span>
                        <span class="strip-text"></span>
                        <span class="strip-text"></span>
                        <span class="strip-textHalf"></span>
                        <span class="space"></span>
                        <span class="strip-text"></span>
                        <span class="strip-text"></span>
                         <span class="strip-text"></span>
                        <span class="strip-textHalf"></span>
                      </div>

                    </div>
                    <!-- mob Device end -->

                  </div>

                 <?php elseif(isset($project_domains->data->public_host) && $project_domains->data->status==='3') : ?>

                  <div class="col-md-12 ">
                    <div class="sortCard ">
                      <ul class="step_3 stepMenu">
                        <li class="disbl">Setup Public Host</li>
                        <li class="disbl">Generate & Verify SSL</li>
                        <li>Deploy CDN & Go Live !</li>
                      </ul>
                    </div>
                  </div>

                  <div class="sortCard content-card mt30">
                    <h5 class="sortHed form_dedicated_heading">Deployment </h5>
                      <div class="domnSetp domSt1">
                          <span class="thead">Domain URL : </span><span><?php echo wp_kses_data($project_domains->data->public_host); ?></span>
                      </div>
                        
                      <div class="congratBox">
                        <h1>Congratulations! Deployment Complete</h1>
                        <h2></h2>
                        <span><img src="<?php echo wp_kses_data(SORTD_CSS_URL);?>/ribon.png"></span>
                      </div>

                      <div class="domnSetp">

                        <div class="nxTp"><span>Step 1 </span></div>
                        <div class="cNam">
                         <h5>Create CNAME record in your DNS panel for your public domain</h5>

                          <table class="table cNmtable">
                             <thead class="bgHed-2">
                                <tr>
                                   <th class="headth" scope="col">Name</th>
                                   <th class="headth" scope="col">Type</th>
                                   <th class="headth" scope="col">Value</th>
                                </tr>
                             </thead>
                             <tbody>
                                <tr>
                                   <td class="txtFlw"><p class="copyname"><?php echo wp_kses_data($project_domains->data->public_host); ?></p> <span class="copied1" style="display:none">copied</span><span class="copyTxt coname tooltiptext" title="Copy" ><i class="bi bi-back"></i></span></td>
                                   <td>CNAME</td>
                                   <td class="txtFlw"><p class="copyvalue"><?php echo wp_kses_data($project_domains->data->public_host_cname); ?></p><span class="copied" style="display:none">copied</span> <span class="copyTxt covalue tooltiptext" title="Copy"><i class="bi bi-back"></i></span></td>
                                </tr>
                             </tbody>
                          </table>



                          <div class="nxTp mb20"><span>Step 2</span></div>
                          <div class="cNam">
                            <h5>Please click to verify the CNAME</h5>
                          </div>
                          <div class="fulW notMob db1">
                           <button type="button" class="btn btn-ad-blu verify-cname" data-siteurl="<?php echo wp_kses_data(site_url());?>">Verify CNAME <i class="bi bi-arrow-right-circle-fill"></i></button>
                          </div>
                        </div>
                      </div>
                  </div>

                  <?php elseif(isset($project_domains->data->public_host) && $project_domains->data->status==='4') : ?>
                  <div class="col-md-12 ">
                    <div class="sortCard ">
                      <ul class="step_3 stepMenu">
                        <li class="disbl">Setup Public Host</li>
                        <li class="disbl">Generate & Verify SSL</li>
                        <li class="disbl">Deploy CDN & Go Live !</li>
                      </ul>
                    </div>
                  </div>

                  <div class="sortCard content-card mt30">
                    <h5 class="sortHed form_dedicated_heading">CNAME Verification</h5>
                      <div class="domnSetp domSt1">
                          <span class="thead">Domain URL : </span>
                          <span><?php echo wp_kses_data($project_domains->data->public_host); ?></span>
                      </div>
                        
                      <div class="congratBox">
                        <h1>Congratulations! CNAME Verification Complete</h1>
                        <h2></h2>
                        <span><img src="<?php echo wp_kses_data(SORTD_CSS_URL);?>/ribon.png"></span>
                      </div>

                      <div class="domnSetp">
                        <div class="cNam">
                          
                          <table class="table cNmtable">
                             <thead class="bgHed-2">
                                <tr>
                                   <th class="headth" scope="col">Name</th>
                                   <th class="headth" scope="col">Type</th>
                                   <th class="headth" scope="col">Value</th>
                                </tr>
                             </thead>
                             <tbody>
                                <tr>
                                   <td class="txtFlw"><p class="copyname"><?php echo wp_kses_data($project_domains->data->public_host); ?></p> <span class="copied1" style="display:none">copied</span><span class="copyTxt coname tooltiptext" title="Copy" ><i class="bi bi-back"></i></span></td>
                                   <td>CNAME</td>
                                   <td class="txtFlw"><p class="copyvalue"><?php echo wp_kses_data($project_domains->data->public_host_cname); ?></p><span class="copied" style="display:none">copied</span> <span class="copyTxt covalue tooltiptext" title="Copy"><i class="bi bi-back"></i></span></td>
                                </tr>
                             </tbody>
                          </table>



                          <?php if($host_flag === 1) { ?>  
                              <h5>Public host is working... It may take upto 2-3 minutes...</h5>
                              <div class="fulW notMob db1">
                                 <a class="btn-ad-blu pdvisit" style="float:left;" href="<?php echo esc_url($project_domains->data->public_host); ?>" target="_blank"> Visit Now <i class="bi bi-box-arrow-up-right"></i></a>

               

                              </div>

                          <?php  }  else { ?>
                          <h5>Public host is working. It may take upto 5-10 minutes. Kindly clear the cache to see all the changes made.</h5>
                          <div class="fulW notMob db1">
                            <a class="btn-ad-blu pdvisit" style="float:left;" href="https://<?php echo esc_attr($project_domains->data->public_host); ?>" target="_blank"> Visit Now <i class="bi bi-box-arrow-up-right"></i></a>

     

                          </div>
                          <?php } ?>
                          
                        </div>
                      </div>
                  </div>
                 <?php endif; ?>
              

                 <div class="infoAlrt" style ="display:none"><img src="<?php echo wp_kses_data(SORTD_CSS_URL);?>/load.gif" width="30px" style="margin-right: 20px">Updating...</div>
         
       
            </div>
          </div>
        </div>

  </div>
</div>

<script><?php echo wp_kses_data($chatbot_dashboard_data->data); ?></script>

<script>
jQuery(document).ready(function(){
  jQuery("#public_host").keyup(function(){
        // Getting the current value of textarea
        var currentText = jQuery(this).val();
        
        // Setting the Div content
        jQuery(".domaintype").text(currentText);
    });
});

function copyToClipboard(element) {
  var $temp = jQuery("<input>");
  jQuery("body").append($temp);
  $temp.val(jQuery(element).text()).select();
  document.execCommand("copy");
  $temp.remove();
}

jQuery(".coname").click(function(){
    copyToClipboard('.copyname');

     setTimeout(function() {
                     jQuery(".copied1").show();
                }, 1000);

     setTimeout(function() {
                     jQuery(".copied1").hide();
                }, 3000);
});

jQuery(".covalue").click(function(){
    copyToClipboard('.copyvalue');

     setTimeout(function() {
                     jQuery(".copied").show();
                }, 1000);

     setTimeout(function() {
                     jQuery(".copied").hide();
                }, 3000);

   
});
</script>