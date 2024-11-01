<?php 

?>

<div class="content-section">
   <div class="container-pj">
      <div class="row">
         <div class="col-lg-12">
            <div class="sortCard cardslabel">

                 <!-- Modal HTML -->
            <div id="myModal" class="modal fade" tabindex="-1">
               <div class="modal-dialog">
                     <div class="modal-content">
                     
                        <div class="modal-header">
                           <h5 class="modal-title">Mark Free Paid Articles</h5>
                           <button type="button" class="close closepaid" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                        <div class="loadgif" style="display:none" >
                           <span class="spanpreviewloader">Please Wait...</span>
                           <img width="35px" src="<?php echo wp_kses_data(SORTD_CSS_URL);?>/load.gif" id="loader">
                        </div> 
                           <p id="paidarticleCountmsg" ></p>
                        </div>
                        <div class="modal-footer">
                           <button type="button" class="btn btn-secondary cancelBtnPaidAction" data-dismiss="modal">Cancel</button>
                           <button type="button" class="btn btn-primary saveBtnPaidAction">OK</button>
                        </div>
                     </div>
               </div>
            </div>
               <form>
                  <!--   <fieldset class="scheduler-border">
                     <legend class="scheduler-border">Recently Sent Notifications</legend> -->
                  <div class="row">
                     <div class="col-lg-6">
                        <h5 class="sortHed m-b20">Paid Articles</h5>
                     </div>  
                     <div class="col-lg-6">
                     <?php if(get_option('sortd_'.$project_id.'_redirection_code') === false) { ?>
                        <div class="enbl_redirect">
                           <a class="butn-df" href="<?php echo esc_url(wp_nonce_url(wp_kses_data(admin_url().'admin.php?page=sortd-manage-settings&section=sortd_redirection'),SORTD_NONCE));?>">Enable Redirection</a>
                        </div>
                        <?php } ?> 
                     </div> 
                   


                     <div class="paid_art_search">
                        <div class="row">
                           <div class="col-md-6">
                              <label class="pure-material-textfield-outlined">
                                 <input type="text" placeholder="" id= "titleFilter" type="text" name="" value='' aria-describedby="emailHelp">
                                 <span>Enter Title</span>
                              </label>
                             <!--  <input type="text" class="form-control" id="titleFilter" aria-describedby="emailHelp" placeholder="Enter Title"> -->
                           </div>
                           <div class="col-md-6 selctPaid_catg">
                           
                           <select  class=" selectpicker"  title="Select Category" multiple data-live-search="true" id="categoryBasedFilter">
                           <span>Enter Title</span>
                              <?php if(sizeof($cat_array) !== 0) { 
                                       foreach($cat_array as $ck => $cv) {  ?>
                              <option value="<?php echo wp_kses_data($cv['id']);?>" ><?php echo wp_kses_data($cv['name']);?></option>
                              <?php } 
                                 } else { ?>
                                    
                                    <option >No categories found</option>

                              <?php  }
                              ?>
                             
                           </select>
                           
                           </div>
                           <div class="col-md-12">
                              <div class="rangBox" style="margin-right:2%">
                                 <label class="pure-material-textfield-outlined">
                                    <input type="number"  min="1" placeholder="" id= "priceFromFilter" type="number" name="" value='' aria-describedby="emailHelp">
                                    <span>Price From</span>
                                 </label>
                                 <span id="numberspan" style="display:none;color:red"></span>
                                 <!-- Range From<input type="number" class="form-control" id="priceFromFilter" aria-describedby="emailHelp" placeholder="Price"> -->
                              </div>
                              <div class="rangBox">
                                 <label class="pure-material-textfield-outlined">
                                    <input type="number" min="1" placeholder="" id= "priceToFIlter" type="number" name="" value='' aria-describedby="emailHelp">
                                    <span>Price To</span>
                                 </label>
                                 <span id="numberspanto" style="display:none;color:red"></span>
                                 <!-- Range To<input type="number" class="form-control" id="priceToFIlter" aria-describedby="emailHelp" placeholder="Price"> -->
                              </div>
                           </div>
                           <div class="col-md-12">
                           <button type="button" class="btn btn-ad-dflt resetBtn">Reset</button>
                              <button type="button" class="btn btn-ad-dflt filterBtn">Search</button>
                              
                           </div>

                        </div>
                     </div>
                     <div class="col-md-12">
                     <?php if($markflag === '1'){ ?> 
                        <div class="alert alert-success get_success_mark_free"  role="alert">
                           Articles successfully marked as free!
                        </div>
                        <?php } update_option('sortd_'.$project_id.'_markfreeflag',0) ;?>
                     <?php if(!empty($paid_articles_data) && isset($message)){ ?> 
                        <p class="enBl_Msg">
                           <?php echo wp_kses_data($message);?>
                        </p>
                     </div>
                     <?php } ?>
                     <p id="validationmsg" style="color:red;display:none;"></p>
                  </div>

                  <!-- 
                     <table class="table"><thead class="text-primary"></thead><tbody><td colspan="4" class="text-center"><h4 class="card-title">No Notifications Found</h4></td></tbody></table> -->
                  <table id="table" class="display table paidArtTbl" style="width:100%">
                     <?php
                        if(empty($paid_articles_data )) { ?>
                     <thead class="text-primary"></thead>
                     <tbody>
                        <td colspan="4" class="text-center">
                           <h5 class = "noarticlefoundclass">No Articles Found</h5>
                        </td>
                     </tbody>
                     <?php } else { ?>
                     <thead class="bgHed" >
                        <tr>
                           <th><input type="checkbox" name="" id="allSelectPaidArticles"></th>
                           <th class="headth">Title</th>
                           <th class="headth">Category</th>
                           <th class="headth">Price</th>
                           <th class="headth">Edit</th>
                        </tr>
                     </thead>
                     <tbody id="getlist">
                        <?php 
                        $date_format = get_option('date_format').' '.get_option('time_format');
                        
                        
                        foreach($paid_articles_data as $not_key => $paid_article_value){ ?>
                        <tr>
                           <td><input type="checkbox" name="paid_flag[]" value="<?php echo wp_kses_data($paid_article_value['post_id']); ?>"></td>
                           <td><a target="_blank" href="<?php echo wp_kses_data($paid_article_value['url']);?>"><?php echo wp_kses_data($paid_article_value['title']);?></a></td>
                           <td><?php echo wp_kses_data($paid_article_value['categories']);?></td>
                           <td><span>&#8377;</span> <?php echo wp_kses_data($paid_article_value['paid_price']);?></td>
                           <td><a target="_blank" href="<?php echo wp_kses_data(admin_url().'post.php?post='.$paid_article_value['post_id'].'&action=edit');?>"><i class="bi bi-pencil-square"></i></a></td>
                         
                        </tr>
                        <?php } ?>

                         
                     <?php   } ?>
                     </tbody>
                  </table>
               
                  <?php if(!empty($paid_articles_data )) { ?>
                  <ul> 
                     <li>
                        <span id="articlesSelectedCount"></span>
                     </li> 
                     <li>
                        <button id="bulk_mark_free">Mark as free</button>
                        <span id="paid_flag_warning" style="color:red;display:none;">Select articles to mark as free</span>
                     </li>
                  </ul>
                  <ul class="pagination sortPag">
                
                     <li class="page-item">
                        <a class="page-link" id="previous" href="javascript:void(0);" aria-label="Previous">
                        <span aria-hidden="true">&laquo;</span>
                        <span class="sr-only">Previous</span>
                        </a>
                     </li>
                     <?php $pages_count = ceil($count_posts/10); 
                     
                        for($i = "1"; $i <= $pages_count ;$i++){ ?>
                     <li class="page-item"><a class="page-link" id="page<?php echo wp_kses_data($i);?>" data-page="<?php echo wp_kses_data($i);?>" href="javascript:void(0);"><?php echo wp_kses_data($i);?></a></li>
                     <?php } ?>
                     <input type ="hidden" id="pagecount" value="<?php echo wp_kses_data($pages_count);?>">
                     <input type ="hidden" id="postscount" value="<?php echo wp_kses_data($count_posts);?>">
                     <li class="page-item">
                        <a class="page-link" id="next" href="javascript:void(0);" aria-label="Next">
                        <span aria-hidden="true">&raquo;</span>
                        <span class="sr-only">Next</span>
                        </a>
                     </li>
                  </ul>
                  <?php } else { ?>
                     <div class="mesnotify"></div>
                  <?php } ?>
                  </fieldset>
               </form>
            </div>
         </div>
      </div>
   </div>
</div>

