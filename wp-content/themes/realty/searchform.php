<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<label>
		<span class="screen-reader-text"><?php _x( 'Search for:', 'label', 'realty' ); ?></span>
		<input type="search" class="search-field" placeholder="<?php echo esc_attr_x( 'Search for &hellip;', 'placeholder', 'realty' ); ?>" value="<?php get_search_query(); ?>" name="s" title="<?php esc_attr_x( 'Search for:', 'label', 'realty' ); ?>" />
	</label>
</form>