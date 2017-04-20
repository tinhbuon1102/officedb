<ul class="latest-news-list row">
<?php
    $recent_posts = wp_get_recent_posts(array('post_type'=>'news'));
    foreach( $recent_posts as $recent ){
        echo '<li class="col-sm-4"><a href="' . get_permalink($recent["ID"]) . '" title="Look '.esc_attr($recent["post_title"]).'" ><div class="post-date">' .   $recent["post_time"] .'</div>'.$recent["post_title"].'</a> </li> ';
    }
?>
</ul>