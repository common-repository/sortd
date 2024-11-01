<?php

/**
 * Provide a version support view for the plugin
 *
 * This file is used to markup the oneclick process - get started aspects of the plugin.
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
?>

<div class="content-section">
	<div class="container-pj">
	   <div class="row">
	      <div class="col-md-12">
	         <div class="heading-main">
	            <div class="logoLft">
	               <img src="<?php echo wp_kses_data(SORTD_CSS_URL);?>/logo.png">
	              <h5>Alert</h5>
	            </div>
	            <div class="headingNameTop">
	               <h2></h2>
	            </div>
	         </div>
	      </div>
	   </div>

	   <div class="row">
	   		<div class="col-md-12">
	   			<div class="msgSectn">
	   				<h5><?php echo wp_kses_data($project_details->error->message);?></h5>
	   			</div>
	   		</div>
	   </div>
	</div>
</div>
