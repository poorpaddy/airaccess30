<?php if ( $image = get_field( 'top_banner_image', 'option', false ) ) : ?>
	<div class="banner">
		<?php echo wp_get_attachment_image( $image, 'thumbnail_1920x178' ); ?>
		<?php if ( $logo = get_field( 'top_banner_logo', 'option' ) ) : ?>
			<div class="text-wrap">
				<div class="text">
					<div class="stiker mobile-hidden"><?php echo wp_get_attachment_image( $logo, 'full' ); ?></div>
				</div>
			</div>
		<?php endif; ?>
	</div>
<?php endif; ?>