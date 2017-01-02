<?php get_header(); ?>
<main id="main">
	<?php get_template_part( 'blocks/top_banner' ); ?>
	<div class="container">
		<div id="twocolumns">
			<div id="content">
				<?php while ( have_posts() ) : the_post(); ?>
					<article class="article">
						<?php if ( has_post_thumbnail() ) : ?>
							<div class="visual-wrap"><?php the_post_thumbnail( 'thumbnail_794x342' ); ?></div>
						<?php endif; ?>
						<?php the_title( '<h2>', '</h2>' ); ?>
						<?php the_content(); ?>
						<?php edit_post_link( __( 'Edit', 'acaawp' ) ); ?>
					</article>
				<?php endwhile; ?>
				<?php wp_link_pages(); ?>
				<?php comments_template(); ?>
			</div>
			<?php get_sidebar(); ?>
		</div>
	</div>
</main>
<?php get_footer(); ?>
