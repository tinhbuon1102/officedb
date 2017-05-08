<?php
/**
 * Allow HTML in author bio/description
 *
 */

global $realty_theme_option;

if ( isset( $realty_theme_option['allow-html-author-bio'] )&& ! empty( $realty_theme_option['allow-html-author-bio'] ) ) {
	remove_filter( 'pre_user_description', 'wp_filter_kses' );
}

/**
 * Retrieve attachment ID from file URL
 * https://pippinsplugins.com/retrieve-attachment-id-from-image-url/
 *
 */
if ( ! function_exists( 'tt_get_image_id' ) ) {
	function tt_get_image_id( $image_url ) {
		global $wpdb;
		$attachment = $wpdb->get_col( $wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ) );
	  return $attachment[0];
	}
}

/**
 * Add Role "agent". Run Once After Theme Activation.
 * http://advent.squareonemd.co.uk/controlling-wordpress-custom-post-types-capabilities-and-roles/
 *
 */
if ( ! function_exists( 'tt_add_role_agent' ) ) {
	function tt_add_role_agent() {

		global $wp_roles;
		//$wp_roles->remove_role( 'agent' );
		$capabilities = array(
			'unfiltered_html' => true,
			'upload_files'    => true,
			'level_1'         => true
		);

		$wp_roles->add_role( 'agent', esc_html__( 'Agent', 'realty' ), $capabilities );

		// Remove "read" Capability For "subscriber" Role.
		$role_subscriber = get_role( 'subscriber' );
		if ( is_object( $role_subscriber ) && method_exists( $wp_roles,'add_cap' ) ) {
			$role_subscriber->remove_cap( 'read' );
			$role_subscriber->add_cap( 'upload_files' );
		}

	}
}
add_action( 'after_switch_theme', 'tt_add_role_agent' );
//add_action( 'init', 'tt_add_role_agent' );

/**
 * Author Base Rewrite
 * http://wordpress.org/support/topic/how-to-change-author-base-without-front
 *
 */
if ( ! function_exists( 'tt_author_permalinks' ) ) {
	function tt_author_permalinks() {

		global $wp_rewrite, $wp_roles;
		$wp_rewrite->author_base = 'agent';
		$wp_rewrite->author_structure = '/' . $wp_rewrite->author_base . '/%author%';

		$role_agent = get_role( 'agent' );

		if ( method_exists( $wp_roles, 'add_cap' ) && is_object( $role_agent ) ) {
			$role_agent->add_cap('upload_files');
			$role_agent->add_cap( 'publish_posts' );
			$role_agent->add_cap( 'delete_published_posts' );
			$role_agent->remove_cap( 'read' );
			$role_agent->add_cap( 'edit_posts' );
			$role_agent->add_cap( 'delete_posts' );
			$role_agent->add_cap( 'edit_published_posts' );
			$role_agent->add_cap( 'edit_others_posts' );
		}

	}
}
add_action( 'init', 'tt_author_permalinks' );

/**
 * Blog
 *
 */
if ( ! function_exists( 'tt_blog_pagination' ) ) {
	function tt_blog_pagination() {

		// Don't print empty markup if there's only one page.
		if ( $GLOBALS['wp_query']->max_num_pages < 2 ) {
			return;
		}

		$paged        = get_query_var( 'paged' ) ? intval( get_query_var( 'paged' ) ) : 1;
		$pagenum_link = html_entity_decode( get_pagenum_link() );
		$query_args   = array();
		$url_parts    = explode( '?', $pagenum_link );

		if ( isset( $url_parts[1] ) ) {
			wp_parse_str( $url_parts[1], $query_args );
		}

		$pagenum_link = remove_query_arg( array_keys( $query_args ), $pagenum_link );
		$pagenum_link = trailingslashit( $pagenum_link ) . '%_%';

		$format  = $GLOBALS['wp_rewrite']->using_index_permalinks() && ! strpos( $pagenum_link, 'index.php' ) ? 'index.php/' : '';
		$format .= $GLOBALS['wp_rewrite']->using_permalinks() ? user_trailingslashit( 'page/%#%', 'paged' ) : '?paged=%#%';

		// Set up paginated links.
		$links = paginate_links( array(
			'base'     		=> $pagenum_link,
			'format'   		=> $format,
			'total'    		=> $GLOBALS['wp_query']->max_num_pages,
			'current'  		=> $paged,
			'end_size'    => 4,
			'mid_size'    => 2,
			'type'				=> 'list',
			'add_args' 		=> array_map( 'urlencode', $query_args ),
			'prev_text' 	=> '<i class="icon-arrow-left"></i>',
			'next_text' 	=> '<i class="icon-arrow-right"></i>',
		) );
		?>

		<?php if ( $links ) {	?>
			<nav id="pagination" role="navigation">
				<?php echo $links; ?>
			</nav>
		<?php }

	}
}

