<?php get_header(); ?>
<main id="main">
	<?php get_template_part( 'blocks/top_banner' ); ?>
	<div class="container">
		<div id="content">
			<div class="text-content">
				<?php the_archive_title( '<h2>', '</h2>' ); ?>
			</div>
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
