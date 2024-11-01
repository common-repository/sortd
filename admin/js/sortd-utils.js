(function ($) {
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
        const utils = {

                allPaidFlag : false,
                verifyCredentials: function () {
                        let licenseDetails = $("#licensekey").val();
                        let siteUrl = $("#siteurl").val();
                        let sortdCreKey = $("#sortd_cre_key").val();

                        $(this).prop('disabled',true);

                        if (!licenseDetails) {
                                $("#validationspan").html('Enter Credentials');
                                $(this).prop('disabled',false);
                        }
                        
                        else {
                                try{
                                
                                        let licenseObject = JSON.parse(licenseDetails);
                                        let accessKey = licenseObject.access_key;
                                        let secretKey = licenseObject.secret_key;
                                        let projectName = licenseObject.project_name;
                                        let projectId = licenseObject.project_id;
                                        let host = licenseObject.host;
                                        let htmlappend = '';
                                        console.log(licenseDetails);
                                        $.ajax({
                                                url: sortd_ajax_obj_utils.ajax_url,
                                                data: {
                                                        'action': 'sortd_verify_credentials',
                                                        'access_key': accessKey,
                                                        'secret_key': secretKey,
                                                        'project_name': projectName,
                                                        'project_id': projectId,
                                                        'host': host,
                                                        'license_data': licenseDetails,
                                                        'sortd_nonce': sortd_ajax_obj_utils.nonce
                                                },
                                                type: 'post',
                                                success: function (result) {
                                                //   console.log(result);return false;
                                                        try {

                                                                let remove_after = result.lastIndexOf('}');
                                                                if (remove_after != -1) {
                                                                        let dataresult = result.substring(0, remove_after + 1);
                                                                        let res = JSON.parse(dataresult);
                                                                
                                                        
                                                                 // console.log(res);return false;

                                                                        if (res.status == true) {
                                                                              //  console.log("25nov verified");return false;                                                                           
                                                                                $(".already_verified").show();
                                                                                $(".already_verified_onload").hide();
                                                                        } else {
                                                                                if (res.verify.status === true) {
                                                                                        if (sortdCreKey == 0) {
                                                                                                $.ajax({
                                                                                                        url: sortd_ajax_obj_utils.ajax_url,
                                                                                                        //url: siteurl+'/wp-content/plugins/wp_sortd/includes/class-sortd-ajax-extended.php',
                                                                                                        data: {
                                                                                                                'action': 'sortd_get_contractdetailsafter_verify',
                                                                                                                'sortd_nonce': sortd_ajax_obj_utils.nonce
                                                                                                        },
                                                                                                        type: 'post',
                                                                                                        success: function (result) {

                                                                                                        //  console.log(result);return false;

                                                                                                                let resJson = JSON.parse(result);

                                                                                                                let gettimestart = utils.getDateFormat(resJson.data.plan_start_date);
                                                                                                                let gettimeend = utils.getDateFormat(resJson.data.plan_expire_date);
                                                                                                                let startdatesplit = gettimestart.split('-');
                                                                                                                let enddatesplit = gettimeend.split('-');
                                                                                                                let startdate;
                                                                                                                let enddate;
                                                                                                                let monthNames = {
                                                                                                                        "01": "January",
                                                                                                                        "02": "February",
                                                                                                                        "03": "March",
                                                                                                                        "04": "April",
                                                                                                                        "05": "May",
                                                                                                                        "06": "June",
                                                                                                                        "07": "July",
                                                                                                                        "08": "August",
                                                                                                                        "09": "September",
                                                                                                                        "10": "October",
                                                                                                                        "11": "November",
                                                                                                                        "12": "December"
                                                                                                                };
                                                                                                                $.each(monthNames, function (i, j) {

                                                                                                                        if (startdatesplit[1] == i) {
                                                                                                                                startdate = startdatesplit[0] + ' ' + j + ' ' + startdatesplit[2]
                                                                                                                        }
                                                                                                                        if (enddatesplit[1] == i) {
                                                                                                                                enddate = enddatesplit[0] + ' ' + j + ' ' + enddatesplit[2]
                                                                                                                        }

                                                                                                                });

                                                                                                                //console.log(gettimeend,enddatesplit);return false;

                                                                                                                htmlappend += `<h3> Plan : ${resJson.data.plan_name}</h3>`;
                                                                                                                let plandetailshtml = '';
                                                                                                                plandetailshtml += startdate + '-' + enddate;
                                                                                                                $("#activeplandiv").append(htmlappend);
                                                                                                                $("#plandetailspan").append(plandetailshtml);
                                                                                                                $("#validationspan").html("");
                                                                                                                $("#congratsdiv").show();
                                                                                                                $("#opacityBox").show();
                                                                                                        }

                                                                                                });


                                                                                                //$(".verifyprojectdetialsfinal").show();
                                                                                                $(".verifyprojectdetials").hide();

                                                                                                //$(this).text('Redirecting soon....');


                                                                                                let delay = 7000;
                                                                                                let url = siteUrl + '/wp-admin/admin.php?page=sortd_manage_templates'

                                                                                                setTimeout(function () {
                                                                                                        location.href = url;
                                                                                                }, delay);

                                                                                        } else {
                                                                                                $("#validationspan").html("");
                                                                                                $("#successfully_verified").show();
                                                                                                $("#opacityBox").show();
                                                                                                $(".verifyprojectdetialsfinal").show();
                                                                                                $(".verifyprojectdetials").hide();
                                                                                                //console.log("yes already",res.screenstatus);return false;
                                                                                                if (res.screenstatus == 3) {
                                                                                                        let delay = 5000;
                                                                                                        let url = siteUrl + '/wp-admin/admin.php?page=sortd-manage-settings&section=sortd_setup'
                                                                                                        setTimeout(function () {
                                                                                                                location.href = url;
                                                                                                        }, delay);
                                                                                                } else if (res.screenstatus == 1) {
                                                                                                        let delay = 5000;
                                                                                                        let url = siteUrl + '/wp-admin/admin.php?page=sortd_manage_templates'
                                                                                                        setTimeout(function () {
                                                                                                                location.href = url;
                                                                                                        }, delay);
                                                                                                } else if (res.screenstatus == 3) {
                                                                                                        let delay = 5000;
                                                                                                        let url = siteUrl + '/wp-admin/admin.php?page=sortd-manage-settings&section=sortd_setup'
                                                                                                        setTimeout(function () {
                                                                                                                location.href = url;
                                                                                                        }, delay);
                                                                                                } else {
                                                                                                        location.reload();
                                                                                                }
                                                                                        }
                                                                                } else if (res.verify.status == false) {
                                                                                
                                                                                        $(".verifyprojectdetialsfinal").hide();
                                                                                        $(".message").prepend(`<div class="notice notice-error is-dismissible"><p>${ res.verify.error.message}</p><span class="closeicon" aria-hidden="true">&times;</span></div>`);
                                                                                        $(".notice-error").delay(4000).fadeOut(500);
                                                                                        $(".verifyprojectdetials").hide();
                                                                                        $("#successfully_verified").hide();
                                                                                        $(".already_verified_onload").hide();
                                                                                
                                                                                        setInterval(function () { $(".verifycredentialsbtn").prop('disabled',false);}, 4000);
                                                                                }

                                                                        }
                                                                } else {
                                                                        console.log("This is not valid JSON format")
                                                                }
                                                        } catch (e) {

                                                                console.log(e);
                                                                return false;
                                                        }

                                                        $(this).prop('disabled',false);
                                                }
                                        });
                                }  catch(err){
                                        console.log(err);
                                        
                                        setInterval(function () { $(".verifycredentialsbtn").prop('disabled',false);}, 5000);
                                        $("#validationspan").html('Wrong JSON Format');
                                }
                        }
                },


                getDateFormat: function (date) {
                        let d = new Date(date),
                                month = '' + (d.getMonth() + 1),
                                day = '' + d.getDate(),
                                year = d.getFullYear();

                        if (month.length < 2)
                                month = '0' + month;
                        if (day.length < 2)
                                day = '0' + day;
                                
                        let cdate = new Date();
                        cdate.toLocaleDateString();

                        return [day, month, year].join('-');
                },

                isValidColor: function (str) {

                },

                getPaidArticles : function(e){
                        
                        let page = $(this).attr('data-page');
                        let pagecount = $("#pagecount").val();
                       // console.log(page);return false;
                        let pageid = $(this).attr('id');
                        $("#articlesSelectedCount").hide();
        
                        if(pageid !== 'previous' && pageid !== 'next'){
                                $('.page-link').removeClass('activePage');
                                $(this).addClass('activePage');
                        }
        
                        let activeid = $(".page-link.activePage").attr('id');
        
                        if(pageid == 'previous'){
                            let splitpage = activeid.split('page');
                            if( splitpage[1] == 1){
                                page = 1;
                            } else {
                                page = splitpage[1] - 1;
                            }
        
                            $('.page-link').removeClass('activePage');
                            $("#page"+page).addClass('activePage');
                            
                        } else if(pageid == 'next'){
                            
                            let splitpage = activeid.split('page');
        
                            if(pagecount == splitpage[1]){
                                page = pagecount;
                            } else {
                                page = parseInt(splitpage[1]) + 1;
                            }
        
                            $('.page-link').removeClass('activePage');
                            $("#page"+page).addClass('activePage');
        
                        }

                        let titleFilter = $("#titleFilter").val();
                        let priceFromFilter = $("#priceFromFilter").val();
                        let priceToFIlter = $("#priceToFIlter").val();
                        let categoryBasedFilter = $("#categoryBasedFilter").val();
   
                        if(titleFilter !== "" || priceFromFilter !== "" || priceToFIlter !== "" ||  categoryBasedFilter.length !== 0){
                               // console.log(categoryBasedFilter);return false;
                                let flag = true;
                                utils.filterBasedResults(e,page,flag);

                        } else {
        
                                
                        $.ajax({
                            url: sortd_ajax_obj_utils.ajax_url,
                            data : {'action':'sortd_get_paid_articles','page' : page,'sortd_nonce' : sortd_ajax_obj_utils.nonce},
                            type : 'post', 
                            success: function(result){
                                //console.log(result);return false;
                               
                                try{
        
                                    let remove_after= result.lastIndexOf('}');
                                    if(remove_after != -1){
                                      let dataresult =  result.substring(0,remove_after +1);
                                      let res = JSON.parse(result);
                                      let newtr = '';
                                      
                                     // console.log(typeof(res.paid_articles_data.length));return false;
                                      if(res.paid_articles_data.length != 0){
                                        
                                          $.each(res.paid_articles_data,function(i,j){
                                               
                                            newtr += `<tr>
                                            <td><input type="checkbox" name="paid_flag[]" value="${j.post_id}"></td>
                                            <td><a target="_blank" href="${j.url}">${j.title}</a></td>
                                            <td>${j.categories}</td>
                                            <td><span>&#8377;</span>${j.paid_price}</td>
                                            <td><a target="_blank" href="${j.url_admin}"><i class="bi bi-pencil-square"></i></a></td>
                                            </tr>`
                                            //newtr += "<tr><td>"+j.message+"</td><td>"+j.platform+"</td><td>"+j.message_type+"</td><td>"+j.sent_on+"</td></tr>"
                                          });
                                      } else {
                                          let strPrepend = '';
                                          strPrepend +=`<div class="notice notice-error is-dismissible"><p>${res.error.message}</p><span class="closeicon" aria-hidden="true">&times;</span></div>`;
                                          $(".mesnotify").prepend(strPrepend);
                                      }
        
                                      $("#getlist").html(newtr);

                                        //  counterFlag :0
                                        utils.checkedCounter();
                                  }else {
                                    console.log("This is not valid JSON format")
                                  } 
        
                                } catch (e){
                                    console.log(e);
                                    return false;
                                } 	
        
                            }
        
                        });

                   
                        }

                      

                       
        
                    },

                markBulkFree : function(e){
                        e.preventDefault();
                        let paidFlagCheckedLength = $('[name="paid_flag[]"]:checked').length;
                        let counter = 0;
                        if(paidFlagCheckedLength == 0){
                           $("#paid_flag_warning").show();
                            return false;
                        }

                     
                        var markFreeArticles = [];
                      
                        $('input[name="paid_flag[]"]:checked').each(function () {
                                markFreeArticles.push(this.value);
                                counter++;
                        });

                        if( utils.allPaidFlag  == true){
                                $("#paidarticleCountmsg").html(`Total ${counter} articles selected ... Are you sure want to mark all articles as free??`)
                                $("#myModal").modal('show');

                          
                             
                        } else {
                                $("#paid_flag_warning").hide();
                                $.ajax({

                                        url: sortd_ajax_obj_utils.ajax_url,
                                        data: { 'action': 'mark_free_sortd_action', 'markFreeArticles':markFreeArticles,'sortd_nonce': sortd_ajax_obj_utils.nonce},
                                        type: 'post',
                                        success: function (result) {
                                         let response = JSON.parse(result);
                                                if(response.status == true){
                                                       
                                                      
                                                        location.reload();
                                                }
                                          
                                        }
                                        
                                });
                        }
                      
                       
                },
                displayMessages: function(){
                       // alert("hiii")
                        setTimeout(function() {
                                $('.get_success_mark_free').fadeOut('fast');
                            }, 3000);
                },
                markBulkFreeSelect : function(e){
                        e.preventDefault();
                        var markFreeArticles = [];
                        let paidFlagCheckedLength = $('[name="paid_flag[]"]:checked').length;
                        $("#paidarticleCountmsg").hide();
                        $(".loadgif").show();
                        $('input[name="paid_flag[]"]').each(function () {
                                $(this).prop('checked',true);
                                markFreeArticles.push(this.value);
                        });


                        $.ajax({

                                url: sortd_ajax_obj_utils.ajax_url,
                                data: { 'action': 'mark_free_sortd_action', 'markFreeArticles':markFreeArticles,'sortd_nonce': sortd_ajax_obj_utils.nonce},
                                type: 'post',
                                success: function (result) {
                                 let response = JSON.parse(result);
                                        if(response.status == true){
                                               
                                              
                                                location.reload();
                                        }
                                  
                                }
                                
                        });

                },

                markAllPaidFlag : function(){

                        let isChecked = $('#allSelectPaidArticles').prop('checked');
                       
                        if(isChecked == true){
                                let counter = 0;
                                let postsCount = $("#postscount").val();

                                utils.allPaidFlag = true;
                                $('input[name="paid_flag[]"]').each(function () {
                                        $(this).prop('checked',true);

                                        counter++;
                                
                                });

                                $("#articlesSelectedCount").show();
                                $("#paid_flag_warning").hide();

                                $("#articlesSelectedCount").text(`${counter} articles selected of ${postsCount} articles.`);
                        } else {
                                utils.allPaidFlag = false;
                                $('input[name="paid_flag[]"]').each(function () {
                                        $(this).prop('checked',false);
                                });  
                                
                                $("#articlesSelectedCount").hide();
                        }
                       
                        
                },

                filterBasedResults : function(e,page,flag){

                     e.preventDefault();
                     let titleFilter = $("#titleFilter").val();
                     let priceFromFilter = $("#priceFromFilter").val();
                     let priceToFIlter = $("#priceToFIlter").val();
                     let categoryBasedFilter = $("#categoryBasedFilter").val();
                     if(flag == '' || flag == undefined){
                        flag = false;
                     }

                     if(page == undefined){
                        page = 1;
                     }
                   
                     let toPrice = $('#priceToFIlter').val();
                     let fromPrice = $('#priceFromFilter').val();
                     if((toPrice !== "") && (fromPrice !== "") && (parseInt(toPrice) < parseInt(fromPrice)) ){
                        console.log("Addd validation please");
                        $("#numberspan").html("Max price must be greater than Min price");
                        $("#numberspan").show();
                        return false;
                     }
                    else  if(titleFilter == "" && priceFromFilter == "" &&  priceToFIlter == "" &&  categoryBasedFilter.length == 0 && flag== false){
                             console.log("Addd validation please");
                             $("#validationmsg").html("Select any filter");
                             $("#validationmsg").show();
                            // articlesSelectedCount

                                return false;
                     }

                    else {
                        $("#numberspan").hide(); 
                     $.ajax({

                        url: sortd_ajax_obj_utils.ajax_url,
                        data: { 'action': 'search_based_on_filters','page':page, 'title':titleFilter,'price':priceFromFilter,'priceto':priceToFIlter,'categoryData':categoryBasedFilter,'sortd_nonce': sortd_ajax_obj_utils.nonce},
                        type: 'post',
                     
                        success: function (result) {
                         let res = JSON.parse(result);
                         $("#validationmsg").hide();

                       
                         let newtr = '';
                         let newpagstr = '';
                                      
                                   //  console.log(res.chunks.length);return false;
                                      if(res.chunks.length != 0){

                                        if(res.chunks.length < 10){
                                           $(".sortPag").hide();
                                        }
                                       let  $pages_count = Math.ceil(res.count/10); 
                                        newpagstr += `
                                        
                                        <li class="page-item">
                                                <a class="page-link" id="previous" href="javascript:void(0);" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                                <span class="sr-only">Previous</span>
                                                </a>
                                        </li>`;
                                   
                                     
                                        for(let $i = "1"; $i <= $pages_count ;$i++){ 
                                           newpagstr += `<li class="page-item"><a class="page-link" id="page${$i}" data-page="${$i}" href="javascript:void(0);">${$i}</a></li>`;
                                         } 

                                         newpagstr+= `
                                        <input type ="hidden" id="pagecount" value="${$pages_count}">
                                        <input type ="hidden" id="postscount" value="${res.count}">
                                        <li class="page-item">
                                                <a class="page-link" id="next" href="javascript:void(0);" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                                <span class="sr-only">Next</span>
                                                </a>
                                        </li>
                                        
                                        `;

                                        $(".sortPag").html(newpagstr);
                                        let counter = 0;
                                          $.each(res.chunks,function(i,j){
                                               counter++;
                                            newtr += `<tr>
                                            <td><input type="checkbox" name="paid_flag[]" value="${j.post_id}"></td>
                                            <td><a target="_blank" href="${j.url}">${j.title}</a></td>
                                            <td>${j.categories}</td>
                                            <td><span>&#8377;</span>${j.paid_price}</td>
                                            <td><a target="_blank" href="${j.url_admin}"><i class="bi bi-pencil-square"></i></a></td>
                                            </tr>`
                                            //newtr += "<tr><td>"+j.message+"</td><td>"+j.platform+"</td><td>"+j.message_type+"</td><td>"+j.sent_on+"</td></tr>"
                                          });
                                          let postsCount = $("#postscount").val();
                                          $("#articlesSelectedCount").text(`${counter} articles of ${postsCount} articles.`);

                                          $("#getlist").html(newtr);
                                           $("#bulk_mark_free").show();
                                           $("#allSelectPaidArticles").show();

                                           utils.checkedCounter();
                                         
                                      } else {

                                      
                                        $("#getlist").html("<tr><td></td><td></td><td></td><td>No article found</td><td></td></tr>>"); 
                                        $("#bulk_mark_free").hide();
                                        $("#allSelectPaidArticles").hide();
                                        $(".sortPag").hide();
                                        $("#articlesSelectedCount").hide();
                                      
                                      }
                         
                                      $(".page-link").click(utils.getPaidArticles);
                          
                        }
                        
                        
                     });

                }
                   


                },
                resetBulkAction: function(){
                        $("#myModal").modal('hide');
                        $('input[name="paid_flag[]"]:checked').each(function () {
                                $(this).prop('checked',false);

                        });
                        $("#allSelectPaidArticles").prop("checked",false);
                        $("#articlesSelectedCount").hide();
                      
                },
                resetFilters : function(e){
                        
                        e.preventDefault();
                       $("#titleFilter").val('');
                       $("#priceFromFilter").val('');
                       $("#priceToFIlter").val('');
                       $("#categoryBasedFilter").val('');
                       $(".filter-option-inner-inner").text('Select Category');
                       $(".filter-option-inner-inner").css('color','#a8a6a2');
                      // $("#categoryBasedFilter option:selected").removeAttr("selected");
                       $("#numberspan").hide(); 
                       $(".filterBtn").prop("disabled",false);
                       $("#validationmsg").hide();

                       $.ajax({
                        url: sortd_ajax_obj_utils.ajax_url,
                        data : {'action':'get_count_after_reset','sortd_nonce' : sortd_ajax_obj_utils.nonce},
                        type : 'post', 
                        success: function(result){

                                let res = JSON.parse(result);
                                let newpagstr = '';
                                let  $pages_count = Math.ceil(res/10); 
                                        newpagstr += `
                                        
                                        <li class="page-item">
                                                <a class="page-link" id="previous" href="javascript:void(0);" aria-label="Previous">
                                                <span aria-hidden="true">&laquo;</span>
                                                <span class="sr-only">Previous</span>
                                                </a>
                                        </li>`;
                                   
                                     
                                        for(let $i = "1"; $i <= $pages_count ;$i++){ 
                                           newpagstr += `<li class="page-item"><a class="page-link" id="page${$i}" data-page="${$i}" href="javascript:void(0);">${$i}</a></li>`;
                                         } 

                                         newpagstr+= `
                                        <input type ="hidden" id="pagecount" value="${$pages_count}">
                                        <input type ="hidden" id="postscount" value="${res.count}">
                                        <li class="page-item">
                                                <a class="page-link" id="next" href="javascript:void(0);" aria-label="Next">
                                                <span aria-hidden="true">&raquo;</span>
                                                <span class="sr-only">Next</span>
                                                </a>
                                        </li>
                                        
                                        `;

                                        $(".sortPag").html(newpagstr);
                                        $(".sortPag").show();
                        }
                });

              
                       let page = 1;
                       $.ajax({
                        url: sortd_ajax_obj_utils.ajax_url,
                        data : {'action':'sortd_get_paid_articles','page' : page,'sortd_nonce' : sortd_ajax_obj_utils.nonce},
                        type : 'post', 
                        success: function(result){
                         
                            try{
    
                                let remove_after= result.lastIndexOf('}');
                                if(remove_after != -1){
                                  let dataresult =  result.substring(0,remove_after +1);
                                  let res = JSON.parse(result);
                                  let newtr = '';
                                  
                                 // console.log(typeof(res.paid_articles_data.length));return false;
                                  if(res.paid_articles_data.length != 0){
                                    
                                      $.each(res.paid_articles_data,function(i,j){
                                           
                                        newtr += `<tr>
                                        <td><input type="checkbox" name="paid_flag[]" value="${j.post_id}"></td>
                                        <td><a target="_blank" href="${j.url}">${j.title}</a></td>
                                        <td>${j.categories}</td>
                                        <td><span>&#8377;</span>${j.paid_price}</td>
                                        <td><a target="_blank" href="${j.url_admin}"><i class="bi bi-pencil-square"></i></a></td>
                                        </tr>`
                                      });
                                  } else {
                                      let strPrepend = '';
                                      strPrepend +=`<div class="notice notice-error is-dismissible"><p>${res.error.message}</p><span class="closeicon" aria-hidden="true">&times;</span></div>`;
                                      $(".mesnotify").prepend(strPrepend);
                                  }
    
                                  $("#getlist").html(newtr);
                                  $("#articlesSelectedCount").hide();

                                  $(".page-link").click(utils.getPaidArticles);
                              }else {
                                   
                                console.log("This is not valid JSON format")
                              } 
    
                            } catch (e){
                                console.log(e);
                                return false;
                            } 	
    
                        }
    
                    });
                },

                checkedCounter : function(){
                        $('input[name="paid_flag[]"]').change(function() {

                                let counter=0;
                               
                                          $('[name="paid_flag[]"]:checked').each(function (i,j) {
                                                  counter++;
                                          });
                                          let postsCount = $("#postscount").val();
                  
                                  $("#articlesSelectedCount").text(`${counter} articles selected of ${postsCount} articles.`);
                                  $("#allSelectPaidArticles").prop("checked",false);
                                  console.log(counter,'dasdsad');
                                          
                         });
                },

                numberValidation:function(){

                        let text = $('#priceFromFilter').val();
                        let toPrice = $('#priceToFIlter').val();
                        console.log(text.length);

                        if(text.length !== 0){
                                if(text <= 0){
                                        $("#numberspan").html("Please enter number greater than 0");
                                        $("#numberspan").show();
                                        $(".filterBtn").prop("disabled",true);
                                }  else {
                                        $("#numberspan").hide();
                                        $(".filterBtn").prop("disabled",false);
                                }
                        } else {
                                $("#numberspan").hide();
                                $(".filterBtn").prop("disabled",false);
                        }
                       
                       
                },
                numberValidationTo:function(){

                        let text = $('#priceToFIlter').val();
                        let fromPrice = $('#priceFromFilter').val();

                        console.log(text);

                        if(text.length !== 0){
                                if(text <= 0){
                                        console.log("Sdds",fromPrice);
                                        $("#numberspanto").html("Please enter number greater than 0");
                                        $("#numberspanto").show();
                                        $(".filterBtn").prop("disabled",true);
                                }  else {
                                        $("#numberspanto").hide();
                                        $(".filterBtn").prop("disabled",false);
                                }
                        }else {
                                $("#numberspanto").hide();
                                $(".filterBtn").prop("disabled",false);
                        }
                       
                }

            


        }

        $(".closepaid").click(function(){
                $("#myModal").modal('hide');
        });

        $(document).keypress(function(e) {
               // e.preventDefault();
                if(e.which == 13) {
                        utils.filterBasedResults(e,'',false);
                        $("#paid_flag_warning").hide();
                }
            });

       
        $(window).load( utils.checkedCounter);
        $("#priceFromFilter").on('input',utils.numberValidation);
        $("#priceToFIlter").on('input',utils.numberValidationTo);
        
        $(".resetBtn").click(utils.resetFilters);
        $(".cancelBtnPaidAction").click(utils.resetBulkAction)
        $(".filterBtn").click(utils.filterBasedResults)
        $(".saveBtnPaidAction").click(utils.markBulkFreeSelect);
        $("#allSelectPaidArticles").click(utils.markAllPaidFlag);
        $("#bulk_mark_free").click(utils.markBulkFree);
        $(window).load(utils.displayMessages);
        $(".verifycredentialsbtn").click(utils.verifyCredentials);
        $(".page-link").click(utils.getPaidArticles);
       



})(jQuery);