<?php 
if ( ! defined( 'ABSPATH' ) ) exit; 

	$custom_logo_id = get_theme_mod( 'custom_logo' );
	$image = wp_get_attachment_image_src( $custom_logo_id , 'full' );

   $url = site_url();
	//$resultog= read_og_tags_as_json($url);



	//echo "<pre>";print_r(($projectDomains));die;


?>

<style type="text/css">
  .floatBtn {
    position: fixed;
    right: 0px;
    bottom: 30px;
    z-index: 999
}
.btn-contact {
    margin-left: 20px;
    display: inline-block;
    text-transform: capitalize;
    font-size: 14px;
    font-weight: 500;
}
</style>
   
          <?php if(isset($responseApi->error) ||  isset($responseApi->error)) { ?>
             <div class="notice notice-error is-dismissible curlErrorDiv">
           <p><?php echo esc_attr($responseApi->error->message);?></p>
            </div> 
          <?php if(isset($responseApi->error->errorCode) && $responseApi->error->errorCode == 403) { update_option('sortd_credentials','');} }  ?>
       
<a  class="btn btn-ad-blu floatBtn" href="https://www.sortd.mobi/" target="_blank">Get Help <i class="bi bi-question-circle"></i></a>
   <div class="content-section">
      <div class="container-pj">
         <div class="row">

         </div>

         <div class="row">
            <div class="col-md-12 mt-30">
              <div class="sortCard ">
                  <div class="sortdWizrd">
                    <div class="logoLft">
                       <img src="<?php echo plugin_dir_url( __DIR__ );?>css/logo.png">
                    </div>
                     <h5 class="sortHed-1">
                        <span style="font-size:4em;"> Welcome</span></h5>
                        <p>SORTD helps you create a beautiful mobile website. It's fast, feature-rich and easy to manage. Your mobile website will be a progressive web application (PWA) and support accelerated mobile pages (AMP) for every post created
                        </p>

                        <form action="https://console.sortd.mobi/onboard/get-started" method="post" id="getstartedform">
                          <input type="hidden" name="site_title" class="sortdmetatitle" value="<?php echo get_bloginfo('name');?>">
                          <input type="hidden"  name="site_description" class="sortdmetadescription" value="<?php echo get_bloginfo('description');?>">
                          <input type="hidden"   name="site_favicon" class="favicon" value="<?php echo get_site_icon_url();?>">
                            <input type="hidden"   name="language" class="sortdlocale" value="<?php echo get_bloginfo('language');?>">
                          
                          <input type="hidden"   name="timezone" class="sortdtimezone" value="<?php echo get_option('timezone_string'); ?>">
                            <input type="hidden"   name="admin_email" class="admin_email" value="<?php echo  bloginfo('admin_email'); ?>">
                          <input type="hidden"  name="wp_plugin_url" class="sortdpluginurl" value="https://www.sortd.mobi/">
                          <input type="hidden"   name="wp_hosted_site_url" class="siteurlwordpress" value="<?php echo site_url();?>">
                          <input type="hidden"   name="logo_url" class="logourl" value="<?php echo esc_attr($image[0]);?>">
                            <input type="hidden"   name="callback_url" class="logourl" value="<?php echo site_url();?>/wp-admin/admin.php?page=sortd_credential_settings">

                          <input type="hidden"  name="og_data" class="og_data" value= '<?php if(isset($resultog)) { echo esc_attr($resultog);} ?>'>
                          
                      
                          <button type="submit"  class="btn btn-ad-blu">Get Started <i class="bi bi-arrow-right-circle-fill"></i></button>
                          <!-- <a class=" btn-contact" target="_blank" href="https://www.sortd.mobi/contact">talk to Us </a> -->

                        </form>

                  </div>   
                  <div class="videoSecn" style="-webkit-mask-box-image: url(<?php echo plugin_dir_url( __DIR__ );?>/css/Vector77.svg);">

                     <div class="text_animation_sortd">
                        <div class="wrapper">
                           <div class="static-txt">I'm</div><br>
                           <ul class="dynamic-txts">
                              <li><span>a Publisher.</span></li>
                              <li><span>Sortd.</span></li>
                              <li><span>a Developer.</span></li>
                              <li><span>Sortd.</span></li>
                           </ul>
                        </div>
                     </div>

                  </div>
              </div>
            </div>

            <div class="col-md-12 mt30">
              <div class="sortCard ">
                <h4 class="smHed">You are 3 steps away:</h4>

                <ul class="step_3">
                  <li>Register & Create Project</li>
                  <li>Sync with One Click.</li>
                  <li>Choose theme & Go live !</li>
                </ul>
              </div>
            </div>

            <div class="col-md-6">
              <div class="dashImg">
                 <img src="<?php echo plugin_dir_url( __DIR__ );?>css/manage_sortd_1.png">
              </div>
            </div>
            <div class="col-md-6">
              <div class="dashCont txtrt">
                <h2>Register & Create Project</h2>
                <p>Register on SORTD & create a project to design your new mobile website.</p>
              </div>
            </div>

            <div class="col-md-6">
              <div class="dashCont">
                <h2>Sync with one-click</h2>
                <p>Sync your categories & posts from WordPress to SORTD with one-click. You can choose to add or remove categories later.
