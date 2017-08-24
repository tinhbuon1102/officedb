<article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>

<?php echo tt_post_content_navigation(); ?>
<div class="goback-wrap"><a class="goback-home" href="<?php echo esc_url( home_url( '/' ) ); ?>"><?php esc_html_e( 'トップページへ戻る', 'realty' ); ?></a></div>
</article>