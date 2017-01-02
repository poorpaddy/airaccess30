<?php

// Custom Text Widget without <div>
class Custom_Widget_Text extends WP_Widget {

	function __construct() {
		$widget_ops = array(
				'classname'   => 'widget_text',
				'description' => __( 'Arbitrary text or HTML', 'acaawp' ),
				);
		$control_ops = array(
				'width'  => 400,
				'height' => 350,
				);
		parent::__construct( 'text', __( 'Text', 'acaawp' ), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );
		$title	= apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base );
		$text	= apply_filters( 'widget_text', $instance['text'], $instance );
		echo $before_widget;
		if ( !empty( $title ) ) {
			echo $before_title . $title . $after_title;
		}
		echo $instance['filter'] ? wpautop($text) : $text;
		echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );
		if ( current_user_can( 'unfiltered_html' ) )
			$instance['text'] =  $new_instance['text'];
		else
			$instance['text'] = stripslashes( wp_filter_post_kses( addslashes( $new_instance['text'] ) ) ); // wp_filter_post_kses() expects slashed
		$instance['filter'] = isset( $new_instance['filter'] );

		//replace site-url by shortcodes
		$instance['text'] = str_replace( get_template_directory_uri(), '[template-url]', $instance['text'] );
		$instance['text'] = str_replace( home_url(), '[site-url]', $instance['text'] );

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '', 'text' => '' ) );
		$title    = strip_tags( $instance['title'] );
		$text     = esc_textarea( $instance['text'] );
?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'acaawp' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>

		<textarea class="widefat" rows="16" cols="20" id="<?php echo $this->get_field_id( 'text' ); ?>" name="<?php echo $this->get_field_name( 'text' ); ?>"><?php echo $text; ?></textarea>

		<p><input id="<?php echo $this->get_field_id( 'filter' ); ?>" name="<?php echo $this->get_field_name( 'filter' ); ?>" type="checkbox" <?php checked( isset( $instance['filter'] ) ? $instance['filter'] : 0 ); ?> />&nbsp;<label for="<?php echo $this->get_field_id( 'filter' ); ?>"><?php _e( 'Automatically add paragraphs', 'acaawp' ); ?></label></p>
<?php
	}
}

add_action( 'widgets_init', create_function( '', 'unregister_widget( "WP_Widget_Text" ); return register_widget( "Custom_Widget_Text" );' ) );


//Custom widget Recent Posts From Specific Category
class Widget_Recent_Posts_From_Category extends WP_Widget {

	function __construct() {
		$widget_ops = array(
				'classname' => 'widget_recent_entries_from_category',
				'description' => __( 'The most recent posts from specific category on your site', 'acaawp' ),
				);
		parent::__construct( 'recent-posts-from-category', __( 'Recent Posts From Specific Category', 'acaawp' ), $widget_ops );
		$this->alt_option_name = 'widget_recent_entries_from_category';
		
		add_action( 'save_post',    array( &$this, 'flush_widget_cache' ) );
		add_action( 'deleted_post', array( &$this, 'flush_widget_cache' ) );
		add_action( 'switch_theme', array( &$this, 'flush_widget_cache' ) );
	}

	function widget( $args, $instance ) {
		$cache = wp_cache_get( 'widget_recent_posts_from_category', 'widget' );
		
		if ( !is_array( $cache ) )
			$cache = array();
		
		if ( isset( $cache[$args['widget_id']] ) ) {
			echo $cache[$args['widget_id']];
			return;
		}
		
		ob_start();
		extract( $args );
		
		$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? __( 'Recent Posts', 'acaawp' ) : $instance['title'], $instance, $this->id_base );
		if ( ! $number = absint( $instance['number'] ) )
			$number = 10;
		$q_args = array(
			'posts_per_page'      => $number,
			'no_found_rows'       => true,
			'post_status'         => 'publish',
			'ignore_sticky_posts' => true,
			'cat'                 => $instance['cat'],
		);
		if ( is_singular( 'post' ) ) {
			global $post;
			$q_args['post__not_in'] = array( $post->ID );
		}
		$r = new WP_Query( $q_args );
		if ( $r->have_posts() ) :
		?>
		<?php echo $before_widget; ?>
		<?php if ( $title ) echo '<h2>' . $title . '</h2>'; ?>
		<div class="thumbnail-wrap">
			<?php  while ( $r->have_posts() ) : $r->the_post(); ?>
				<div class="thumbnail">
					<a href="<?php the_permalink(); ?>" class="visual">
						<?php the_post_thumbnail( 'thumbnail_287x287' ); ?>
						<span class="caption">
							<span class="hold"><?php the_title(); ?></span>
						</span>
					</a>
				</div>
			<?php endwhile; ?>
		</div>
		<?php echo $after_widget; ?>
		<?php
		wp_reset_postdata();
		
