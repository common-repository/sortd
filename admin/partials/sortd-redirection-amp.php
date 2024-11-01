<?php

   /* 	Plugin Name: SORTD PWA & AMP Generator
   	Description: Update few things to create your pwa and amp
   	Version: 1.0 */
  
   //echo "<pre>";print_r($projectDetails);die;

   	//$projectDetails->data->domain->public_host = 'abc.com';
   $url = site_url();

   $break = explode("://",$url);
   
   ?>

   <style type="text/css">
      .tab {
      background-color: #ffffff; 
      margin: 20px auto; 
      padding: 40px; 
      width: 90%; 
      min-width: 300px;
      border-radius: 10px;
      min-height: 40px;
      }
      .submit {
      width: 12% !important; float: right;
      }
     .remove_less{ 
      background-color: DodgerBlue;
      border: none;
      color: white !important;
      padding: 8px 11px;
      font-size: 16px;
      cursor: pointer;
      text-decoration: none !important;
      border-radius: 10px;
      margin: -10px 0px 0px 0px;
      float: right;
      }
      .heading_style {
      color: rgba(38,50,56 ,1);
      font-size: 16px;
      padding: 7px 0px;
      width: 17%;
      margin: -24px -24px 5px;
      font-weight: bold;
      }
      .tab {
      overflow: hidden;
      border: 1px solid #ccc;
      background-color: #f1f1f1;
      }
      .tabbingMain {
      overflow: hidden;
      overflow: hidden;
      background-color: #ffffff;
      margin: 20px auto;
      padding: 3px;
      width: 90%;
      min-width: 300px;
      border-radius: 10px;
      min-height: 30px;
      }
      /* Style the buttons inside the tab */
      .tab button {
      background-color: inherit;
      float: left;
      border: none;
      outline: none;
      cursor: pointer;
      padding: 4px 6px;
      transition: 0.3s;
      font-size: 17px;
      }
      /* Change background color of buttons on hover */
      .tab button:hover {
      background-color: #ddd;
      }
      /* Create an active/current tablink class */
      .tab button.active {
      background-color: #ccc;
      }
      /* Style the tab content */
      .tabcontent {
      display: none;
      padding: 6px 12px;
      /* border: 1px solid #ccc;*/
      border-top: none;
      }
      .inputExcludeUrl{
      width:95%;
      }
.removelink {
    position: absolute;
    right: 0px;
    margin-top: 10px;
}
      .spaceValidation,.spanfieldvalidate{
      width:100%;margin-left:0;color:red;display:none;float: left;margin-bottom: 10px
      }
.removelinkOfSaved {
    position: relative;
    right: -20px;
    top: 0px;
}


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

   </style>
