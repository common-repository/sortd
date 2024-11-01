<?php

/**
 * Provide a categories - manage view for the plugin
 *
 * This file is used to markup the categories - manage aspects of the plugin.
 *
 * @link       https://www.sortd.mobi
 * @since      2.0.0
 *
 * @package    Sortd
 * @subpackage Sortd/admin/partials
 */
?>
<style>
    .inlineLi {
        display: inline-block;
    }

    .oderBtn.url {
    display: grid;
    padding-right: -10px !important;
    }
</style>
<div class="col-md-12 buttonsmanage" >
    <div class="oderBtn">
    <input type="hidden" id="nonce_input" value="<?php echo esc_attr(wp_create_nonce(SORTD_NONCE)); ?>">

        <input type ="hidden" id="siteurl" value="<?php echo esc_attr(site_url());?>">
        <button type="button" onclick="window.open('https://support.sortd.mobi/portal/en/kb/gni-adlabs/general','_blank')" class="btn infoIcn icPd0-l" title="AdLabs Support"><i class="bi bi-info-circle"></i></button>
        <button class="butn-df syncCat <?php if($action==='sync'){ ?>manageCategory-active<?php } ?>"> Sync Categories</button>
        <button class="butn-df reor-renameCat <?php if($action==='reorder'){ ?>manageCategory-active<?php } ?>" >Reorder/Rename Categories</button>
            <?php 

                if(get_option('category_url_redirection_flag') === false) {
                    update_option('category_url_redirection_flag', 0);
                }

                if(get_option('article_url_redirection_flag') === false) {
                    update_option('article_url_redirection_flag', 0);
                }

                $category_option_value = get_option('category_url_redirection_flag');
                $article_option_value = get_option('article_url_redirection_flag');

                if($category_option_value === '1') {
                    $cat_checked = 'checked';
                } else{
                    $cat_checked = '';
                }

                if($article_option_value === '1') {
                    $article_checked = 'checked';
                } else{
                    $article_checked = '';
                }
            ?>
    </div>
    
</div>