<!DOCTYPE html>
<html <?php language_attributes(); ?>>
	<head>
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">		
		<script type="text/javascript">
			var pathInfo = {
				base: '<?php echo get_template_directory_uri(); ?>/',
				css: 'css/',
				js: 'js/',
				swf: 'swf/',
			}
		</script>
		<?php wp_head(); ?>
	</head>
	<body <?php body_class(); ?>>
		<div id="wrapper">
			<div class="container">
				<header id="header">
					<div class="logo"><a href="<?php echo home_url(); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/logo.png" height="106" width="291" alt="<?php bloginfo( 'name' ); ?>"></a></div>
					<nav id="nav">
						<a href="#" class="nav-opener"><span class="icon-burger"></span></a>
						<div class="drop">
							<div class="drop-hold">
								<?php
									$link = get_field( 'header_contact_link', 'option' );
									$link_text = get_field( 'header_contact_link_text', 'option' );
								?>
								<?php if ( $link && $link_text ) : ?>
									<div class="panel">
										<a href="<?php echo esc_url( $link ); ?>" class="contact-us"><span class="icon-email"></span> <?php echo esc_html( $link_text ); ?></a>
									</div>
								<?php endif; ?>
								<?php if ( has_nav_menu( 'primary' ) )
									wp_nav_menu( array(
										'container' => false,
										'theme_location' => 'primary',
										'items_wrap'     => '<ul>%3$s</ul>',
									) ); ?>
							</div>
						</div>
					</nav>
				</header>
			</div>
			