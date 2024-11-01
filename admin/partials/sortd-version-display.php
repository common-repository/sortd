<?php

/**
 * Provide a version display view for the plugin
 *
 * This file is used to markup the version display aspects of the plugin.
 *
 * @link       https://www.sortd.mobi
 * @since      2.0.0
 *
 * @package    Sortd
 * @subpackage Sortd/admin/partials
 */
?>
<?php if ( ! defined( 'ABSPATH' ) ) exit;  ?>
<div class="content-section">
  <div class="container-pj">
    <div class="row">
        <div class="col-md-12">
           <div class="sortCard">
              <h5 class="sortHed">Current Version</h5>
              <div class="sort-body">
                <div class="vBox">
                   <p class="cardH">WordP</p>
                   <a href="#" class="valueBx"><?php echo wp_kses_data($version['wpversion']);?></a>
                </div>
                <div class="vBox">
                   <p class="cardH">PHP</p>
                   <a href="#" class="valueBx"><?php echo wp_kses_data($version['phpversion']);?></a>
                </div>
                <div class="vBox">
                   <p class="cardH">CURL</p>
                   <a href="#" class="valueBx"><?php echo  wp_kses_data($version['curl_version']['version']);?></a>
                </div>
                <div class="vBox">
                   <p class="cardH">MySQL</p>
                   <a href="#" class="valueBx"><?php echo  wp_kses_data($version['sql_version']);?></a>
                </div>
                <div class="vBox">
                   <p class="cardH">XML</p>
                   <a href="#" class="valueBx"><?php echo  wp_kses_data($version['phpversion']);?></a>
                </div>
                <div class="vBox">
                   <p class="cardH">DOM</p>
                   <a href="#" class="valueBx"><?php echo  wp_kses_data($version['phpversion']);?></a>
                </div>
              </div>
           </div>
        </div>
        <div class="col-md-12">
           <div class="sortCard">
              <h5 class="sortHed">Minimum Version Required</h5>
              <div class="sort-body">
                <div class="vBox">
                   <p class="cardH">WordP</p>
                   <a href="#" class="valueBx">5.2</a>
                </div>
                <div class="vBox">
                   <p class="cardH">PHP</p>
                   <a href="#" class="valueBx">5.6</a>
                </div>
                <div class="vBox">
                   <p class="cardH">CURL</p>
                   <a href="#" class="valueBx">5.6</a>
                </div>
                <div class="vBox">
                   <p class="cardH">MySQL</p>
                   <a href="#" class="valueBx">5.0</a>
                </div>
                <div class="vBox">
                   <p class="cardH">XML</p>
                   <a href="#" class="valueBx">5.6</a>
                </div>
                <div class="vBox">
                   <p class="cardH">DOM</p>
                   <a href="#" class="valueBx">5.6</a>
                </div>
              </div>
           </div>
        </div>
    </div>
  </div>
</div>