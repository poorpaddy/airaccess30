<?php get_header(); ?>
<main id="main">
	<?php get_template_part( 'blocks/top_banner' ); ?>
	<div class="container">
		<div id="twocolumns">
			<div id="content">
				<div class="text-content">
					<h2><?php printf( __( 'Search Results for: %s', 'acaawp' ), '<span>' . get_search_query() . '</span>'); ?></h2>
				</div>
				<?php if ( have_posts() ) : ?>
					<?php while ( have_posts() ) : the_post(); ?>
						<?php get_template_part( 'blocks/content', get_post_type() ); ?>
					<?php endwhile; ?>
					<?php get_template_part( 'blocks/pager' ); ?>
				<?php else: ?>
					<?php get_template_part( 'blocks/not_found' ); ?>
				<?php endif; ?>
			</div>
			<?php get_sidebar(); ?>
		</div>
	</div>
</main>
<?php get_footer(); ?>