		endif;
		
		$cache[$args['widget_id']] = ob_get_flush();
		wp_cache_set( 'widget_recent_posts_from_category', $cache, 'widget' );
	}

	function update( $new_instance, $old_instance ) {
		$instance           = $old_instance;
		$instance['title']  = strip_tags( $new_instance['title'] );
		$instance['number'] = (int) $new_instance['number'];
		$instance['cat']    = (int) $new_instance['cat'];
		$this->flush_widget_cache();
		
		$alloptions = wp_cache_get( 'alloptions', 'options' );
		if ( isset( $alloptions['widget_recent_entries_from_category'] ) )
			delete_option( 'widget_recent_entries_from_category' );
		
		return $instance;
	}

	function flush_widget_cache() {
		wp_cache_delete( 'widget_recent_posts_from_category', 'widget' );
	}

	function form( $instance ) {
		$title	= isset( $instance['title'] )  ? esc_attr( $instance['title'] ) : '';
		$number	= isset( $instance['number'] ) ? absint( $instance['number'] ) : 5;
		$cat	= isset( $instance['cat'] )    ? $instance['cat'] : 0;
		?>
<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'acaawp' ); ?></label>
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo $title; ?>" /></p>

<p>
<label>
<?php _e( 'Category', 'acaawp' ); ?>:
<?php wp_dropdown_categories( array( 'name' => $this->get_field_name("cat"), 'selected' => $cat ) ); ?>
</label>
</p>

<p><label for="<?php echo $this->get_field_id( 'number' ); ?>"><?php _e( 'Number of posts to show:', 'acaawp' ); ?></label>
<input id="<?php echo $this->get_field_id( 'number' ); ?>" name="<?php echo $this->get_field_name( 'number' ); ?>" type="text" value="<?php echo $number; ?>" size="3" /></p>
<?php
	}
}
add_action( 'widgets_init', create_function( '', 'return register_widget( "Widget_Recent_Posts_From_Category" );' ) );


// Custom Widget Share Story
class Custom_Widget_Share_Story extends WP_Widget {

	function __construct() {
		$widget_ops = array(
				'classname'   => 'widget_share_story',
				'description' => __( 'Share Story Widget', 'acaawp' ),
				);
		$control_ops = array(
				'width'  => 400,
				'height' => 350,
				);
		parent::__construct( 'share_story', __( 'Share story', 'acaawp' ), $widget_ops, $control_ops );
	}

	function widget( $args, $instance ) {
		extract( $args );
		$title	= apply_filters( 'widget_title', empty($instance['title']) ? '' : $instance['title'], $instance, $this->id_base );
		echo $before_widget; ?>
		<div class="story-box">
			<?php if ( !empty( $title ) ) : ?>
				<div class="title">
					<h3><span class="ico icon-microphone"></span> <?php echo $title; ?></h3>
				</div>
			<?php endif; ?>
			<?php
				$image = get_field( 'share_story_image', 'widget_' . $widget_id, false );
				$text = get_field( 'share_story_text', 'widget_' . $widget_id );
				$link = get_field( 'share_story_link', 'widget_' . $widget_id );
				$link_text = get_field( 'share_story_link_text', 'widget_' . $widget_id );
			?>
			<?php if ( $image || $text || ( $link && $link_text ) ) : ?>
				<div class="body-stopy">
					<?php if ( $image ) : ?>
						<div class="visual">
							<?php echo wp_get_attachment_image( $image, 'thumbnail_330x9999' ); ?>
						</div>
					<?php endif; ?>
					<?php echo $text; ?>
					<?php if ( $link && $link_text ) : ?>
						<div class="btn-hold">
							<a href="<?php echo esc_url( $link ); ?>" class="btn"><?php echo esc_html( $link_text ); ?></a>
						</div>
					<?php endif; ?>
				</div>
			<?php endif; ?>
		</div>
		<?php echo $after_widget;
	}

	function update( $new_instance, $old_instance ) {
		$instance = $old_instance;
		$instance['title'] = strip_tags( $new_instance['title'] );

		return $instance;
	}

	function form( $instance ) {
		$instance = wp_parse_args( (array) $instance, array( 'title' => '' ) );
		$title    = strip_tags( $instance['title'] );
?>
		<p><label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Title:', 'acaawp' ); ?></label>
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" /></p>
		
<?php
	}
}

add_action( 'widgets_init', create_function( '', 'return register_widget( "Custom_Widget_Share_Story" );' ) );