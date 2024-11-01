<?php
/**
 * Provide a string field rendering  view for the plugin
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

?>
<div class="singl-section">
        <div class="picker">
                <h5 class="subName"><?php echo wp_kses_data(ucfirst($field_details->label)); ?> 
 <?php if(isset( $field_details->helptext)&& !empty($field_details->helptext)) { ?>
                                                  <div class="inputMsg"><?php wp_kses_data($field_details->helptext);?></div>
                                                  <?php } ?>
        </h5>
        
    <input  type="color" class="colorpicker" id= "<?php echo wp_kses_data($field_id);?>" name="<?php echo wp_kses_data($field_name);?>" value="<?php echo wp_kses_data($field_value);?>">



<input type="text"  id="hex_<?php echo wp_kses_data($field_id);?>" class="hexcolor" name="<?php echo wp_kses_data($field_name);?>"  autocomplete="off" spellcheck="false"  value='<?php echo wp_kses_data($field_value);?>'>
   <span class= "hexspan_<?php echo wp_kses_data($field_id);?>"  style="color:red;display:none">Only Hex color code is accepted</span> 
  </div>

</div>
