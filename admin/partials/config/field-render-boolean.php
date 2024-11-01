<?php
/**
 * Provide a boolean field rendering  view for the plugin
 *
 * This file is used to markup the config aspects of the plugin.
 *
 * @link       https://www.sortd.mobi
 * @since      2.0.0
 *
 * @package    Sortd
 * @subpackage Sortd/admin/partials
 */
?>
<?php

    $field_data_params = "";
    $mandatory_star = '';
    
    if(!empty($field_details->required)){
        $field_data_params = "data-param = 'required'";
        $mandatory_star = '*';
    }
    if($field_value === true){
        $prop = 'checked';
        $checkedvalue = "true";
    } else {
        $prop = '';
        $checkedvalue = "false";
    }
?>

<!-- TOGGLE SWITCH START -->
          <div class="singl-section" <?php if($field_details->label === "Enable Category post order" && !is_plugin_active("sortd_post_reorder_v2/sortd_post_reorder.php")) { echo wp_kses_data("style='display:none;'");} elseif($field_details->label === "Enable Post Priority" && !is_plugin_active("sortd_post_reorder/sortd-post-order.php")) {echo wp_kses_data("style='display:none;'");} ?>>
              <h5 class="subName"><?php echo wp_kses_data($field_details->label); ?>

              <?php if(isset( $field_details->helptext)&& !empty($field_details->helptext)) { ?>
                                <span class="inputMsg sinfo" ><?php echo wp_kses_data($field_details->helptext);?></span>
                                <?php } ?><span style = "color:red"><?php echo wp_kses_data($mandatory_star);?></span>
              </h5>

              <label class="switch-tog">
                          <input type="checkbox" name="<?php echo wp_kses_data($field_name);?>" value="<?php echo wp_kses_data($checkedvalue);?>" id= "<?php echo wp_kses_data($field_id);?>" <?php echo  wp_kses_data($prop);?> onclick="getEvent(this)">
                          <span class="slider-tog round"></span>

                      </label>

          </div>


          <!-- TOGGLE SWITCH END -->
