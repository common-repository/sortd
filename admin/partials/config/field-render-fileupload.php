<?php
/**
 * Provide a file upload field rendering  view for the plugin
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
    
    $mandatory_star = '';
    
    if(!empty($field_details->required)){
        $mandatory_star = '*';
    }

    $accept = $field_details->file_type;
    $sizes = explode('x',$field_details->size);
    $width = $sizes[0];
    $height = $sizes[1];

    $image_class = 'favImage';
    if($field_details->size === '16x16'){
        $image_class = 'fav-bx';
    }
    
?>


<!-- IMAGE UPLOAD START -->
    <div class="singl-section">
        <div class="avatar-upload">
            <div class="avatar-edit">
                <label for="<?php echo wp_kses_data($field_id);?>"><h5 class="subName"><?php echo wp_kses_data($field_details->label).'('.wp_kses_data($field_details->size).')'; ?><span style="color:red"><?php echo wp_kses_data($mandatory_star);?></span>
                    <?php if(isset( $field_details->helptext)&& !empty($field_details->helptext)) { ?>
                            <div class="inputMsg"><?php echo wp_kses_data($field_details->helptext);?></div>
                            <?php } ?>
                    </h5>
                </label> 
                <div class="avatar-preview" >
                    <div id="imagePreview" class="<?php echo wp_kses_data($image_class); ?>"><img id="dvPreview<?php echo wp_kses_data($field_id);?>" src="<?php echo wp_kses_data($field_value);?>" />
                    </div>
                </div>
                <div class="up-input">
                        <input class="sortd_upload_file" type='file' data-height="<?php echo wp_kses_data($height);?>" data-width="<?php echo wp_kses_data($width);?>"  id="<?php echo wp_kses_data($field_id);?>"   accept="<?php echo wp_kses_data($accept);?>"  />

                        <input type='hidden' value='<?php echo wp_kses_data($field_value);?>' name='<?php echo wp_kses_data($field_name);?>'  id="hidden_<?php echo wp_kses_data($field_id);?>"   />

                        <span class="spnerror<?php echo wp_kses_data($field_id);?> msg-eror" style="color:red;display:none">Please upload image of <?php echo wp_kses_data($width);?>x<?php echo wp_kses_data($height);?> </span>

                         <button type="button" id="remove<?php echo wp_kses_data($field_id);?>" class=" btn btn-danger imageRemoveBtn"><i class="fa fa-times"></i>Remove</button>
                </div>

            </div>
            </div>
        </div>