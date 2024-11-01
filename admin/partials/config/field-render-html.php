<?php
/**
 * Provide a html field rendering  view for the plugin
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

<div class="form-box singl-section">
            <h5 class="subName"> <?php echo wp_kses_data($field_details->label); ?> <?php if(isset( $field_details->helptext)&& !empty($field_details->helptext)) { ?>
            <div class="inputMsg"><?php echo wp_kses_data($field_details->helptext);?></div>
                                                              <?php } ?></h5>
    <label class="pure-material-textfield-outlined">
        <textarea class="form-control" id="<?php echo wp_kses_data($field_id);?>" name="<?php echo wp_kses_data($field_name);?>"  rows="3"><?php echo esc_textarea($field_value);?></textarea>
        <span><?php echo wp_kses_data($field_details->label); ?></span>
    </label>
</div>