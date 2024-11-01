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
.contact-info {
    width: 100%;
    float: left;
}
.contact-info h1 {
    font-family: 'Barlow';
    font-weight: 600;
    margin-top: 50px;
    margin-bottom: 20px;
    font-size: 40px;
}
.contact-info h6 {
    color: #909090;
    font-weight: 400;
    font-family: 'Barlow';
    font-size: 20px;
    margin-bottom: 70px;
}
.contact-info h1 span {
    color: #3600e3;
    /*font-size: 76px;*/
}
.cont-Form{
  width: 100%;
  float: left;
  border-left:1px solid #ccc ;
  height: 300px;
}

.gtStrt {
    color: #0660f0;
    float: left;
    font-family: 'Barlow';
    font-weight: 500;
    text-decoration:none;
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
                   <h5>Speed up Mobile Experience </h5>
                </div>
                <div class="headingNameTop">
                <button type="button" onclick="window.open('https://support.sortd.mobi/portal/en/kb/gni-adlabs/general','_blank')" class="btn infoIcn icPd0" title="AdLabs Support"><i class="bi bi-info-circle"></i></button>
                </div>
             </div>
          </div>
      </div>

      <div class="row ">
        <div class="col-md-12 ">
          <div class="content-card mt-30">
            <div class="row">
              <div class="col-md-6">
                <div class="contact-info">
                  <h1><span>Speed up</span> your mobile Experience</h1>
                  <h6>Let us help you analyse the wordpress setup & provide you the best solution possible.</h6>
                  <a class="gtStrt" target="_blank" href="<?php echo wp_kses_data($console_url);?>/plans/buyplan/<?php echo wp_kses_data($slug);?>">Get Started Right Now!</a>
                </div>
                
              </div>
              <div class="col-md-6">
                <div class="cont-Form">
  
                <ins id="sortd-formdisplay-widget"> </ins>
                    <?php
                    $script_url = "https://ads.rwadx.com/sortdformdsiplay.js";

                    wp_enqueue_script('sortdformdisplay', $script_url,array() ,null);
                    ?>
              
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
  </div>
</div>

     