/**
 * Open Graph Meta Tag (Facebook Sharer Thumbnail Assignment)
 *
 */
if ( ! function_exists( 'tt_meta_open_graph' ) ) {
	function tt_meta_open_graph() {

		global $post;

		if ( is_singular() ) {
			if ( has_post_thumbnail( $post->ID ) ) {
				$thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
				echo '<meta property="og:image" content="' . esc_attr( $thumbnail_src[0] ) . '" />';
			}
		}

	}
}
add_action( 'wp_head', 'tt_meta_open_graph', 5 );

/**
 * Taxonomy Query
 *
 */
if ( ! function_exists( 'tt_taxonomy_query' ) ) {
	function tt_taxonomy_query( $query ) {
		if ( is_tax() ) {

			// Search Results Per Page: Check for Theme Option
			global $realty_theme_option;
			$search_results_per_page = $realty_theme_option['search-results-per-page'];

			if ( $search_results_per_page ) {
				$query->set( 'posts_per_page', $search_results_per_page );
			}

	  }
	}
}
add_action( 'pre_get_posts', 'tt_taxonomy_query' );

/**
 * Excerpt
 *
 */
if ( ! function_exists( 'tt_excerpt_more' ) ) {
	function tt_excerpt_more( $more ) {
		return ' ..';
	}
}
add_filter( 'excerpt_more', 'tt_excerpt_more' );

if ( ! function_exists( 'tt_excerpt_length' ) ) {
	function tt_excerpt_length( $length ) {
		return 20;
	}
}
add_filter( 'excerpt_length', 'tt_excerpt_length', 999 );

/**
 * Post Classes
 *
 */
if ( ! function_exists( 'tt_post_classes' ) ) {
	function tt_post_classes( $classes ) {
		// Add .border-box only to blog posts index, single posts
		if ( is_single() || is_home() ) {
			$classes[] = 'border-box';
		}
		return $classes;
	}
}
add_filter( 'post_class', 'tt_post_classes' );

/**
 * Page Banner
 *
 */
if ( ! function_exists( 'tt_page_banner' ) ) {
	function tt_page_banner() {
		if ( has_post_thumbnail( get_the_ID() ) ) {
			$page_id = get_option( 'page_for_posts' );
			$post_thumbnail_id = get_post_thumbnail_id( $page_id );
			$post_thumbnail = wp_get_attachment_image_src( $post_thumbnail_id, 'full', false );
			?>
			<div id="page-banner" class="page-header" style="background-image: url(<?php echo $post_thumbnail[0]; ?>)">
				<div class="overlay"></div>
				<div class="container">
					<h1 class="banner-title"><?php echo get_the_title( $page_id ); ?></h1>
				</div>
			</div>
			<?php
		}
	}
}

/**
 * Post Content & Navigation
 *
 */
if ( ! function_exists( 'tt_post_content_navigation' ) ) {
	function tt_post_content_navigation() {
	?>
		<div class="entry-content">
			<?php
				the_content( esc_html__( 'Read More', 'realty' ) );
				wp_link_pages( array(
					'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'realty' ) . '</span>',
					'after'       => '</div>',
					'link_before' => '<span>',
					'link_after'  => '</span>',
				) );
			?>
		</div>
		<?php
			if ( is_single() ) {
				tt_social_sharing();
			}
	}
}

/**
 * Social Sharing Buttons
 *
 */
