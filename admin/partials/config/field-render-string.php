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
<!-- <script>
    function validate(input){
        if(/^\s/.test(input.value))
        input.value = '';
    }
</script> -->

<div class="form-box singl-section">
    
        <h5 class="subName"><?php echo wp_kses_data($field_details->label); ?><span style = "color:red"><?php echo wp_kses_data($mandatory_star);?></span>
                <?php if(isset( $field_details->helptext)&& !empty($field_details->helptext)) { ?>
                    <div class="inputMsg"><?php echo wp_kses_data($field_details->helptext);?></div>
                <?php } ?>
        </h5>

        <label class="pure-material-textfield-outlined">
            <input placeholder=" " id= "<?php echo wp_kses_data($field_id);?>" type="text" name="<?php echo wp_kses_data($field_name);?>" <?php echo wp_kses_data($field_data_params);?> value='<?php echo wp_kses_data($field_value); ?>'>
            <span class><?php echo wp_kses_data($field_details->label); ?></span>
            <br><span class="error-message" id= "<?php echo esc_attr($field_id."error_msg");?>" style= "color:red"></span>
        </label>

</div>