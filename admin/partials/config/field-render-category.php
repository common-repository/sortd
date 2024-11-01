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
 
$dependent_field_id = '';
if(isset($field_details->dependent_field) && $field_details->dependent_field!==''){
    $explode_field_id = explode('-',$field_id);
    $explode_field_id[count($explode_field_id)-1] = $field_details->dependent_field;
    $dependent_field_id = implode('-',$explode_field_id);
}

$field_data_params = "";
$mandatory_star = '';

if(!empty($field_details->required)){
    $field_data_params = "data-enum = 'required'";
    $mandatory_star = '*';
}
   
?>

<div class="singl-section">
    
<h5 class="subName"><?php echo wp_kses_data($field_details->label); ?>

        <?php if(isset( $field_details->helptext)&& !empty($field_details->helptext)) { ?>

        <div class="inputMsg"><?php echo wp_kses_data($field_details->helptext);?></div>

<?php } ?>
<span style = "color:red"><?php echo wp_kses_data($mandatory_star);?></span>
</h5>


        <label class="pure-material-textfield-outlined">
                <div class="w-bg1"></div>
  <select  onchange="handleOnchangeEvent(this,'<?php echo wp_kses_data($dependent_field_id); ?>')"id="<?php echo wp_kses_data($field_id);?>" name="<?php echo wp_kses_data($field_name);?>" class="<?php echo wp_kses_data($field_id);?>">


      <optgroup  label=" Select <?php echo wp_kses_data($field_details->label);?>">

                <?php  foreach ($categories['categories'] as $category_key => $category_details) { 

                            
                        if($category_details['cat_guid'] === (int)$field_value){
                          
                                $attr = "selected";
                        } else {
                                $attr = "";
                        }


                ?>
                           <option <?php echo wp_kses_data($attr);?> value="<?php echo wp_kses_data($category_details['cat_guid']);?>"><?php echo wp_kses_data($category_details['name']);?></option>
                <?php } ?>


<?php 

?>



</select>
  <span><?php echo wp_kses_data($field_details->label); ?></span>
   <span id="selectalert<?php echo wp_kses_data($field_id);?>" style="color:red;display:none">Select Category</span>

</label>

</div>