if ( ! function_exists( 'tt_social_sharing' ) ) {
	function tt_social_sharing() {
	?>
	  <div id="share-post" class="social">
			<a href="http://www.facebook.com/share.php?u=<?php echo esc_url( get_permalink() ); ?>" target="_blank"><i class="icon-facebook facebook" data-toggle="tooltip" title="<?php esc_html_e( 'Share on Facebook', 'realty' ); ?>"></i></a>
			<a href="http://twitter.com/share?text=<?php the_title(); ?>&url=<?php echo esc_url( get_permalink() ); ?>" target="_blank"><i class="icon-twitter twitter" data-toggle="tooltip" title="<?php esc_html_e( 'Share on Twitter', 'realty' ); ?>"></i></a>
			<a href="https://plus.google.com/share?url=<?php echo esc_url( get_permalink() ); ?>" target="_blank"><i class="icon-google-plus google-plus" data-toggle="tooltip" title="<?php esc_html_e( 'Share on Google+', 'realty' ); ?>"></i></a>
			<?php
			if ( has_post_thumbnail() ) {
				$attachment_id =  get_post_thumbnail_id();
				$attachment_array = wp_get_attachment_image_src( $attachment_id );
			?>
			<a href="http://pinterest.com/pin/create/button/?url=<?php echo esc_url( get_permalink() ); ?>&media=<?php echo $attachment_array[0]; ?>&description=<?php echo strip_tags( get_the_excerpt() ); ?>" target="_blank"><i class="icon-pinterest pinterest" data-toggle="tooltip" title="<?php esc_html_e( 'Share on Pinterest', 'realty' ); ?>"></i></a>
			<?php } ?>
		</div>

		<?php
	}
}

/**
 * Comments
 *
 */
