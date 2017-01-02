<div <?php post_class(); ?> id="post-<?php the_ID(); ?>">
	<div class="title">
			<?php if ( is_single() ) :
				the_title( '<h1>', '</h1>' );
			else :
				the_title( '<h2><a href="' . esc_url( get_permalink() ) . '" rel="bookmark">', '</a></h2>' );
			endif; ?>		
			<p class="info"><strong class="date"><a href="<?php the_permalink() ?>" rel="bookmark"><?php the_time( 'F jS, Y' ) ?></a></strong> <?php _e( 'by', 'acaawp' ); ?> <?php the_author(); ?></p>
	</div>
	<div class="content">
		<?php the_post_thumbnail( 'full' ); ?>
		<?php if ( is_single() ) :
			the_content();
		else:
			theme_the_excerpt();
		endif; ?>
	</div>
	<?php wp_link_pages(); ?>
	<div class="meta">
		<ul>
			<li><?php _e( 'Posted in', 'acaawp' ); ?> <?php the_category( ', ' ) ?></li>
			<li><?php comments_popup_link( __( 'No Comments', 'acaawp' ), __( '1 Comment', 'acaawp' ), __( '% Comments', 'acaawp' ) ); ?></li>
			<?php the_tags( __( '<li>Tags: ', 'acaawp' ), ', ', '</li>' ); ?>
			<?php edit_post_link( __( 'Edit', 'acaawp' ), '<li>', '</li>' ); ?>
		</ul>
	</div>
</div>
