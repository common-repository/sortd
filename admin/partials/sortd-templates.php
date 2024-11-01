<?php

/**
 * Provide a templates view for the plugin
 *
 * This file is used to markup the templates aspects of the plugin.
 *
 * @link       https://www.sortd.mobi
 * @since      2.0.0
 *
 * @package    Sortd
 * @subpackage Sortd/admin/partials
 */

?>

<div class="content-section ">
	<div class="container-pj">
	   <div class="row">
	      <div class="col-md-12">
	         <div class="heading-main">
	            <div class="logoLft">
	               <img src="<?php echo wp_kses_data(SORTD_CSS_URL);?>/logo.png">
	              <h5>Select Themes </h5>
	            </div>
	            <div class="headingNameTop">
					 <button type="button" onclick="window.open('https://support.sortd.mobi/portal/en/kb/gni-adlabs/general','_blank')" class="btn infoIcn icPd0" title="AdLabs Support"><i class="bi bi-info-circle"></i></button>
	               <h2></h2>
	            </div>
	         </div>
	      </div>
	   </div>

	   <div class="row">
	   		<div class="col-md-12">
	   			<div class="msgSectn dvce">
	   			<div class="second-heading">
                  <h5>Select the theme of your choice</h5>
               	</div>

               	<input type="hidden" id="adminurl" value="<?php echo wp_kses_data(admin_url());?>" >
	   				<!-- <h5> Choose the theme to click on view demo</h5> -->
	   			

					<!-- template section start -->
					<div class="col-md-12">
						<div class="templateSectn">
							<!-- Slider main container -->
							<div class="row">
							<input type="hidden" id="nonce_input" value="<?php echo esc_attr(wp_create_nonce(SORTD_NONCE)); ?>">					
									<?php
                                    foreach($themes_data as $k => $theme_detail) { 

                                                                                    $theme_default_images = json_decode($theme_detail->default_images);
									?>
								<div class="col-md-6 ">
									<div class="carousel-card themeselecteddiv_<?php echo wp_kses_data($theme_detail->id);?> <?php if($theme_detail->id === $saved_template_id){ ?>activetheme <?php } ?>">
					            		
						                <figure>
						                    <figcaption>
                                                                        <h4><?php echo wp_kses_data($theme_detail->name);?> <span class="acThm">Active</span></h4>
						                        <p><?php echo wp_kses_data($theme_detail->description);?></p>
						                    </figcaption>
						                     <button class=" btn-ad" href="#" data-bs-toggle="modal" data-bs-target="#exampleModal_<?php echo wp_kses_data($theme_detail->id);?>">Select <i class="bi bi-hand-index"></i></button>
						                </figure>
						                <div class="imgCrd">
						                	<img src="<?php echo wp_kses_data($theme_default_images->home);?> "> 
						                </div>
					            	</div>
					            </div>


					            <div class="modal fade" id="exampleModal_<?php echo wp_kses_data($theme_detail->id);?>" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
											  <div class="modal-dialog modal-lg modal-dialog-centered">
											    <div class="modal-content">
											      <div class="modal-header aln">
											        <h5 class="modal-title" id="exampleModalLabel"><?php echo wp_kses_data($theme_detail->name);?></h5>
											        <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
											        <div class="acnBt">
														<input type="hidden" id="nonce_value" value="<?php echo wp_kses_data(wp_create_nonce(-1)); ?>">
												        <button type="button" class="btn btn-ad-blu themebtn" style="float: right;" id="<?php echo wp_kses_data($theme_detail->id);?>">Select Theme</button>
												        <button type="button" class="btn btn-cancl"  data-bs-dismiss="modal">Close</button>
												    </div>
											      </div>
											      <div class="modal-body">
											        <div class="row">
											        	<div class="col-md-4">
															<div class="mobDevice" style="display:">
											                      <span class="circl"></span>
											                      <span class="barcrcl"></span>
											                      <img src="<?php echo wp_kses_data($theme_default_images->home);?>" >
											                </div>
														</div>

														
														<div class="col-md-4">
															<div class="mobDevice" style="display:">
											                      <span class="circl"></span>
											                      <span class="barcrcl"></span>
											                       <img src="<?php echo wp_kses_data($theme_default_images->category);?>" >
											                </div>
														</div>
														<div class="col-md-4">
															<div class="mobDevice" style="display:">
											                      <span class="circl"></span>
											                      <span class="barcrcl"></span>
											                       <img src="<?php echo wp_kses_data($theme_default_images->article);?>" >
											                </div>
														</div>
											        </div>
											      </div>
											      <div class="modal-footer">
											        
											      </div>
											    </div>
											  </div>
											</div>

					          <?php } ?>

				           	</div>
						</div>	
					</div>
					<!-- template-section end -->
				
					<!-- <div>
						<button type="button" class="btn btn-ad saveTemplate">Save</button>
					</div> -->
	   			</div>

	   		</div>
	   </div>
	</div>
</div>


<div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row">
        	<div class="col-md-4">
				<div class="mobDevice" style="display:">
                      <span class="circl"></span>
                      <span class="barcrcl"></span>
                      <img src="<?php echo wp_kses_data($theme_default_images->home);?>" >
                </div>
			</div>

			
			<div class="col-md-4">
				<div class="mobDevice" style="display:">
                      <span class="circl"></span>
                      <span class="barcrcl"></span>
                       <img src="<?php echo wp_kses_data($theme_default_images->category);?>" >
                </div>
			</div>
			<div class="col-md-4">
				<div class="mobDevice" style="display:">
                      <span class="circl"></span>
                      <span class="barcrcl"></span>
                       <img src="<?php echo wp_kses_data($theme_default_images->article);?>" >
                </div>
			</div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-cancl" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-ad">Choose Theme</button>
      </div>
    </div>
  </div>
</div>