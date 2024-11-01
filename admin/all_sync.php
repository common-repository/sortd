<?php

    ini_set('max_execution_time', 0);
    $scriptStartTime = time();

    echo "Script started at : ".date('Y-m-d h:i:s',$scriptStartTime)."\n\n";
    require_once('/var/www/html/wordpress/wp-load.php' );   
    $project_id = Sortd_Helper::get_project_id();
    global $wpdb;

    $meta_key = 'sortd_'.$project_id.'_post_sync';
    $meta_value = 1;

    $query = $wpdb->prepare(
        "SELECT post_id FROM {$wpdb->postmeta} WHERE meta_key = %s AND meta_value = %s",
        $meta_key,
        $meta_value
    );

    $results = $wpdb->get_results($query);
    foreach($results as $res) {

        $res = Sortd_Article::sync_article($res->post_id, $post);
        if ($res->status == 1) {
            echo "Article with guid " . $post->ID . " synced successfully\n";
        } else {
            echo "Article with guid " . $post->ID . " was not synced\n";
        }
    }

    echo "Action completed ... \n";
    $scriptStopTime = time();
    echo "Script stop at : ".date('Y-m-d h:i:s',$scriptStopTime)."\n";
    echo "Total running time : ".($scriptStopTime-$scriptStartTime)."\n";
?>
