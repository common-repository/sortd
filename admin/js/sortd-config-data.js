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
        
        const config = {
            
            validateAndSaveConfig : function(){

                let hidden_nonce = $(this).attr('data-nonce');
                
                let flagvalid = 0;
                
                let btnattr = $(this).attr('data-btn');
                let wp_domain = $(this).attr('data-wp_domain');
                let project_slug = $(this).attr('data-project_slug');
                let current_user = $(this).attr('data-current_user');

                if (typeof gtag === 'function') {
                    gtag('event', 'sortd_action', {
                        'sortd_page_title': 'sortd_config',
                        'sortd_feature': 'Save Config',
                        'sortd_domain': wp_domain,
                        'sortd_project_slug': project_slug,
                        'sortd_user': current_user
                    });
                }

                let divid = $("#tabcontent_id_"+btnattr);

                let attr = $(".contentMenu_"+btnattr).attr('data-div');

                let nonce = $("#sortd-hidden-nonce-"+btnattr).val();

                let context;

                $(".contentMenu_"+btnattr+" > div").each(function(){
                    context = $(this).attr('id');
                });

                window.leavepageflag = true;
                
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
	
                var urlInputs = {};


                $(".contentMenu_"+btnattr+" :input[type=url]").each(function(e){	
                     let name = this.name;
                     let attridurl = $(this).attr('data-param-url');
                     if(name != '' && attridurl == undefined){
                         urlInputs[name] = {'url':true};
                     } else if(name != '' && attridurl != undefined){
                         urlInputs[name] = {'required':true,'url':true};
                     } 
                     $(".ldGf").hide();
                });

                

                var textInputs = {};
                var $i=0;

                   
                

                $(".contentMenu_"+btnattr+" :input[type=text]").each(function(e){	
                    // console.log(textInputs);
                    $i++;
                    let text_name = this.name;
                    console.log(text_name + $i);
                    
                    let attrid = $(this).attr('data-param');
                    console.log(attrid);
                    
                    if(text_name != '' && attrid != undefined){
                        textInputs[text_name] = {'required':true};
                    }
                    

                    
                    $(".ldGf").hide();
                });

                   
                  

                var numberInputs = {};

                $(".contentMenu_"+btnattr+" :input[type=number]").each(function(e){	
                     let number_name = this.name
                     let attridint = $(this).attr('data-intattr');
                     if(number_name != '' && attridint != ""){
                         numberInputs[number_name] = {'required':true};
                     }

                    $(".ldGf").hide();
                });

                let urlInputs_count = Object.keys(urlInputs).length;
                let textInputs_count = Object.keys(textInputs).length;
                let numberInputs_count = Object.keys(numberInputs).length;


                let allInputs = [];
                if(urlInputs_count > 0 && textInputs_count == 0 && numberInputs_count == 0){
                     allInputs = urlInputs ;
                } else if(urlInputs_count > 0 && textInputs_count > 0 && numberInputs_count == 0){
                     allInputs = $.extend( urlInputs ,textInputs ) ;
                } else if(urlInputs_count > 0 && textInputs_count > 0 && numberInputs_count > 0) {
                     allInputs = $.extend( urlInputs ,textInputs,  numberInputs );  
                } else if(urlInputs_count == 0 && textInputs_count > 0 && numberInputs_count == 0){
                     allInputs = ( textInputs);
                } else if(urlInputs_count == 0 && textInputs_count > 0 && numberInputs_count > 0) {
                     allInputs = $.extend(  textInputs,numberInputs);
                } else if(urlInputs_count == 0 && textInputs_count == 0 && numberInputs_count > 0){
                     allInputs = (numberInputs);
                } else if(urlInputs_count > 0 && textInputs_count == 0 && numberInputs_count > 0) {
                     allInputs = $.extend(  urlInputs, numberInputs);
                } 

               let allInputsValidationString = JSON.stringify(allInputs);
               
               $("#form"+btnattr).validate({
                   
                    rules : JSON.parse(allInputsValidationString),
                    
                    highlight: function (element) {
                       
                       $( element ).next( ".hidevallabel" ).css( "display", "none" );
                    },

                    submitHandler: function (form) {
                        $(".ldGf").show();
                        let newSerializeArray = [];
			            let serializedArray = $('#form'+btnattr).serializeArray();
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
                        
                      //  console.log(newSerializeArray);return false;
                        
                        let configData = JSON.stringify(newSerializeArray);
                        
                        if(flagvalid == 0){
                            
                            $.ajax({
                                    url: sortd_ajax_obj_config.ajax_url,

                                    data : {'action':'sortd_ajax_save_config','items':btoa(unescape(encodeURIComponent(configData))),'sortd_nonce' : sortd_ajax_obj_config.nonce,'group_name':btnattr},

                                    type : 'post', 
                                    success: function(result){
                                      
                                            try
                                                {
                                                    
                                                    let remove_after= result.lastIndexOf('}');
                                                    if(remove_after != -1){
                                                        let dataresult =  result.substring(0,remove_after +1);
                                                        let jsonRes = JSON.parse(result);

                                                        console.log("HUIHUIHUIHUI: ",jsonRes)
                                                        

                                                        if(jsonRes.status == false && (jsonRes.error.errorCode == 1004 || jsonRes.error.errorCode == 1005)){
                                                                location.href = siteurl+"/wp-admin/admin.php?page=sortd-manage-settings&section=sortd_credential_settings";

                                                        } else if(jsonRes.status == false && (jsonRes.error.errorCode == 408)){

                                                                location.reload();

                                                        } else if((jsonRes.status === false) && (jsonRes.error) && (jsonRes.error.errorCode == 503)) {
                                                            $(".ldGf").hide();
                                                            $('.config_not_saved').show();
                                                            $('.config_not_saved').html(jsonRes.error.message);
                                                            setTimeout(function() {
                                                                $('.config_not_saved').fadeOut(500);
                                                            }, 2000);
                                                        }   else {

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
                                                            let nonceValue = $("#nonce_input").val();
                                                            location.href = location.href + "&section=sortd_config&parameter="+btnattr + "&_wpnonce=" + nonceValue;
                                                           // location.href = location.href + "&section=sortd_config&parameter="+btnattr + "&_wpnonce=" + hidden_nonce;
                                                        });	
                                                    }else {
                                                        console.log("This is not valid JSON format")
                                                    }     

                                                }catch(e){
                                                        console.log(e);
                                                        return false;
                                                }		

                                    }
                                });
                        }
                        
                        return false;
                    }
                });
                
                
               
               
            },
            
            insertParam : function(key, value){
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

                    const refresh = location.protocol + "//" + location.host + location.pathname + '?' + kvp.join('&');  
                         window.history.pushState({ path: refresh }, '', refresh);

                    //this will reload the page, it's likely better to store this until finished
                   // document.location.search = kvp.join('&');
                }
            },

            changeConfigTab : function(){
                let currentConfigGroup = $(this).attr('id');
                console.log("HI");
                const searchParams = new URLSearchParams(window.location.search);
                

                let hidden_nonce = searchParams.get('_wpnonce');
                //let currURL = window.location.href;
                //var url = (currURL.split(window.location.host)[1]).split("&")[0]+'&'+(currURL.split(window.location.host)[1]).split("&")[1];
                let url = "/wp-admin/admin.php?page=sortd-manage-settings&section=sortd_config";
                
                let siteurl = $("#site_url").val();
                
                $.ajax({
                    url: sortd_ajax_obj_config.ajax_url,
                    data : {'action':'sortd_ajax_display_group_config','group_name':currentConfigGroup,'sortd_nonce' : sortd_ajax_obj_config.nonce},
                    type : 'post', 
                    success: function(result){
                        if(result == 'false'){
                           // let nonceValue = $("#nonce_input").val();

                            location.href = siteurl+"/wp-admin/admin.php?page=sortd-manage-settings&section=sortd_config&parameter="+currentConfigGroup+"&_wpnonce="+hidden_nonce;
                        }else{
                            console.log("HERE");
                            $('#configgroup_tabdata').html('');
                            $('#configgroup_tabdata').html(result);
                            config.loadDefaults();
                            var req_input_fields=[];
                            $('input[type="text"]').each(function() {
                                
                                var textFieldId = $(this).attr('id');
                                var dataParam = $(this).data('param');
                                if (dataParam === 'required') {
                                  // Execute your function for each text field with data-param="required"
                                  
                                  console.log(textFieldId);
                                  console.log("hehehe");
                                  req_input_fields.push(textFieldId);
                                  
                                }
                              });
                              
                              console.log(req_input_fields);
                  
                              req_input_fields.forEach((inputFieldSelector) => {
                                  
                                  console.log("jajajaja");
                                  var inputField = $('#'+ inputFieldSelector);
                                  // console.log(inputField);
                                  // var errorMessage = inputField.next('.error-message');
                                  var dynamicId = '#'+ inputFieldSelector + 'error_msg';
                                  var errorMessage = $(dynamicId);
                                  // console.log(errorMessage);
                                  inputField.on('keypress paste', function(event) {
                                    if (event.type === 'keypress') {
                                      // Check if the pressed key is a space (key code 32)
                                      if (event.which === 32 && event.target.selectionStart === 0) {
                                        // Prevent the default behavior (i.e., entering the space)
                                        event.preventDefault();
                                        
                                        // Show the error message
                                        errorMessage.html("First Character can't be a space");
                                      } else {
                                        // Clear the error message
                                        errorMessage.html("");
                                      }
                                    } else if (event.type === 'paste') {
                                      // Clear the error message on paste event
                                      console.log("text added");
                                      errorMessage.html("");
                                    }
                                  });
                                })

                            $( ".sortd_upload_file").unbind( "click" );
                            $( ".saveBtn").unbind( "click" );
                            $( ".recentAddMore").unbind( "click" );
                            $( ".removeBtn").unbind( "click" );


                            $(".sortd_upload_file").change(config.sortdUploadFile);
                            $(".saveBtn").click(config.validateAndSaveConfig);
                            $(".recentAddMore").click(config.addMultiinputSection);
                            $(".removeBtn").click(config.removeMultiInputSection);
                        }
                        
                    }
                });
                
                window.history.pushState({}, document.title, url);
                config.insertParam('parameter',currentConfigGroup);
                config.insertParam('_wpnonce',hidden_nonce);
                  //$(".infourl").removeClass();

                $(".infourl").attr('id','infourl'+currentConfigGroup);


            },

            loadDefaults : function(){
                console.log(" loadDefaults called ");
                window.leavepageflag =true;
                $(document).on('keypress',function(e) {
                    window.leavepageflag =false;
                });
             
                    let urlString  = location.href;
                    let url = new URL(urlString);
                    let currentConfigGroup = url.searchParams.get("parameter");
                    let activeNav = url.searchParams.get("navigate");

                    scrollTo(0,0);
                    let menuBar = $(".stickkey"+currentConfigGroup);
                    let menuCont = $(".contentMenu_"+currentConfigGroup);
                    
                    $(window).scroll(function() {    
                        let scroll = $(window).scrollTop();
                        if (scroll >= 250) {
                           menuBar.addClass("sticky-menu-left");
                           menuCont.addClass("cm-left")
                         } else {
                           menuBar.removeClass("sticky-menu-left");
                           menuCont.removeClass("cm-left")
                        }
                    });
                  
                    let first = $(".contentMenu_"+currentConfigGroup ).children(":first");

                    let attrId = first.attr('id');

                    let splitfirst = attrId.split(currentConfigGroup);

                    let pageContFirst = $(".nav_"+currentConfigGroup+splitfirst[1]);

                    (pageContFirst).addClass("active").siblings().removeClass('active');

                    $( ".contentMenu_"+currentConfigGroup ).find('div[class*="page-section-a"]').each(function( ) {

                        let classname = $(this).attr('class');

                        let navscrollid = $(this).attr('id');

                        let splitscroll = navscrollid.split('page_section_');

                        let pageBar = $("#"+navscrollid);//$("#page_section_"+currentConfigGroup+splitscroll[1]);

                        let pageCont = $(".nav_"+splitscroll[1]);

                        $(window).scroll(function() {    
                            let scrollPage = $(window).scrollTop();
                            let aTop = pageBar;
                            if(scrollPage>= aTop.offset().top-100){
                                (pageCont).addClass("active").siblings().removeClass('active');
                            } else {
                                (pageCont).removeClass("active");
                            }
                        });
                        
                        if(activeNav != ''){
                            setTimeout(function () { $('#navlink_'+activeNav).click(); }, 500);
                            $("#navlink_"+activeNav).addClass("active").siblings().removeClass('active');
                        }
                    });
              
                    $("#tabcontent_id_"+currentConfigGroup).show().siblings("div").hide();
                    $('.tablinks').removeClass('active');
                    $("#"+currentConfigGroup).addClass('active');
                    
                    $(".navigation__link").click(function(){
                        let navId = $(this).attr('data-nav-id');
                        $('html,body').delay(500).animate({
                            scrollTop: $("#page_section_"+navId).offset().top-100},
                        'slow');
                        return false;
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

                    $(".imageRemoveBtn").unbind('click'); // unbind
                    $(".imageRemoveBtn").click(config.removeImage); // bind again
                    $('.colorpicker').on('input',config.colorValidation);
                    $('.hexcolor').on('input',config.hexcolorValidation);
                    $('.urlclass').on('input',config.urlValidation);
                   
                    window.addEventListener("beforeunload", function (e) {
                        if (window.leavepageflag == false) {
    
                            var confirmationMessage = 'It looks like you have been editing something. '
                                + 'If you leave before saving, your changes will be lost.';
    
                            (e || window.event).returnValue = confirmationMessage; //Gecko + IE
                            return confirmationMessage; //Gecko + Webkit, Safari, Chrome etc.
    
                        }
                    });

                      

                $(".infourl").attr('id' ,'infourl'+currentConfigGroup);

                // $('#infourl'+currentConfigGroup).click(function(){

                //     config.loadInfoUrl(currentConfigGroup);

                // });

            },

            scrollOnNavigationClick : function(){

            },

            getMultiInputBooleanClickEvent : function(event){

            },

            categoryNameAutofill : function(){

            },

            removeMultiInputSection : function(){
                
                let removeMultiInputSectionId = $(this).attr('id');
                let splitRemoveMultiInputSectionId = removeMultiInputSectionId.split('remove_multiinput_section_');
                let removeSectionId = splitRemoveMultiInputSectionId[1];
                
                let getSectionNumber = removeSectionId.split('-');
                let sectionNumber = getSectionNumber[getSectionNumber.length-1];
                
                getSectionNumber.splice(getSectionNumber.length-1, 1);
                let multiInputSectionId = getSectionNumber.join('-');
                let multiInputSectionName = getSectionNumber.join(':');
                
                let currentTotalSections = parseInt($('#section-count-'+multiInputSectionId).val());
                let newTotalSections = currentTotalSections-1;
                
                if(currentTotalSections <= 1){
			$(".spancantremove"+removeSectionId).show();
			return false;
		}
                
                let isLastSection = parseInt(sectionNumber)+1;
                
                $('#'+removeSectionId).remove();
                $('#separator_'+removeSectionId).remove();
                $('#section-count-'+multiInputSectionId).val(newTotalSections);
                
                if(currentTotalSections > isLastSection){
                    let multiInputHtml = $('#multiinputdiv-'+multiInputSectionId).html();
                    for(let sections=isLastSection;sections<currentTotalSections;sections++){
                            //alert(sections);
                            let replaceSection = sections-1;
                            let idsReplaceWith = multiInputSectionId+"-"+replaceSection;
                            let namesReplaceWith = multiInputSectionName+":"+replaceSection;
                            
                            let idsReplace = multiInputSectionId+"-"+sections;
                            let namesReplace = multiInputSectionName+":"+sections;
                            
                            multiInputHtml = multiInputHtml.replaceAll(idsReplace,idsReplaceWith);
                            multiInputHtml = multiInputHtml.replaceAll(namesReplace,namesReplaceWith);
                            
                    }
                    
                   
                    $('#multiinputdiv-'+multiInputSectionId).html(multiInputHtml);
                    
                }else{
                    //last section removed
                }
                
                $( ".removeBtn").unbind( "click" );
                $(".removeBtn").click(config.removeMultiInputSection);
                
             
            },


            addMultiinputSection : function(){
                
                let addMoreMultiInputSectionId = $(this).attr('id');
                let splitAddMoreMultiInputSectionId = addMoreMultiInputSectionId.split('add_more_');
                let multiInputSectionId = splitAddMoreMultiInputSectionId[1];
                let currentTotalSections = $('#section-count-'+multiInputSectionId).val();
                
                let newSectionIndex = parseInt(currentTotalSections);
                
                let sectionData = {
                            section_id: newSectionIndex
                };
                          
                // Grab html from template
                let template = $('#template_'+multiInputSectionId).html();

                $('#multiinputdiv-'+multiInputSectionId).append(config.renderTemplate(template, sectionData));
                $('#section-count-'+multiInputSectionId).val(newSectionIndex+1);
                $( ".removeBtn").unbind( "click" );
                $(".removeBtn").click(config.removeMultiInputSection);

                $('.urlclass').on('input',config.urlValidation);
                $('.colorpicker').on('input',config.colorValidation);
                $('.hexcolor').on('input',config.hexcolorValidation);
            },

            isUrl : function(url) {
                let regexp = /(ftp|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
                return regexp.test(s);
            },
            
            renderTemplate : function(template, data) {
              let patt = /\{([^}]+)\}/g; // matches {key}
              return template.replace(patt, function(_, key) {
                return data[key];
              });
            },
            
            // sortdUploadFile : function(){
            //     let splitimg;
            //     let imagedata;

            //     let _URL = window.URL || window.webkitURL;
                                                                
            //     let id = $(this).attr('id');
            //     let width = $(this).attr('data-width');
            //     let height = $(this).attr('data-height');
            //     let fileInput = document.getElementById(id);

            //     let reader = new FileReader();
            //     // reader.readAsDataURL(fileInput.files[0]);
            //     // console.log(fileInput.files[0]);
            //     let file = fileInput.files[0];
            //     let image;

            //     if (fileInput.files.length > 0) {

            //             image = new Image();

            //             image.onload = function() {

            //                 if(width !=this.width || height !=this.height){
            //                     $(".spnerror"+id).show();
            //                 } else {


            //                     splitimg = reader.result.split(',')
            //                     imagedata = {
            //                        "imageData": '"'+splitimg[1]+'"', "filedName" : "icon"
            //                     };

            //                     // console.log(imagedata);

            //                     $.ajax({
            //                         url: sortd_ajax_obj_config.ajax_url,
            //                         data : {'action':'sortd_ajax_config_file_upload','data':imagedata,'sortd_nonce' : sortd_ajax_obj_config.nonce},
            //                         type : 'post', 
            //                         success: function(result){
            //                         //    console.log(result);return false;
            //                             let resImage = JSON.parse(result);
            //                             // console.log(resImage);
            //                             $(".spnerror"+id).hide();
            //                             $('#hidden_'+id).attr('value',resImage.data.imageUrl);
            //                             $('#dvPreview'+id).attr("src", resImage.data.imageUrl);
            //                             $("#remove"+id).show();
            //                         }
            //                     });

            //                 }

            //             };

            //             image.src = _URL.createObjectURL(file);

            //     }

            // },

            sortdUploadFile: function () {
                let id = $(this).attr('id');
                let width = $(this).attr('data-width');
                let height = $(this).attr('data-height');
                let fileInput = document.getElementById(id);
            
                if (fileInput.files.length > 0) {
                    let reader = new FileReader();
                    let file = fileInput.files[0];
            
                    reader.onload = function () {
                        let splitimg = reader.result.split(',');
                        let imagedata = {
                            "imageData": '"' + splitimg[1] + '"',
                            "filedName": "icon"
                        };
            
                        let image = new Image();
                        image.onload = function () {
                            if (width != this.width || height != this.height) {
                                $(".spnerror" + id).show();
                            } else {
                                $.ajax({
                                    url: sortd_ajax_obj_config.ajax_url,
                                    data: {
                                        'action': 'sortd_ajax_config_file_upload',
                                        'data': imagedata,
                                        'sortd_nonce': sortd_ajax_obj_config.nonce
                                    },
                                    type: 'post',
                                    success: function (result) {
                                        let resImage = JSON.parse(result);
                                        $(".spnerror" + id).hide();
                                        $('#hidden_' + id).attr('value', resImage.data.imageUrl);
                                        $('#dvPreview' + id).attr("src", resImage.data.imageUrl);
                                        $("#remove" + id).show();
                                    },
                                    error: function (xhr, status, error) {
                                        console.error('AJAX request failed:', error);
                                    }
                                });
                            }
                        };
            
                        image.src = URL.createObjectURL(file);
                    };
            
                    reader.readAsDataURL(file);
                }
            },
            
            removeImage:function(){
            
                let removeId = $(this).attr('id');

                let removeSplitId = removeId.split('remove');
                let id = removeSplitId[1];
                   $(".spnerror"+removeSplitId[1]).hide();
                    $("#"+removeSplitId[1]).val('');
                    $('#hidden_'+removeSplitId[1]).val('');
                    $('#dvPreview'+removeSplitId[1]).attr("src", "");

               
            },

            colorValidation : function(){
                var id = this.id;

                $('#hex_'+id).val(this.value);
        
                let colorValidFlag =  isValidColor($('#hex_'+id).val());
        
                if(colorValidFlag == true){
                    $('.saveBtn').prop('disabled', false);
                } else {
                    $('.saveBtn').prop('disabled', true);
                }
            },

            hexcolorValidation : function(){
                var idcolor = this.id;
                var splitvalue = idcolor.split('hex_')
              $('#'+splitvalue[1]).val(this.value);
        
              var flag =  isValidColor(this.value);
        
                if(flag == false){
                    $(".hexspan_"+splitvalue[1]).show();
                    $('.saveBtn').prop('disabled', true);
                } else if(flag == true) {
                    $('.saveBtn').prop('disabled', false);
                }
            },

            urlValidation : function(){
                var urlval = this.value;
                var id = this.id;
                var valid_url = isUrl(urlval);
              //  console.log(urlval.length);return false;
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

              if(urlval.length == 0){
                $('#urlhttps'+id).hide();
                }
            },

            loadInfoUrl : function(currentConfigGroup){

                 let info_url = "https://support.sortd.mobi/portal/en/kb/gni-adlabs/general";
                if(currentConfigGroup == 'general_settings'){
                     info_url = "https://support.sortd.mobi/portal/en/kb/gni-adlabs/general";
                } else if(currentConfigGroup == 'header'){
                    info_url = "https://support.sortd.mobi/portal/en/kb/gni-adlabs/general";
                } else if(currentConfigGroup == 'footer'){
                    info_url = "https://support.sortd.mobi/portal/en/kb/gni-adlabs/general";
                } else if(currentConfigGroup == 'top_menu'){
                     info_url = "https://support.sortd.mobi/portal/en/kb/gni-adlabs/general";
                } else if(currentConfigGroup == 'manifest'){
                     info_url = "https://support.sortd.mobi/portal/en/kb/gni-adlabs/general";
                } else if(currentConfigGroup == 'home'){
                     info_url = "https://support.sortd.mobi/portal/en/kb/gni-adlabs/general";
                } else if(currentConfigGroup == 'category'){
                     info_url = "https://support.sortd.mobi/portal/en/kb/gni-adlabs/general";
                } else if(currentConfigGroup == 'article'){
                     info_url = "https://support.sortd.mobi/portal/en/kb/gni-adlabs/general";
                } else if(currentConfigGroup == 'widgets'){
                     info_url = "https://support.sortd.mobi/portal/en/kb/gni-adlabs/general";
                } 

                //console.log(info_url);

                  
                        window.open(info_url);
                        return false;
               
            }
            
        }
                
        $(".tablinks").click(config.changeConfigTab);
	
        $(".sortd_upload_file").change(config.sortdUploadFile);
        
        console.log("Outside");

        $(window).load(function() {
            var req_input_fields=[];
            $('input[type="text"]').each(function() {
              var textFieldId = $(this).attr('id');
              var dataParam = $(this).data('param');
              if (dataParam === 'required') {
                // Execute your function for each text field with data-param="required"
                
                console.log(textFieldId);
                console.log("hehehe");
                req_input_fields.push(textFieldId);
                
              }
            });
            
            console.log(req_input_fields);

            req_input_fields.forEach((inputFieldSelector) => {
                
                console.log("jajajaja");
                var inputField = $('#'+ inputFieldSelector);
                // console.log(inputField);
                // var errorMessage = inputField.next('.error-message');
                var dynamicId = '#'+ inputFieldSelector + 'error_msg';
                var errorMessage = $(dynamicId);
                // console.log(errorMessage);
                inputField.on('keypress paste', function(event) {
                    if (event.type === 'keypress') {
                      // Check if the pressed key is a space (key code 32)
                      if (event.which === 32 && event.target.selectionStart === 0) {
                        // Prevent the default behavior (i.e., entering the space)
                        event.preventDefault();
                        
                        // Show the error message
                        errorMessage.html("First Character can't be a space");
                      } else {
                        // Clear the error message
                        errorMessage.html("");
                      }
                    } else if (event.type === 'paste') {
                      // Clear the error message on paste event
                      errorMessage.html("");
                    }
                  });
                     
            })
        });
        
        $(".saveBtn").click(config.validateAndSaveConfig);
        
        $(".recentAddMore").click(config.addMultiinputSection);
        
        $(".removeBtn").click(config.removeMultiInputSection);
                
        $( window ).load(config.loadDefaults);
        $('.infoIcn').attr('title','Adlabs Support');


        $('.infourl').click(function(){

            let configGroup = $(this).attr('id');

            let urlid = configGroup.split('infourl');

           // console.log("hello",urlid[1]);return false;

            config.loadInfoUrl(urlid[1]);

        });
       
        
        


        function isUrl(s) {
            var regexp = /(ftp|https):\/\/(\w+:{0,1}\w*@)?(\S+)(:[0-9]+)?(\/|\/([\w#!:.?+=&%@!\-\/]))?/
            return regexp.test(s);
        }

})( jQuery );
