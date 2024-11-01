<?php

/**
 * Provide Help/FAQs view for the plugin
 *
 * This file is used to markup the Help/FAQs aspects of the plugin.
 *
 * @link       https://www.sortd.mobi
 * @since      2.0.0
 *
 * @package    Sortd
 * @subpackage Sortd/admin/partials
 */
?>
<?php 
    if ( ! defined( 'ABSPATH' ) ) exit; 
    wp_enqueue_script( 'sortd-article', SORTD_JS_URL . '/sortd-article.js', array( 'jquery' ),true );
        wp_localize_script(
            'sortd-article',
            'sortd_ajax_obj_article',
            array(
                'ajax_url' => admin_url( 'admin-ajax.php' ),
                'nonce'    => wp_create_nonce( 'sortd-ajax-nonce-article' ),
            )
    );
?>

<div class="content-section">
	<div class="container-pj">
	   <div class="row">
	      <div class="col-md-12">
	         <div class="heading-main">
	            <div class="logoLft">
	               <img src="<?php echo wp_kses_data(SORTD_CSS_URL);?>/logo.png">
	              <h5>Help & FAQs</h5>
	            </div>
	            <div class="headingNameTop df_bTn">
              <button type="button" onclick="window.open('https://support.sortd.mobi/portal/en/kb/gni-adlabs/general','_blank')" class="btn infoIcn icPd0" title="AdLabs Support"><i class="bi bi-info-circle"></i></button>
                  <button class="butn-df" onclick="window.open('https://www.sortd.mobi/contact/','_blank')">Contact Support <i class="bi bi-headset"></i></button>
                              
	            </div>
	         </div>
	      </div>
	   </div>

	   <div class="row">
	   		<div class="col-md-12">
	   			<div class="faqBox mt30">
	   				<h1>Frequently Asked Questions</h1>
	   				<div class="accordion" id="accordionExample">
					  <?php 
                                            $i = 1;
                                            if(isset($faqs->data)){
                                            foreach($faqs->data as $faq_key => $faq_details) {  
                                          ?>
					  	<h2 class="accordion-header mainH" id="heading<?php echo wp_kses_data($i) ?>">
							<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo wp_kses_data($i);?>" aria-expanded="true" aria-controls="collapse<?php echo wp_kses_data($i);?>">
					          <?php echo wp_kses_data($faq_details->name);?>
					      </button>
					    </h2>

                                                <?php

                                                $iterate = 1;
                                                foreach($faq_details->questions as $question_key => $question_details) {
                                                    if($iterate=== 1){ 
                                                        $class="show";
                                                    } else { 
                                                        $class= "";
                                                    }
                                                ?>


                                                <div class="accordion-item">
                                                            <h2 class="accordion-header" id="heading<?php echo wp_kses_data($iterate.$faq_key); ?>">
                                                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo wp_kses_data($iterate.$faq_key);?>" aria-expanded="true" aria-controls="collapse<?php echo wp_kses_data($iterate.$faq_key);?>">
                                                            <?php echo wp_kses_data($iterate);?>.   <?php echo wp_kses_data($question_details->question);?>
                                                          </button>
                                                        </h2>
                                                            <div id="collapse<?php echo wp_kses_data($iterate.$faq_key);?>" class="accordion-collapse collapse <?php echo wp_kses_data($class);?>" aria-labelledby="heading<?php echo wp_kses_data($iterate.$faq_key) ?>" data-bs-parent="#accordionExample">
                                                          <div class="accordion-body">
                                                            <div class="accorBox">
                                                                    <p><?php echo wp_kses_data($question_details->answer);?></p>

                                                            </div>
                                                          </div>
                                                        </div>
                                                      </div>

                                                <?php $iterate++; } ?>
						
						
                                            <?php $i++; } } ?>

					</div>
	   			</div>
	   		</div>
	   </div>
	</div>
</div>

