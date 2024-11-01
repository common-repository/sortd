<?php 
?>

<style type="text/css">
    
.m-siteBox {
    width: 100%;
    float: left;
    border-bottom: 1px solid #ccc;
    padding-bottom: 10px;
    margin-bottom: 10px;
}
.check-bxFld {
    width: 70%;
    float: left;
    text-align: right;
}
.m-siteBox h2.card-titl {
    width: 30%;
    float: left;
}
.check-bxFld label.reAlin {
    position: relative;
    top: -2px;
    font-size: 14px;
    font-weight: 400;
}



</style>

<div class="content-section">
    <div class="container-pj">
        <div class="heading-main">
            <div class="logoLft">
               <img src="<?php echo wp_kses_data(SORTD_CSS_URL);?>/logo.png">
                <h5>Enable sortd for mobile website</h5>
            </div>  
            <div class="headingNameTop">
            <button type="button" onclick="window.open('https://support.sortd.mobi/portal/en/kb/gni-adlabs/general','_blank')" class="btn infoIcn icPd0" title="AdLabs Support"><i class="bi bi-info-circle"></i></button>   
            </div>
        </div>

          <div class="cardNdash">
            <!-- heading start -->
            <div class="cardNdash-box plBox">
             <h3>Sortd for mobile website</h3>
            </div>
            <!-- heading end -->


             <div class="container">
                <div class="taxonomycontainer">

                <?php foreach( $html_data as $k => $v) { ?>
                    <div class="m-siteBox">
                        <h2 class="card-titl"><?php echo wp_kses_data($v['postype_name']);?></h2>
                    
                            <div class="check-bxFld">
                            <?php foreach($v['taxonomy_type'] as $key => $value){ ?> 
                                <?php foreach($value as $keyV => $valueV){ 
                                    if($keyV === 'taxonomy_name'){
                                ?> 
                        
                                <input type="checkbox" <?php if($value['taxonomy_slug'] === 'category' || $value['taxonomy_slug'] === 'post_tag') { echo 'disabled';} ?> name="custom_taxonomy_name" id="custom_taxonomy_id_<?php echo wp_kses_data($value['taxonomy_slug']);?>" data-current_user="<?php echo esc_attr($current_user); ?>" data-wp_domain="<?php echo esc_attr($wp_domain); ?>" data-project_slug="<?php echo esc_attr($project_slug); ?>" data-postname="<?php echo wp_kses_data($v['postype_name'])?>" class="custom_taxonomy_class" data-taxonomyname="<?php echo wp_kses_data($valueV) ?>" data-postslug="<?php echo wp_kses_data($v['postype_slug'])?>" data-taxonomyslug="<?php echo wp_kses_data($value['taxonomy_slug']);?>">
                                <label for="custom_taxonomy_name" class="reAlin" data-current_user="<?php echo esc_attr($current_user); ?>" data-wp_domain="<?php echo esc_attr($wp_domain); ?>" data-project_slug="<?php echo esc_attr($project_slug); ?>"> <?php echo wp_kses_data($valueV) ;?>
                                </label>
                                <span id="succ_tax_msg<?php echo wp_kses_data($value['taxonomy_slug']) ;?>" class="succ_tax_class" style="display:none;color:green"></span>

                            <?php  }   ?>

                                <input type="hidden" name="slug_hidden" id="slug_hidden_id" value="<?php echo wp_kses_data($valueV);?>">

                            <?php   }  ?> 
                            <?php } ?>
                            </div>
                    </div>
            <?php } ?>
                </div>
             </div>

         </div>