if ( ! function_exists( 'tt_list_comments' ) ) {
	function tt_list_comments($comment, $args, $depth) {
		$GLOBALS['comment'] = $comment;
		$args = array(
			'walker'            => null,
			'max_depth'         => '10',
			'style'             => 'ul',
			'callback'          => null,
			'end-callback'      => null,
			'type'              => 'comment',
			'reply_text'        => esc_html__( 'Reply', 'realty' ),
			'page'              => '',
			'per_page'          => '',
			'avatar_size'       => 130,
			'reverse_top_level' => null,
			'reverse_children'  => '',
			'format'            => 'html5',
			'short_ping'        => true
		);

		if ( 'div' == $args['style'] ) {
			$tag = 'div';
			$add_below = 'comment';
		} else {
			$tag = 'li';
			$add_below = 'div-comment';
		}
		?>
		<<?php echo esc_html( $tag ); ?>  <?php echo $tag ?> <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID(); ?>">

		<?php if ( 'div' != $args['style'] ) : ?>
			<div id="div-comment-<?php comment_ID() ?>" class="comment-body">
		<?php endif; ?>

			<div class="comment-avatar">
				<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
			</div>

			<div class="comment-author vcard">
				<h5 class="fn"><?php echo get_comment_author_link(); ?></h5>

				<?php if ($comment->comment_approved == '0') : ?>
					<em class="comment-awaiting-moderation"><?php esc_html_e( 'Your comment is awaiting moderation.', 'realty' ) ?></em><br />
				<?php endif; ?>

				<div class="comment-meta">
					<span><?php echo human_time_diff( get_comment_time( 'U' ), current_time( 'timestamp' ) ) . " " . esc_html__( 'ago', 'realty' ); ?></span>
				</div>

			</div>

			<div class="comment-content">
				<?php comment_text() ?>
				<?php if ( comments_open() ) { ?>
					<div class="reply btn btn-default btn-sm">
						<?php comment_reply_link( array_merge( $args, array( 'add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
					</div>
				<?php } ?>
			</div>

		<?php if ( 'div' != $args['style'] ) : ?>
			</div>
		<?php endif; ?>

	<?php
	}
}

/**
 * Get page ID set to "User - Profile" Page Template
 *
 */
if ( ! function_exists( 'tt_page_id_user_profile' ) ) {
	function tt_page_id_user_profile() {

		$template_user_profile_page_id = null;
		$template_user_profile_array = get_pages( array (
			'meta_key'   => '_wp_page_template',
			'meta_value' => 'template-user-profile.php'
		) );

		if ( $template_user_profile_array ) {
			$template_user_profile_page_id = $template_user_profile_array[0]->ID;
		}

		return $template_user_profile_page_id;

	}
}

/**
 * Get page ID set to "User - Favorites" Page Template
 *
 */
if ( ! function_exists( 'tt_page_id_user_favorites' ) ) {
	function tt_page_id_user_favorites() {

		$template_user_favorites_page_id = null;
		$template_user_favorites_array = get_pages( array (
			'meta_key'   => '_wp_page_template',
			'meta_value' => 'template-user-favorites.php'
		) );

		if ( $template_user_favorites_array ) {
			$template_user_favorites_page_id = $template_user_favorites_array[0]->ID;
		}

		return $template_user_favorites_page_id;

	}
}

/**
 * Get page ID set to "Property Submit" Page Template
 *
 */
if ( ! function_exists( 'tt_page_id_property_submit' ) ) {
	function tt_page_id_property_submit() {

		$template_property_submit_page_id = null;
		$template_property_submit_array = get_pages( array (
			'meta_key'   => '_wp_page_template',
			'meta_value' => 'template-property-submit.php'
		) );

		if ( $template_property_submit_array ) {
			$template_property_submit_page_id = $template_property_submit_array[0]->ID;
		}

		return $template_property_submit_page_id;

	}
}

/**
 * Get page ID set to "Property Submit Listing" Page Template
 *
 */
if ( ! function_exists( 'tt_page_id_property_submit_listing' ) ) {
	function tt_page_id_property_submit_listing() {

		$template_property_submit_listing_page_id = null;
		$template_property_submit_listing_array = get_pages( array (
			'meta_key'   => '_wp_page_template',
			'meta_value' => 'template-property-submit-listing.php'
		) );

		if ( $template_property_submit_listing_array ) {
			$template_property_submit_listing_page_id = $template_property_submit_listing_array[0]->ID;
		}

		return $template_property_submit_listing_page_id;

	}
}

/**
 * Get page ID set to "User Login" Page Template
 *
 */
if ( ! function_exists( 'tt_page_id_user_login' ) ) {
	function tt_page_id_user_login() {

		$template_user_login_page_id = null;
		$template_user_login_array = get_pages( array (
			'meta_key'   => '_wp_page_template',
			'meta_value' => 'template-user-login.php'
		)	);

		if ( $template_user_login_array  ) {
			$template_user_login_page_id = $template_user_login_array[0]->ID;
		} else {
		 $template_user_login_page_id = '#login-modal';
		}

		return $template_user_login_page_id;

	}
}

/**
 * Get page ID of "Property Search" Page Template
 *
 */
if ( ! function_exists( 'tt_page_id_template_search' ) ) {
	function tt_page_id_template_search() {

		global $realty_theme_option;

		$current_lang = pll_current_language();
		$translations = pll_get_post_translations ($realty_theme_option['property-search-results-page']);
		
		if ( isset( $realty_theme_option['property-search-results-page'] ) && ! empty( $realty_theme_option['property-search-results-page'] ) ) {
			// @since v3.0
// 			return $realty_theme_option['property-search-results-page'];
			return $translations[$current_lang];
		} else {
			// @until v2.4.3


			$template_page_property_search_array = array();


			$template_page_property_search_array[] = get_pages( array (
				'meta_key' => '_wp_page_template',
				'meta_value' => 'template-map-vertical.php'
			));
			$template_page_property_search_array[] = get_pages( array (
				'meta_key' => '_wp_page_template',
				'meta_value' => 'template-property-search.php'
			) );


			if ( ! empty( $template_page_property_search_array[0] ) ) {
				foreach ( $template_page_property_search_array as $template_page_property_search ) {
					$translations = pll_get_post_translations ($template_page_property_search->ID);
					return $translations[$current_lang];
					
// 					return $template_page_property_search = $template_page_property_search->ID;
					break;
				}
			}

		}

	}
}

/**
 * Restrict users to media they have uploaded.
 *
 */
if ( ! function_exists( 'tt_show_users_own_media_library' ) ) {
	function tt_show_users_own_media_library( $query ) {

		$id = get_current_user_id();
		 if ( ! current_user_can( 'manage_options' ) )
		 $query['author'] = $id;
		 return $query;

	}
}
add_filter( 'ajax_query_attachments_args', 'tt_show_users_own_media_library', 11, 1 );

/**
 * User Profile - Additional Fields
 *
 */
if ( ! function_exists( 'tt_custom_user_contact_methods' ) ) {
	function tt_custom_user_contact_methods( $user_contact ) {

		$user_contact['company_name']        = esc_html__( 'Company Name', 'realty' );
		$user_contact['office_phone_number'] = esc_html__( 'Office Phone Number', 'realty' );
		$user_contact['mobile_phone_number'] = esc_html__( 'Mobile Phone Number', 'realty' );
		$user_contact['fax_number']          = esc_html__( 'Fax Number', 'realty' );
		$user_contact['custom_facebook']     = esc_html__( 'Facebook', 'realty' );
		$user_contact['custom_twitter']      = esc_html__( 'Twitter', 'realty' );
		$user_contact['custom_google']       = esc_html__( 'Google+', 'realty' );
		$user_contact['custom_linkedin']     = esc_html__( 'Linkedin', 'realty' );

		$user_contact['subscribed_package']                    = esc_html__( 'Membership Plan', 'realty' );
		$user_contact['subscribed_package_default_id']         = esc_html__( 'Membership Plan Default ID', 'realty' );
		$user_contact['user_package_activation_time']          = esc_html__( 'Activation Date', 'realty' );
		$user_contact['subscribed_listing_remaining']          = esc_html__( 'Remaining Allowed Listings', 'realty' );
		$user_contact['subscribed_featured_listing_remaining'] = esc_html__( 'Remaining Allowed Featured Listings', 'realty' );
		$user_contact['subscribed_free_package_once']          = esc_html__( 'Free Package Subscribed', 'realty' );

		return $user_contact;

	}
}
add_filter( 'user_contactmethods', 'tt_custom_user_contact_methods' );

/**
 * User Profile - Additional Field "Image"
 * http://www.flyinghippo.com/blog/adding-custom-fields-uploading-images-wordpress-users/
 *
 */
if ( ! function_exists( 'tt_user_profile_picture_field' ) ) {
	function tt_user_profile_picture_field( $user ) {

	?>
	<style type="text/css">
		.profile-upload-options th,
		.profile-upload-options td,
		.profile-upload-options input {
			margin: 0;
			vertical-align: top;
		}
		.user-preview-image {
			display: block;
			height: auto;
			width: 300px;
		}
	</style>

	<table class="form-table profile-upload-options">
		<tr>
			<th>
				<label for="user_image"><?php _e( 'Profile Picture', 'realty' ); ?></label>
			</th>
			<td>
				<img class="user-preview-image" src="<?php echo esc_attr( get_the_author_meta( 'user_image', $user->ID ) ); ?>"><br />
				<input type="text" name="user_image" id="user_image" value="<?php echo esc_attr( get_the_author_meta( 'user_image', $user->ID ) ); ?>" class="regular-text" />
				<input type='button' class="button-primary" value="Upload Image" id="uploadimage"/><br />
			</td>
		</tr>
	</table>

	<script type="text/javascript">
		(function($) {
			<?php add_thickbox(); ?>
			$( '#uploadimage' ).on( 'click', function() {
				tb_show( 'Profile Picture', 'media-upload.php?type=image&TB_iframe=1' );
				window.send_to_editor = function( html )
				{
					imgurl = $( 'img', html ).attr( 'src' );
					$( '#user_image' ).val(imgurl);
					tb_remove();
				}
				return false;
			});
		})(jQuery);
	</script>
	<?php

	}
}
add_action( 'show_user_profile', 'tt_user_profile_picture_field' );
add_action( 'edit_user_profile', 'tt_user_profile_picture_field' );

/**
 * User Profile - Save "Image" Field
 *
 */
if ( ! function_exists( 'tt_save_user_profile_picture_field' ) ) {
	function tt_save_user_profile_picture_field( $user_id ) {
		if ( ! current_user_can( 'edit_user', $user_id ) ) {
			return false;
		}
		update_user_meta( $user_id, 'user_image', $_POST[ 'user_image' ] );
	}
}
add_action( 'personal_options_update', 'tt_save_user_profile_picture_field' );
add_action( 'edit_user_profile_update', 'tt_save_user_profile_picture_field' );

/**
 * User Profile - Delete User Image
 *
 */
if ( ! function_exists( 'tt_ajax_delete_user_profile_picture_function' ) ) {
	function tt_ajax_delete_user_profile_picture_function() {
		delete_user_meta( $_POST['user_id'], 'user_image' );
		die;
	}
}
add_action( 'wp_ajax_tt_ajax_delete_user_profile_picture_function', 'tt_ajax_delete_user_profile_picture_function' );

/**
 * dsIDX ACF conflict fix
 *
 */
if ( ! function_exists( 'tt_acf_idx_conflict_script' ) ) {
	function tt_acf_idx_conflict_script() {

		global $post_type;
	  if ( 'property' == $post_type ) {
			wp_dequeue_script( 'googlemaps3' );
			wp_dequeue_script( 'dsidxpress_google_maps_geocode_api' );
			//wp_enqueue_script( 'google-maps-api', '//maps.google.com/maps/api/js?sensor=false&libraries=places&v=3.18', array( 'jquery' ), null, false  );
	  }

	}
}
add_action( 'admin_print_scripts-post-new.php', 'tt_acf_idx_conflict_script', 11 );
add_action( 'admin_print_scripts-post.php', 'tt_acf_idx_conflict_script', 11 );
