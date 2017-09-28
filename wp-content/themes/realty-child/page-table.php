<?php
/*
Template Name: Table Test
*/
?>
	<?php get_header(); ?>

	<?php while ( have_posts() ) : the_post(); ?>

		<?php
			/**
			 * Add .container wrapper, if:
			 * (1) VC plugin not active OR
			 * (2) active, but no actual VC elements (vc_row etc.) being used.
			 * Which is the case when updating to v3.0 from a previous Realty version
			 *
			 */
			$raw_content = get_the_content();

			if ( strpos( $raw_content, 'vc_row' ) ) {
				$has_vc_elements = true;
			} else {
				$has_vc_elements = false;
			}
		?>

		<?php if ( ! class_exists( 'Vc_Manager' ) || ! $has_vc_elements ) { ?>
			<div class="container">
		<?php } ?>

			<table class="favorite_list_later">
			<thead>
				<tr>
				<th colspan="2" class="floor_picture">Building Name</th>
				<th class="floor_subscribe">更新情報配信設定</th>
				<th colspan="2" class="floor_name">所在階</th>
				<th class="floor_rent">賃料</th>
				<th class="floor_area">契約面積</th>
				<th class="floor_date_move">入居日</th>
				<th class="floor_contact"></th>
				<th class="floor_action_remove"></th>
				</tr>
			</thead>
			<tbody>
				<tr class="favorite_item">
				<td rowspan="4" class="floor_picture">
					<span class="floor_thumb"><a target="_blank" href="http://premium-office.net/property/%e3%83%8b%e3%83%a5%e3%83%bc%e3%82%aa%e3%83%bc%e3%82%bf%e3%83%8b%e3%82%ac%e3%83%bc%e3%83%87%e3%83%b3%e3%82%b3%e3%83%bc%e3%83%88-2%e9%9a%8e/"><img width="113" height="150" src="http://premium-office.net/wp-content/uploads/2017/09/8995938b660f0b20.jpg" class="attachment-thumbnail size-thumbnail wp-post-image" alt=""></a></span>					
				</td>
				<td rowspan="4" class="bld_name"><span class="floor_name">ニューオータニガーデンコート</span></td>
				<td rowspan="4" class="floor_subscribe"><a class="btn btn-success add-to-follow-popup follow-popup " data-fav-id="12466" data-subscribe="受信する" data-unsubscribe="配信停止" href="javascript:void(0)">受信する</a></td>
				<td class="floor_check">check</td>				
				<td class="floor_rent">floor</td>
				<td class="floor_area">area</td>
				<td class="floor_date_move">2017/06/02</td>
				<td class="floor_contact">Contact</td>
				<td rowspan="4" class="floor_action_remove"><a href="javascript:void(0)" class="remove_property add-to-favorites" data-fav-id="12490">削除</a></td>
				</tr>
				<tr class="favorite_item multiple_items">
				<td class="floor_check">check</td>				
				<td class="floor_rent">floor</td>
				<td class="floor_area">area</td>
				<td class="floor_date_move">2017/06/02</td>
				<td class="floor_contact">Contact</td>
				</tr>
				<tr class="favorite_item multiple_items">
				<td class="floor_check">check</td>				
				<td class="floor_rent">floor</td>
				<td class="floor_area">area</td>
				<td class="floor_date_move">2017/06/02</td>
				<td class="floor_contact">Contact</td>
				</tr>
				<tr class="favorite_item multiple_items">
				<td class="floor_check">check</td>				
				<td class="floor_rent">floor</td>
				<td class="floor_area">area</td>
				<td class="floor_date_move">2017/06/02</td>
				<td class="floor_contact">Contact</td>
				</tr>
			</tbody>
			</table>


		<?php if ( ! class_exists( 'Vc_Manager' ) || ! $has_vc_elements ) { ?>
			</div>
		<?php } ?>

	<?php endwhile; ?>

<?php get_footer(); ?>