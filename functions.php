<?php

//Staging restrictions
if ( file_exists( sys_get_temp_dir() . '/staging-restrictions.php' ) ) {
	define( 'STAGING_RESTRICTIONS', true );
	require_once sys_get_temp_dir() . '/staging-restrictions.php';
}

function seo_warning() {
	if( get_option( 'blog_public' ) ) return;
	
	$message = __( 'You are blocking access to robots. You must go to your <a href="%s">Reading</a> settings and uncheck the box for Search Engine Visibility.', 'acaawp' );

	echo '<div class="error"><p>';
	printf( $message, admin_url( 'options-reading.php' ) );
	echo '</p></div>';
}
add_action( 'admin_notices', 'seo_warning' );

include( get_template_directory() . '/widgets.php' );

add_action( 'themecheck_checks_loaded', 'theme_disable_cheks' );
function theme_disable_cheks() {
	$disabled_checks = array( 'TagCheck', 'Plugin_Territory', 'CustomCheck', 'EditorStyleCheck' );
	global $themechecks;
	foreach ( $themechecks as $key => $check ) {
		if ( is_object( $check ) && in_array( get_class( $check ), $disabled_checks ) ) {
			unset( $themechecks[$key] );
		}
	}
}

add_theme_support( 'automatic-feed-links' );

if ( !isset( $content_width ) ) {
	$content_width = 900;
}

remove_action( 'wp_head', 'wp_generator' );

add_action( 'after_setup_theme', 'theme_localization' );
function theme_localization () {
	load_theme_textdomain( 'base', get_template_directory() . '/languages' );
}

/*
 * Let WordPress manage the document title.
 * By adding theme support, we declare that this theme does not use a
 * hard-coded <title> tag in the document head, and expect WordPress to
 * provide it for us.
 */
add_theme_support( 'title-tag' );

function theme_widget_init() {
	register_sidebar( array(
		'id'            => 'default-sidebar',
		'name'          => __( 'Default Sidebar', 'acaawp' ),
		'before_widget' => '<div class="widget %2$s" id="%1$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h3>',
		'after_title'   => '</h3>'
	) );
}
add_action( 'widgets_init', 'theme_widget_init' );

add_theme_support( 'post-thumbnails' );
set_post_thumbnail_size( 50, 50, true ); // Normal post thumbnails
add_image_size( 'thumbnail_400x9999', 400, 9999, true );
add_image_size( 'thumbnail_287x287', 287, 287, true );
add_image_size( 'thumbnail_330x9999', 330, 9999, false );
add_image_size( 'thumbnail_794x342', 794, 342, true );
add_image_size( 'thumbnail_1920x178', 1920, 178, true );

register_nav_menus( array(
	'primary' => __( 'Primary Navigation', 'acaawp' ),
	'footer_first' => __( 'Footer First Navigation', 'acaawp' ),
	'footer_second' => __( 'Footer Second Navigation', 'acaawp' ),
) );

//Add [email]...[/email] shortcode
function shortcode_email( $atts, $content ) {
	return antispambot( $content );
}
add_shortcode( 'email', 'shortcode_email' );

//Register tag [template-url]
function filter_template_url( $text ) {
	return str_replace( '[template-url]', get_template_directory_uri(), $text );
}
add_filter( 'the_content', 'filter_template_url' );
add_filter( 'widget_text', 'filter_template_url' );

//Register tag [site-url]
function filter_site_url( $text ) {
	return str_replace( '[site-url]', home_url(), $text );
}
add_filter( 'the_content', 'filter_site_url' );
add_filter( 'widget_text', 'filter_site_url' );

if( class_exists( 'acf' ) && !is_admin() ) {
	add_filter( 'acf/load_value', 'filter_template_url' );
	add_filter( 'acf/load_value', 'filter_site_url' );
}

//Replace standard wp menu classes
function change_menu_classes( $css_classes ) {
	return str_replace( array( 'current-menu-item', 'current-menu-parent', 'current-menu-ancestor' ), 'active', $css_classes );
}
add_filter( 'nav_menu_css_class', 'change_menu_classes' );

//Allow tags in category description
$filters = array( 'pre_term_description', 'pre_link_description', 'pre_link_notes', 'pre_user_description' );
foreach ( $filters as $filter ) {
	remove_filter( $filter, 'wp_filter_kses' );
}

function clean_phone( $phone ){
    return preg_replace( '/[^0-9]/', '', $phone );
}

//Make wp admin menu html valid
function wp_admin_bar_valid_search_menu( $wp_admin_bar ) {
	if ( is_admin() )
		return;

	$form  = '<form action="' . esc_url( home_url( '/' ) ) . '" method="get" id="adminbarsearch"><div>';
	$form .= '<input class="adminbar-input" name="s" id="adminbar-search" tabindex="10" type="text" value="" maxlength="150" />';
	$form .= '<input type="submit" class="adminbar-button" value="' . __( 'Search', 'acaawp' ) . '"/>';
	$form .= '</div></form>';

	$wp_admin_bar->add_menu( array(
		'parent' => 'top-secondary',
		'id'     => 'search',
		'title'  => $form,
		'meta'   => array(
			'class'    => 'admin-bar-search',
			'tabindex' => -1,
		)
	) );
}