<div class="content-section">
	<div class="container-pj">
		<div class="heading-main">
	        <div class="logoLft">
               <img src="<?php echo plugin_dir_url( __DIR__ );?>css/logo.png">
            	<h5>Mobile Redirection</h5>
            </div>            
	    </div>


	    <!-- <div class="second-heading">
            <h5>If you enable mobile redirect kindly clean cache for better experience.</h5>
        </div> -->

        <input type="hidden" id="hiddenhost" value="<?php if(isset(  $projectDetails->data->domain->public_host)) { echo $projectDetails->data->domain->public_host; } ?>">

        <?php //$projectDetails->data->domain->public_host = "abc" ; ?>

        <?php if(isset($projectDetails->data->domain->public_host)){ ?> 
        <div id="config" class="tabcontent tbpad content-card" style="display: block">
	    
	      <?php wp_nonce_field('rw-sortd-redirection', 'sortd-hidden-nonce'); ?>
	      <input type="hidden" id="paramRedirect" name="param" value="redirectTurbo"/>
	      <input type="hidden" id="siteUrlId"  value="<?php echo site_url();?>"/>
	      
	      <div class="sortCard ">
	         <h5 class="sortHed">Redirection</h5>
	         <p>
	            <label class="pure-material-textfield-outlined"	 for="domain_code">
	            	<!-- <?php //_e( ' <strong>Domain Name</strong>', 'sortd-pwa-amp-generator' ); ?> -->
	           
	            <?php if(isset($projectDetails->data->domain->public_host)){ ?>
	            <input type="text" value="<?php echo ($projectDetails->data->domain->public_host);?>" disabled name="domain_name" id="domain_code" onclick="sortd_ShowHideExcludeUrls(this)" />
	            <?php } else { ?>
	            <input type="text" value="<?php echo get_option('sortd_'.$projectDetails->data->id.'_domain_name');?>"  name="domain_name" id="domain_code" onclick="sortd_ShowHideExcludeUrls(this)" required/>
	            <?php } ?>
	            <span><?php _e( ' <strong>Domain Name</strong>', 'sortd-pwa-amp-generator' ); ?></span>
	             </label>
	         </p>
	         <?php if(get_option('sortd_'.$projectDetails->data->id.'_redirection_code') == 'true' ){ 
	            $checked = 'checked';
	            
	            } else {
	            $checked = '';	
	            //update_option('sortd_'.$projectDetails->data->id.'_exclude_url','');				
	            } 
	            
				if(get_option('sortd_'.$projectDetails->data->id.'redirectValueAmp') == 'true' ){ 
					$checkedamp = 'checked';
					
					} else {
					$checkedamp = '';	
					//update_option('sortd_'.$projectDetails->data->id.'_exclude_url','');				
					} 
	            
	            //echo get_option('sortd_'.$projectDetails->data->id.'_redirection_code');
	            ?>
	         <p>
	            <input type="checkbox"  name="redirection_code" id="redirection_code" onclick="sortd_ShowHideExcludeUrls(this)" <?php echo esc_attr($checked); ?> />
	            <label for="redirection_code" class="reAlin"><?php _e( ' <strong>Enable PWA </strong>', 'sortd-pwa-amp-generator' ); ?>
	            </label>

	            <input type="checkbox"  name="redirection_code_amp" id="redirection_code_amp"  <?php echo esc_attr($checkedamp); ?> />
	            <label for="redirection_code_amp" class="reAlin"><?php _e( ' <strong>Enable AMP </strong>', 'sortd-pwa-amp-generator' ); ?>
	            </label>
	         </p>
	         <p id="excludeUrls">

	         	
	         		<?php _e( '<strong>Exclude Urls that contain :</strong>', 'sortd-pwa-amp-generator' ); ?>

	         		<a class="add_more" onclick="sortd_addMoreExcludeUrl()"><i class="bi bi-plus"></i> </a>
	         
	            <label id="exclude_label" style="margin-top: 20px;float: left;width: 100%" for="exclude_url">
	               

	               <?php 
	                  $excludeUrls = get_option('sortd_'.$projectDetails->data->id.'_exclude_url');
	                  if(empty($excludeUrls)) { ?>
	               <input type="text" name="exclude_url[]" id="exclude_url_add1" data-exclude="excludeurl_1"  placeholder="category" class="dfltInput" style="margin-bottom: 14px !important" /> 
	               <!-- <span>category</span> -->
	               <span id="exclude_url_span_add0" style="width:100%;float:left; color: red;margin-bottom:10px;display:none">Spaces are not allowed</span>
	               <span id="exclude_url_spanvalida_add0" style="width:100%;float:left; color: red;margin-bottom:10px;display:none">Please enter value or remove the fields</span>
	               <a href="#" id="exclude_url_remove0" class="delIcn" onclick="removeScript(this.id)"></a><?php
	                  } else {
	                  	$i=1;
	                  	foreach ($excludeUrls as $key => $url) { ?>
	               <input type="text" style="width: 95%" placeholder="category" name="exclude_url[]" id="exclude_url_add<?php echo esc_attr($i);?>" data-exclude="excludeurl_<?php echo esc_attr($i);?>" value="<?php echo esc_attr($excludeUrls[$key]); ?>" class="dfltInput"/> 
	             <!--   <span>category</span> -->

	               <span id="exclude_url_span_add<?php echo esc_attr($i);?>" style="width: 90%; margin-left: 5%; color: red;display:none">Spaces are not allowed</span><a href="#" class="removelinkOfSaved" onclick="removeScript(this.id)" id="exclude_url_remove<?php echo esc_attr($i);?>"><i class="bi bi-trash-fill"></i></a><?php $i++;

	                  }
	                  }
	                  ?>
	            </label>
	            <span class="emptyString" id="Contentable" style="color:red;display:none;"></span>
	            
	         </p>
	      </div>
	      <script type="text/javascript">
	         if(document.getElementById("redirection_code").checked === true){
	         
	         	document.getElementById("excludeUrls").style.display = "block";
	         } else {
	         	document.getElementById("excludeUrls").style.display = "none";
	         }
	         
	         function removeScript(id){
	         
	         	var currentRemId = id;
	         
	         	var resID = currentRemId.split("remove");
	         
	         
	         	document.getElementById("exclude_url_add"+resID[1]).remove();
	         	document.getElementById("exclude_url_span_add"+resID[1]).remove();
	         	document.getElementById("exclude_url_remove"+resID[1]).remove();
	         	document.getElementById("exclude_url_spanvalida_add"+resID[1]).remove();
	         	
	         
	         }
	         
	         //console.log(document.getElementById("redirection_code").checked);
	         	
	         function sortd_ShowHideExcludeUrls(redirectEnable) {
	         	var excludeUrls = document.getElementById("excludeUrls");
	         
	         
	         	excludeUrls.style.display = redirectEnable.checked ? "block" : "none";
	         }
	         
	         function sortd_addMoreExcludeUrl() {
	         
	         	var lastChildPara = document.getElementById("excludeUrls").lastChild.id;
	         
	         	if(lastChildPara == undefined){
	         		//var lastChildID = document.getElementById("exclude_label").lastChild.previousElementSibling.id;
	         		var lastChildID = 'exclude_url_span_add1';
	         	} else if(lastChildPara != undefined){
	         		var lastChildID = document.getElementById("excludeUrls").lastChild.id;
	         	}
	         
	         	var str_pos = lastChildID.indexOf("remove");
	         
	         	if(str_pos > -1){
	         		var res = lastChildID.split("remove");
	         	} else {
	         		var res = lastChildID.split("add");
	         	}
	         
	         	//console.log(res);
	         
	         	
	         	//var lastChildID = document.getElementById("excludeUrls").lastChild;
	         
	         	
	         	var addCount = parseInt(res[1])+1;
	         
	         	var textfield = document.createElement("input");
	         				textfield.type = "text"; textfield.id = "exclude_url_add"+addCount;
	         				// textfield.style= "";
	         				  textfield.name= "exclude_url[]";
	         				  textfield.placeholder = "category/";
	         				  textfield.dataset.exclude = "excludeurl_"+addCount;
	         				  textfield.className = " inputExcludeUrl dfltInput ";
	         
	         	
	         
	         var link=document.createElement("a");
	         link.appendChild(document.createTextNode(""));
	         link.href = '#';
	         link.id = "exclude_url_remove"+addCount;
	         link.onclick = loadScript;
	         link.className = "removelink delIcn";
	         document.body.appendChild(link);
	         
	         function loadScript(){
	         
	         var currentRemId = this.id;
	         
	         var resID = currentRemId.split("remove");
	         
	         
	         document.getElementById("exclude_url_add"+resID[1]).remove();
	         document.getElementById("exclude_url_span_add"+resID[1]).remove();
	         document.getElementById("exclude_url_remove"+resID[1]).remove();
	         document.getElementById("exclude_url_spanvalida_add"+resID[1]).remove();
	         
	         
	         
	         document.getElementById("Contentable").style.display = 'none';
	         
	         }
	         
	         
	         
	         				  var spanfield = document.createElement("span");
	         				spanfield.innerHTML = "Space is not allowed"; spanfield.id = "exclude_url_span_add"+addCount;
	         				 //spanfield.style= "width:90%;margin-left:5%;color:red;display:none";
	         				  spanfield.className= "spaceValidation";

	         				   var spanfieldvalidate = document.createElement("span");
	         				spanfieldvalidate.innerHTML = "Please enter value or remove the fields"; spanfieldvalidate.id = "exclude_url_spanvalida_add"+addCount;
	         				 //spanfield.style= "width:90%;margin-left:5%;color:red;display:none";
	         				  spanfieldvalidate.className= "spanfieldvalidate";
	         				  
	         				 
	         
	         	//console.log(lastChildID);
	         	document.getElementById("excludeUrls").appendChild(textfield);
	         	document.getElementById("excludeUrls").appendChild(link);
	         	document.getElementById("excludeUrls").appendChild(spanfield);
	         	document.getElementById("excludeUrls").appendChild(spanfieldvalidate);
	         	
	         
	         	
	         
	         
	         
	         }
	         
	         
	         
	         
	      </script>
	      <?php 
	         //submit_button(); ?>
	      <button type="button" class="btn btn-ad-dflt saveRedirection">Save</button>
	      <!-- </form> -->
	   </div>
	 <?php }  else { ?>


	    <div class="sortCard onClik_lft heigtDv">
    		<div class="afterStartSetup">
              <div class="onclick_setup_dv">
                <h1>Your public host is not configured.</h1>
                <h2 class="syHead">You need to setup a separate public host*  for mobile PWA + AMP. An additional HTTPS 2.0 certificate will also be added smooth delivery.</h2>

                <div class="fulW">
                  <a class="btn btn-ad-blu" id="demohostclick" href="<?php echo admin_url();?>admin.php?page=sortd-manage-settings&section=sortd_manage_domains">Setup Public Host <i class="bi bi-arrow-right-circle-fill"></i></a>
                </div>
              </div>
              <!-- mob Device -->
              <div class="mobOnclick">
                <div class="mobWt rds">
                  <div class="logMobBar">
                    <p><i class="bi bi-lock-fill"></i>m.<?php echo  $break[1];?></p>
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
        </div>


        <p class="dnsInf">* Please keep your DNS credentials ready to make the changes. </p>


	 	<!-- <div id="" class="tabcontent tbpad content-card" style="display: block">
	 		Your public host is not configured. Please <a id="demohostclick" href="<?php //echo admin_url();?>admin.php?page=sortd-manage-settings&section=sortd_manage_domains">click here<a> to setup public host 
	 	</div> -->
	 <?php } ?>
	</div>
</div>
<?php
   //page function end
   
   ?>
