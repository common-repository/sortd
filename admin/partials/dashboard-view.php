<?php

/**
 * Provide a oneclick process - setup view for the plugin
 *
 * This file is used to markup the oneclick process - setup of the plugin.
 *
 * @link       https://www.sortd.mobi
 * @since      2.0.0
 *
 * @package    Sortd
 * @subpackage Sortd/admin/partials
 */
?>
<?php if ( ! defined( 'ABSPATH' ) ) exit;  ?> 
<style>
   fieldset.scheduler-border {
   border: 1px groove #ddd !important;
   padding: 0 1.4em 1.4em 1.4em !important;
   margin: 0 0 1.5em 0 !important;
   -webkit-box-shadow:  0px 0px 0px 0px #000;
   box-shadow:  0px 0px 0px 0px #000;
   }
   legend.scheduler-border {
   font-size: 1.2em !important;
   font-weight: bold !important;
   text-align: left !important;
   width:auto;
   padding:0 10px;
   border-bottom:none;
   }
   .sortCard{
      font-family: 'Barlow', sans-serif;
   }
   .sendPushNotifications{
   text-align: center;
   }
   .swal2-popup {
   font-size: 12px!important;
   font-family: 'Barlow', sans-serif;
   }
   .activePage{
   background-color:#87CEFA;
   }
   .pagination{
   display: flex;
   justify-content: center;
   }
   .scrollit{
   overflow:scroll;
   height:100px;
   }
   .statsheading{
   display: flex;
   justify-content: center;
   }
   .statsdef{
   margin-top: 10px;
   }
   .datachartli{
   display: flex;
   justify-content: center;
   }
   .noarticlefoundclass{
   margin-top:50px;
   text-align: center;
   font-size: 17px;
   font-family: 'Barlow', sans-serif;
   }
   .articlepromotionclass{
   font-size: 10px;
   }
   .sync_cat_label, .sync_article_label,.minute_label {
      display: none;
      padding: 10px 0px;
      color: #333
   }
   .sync_cat_result, .sync_article_result{
      display: none;
      padding: 10px 0px 10px;
      color: #005BF0;
      background: #fff;
    margin-bottom: 10px;
    padding-left: 0px;
    font-size: 15px;
    font-family: 'Barlow', sans-serif;
    width: 100%;
    float: left;
   }
   .sync_article_label{
    width: 100%;
    float: left;
   }
   .sync_cat_result {
      margin-top: 30px
   }
   .sync_article_result{
    margin-bottom: 30px;
    background: whitesmoke;
    padding-left: 15px;
    border: 1px solid #ccc;
    border-radius: 10px;
}
.sync_cat_result{
    margin-top: 30px;
    padding-left: 15px;
    background: whitesmoke;
    border: 1px solid #ccc;
    border-radius: 10px;
}

   .setupAction {
    float: left;
    width: 100%;
    text-align: center;
    z-index: 999;
    box-shadow: rgb(226 226 226) -1px 1px 14px 0px;
    animation: 1s ease-out 0s 1 normal none running zoom-in-zoom-out;
    background: #fff;
    height: 300px;
    overflow: hidden;
    margin-top: 30px
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
/*.onclick_setup_dv .one_click{
    border: 1px solid #fff !important;
    background: transparent;
}*/
.onclick_setup_dv .one_click:hover{
  background: #0b4ec1 !important
  }
/*#imgscreenshot_iframe{
   width: 100%;
    height: 100vh;
    border: 1px solid #ddd;
}*/

.prgDiv .progress{
   width: 100%;
}

.custom_linkfor_dashbrd {
    width: 200px;
    float: left;
    padding: 26px 0 0 24px;
    background: #fff !important;
    border: 1px solid #333 !important;
    color: #333 !important;
    padding: 5px 20px !important;
    margin-right: 15px;
}
.custom_linkfor_dashbrd i{
  color: #333 !important
}

.previewload{
   float: right;
}

/*--- RIBON CSS --*/

@keyframes confetti-slow {
  0% {
    transform: translate3d(0, 0, 0) rotateX(0) rotateY(0);
  }
  100% {
    transform: translate3d(25px, 105vh, 0) rotateX(360deg) rotateY(180deg);
  }
}
@keyframes confetti-medium {
  0% {
    transform: translate3d(0, 0, 0) rotateX(0) rotateY(0);
  }
  100% {
    transform: translate3d(100px, 105vh, 0) rotateX(100deg) rotateY(360deg);
  }
}
@keyframes confetti-fast {
  0% {
    transform: translate3d(0, 0, 0) rotateX(0) rotateY(0);
  }
  100% {
    transform: translate3d(-50px, 105vh, 0) rotateX(10deg) rotateY(250deg);
  }
}


.confetti-container {
  perspective: 700px;
  position: absolute;
  overflow: hidden;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
}

.confetti {
  position: absolute;
  z-index: 1;
  top: -10px;
  border-radius: 0%;
}
.confetti--animation-slow {
  animation: confetti-slow 2.25s linear 1 forwards;
}
.confetti--animation-medium {
  animation: confetti-medium 1.75s linear 1 forwards;
}
.confetti--animation-fast {
  animation: confetti-fast 1.25s linear 1 forwards;
}
.container_confetti{
      position: fixed !important;
    width: 100%;
    height: 100vh;
    top: 0px;
    right: 0px;
    display: none;
}

.afterStartSetup{
    width: 100%;
    float: left;
}

/*-- RBON CSS END ----*/

</style>
<h1>Dashboard View</h1>
<div class="content-section">
<div class="container-pj">
  
   <div class="row">
      <div class="col-md-12">
         <div class="heading-main">
            <div class="logoLft">
               <img src="<?php echo wp_kses_data(SORTD_CSS_URL);?>/logo.png">
              <h5>Setup PWA & AMP</h5>
            </div>
         <!--    <div class="headingNameTop">
               <h2></h2>
            </div> -->
         </div>
      </div>
   </div>

      <div class="row">
         <div class="col-md-12 mt-30">
            <div class="sortCard onClik_lft">

                <div class="afterStartSetup startsetup">
                  <div class="onclick_setup_dv">
                    <h1>Your new advanced mobile site is just 1 - Click away !</h1>
                    <h2 class="syHead">We will sync your recent articles & categories from Wordpress through our one - click setup wizard. <br>  <br> What data will be synced for setup : </h2>

                    <div class="postInf">
                      <ul>
                        <li><span class="postN">Posts <b>:</b></span><span class="postD"><?php echo wp_kses_data($article_quota); ?></span></li>
                        <li><span class="postN">Categories <b>:</b></span><span class="postD"><?php echo wp_kses_data($category_quota); ?></span></li>
                        <li><span class="postN">Media <b>:</b></span><span class="postD">Related</span></li>
                      </ul>
                    </div>

                    <div class="fulW">
                    <input type="hidden" id="category_quota" name="category_quota" value="<?php echo esc_attr($category_quota); ?>">
                    <input type="hidden" id="article_quota" name="article_quota" value="<?php echo esc_attr($article_quota); ?>">
                    <input type="hidden" id="date_till" name="date_till" value="<?php echo esc_attr($date_till); ?>">
                    <input type="hidden" id="site_url_hidden" name="site_url_hidden" value="<?php echo esc_attr(site_url()); ?>">
                      <button type="button" class="btn btn-ad-blu one_click" data-siteurl="<?php echo wp_kses_data(site_url());?>">Start Now <i class="bi bi-arrow-right-circle-fill"></i></button>
                    </div>
                  </div>
                  
                  <!-- mob Device -->
                  <div class="mobOnclick">
                    <div class="mobWt">
                      <div class="logMob">
                        <span class="logWmob">

                            <?php 

                              if ( has_custom_logo() ) {
                                $logo = wp_get_attachment_image_src( get_theme_mod( 'custom_logo' ), 'full' )[0]; 

                                    if(!empty($logo)){
                                        $logo = $logo;
                                    } 

                                  }else {

                                        if(function_exists( 'tie_logo_args' ) ) {
                                           $logo_args  = tie_logo_args();
                                           $logo = $logo_args['logo_img'];
                                        } elseif (class_exists('td_util')){

                                        if(method_exists('td_util','get_option')) {

                                           $td_customLogo = td_util::get_option('tds_logo_upload');

                                           $logo = $td_customLogo;

                                         }
                                        }else {
                                           $logo = SORTD_CSS_URL.'/logo.png';
                                        }
                                 
                                }
                            ?>


                        <img src="<?php echo wp_kses_data($logo);?>">
                        
                        </span>
                        <span class="rIcn">
                          <img src="<?php echo wp_kses_data(SORTD_CSS_URL);?>/graph.png">
                        </span>
                      </div>
                      <span class="strip-line"></span>
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

               <div class="afterStartSetup inprogresssetup" style="display:none">
                  <div class="onclick_setup_dv">
                    <h1>1-click Started..</h1>
                    <h2 class="syHead">Please wait for some time as it may take few minutes.</h2>
                   
                    <!-- sync section start -->
                  <div class="minute_label"><img src="<?php echo wp_kses_data(SORTD_CSS_URL);?>/load.gif" width="30px" style="margin-right: 20px">This might take few minutes...</div>

                  <div class="sync_cat_label"><img src="<?php echo wp_kses_data(SORTD_CSS_URL);?>/load.gif" width="30px" style="margin-right: 20px">Syncing categories...</div>

                  <div class="sync_cat_result ">14 Categories have been synced to SORTD</div>

                  

                  <div class="sync_article_label"><img src="<?php echo wp_kses_data(SORTD_CSS_URL);?>/load.gif" width="30px" style="margin-right: 20px">Syncing articles...</div>

                  <div class="sync_article_result ">100 Articles have been synced to SORTD</div>

                  <div class="prgDiv" style="display:none;">
                      <div class="progress">
                        <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:0%"></div>
                      </div>
                  </div>

                  <div class="skiporcontinue" style="display:none;float:right;margin:10px">
                      <div class="row">
                        <div class="col-sm-5">
                          <button type="button" class="btn btn-secondary btn-sm retrysync">Retry</button>
                        </div>
                        <div class="col-sm-5">
                          <button type="button" class="btn  btn-primary btn-sm skipfornow">Skip</button>
                        </div>
                    </div>
                  </div>

                  <div class="infoAlrt" style ="display:none"><img src="<?php echo wp_kses_data(SORTD_CSS_URL);?>/load.gif" width="30px" style="margin-right: 20px">PWA & AMP is being setup...</div>
                  
                  <input type="hidden" name="sitetitle" id="sitetitle" value="<?php echo wp_kses_data(get_bloginfo( 'name' ));?>">
                  <input type="hidden" name="siteurl" id="siteurl" value="<?php echo wp_kses_data(site_url());?>">

                   <input type="hidden" name="sitedescription" id="sitedescription" value="<?php echo wp_kses_data(get_bloginfo( 'description' ));?>">
                    <!-- sync section end -->

                  </div>
                  <div class="mobOnclick">
                    <div class="mobWt">
                      <img class="lGif" src="<?php echo wp_kses_data(SORTD_CSS_URL);?>/loader-n.gif">
                    </div>
                  </div>

               </div>


               


                <!-- congrats section -->

                <div class="afterStartSetup finalsetup" style="display:none">
                  <div class="onclick_setup_dv">
                    <h1>1 - Click Setup Completed.</h1>
                    
                  </div>


                    <div class="setupAction bdr2" style="display:none">
                      <div class="msgbox oneSetCongrt">
                        
                        <h1>Congratulations !!</h1>
                        <h2>Your PWA + AMP project has been setup. </h2>

                        <div class="userActnBtn">

                           <a class="btn-ad-blu custom_linkfor_dashbrd" href="<?php echo wp_kses_data(wp_nonce_url(admin_url()."admin.php?page=sortd-settings"));?> ">Dashboard <i class="bi bi-grid-fill"></i></a>

                           <a class=" btn-ad-blu" href="<?php echo wp_kses_data($project->data->domain->demo_host);?>" target = "_blank" onclick="window.open(this.href,'targetWindow',
                                   `toolbar=no,
                                    location=no,
                                    status=no,
                                    menubar=no,
                                    scrollbars=yes,
                                    resizable=yes,
                                    width=360px,
                                    height=500px`);return false;">View Demo <i class="bi bi-box-arrow-up-right"></i></a>

                              <a class="btn-ad-blu speedBtn" href="https://pagespeed.web.dev/report?url=<?php echo wp_kses_data($project->data->domain->demo_host);?>" target="_blank"><img width="35px" src="<?php echo wp_kses_data(SORTD_CSS_URL);?>/favicon_48.png"> Measure Page Speed <i class="bi bi-box-arrow-up-right"></i></a>

                        </div>
                         
                        
                      </div>
                      <div class="prvBox" >
                        <span class="spanpreviewloader" style="display: none;">Please Wait...</span>
                        <img width="35px" src="<?php echo wp_kses_data(SORTD_CSS_URL);?>/load.gif" id="loader" style="display:none;">
                      </div> 
                      <div class="previewnot" style="color:red;"></div>

                       <!-- mob Device -->
                      <div class="mobOnclick">
                        <div class="mobWt extremebluebg">

                          <?php
                            $normal_demourlwp = wp_kses_data($project->data->domain->demo_host);
                            $demobase_encoded = base64_encode($normal_demourlwp);
                          ?>

                          <img class="mobWt_qrcodeimage" src="<?php echo wp_kses_data($project->data->domain->demo_host);?>/sortd-service/qrcode/v22-01/small?url=<?php echo wp_kses_data($demobase_encoded); ?>">

                          <span class="mobWt_textofqr"> Scan to view demo </span>
                          
                        </div>
                      </div>
                      <!-- mob Device end -->
                  
                    </div>
                </div>
                 
            </div>
         </div>
      </div>
      <div class="js-container container_confetti"></div>
   </div>
