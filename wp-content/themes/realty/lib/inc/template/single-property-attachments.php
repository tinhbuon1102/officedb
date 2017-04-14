<?php
	$property_title_attachments = $realty_theme_option['property-title-attachments'];
	if ( $property_title_attachments ) {
?>
	<h3 class="section-title"><span><?php echo $property_title_attachments; ?></span></h3>
<?php } ?>

<ul class="list-unstyled row">
	<?php foreach ( $property_attachments as $attachment_id ) { ?>
		<?php
			$attachment_url = wp_get_attachment_url( $attachment_id );
			$attachment_file_type = wp_check_filetype( $attachment_url );
			$attachment_file_type = $attachment_file_type['ext'];
			$output  = '<li class="col-sm-4 col-md-3">';
			$output .= tt_icon_attachment($attachment_file_type) . ' <a href="' . $attachment_url . '" target="_blank">' . get_the_title( $attachment_id ) . '</a>';
			$output .= '</li>';
			echo $output;
		?>
	<?php } ?>
</ul>
