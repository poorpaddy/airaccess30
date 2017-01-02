<?php get_header(); ?>
<main id="main">
	<?php get_template_part( 'blocks/top_banner' ); ?>
	<div class="container">
		<div id="twocolumns">
			<div id="content">
				<?php while ( have_posts() ) : the_post(); ?>
					<article class="article">
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="visual-wrap"><?php the_post_thumbnail( 'thumbnail_794x342' );
							echo get_post(get_post_thumbnail_id())->post_excerpt; ?></div>
						<?php endif; ?>
						<?php
							the_title( '<h2>', '</h2>' );
							the_content();
							wp_link_pages();
							comments_template();
						?>
						<?php 
						$submitter_title = get_field( "submitter_title" );
						$submitter_first_name = get_field( "submitter_first_name" );
						$submitter_last_name = get_field( "submitter_last_name" );
						$submitter_state = get_field( "submitter_state" );
						if( $submitter_title ) {
							echo $submitter_title,' ',$submitter_first_name,' ',$submitter_last_name,', ',$submitter_state;
						} else {
							echo $submitter_first_name,' ',$submitter_last_name,', ',$submitter_state;
						}
						?>
						<?php
							$link = get_field( 'submit_story_link', 'option' );
							$link_text = get_field( 'submit_story_link_text', 'option' );
						?>
						<?php if ( $link && $link_text ) : ?>
							<div class="btn-wrap"><a href="<?php echo esc_url( $link ); ?>" class="btn"><?php echo esc_html( $link_text ); ?></a></div>
						<?php endif; ?>
					</article>
				<?php endwhile; ?>
			</div>
			<?php get_sidebar(); ?>
		</div>
	</div>
</main>
<?php get_footer(); ?>