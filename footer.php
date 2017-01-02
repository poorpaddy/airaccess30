			<footer id="footer">
				<div class="container">
					<div class="freecolumns">
						<?php
							$footer_text_header = get_field( 'footer_text_header', 'option' );
							$footer_text = get_field( 'footer_text', 'option' );
						?>
						<?php if ( $footer_text_header || $footer_text ) : ?>
							<div class="col">
								<?php if ( $footer_text_header ) : ?>
									<h2><?php echo esc_html( $footer_text_header ); ?></h2>
								<?php endif; ?>
								<?php echo $footer_text; ?>
							</div>
						<?php endif; ?>
						<?php if ( has_nav_menu( 'footer_first' ) || has_nav_menu( 'footer_second' ) ) : ?>
							<div class="col">
								<?php if ( $header = get_field( 'footer_learn_more_header', 'option' ) ) : ?>
									<h2><?php echo $header; ?></h2>
								<?php endif; ?>
								<?php if ( has_nav_menu( 'footer_first' ) ) : ?>
									<div class="box">
										<?php if ( $menu_name = _get_theme_menu_name( 'footer_first' ) ) : ?>
											<h3><?php echo $menu_name; ?></h3>
										<?php endif; ?>
										<?php wp_nav_menu( array(
											'container' => 'nav',
											'container_class' => 'menu',
											'theme_location' => 'footer_first',
											'items_wrap'     => '<ul>%3$s</ul>',
										) ); ?>
									</div>
								<?php endif; ?>
								<?php if ( has_nav_menu( 'footer_second' ) ) : ?>
									<div class="box">
										<?php if ( $menu_name = _get_theme_menu_name( 'footer_second' ) ) : ?>
											<h3><?php echo $menu_name; ?></h3>
										<?php endif; ?>
										<?php wp_nav_menu( array(
											'container' => 'nav',
											'container_class' => 'menu',
											'theme_location' => 'footer_second',
											'items_wrap'     => '<ul>%3$s</ul>',
										) ); ?>
									</div>
								<?php endif; ?>
							</div>
						<?php endif; ?>
						<div class="col">
							<div class="logo-footer"><a href="<?php echo home_url(); ?>"><img src="<?php echo get_template_directory_uri(); ?>/images/logo-footer.png" height="102" width="278" alt="<?php bloginfo( 'name' ); ?>"></a></div>
							<?php
								$footer_address = get_field( 'footer_address', 'option' );
								$footer_phone = get_field( 'footer_phone', 'option' );
								$footer_email = get_field( 'footer_email', 'option' );
							?>
							<?php if ( $footer_address || $footer_phone || $footer_email ) : ?>
								<ul class="list-footer">
									<?php if ( $footer_address ) : ?>
										<li>
											<span class="ico icon-point"></span>
											<address>
												<?php echo $footer_address; ?>
											</address>
										</li>
									<?php endif; ?>
									<?php if ( $footer_phone ) : ?>
										<li>
											<a href="tel:<?php echo clean_phone( $footer_phone ); ?>">
												<span class="ico icon-phone"></span>
												<?php echo esc_html( $footer_phone ); ?>
											</a>
										</li>
									<?php endif; ?>
									<?php if ( $footer_email ) : ?>
										<li>
											<a href="mailto:<?php echo antispambot( $footer_email ); ?>">
												<span class="ico icon-email"></span>
												<?php echo antispambot( $footer_email ); ?>
											</a>
										</li>
									<?php endif; ?>
								</ul>
							<?php endif; ?>
							<?php if ( have_rows( 'social_links', 'option' ) ) : ?>
								<ul class="social-networks">
									<?php while ( have_rows( 'social_links', 'option' ) ) : the_row(); ?>
										<?php
											$link_class = get_sub_field( 'link_class' );
											$link = get_sub_field( 'link' );
										?>
										<?php if ( $link_class && $link ) : ?>
											<li>
												<a href="<?php echo esc_url( $link ); ?>" target="_blank">
													<span class="icon-<?php echo esc_attr( $link_class ); ?>"></span>
												</a>
											</li>
										<?php endif; ?>
									<?php endwhile; ?>
								</ul>
							<?php endif; ?>
						</div>
					</div>
					<div class="disclaimer">
						<?php 
						$footer_disclaimer = get_field( 'footer_disclaimer', 'option' );
						if( $footer_disclaimer ) {
							echo $footer_disclaimer;
						} else { echo $footer_disclaimer; }
						?>
					</div>
					<div class="bar">
						<p>&copy; <?php echo date( 'Y' ); ?> <a href="http://www.pva.org">Paralyzed Veterans of America</a> <span class="separator">•</span> All Rights Reserved</p>
					</div>
				</div>
			</footer>
		</div>
		<?php wp_footer(); ?>
	</body>
</html>