</p>
              </div>
            </div>
            <div class="col-md-6">
              <div class="dashImg txtrt">
                 <img src="<?php echo plugin_dir_url( __DIR__ );?>css/manage_sortd_2.png">
              </div>
            </div>

            <div class="col-md-6">
              <div class="dashImg">
                 <img src="<?php echo plugin_dir_url( __DIR__ );?>css/manage_sortd_3.png">
              </div>
            </div>
            <div class="col-md-6">
              <div class="dashCont txtrt">
                <h2>Choose theme & Go Live</h2>
                <p>You can choose the theme of your mobile website from the themes curated & designed beautifully by our designers. You can edit the theme later as per your imagination, suited for your brand.</p>
              </div>
            </div>
        </div>
            
      </div>
   </div>

  <div class="content-section foter_Sortd">
    <div class="container-pj">
          <div class="row">
              <div class="col-md-4">
                <div class="foter_cont">
                  <img src="<?php echo plugin_dir_url( __DIR__ );?>css/logo.png">

                  <ul>
                    <li>Complete Mobile Solution </li>
                    <li>Redefining your Mobile Content Distribution Strategy</li>
                    <li>Copyright © 2021 by Sortd All rights reserved</li>
                  </ul>
                </div>
              </div>
              <div class="col-md-4">
                <div class="foter_cont">
                  <h4>Solutions</h4>
                  <ul>
                    <li><a target="_blank" href="https://www.sortd.mobi/pwa-amp/">Progressive Web Apps</a></li>
                    <li><a target="_blank" href="https://www.sortd.mobi/amp/">AMP</a></li>
                    <li><a target="_blank" href="https://www.sortd.mobi/native-apps/">Native Apps</a></li>
                    <li><a target="_blank" href="https://www.sortd.mobi/smart-tv-app/">Smart Devices App</a></li>
                  </ul>
                </div>
              </div>
              <div class="col-md-4">
                <div class="foter_cont bd-n">
                  <h4>Company</h4>
                  <ul>
                    <li><a target="_blank" href="https://www.sortd.mobi/about-us/">About Us</a></li>
                    <li><a target="_blank" href="https://www.sortd.mobi/contact/">Contact Us</a></li>
                    <li>183, GoWork Udyog Vihar Phase 1 Gurgaon, Haryana, India - 122016</li>
                  </ul>
                </div>
              </div>
          </div>
    </div>
  </div>




<?php 
function read_og_tags_as_json($url){


   $response     = wp_remote_get($url);
   $responseCode = wp_remote_retrieve_response_code($response);

   if ( is_array( $response ) && ! is_wp_error( $response ) ) {
       $HTML_DOCUMENT = wp_remote_retrieve_body($response);
   }

    $doc = new DOMDocument();
    $doc->loadHTML($HTML_DOCUMENT);

    // fecth <title>
    $res['title'] = $doc->getElementsByTagName('title')->item(0)->nodeValue;

    // fetch og:tags
    foreach( $doc->getElementsByTagName('meta') as $m ){

          // if had property
          if( $m->getAttribute('property') ){

              $prop = $m->getAttribute('property');

              // here search only og:tags
              if( preg_match("/og:/i", $prop) ){

                  // get results on an array -> nice for templating
                  $res['og_tags'][] =
                  array( 'property' => $m->getAttribute('property'),
                          'content' => $m->getAttribute('content') );
              }

          }
          // end if had property

          // fetch <meta name="description" ... >
          if( $m->getAttribute('name') == 'description' ){

            $res['description'] = $m->getAttribute('content');

          }


    }
    // end foreach

    // render JSON

    // echo "<pre>";print_r($res);

  	
if(isset($res['og_tags'])){

   foreach( $res['og_tags'] as $k => $v){
   		if($v['property'] == 'og:title'){
   			 $resOg['og_title'] = $v['content'];
   		}

   		if($v['property'] == 'og:description'){
   			 $resOg['og_description'] = $v['content'];
   		}

   		if($v['property'] == 'og:type'){
   			 $resOg['og_type'] = $v['content'];
   		}

   		if($v['property'] == 'og:title'){
   			 $resOg['og_title'] = $v['content'];
   		}

   		if($v['property'] == 'og:url'){
   			 $resOg['og_url'] = $v['content'];
   		}

   		if($v['property'] == 'og:site_name'){
   			 $resOg['og_sitename'] = $v['content'];
   		}

   		if($v['property'] == 'og:image'){
   			 $resOg['og_image'] = $v['content'];
   		}
   }

  }

  return $resOg;

}




?>