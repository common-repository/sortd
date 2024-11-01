(function( $ ) {
	'use strict';

	/**
	 * All of the code for your admin-facing JavaScript source
	 * should reside in this file.
	 *
	 * Note: It has been assumed you will write jQuery code here, so the
	 * $ function reference has been prepared for usage within the scope
	 * of this function.
	 *
	 * This enables you to define handlers, for when the DOM is ready:
	 *
	 * $(function() {
	 *
	 * });
	 *
	 * When the window is loaded:
	 *
	 * $( window ).load(function() {
	 *
	 * });
	 *
	 * ...and/or other possibilities.
	 *
	 * Ideally, it is not considered best practise to attach more than a
	 * single DOM-ready or window-load handler for a particular page.
	 * Although scripts in the WordPress core, Plugins and Themes may be
	 * practising this, we should strive to set a better example in our own work.
	 */

	 var  multiinputlength = {}; 
	 var keyArray=[];

	$('.sortd_configure').on( 'click', function( e ) {

		e.preventDefault();

		$("#sortd_configContainer").show();

		return false;

	});

function isValidColor(str) {
    return str.match(/^#[a-f0-9]{6}$/i) !== null;
}	

$(".sortCatCheck").on('change',function() {

	var id = $(this).attr('id');

	var typeId = $(".catDynamic_"+id).attr('data-sortdindex');

	//var nonce = $(this).attr('data-nonce');
	
	  if($(this).is(":checked")) {
	 	//console.log(id);

	 	var flag = true;

	 	typeId = 0;

	 } else {

		var flag = false;

		typeId = typeId;



	 }


	var site_url = $("#siteurl").val();


	  	$.ajax({
	  		url: sortd_ajax_obj_config.ajax_url,
	  		//url: site_url+'/wp-content/plugins/wp_sortd/includes/class-sortd-ajax.php',
	  		data : {'id':id,'action':'categoryChecked','flag' : flag,'typeId':typeId,'sortd_nonce' : sortd_ajax_obj_config.nonce},
	  		type : 'post', 
	  		success: function(result){ 					 
   						//console.log(result);return false;	
	  					try
						{
							
								var remove_after= result.lastIndexOf('}');
								if(remove_after != -1){
								  var dataresult =  result.substring(0,remove_after +1);
								 var res = JSON.parse(result);
												}else {
								  console.log("This is not valid JSON format")
								} 
						
	    			if(res.flag == "true" || res.flag == true){
	    				


	    				if(res.response.status == false || res.response.error.errorCode != 408 && (res.response.error.errorCode == 1004 || res.response.error.errorCode == 1005)){
	    					//console.log(res.response.status,"ad" );return false;
							//window.location.href = site_url+"/wp-admin/admin.php?page=sortd-manage-settings&section=sortd_credential_settings";
							$('.content-section').prepend(`<div class="notice notice-error is-dismissible"><p>`+res.response.error.message+`</p><span class="closeicon" aria-hidden="true">&times;</span></div>`);
						}else if(res.response.status == false && res.response.error.errorCode == 408){

							$('.content-section').prepend(`<div class="notice notice-error is-dismissible"><p>`+res.response.error.message+`</p><span class="closeicon" aria-hidden="true">&times;</span></div>`);

	    					$(".notice-error").delay(2000).fadeOut(500);
						
						} else {

							$(".catDynamic_"+id).attr('data-sortdindex',res.response.data._id);

		    				$("#catSpan_"+id).show();

		    				$(".thtick,.succmsg").show();

		    				$(".img"+id).show();

		
		    			//	$('.content-section').prepend('<div class="notice notice-success is-dismissible"><p>Category successfully added</p><span class="closeicon" aria-hidden="true">&times;</span></div>');

		    			//	$(".notice-success").delay(2000).fadeOut(500);

		    		
						}

	    			
	  					

	    			
	    			} else if(res.flag === "false" || res.flag === false){

	    				
	    				if(res.response.status == false || res.response.error.errorCode != 408 && (res.response.error.errorCode == 1004 || res.response.error.errorCode == 1005)){

							//window.location.href = site_url+"/wp-admin/admin.php?page=sortd-manage-settings&section=sortd_credential_settings";
							$('.content-section').prepend(`<div class="notice notice-error is-dismissible"><p>`+res.response.error.message+`</p><span class="closeicon" aria-hidden="true">&times;</span></div>`);
						}	else if(res.response.status == false && res.response.error.errorCode == 408){

								$('.content-section').prepend(`<div class="notice notice-error is-dismissible"><p>`+res.response.error.message+`</p><span class="closeicon" aria-hidden="true">&times;</span></div>`);

	    						$(".notice-error").delay(2000).fadeOut(500);
						
						} else {

	    			
	    				$("#catSpan_"+id).hide();
	    			

	    				$.each( res.response.data.cat_guid, function( i, l ){
						

						  	$('.table tbody tr').each((index, tr)=> {
      
					        $(tr).children('td').each ((index, td) => {


					            $(".catSyncHead_"+l).hide();
					           
					             $(".sortcheckclass"+l).prop("checked", false);

					        }); 

    						});
						});

	    				//	$(".thtick,.succmsg").hide();

		    				$(".img"+id).hide();

						//$('.content-section').prepend(`<div class="notice notice-success is-dismissible"><p>Category successfully removed</p><span class="closeicon" aria-hidden="true">&times;</span></div>`);

	    			//	$(".notice-success").delay(2000).fadeOut(500);


	    			}
	    			
	    			} else if(res.response.status == false) {

	    				$('.content-section').prepend(`<div class="notice notice-error is-dismissible"><p>`+res.error.message+`</p><span class="closeicon" aria-hidden="true">&times;</span></div>`);
 						$(".notice-error").delay(2000).fadeOut(500);
	    			}
	    			


	    			$(".closeicon").click(function(){
					    $(".notice").hide();
					});

	    		}	catch (e)
						{
							
							console.log(e);
							return false;
						}
	    			
	  		}
		});
 });


$(".cancelRedorder").click(function(){
	window.location.href = window.location.href;
});

	

$("#cancel_add").on('click',function(){

	$("#sortd_secretKeyId").val('');
	$("#sortd_accessKeyId").val('');
});

$("#cancel_update").on('click',function(){

	var valueAccessKey = $("#sortd_accessKeyIdupdate").attr('data-accesskey');
	var valueSecretKey = $("#sortd_secretKeyIdupdate").attr('data-secretkey');

	//console.log(valueAccessKey);return false;

	$("#sortd_secretKeyIdupdate").val(valueSecretKey);
	$("#sortd_accessKeyIdupdate").val(valueAccessKey);
});

var keypressFlag;

$(".editBtn").on('click',function() {

	var sortdid = $(this).data('sortdid');
	$(".display_"+sortdid).hide();
	$(".edit_"+sortdid).show();
	var nameVal = $( "#name_"+sortdid ).val();

	


	$( "#name_"+sortdid ).keypress(function( event ) {

		

		
			if ( event.which == 32 ) {
			   keypressFlag = 1;
			  } else {
			  	keypressFlag = 0;
			  }
		
	});

	});

$(".cancelBtn").on('click',function() {

	var site_url = $("#siteurl").val();
	var sortdid = $(this).data('sortdid');
	var cat_name = $("#name_"+sortdid).val();
	var cat_alias = $("#alias_"+sortdid).val();

	$(".catCheck"+sortdid).hide();

	$.ajax({
		url: sortd_ajax_obj_config.ajax_url,
	  	//url: site_url+'/wp-content/plugins/wp_sortd/includes/class-sortd-ajax.php',
  		data : {'action':'categoryRename','cat_id':sortdid,'name':cat_name,'alias':cat_alias,'sortd_nonce' : sortd_ajax_obj_config.nonce},
  		type : 'post', 
  		success: function(result){

  				var incStr = result.includes("<!--");  
   							
	  					try
						{
							
							var remove_after= result.lastIndexOf('}');
								if(remove_after != -1){
								  var dataresult =  result.substring(0,remove_after +1);
								 var res = JSON.parse(result);
												}else {
								  console.log("This is not valid JSON format")
								} 
						

				  		//	let res = JSON.parse(result);

				  			if(res.status == false && (res.error.errorCode == 1004 || res.error.errorCode == 1005)){
				  				window.location.href = site_url+'/wp-admin/admin.php?page=sortd-manage-settings&section=sortd_credential_settings';
				  			} else {
				  				//$(".succmsg").show();
										$(".img"+sortdid).hide();
				  			}


  						} catch (e)
						{
							
							console.log(e);
							return false;
						}
  		}
	});

	var sortdid = $(this).data('sortdid');	
	$(".edit_"+sortdid).hide();
	$(".display_"+sortdid).show();
});

$(".updateBtn").on('click',function() {

	var sortdid = $(this).data('sortdid');
	var cat_name = $("#name_"+sortdid).val();
	var cat_alias = $("#alias_"+sortdid).val();
	var site_url = $("#siteurl").val();


	var my_string = cat_name;
	var spaceCount = (my_string.split(" ").length - 1);
	var string = (my_string.split(" "));
	//console.log(my_string.length);return false;
	
	if(my_string.match(/^\s+$/) === null && my_string.length !== 0) {
	
	
		
		$.ajax({
			url: sortd_ajax_obj_config.ajax_url,
	  		//url: site_url+'/wp-content/plugins/wp_sortd/includes/class-sortd-ajax.php',
	  		data : {'action':'categoryRename','cat_id':sortdid,'name':cat_name,'alias':cat_alias,'sortd_nonce' : sortd_ajax_obj_config.nonce},
	  		type : 'post', 
	  		success: function(result){

	  				//console.log(result);return false;
	  				var remove_after= result.lastIndexOf('}');
								if(remove_after != -1){
				  var dataresult =  result.substring(0,remove_after +1);
				 var res = JSON.parse(result);
								}else {
				  console.log("This is not valid JSON format")
								} 
	  				try
						{
							
							var remove_after= result.lastIndexOf('}');
								if(remove_after != -1){
								  var dataresult =  result.substring(0,remove_after +1);
								 var res = JSON.parse(result);
												}else {
								  console.log("This is not valid JSON format")
								} 
						

	  				
	  				//var res = JSON.parse(result); 	

	    			if(res.status === true){
	    				$("#lbl_name_"+sortdid).text(cat_name);
	    				$("#lbl_alias_"+sortdid).text(cat_alias);

	    				$(".edit_"+sortdid).hide();
						$(".display_"+sortdid).show();
						console.log('Category Updated Successfully : ' + res.data.cat_id);	 

						$(".colsuccess").show();
						$(".succmsg").show();
						$(".img"+sortdid).show();
					//	$('.content-section').prepend(`<div class="notice notice-success is-dismissible"><p>Category successfully renamed</p><span class="closeicon" aria-hidden="true">&times;</span></div>`);
 					//	$(".notice-success").delay(2000).fadeOut(500);

	    			
	    			} else {

						if(res.error.errorCode != 408 && (res.error.errorCode == 1004 || res.error.errorCode == 1005)){

							window.location.href = site_url+"/wp-admin/admin.php?page=sortd-manage-settings&section=sortd_credential_settings";
						
						}	else {
								$(".edit_"+sortdid).hide();
								$(".display_"+sortdid).show();
								console.log('Category Not Updated');
								$('.content-section').prepend(`<div class="notice notice-error is-dismissible"><p>`+res.error.message+`</p><span class="closeicon" aria-hidden="true">&times;</span></div>`);
		 						$(".notice-error").delay(2000).fadeOut(500);

						}
							
						
						
	    			
	    			}  	

	    			$(".catCheck"+sortdid).hide();			

	    		}   catch (e)
						{
							
							console.log(e);
							return false;
						}  			
	  		}
		});	



		} else if(my_string.length == 0) {
		   $(".catCheck"+sortdid).show();
		} else  {
		   $(".catCheck"+sortdid).show();
		}
});



function insertParam(key, value) {
        key = escape(key); value = escape(value);

        var kvp = document.location.search.substr(1).split('&');
        if (kvp == '') {
            document.location.search = '?' + key + '=' + value;
        }
        else {

            var i = kvp.length; var x; while (i--) {
                x = kvp[i].split('=');

                if (x[0] == key) {
                    x[1] = value;
                    kvp[i] = x.join('=');
                    break;
                }
            }

            if (i < 0) { kvp[kvp.length] = [key, value].join('='); }

            const refresh = window.location.protocol + "//" + window.location.host + window.location.pathname + '?' + kvp.join('&');  
       		 window.history.pushState({ path: refresh }, '', refresh);

            //this will reload the page, it's likely better to store this until finished
           // document.location.search = kvp.join('&');
        }
    }


$(".tablinks").click(function(){
	var dynamicId = $(this).attr('id');
		



 scrollTo(0,0);

	var menuBar = $(".stickkey"+dynamicId);
var menuCont = $(".contentMenu_"+dynamicId);
//var menuCont = $("#restBox");
    $(window).scroll(function() {    
      var scroll = $(window).scrollTop();
         if (scroll >= 250) {
            menuBar.addClass("sticky-menu-left");
            menuCont.addClass("cm-left")
          } else {
            menuBar.removeClass("sticky-menu-left");
            menuCont.removeClass("cm-left")
          }
  });


  var first = $(".contentMenu_"+dynamicId ).children(":first");

	var attrId = first.attr('id');

	var splitfirst = attrId.split(dynamicId);

	var pageContFirst = $(".nav_"+dynamicId+splitfirst[1]);

	  (pageContFirst).addClass("active").siblings().removeClass('active');

    $( ".contentMenu_"+dynamicId ).find('div[class*="page-section-a"]').each(function( ) {

    	var classname = $(this).attr('class');

   
    			var navscrollid = $(this).attr('id');

    			var splitscroll = navscrollid.split(dynamicId);


    		

	    			var pageBar = $("#"+navscrollid);//$("#page_section_"+dynamicId+splitscroll[1]);

						var pageCont = $(".nav_"+dynamicId+splitscroll[1]);

					


						    $(window).scroll(function() {    
						      var scrollPage = $(window).scrollTop();

						 
									var aTop = pageBar;
							    if(scrollPage>= aTop.offset().top-100){
							      
							      (pageCont).addClass("active").siblings().removeClass('active');
							     
							        
							    } else {
							    	 (pageCont).removeClass("active");
							    }
						    
						  });
    		

    
  });





	$("#tabcontent_id_"+dynamicId).show().siblings("div").hide();
	  $('.tablinks').removeClass('active');
	 $("#"+dynamicId).addClass('active');

	  var currURL = window.location.href;
        var url = (currURL.split(window.location.host)[1]).split("&")[0];
        window.history.pushState({}, document.title, url)
        insertParam('parameter',dynamicId);

  

             /*$.ajax({
		        type: 'POST',
		        url: 'http://local.mywordpress.com/wp-content/plugins/wp_sortd/admin/partials/sortd-admin-configform-display.php',
		        data: { 'dynamicId': dynamicId},
		        success: function(response) {
		           // $('#result').html(response);
		        }
		    });*/

	var siteurl = $("#site_url").val();

	var parseCategories;

	$.ajax({
		url: sortd_ajax_obj_config.ajax_url,
  		//url: siteurl+'/wp-content/plugins/wp_sortd/includes/class-sortd-ajax.php',
  		data : {'action':'fetchCategories','sortd_nonce' : sortd_ajax_obj_config.nonce},
  		type : 'post', 
  		async: false,
  		success: function(result){

  		//	console.log("sSS");return false;

  			var incStr = result.includes("<!--");  
			try
			{
				
			

				var remove_after= result.lastIndexOf('}');
								if(remove_after != -1){
								  var dataresult =  result.substring(0,remove_after +1);
								  parseCategories = JSON.parse(result);
								}else {
								  console.log("This is not valid JSON format")
								} 
						

  				
  			
  			} catch (e)
				{
					
					console.log(e);
					return false;
				}  

  		}


	});

	var configSchema;
	var categoryName = [];
	  		
	$.ajax({
  		//url: siteurl+'/wp-content/plugins/wp_sortd/includes/class-sortd-reorder.php',
  		url: sortd_ajax_obj_config.ajax_url,
  		data : {'action':'fetchConfigGroupwise','sortd_nonce' : sortd_ajax_obj_config.nonce},
  		type : 'post', 
  		async: false,
  		success: function(result){

  				var incStr = result.includes("<!--");  
			try
			{
				
			
				var remove_after= result.lastIndexOf('}');
								if(remove_after != -1){
								  var dataresult =  result.substring(0,remove_after +1);
								  configSchema = JSON.parse(result);
								}else {
								  console.log("This is not valid JSON format")
								} 

  			

  			$.each(configSchema.data,function(a,b){

  					categoryName.push(b.items.categoryList.items.category_list.type_items[0].name.type_items);
  					
  			});
  			
  		} catch (e)
				{
					
					console.log(e);
					return false;
				}  

		}
	});


	var configSchemaForArticle;
	var articleConfig = [];
	var articleConfigAmp = [];

	  		
	$.ajax({
  		//url: siteurl+'/wp-content/plugins/wp_sortd/includes/class-sortd-reorder.php',
  		url: sortd_ajax_obj_config.ajax_url,
  		data : {'action':'fetchConfigGroupwiseForArticle','sortd_nonce' : sortd_ajax_obj_config.nonce},
  		type : 'post', 
  		async: false,
  		success: function(result){

  			var incStr = result.includes("<!--");  
			try
			{
				
				

					var remove_after= result.lastIndexOf('}');
								if(remove_after != -1){
								  var dataresult =  result.substring(0,remove_after +1);
								  configSchemaForArticle = JSON.parse(result);
								}else {
								  console.log("This is not valid JSON format")
								} 


  			
	  			$.each(configSchemaForArticle.data,function(a,b){

	  					articleConfig.push(b.items.ads.items.ad_codes.type_items[0].position.type_items);
	  					articleConfigAmp.push(b.items.ads.items.ad_codes.type_items[0].position.type_items);
	  					
	  			});
  			
  			} catch (e)
				{
					
					console.log(e);
					return false;
				}  
  		}


	});


	//console.log(articleConfig,'sdsf',articleConfigAmp);
	//return false;



	$.ajax({
	  		url: sortd_ajax_obj_config.ajax_url,
	  		data : {'action':'getConfigSchemaGroupWise','data':dynamicId,'sortd_nonce' : sortd_ajax_obj_config.nonce},
	  		
	  		type : 'post', 
	  		success: function(result){

	  			var incStr = result.includes("<!--");  


				try
				{
					
				

					var remove_after= result.lastIndexOf('}');
								if(remove_after != -1){
								  var dataresult =  result.substring(0,remove_after +1);
								  var res = JSON.parse(result);
								}else {
								  console.log("This is not valid JSON format")
								} 


	  			

	  			if(res.status == "true" || res.status == true){



	  				if(dynamicId == 'header'){

	  		
	  					var a =JSON.parse(res.data.header);

	  				//	console.log(a);return false;
	  				
	  				} else if(dynamicId == 'general_settings'){

	  					var a =JSON.parse(res.data.general_settings);
	  				
	  				} else if(dynamicId == 'footer'){

	  					var a =JSON.parse(res.data.footer);
	  				
	  				} else if(dynamicId == 'top_menu'){

	  					var a =JSON.parse(res.data.top_menu);
	  				
	  				} else if(dynamicId == 'manifest'){

	  					var a =JSON.parse(res.data.manifest);
	  				
	  				} else if(dynamicId == 'home'){

	  					var getSchema = JSON.parse($(".getschema").val());

	  				

		  				let addmoresection = getSchema.data[dynamicId];

		  				//console.log(addmoresection.items.categoryList.items.category_list.type_items);return false;

		  				$.each(addmoresection.items.categoryList.items.category_list.type_items  , function(a,b){

								
									$.each(b , function(c,d){
								
									
										
										keyArray.push(c);
								});
								
								

							
						});

	  					var a =JSON.parse(res.data.home);
	  				
	  				} else if(dynamicId == 'category'){

	  					var a =JSON.parse(res.data.category);
	  				
	  				} else if(dynamicId == 'article'){

	  						var getSchema = JSON.parse($(".getschema").val());

	  				

		  				let addmoresection = getSchema.data[dynamicId];

		  				//console.log(addmoresection.items.categoryList.items.category_list.type_items);return false;

		  				$.each(addmoresection.items.ads.items.ad_codes.type_items , function(a,b){

								
									$.each(b , function(c,d){
								
									
										
										keyArray.push(c);
								});
								
								

							
						});


	  					var a =JSON.parse(res.data.article);
	  				
	  				} else if(dynamicId == 'widgets'){

	  					var a =JSON.parse(res.data.widgets);
	  				}

	  				

	  		

	  				$('.contentMenu_'+dynamicId).find('input, select, textarea').each(function() {
				       	var id =  ($(this).attr('id'));
				       	var nameField = $(this).attr('name');

				      	//console.log(id);


				     	var type = $(this).attr('type')

				 		

				     	if(type == 'file'){

				       				var splitimg;
				       				var imagedata;

				       				var _URL = window.URL || window.webkitURL;
				       				

				       				$("#"+id).change(function(){


				       					//console.log();return false;

				       					
				       							var width = $(this).attr('data-width');
				       							var height = $(this).attr('data-height');
				       					 
										    	var fileInput = document.getElementById(id);

										    	


												var reader = new FileReader();
												reader.readAsDataURL(fileInput.files[0]);
												
												 var image, file;

												    if ((file = fileInput.files[0])) {
												       
												        image = new Image();
												        
												        image.onload = function() {

												        	//console.log(this.width,this.height,"1");

												        	if(width !=this.width && height !=this.height){
												        		//console.log(this.width,this.height,"2");return false;

												        		$(".spnerror"+id).show();
												        	
												        	} else {

												        		


												//reader.onload = function () {
													//console.log(reader.result);//base64encoded string

													 splitimg = reader.result.split(',')
													 imagedata = {
													    "imageData": '"'+splitimg[1]+'"', "filedName" : "icon"
													};

													//console.log(imagedata,this.width,this.height,"1");return false;
										

							       				$.ajax({
							       					url: sortd_ajax_obj_config.ajax_url,
											  		//url: siteurl+'/wp-content/plugins/wp_sortd/includes/class-sortd-ajax.php',
											  		data : {'action':'fileUpload','data':imagedata,'sortd_nonce' : sortd_ajax_obj_config.nonce},
											  		type : 'post', 
											  		success: function(result){

											  			

											  			var resImage = JSON.parse(result);

											  			$(".spnerror"+id).hide();
											  			$('#hidden_'+id).attr('value',resImage.data.imageUrl);
											  			$('#dvPreview'+id).attr("src", resImage.data.imageUrl);
											  			$("#remove"+id).show();
											
											  			
											  		}
												});

											//}

										}

												        
												           
										};
												    
												       
										image.src = _URL.createObjectURL(file);

									}

				       		});

				       	}

				       	if(type !== undefined)

					    $("#remove"+id).click(function(){

					    	$(".spnerror"+id).hide();

					    	$("#"+id).val('');

					    	if(id == 'components-header_template-external_icon1'){
					    		$("#components-header_template-external_icon1").prop('required',true);
					    	} else if(id == 'components-header_template-external_icon2'){
					    		$("#components-header_template-external_icon2").prop('required',true);
					    	} else if(id == 'header_branding-brand_logo'){
					    		$("#header_branding-brand_logo").prop('required',true);
					    	}
					    	
					    

					    	
				    		
				    		$('#hidden_'+id).val('');
				    		$('#dvPreview'+id).attr("src", "");

					    });
				     

					    if(id){
					    	
					    	var idArray = id.split("-");

					    }

					    
					    var savedConfig = a;
					    //console.log(idArray);return false;

					    if(idArray){

				
				    		 

				
				    		if(idArray.length == 1){

				    			

				    				let fieldVal = "";

				    				if(savedConfig[idArray[0]] !== undefined){
				    					fieldVal = savedConfig[idArray[0]];
				    					if(type == 'file'){

					       								if(fieldVal === ""){
					       									$("#remove"+id).hide();
					       								}

					       								if(fieldVal !=  ""){
					       									$('#dvPreview'+id).attr("src",fieldVal)
					       								}


					       								//$('#dvPreview'+id).attr("src",fieldVal);
					       								$(".spnerror"+id).hide();
					       								$('#hidden_'+id).attr('value',fieldVal);

					       							} else{

					       								if(type == 'checkbox' && (fieldVal == 'on' || fieldVal == 'true' || fieldVal == true)){
					       									$("#"+id).attr('checked',true);
					       									$("#"+id).attr('value',true);
					       								} else if(type == 'checkbox' && (fieldVal == false || fieldVal == "false")){

					       								

					       									$("#"+id).attr('checked',false);
					       									$("#"+id).attr('value',false);
					       								
					       								} else {
					       									if(fieldVal.length == 0 && id== 'categories' ){
					       									
					       										   $('#'+id+' option').prop('selected', true);
					       				
					       									} else {
					       										($("#"+id).val(fieldVal));
					       									}


					       								}


					       								
					       								if(type == 'color'){
					       									console.log("#hex_"+id)
					       									console.log(fieldVal)
					       									$("#hex_"+id).val(fieldVal);
					       								}
					       								//$("#hex_"+id).val(fieldVal);
					       							}
				    				}else{
				    					//continue;
				    				}

							
					       	}


					       	if(idArray.length == 2){

					       		
					       		let fieldVal = "";

					       		

					       		if( savedConfig[idArray[0]] !== undefined){
				    					fieldVal = savedConfig[idArray[0]][idArray[1]];
				    					if(type == 'file'){

					       								if(fieldVal === ""){
					       									$("#remove"+id).hide();
					       								}

					       								if(fieldVal !=  ""){
					       									$('#dvPreview'+id).attr("src",fieldVal)
					       								}


					       								//$('#dvPreview'+id).attr("src",fieldVal);
					       								$(".spnerror"+id).hide();
					       								$('#hidden_'+id).attr('value',fieldVal);

					       							} else{
					       								if(type == 'checkbox' && (fieldVal == 'on' || fieldVal == 'true' || fieldVal == true)){
					       									$("#"+id).attr('checked',true);
					       									$("#"+id).attr('value',true);
					       								} else if(type == 'checkbox' && (fieldVal == false || fieldVal == "false")){

					       								

					       									$("#"+id).attr('checked',false);
					       									$("#"+id).attr('value',false);
					       								
					       								} else {
					       									$("#"+id).val(fieldVal);
					       									$("textarea#"+id).text(fieldVal)
					       								}
					       							
	
					       								//($("#"+id).val(fieldVal));
					       								if(type == 'color'){
					       									console.log("#hex_"+id)
					       									console.log(fieldVal)
					       									$("#hex_"+id).val(fieldVal);
					       								}
					       								//$("#hex_"+id).val(fieldVal);
					       							}
				    				}else{
				    					//continue;

				    					console.log("fail")
				    				}

					       	} 

					       	if(idArray.length == 3){
					       		
					       		let fieldVal = "";

					       		
					       		if( savedConfig[idArray[0]] !== undefined){
				    					fieldVal = savedConfig[idArray[0]][idArray[1]][idArray[2]];
				    					if(type == 'file'){

					       								if(fieldVal === ""){
					       									$("#remove"+id).hide();
					       								}

					       								if(fieldVal !=  ""){
					       									$('#dvPreview'+id).attr("src",fieldVal)
					       								}


					       								//$('#dvPreview'+id).attr("src",fieldVal);
					       								$(".spnerror"+id).hide();
					       								$('#hidden_'+id).attr('value',fieldVal);

					       							} else{

					       								if(type == 'checkbox' && (fieldVal == 'on' || fieldVal == 'true' || fieldVal == true)){
					       									$("#"+id).attr('checked',true);
					       									$("#"+id).attr('value',true);
					       								} else if(type == 'checkbox' && (fieldVal == false || fieldVal == "false")){

					       								

					       									$("#"+id).attr('checked',false);
					       									$("#"+id).attr('value',false);
					       								
					       								} else {
					       									$("#"+id).val(fieldVal);
					       								}


					       								
					       								if(type == 'color'){
					       									console.log("#hex_"+id)
					       									console.log(fieldVal)
					       									$("#hex_"+id).val(fieldVal);
					       								}
					       								//$("#hex_"+id).val(fieldVal);
					       							}
				    				}else{
				    					//continue;

				    					console.log("fail 3");
				    				}

					       		//return false;
					         				
							
											       		
							}

								

							if(idArray.length == 4){

								//console.log(id,"testing");

								//console.log(savedConfig[idArray[0]][idArray[1]][idArray[2]].length);

					       		let fieldVal = "";

				    				if(savedConfig[idArray[0]] !== undefined){
				    					fieldVal = savedConfig[idArray[0]][idArray[1]][idArray[2]][idArray[3]];
				    					if(type == 'file'){

					       								if(fieldVal === ""){
					       									$("#remove"+id).hide();
					       								}

					       								if(fieldVal !=  ""){
					       									$('#dvPreview'+id).attr("src",fieldVal)
					       								}


					       							//	$('#dvPreview'+id).attr("src",fieldVal);
					       								$(".spnerror"+id).hide();
					       								$('#hidden_'+id).attr('value',fieldVal);

					       							} else{

					       								if(type == 'checkbox' && (fieldVal == 'on' || fieldVal == 'true' || fieldVal == true)){
					       									$("#"+id).attr('checked',true);
					       									$("#"+id).attr('value',true);
					       								} else if(type == 'checkbox' && (fieldVal == false || fieldVal == "false")){

					       								

					       									$("#"+id).attr('checked',false);
					       									$("#"+id).attr('value',false);
					       								
					       								}  else{
					       									
						       								if(type == 'color'){
						       									
						       									//console.log(fieldVal)
						       									$("#hex_"+id).val(fieldVal);
						       									($("#"+id).val(fieldVal));
						       								} else {
					       										$("#"+id).val(fieldVal);
					       											
						       								}
					       								}


					       								
					       							
					       							}
				    				}else{
				    					//continue;
				    					console.log("fail 4");
				    				}


					       			
								
					       						
									

									
												       		
							}

							if(idArray.length == 5){

								

					       		let fieldVal = "";

				    				if(savedConfig[idArray[0]][idArray[1]][idArray[2]][idArray[3]][idArray[4]] !== undefined){
				    					fieldVal = savedConfig[idArray[0]][idArray[1]][idArray[2]][idArray[3]][idArray[4]];
				    					if(type == 'file'){

					       								if(fieldVal === ""){
					       									$("#remove"+id).hide();
					       								}

					       								if(fieldVal !=  ""){
					       									$('#dvPreview'+id).attr("src",fieldVal)
					       								}


					       							//	$('#dvPreview'+id).attr("src",fieldVal);
					       								$(".spnerror"+id).hide();
					       								$('#hidden_'+id).attr('value',fieldVal);

					       							} else{

					       								if(type == 'checkbox' && (fieldVal == 'on' || fieldVal == 'true' || fieldVal == true)){
					       									$("#"+id).attr('checked',true);
					       									$("#"+id).attr('value',true);
					       								} else if(type == 'checkbox' && (fieldVal == false || fieldVal == "false")){

					       								

					       									$("#"+id).attr('checked',false);
					       									$("#"+id).attr('value',false);
					       								
					       								}  else {
					       									($("#"+id).val(fieldVal));
					       								}


					       								($("#"+id).val(fieldVal));
					       								if(type == 'color'){
					       									console.log("#hex_"+id)
					       									console.log(fieldVal)
					       									$("#hex_"+id).val(fieldVal);
					       								}
					       								//$("#hex_"+id).val(fieldVal);
					       							}
				    				}else{
				    					//continue;
				    					console.log("fail 4");
				    				}


					       			
								
					       						
									

									
												       		
							}


						




						}	


				       
				    });
				
				} else {


					$('.header').prepend(`<div class="notice notice-error is-dismissible"><p>Config not found . Some error occured</p><span class="closeicon" aria-hidden="true">&times;</span></div>`);
 					$(".notice-error").delay(2000).fadeOut(500);
	    			
	  			}


	  				$('.colorpicker').on('input', function() {



				  		var id = this.id;
					
				  		//console.log(this.value);return false;
						$('#hex_'+id).val(this.value);
					});
					
					$('.hexcolor').on('input', function() {
						var idcolor = this.id;
						var splitvalue = idcolor.split('hex_')
					  $('#'+splitvalue[1]).val(this.value);

					  var flag =  isValidColor(this.value);

							if(flag == false){
								$(".hexspan_"+splitvalue[1]).show();
							}
					});
	    			
	  		
              } catch(e){
              	console.log(e);
              	return false;
              }
	  		}
		});


});

$( window ).load(function() {



	$('.tablinks').removeClass('active');
	
	var url_string  = window.location.href;
	var url = new URL(url_string);
	var dynamicId = url.searchParams.get("parameter");
	var sp = url_string.split('/wp-admin');
	var siteurl = sp[0];
	var page = url.searchParams.get("page");
	var section = url.searchParams.get("section");
	

	if(dynamicId == null && section == 'sortd_config'){
		dynamicId = 'general_settings';
	}

	var first = $(".contentMenu_"+dynamicId ).children(":first");

	var attrId = first.attr('id');

	if(attrId != undefined){

	var splitfirst = attrId.split(dynamicId);

	var pageContFirst = $(".nav_"+dynamicId+splitfirst[1]);

	  (pageContFirst).addClass("active").siblings().removeClass('active');


	    $( ".contentMenu_"+dynamicId ).find('div[class*="page-section-a"]').each(function( ) {


    	var classname = $(this).attr('class');


   
    			var navscrollid = $(this).attr('id');

    			var splitscroll = navscrollid.split(dynamicId);


    		

	    			var pageBar = $("#"+navscrollid);//$("#page_section_"+dynamicId+splitscroll[1]);

						var pageCont = $(".nav_"+dynamicId+splitscroll[1]);


						    $(window).scroll(function() {    
						      var scrollPage = $(window).scrollTop();

						 
									var aTop = pageBar;
							    if(scrollPage>= aTop.offset().top-100){
							      
							      (pageCont).addClass("active").siblings().removeClass('active');
							     
							        
							    } else {
							    	 (pageCont).removeClass("active");
							    }
						    
						  });
    		

    
  });

	}

	
	var menuBar = $(".stickkey"+dynamicId);
var menuCont = $(".contentMenu_"+dynamicId);
//var menuCont = $("#restBox");
    $(window).scroll(function() {    
      var scroll = $(window).scrollTop();
         if (scroll >= 250) {
            menuBar.addClass("sticky-menu-left");
            menuCont.addClass("cm-left")
          } else {
            menuBar.removeClass("sticky-menu-left");
            menuCont.removeClass("cm-left")
          }
  });


    if(section == 'sortd_config'){


		var parseCategories;

		$("#tabcontent_id_"+dynamicId).show();


		$("#"+dynamicId).addClass('active');

		$.ajax({
			url: sortd_ajax_obj_config.ajax_url,
	  		//url: siteurl+'/wp-content/plugins/wp_sortd/includes/class-sortd-ajax.php',
	  		data : {'action':'fetchCategories','sortd_nonce' : sortd_ajax_obj_config.nonce},
	  		type : 'post', 
	  		async: false,
	  		success: function(result){

	 				var incStr = result.includes("<!--");  
				try
				{
					
						var remove_after= result.lastIndexOf('}');
									if(remove_after != -1){
									  var dataresult =  result.substring(0,remove_after +1);
									  parseCategories = JSON.parse(result);
									}else {
									  console.log("This is not valid JSON format")
									} 


	  			

	  			

	  			if(parseCategories.error != undefined){

		  			if(parseCategories.error.errorCode != 408 && parseCategories.error.errorCode != 1004 && parseCategories.error.errorCode != 1005){

		  				$(".notice-success").delay(5000).fadeOut(500);
						$('.header').prepend(`<div class="notice notice-error is-dismissible"><p>`+parseCategories.error.message+`</p><span class="closeicon" aria-hidden="true">&times;</span></div>`);
		 				$(".notice-error").delay(2000).fadeOut(500);
					} if(parseCategories.error.errorCode != 408 && (parseCategories.error.errorCode == 1004 || parseCategories.error.errorCode == 1005)){


						$('.header').prepend(`<div class="notice notice-error is-dismissible"><p>`+parseCategories.error.message+`</p><span class="closeicon" aria-hidden="true">&times;</span></div>`);
		 				
					} else if( parseCategories.error.errorCode == 408) {
										//$('.header').prepend(`<div class="notice notice-error is-dismissible "><p>`+res.error.message+`</p><span class="closeicon" aria-hidden="true">&times;</span></div>`);
		 				$("#curlErrorp").text(parseCategories.error.message);
		 				$(".curlErrorDivConfig").show();
		 				$(".header").hide();
		 				//$(".curlErrorDiv").delay(2000).fadeOut(500);

					} 
				}else {
					$(".notice-success").delay(15000).fadeOut(500);
					parseCategories = parseCategories;

					$(".notice-dismiss").click(function(){

		
									$(".configPopup").hide();
								});

				}


		  		} catch(e){
		  			console.log(e);return false;
		  		}	
	  			
	  		}


		});

	}

	var configSchema;
	var categoryName = [];

  	if(section == 'sortd_config'){


		$.ajax({
	  		url: sortd_ajax_obj_config.ajax_url,
	  		data : {'action':'fetchConfigGroupwise','sortd_nonce' : sortd_ajax_obj_config.nonce},
	  		type : 'post', 
	  		async: false,
	  		success: function(result){

	  			var incStr = result.includes("<!--");  
				try
				{
					
						var remove_after= result.lastIndexOf('}');
									if(remove_after != -1){
									  var dataresult =  result.substring(0,remove_after +1);
									  configSchema = JSON.parse(result);
									}else {
									  console.log("This is not valid JSON format")
									} 

	  			

	  			$.each(configSchema.data,function(a,b){

	  					categoryName.push(b.items.categoryList.items.category_list.type_items[0].name.type_items);
	  					
	  			});
	  			
	  		} catch(e){
	  			console.log(e);return false;
	  		}

	  		}


		});


	}

	var configSchemaForArticle;
	var articleConfig = [];
	var articleConfigAmp = [];

		if(section == 'sortd_config'){


	$.ajax({
  		url: sortd_ajax_obj_config.ajax_url,
  		data : {'action':'fetchConfigGroupwiseForArticle','sortd_nonce' : sortd_ajax_obj_config.nonce},
  		
  		type : 'post', 
  		async: false,
  		success: function(result){

  			var incStr = result.includes("<!--");  
			try
			{
				
					var remove_after= result.lastIndexOf('}');
								if(remove_after != -1){
								  var dataresult =  result.substring(0,remove_after +1);
								  configSchemaForArticle = JSON.parse(result);
								}else {
								  console.log("This is not valid JSON format")
								} 

  			

  			$.each(configSchemaForArticle.data,function(a,b){

  					articleConfig.push(b.items.ads.items.ad_codes.type_items[0].position.type_items);
  					articleConfigAmp.push(b.items.ads.items.ad_codes.type_items[0].position.type_items);
  					
  			});
  			
  		} catch(e){
  			console.log(e);return false;
  		}

  		}


	});

}
	
	if(section == 'sortd_config'){
		$.ajax({
	  		url: sortd_ajax_obj_config.ajax_url,
	  		data : {'action':'getConfigSchemaGroupWise','data':dynamicId,'sortd_nonce' : sortd_ajax_obj_config.nonce},
	  		type : 'post', 
	  		success: function(result){

	  		var incStr = result.includes("<!--");  
			try
			{
				
					var remove_after= result.lastIndexOf('}');
								if(remove_after != -1){
								  var dataresult =  result.substring(0,remove_after +1);
								  var res = JSON.parse(result);
								}else {
								  console.log("This is not valid JSON format")
								} 

	  			



	  			if(res.status == true || res.status == "true"){

	  				if(dynamicId == 'header'){

	  					var a =JSON.parse(res.data.header);
	  				
	  				} else if(dynamicId == 'general_settings'){

	  					var a =JSON.parse(res.data.general_settings);
	  				
	  				} else if(dynamicId == 'footer'){

	  					var a =JSON.parse(res.data.footer);
	  				
	  				} else if(dynamicId == 'top_menu'){

	  					var a =JSON.parse(res.data.top_menu);
	  				
	  				} else if(dynamicId == 'manifest'){

	  					var a =JSON.parse(res.data.manifest);
	  				
	  				} else if(dynamicId == 'home'){

	  					var getSchema = JSON.parse($(".getschema").val());

	  				

		  				let addmoresection = getSchema.data[dynamicId];

		  				//console.log(addmoresection.items.categoryList.items.category_list.type_items);return false;

		  				$.each(addmoresection.items.categoryList.items.category_list.type_items , function(a,b){

								
									$.each(b , function(c,d){
								
									
										
										keyArray.push(c);
								});
								
								

							
						});

	  					var a =JSON.parse(res.data.home);
	  				
	  				} else if(dynamicId == 'category'){

	  					var a =JSON.parse(res.data.category);
	  				
	  				} else if(dynamicId == 'article'){

	  					var getSchema = JSON.parse($(".getschema").val());

	  				

		  				let addmoresection = getSchema.data[dynamicId];

		  				//console.log(addmoresection.items.ads.items.ad_codes.type_items);return false;

		  				$.each(addmoresection.items.ads.items.ad_codes.type_items , function(a,b){

								
									$.each(b , function(c,d){
								
									
										
										keyArray.push(c);
								});
								
								

							
						});

	  					var a =JSON.parse(res.data.article);
	  				
	  				} else if(dynamicId == 'widgets'){

	  					var a =JSON.parse(res.data.widgets);
	  				}


	  				console.log(a);

	  					var savedConfig = a;

	  			 	$('.contentMenu_'+dynamicId).find('input, select, textarea').each(function() {
				       	var id =  ($(this).attr('id'));
				       	var nameField = $(this).attr('name');

				      //	console.log(id,"idtest");


				     	var type = $(this).attr('type')

				 		

				     	if(type == 'file'){

				       				var splitimg;
				       				var imagedata;

				       				var _URL = window.URL || window.webkitURL;
				       				

				       				$("#"+id).change(function(){


				       					//console.log();return false;

				       					
				       							var width = $(this).attr('data-width');
				       							var height = $(this).attr('data-height');
				       					 
										    	var fileInput = document.getElementById(id);

										    	


												var reader = new FileReader();
												reader.readAsDataURL(fileInput.files[0]);
												
												 var image, file;

												    if ((file = fileInput.files[0])) {
												       
												        image = new Image();
												        
												        image.onload = function() {

												        	//console.log(this.width,this.height,"1");

												        	if(width !=this.width && height !=this.height){
												        		//console.log(this.width,this.height,"2");return false;

												        		$(".spnerror"+id).show();
												        	
												        	} else {

												        		


												//reader.onload = function () {
													//console.log(reader.result);//base64encoded string

													 splitimg = reader.result.split(',')
													 imagedata = {
													    "imageData": '"'+splitimg[1]+'"', "filedName" : "icon"
													};

												//	console.log(imagedata,this.width,this.height,"1");return false;
										

							       				$.ajax({
							       					url: sortd_ajax_obj_config.ajax_url,
											  		//url: siteurl+'/wp-content/plugins/wp_sortd/includes/class-sortd-ajax.php',
											  		data : {'action':'fileUpload','data':imagedata,'sortd_nonce' : sortd_ajax_obj_config.nonce},
											  		type : 'post', 
											  		success: function(result){

											  			

											  			var resImage = JSON.parse(result);

											  			
											  			//$('#hidden_'+id).val(resImage.data.imageUrl);
											  			$('#dvPreview'+id).attr("src", resImage.data.imageUrl);
														$('#hidden_'+id).attr('value',resImage.data.imageUrl);
					       									$("#remove"+id).show();
					       									$(".spnerror"+id).hide();
					       								
											  			
											  		}
												});

											//}

										}

												        
												           
										};
												    
												       
										image.src = _URL.createObjectURL(file);

									}



				       				
				       				});
				       	}

				       	if(type !== undefined)

					    $("#remove"+id).click(function(){

					    	$("#"+id).val('');

					    		if(id == 'components-header_template-external_icon1'){
					    		$("#components-header_template-external_icon1").prop('required',true);
					    	} else if(id == 'components-header_template-external_icon2'){
					    		$("#components-header_template-external_icon2").prop('required',true);
					    	} else if(id == 'header_branding-brand_logo'){
					    		$("#header_branding-brand_logo").prop('required',true);
					    	}
				    		
				    		$('#hidden_'+id).val('');
				    		$('#dvPreview'+id).attr("src", "");

					    });
				     

					    if(id){
					    	
					    	var idArray = id.split("-");

					    }

					    // console.log(idArray);
					    // console.log(savedConfig);
					    // console.log(savedConfig[idArray[0]])

					    // //return false;

					    if(idArray){

				
				    		if(idArray.length == 1){

				    			

				    				let fieldVal = "";

				    				if(savedConfig[idArray[0]] !== undefined){
				    					fieldVal = savedConfig[idArray[0]];
				    					if(type == 'file'){

					       								if(fieldVal === ""){
					       									$("#remove"+id).hide();
					       								}

					       								if(fieldVal !=  ""){
					       									$('#dvPreview'+id).attr("src",fieldVal)
					       								}


					       							//	$('#dvPreview'+id).attr("src",fieldVal);
					       								$(".spnerror"+id).hide();
					       								$('#hidden_'+id).attr('value',fieldVal);

					       							} else{

					       								if(type == 'checkbox' && (fieldVal == 'on' || fieldVal == 'true' || fieldVal == true)){
					       									$("#"+id).attr('checked',true);
					       									$("#"+id).attr('value',true);
					       								} else if(type == 'checkbox' && (fieldVal == false || fieldVal == "false")){

					       								

					       									$("#"+id).attr('checked',false);
					       									$("#"+id).attr('value',false);
					       								
					       								} else {

					       									if(fieldVal.length == 0 && id== 'categories' ){
					       									
					       										   $('#'+id+' option').prop('selected', true);
					       				
					       									} else {
					       										($("#"+id).val(fieldVal));
					       									}
					       									
					       										
					       								}


					       							
					       								if(type == 'color'){
					       									console.log("#hex_"+id)
					       									console.log(fieldVal)
					       									$("#hex_"+id).val(fieldVal);
					       								}
					       								//$("#hex_"+id).val(fieldVal);
					       							}
				    				}else{
				    					//continue;
				    				}

							
					       	}


					       	if(idArray.length == 2){

					       		
					       		let fieldVal = "";

					       		

					       		if( savedConfig[idArray[0]] !== undefined){
				    					fieldVal = savedConfig[idArray[0]][idArray[1]];

				    					
				    					if(type == 'file'){

					       								if(fieldVal === ""){
					       									$("#remove"+id).hide();
					       								}

					       								if(fieldVal !=  ""){
					       									$('#dvPreview'+id).attr("src",fieldVal)
					       								}


					       							//	$('#dvPreview'+id).attr("src",fieldVal);
					       								$(".spnerror"+id).hide();
					       								$('#hidden_'+id).attr('value',fieldVal);

					       							} else{


					       								if(type == 'checkbox' && (fieldVal == 'on' || fieldVal == 'true' || fieldVal == true)){
					       									$("#"+id).attr('checked',true);
					       									$("#"+id).attr('value',true);
					       								} else if(type == 'checkbox' && (fieldVal == false || fieldVal == "false")){

					       								

					       									$("#"+id).attr('checked',false);
					       									$("#"+id).attr('value',false);
					       								
					       								} else {

					       									console.log(fieldVal,"f1");
					       										($("#"+id).val(fieldVal));
					       										//$("textarea#ads-header_adcode").text("fieldVal");
					       										$("textarea#"+id).text(fieldVal)
					       										//console.log(id,fieldVal)

					       									//	$("#"+id).attr("value", fieldVal);

					       								}

					       							

					       								if(type == 'color'){
					       								//	console.log("#hex_"+id)
					       								//	console.log(fieldVal)
					       									$("#hex_"+id).val(fieldVal);
					       								}
					       								
					       							}
				    				}else{
				    					//continue;

				    					console.log("fail")
				    				}

					       	} 

					       	if(idArray.length == 3){
					       		
					       		let fieldVal = "";

					       		
					       		if( savedConfig[idArray[0]] !== undefined){
				    					fieldVal = savedConfig[idArray[0]][idArray[1]][idArray[2]];
				    					if(type == 'file'){

					       								if(fieldVal === ""){
					       									$("#remove"+id).hide();
					       								}

					       								if(fieldVal !=  ""){
					       									$('#dvPreview'+id).attr("src",fieldVal)
					       								}


					       							//	$('#dvPreview'+id).attr("src",fieldVal);
					       								$(".spnerror"+id).hide();
					       								$('#hidden_'+id).attr('value',fieldVal);

					       							} else{

					       								if(type == 'checkbox' && (fieldVal == 'on' || fieldVal == 'true' || fieldVal == true)){
					       									
					       									console.log(fieldVal,id,"yesyyes");
					       									$("#"+id).attr('checked',true);
					       									$("#"+id).attr('value',true);
					       								} else if(type == 'checkbox' && (fieldVal == false || fieldVal == "false")){

					       								

					       									$("#"+id).attr('checked',false);
					       									$("#"+id).attr('value',false);
					       								
					       								} else {
					       										($("#"+id).val(fieldVal));
					       								}


					       								
					       								if(type == 'color'){
					       									console.log("#hex_"+id)
					       									console.log(fieldVal)
					       									$("#hex_"+id).val(fieldVal);
					       								}
					       								//$("#hex_"+id).val(fieldVal);
					       							}
				    				}else{
				    					//continue;

				    					console.log("fail 3");
				    				}

					       		//return false;
					         				

							

											       		
							}

								

							if(idArray.length == 4){
								console.log('id is' ,(id))
							//	console.log( savedConfig[idArray[0]][idArray[1]]);

								//console.log(savedConfig[idArray[0]][idArray[1]][idArray[2]]);

					       		let fieldVal = "";

				    				if(savedConfig[idArray[0]] !== undefined){
				    					fieldVal = savedConfig[idArray[0]][idArray[1]][idArray[2]][idArray[3]];
				    					if(type == 'file'){

					       								if(fieldVal === ""){
					       									$("#remove"+id).hide();
					       								}

					       								if(fieldVal !=  ""){
					       									$('#dvPreview'+id).attr("src",fieldVal)
					       								}


					       							//	$('#dvPreview'+id).attr("src",fieldVal);
					       								$(".spnerror"+id).hide();
					       								$('#hidden_'+id).attr('value',fieldVal);

					       							} else{

					       								if(type == 'checkbox' && (fieldVal == 'on' || fieldVal == 'true' || fieldVal == true)){
					       									$("#"+id).attr('checked',true);
					       									$("#"+id).attr('value',true);
					       								} else if(type == 'checkbox' && (fieldVal == false || fieldVal == "false")){

					       								

					       									$("#"+id).attr('checked',false);
					       									$("#"+id).attr('value',false);
					       								
					       								}  else {

						       								if(type == 'color'){
						       									
						       									//console.log(fieldVal)
						       									$("#hex_"+id).val(fieldVal);
						       									($("#"+id).val(fieldVal));
						       								} else {
					       										$("#"+id).val(fieldVal);
					       											
						       								}
					       								}


					       								
					       								// if(type == 'color'){
					       								// 	console.log("#hex_"+id)
					       								// 	//console.log(fieldVal)
					       								// 	$("#hex_"+id).val(fieldVal);
					       								// }
					       								//$("#hex_"+id).val(fieldVal);
					       							}
				    				}else{
				    					//continue;
				    				//	console.log("fail 4");
				    				}


					       			
								
					       						
									

									
												       		
							}

							if(idArray.length == 5){

								

					       		let fieldVal = "";

				    				if(savedConfig[idArray[0]][idArray[1]][idArray[2]][idArray[3]][idArray[4]] !== undefined){
				    					fieldVal = savedConfig[idArray[0]][idArray[1]][idArray[2]][idArray[3]][idArray[4]];
				    					if(type == 'file'){

					       								if(fieldVal === ""){
					       									$("#remove"+id).hide();
					       								}

					       								if(fieldVal !=  ""){
					       									$('#dvPreview'+id).attr("src",fieldVal)
					       								}


					       								//$('#dvPreview'+id).attr("src",fieldVal);
					       								$(".spnerror"+id).hide();
					       								$('#hidden_'+id).attr('value',fieldVal);

					       							} else{

					       								if(type == 'checkbox' && (fieldVal == 'on' || fieldVal == 'true' || fieldVal == true)){
					       									$("#"+id).attr('checked',true);
					       									$("#"+id).attr('value',true);
					       								} else if(type == 'checkbox' && (fieldVal == false || fieldVal == "false")){

					       								

					       									$("#"+id).attr('checked',false);
					       									$("#"+id).attr('value',false);
					       								
					       								}  else {
					       										($("#"+id).val(fieldVal));
					       								}


					       							
					       								if(type == 'color'){
					       									console.log("#hex_"+id)
					       									console.log(fieldVal)
					       									$("#hex_"+id).val(fieldVal);
					       								}
					       								//$("#hex_"+id).val(fieldVal);
					       							}
				    				}else{
				    					//continue;
				    					console.log("fail 4");
				    				}


					       			
								
					       						
									

									
												       		
							}


						}	


				       
				    });
				} else {

					if(section == 'sortd_config'){

						if(res.error.errorCode != 408 && res.error.errorCode != 1004 && res.error.errorCode != 1005){
							$('.header').prepend(`<div class="notice notice-error is-dismissible"><p>`+res.error.message+`</p><span class="closeicon" aria-hidden="true">&times;</span></div>`);
								$(".notice-error").delay(2000).fadeOut(500);
						} else if(res.error.errorCode != 408 && (res.error.errorCode == 1004 || res.error.errorCode == 1005)){

							$('.header').prepend(`<div class="notice notice-error is-dismissible"><p>`+res.error.message+`</p><span class="closeicon" aria-hidden="true">&times;</span></div>`);
	 				
						}	else {
								//$('.header').prepend(`<div class="notice notice-error is-dismissible "><p>`+res.error.message+`</p><span class="closeicon" aria-hidden="true">&times;</span></div>`);
 							$(".curlErrorDiv").show();
 							$(".header").hide();

							}
							
						
						


 					}
	    			
	  			}



	  				$('.colorpicker').on('input', function() {



				  		var id = this.id;
					
				  		//console.log(this.value);return false;
						$('#hex_'+id).val(this.value);
					});
					
					$('.hexcolor').on('input', function() {
						var idcolor = this.id;
						var splitvalue = idcolor.split('hex_')
					  $('#'+splitvalue[1]).val(this.value);

					   var flag =  isValidColor(this.value);

							if(flag == false){
								$(".hexspan_"+splitvalue[1]).show();
							}
					});
			
	  		} catch(e){
	  			console.log(e);return false;
	  		}
			}
		});

	}



});


$(".navigation__link").click(function(){
	var navId = $(this).attr('data-nav-id');



	  $('html,body').delay(500).animate({
        scrollTop: $("#page_section_"+navId).offset().top-100},
        'slow');


	return false;
});




	function getEvent(event){

	event.value==="true" ? event.value="false" : event.value="true" ;
		//event.value="hi"


	}




$(document).on('change','.categoryList-category_list-category_id',function(){
     	var selectVal = $(this).val();
			var recentValue = $(this).attr('name');

		var optionSelected = $(this).find("option:selected");
     var valueSelected  = optionSelected.val();
     var textSelected   = optionSelected.text();

			var splitname = recentValue.split('/');


			 $("input[name='categoryList:category_list:card_label/"+splitname[1]+"']").val(textSelected);
	
});



/*multi-input function for rendering section */
var j
var addVa;

function addMultiinputSection(currentTab,currentSection,getcategoriesAll,getSchema,btnAttribute,getSavedConfig){

	
//	console.log('wdsada',multiinputlength);
//j=1;
	let currentMenu = currentSection.toLowerCase();

	
	var htmlmulti ='';

	
	
	var getConfigTypeBasedSchema;
	
	
	
	
	var attributeCurrent = btnAttribute.split('-');


			let addmoresection = getSchema.data[currentTab];

			let getSavedConfiggroupwise = getSavedConfig.data[currentTab];
			let getSavedConfigGroup = JSON.parse(getSavedConfiggroupwise);
			

			attributeCurrent.forEach(attri=>{
				
				addmoresection = addmoresection['items'][attri]
				getSavedConfigGroup =  getSavedConfigGroup[attri]
				//currentMultiinput = attri

				
			});



			if(!multiinputlength[btnAttribute]){
				 multiinputlength[btnAttribute] = getSavedConfigGroup.length;
				
			}else{
				multiinputlength[btnAttribute] =  multiinputlength[btnAttribute];
			}
			
			// if(j== undefined){

			// 	if( multiinputlength[btnAttribute] == 0){
			// 	 addVa =1;
			// 	} else {
			// 		 addVa = multiinputlength[btnAttribute];
			// 	}
			// } else {

				
			// 	 addVa = j;

				
			// }
			//console.log( multiinputlength[btnAttribute]);return false;
			
		
			htmlmulti += `<div class="multidivclass_${currentSection}" id="divattr_${btnAttribute}-${multiinputlength[btnAttribute]}"><span class="spanincdec" id="spanincdec_${btnAttribute}-${multiinputlength[btnAttribute]}">${multiinputlength[btnAttribute]}</span>`;
		
			$.each(addmoresection.type_items , function(a,b){

					console.log(a,b)
					$.each(b , function(c,d){

						
							htmlmulti=appendMultiInputFields(d,getcategoriesAll,btnAttribute,htmlmulti,c,multiinputlength[btnAttribute])
							keyArray.push(c);
					});

				
			});

			htmlmulti+=addRemoveBtn(btnAttribute,getcategoriesAll,btnAttribute,htmlmulti,multiinputlength[btnAttribute],currentSection,currentTab)
			htmlmulti += `</div>`;
		
			multiinputlength[btnAttribute]++;



			
		$(".multiinput_div5_"+currentSection).append(htmlmulti);
		
		//$(".multiinput_div2").append(htmlmulti);

		if(j !== undefined){
			 j++;
		}
      


}




$(document).on('click','.removeBtn',function(){

	var curSec = $(this).attr('data-activesection');

	var curSecActive = $(this).attr('data-activetab');


	var parent = document.getElementById("page_section_"+curSecActive+curSec);
   	var nodesSameClass = parent.getElementsByClassName("removeBtn");
  // 	console.log(nodesSameClass.length);
 
	
	//console.log("page_section_"+curSecActive+curSecActive);return false;
	
	var le = $(".multiinput_div5_"+curSec+" > div").length;

	

		var rmvid = $(this).attr('id');


		if(nodesSameClass.length <= 1){
			$(".spancantremove"+rmvid).show();
			return false;
		} else {


		    j=1;

			if(btnAttribute == undefined){
				btnAttribute = $(this).attr('data-div5');

			}

			
			var totalattr = $(this).attr('data-total');

			if(!multiinputlength[btnAttribute]){
				multiinputlength[btnAttribute] = parseInt(totalattr);
			}

			if(totalattr != undefined){
				j=totalattr;
			}
			


			$("#"+rmvid).remove(); 

			
			let  multiDiv = rmvid.split('-');

			let multiInputType = multiDiv[multiDiv.length - 2];

			let currentIndex = parseInt(multiDiv[multiDiv.length - 1]);
			let currentIndexKey = "";
			

			var splitidrm = btnAttribute.split('-');


			var blkstrrm = [];
			$.each(splitidrm, function(idx2,val2) {                    
			  var str = val2;
			  blkstrrm.push(str);
			});

			const nameAttrrm = blkstrrm.join(":");
			
			

			var contentToRemovediv = document.querySelectorAll("#divattr_"+rmvid);
			$(contentToRemovediv).remove(); 


				for(let i = currentIndex+1;i<multiinputlength[btnAttribute];i++){

				const divid = btnAttribute+"-"+i;
				const newdivid=  btnAttribute+"-"+(i-1);



				// keyArray.forEach(element=>{

					

				// 	const tempId = btnAttribute+"-"+i+"-"+element;
				// 	const newId = btnAttribute+"-"+(i-1)+"-"+element;
				// 	const tempName = nameAttrrm+":"+element+"/"+(i-1);

				// 	$("#"+tempId).attr('name',tempName);
				// 	$("#"+tempId).attr('id',newId);
				// 	$("#hex_"+tempId).attr('id','hex_'+newId);
				// 	$("#hex_"+tempId).attr('name',tempId);
				// 	$("#hexspan_"+tempId).attr('id','hexspan_'+newId);
				
					
				// })
				
				
				
			
				
			}

	
				$( ".multiinput_div5_"+curSec ).find('.spanincdec').each(function(index) {

					

					var spanid = $(this).attr('class');

					//if(spanid !== undefined){

						var idspan = $(this).attr('id');
			
						$("#"+idspan).text(index+1);

				});

				$('.saveBtn').prop('disabled', false);

		}


		return false;


	if(curSec == 'Footer_Builder'){
			if(($(".multiinput_div1").children().length > 1 || $(".multiinput_div5_"+curSec+" > div").length > 1)){


		    j=1;

			if(btnAttribute == undefined){
				btnAttribute = $(this).attr('data-div5');

			}

			
			var totalattr = $(this).attr('data-total');

			if(!multiinputlength[btnAttribute]){
				multiinputlength[btnAttribute] = parseInt(totalattr);
			}

			if(totalattr != undefined){
				j=totalattr;
			}
			

			
			//console.log(totalattr);return false;
			


			$("#"+rmvid).remove(); 

			//console.log(rmvid);return false;
			let  multiDiv = rmvid.split('-');

			let multiInputType = multiDiv[multiDiv.length - 2];

			let currentIndex = parseInt(multiDiv[multiDiv.length - 1]);
			let currentIndexKey = "";
			

			var splitidrm = btnAttribute.split('-');


			var blkstrrm = [];
			$.each(splitidrm, function(idx2,val2) {                    
			  var str = val2;
			  blkstrrm.push(str);
			});

			const nameAttrrm = blkstrrm.join(":");
			
			

			var contentToRemovediv = document.querySelectorAll("#divattr_"+rmvid);
			$(contentToRemovediv).remove(); 


				for(let i = currentIndex+1;i<multiinputlength[btnAttribute];i++){

				const divid = btnAttribute+"-"+i;
				const newdivid=  btnAttribute+"-"+(i-1);



				keyArray.forEach(element=>{

					

					const tempId = btnAttribute+"-"+i+"-"+element;
					const newId = btnAttribute+"-"+(i-1)+"-"+element;
					const tempName = nameAttrrm+":"+element+"/"+(i-1);

					$("#"+tempId).attr('name',tempName);
					$("#"+tempId).attr('id',newId);
					
				})
				
				
				
			
				
			}

		//	multiinputlength[btnAttribute]--;

			//for(var l = 1; l<= multiinputlength[btnAttribute];l++){

				

				$( ".multiinput_div5_"+curSec ).find('.spanincdec').each(function(index) {

					

					var spanid = $(this).attr('class');

					//if(spanid !== undefined){

						var idspan = $(this).attr('id');
			
						$("#"+idspan).text(index+1);

						
				//	}

					
				});

			//}


		} else {

			
			$(".spancantremove"+rmvid).show();
			return false;
		}
	} else {


	
			if(($(".multiinput_div1").children().length > 1 || $(".multiinput_div5_"+curSec+" > div").length > 1)){


		    j=1;

			if(btnAttribute == undefined){
				btnAttribute = $(this).attr('data-div5');

			}

			
			var totalattr = $(this).attr('data-total');

			if(!multiinputlength[btnAttribute]){
				multiinputlength[btnAttribute] = parseInt(totalattr);
			}

			if(totalattr != undefined){
				j=totalattr;
			}
			

			
			//console.log(totalattr);return false;
			


			$("#"+rmvid).remove(); 

			//console.log(rmvid);return false;
			let  multiDiv = rmvid.split('-');

			let multiInputType = multiDiv[multiDiv.length - 2];

			let currentIndex = parseInt(multiDiv[multiDiv.length - 1]);
			let currentIndexKey = "";
			

			var splitidrm = btnAttribute.split('-');


			var blkstrrm = [];
			$.each(splitidrm, function(idx2,val2) {                    
			  var str = val2;
			  blkstrrm.push(str);
			});

			const nameAttrrm = blkstrrm.join(":");
			
			

			var contentToRemovediv = document.querySelectorAll("#divattr_"+rmvid);
			$(contentToRemovediv).remove(); 


				for(let i = currentIndex+1;i<multiinputlength[btnAttribute];i++){

				const divid = btnAttribute+"-"+i;
				const newdivid=  btnAttribute+"-"+(i-1);


			


				keyArray.forEach(element=>{

					

					const tempId = btnAttribute+"-"+i+"-"+element;
					const newId = btnAttribute+"-"+(i-1)+"-"+element;
					const tempName = nameAttrrm+":"+element+"/"+(i-1);

					$("#"+tempId).attr('name',tempName);
					$("#"+tempId).attr('id',newId);
					
				})
				
				
				
			
				
			}

		//	multiinputlength[btnAttribute]--;

			//for(var l = 1; l<= multiinputlength[btnAttribute];l++){

				$( ".multiinput_div5_"+curSec ).find('.spanincdec').each(function(index) {

					//console.log(index,"322332")

					var spanid = $(this).attr('class');

					//if(spanid !== undefined){

						var idspan = $(this).attr('id');
			
						$("#"+idspan).text(index+1);

						
				//	}

					
				});
		//	}

		} else {

		
			$(".spancantremove"+rmvid).show();
			return false;
		}

}

	


	console.log("length",multiinputlength[btnAttribute]);
	
});


function addRemoveBtn(d,getcategoriesAll,btnAttribute,htmlmulti,multiinputlength,currsection,activetab){
	var htmlmultiremove = ``;
	
	 htmlmultiremove += ` <div class="form-box">
	 							<label class="pure-material-textfield-outlined">
	 								<button type="button" data-activetab ="${activetab}" data-activesection="${currsection}" id="${btnAttribute}-${multiinputlength}" data-div5="${btnAttribute}" class=" btn btn-info btn-ad removeBtn removeLinkbtn">Remove</button>
								  <span style="color:red;display:none;" class="spancantremove${btnAttribute}-${multiinputlength}">You can't remove the last element </span></label>
				            </div>`

	return htmlmultiremove;
}

function appendMultiInputFields(value5,getCategories,btnAttribute,htmlmulti,k,multiinputlength){

	
	var helptext = '';
	var selectArray = '';

	var required;

	var idAttribute = btnAttribute+'-'+k;
	var idAttributeMulti = btnAttribute+'-'+multiinputlength+'-'+k;

	var splitid = idAttribute.split('-');


	var blkstr = [];
	$.each(splitid, function(idx2,val2) {                    
	  var str = val2;
	  blkstr.push(str);
	});

	const nameAttr = blkstr.join(":");


	

	if(value5.type == 'string' && value5.source == undefined){

		if(value5.helptext != undefined){
			helptext += `<div class="inputMsg">${value5.helptext}</div>`;
		}

		if(value5.required === true && value5.required != undefined){
			required = '*';
		} else{
			required = '';
		}

		

		htmlmulti += `<div class="form-box" ><h5 class="subName">${value5.label}
				<span style = "color:red">${required}</span>`;
					
				htmlmulti += helptext;	
          				

			htmlmulti +=	`</h5>
				
			  	<label class="pure-material-textfield-outlined">
			    <input placeholder=" " id="${idAttributeMulti}" name= "${nameAttr}/${multiinputlength}"  type="text"  value=''>
			    <span>${value5.label}</span>
			    
			   
			</label>
					

			</div>`;
			

	}

	if(value5.type == 'string' && value5.source != undefined){

		if(value5.required === true && value5.required != undefined){
			required = '*';
		} else{
			required = '';
		}
		
		var parsedgetCategories = (getCategories);

		

		var catStr = '';

		$.each(getCategories,function(a,b){

		
			catStr += `<option value="${b.cat_guid}">${b.name}</option>`;
		})

		if(value5.helptext != undefined){
			helptext += `<div class="inputMsg">${value5.helptext}</div>`;
		}

		
		htmlmulti += `<div class="form-box" ><h5 class="subName">${value5.label}
				<span style = "color:red">${required}</span>`;
					
				htmlmulti += helptext;	
          				

			htmlmulti +=	`</h5>
				
			  	<label class="pure-material-textfield-outlined"><div class="w-bg1"></div>
			    
			    <select id="${idAttributeMulti}" name="${nameAttr}/${multiinputlength}" class="${btnAttribute}-${k}">
					<optgroup label="Category">`
					htmlmulti += catStr;

	    			htmlmulti+= `</optgroup>

	    			</select>

								    <span>${value5.label}</span>
								    
								   
								</label>
										

                  			</div>`;

	
			

	}

	if(value5.type == 'hex_color'){

		if(value5.helptext != undefined){
			helptext += `<div class="inputMsg" >${value5.helptext}</div>`;
		}

		if(value5.required === true && value5.required != undefined){
			required = '*';
		} else{
			required = '';
		}

	   	htmlmulti +=		`<div class="singl-section" >
              			<div class="picker">
              				<h5 class="subName">${value5.label}`;		

              				htmlmulti += helptext;						                              				 
              				htmlmulti +=`</h5>
						  <input type="color" class="colorpicker" id="${idAttributeMulti}" name="${nameAttr}/${multiinputlength}" value="#000000">
						 
	 
					   <input type="text" id="hex_${idAttributeMulti}" class="hexcolor" name="${nameAttr}/${multiinputlength}" autocomplete="off" spellcheck="false" value="#000000">
						 <span class="hexspan_${idAttributeMulti}" style="color:red;display:none">Only Hex color code is accepted</span> 
						</div>
						

					</div>`



	  				
	    			

			

	}

	if(value5.type == 'url'){

		if(value5.helptext != undefined){
			helptext += `<div class="inputMsg" >${value5.helptext}</div>`;
		}

		if(value5.required === true && value5.required != undefined){
			required = '*';
		} else{
			required = '';
		}

		htmlmulti += `<div class="form-box" ><h5 class="subName">${value5.label}
				<span style = "color:red">${required}</span>`;
					
				htmlmulti += helptext;	
          				

			htmlmulti +=	`</h5>
				
			  	<label class="pure-material-textfield-outlined">
			    <input placeholder=" " class="urlclass" id="${idAttributeMulti}" name= "${nameAttr}/${multiinputlength}"  type="text"  value=''>
			     <span>${value5.label}</span><span id="urlhttps${idAttributeMulti}" style="color:red;display:none">Only https:// is allowed</span>
			    
			   
			</label>
					

			</div>`;


			

	}

	

	if(value5.type == 'html'){

		if(value5.required === true && value5.required != undefined){
			required = '*';
		} else{
			required = '';
		}



		htmlmulti += `<div class="form-box" ><h5 class="subName">${value5.label}
				<span style = "color:red">${required}</span>`;
					
				htmlmulti += helptext;	
          				

			htmlmulti +=	`</h5>
				
			  	<label class="pure-material-textfield-outlined">
			   
			    <textarea class="form-control" id="${idAttributeMulti}" name="${nameAttr}/${multiinputlength}" rows="3"></textarea>
			    <span>${value5.label}</span>
			    
			   
			</label>
					

			</div>`;


			

	}

	if(value5.type == 'boolean'){
		if(value5.required === true && value5.required != undefined){
			required = '*';
		} else{
			required = '';
		}


		htmlmulti += `<div class="singl-section"><h5 class="subName">${value5.label}
				<span style = "color:red">${required}</span>`;
					
				htmlmulti += helptext;	
          				

			htmlmulti +=	`</h5>
				
			  	<label class="switch-tog">
			    <input placeholder=" " id="${idAttributeMulti}" name= "${nameAttr}/${multiinputlength}"  type="checkbox"  value='false' onclick="getEvent(this)">
			    
			    <span class="slider-tog round"></span>
			    
			   
			</label>
					

			</div>`;

			

	}

	if(value5.type == 'enum'){

		if(value5.required === true && value5.required != undefined){
			required = '*';
		} else{
			required = '';
		}

		$.each(value5.type_items,function(a,b){
			selectArray += `<option value="${b.value}">${b.label}</option>`
		});



		htmlmulti += `<div class="singl-section" ><h5 class="subName">${value5.label}
				<span style = "color:red">${required}</span>`;
					
				htmlmulti += helptext;	
          				

			htmlmulti +=	`</h5>
				
			  	<label class="pure-material-textfield-outlined">
			    <select id="${idAttributeMulti}" name="${nameAttr}/${multiinputlength}" >
			    <optgroup label="${value5.label}">`;
			    
				htmlmulti += selectArray;

		    htmlmulti +=	`</optgroup>

		    	</select>
			    <span>${value5.label}</span>
			    
			   
			</label>
					

			</div>`;
;

			
		selectArray = ''	
	}

	if(value5.type == 'array'){

		var parsedgetCategories = (getCategories);

		if(value5.required === true && value5.required != undefined){
			required = '*';
		} else{
			required = '';
		}
		

		var catStr = '';

		$.each(parsedgetCategories.data,function(a,b){

		
			catStr += `<option value="${b.cat_guid}">${b.name}</option>`;
		})

		if(value5.helptext != undefined){
			helptext += `<div class="inputMsg">${value5.helptext}</div>`;
		}



		htmlmulti += `<div class="singl-section" ><h5 class="subName">${value5.label}
				<span style = "color:red">${required}</span>`;
					
				htmlmulti += helptext;	
          				

			htmlmulti +=	`</h5>
				
			  	<label class="pure-material-textfield-outlined">
			  	<div class="w-bg1"></div>
			    
			    <select  id="${idAttributeMulti}" name="${nameAttr}#multidropdown" required="" multiple="">
			    <optgroup label="Category">`
					htmlmulti += catStr;

	    			htmlmulti+= `</optgroup>

	    			</select>
			    <span>${value5.label}</span>
			    
			   
			</label>
					

			</div>`;

		
			

	}


	

	return htmlmulti;

}


var btnAttribute;

$(".recentAddMore").click(function(){

	//console.log(j);
	
    btnAttribute = $(this).attr('data-div5');
	var activetab = $(this).attr('data-activetab');
	var currentSelectedTab = btnAttribute.split("-");
	var getSchema = JSON.parse($(".getschema").val());
	var getcategoriesAll = JSON.parse($(".getcategoriesAll").val());
	var getSavedConfig = JSON.parse($(".getSavedConfig").val());
	var curSection = $(this).attr('data-activesection');




	
	addMultiinputSection(activetab,curSection,getcategoriesAll,getSchema,btnAttribute,getSavedConfig);
	
	$( ".multiinput_div5_"+curSection ).find('.spanincdec').each(function(index) {

					//console.log(index,"322332")

					var spanid = $(this).attr('class');

					//if(spanid !== undefined){

						var idspan = $(this).attr('id');
			
						$("#"+idspan).text(index+1);

						
				//	}

					
				});
	$('.colorpicker').on('input', function() {



				  		var id = this.id;
					
				  		//console.log(this.value);return false;
						$('#hex_'+id).val(this.value);
					});
					
					$('.hexcolor').on('input', function() {
						var idcolor = this.id;
						var splitvalue = idcolor.split('hex_')
					  $('#'+splitvalue[1]).val(this.value);

					  var flag =  isValidColor(this.value);

							if(flag == false){
								$(".hexspan_"+splitvalue[1]).show();
							}
					});


					$('.urlclass').on('input', function() {

						  var urlval = this.value;

						  

					  		var id = this.id;

					  	    var valid_url = isUrl(urlval);

					  		if(urlval == ""){
					  			 $('.saveBtn').prop('disabled', false);
					  		} else {

						  		if(valid_url == false){
						  			$('#urlhttps'+id).show();
						  			 $('.saveBtn').prop('disabled', true);
						  		} else {
						  			$('#urlhttps'+id).hide();
						  			 $('.saveBtn').prop('disabled', false);
						  		}
						  	}
					  		

						});
});

 var  flagvalid = 0;

window.leavepageflag = false;

$(".saveBtn").click(function(){

	var btnattr = $(this).attr('data-btn');

	var divid = $("#tabcontent_id_"+btnattr);

	var attr = $(".contentMenu_"+btnattr).attr('data-div');

	var nonce = $("#sortd-hidden-nonce-"+btnattr).val();

	var context;

	$(".contentMenu_"+btnattr+" > div").each(function(){
    	 context = $(this).attr('id');
   
	});

	window.leavepageflag = true;
	
	

 var items = {};


$(".contentMenu_"+btnattr+" :input[type=url]").each(function(e){	
    var name = this.name;
    var attridurl = $(this).attr('data-param-url');
    

//var escapeSelector = $.escapeSelector(name);

if(name != '' && attridurl == undefined){

	 items[name] = {'url':true};

} else if(name != '' && attridurl != undefined){
	 items[name] = {'required':true,'url':true};


} 
// else {
// 	 items[name] = {'url':true};
// }

$(".ldGf").hide();

});

 var items1 = {};

$(".contentMenu_"+btnattr+" :input[type=text]").each(function(e){	
 var name1 = this.name;
var attrid = $(this).attr('data-param');

if(name1 != '' && attrid != undefined){

	items1[name1] = 'required';
}

$(".ldGf").hide();

});


 var items2 = {};

$(".contentMenu_"+btnattr+" :input[type=number]").each(function(e){	
 var name2 = this.name
 var attridint = $(this).attr('data-intattr');




if(name2 != '' && attridint != ""){

	items2[name2] = 'required';
}

$(".ldGf").hide();
});

var length1 = Object.keys(items).length;
var length2 = Object.keys(items1).length;
var length3 = Object.keys(items2).length;



if(length1 > 0 && length2 == 0 && length3 == 0){

	

	var n = items ;

} else if(length1 > 0 && length2 > 0 && length3 == 0){



	var n = $.extend( items ,items1 ) ;



} else if(length1 > 0 && length2 > 0 && length3 > 0) {

	

	var n = $.extend( items ,items1,  items2 );  

	

} else if(length1 == 0 && length2 > 0 && length3 == 0){

	

	var n = ( items1);

} else if(length1 == 0 && length2 > 0 && length3 > 0) {

	

	var n = $.extend(  items1,items2);

} else if(length1 == 0 && length2 == 0 && length3 > 0){


	var n = (items2);

} else if(length1 > 0 && length2 == 0 && length3 > 0) {



	var n = $.extend(  items, items2);

} else if(length1 == 0 && length2 == 0 && length3 == 0){

	

	var n = []

} 





var jsonString = JSON.stringify(n);

$("#form"+btnattr).find('#categories').each(function(){
    if(!$(this).prop('required')){
         $("#catspan").show();
    } else {
        $("#catspan").hide();
    }
});

	$("#form"+btnattr).find('.components-special_widgets-category_id').each(function(){
	    if($(this).val() == 'Select Category'){

	    	 $("#selectalert"+this.id).show();
	    	

	    	  flagvalid = 1;
	    	 
	    	  return false;

	    } else {
	        $("#selectalert"+this.id).hide();

	         flagvalid = 0;
	       
	        return false;
	    }

	   
	});



 $("#form"+btnattr).validate({
  	     rules : JSON.parse(jsonString),
  	     highlight: function (element) {
  	     
  	    	$( element ).next( ".hidevallabel" ).css( "display", "none" );
   			 },

  	     submitHandler: function (form) {

  	     	//form.preventDefault();

  	     	if(flagvalid == 0){
  	     		$(".ldGf").show();
  	     	}

  	     	//

  	     	var siteurl = $("#site_url").val();
  	     	
  	     	var currentGroupName = "";
  	     	var currentGroupName1 = "";
  	     	var currentGroupName2= "";
  	     	var currentGroupName3 = "";
  	     	var values = {};
  	     	var items = {};
  	     	var elementValue = {}
  	     	var elementValue1 = {}

  	     	var arrayValue = [];
  	     	var arrayValue1 = [];
  	     	var arrayValue2 = [];
  	     	var elementValue2={};

  	     	var usedName1 = [];

  	     	var selectObj = [];

  	     	
  	     	var j = 0;


  	     	var newSerializeArray = [];
			var serializedArray = $('#form'+btnattr).serializeArray();




			serializedArray = serializedArray.concat(
            $('#form'+btnattr+' input[type=checkbox]:not(:checked)').map(
                    function() {
                        return {"name": this.name, "value": false}
                    }).get()
    		);

    		$.each(serializedArray, function(a,b){
    				if(b.value == 'true'){
    					b.value = true;
    				}

    				if(b.value == '[]'){
    					b.value = [];
    				}
    				newSerializeArray.push(b);


    			

    		});


    	
			$.each(newSerializeArray, function(i, field) {

		
			
					if(field.value !== "" || field.value !== null || field.value !== undefined){
							
						var name = field.name
						var nameArray = name.split(":");

						var split = name.split("/");

						if(currentGroupName !== nameArray[0]){

							if(values[nameArray[0]]){
								elementValue = values[nameArray[0]]

								if(!elementValue[nameArray[1]]){
									arrayValue1 = [];
								} 
							} else {
									elementValue = {};
									elementValue1 = {};
									arrayValue1 = [];
							}

					
					
						//	arrayValue1 = [];



				  	     	
							currentGroupName = nameArray[0];

							

						}

					

						if( currentGroupName1 !== nameArray[1]){


							if(values[nameArray[0]] && values[nameArray[0]][nameArray[1]]){
								
								elementValue1 = values[nameArray[0]][nameArray[1]];

								
							} else {
								elementValue1 = {};
								elementValue2 = {};
							
							}

					//		if(values[nameArray[0]] && values[nameArray[1]].length )

				  	     	
							currentGroupName1 = nameArray[1];

						//	console.log(currentGroupName , nameArray[1] , values[nameArray[0]])


						}

						if(currentGroupName2 !== nameArray[2]){


							if(values[nameArray[0]] && values[nameArray[0]][nameArray[1]] && values[nameArray[0]][nameArray[1]][nameArray[2]]){
								
								elementValue2 = values[nameArray[0]][nameArray[1]][nameArray[2]];

								//console.log(nameArray[1],nameArray[0])
							} else {
								elementValue2 = {};
								//elementValue3 = {};
							}

							//elementValue2 = {};

				  	     	
							currentGroupName2 = nameArray[2];
						}

						


						if(nameArray.length == 1){

						let multiInputArray = nameArray[0].split("#");
							

							if(multiInputArray.length > 1){

								if(!values[multiInputArray[0]]){

									values[multiInputArray[0]] = [];

								}
									
								values[multiInputArray[0]] = [
								
										...values[multiInputArray[0]],

										field.value

									]

							} else {
									values[nameArray[0]] = field.value;

							}
						//	

					
						
						} 

						if(nameArray.length == 2){

							if(split.length > 1){




                                
                                let fieldName = nameArray[1].split('/')

                                
								
                                if(arrayValue[parseInt(split[1])]){
                                    arrayValue[parseInt(split[1])]={
                                        ...arrayValue[parseInt(split[1])],
                                        [fieldName[0]]: field.value
                                    }

                                    

                                } else {

                                    arrayValue[parseInt(split[1])]={
                                        [fieldName[0]] : field.value
                                    }

                                    
                                }




                                elementValue = {

	                                ...elementValue,
	                                [nameArray[1]]:[...arrayValue]
                           		 }

                           //console.log(elementValue)

                            } else {

                            		elementValue = {

											...elementValue,
											[nameArray[1]]:field.value
										}


										


                            }

			

							
										values[nameArray[0]] = {
											...values[nameArray[0]],
											...elementValue

										};
						
						


						} 

						if(nameArray.length == 3){

							
							let multiInputArray = nameArray[2].split("#");
							

							if(multiInputArray.length > 1){

								if(!values[nameArray[0]][nameArray[1]][multiInputArray[0]]){

									  	values[nameArray[0]][nameArray[1]][multiInputArray[0]] = [];

									  	//console.log(field.value);

								}

								//console.log(field.value);
								let currValues = [...values[nameArray[0]][nameArray[1]][multiInputArray[0]]];

								

								if(field.value){
									currValues.push(field.value);
								}

								
								 elementValue1 = {

	                                    ...elementValue1,
	                                    [multiInputArray[0]]: [
	                                    	...currValues
	                                    ]
                                	}

                                	 elementValue = {

	                                	...elementValue,
	                                	[nameArray[1]]:{...elementValue1}

	                               	 }
									
								

							} else {
									

						

							
							if(split.length > 1){

                                
                                let fieldName = nameArray[2].split('/')

                               

                                if(arrayValue1[parseInt(split[1])]){

                                

                                    arrayValue1[parseInt(split[1])]={
                                        ...arrayValue1[parseInt(split[1])],
                                        [fieldName[0]]: field.value
                                    }

                                  

                                } else {

                                    arrayValue1[parseInt(split[1])]={
                                        [fieldName[0]] : field.value
                                    }

                                 
                                }




                                elementValue = {

	                                ...elementValue,
	                                [nameArray[1]]:[...arrayValue1]
                           		 }

                         

                            } else {

                            		
                            		

                                    elementValue1 = {

	                                    ...elementValue1,
	                                    [nameArray[2]]: field.value
                                	}

                                	    elementValue = {

	                                	...elementValue,
	                                	[nameArray[1]]:{...elementValue1}

	                               	 }

	                               		
	                               	

                            }



                          }  

                        

                            	values[nameArray[0]] = {
								...values[nameArray[0]],
								...elementValue

							}

           			
						}


						if(nameArray.length == 4){


							if(split.length > 1){

//parseInt(split[1])
								
								let fieldName = nameArray[3].split('/')

								console.log(nameArray[3]);


								if(arrayValue2[parseInt(split[1])]){
									arrayValue2[parseInt(split[1])]={
										...arrayValue2[parseInt(split[1])],
										[fieldName[0]]: field.value
									}

								} else {

									arrayValue2[parseInt(split[1])]={
										[fieldName[0]] : field.value
									}


								}

								console.log(arrayValue2)


								elementValue1 = {

								...elementValue1,
								[nameArray[2]]:[...arrayValue2]
								}

								
							

							} else {

								elementValue2 = {

								...elementValue2,
								[nameArray[3]]:field.value
								}

									elementValue1 = {

								...elementValue1,
								[nameArray[2]]:{...elementValue2}
								}



							}

							

						
							

							
							elementValue = {

								...elementValue,
								[nameArray[1]]:{...elementValue1}
							}

							

							values[nameArray[0]] = {
								...values[nameArray[0]],
								...elementValue

							}

							
							
						}


				}

		
			});

			


			items['groupName'] = attr;
			items['formData'] = values;

			//var clean = pruneEmpty(items);
           
            var jsonStringdata = JSON.stringify(items);

			//console.log(values);return false;

			if(flagvalid == 0){

  			$.ajax({
  				url: sortd_ajax_obj_config.ajax_url,
		  		
		  		data : {'action':'configSchemaSaveData','items':btoa(unescape(encodeURIComponent(jsonStringdata))),'sortd_nonce' : sortd_ajax_obj_config.nonce},

		  		type : 'post', 
		  		success: function(result){

		  			

  			try
					{
						
							var remove_after= result.lastIndexOf('}');
								if(remove_after != -1){
								  var dataresult =  result.substring(0,remove_after +1);
								  var jsonRes = JSON.parse(result);
								}else {
								  console.log("This is not valid JSON format")
								} 

		  			if(jsonRes.status == false && (jsonRes.error.errorCode == 1004 || jsonRes.error.errorCode == 1005 || jsonRes.error.errorCode == 403)){
		  			//	console.log("result");return false;
		  			location.href = location.href + "&section=sortd_config&parameter="+btnattr;
		  			//location.reload();
		  				//window.location.href = siteurl+"/wp-admin/admin.php?page=sortd-manage-settings&section=sortd_credential_settings";

		  			} else if(jsonRes.status == false && (jsonRes.error.errorCode == 408)){
		  				
		  				location.reload();
		  			
		  			} else {

		  				if(jsonRes.updatedConfig.status === true || jsonRes.updatedConfig.status === "true"){
		  					window.leavepageflag = true;

		  					$("#view_changes_inqr").show();
		  					$(".config-main-div").hide();
		  					//location.href = location.href + "&section=sortd_config&parameter="+btnattr;
		  					$(".notice-success").delay(10000).fadeOut(5000);

		  					$(".notice-dismiss").click(function(){

	
								$(".configPopup").hide();
							});

			  			} else {

			  				$('.header').prepend(`<div class="notice notice-error is-dismissible"><p>`+jsonRes.error.message+`</p><span class="closeicon" aria-hidden="true">&times;</span></div>`);
	 						$(".notice-error").delay(2000).fadeOut(500);

			  			} 

		  			} 

		  		$(".scanpopup-close").click(function(){
		  			//$("#view_changes_inqr").hide();

		  			location.href = location.href + "&section=sortd_config&parameter="+btnattr;
		  			//$(".config-main-div").show();
		  		});	

		  		}catch(e){
		  			console.log(e);return false;
		  		}		
		  			
		    		
	
		  		}
			});

  	}

        return false;
    }

  
       
    });

  $.validator.addClassRules({
        requiredClass: {
            required: true,
           
        }
    });


   $.validator.addClassRules({
        imageUploadClass: {
            required: true,
           
        }
    });


  
});



function isUrl(s) {
    var regexp = /(ftp|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
    return regexp.test(s);
}




})( jQuery );