function fix_admin_menu_search() {
	remove_action( 'admin_bar_menu', 'wp_admin_bar_search_menu', 4 );
	add_action( 'admin_bar_menu', 'wp_admin_bar_valid_search_menu', 4 );
}
add_action( 'add_admin_bar_menus', 'fix_admin_menu_search' );

//Disable comments on pages by default
function theme_page_comment_status( $post_ID, $post, $update ) {
	if ( !$update ) {
		remove_action( 'save_post_page', 'theme_page_comment_status', 10 );
		wp_update_post( array(
			'ID' => $post->ID,
			'comment_status' => 'closed',
		) );
		add_action( 'save_post_page', 'theme_page_comment_status', 10, 3 );
	}
}
add_action( 'save_post_page', 'theme_page_comment_status', 10, 3 );

//custom excerpt
function theme_the_excerpt() {
	global $post;
	
	if ( trim( $post->post_excerpt ) ) {
		the_excerpt();
	} elseif ( strpos( $post->post_content, '<!--more-->' ) !== false ) {
		the_content();
	} else {
		the_excerpt();
	}
}

//theme password form
function theme_get_the_password_form() {
	global $post;
	$post = get_post( $post );
	$label = 'pwbox-' . ( empty($post->ID) ? rand() : $post->ID );
	$output = '<form action="' . esc_url( site_url( 'wp-login.php?action=postpass', 'login_post' ) ) . '" class="post-password-form" method="post">
	<p>' . __( 'This content is password protected. To view it please enter your password below:', 'acaawp' ) . '</p>
	<p><label for="' . $label . '">' . __( 'Password:', 'acaawp' ) . '</label> <input name="post_password" id="' . $label . '" type="password" size="20" /> <input type="submit" name="Submit" value="' . esc_attr__( 'Submit', 'acaawp' ) . '" /></p></form>
	';
	return $output;
}
add_filter( 'the_password_form', 'theme_get_the_password_form' );

function base_scripts_styles() {
	$in_footer = true;
	/*
	 * Adds JavaScript to pages with the comment form to support
	 * sites with threaded comments (when in use).
	 */
	wp_deregister_script( 'comment-reply' );
	if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
		wp_enqueue_script( 'comment-reply', get_template_directory_uri() . '/js/comment-reply.js', '', '', $in_footer );
	}

	// Loads JavaScript file with functionality specific.
	wp_enqueue_script( 'base-script', get_template_directory_uri() . '/js/jquery.main.js', array( 'jquery' ), '', $in_footer );

	// Loads our main stylesheet.
	wp_enqueue_style( 'base-style', get_stylesheet_uri(), array() );
	
	// Implementation stylesheet.
	wp_enqueue_style( 'base-theme', get_template_directory_uri() . '/theme.css', array() );
}
add_action( 'wp_enqueue_scripts', 'base_scripts_styles' );

add_action( 'admin_init', 'basetheme_options_capability' );
function basetheme_options_capability(){
	$role = get_role( 'administrator' );
	$role->add_cap( 'theme_options_view' );
}

//theme options tab in appearance
if( function_exists( 'acf_add_options_sub_page' ) && current_user_can( 'theme_options_view' ) ) {
	acf_add_options_sub_page( array(
		'title'  => 'Theme Options',
		'parent' => 'themes.php',
	) );
}

//acf theme functions placeholders
if( !class_exists( 'acf' ) && !is_admin() ) {
	function get_field_reference( $field_name, $post_id ) { return ''; }
	function get_field_objects( $post_id = false, $options = array() ) { return false; }
	function get_fields( $post_id = false ) { return false; }
	function get_field( $field_key, $post_id = false, $format_value = true )  { return false; }
	function get_field_object( $field_key, $post_id = false, $options = array() ) { return false; }
	function the_field( $field_name, $post_id = false ) {}
	function have_rows( $field_name, $post_id = false ) { return false; }
	function the_row() {}
	function reset_rows( $hard_reset = false ) {}
	function has_sub_field( $field_name, $post_id = false ) { return false; }
	function get_sub_field( $field_name ) { return false; }
	function the_sub_field( $field_name ) {}
	function get_sub_field_object( $child_name ) { return false;}
	function acf_get_child_field_from_parent_field( $child_name, $parent ) { return false; }
	function register_field_group( $array ) {}
	function get_row_layout() { return false; }
	function acf_form_head() {}
	function acf_form( $options = array() ) {}
	function update_field( $field_key, $value, $post_id = false ) { return false; }
	function delete_field( $field_name, $post_id ) {}
	function create_field( $field ) {}
	function reset_the_repeater_field() {}
	function the_repeater_field( $field_name, $post_id = false ) { return false; }
	function the_flexible_field( $field_name, $post_id = false ) { return false; }
	function acf_filter_post_id( $post_id ) { return $post_id; }
}


function _get_theme_menu( $theme_location ) {
    if( ! $theme_location ) return false;
    $theme_locations = get_nav_menu_locations();
    if( ! isset( $theme_locations[$theme_location] ) ) return false;
    $menu_obj = get_term( $theme_locations[$theme_location], 'nav_menu' );
    if( ! $menu_obj ) $menu_obj = false;
    return $menu_obj;
}

function _get_theme_menu_name( $theme_location ) {
    if( ! $theme_location ) return false;
    $menu_obj = _get_theme_menu( $theme_location );
    if( ! $menu_obj ) return false;
    if( ! isset( $menu_obj->name ) ) return false;
    return $menu_obj->name;
}
