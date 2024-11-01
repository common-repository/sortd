<?php
/**
 * Provide a field rendering display view for the plugin
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

    $field_details = $view_data['field_details'];

    $current_group_saved_config = $view_data['current_group_saved_config'];

    $field_child = $view_data['field_child'];
    
    $field_sub_parent = $view_data['field_sub_parent'];
    
    $field_parent = $view_data['field_parent'];

    $current_group = $view_data['current_group'];
    
    $field_id = $field_name = $field_value = '';


    if($field_parent !== ''){
        
        $field_id = $field_parent."-".$field_sub_parent."-".$field_child;
        $field_name = $field_parent.":".$field_sub_parent.":".$field_child;
        if(isset($current_group_saved_config->$field_parent) && isset($current_group_saved_config->$field_parent->$field_sub_parent)  && isset($current_group_saved_config->$field_parent->$field_sub_parent->$field_child)){
            $field_value = $current_group_saved_config->$field_parent->$field_sub_parent->$field_child;
        }
        
    }else if($field_sub_parent !== ''){
        
        $field_id = $field_sub_parent."-".$field_child;
        $field_name = $field_sub_parent.":".$field_child;
        if(isset($current_group_saved_config->$field_sub_parent)  && isset($current_group_saved_config->$field_sub_parent->$field_child)){
            $field_value = $current_group_saved_config->$field_sub_parent->$field_child;
        }
        
    }else{
        
        $field_id = $field_child;
        $field_name = $field_child;
        
        if(isset($current_group_saved_config->$field_child)){
            $field_value = $current_group_saved_config->$field_child;
        }
    }

    if($field_details->type === 'boolean' && $field_value === false){
        $field_value = 'false';
    }
    
    if($field_details->type !== 'multi-input'){
        
            if(empty($field_value) && isset($field_details->default) && !empty($field_details->default)){
                $field_value = $field_details->default;
            } 

            $view_data['field_id'] = $field_id;
            $view_data['field_name'] = $field_name;
            $view_data['field_value'] = $field_value;

            Sortd_Helper::render_partials(array('field-type-render'), array('field_details'=>$field_details,'view_data'=>$view_data), 'config');
            
    }else{

        $multi_input_sections = is_array($field_value) ? sizeof($field_value) : 1;
        if($multi_input_sections === 0){
            $multi_input_sections = 1;
        }
        
        foreach ($field_details->type_items as $multi_input_key => $multi_input_fields) {
            ?>
            <div class="multiinput_div1" id="multiinputdiv-<?php echo wp_kses_data($field_id);?>" >
                <h2 class='card-titl'><?php echo wp_kses_data($field_details->label); ?><?php if(isset($field_details->helptext) && !empty($field_details->helptext));?></h2>	
                <input type="hidden" id="section-count-<?php echo wp_kses_data($field_id);?>" value="<?php echo wp_kses_data($multi_input_sections); ?>"/>
            <?php
              for($section = 0; $section < $multi_input_sections; $section++){ ?>
                    <span class="sepRater" id="separator_<?php echo wp_kses_data($field_id).'-'.wp_kses_data($section);?>"></span>
                    <div id="<?php echo wp_kses_data($field_id).'-'.wp_kses_data($section);?>">

                            <?php foreach ($multi_input_fields as $multi_input_field_key => $multi_input_field_details) {
                                
                                    $multi_input_field_id = $field_id.'-'.$section.'-'.$multi_input_field_key;
                                    $multi_input_field_name = $field_name.':'.$section.':'.$multi_input_field_key;
                                    $multi_input_field_value = '';
                                    if(isset($field_value[$section]) && isset($field_value[$section]->$multi_input_field_key)){
                                        $multi_input_field_value = $field_value[$section]->$multi_input_field_key;
                                    }
                                    if(empty($multi_input_field_value) && isset($multi_input_field_details->default)){
                                        $multi_input_field_value = $multi_input_field_details->default;
                                    }
                                    
                                    if($multi_input_field_details->type === 'boolean' && $multi_input_field_value === ''){
                                        $multi_input_field_value = 'false';
                                    }

                                    $view_data['field_id'] = $multi_input_field_id;
                                    $view_data['field_name'] = $multi_input_field_name;
                                    $view_data['field_value'] = $multi_input_field_value;
                                    $view_data['field_details'] = $multi_input_field_details;
                                    
                                    Sortd_Helper::render_partials(array('field-type-render'), array('field_details'=>$multi_input_field_details,'view_data'=>$view_data), 'config');
                                    
                                    
                                }
                            ?>
                        
                        <div class="form-box">
                            <label class="pure-material-textfield-outlined smBtnArea">
                                <button type="button" class=" btn btn-info btn-ad  removeBtn"  id="remove_multiinput_section_<?php echo  wp_kses_data($field_id).'-'.wp_kses_data($section);?>">Remove</button>
                                <span style="color:red;display:none;" class="spancantremove<?php echo  wp_kses_data($field_id).'-'.wp_kses_data($section);?>">You can't remove the last element </span>
                            </label>
                        </div>
                    </div>  
                    <?php
                }
                ?>
            </div>


            <div class="form-box">
                <label class="pure-material-textfield-outlined smBtnArea">
                    <button type="button" class=" btn btn-info btn-ad  recentAddMore"  id="add_more_<?php echo wp_kses_data($field_id);?>">Add More</button>
                </label>
            </div>        
                <?php
        }
        
        foreach ($field_details->type_items as $multi_input_template_key => $multi_input_template_fields) {
            ?>
            <script id="template_<?php echo wp_kses_data($field_id); ?>" type="text/html">
                <span class="sepRater" id="separator_<?php echo wp_kses_data($field_id).'-';?>{section_id}"></span>
                   <div id="<?php echo wp_kses_data($field_id).'-';?>{section_id}">
                                <?php foreach ($multi_input_template_fields as $multi_input_template_field_key => $multi_input_template_field_details) {
                                
                                    $multi_input_template_field_id = $field_id.'-{section_id}-'.$multi_input_template_field_key;
                                    $multi_input_template_field_name = $field_name.':{section_id}:'.$multi_input_template_field_key;
                                    $multi_input_template_field_value = '';
                                    
                                    if(isset($multi_input_template_field_details->default)){
                                        $multi_input_template_field_value = $multi_input_template_field_details->default;
                                    }
                                    
                                    $view_data['field_id'] = $multi_input_template_field_id;
                                    $view_data['field_name'] = $multi_input_template_field_name;
                                    $view_data['field_value'] = $multi_input_template_field_value;
                                    $view_data['field_details'] = $multi_input_template_field_details;
                                    
                                    Sortd_Helper::render_partials(array('field-type-render'), array('field_details'=>$multi_input_template_field_details,'view_data'=>$view_data), 'config');
                                    
                                    
                                }
                            ?>
                       <div class="form-box">
                            <label class="pure-material-textfield-outlined smBtnArea">
                                <button type="button" class=" btn btn-info btn-ad  removeBtn"  id="remove_multiinput_section_<?php echo  wp_kses_data($field_id).'-{section_id}';?>">Remove</button>
                                <span style="color:red;display:none;" class="spancantremove<?php echo  wp_kses_data($field_id).'-{section_id}';;?>">You can't remove the last element </span>
                            </label>
                        </div>
                   </div>
            </script>
            <?php
        }
           
    }