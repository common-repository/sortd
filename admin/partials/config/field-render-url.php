<?php
/**
 * Provide a url field rendering  view for the plugin
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
        $field_data_params = "data-param-url='required'";
        $mandatory_star = '*';
    }


?>

<div class="form-box singl-section">
        <h5 class="subName"><?php echo wp_kses_data($field_details->label); ?><span style="color:red;"><?php echo wp_kses_data($mandatory_star);?></span>
         <?php if(isset( $field_details->helptext)&& !empty($field_details->helptext)) { ?>
                                          <div class="inputMsg"><?php echo wp_kses_data($field_details->helptext);?></div>
                                          <?php } ?>


        </h5>
        <label class="pure-material-textfield-outlined">
            <input placeholder=" " class="urlclass" id="<?php echo wp_kses_data($field_id);?>" type="url" name="<?php echo wp_kses_data($field_name);?>" <?php echo wp_kses_data($field_data_params);?> value="<?php echo wp_kses_data($field_value);?>">

         <span class="hidevallabel"><?php echo wp_kses_data($field_details->label); ?></span>

        <span id="urlhttps<?php echo wp_kses_data($field_id);?>" style="color:red;display:none">Only https:// is allowed</span>


</div>