<?php
/*
Template Name: Story Form Template
*/
get_header(); ?>
<main id="main">
	<?php get_template_part( 'blocks/top_banner' ); ?>
	<div class="container">
		<div id="content">
			<?php while ( have_posts( ) ) : the_post(); ?>
				<?php if ( has_post_thumbnail() ) : ?>
					<div class="visual-wrap"><?php the_post_thumbnail( 'thumbnail_794x342' ); ?></div>
				<?php endif; ?>
				<?php the_title( '<h2>', '</h2>' ); ?>
				<?php the_content(); ?>
				<?php edit_post_link( __( 'Edit', 'acaawp' ) ); ?>
			<?php endwhile; ?>
			<?php wp_link_pages(); ?>
			<?php comments_template(); ?>
		</div>
	</div>
</main>
<?php get_footer(); ?>