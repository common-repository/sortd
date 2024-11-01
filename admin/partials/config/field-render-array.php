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
   

if(isset($field_details->source)){

?>

    <div class="singl-section">
        <h5 class="subName"> <?php echo wp_kses_data($field_details->label); ?>

        </h5>

            <label class="pure-material-textfield-outlined">
                    <div class="w-bg1"></div>
                        <select b id="<?php echo wp_kses_data($field_id);?>" name="<?php echo wp_kses_data($field_name);?>#multivaluearray" multiple>

                            <optgroup label=" <?php echo wp_kses_data($field_details->label);?>">

                            <?php if($current_group !== "category") {foreach ($categories['categories'] as $cat_key => $category_details) { 
                                    if (!isset($category_details['parent_id']['_id'])) {

                                    if(!empty($category_details['sub_categories'])){ 
                                        foreach ($category_details['sub_categories'] as $subcat_key => $subcategory_details) { 
                                                ?>
                                        <option <?php if(in_array($subcategory_details['cat_guid'], $field_value, true)){ ?> selected<?php } ?> value="<?php echo wp_kses_data($subcategory_details['cat_guid']);?>"><?php echo wp_kses_data($subcategory_details['name']);?></option> 
                                            <?php 	
                                            
                                          }

                                    }

                                    ?>
                                       <option <?php if( is_array($field_value) && in_array((string)$category_details['cat_guid'], $field_value, true)){ ?> selected<?php } ?> value="<?php echo wp_kses_data($category_details['cat_guid']);?>"><?php echo wp_kses_data($category_details['name']);?></option>
                            <?php }}} else {
                                    foreach ($categories['categories'] as $cat_key => $category_details) { 
                                ?>
                                <option <?php if(in_array((string)$category_details['cat_guid'], (array)$field_value, true)){ ?> selected<?php } ?> value="<?php echo wp_kses_data($category_details['cat_guid']);?>"><?php echo wp_kses_data($category_details['name']);?></option>
                            <?php
                            }}?>

                            </optgroup>

                        </select>
                    
                        <span id="catspan"><?php echo wp_kses_data($field_details->label); ?></span>
                         <?php if(isset( $field_details->helptext)&& !empty($field_details->helptext)) { ?>

                              <div class="inputMsg"><?php wp_kses_data($field_details->helptext);?></div>

                          <?php } ?>

            </label>

    </div>
<?php }  