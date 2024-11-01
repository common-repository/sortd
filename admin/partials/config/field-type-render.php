<?php
/**
 * Provide a field type rendering logic view for the plugin
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

switch ($field_details->type){
    case "string":
        if(isset($field_details->source) && $field_details->source==='category_list'){
            Sortd_Helper::render_partials(array('field-render-category'), $view_data, 'config');
        }else{
            Sortd_Helper::render_partials(array('field-render-string'), $view_data, 'config');
        }
        break;

    case "integer":
        Sortd_Helper::render_partials(array('field-render-integer'), $view_data, 'config');
        break;

    case "html":
        Sortd_Helper::render_partials(array('field-render-html'), $view_data, 'config');
        break;

    case "boolean":
        Sortd_Helper::render_partials(array('field-render-boolean'), $view_data, 'config');
        break;

    case "url":
        Sortd_Helper::render_partials(array('field-render-url'), $view_data, 'config');
        break;

    case "file_upload":
        Sortd_Helper::render_partials(array('field-render-fileupload'), $view_data, 'config');
        break;

    case "array":
        Sortd_Helper::render_partials(array('field-render-array'), $view_data, 'config');
        break;

    case "hex_color":
        Sortd_Helper::render_partials(array('field-render-hexcolor'), $view_data, 'config');
        break;

    case "enum":
        Sortd_Helper::render_partials(array('field-render-enum'), $view_data, 'config');
        break;

    default:
        break;
}