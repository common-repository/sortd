<?php
/**
 * Provide a enum field rendering  view for the plugin
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
        $field_data_params = "data-enum = 'required'";
        $mandatory_star = '*';
    }

?>
<div class="singl-section">
<h5 class="subName"> <?php echo wp_kses_data($field_details->label); ?>
<span style = "color:red"><?php echo wp_kses_data($mandatory_star);?></span>
<?php if(isset( $field_details->helptext)&& !empty($field_details->helptext)) { ?>

<div class="inputMsg"><?php echo wp_kses_data($field_details->helptext);?></div>

<?php } ?>
</h5>

<label class="pure-material-textfield-outlined">
<select a onchange="handleOnchangeEvent()" id="<?php echo wp_kses_data($field_id);?>" name="<?php echo wp_kses_data($field_name);?>">

<optgroup  label=" Select <?php echo wp_kses_data($field_details->label);?>">

<?php foreach ($field_details->type_items as $enum_key => $enum_details) { 
    
if((string)$field_value === (string)$enum_details->value){
    $select = 'selected';
} else {
    $select = '';
}


?>

<option <?php echo wp_kses_data($select);?> value="<?php echo wp_kses_data($enum_details->value);?>"><?php echo wp_kses_data($enum_details->label);?></option>

<?php 

} ?>

</optgroup>

</select>
<span><?php echo wp_kses_data($field_details->label); ?></span>

</label>

</div>