</div>

<script>
  const Confettiful = function(el) {
  this.el = el;
  this.containerEl = null;
  
  this.confettiFrequency = 3;
  this.confettiColors = ['#fce18a', '#ff726d', '#b48def', '#f4306d'];
  this.confettiAnimations = ['slow', 'medium', 'fast'];
  
  this._setupElements();
  this._renderConfetti();
};

Confettiful.prototype._setupElements = function() {
  const containerEl = document.createElement('div');
  const elPosition = this.el.style.position;
  
  if (elPosition !== 'relative' || elPosition !== 'absolute') {
    this.el.style.position = 'relative';
  }
  
  containerEl.classList.add('confetti-container');
  
  this.el.appendChild(containerEl);
  
  this.containerEl = containerEl;
};

Confettiful.prototype._renderConfetti = function() {
  this.confettiInterval = setInterval(() => {
    const confettiEl = document.createElement('div');
    const confettiSize = (Math.floor(Math.random() * 3) + 7) + 'px';
    const confettiBackground = this.confettiColors[Math.floor(Math.random() * this.confettiColors.length)];
    const confettiLeft = (Math.floor(Math.random() * this.el.offsetWidth)) + 'px';
    const confettiAnimation = this.confettiAnimations[Math.floor(Math.random() * this.confettiAnimations.length)];
    
    confettiEl.classList.add('confetti', 'confetti--animation-' + confettiAnimation);
    confettiEl.style.left = confettiLeft;
    confettiEl.style.width = confettiSize;
    confettiEl.style.height = confettiSize;
    confettiEl.style.backgroundColor = confettiBackground;
    
    confettiEl.removeTimeout = setTimeout(function() {
      confettiEl.parentNode.removeChild(confettiEl);
    }, 3000);
    
    this.containerEl.appendChild(confettiEl);
  }, 25);
};

window.confettiful = new Confettiful(document.querySelector('.js-container'));



</script>