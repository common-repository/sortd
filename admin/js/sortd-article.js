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

    const article = {
        dynamicId: null,
        syncCount: 0,
        requestCount: 0,

        bulkSyncArticles:  async function () {

            

            let postCheckedLength = $('[name="post[]"]:checked').length;

            if (postCheckedLength == 0) {
                $(".bulk_validation").show();
                return false;
            }
           // const response = await article.bulkCountPostSync();
           var wp_domain = $(this).attr('data-wp_domain');
           var project_slug = $(this).attr('data-project_slug');
           var current_user = $(this).attr('data-current_user');

           if (typeof gtag === 'function') {
               gtag('event', 'sortd_action', {
                   'sortd_page_title': 'All Post Screen',
                   'sortd_feature': 'Bulk Sync',
                   'sortd_domain': wp_domain,
                   'sortd_project_slug': project_slug,
                   'sortd_user': current_user
               });
           }

           let articlesToSync = []
            $('input[name="post[]"]:checked').each(async function () {
                articlesToSync.push(this.value)
                $(".bulk_validation").hide();



            });


            // AJAX call to check the flag of the article in the database
            const filtered_arr = await $.ajax({
                url: sortd_ajax_obj_article.ajax_url,
                data: {
                'action': 'filter_article_array',
                'sortd_nonce': sortd_ajax_obj_article.nonce,
                'articles_to_sync':articlesToSync
                },
                type: 'post'
            });
            console.log(filtered_arr);

            // return false;
            // return false;

            // const flagResponse = JSON.parse(response);

            // if (flagResponse.flag == "1" || flagResponse.flag == 1) {
            //     // Flag is false, push article to articlesToSync array
            //     articlesToSync.push(articleGuid);
            // }


            articlesToSync=JSON.parse(filtered_arr);
            console.log({
                articlesToSync
            })
            // return false;

            // already_synced=0;
            for(const articleGuid of articlesToSync){
                const respo =  await article.bulkCountPostSync(articleGuid);
            }

            //update

            $.ajax({

                    url: sortd_ajax_obj_article.ajax_url,
                    data: {
                        'action': 'sortd_update_bulk_flag',
                        'post_count_1': article.syncCount,
                        'sortd_nonce': sortd_ajax_obj_article.nonce
                    },
                    type: 'post',
                    success: function (result) {

                        $(".bulkactionloader").hide();
                        location.reload();

                    }

                });

        },

        bulkCountPostSync: async function (articleGuid) {
            const response = await article.bulkSyncCall(articleGuid);
            return response;
        },

        bulkSyncCall: function (value) {

            return new Promise((resolve,reject)=>{
                $.ajax({

                    url: sortd_ajax_obj_article.ajax_url,
                    data: {
                        'action': 'sync_articles_in_bulk',
                        'page': 0,
                        'post_count': article.syncCount,
                        'postids': value,
                        'sortd_nonce': sortd_ajax_obj_article.nonce
                    },
                    type: 'post',
                    success: function (result) {


                        $(".bulkactionloader").show();
                        // console.log(result);
                        let response = JSON.parse(result);
                        console.log(response.status);


                        // article.requestCount++;
                        if (response.status == "true" || response.status == true) {
                            // console.log("I am here");
                            article.syncCount++;
                            console.log(article.syncCount)
                            // console.log(article.syncCount);


                            $.ajax({
                                url: sortd_ajax_obj_article.ajax_url,
                                data: {
                                    'action': 'sortd_update_bulk_count',
                                    'post_count_1': article.syncCount,
                                    // 'post_count_1':sizeof(articlesToSync),
                                    'post_id': value,
                                    'sortd_nonce': sortd_ajax_obj_article.nonce
                                },
                                type: 'post',
                                success: function (result) {
                                    // console.log("plzzzzzzzzzzzzz");

                                }
                            });

                        } else {

                            // article.syncCount++;
                            // swal({
                            //     icon: 'error',
                            //     text: 'Articles could not be synced !!!',
                            //     timer: 3000
                            // });
                            // $(".bulkactionloader").hide();
                        }


                        return resolve(article.syncCount);

                    }

                });

            });
        },

        manualSyncArticle: function (e) {

            e.stopImmediatePropagation();
            e.preventDefault();
            let siteUrl = $(this).attr('data-siteurl');
            let postId = $(this).attr('data-guid');
            let posttype = $(this).attr('data-post_type');
            let project_slug = $(this).attr('data-project_slug');
            let current_user = $(this).attr('data-current_user');
  
            let qr_code = $("#action_sortd_btn" + postId).attr('data-qrcode');

            let is_paid_flag = $(this).attr('data-is_paid');
            let paid_flag
            if (is_paid_flag == undefined || is_paid_flag == 'undefined') {
                paid_flag = false;
            } else {
                paid_flag = true;
            }

            if (typeof gtag === 'function') {
                gtag('event', 'sortd_action', {
                    'sortd_page_title': 'All Post Screen',
                    'sortd_feature': 'Manual Post Sync',
                    'sortd_domain': siteUrl,
                    'sortd_project_slug': project_slug,
                    'sortd_user': current_user
                });
            }

            $.ajax({
                url: sortd_ajax_obj_article.ajax_url,
                data: {
                    'action': 'sortd_ajax_manual_sync',
                    'post_id': postId,
                    'sortd_nonce': sortd_ajax_obj_article.nonce
                },
                type: 'post',
                success: function (result) {

                    let incStr = result.includes("<!--");
                    try {

                        let removeAfter = result.lastIndexOf('}');
                        if (removeAfter != -1) {
                            let dataResult = result.substring(0, removeAfter + 1);
                            let response = JSON.parse(result);

                            if (response.status === true) {

                                console.log(response);
                                $("#post-" + postId).find(".sortdview").html(`<img class="unsyncBtn unsync_${postId} " src="${siteUrl}/wp-content/plugins/wp_sortd/admin/css/check.png">`);

                                $(".timeupdatepostid" + postId).hide();

                                $("#sync_" + postId).hide();
                                $(".syncfailed_" + postId).hide();
                                $(".unsync_" + postId).show();
                                $("#unsync_" + postId).show();
                                $(".sortsyncnotify" + postId).show();
                                $(".successsync_" + postId).show();
                                $(".successsync_" + postId).text('synced');
                                $(".btnnotify" + postId).show();
                                setInterval(function () {
                                    $(".successsync_" + postId).hide();

                                }, 2000);
                                $("#unsync_" + postId).show();



                                if (is_paid_flag !== "" && response.paid_value !== '') {

                                    $(".artc_paid_By_id" + postId).show();


                                }
                                if (response.is_paid == true && posttype != 'live-blog') {
                                    $(".edit_price").show();
                                }

                                $(".imgunsync" + postId).hide();
                                $(".imgsync" + postId).show();

                                $(".showsyncstatus" + postId).text('Synced');
                                $(".showsyncstatus" + postId).css('color', "#49a827");
                                $(".showsyncstatus" + postId).show();
                                $(".showunsyncstatus" + postId).hide();

                                $(".artc_paid_By" + postId).text(response.paid_value);
                                //  $(".artc_paid_By_id"+postId).show();


                                $("#qRBoxId").attr("src", qr_code);

                            } else if (response.status == false) {
                                if (response.error.errorCode != 1004 && response.error.errorCode != 1005) {
                                    $("#sync_" + postId).prepend(`<div class="notice notice-error is-dismissible"><p>${response.error.message}</p><span class="closeicon" aria-hidden="true">&times;</span></div>`);
                                    $(".notice-error").delay(2000).fadeOut(500);
                                } else {
                                    $('.modal-body').text(response.error.message);
                                    $('#server_msg_modal_' + postId).modal('show');
                                }
                            }
                        } else {
                            console.log("This is not valid JSON format")
                        }
                    } catch (e) {
                        console.log(e);
                        return false;
                    }
                }

            });

            return false;

        },

        unsyncArticle: function (e) {
            e.stopImmediatePropagation();
            e.preventDefault();
            let guid = $(this).data('guid');
            let siteUrl = $(this).attr('data-siteurl');
            let project_slug = $(this).attr('data-project_slug');
            let current_user = $(this).attr('data-current_user');

            if (typeof gtag === 'function') {
                gtag('event', 'sortd_action', {
                    'sortd_page_title': 'All Post Screen',
                    'sortd_feature': 'Manual Post Un-Sync',
                    'sortd_domain': siteUrl,
                    'sortd_project_slug': project_slug,
                    'sortd_user': current_user
                });
            }

            console.log("guid"+guid);
            let nonce = $("#nonce_input").val(); // Retrieve the nonce from the hidden input.
            $.ajax({

                url: sortd_ajax_obj_article.ajax_url,
                data: {
                    'action': 'unsync_article',
                    'guid': guid,
                    'sortd_nonce': sortd_ajax_obj_article.nonce,
                    '_wpnonce':nonce // added by me
                },
                type: 'post',
                success: function (result) {

                    try {
                        let removeAfter = result.lastIndexOf('}');
                        if (removeAfter != -1) {
                            let dataResult = result.substring(0, removeAfter + 1);
                            let response = JSON.parse(result);

                            if (response.status === true) {

                                $(".successsync_" + guid).text('unsynced');
                                // $(".successsync_"+guid).show();
                                $(".imgunsync" + guid).show();
                                $(".imgsync" + guid).hide();
                                $(".artc_paid_By").hide();
                                setInterval(function () {
                                    $(".successsync_" + guid).hide();

                                }, 2000);

                                $(".showsyncstatus" + guid).text('Unsynced');
                                $(".showsyncstatus" + guid).css('color', "#fa9a3e");
                                $("#sync_" + guid).show();
                                $(".sync_" + guid).show();
                                $("#unsync_" + guid).hide();
                                $(".sortsyncnotify" + guid).hide();
                                $(".edit_price").hide();
                                var data404 = $("#qRBoxId").attr('data-imagepath');
                                $("#qRBoxId").attr("src", data404);

                            } else if (response.status == false) {
                                if (response.error.errorCode != 1004 && response.error.errorCode != 1005) {
                                    $("#unsync_" + guid).prepend(`<div class="notice notice-error is-dismissible"><p>${response.error.message}</p><span class="closeicon" aria-hidden="true">&times;</span></div>`);
                                    $(".notice-error").delay(2000).fadeOut(500);
                                } else {
                                    $('.modal-body').text(response.error.message);
                                    $('#server_msg_modal_' + guid).modal('show');
                                }

                            }
                        } else {
                            console.log("This is not valid JSON format")
                        }

                    } catch (e) {

                        return false;
                    }
                }
            });
        },


        bulkUnsyncArticles: function () {
            let unsyncCount = 0;
            let unrequestCount = 0;

            let postCheckedLengthUnsync = $('[name="post[]"]:checked').length;
            let nonce = $("#nonce_input").val(); // Retrieve the nonce from the hidden input.

            //console.log("selected post count: "+postCheckedLengthUnsync);
            if (postCheckedLengthUnsync == 0) {
                $(".bulk_validation_unsync").show();
                return false;
            }

            var wp_domain = $(this).attr('data-wp_domain');
            var project_slug = $(this).attr('data-project_slug');
            var current_user = $(this).attr('data-current_user');

            if (typeof gtag === 'function') {
                gtag('event', 'sortd_action', {
                    'sortd_page_title': 'All Post Screen',
                    'sortd_feature': 'Bulk Un-Sync',
                    'sortd_domain': wp_domain,
                    'sortd_project_slug': project_slug,
                    'sortd_user': current_user
                });
            }

            $('input[name="post[]"]:checked').each(function () {
                $(".bulk_validation_unsync").hide();





                $.ajax({

                    url: sortd_ajax_obj_article.ajax_url,
                    data: {
                        'action': 'unsync_articles_in_bulk',
                        'page': 0,
                        'post_count': unsyncCount,
                        'postids': this.value,
                        'sortd_nonce': sortd_ajax_obj_article.nonce,
                        '_wpnonce':nonce
                    },
                    type: 'post',
                    success: function (result) {


                        $(".bulkactionloaderunysnc").show();
                        let response = JSON.parse(result);

                        unrequestCount++;
                        if (response.status == "true" || response.status == true) {

                            unsyncCount++;

                            console.log(unsyncCount,"gggg")
                            $.ajax({
                                url: sortd_ajax_obj_article.ajax_url,
                                data: {
                                    'action': 'update_bulk_unsync_count',
                                    'post_count_unsync': unsyncCount,
                                    'sortd_nonce': sortd_ajax_obj_article.nonce
                                },
                                type: 'post',
                                success: function (result) {
                                    console.log("ooo",result);
                                }
                            });

                        } else {



                            // swal({
                            //     icon: 'error',
                            //     text: 'Articles could not be unsynced !!!',
                            //     timer: 3000
                            // });
                            $(".bulkactionloaderunysnc").hide();
                        }

                        if (postCheckedLengthUnsync == unrequestCount) {

                            console.log(unsyncCount,'rrr');

                            $.ajax({

                                url: sortd_ajax_obj_article.ajax_url,
                                data: {
                                    'action': 'sortd_update_bulk_unsync_flag',
                                    'post_count_unsync': unsyncCount,
                                    'sortd_nonce': sortd_ajax_obj_article.nonce
                                },
                                type: 'post',
                                success: function (result) {

                                    $(".bulkactionloaderunysnc").hide();
                                    location.reload();

                                }

                            });
                        }
                    }

                });

            });

        },
        unysncWebstory: function (e) {
            e.preventDefault();

            let guid = $(this).data('guid');

            $.ajax({

                url: sortd_ajax_obj_article.ajax_url,
                data: {
                    'action': 'unsync_webstory',
                    'guid': guid,
                    'sortd_nonce': sortd_ajax_obj_article.nonce
                },
                type: 'post',
                success: function (result) {



                    try {
                        let removeAfter = result.lastIndexOf('}');
                        if (removeAfter != -1) {
                            let dataResult = result.substring(0, removeAfter + 1);
                            let response = JSON.parse(result);


                            console.log("yes its done unsyncing",guid);
                                     $(".successsync_" + guid).text('unsynced');


                                


                            if (response.status === true) {


                                $(".syncwebstory"+guid).show();
                                // $(".successsync_"+guid).show();
                                $(".imgunsync" + guid).show();
                                $(".imgsync" + guid).hide();
                                $(".artc_paid_By").hide();
                                setInterval(function () {
                                    $(".successsync_" + guid).hide();

                                }, 2000);

                                $(".showsyncstatus" + guid).text('Unsynced');
                                $(".showsyncstatus" + guid).css('color', "#fa9a3e");
                                $("#sync_" + guid).show();
                                $(".sync_" + guid).show();
                                $("#unsync_" + guid).hide();
                                $(".sortsyncnotify" + guid).hide();
                                $(".edit_price").hide();
                                var data404 = $("#qRBoxId").attr('data-imagepath');
                                $("#qRBoxId").attr("src", data404);

                                $(".unsync_webstory" + guid).hide();
                                //  $("#msgwebstory"+guid).show();
                                $("#msgwebstory" + guid).text('unsynced');
                                $(".sync_webstory" + guid).show();

                                $("#msgwebstory" + guid).delay(3000).fadeOut(800, function () {
                                    $(this).remove();
                                });


                            } else if (response.status == false) {

                                if (response.error.errorCode != 1004 && response.error.errorCode != 1005) {
                                    $("#unsync_" + guid).prepend(`<div class="notice notice-error is-dismissible"><p>${response.error.message}</p><span class="closeicon" aria-hidden="true">&times;</span></div>`);
                                    $(".notice-error").delay(2000).fadeOut(500);
                                } else {
                                    $('.modal-body').text(response.error.message);
                                    $('#server_msg_modal_' + guid).modal('show');
                                }

                            }
                        } else {
                            console.log("This is not valid JSON format")
                        }

                    } catch (e) {
                        return false;
                    }
                }
            });
        },
        syncWebstory: function (e) {
            e.preventDefault();


            let siteUrl = $(this).attr('data-siteurl');
            let postId = $(this).attr('data-guid');
             let qr_code = $(".syncwebstory" + postId).attr('data-qrcode');
            //  console.log("HIHIHIHI: ", postId);


            $.ajax({
                url: sortd_ajax_obj_article.ajax_url,
                data: {
                    'action': 'sync_webstory',
                    'post_id': postId,
                    'sortd_nonce': sortd_ajax_obj_article.nonce
                },
                type: 'post',
                success: function (result) {
                    console.log(result);




                    let incStr = result.includes("<!--");
                    try {

                        let removeAfter = result.lastIndexOf('}');
                        if (removeAfter != -1) {
                            let dataResult = result.substring(0, removeAfter + 1);
                            let response = JSON.parse(result);
                            console.log(response);

                                if(response.status != false) {
                                    console.log("yes its done syncing",postId);
                                    $("#post-" + postId).find(".sortdview").html(`<img class="unsyncBtn unsync_${postId} " src="${siteUrl}/wp-content/plugins/wp_sortd/admin/css/check.png">`);
                                //  $(".webstory_action").show();
                                    $(".syncwebstory"+postId).show();
                                    $(".timeupdatepostid" + postId).hide();

                                    $("#sync_" + postId).hide();
                                    $(".syncfailed_" + postId).hide();
                                    $(".unsync_" + postId).show();
                                    $("#unsync_" + postId).show();

                                    $(".successsync_" + postId).show();
                                    $(".successsync_" + postId).text('synced');
                                    $(".btnnotify" + postId).show();
                                    setInterval(function () {
                                        $(".successsync_" + postId).hide();

                                    }, 2000);
                                    $("#unsync_" + postId).show();




                                    $(".imgunsync" + postId).hide();
                                    $(".imgsync" + postId).show();


                                    console.log(  $(".imgunsync" + postId));
                                    console.log($(".imgsync" + postId));

                                    $(".showsyncstatus" + postId).text('Synced');
                                    $(".showsyncstatus" + postId).css('color', "#49a827");
                                    $(".showsyncstatus" + postId).show();
                                    $(".showunsyncstatus" + postId).hide();

                                    //  $(".artc_paid_By_id"+postId).show();


                                    $("#qRBoxId").attr("src", qr_code);
                                }

                            if (response.status === true) {


                                $("#post-" + postId).find(".sortdview").html(`<img class="unsyncBtn unsync_${postId} " src="${siteUrl}/wp-content/plugins/wp_sortd/admin/css/check.png">`);

                                $(".timeupdatepostid" + postId).hide();
                                //console.log('Article Synced Successfully : ' + postId);


                                $(".unsync_webstory" + postId).show();
                                $(".sortsyncnotify" + postId).show();
                                $(".sync_webstory" + postId).hide();

                                $("#msgwebstory" + postId).text('synced');
                                $("#msgwebstory" + postId).delay(3000).fadeOut(800, function () {
                                    $(this).remove();
                                });





                            } else if (response.status == false) {
                                if (response.error.errorCode != 1004 && response.error.errorCode != 1005) {
                                    $("#sync_" + postId).prepend(`<div class="notice notice-error is-dismissible"><p>${response.error.message}</p><span class="closeicon" aria-hidden="true">&times;</span></div>`);
                                    $(".notice-error").delay(2000).fadeOut(500);
                                } else if(response.error) {
                                    $("#sync_" + postId).prepend(`<div class="notice notice-error is-dismissible"><p>${response.error}</p><span class="closeicon" aria-hidden="true">&times;</span></div>`);
                                    $(".notice-error").delay(2000).fadeOut(500);
                                } else {
                                    $('.modal-body').text(response.error.message);
                                    $('#server_msg_modal_' + postId).modal('show');
                                }
                            }
                        } else {
                            console.log("This is not valid JSON format")
                        }
                    } catch (e) {
                        console.log(e);
                        return false;
                    }
                }
            });

        },

        bulkSyncWebstories: function (e) {
            e.preventDefault();
            let syncCountWbUnsync = 0;
            let requestCountWbUnsync = 0;

            let postCheckedLengthWbUnsync = $('[name="post[]"]:checked').length;

            if (postCheckedLengthWbUnsync == 0) {
                $(".bulk_validation_wb").show();
                return false;
            }

            $('input[name="post[]"]:checked').each(function () {
                $(".bulk_validation_wb").hide();

                $.ajax({

                    url: sortd_ajax_obj_article.ajax_url,
                    data: {
                        'action': 'bulk_sync_webstories',
                        'page': 0,
                        'post_count_Wb': syncCountWbUnsync,
                        'postids': this.value,
                        'sortd_nonce': sortd_ajax_obj_article.nonce
                    },
                    type: 'post',
                    success: function (result) {


                        $(".bulkactionloaderwb").show();
                        let response = JSON.parse(result);

                        requestCountWbUnsync++;
                        if (response.status == "true" || response.status == true) {

                            syncCountWbUnsync++;
                            $.ajax({
                                url: sortd_ajax_obj_article.ajax_url,
                                data: {
                                    'action': 'update_bulk_count_webstory',
                                    'post_count_Wb': syncCountWbUnsync,
                                    'sortd_nonce': sortd_ajax_obj_article.nonce
                                },
                                type: 'post',
                                success: function (result) {
                                    //return false;
                                }
                            });

                        } else {

                            // swal({
                            //     icon: 'error',
                            //     text: 'Articles could not be synced !!!',
                            //     timer: 3000
                            // });
                            $(".bulkactionloaderwb").hide();
                        }

                        if (postCheckedLengthWbUnsync == requestCountWbUnsync) {

                            $.ajax({

                                url: sortd_ajax_obj_article.ajax_url,
                                data: {
                                    'action': 'update_bulk_flag_webstory',
                                    'post_count_Wb': syncCountWbUnsync,
                                    'sortd_nonce': sortd_ajax_obj_article.nonce
                                },
                                type: 'post',
                                success: function (result) {

                                    $(".bulkactionloaderwb").hide();
                                    location.reload();

                                }

                            });
                        }
                    }

                });

            });

        },

        bulkUnsyncWebstories: function (e) {
            e.preventDefault();
            let unsyncCountWb = 0;
            let unrequestCountWb = 0;

            let postCheckedLengthUnsyncWb = $('[name="post[]"]:checked').length;

            if (postCheckedLengthUnsyncWb == 0) {
                $(".bulk_validation_unsync_wb").show();
                return false;
            }

            $('input[name="post[]"]:checked').each(function () {
                $(".bulk_validation_unsync_wb").hide();

                $.ajax({

                    url: sortd_ajax_obj_article.ajax_url,
                    data: {
                        'action': 'bulk_unsync_webstories',
                        'page': 0,
                        'post_count_wb_unsync': unsyncCountWb,
                        'postids': this.value,
                        'sortd_nonce': sortd_ajax_obj_article.nonce
                    },
                    type: 'post',
                    success: function (result) {


                        $(".bulkactionloaderunysncwb").show();
                        let response = JSON.parse(result);

                        unrequestCountWb++;
                        if (response.status == "true" || response.status == true) {

                            unsyncCountWb++;


                            $.ajax({
                                url: sortd_ajax_obj_article.ajax_url,
                                data: {
                                    'action': 'update_bulk_count_webstory_unsync',
                                    'post_count_unsync': unsyncCountWb,
                                    'sortd_nonce': sortd_ajax_obj_article.nonce
                                },
                                type: 'post',
                                success: function (result) {



                                }
                            });

                        } else {

                            // swal({
                            //     icon: 'error',
                            //     text: 'Articles could not be unsynced !!!',
                            //     timer: 3000
                            // });
                            $(".bulkactionloaderunysncwb").hide();
                        }

                        if (postCheckedLengthUnsyncWb == unrequestCountWb) {

                            $.ajax({

                                url: sortd_ajax_obj_article.ajax_url,
                                data: {
                                    'action': 'update_bulk_flag_webstory_unsync',
                                    'post_count_wb_unsync': unsyncCountWb,
                                    'sortd_nonce': sortd_ajax_obj_article.nonce
                                },
                                type: 'post',
                                success: function (result) {

                                    $(".bulkactionloaderunysncwb").hide();
                                    location.reload();

                                }

                            });
                        }
                    }

                });

            });

        },


        sortdPostActions: function (e) {

            e.preventDefault();
            var id_Attr = $(this).attr('data-popid');
            article.dynamicId = id_Attr;
            var dynamicPath = $(this).attr('data-dynamicpath');
            var data_paoid = $(this).attr('data-data_paoid');
            var paid_price = $(this).attr('data-paid_price');
            var site_url = $(this).attr('data-site_url');
            var admin_url = $(this).attr('data-admin_url');
            var qr_codes = $(this).attr('data-qrcode');
            var post_name = $(this).attr('data-postname');
            var post_title = $(this).attr('data-post_data');
            var host_name = $(this).attr('data-host');
            var paid_article_price = $(this).attr('data-paid_article_price');
            var data_cat = $(this).attr('data-cat');
            var data_str = $(this).attr('data-str');
            var data_action = $(this).attr('data-action');
            var data_post_type = $(this).attr('data-post_type');
            var nonceVal = $("#input_nonce").val();
            var mobile_url = $(this).attr('data-mob_url');
            var desktop_url = $(this).attr('data-desktop_url');
            var wp_domain = $(this).attr('data-wp_domain');
            var project_slug = $(this).attr('data-project_slug');
            var current_user = $(this).attr('data-current_user');

                 let validnonce = $("#nonce_input").val();

            if (typeof gtag === 'function') {
                gtag('event', 'sortd_action', {
                    'sortd_page_title': 'All Post Screen',
                    'sortd_feature': 'Sortd Post Action Pop-up',
                    'sortd_domain': wp_domain,
                    'sortd_project_slug': project_slug,
                    'sortd_user': current_user
                });
            }

            if (data_cat == "" || data_cat == undefined) {
                let newHtml = '';
                newHtml = article.loadModelHtml(nonceVal,id_Attr, data_str, dynamicPath, data_paoid, paid_price, site_url, admin_url, qr_codes, post_name, post_title, host_name, paid_article_price, mobile_url, desktop_url, data_post_type, project_slug, current_user);
                $(".modalclassdynamic").html(newHtml);
            } else if (data_cat == "cat_synced") {
                var newCatHtml = '';
                newCatHtml = article.loadCategoryHtml(id_Attr, site_url, data_paoid, dynamicPath,validnonce)
                $(".modalclassdynamic").html(newCatHtml);
            }


            if (paid_price == undefined || paid_price == '') {

                $(".artc_paid_By_id" + id_Attr).hide();
            }
            //   return false;
            var data404 = $("#qRBoxId").attr('data-imagepath');
            if (data_action == "synced") {
                $("#unsync_" + id_Attr).show();
                $(".sortsyncnotify" + id_Attr).show();
                // $(".edit_price").show();
                if (data_paoid == 1 && paid_price !== '' && data_post_type != 'live-blog') {

                    $(".edit_price").show();
                    $(".artc_paid_By_id" + id_Attr).show();
                } else {

                    $(".edit_price").hide();
                    $(".artc_paid_By_id" + id_Attr).hide();
                }
                $("#sync_" + id_Attr).hide();

            } else {
                $("#unsync_" + id_Attr).hide();
                $(".sortsyncnotify" + id_Attr).hide();
                $(".edit_price").hide();
                $("#sync_" + id_Attr).show();
                $(".artc_paid_By_id" + id_Attr).hide();
                $("#qRBoxId").attr("src", data404);
            }
            $('#myModal_sortdaction' + id_Attr).modal('show');



            $.ajax({

                url: sortd_ajax_obj_article.ajax_url,
                data: {
                    'action': 'get_data_article',
                    'post_id': id_Attr,
                    'sortd_nonce': sortd_ajax_obj_article.nonce
                },
                type: 'post',
                success: function (result) {

                    //console.log(result);return false;
                    let response = JSON.parse(result);
                   
                    $(".imgloadgif").hide();
                    if (response.date == undefined || response.date == '') {
                        $('.btnnotifynot' + id_Attr).css('background', '#8d8d8d');
                        $('.btnnotifynot' + id_Attr).html('No Notification Sent Till Now');
                    } else {
                        $('.btnnotifynot' + id_Attr).html('Last Notification was sent on ' + response.date);
                        $('.btnnotifynot' + id_Attr).css('background', '#c1c1c1');
                    }

                    if (response.is_paid_flag == 1 && data_post_type != 'live-blog') {
                        if (response.old_price == 0 && response.new_price == 0) {
                            $('.paidarticlenotify' + id_Attr).css('background', '#c1c1c1');
                            $('.paidarticlenotify' + id_Attr).html(' Article is Free');
                        } else if (response.old_price == 0 && response.new_price > 0) {
                            $('.paidarticlenotify' + id_Attr).html('Article is paid now');
                            $('.paidarticlenotify' + id_Attr).css('background', '#c1c1c1');
                        } else if (response.old_price > 0 && response.new_price == 0) {
                            $('.paidarticlenotify' + id_Attr).html('Article is free now');
                            $('.paidarticlenotify' + id_Attr).css('background', '#c1c1c1');
                        } else if (response.old_price > 0 && response.new_price > 0) {
                            $('.paidarticlenotify' + id_Attr).html('Article is paid');
                            $('.paidarticlenotify' + id_Attr).css('background', '#c1c1c1');
                        }
                    }

                    if (response.price.length !== 0 && response.status == 'synced') {

                        $('.artc_paid_By' + id_Attr).text(response.price);
                        $(".artc_paid_By").show();

                    } else {

                        $(".artc_paid_By_id" + id_Attr).hide();
                    }

                    if (response.status == 'synced') {

                        $(".showsyncstatus" + id_Attr).text('Synced')
                        $(".sync_" + id_Attr).hide();
                        $("#unsync_" + id_Attr).show();
                        $(".sortsyncnotify" + id_Attr).show();
                        if (response.is_paid_flag == 1 && data_post_type != 'live-blog') {

                            $(".edit_price").show();
                        } else {

                            $(".edit_price").css("display", "none");
                        }

                    } else {
                        $(".showsyncstatus" + id_Attr).text('Unsynced');
                        $(".showsyncstatus" + id_Attr).css('color', "#fa9a3e");
                        $("#sync_" + id_Attr).show();
                        $("#unsync_" + id_Attr).hide();
                        $(".sortsyncnotify" + id_Attr).hide();
                        $(".edit_price").hide();
                    }




                    

                }
                
            });



            $(".cross_modal").click(function () {

                let idAttr = $(this).attr('id');

                let split_array = idAttr.split('close');
                $("#myModal_sortdaction" + split_array[1]).modal('hide');
            });

            $(document).keydown(function (event) {
                if (event.keyCode == 27) {
                    $(".modal" + article.dynamicId).modal('hide');
                    //alert(article.dynamicId);
                    $("#action_sortd_btn" + article.dynamicId).css("box-shadow", '0 0 0 0')
                }

            });
            $('.modalclassdynamic').on('click', '.syncBtn,.syncfailedBtn', article.manualSyncArticle);
            $(".modalclassdynamic").on('click', '.unsyncBtn', article.unsyncArticle);
        },

        restrictChar: function(e){

            var $this = $(this);
            setTimeout(function (e) {
                if (!($this.val()).match(/^[0-9]+$/))
                {   $("#lblError").text("Only Numerical Characters allowed");
                    setTimeout(function (e) {
                        $this.val(''); $("#lblError").text("");
                    },2500);
                }
            }, 5);


        },

        checkInput: function (e) {

            var keyCode = e.keyCode || e.which; //$(this).val();
            $("#lblError").html("");

            //Regex for Valid Characters i.e. Numbers.
            var regex = /^[0-9]+$/;

            //Validate TextBox value against the Regex.
            var isValid = regex.test(String.fromCharCode(keyCode));
            if (!isValid) {
                $("#lblError").html("Only enter round off number");
                $("#publish").attr("disabled", true);
            } else {
                $("#publish").attr("disabled", false);
            }

            return isValid;
        },
        loadModelHtml: function (nonceval,post_id, data_str, dynamicPath, data_paoid, paid_price, site_url, admin_url, qr_codes, post_name, post_title, host_name, paid_article_price, mobile_url, desktop_url, data_post_type, project_slug, current_user) {

            var modelHtml = '';
            modelHtml += ` <!-- Modal -->
                 <div class="sortdPop_actn modal modal${post_id}" id="myModal_sortdaction${post_id}" data-backdrop="static">
                   <div class="modal-dialog">

                     <!-- Modal content-->
                     <div class="modal-content">
                       <div class="modal-header">
                       <div class="action_status">
                               <span class="actn-bx-1"><img src="${dynamicPath}/logo.png"></span>
                               <span class="actn-bx-2"><b>Status</b><span class="s_syn showsyncstatus${post_id}" >Synced</span><span style="display:none;" class="n_syn showunsyncstatus${post_id}">Not Synced</span></span>
                             </div>
                       <h6 class="modal-title"><h6>
                         <button type="button" class="close cross_modal" id="close${post_id}" data-dismiss="modal">&times;</button>
                         <input type="hidden" class="hiddendetials${post_id}" value="${data_paoid}">
                       </div>
                       <div class="modal-body">

                           <div class="action_pop_notify">
                             <div class="article_Cont_inf">
                               <h3>${post_title}</h3>
                                 <img class="imgloadgif" src="${dynamicPath}/load.gif" width="30px" style="margin-right: 20px;">

                                 <div class="notBox btnnotify${post_id}">
                                 <p class="notifyclass btnnotifynot${post_id}"></p>
                                 <p class="paidnotifyclass  paidarticlenotify${post_id}"></p>
                               </div>
                               <span class="artc_paid_By artc_paid_By_id${post_id}" >
                                 <b>Paid Article: <span class="rupeyIcn">₹</span> <span class="artc_paid_By${post_id}">${paid_price}</span></b>
                               </span>
                               <ul class="ulActn">
                                 <li>
                                 <span class="imgIBx">
                                 <div style="float:left;display:none" id="sync_${post_id}">
                                 <button class="def-Btn syncBtn sync_${post_id}" data-is_paid = "${paid_article_price}" data-guid="${post_id}" data-post_type="${data_post_type}" data-siteurl="${site_url}" data-project_slug="${project_slug}" data-current_user="${current_user}" title="Sync the post" data-toggle="tooltip" >
                                 </button></div>
                                 <div style="float:left ;display:none" id="unsync_${post_id}">
                                 <button class="def-Btn unsyncBtn unsyncBtnIcn  unsync_${post_id}" data-guid="${post_id}" data-siteurl="${site_url}" data-project_slug="${project_slug}" data-current_user="${current_user}" title="UnSync the post" data-toggle="tooltip" data-is_paid = "${paid_article_price}">
                                 </button>
                                 </div>
                             </span>

                                 </li>
                                 <li>
                                 <span class="imgIBx">
                                 <span class="popSyUn syncunsyncmsg successsync_${post_id}" style="color:green;"></span>
                                 <div class=" sortsyncnotify${post_id}"><a class="notifyicon " title="Send push notification for article" data-toggle="tooltip"   href="${admin_url}admin.php?page=sortd_notification&post=${post_id}&_wpnonce=${nonceval}"  data-guid="${post_id}" data-siteurl="${site_url}"><b></b></a></div>
                                 <div>
                                 </span>
                                 </li>
                                 <li>
                                 <span class="imgIBx" >
                                   <a title="Edit Price" class="edit_price" href=${site_url}/wp-admin/post.php?post=${post_id}&action=edit ><span class="imgIBx"><img src="${dynamicPath}/rupee.png"></span><b></b></a>
                                   </span>
                                   </li>


                               </ul>



                                <ul class="ulActn-1">
                                 <h5>Share This Post</h5>
                                 <li>
                                   <a href="https://www.facebook.com/sharer/sharer.php?u=${host_name}/article/${post_name}/${post_id}" target = "_blank" ><span class="imgIBx"><img src="${dynamicPath}/facebook.png"></span></a>
                                 </li>
                                 <li>
                                   <a href="https://web.whatsapp.com/send?text=${host_name}/article/${post_name}/${post_id}" target = "_blank"><span class="imgIBx"><img src="${dynamicPath}/whatspp.png"></span></a>
                                 </li>
                                  <li>
                                      <a href="https://twitter.com/intent/tweet?text=${host_name}/article/${post_name}/${post_id}" target = "_blank" ><span class="imgIBx"><img src="${dynamicPath}/Twitter-new-logo.png"></span></a>
                                    </li>
                                    </ul>
                                    <ul class="ulActn-1 copUrl">
                                        <h5>Copy Url To Clipboard</h5>
                                        <li>
                                            <a href="#" class="desktop_url" data-type="desktop" data-url="${desktop_url}"><span class="imgIBx"><img src="${dynamicPath}/computer.png"></span></a>
                                        </li>
                                        <li>
                                            <a href="#" class="mob_url" data-type="mobile" data-url="${mobile_url}"><span class="imgIBx"><img src="${dynamicPath}/mobile.png"></span></a>
                                        </li>
                                        <li class="url_copied" style="color:red;display:none;font-size: 11px; margin-top: 10px;position: absolute;width:100px;"></li>
                                    </ul>
                             </div>
                             <div class="article_Cont_bar">
                               <div class="qRBox">
                                 <span>
                                 <img id="qRBoxId" data-imagepath="${dynamicPath}/not-found.png" style="float:right;" width="150px" height="150px" src="${qr_codes}" title="Link to Demo/Public Host" />
                                 </span>
                                 <b>Scan the QR code to view the article</b>
                               </div>
                             </div>

                           </div>

                       </div>

                     </div>

                   </div>
                 </div>`;



            return modelHtml;
        },
        loadCategoryHtml: function (post_id, site_url, data_paid, css_path,validNonce) {

            console.log(validNonce);
            let catHtml = '';
            catHtml += `	<!-- The Modal -->


                <div class="sortdPop_actn modal modal${post_id}" id="myModal_sortdaction${post_id}">
                <div class="modal-dialog" data-backdrop="static" >

                  <!-- Modal content-->
                  <div class="modal-content">
                    <div class="modal-header">
                    <div class="action_status">
                            <span class="actn-bx-1"><img src="${css_path}/logo.png"></span>
                            <span class="actn-bx-2"><b>Status</b><span class="f_syn showunsyncstatus${post_id}" >Synced Failed</span><span style="display:none;"  class="s_syn showsyncstatus${post_id}">Synced</span></span>
                          </div>
                    <h6 class="modal-title"><h6>
                      <button type="button" class="close cross_modal" id="close${post_id}" data-dismiss="modal">&times;</button>
                      <input type="hidden" class="hiddendetials${post_id}" value="${data_paid}">
                    </div>
                    <div class="modal-body">

                        <div class="action_pop_notify">
                              <div class="article_Cont_inf">
                                <h3>The category of this post is not synced with sortd</h3>
                            </div>
                        </div>

                      <a class="synPop" href="${site_url}/wp-admin/admin.php?page=sortd-manage-settings&section=sortd_manage_categories&action=sync&_wpnonce=${validNonce}">Sync Now</a>
                    </div>
                  </div>

                </div>
              </div>

                `;

            return catHtml;
        },

        rateSortd : function(){



            $.ajax({

                url: sortd_ajax_obj_article.ajax_url,
                data: {
                    'action': 'rate_later',

                    'sortd_nonce': sortd_ajax_obj_article.nonce
                },
                type: 'post',
                success: function (result) {

                   $("#sortd-review-notice").hide();

                }

            });
        },

         showRatePopup : function(){



            $.ajax({

                url: sortd_ajax_obj_article.ajax_url,
                data: {
                    'action': 'show_not_again',

                    'sortd_nonce': sortd_ajax_obj_article.nonce
                },
                type: 'post',
                success: function (result) {

                   $("#sortd-review-notice").hide();

                }

            });
        },

        loadModelHtmlForWebstory: function (nonceval,post_id, data_str, dynamicPath, site_url, admin_url, qr_codes, post_name, post_title, host_name,data_action,hiddenWburl) {


            var modelHtml = '';
            modelHtml += ` <!-- Modal -->
                 <div class="sortdPop_actn modal modal${post_id}" id="myModal_sortdaction${post_id}" data-backdrop="static">
                   <div class="modal-dialog">

                     <!-- Modal content-->
                     <div class="modal-content">
                       <div class="modal-header">
                       <div class="action_status">
                               <span class="actn-bx-1"><img src="${dynamicPath}/logo.png"></span>
                               <span class="actn-bx-2"><b>Status</b><span class="s_syn showsyncstatus${post_id}" >Synced</span><span style="display:none;" class="n_syn showunsyncstatus${post_id}">Not Synced</span></span>
                             </div>
                       <h6 class="modal-title"><h6>
                         <button type="button" class="close cross_modal" id="close${post_id}" data-dismiss="modal">&times;</button>
                         <input type="hidden" class="hiddendetials${post_id}" value="">
                       </div>
                       <div class="modal-body">

                           <div class="action_pop_notify">
                             <div class="article_Cont_inf">
                               <h3>${post_title}</h3>
                               <span><h3 class="wbEmptyTitle${post_id}" style="color:red; display:none;">Title cannot be kept empty<h3></span>
                                 <img class="imgloadgif" src="${dynamicPath}/load.gif" width="30px" style="margin-right: 20px;display:none;">



                               <ul class="ulActn">
                                 <li>
                                 <span class="imgIBx">
                                 <div style="float:left;display:none" id="sync_${post_id}">
                                 <button class="def-Btn syncBtn sync_${post_id}" data-is_paid = "" data-guid="${post_id}"  data-siteurl="${site_url}" title="Sync the post" data-toggle="tooltip" >
                                 </button></div>
                                 <div style="float:left ;display:none" id="unsync_${post_id}">
                                 <button class="def-Btn unsyncBtn unsyncBtnIcn  unsync_${post_id}" data-guid="${post_id}" data-siteurl="${site_url}" title="UnSync the post" data-toggle="tooltip" data-is_paid = "">
                                 </button>
                                 </div>
                             </span>

                                 </li>
                                 <li>
                                 <span class="imgIBx">
                                 <span class="popSyUn syncunsyncmsg successsync_${post_id}" style="color:green;"></span>
                                 <div>
                                 </span>
                                 </li>
                                 <li>

                                   </li>


                               </ul>



                                <ul class="ulActn-1">
                                 <h5>Share This Post</h5>
                                 <li>
                                   <a href="https://www.facebook.com/sharer/sharer.php?u=${hiddenWburl}" target = "_blank" ><span class="imgIBx"><img src="${dynamicPath}/facebook.png"></span></a>
                                 </li>
                                 <li>
                                   <a href="https://web.whatsapp.com/send?text=${hiddenWburl}" target = "_blank"><span class="imgIBx"><img src="${dynamicPath}/whatspp.png"></span></a>
                                 </li>
                                  <li>
                                      <a href="https://twitter.com/intent/tweet?text=${hiddenWburl}" target = "_blank" ><span class="imgIBx"><img src="${dynamicPath}/Twitter-new-logo.png"></span></a>
                                    </li>
                             </div>
                             <div class="article_Cont_bar">
                               <div class="qRBox">
                                 <span>
                                 <img id="qRBoxId" data-imagepath="${dynamicPath}/not-found.png" style="float:right;" width="150px" height="150px" src="${qr_codes}" title="Link to Demo/Public Host" />
                                 </span>
                                 <b>Scan the QR code to view the article</b>
                               </div>
                             </div>

                           </div>

                       </div>

                     </div>

                   </div>
                 </div>`;



            return modelHtml;
        },

        sortdWebstoryActions : function(e){

            e.preventDefault();

            var id_Attr = $(this).attr('data-popid');

           
            article.dynamicId = id_Attr;
            var dynamicPath = $(this).attr('data-dynamicpath');
            var site_url = $(this).attr('data-site_url');
            var admin_url = $(this).attr('data-admin_url');
            var qr_codes = $(this).attr('data-qrcode');
            var post_name = $(this).attr('data-postname');
            var post_title = $(this).attr('data-post_data');
            var host_name = $(this).attr('data-host');
            var data_str = $(this).attr('data-str');
            var data_action = $(this).attr('data-action');
            var nonceVal = $("#input_nonce").val();
            var hiddenWburl = $(this).attr('data-wbURL');

           

            var data_cat = '';

                 let validnonce = $("#nonce_input").val();

                 let newHtml = '';
                 newHtml = article.loadModelHtmlForWebstory(nonceVal,id_Attr, data_str, dynamicPath,  site_url, admin_url, qr_codes, post_name, post_title, host_name,data_action,hiddenWburl);
                    $(".modalclassdynamic").html(newHtml);

                    if(!post_title) {
                        $(".wbEmptyTitle" + id_Attr).show();
                        $(".sync_" + id_Attr).prop("disabled", true);
                        $(".sync_" + id_Attr).css("filter", "grayscale(1)");
                    }

                    if (data_action == "synced") {

                      $(".sync_" + id_Attr).hide();
                      $("#sync_" + id_Attr).hide();
                        $("#unsync_" + id_Attr).show();
                        $(".unsync_" + id_Attr).show();
                        console.log("yessssss");


                        // $(".edit_price").show();

                    //    $("#sync_" + id_Attr).hide();

                    } else {




                        console.log("nooooo");
                        var data404 = $("#qRBoxId").attr('data-imagepath');
                        $("#qRBoxId").attr("src", data404);
                    }

                     $.ajax({

                url: sortd_ajax_obj_article.ajax_url,
                data: {
                    'action': 'get_data_webstory',
                    'post_id': id_Attr,
                    'sortd_nonce': sortd_ajax_obj_article.nonce
                },
                type: 'post',
                success: function (result) {
                    //console.log(result);return false;
                    let response = JSON.parse(result);
                    $(".imgloadgif").hide();
                    if (response.date == undefined || response.date == '') {
                        $('.btnnotifynot' + id_Attr).css('background', '#8d8d8d');
                        $('.btnnotifynot' + id_Attr).html('No Notification Sent Till Now');
                    } else {
                        $('.btnnotifynot' + id_Attr).html('Last Notification was sent on ' + response.date);
                        $('.btnnotifynot' + id_Attr).css('background', '#c1c1c1');
                    }



                    if (response.status == 'synced') {

                        $(".showsyncstatus" + id_Attr).text('Synced')

                        $(".sync_" + id_Attr).hide();
                        $("#sync_" + id_Attr).hide();
                          $("#unsync_" + id_Attr).show();
                          $(".unsync_" + id_Attr).show();

                      //  $("#unsync_"+id_Attr).show();
                      //  $(".unsync_" + id_Attr).show();
                        //$("#unsync_" + id_Attr).show();



                    } else {
                        $(".showsyncstatus" + id_Attr).text('Unsynced');
                        $(".showsyncstatus" + id_Attr).css('color', "#fa9a3e");
                      //  $("#sync_" + id_Attr).show();
                    //   $(".sync_" + id_Attr).show();

                    $("#unsync_" + id_Attr).hide();
                    $(".unsync_" + id_Attr).hide();
                      $(".sync_" + id_Attr).show();
                      $("#sync_" + id_Attr).show();



                    }







                }

            });



                     $('#myModal_sortdaction' + id_Attr).modal('show');



                      $(".cross_modal").click(function () {

                            let idAttr = $(this).attr('id');

                            let split_array = idAttr.split('close');
                            $("#myModal_sortdaction" + split_array[1]).modal('hide');
                        });



                $(document).keydown(function (event) {
                    if (event.keyCode == 27) {
                        $(".modal" + article.dynamicId).modal('hide');
                        //alert(article.dynamicId);
                        $("#action_sortd_btn" + article.dynamicId).css("box-shadow", '0 0 0 0')
                    }

                });
                $('.modalclassdynamic').on('click', '.syncBtn,.syncfailedBtn', article.syncWebstory);
                $(".modalclassdynamic").on('click', '.unsyncBtn', article.unysncWebstory);



        },
        copyToClipboard : function(e) {
            e.preventDefault();
            let url = $(this).attr('data-url');
            let type = $(this).attr('data-type');
            navigator.clipboard.writeText(url);
            if(type === "desktop") {
                $(".url_copied").text("Desktop URL Copied");
                $(".url_copied").show();
				setTimeout(function () {
					$('.url_copied').fadeOut(500);
				}, 2000);
            } else {
                $(".url_copied").text("Mobile URL Copied");
                $(".url_copied").show();
				setTimeout(function () {
					$('.url_copied').fadeOut(500);
				}, 2000);
            }
        }






    }






    $("#meta-box-text").keypress(article.checkInput);
    $(document).on("click", ".action_sortd_btn", article.sortdPostActions);

    $(document).on("click", ".webstory_action", article.sortdWebstoryActions);

    // $(".action_sortd_btn").click(article.sortdPostActions)
    $(".sortdbulkaction").on('click', article.bulkSyncArticles);
    $(".sortdbulkactionunsync").on('click', article.bulkUnsyncArticles);

    $(".closeiconsync").on('click', function () {
        $(".bulksortdaction").hide();
    });

    $(".closeiconunsync").on('click', function () {
        $(".bulksortdactionunysnc").hide();
    });

   // $(".unsyncBtnIcnWebstory").on('click', article.unysncWebstory);
   // $(".syncBtnWebstory").on('click', article.syncWebstory);

    $(".bulksyncwb").on('click', article.bulkSyncWebstories);
    $(".bulkunsyncwb").on('click', article.bulkUnsyncWebstories);
    $('#meta-box-text').on('paste', article.restrictChar);
    $("#sortd-later").click(article.rateSortd)

    $("#sortd-no-rate").click(article.showRatePopup);
    $(document).on("click", ".desktop_url", article.copyToClipboard);
    $(document).on("click", ".mob_url", article.copyToClipboard);







})(jQuery);
