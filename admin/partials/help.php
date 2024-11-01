<?php 
if ( ! defined( 'ABSPATH' ) ) exit; 
//echo "<pre>";print_r($jsonDecoded);die;

	//echo "<pre>";print_r($version);die;
?>

<div class="content-section">
	<div class="container-pj">
	   <div class="row">
	      <div class="col-md-12">
	         <div class="heading-main">
	            <div class="logoLft">
	               <img src="<?php echo plugin_dir_url( __DIR__ );?>css/logo.png">
	              <h5>Help & FAQs</h5>
	            </div>
	            <div class="headingNameTop df_bTn">
                    
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

	   				
					  
					  <?php $i = 1; foreach($jsonDecoded->data as $k => $v) {  ?>
					  	<h2 class="accordion-header mainH" id="heading<?php echo $i ?>">
							<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $i;?>" aria-expanded="true" aria-controls="collapse<?php echo $i;?>">
					          <?php echo $v->name;?>
					      </button>
					    </h2>

					    <?php $iterate = 1; foreach($v->questions as $kQ => $vQ) { if($iterate== 1){ $class="show"; } else { $class= ""; }?>


					    <div class="accordion-item">
							<h2 class="accordion-header" id="heading<?php echo $iterate.$k ?>">
								<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $iterate.$k;?>" aria-expanded="true" aria-controls="collapse<?php echo $iterate.$k;?>">
						        <?php echo $iterate;?>.   <?php echo $vQ->question;?>
						      </button>
						    </h2>
							<div id="collapse<?php echo $iterate.$k;?>" class="accordion-collapse collapse <?php echo $class;?>" aria-labelledby="heading<?php echo $iterate.$k ?>" data-bs-parent="#accordionExample">
						      <div class="accordion-body">
						        <div class="accorBox">
						        	<p><?php echo $vQ->answer;?></p>

						        </div>
						      </div>
						    </div>
						  </div>

					    <?php $iterate++; } ?>
						<!-- <div class="accordion-item">
						<h2 class="accordion-header" id="heading<?php echo $i ?>">
							<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $i;?>" aria-expanded="true" aria-controls="collapse<?php echo $i;?>">
					        <?php echo $i;?>.   <?php echo $v->question;?>
					      </button>
					    </h2>
						<div id="collapse<?php echo $i;?>" class="accordion-collapse collapse <?php echo $class;?>" aria-labelledby="heading<?php echo $i ?>" data-bs-parent="#accordionExample">
					      <div class="accordion-body">
					        <div class="accorBox">
					        	<p><?php echo $v->answer;?></p>

					        </div>
					      </div>
					    </div>
					  </div> -->
						
						<?php $i++; } ?>

				

					

					  

					 
					</div>
	   			</div>
	   		</div>
	   </div>
	</div>
</div>

<!-- <div>
	<span>This version is no longer supported <?php //echo $projectDetails->error->message;?></span>
</div> -->