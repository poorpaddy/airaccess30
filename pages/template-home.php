<?php
/*
Template Name: Home Template
*/
get_header(); ?>
<main id="main">
	<?php while ( have_posts( ) ) : the_post(); ?>
		<?php
			$image = get_field( 'banner_image' );
			$image_mobile = get_field( 'banner_image_mobile' );
			$banner_logo = get_field( 'banner_logo' );
			$subtitle = get_field( 'banner_subtitle' );
			$title = get_field( 'banner_title' );
			$text = get_field( 'banner_text' );
			$link = get_field( 'banner_button_link' );
			$link_text = get_field( 'banner_button_link_text' );
		?>
		<?php if ( $image && ( $banner_logo || $subtitle || $title || $text || ( $link && $link_text ) ) ) : ?>
			<div class="banner banner-1">
				<?php if ( $image_mobile ) : ?>
					<img class="mobile-visible" src="<?php echo $image_mobile['url']; ?>" height="<?php echo $image_mobile['height']; ?>" width="<?php echo $image_mobile['width']; ?>" alt="<?php echo $image_mobile['alt']; ?>">
				<?php endif; ?>
				<img class="mobile-hidden" src="<?php echo $image['url']; ?>" height="<?php echo $image['height']; ?>" width="<?php echo $image['width']; ?>" alt="<?php echo $image['alt']; ?>">
				<?php if ( $banner_logo || $subtitle || $title || $text || ( $link && $link_text ) ) : ?>
					<div class="text-wrap">
						<div class="text">
							<div class="hold">
								<?php if ( $banner_logo ) : ?>
									<div class="stiker mobile-hidden">
										<?php echo wp_get_attachment_image( $banner_logo, 'full' ); ?>
									</div>
								<?php endif; ?>
								<?php if ( $subtitle || $title ) : ?>
									<div class="title">
										<?php if ( $subtitle ) : ?>
											<span class="style-1"><?php echo esc_html( $subtitle ); ?></span>
										<?php endif; ?>
										<?php if ( $title ) : ?>
											<span><?php echo esc_html( $title ); ?></span>
										<?php endif; ?>
									</div>
								<?php endif; ?>
								<?php if ( $text || ( $link && $link_text ) ) : ?>
									<div class="body-banner">
										<?php echo $text; ?>
										<?php if ( $link && $link_text ) : ?>
											<a href="<?php echo esc_url( $link ); ?>" class="btn"><?php echo esc_html( $link_text ); ?></a>
										<?php endif; ?>
									</div>
								<?php endif; ?>
							</div>
						</div>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
	<?php endwhile; ?>
	<div class="container">
		<div id="twocolumns">
			<div id="content">
				<?php while ( have_posts( ) ) : the_post(); ?>
					<div class="body-content">
						<?php the_content(); ?>
						<?php edit_post_link( __( 'Edit', 'acaawp' ) ); ?>
					</div>
				<?php endwhile; ?>
			</div>
			<?php get_sidebar(); ?>
		</div>
		<hr>
		<?php
			$home_posts_header = get_field( 'home_posts_header' );
			query_posts( array( 'posts_per_page' => 4 ) );
		?>
		<?php if ( have_posts() ) : ?>
			<div class="block">
				<?php if ( $home_posts_header ) : ?>
					<h2><?php echo esc_html( $home_posts_header ); ?></h2>
				<?php endif; ?>
				<div class="box-wrap">
					<?php while ( have_posts() ) : the_post(); ?>
						<?php get_template_part( 'blocks/content-teaser', get_post_type() ); ?>
					<?php endwhile; ?>
				</div>
			</div>
		<?php endif; ?>
		<?php wp_reset_query(); ?>
	</div>
</main>
<?php get_footer(); ?>