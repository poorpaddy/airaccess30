<?php get_header(); ?>
<main id="main">
	<?php get_template_part( 'blocks/top_banner' ); ?>
	<div class="container">
		<div id="content">
			<?php if ( $page_for_posts = get_option( 'page_for_posts' ) ) : ?>
				<div class="text-content">
					<h2><?php echo get_the_title( $page_for_posts ); ?></h2>
					<?php
						$content_post = get_post( $page_for_posts );
						echo apply_filters( 'the_content', $content_post->post_content );
						edit_post_link( __( 'Edit', 'acaawp' ), '', '', $page_for_posts );
					?>
				</div>
			<?php endif; ?>
			<?php if ( have_posts() ) : ?>
				<div class="box-wrap">
					<?php while ( have_posts() ) : the_post(); ?>
						<?php get_template_part( 'blocks/content-teaser', get_post_type() ); ?>
					<?php endwhile; ?>
				</div>
				<?php get_template_part( 'blocks/pager' ); ?>
			<?php else: ?>
				<?php get_template_part( 'blocks/not_found' ); ?>
			<?php endif; ?>
		</div>
	</div>
</main>
<?php get_footer(); ?>