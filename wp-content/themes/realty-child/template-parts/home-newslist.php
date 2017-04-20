<ul class="latest-news-list row">
<?php
    $recent_posts = wp_get_recent_posts(array('post_type'=>'news'));
    foreach( $recent_posts as $recent ){
        echo '<li class="col-sm-4"><div class="inner"><div class="post-date">' .   renderJapaneseDate($recent['post_date']) .'</div><div class="title">'.$recent["post_title"].'</div><div class="meta">'.get_the_category($recent["cat_name"]).'</div><a href="' . get_permalink($recent["ID"]) . '" title="Look '.esc_attr($recent["post_title"]).'" class="check_now" >Check now</a></div></li> ';
    }
?>
</ul>