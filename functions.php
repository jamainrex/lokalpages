<?php
/*
 * AIT WordPress Theme
 *
 * Copyright (c) 2013, Affinity Information Technology, s.r.o. (http://ait-themes.com)
 */

// ==================================================
// Enables theme custom post types, widgets, etc...
// --------------------------------------------------
$aitThemeCustomTypes = array('dir-item' => 32,'grid-portfolio' => 33);
$aitThemeWidgets = array('post', 'flickr', 'submenu', 'twitter', 'directory-login','lppost');
$aitEditorShortcodes = array('custom', 'columns', 'images', 'posts', 'buttons', 'boxesFrames', 'lists', 'notifications', 'modal', 'social', 'video', 'gMaps', 'gChart', 'portfolio', 'language', 'tabs', 'gridgallery', 'econtent');
$aitThemeShortcodes = array('boxesFrames' => 2, 'buttons' => 1, 'columns'=> 1, 'custom'=> 1, 'images'=> 1, 'lists'=> 1, 'modal'=> 1, 'notifications'=> 1, 'portfolio'=> 1, 'posts'=> 1, 'sitemap'=> 1, 'social'=> 1, 'video'=> 1, 'language'=> 1, 'gMaps'=> 1, 'gChart'=> 1, 'tabs'=> 1, 'gridgallery'=> 1, 'econtent' => 1, 'directoryRegister' => 1);

// ==================================================
// Loads AIT WordPress Framework
// --------------------------------------------------
require dirname(__FILE__) . '/AIT/ait-bootstrap.php';

// ==================================================
// Loads Theme's text translations
// --------------------------------------------------
load_theme_textdomain('ait', get_template_directory() . '/languages');

// ==================================================
// Metaboxes settings for Posts and Pages
// --------------------------------------------------
$pageOptions = array(
	'header' => new WPAlchemy_MetaBox(array(
		'id' => '_ait_header_options',
		'title' => __('Header', 'ait-admin'),
		'types' => array('page','post'),
		'context' => 'normal',
		'priority' => 'core',
		'config' => dirname(__FILE__) . '/conf/page-header.neon'
	))
);

// ==================================================
// Theme's scripts and styles
// --------------------------------------------------
function aitAdminEnqueueScriptsAndStyles()
{
	aitAddScripts(array(
		'ait-googlemaps-api' => array('file' => 'http://maps.google.com/maps/api/js?sensor=false&amp;language=en', 'deps' => array('jquery')),
		'ait-jquery-gmap3'   => array('file' => THEME_JS_URL . '/libs/gmap3.min.js', 'deps' => array('jquery', 'ait-googlemaps-api')),
	));
}
add_action('admin_enqueue_scripts', 'aitAdminEnqueueScriptsAndStyles');
function aitEnqueueScriptsAndStyles()
{
	// just shortcuts
	$s = THEME_CSS_URL;
	$j = THEME_JS_URL;

	aitAddStyles(array(
		'ait-jquery-prettyPhoto'  => array('file' => "$s/prettyPhoto.css"),
		'ait-jquery-fancybox'     => array('file' => "$s/fancybox/jquery.fancybox-1.3.4.css"),
		'ait-jquery-hover-zoom'   => array('file' => "$s/hoverZoom.css"),
		'ait-jquery-fancycheckbox'=> array('file' => "$s/jquery.fancycheckbox.min.css"),
        'jquery-ui-css'           => array('file' => "$s/jquery-ui-1.10.1.custom.min.css"),
        'jquery-bootstrap'           => array('file' => "$s/bootstrap.min.css"),
        'lokalpages-custome-style'           => array('file' => "$s/custom/style.css"),
        'lokalpages-colombia-style'           => array('file' => "$s/custom/colombia.css"),
		'lokalpages-colombia-responsive-style'           => array('file' => "$s/custom/colombia-responsive.css"),
	));

	aitAddScripts(array(
		'jquery-ui-tabs'              => true,
		'jquery-ui-accordion'         => true,
		'jquery-ui-autocomplete'      => true,
		'jquery-ui-slider'            => true,
		'ait-jquery-fancycheckbox'    => array('file' => "$j/libs/jquery.fancycheckbox.min.js", 'deps' => array('jquery')),
		'ait-jquery-html5placeholder' => array('file' => "$j/libs/jquery.html5-placeholder-shim.js", 'deps' => array('jquery')),
		'ait-googlemaps-api'          => array('file' => 'http://maps.google.com/maps/api/js?sensor=false&amp;language=en', 'deps' => array('jquery')),
		'ait-jquery-gmap3-label'      => array('file' => "$j/libs/gmap3.infobox.js", 'deps' => array('jquery')),
		'ait-jquery-gmap3'            => array('file' => "$j/libs/gmap3.min.js", 'deps' => array('jquery')),
		'ait-jquery-infieldlabel'     => array('file' => "$j/libs/jquery.infieldlabel.js", 'deps' => array('jquery')),
		'ait-jquery-prettyPhoto'      => array('file' => "$j/libs/jquery.prettyPhoto.js", 'deps' => array('jquery')),
		'ait-jquery-fancybox'         => array('file' => "$j/libs/jquery.fancybox-1.3.4.js", 'deps' => array('jquery')),
		'ait-jquery-easing'           => array('file' => "$j/libs/jquery.easing-1.3.min.js", 'deps' => array('jquery')),
        'ait-jquery-mousewheel'       => array('file' => "$j/libs/jquery.mousewheel.min.js", 'deps' => array('jquery')), 
		'ait-jquery-nicescroll'       => array('file' => "$j/libs/jquery.nicescroll.min.js", 'deps' => array('jquery')),
		'ait-jquery-quicksand'        => array('file' => "$j/libs/jquery.quicksand.js", 'deps' => array('jquery')),
		'ait-jquery-hover-zoom'       => array('file' => "$j/libs/hover.zoom.js", 'deps' => array('jquery')),
		'ait-jquery-finished-typing'  => array('file' => "$j/libs/jquery.finishedTyping.js", 'deps' => array('jquery')),
		'ait-spin-ajax-loader'        => array('file' => "$j/libs/spin.min.js"),
		'ait-modernizr-touch'         => array('file' => "$j/libs/modernizr.touch.js"),
		'ait-gridgallery'             => array('file' => "$j/gridgallery.js", 'deps' => array('jquery')),
		'ait-rating'                  => array('file' => "$j/rating.js", 'deps' => array('jquery')),
		'ait-script'                  => array('file' => "$j/script.js", 'deps' => array('jquery')),
	));
	wp_localize_script( 'ait-script', 'MyAjax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'ajaxnonce' => wp_create_nonce('ajax-nonce') ) );
    
    wp_register_style(  'flexslider-v2-css', "$s/flexslider_v2.css" , '', '2.2.2' );    
    wp_register_script( 'jquery_flexslider_v2_2_2_js', "$j/libs/jquery.flexslider.js", array( 'jquery' ), '2.2.2' ); 
    wp_register_script( 'jquery_landing_carousel_custom_js', "$j/landing.carousel.js",array( 'jquery' ), '1.0' ); 
    wp_register_script( 'jquery_listing_carousel_custom_js', "$j/listing.carousel.js",array( 'jquery' ), '1.0' ); 
    wp_register_script( 'jquery_sh_core_js', "$j/libs/shCore.js", array( 'jquery' ), '3.0.8' ); 
    wp_register_script( 'jquery_sh_xml_js', "$j/libs/shBrushXml.js", array( 'jquery' ), '3.0.8' ); 
    wp_register_script( 'jquery_sh_scipt_js', "$j/libs/shBrushJScript.js", array( 'jquery' ), '3.0.8' );
    
    
    
    //$color = get_theme_mod( 'my-custom-color' ); //E.g. #FF0000
    $lpLandingBGImg_default = THEME_URL."/design/img/billboard/bg1.jpg";
    $lpLandingBGImage = get_option( 'lp_landing_bg_image_2' );
    if( !$lpLandingBGImage ) $lpLandingBGImage = $lpLandingBGImg_default;
    
    //$inner_nav_bg = is_front_page() ? '' : ', #globalheader';
    $inner_nav_bg = "";
    /*if( !is_front_page() ){
        $inner_nav_bg = "#globalheader{
                        background-image: url('".$lpLandingBGImage."');
                        background-position: 50% 50%;
                        background-size: cover;
                        background-repeat: no-repeat;
                }";
    }*/
    
    $isLoggedStyles = "
        @media only screen and (min-width: 768px){ #globalheader { top: 32px; }}";
    
    $lp_landing_css = "
                #lpc-billboard .lpc-bb-image{
                        background-image: url('".$lpLandingBGImage."');
                }". $inner_nav_bg;
    
    if( is_user_logged_in() && current_user_can( 'manage_options' ) )            
        $lp_landing_css .= $isLoggedStyles;      
             
    wp_add_inline_style( 'lokalpages-colombia-style', $lp_landing_css );
    
    
    
    /**
    * Header Animate
    */
    wp_register_style(  'header-animate_css', "$s/lp_header_animate.css" , '', '1.0' );   
    wp_register_script( 'header-animate_js', "$j/lp_header_animate.js", array( 'jquery' ), '1.0' ); 
    
    if( !wp_is_mobile() && !is_front_page() && !is_home() ){
        wp_enqueue_style( 'header-animate_css' );
        wp_enqueue_script( 'header-animate_js' );
    }
}
add_action('wp_enqueue_scripts', 'aitEnqueueScriptsAndStyles');


// ==================================================
// Theme setup
// --------------------------------------------------
function aitThemeSetup()
{
	add_editor_style();

	add_theme_support('automatic-feed-links');
	add_theme_support('post-thumbnails');

	register_nav_menu('primary-menu', __('Primary Menu', 'ait-admin'));
	register_nav_menu('footer-menu', __('Footer Menu', 'ait-admin'));
}
add_action('after_setup_theme', 'aitThemeSetup');

// ==================================================
// Plugins
// --------------------------------------------------
aitAddPlugins(array(
	array(
		'name'     => 'Contact Form 7',
		'slug'     => 'contact-form-7',
		'required' => false, // only recommended
	)/*,
	array(
		'name'     => 'Revolution Slider',
		'slug'     => 'revslider',
		'required' => false,
		'source'   => dirname(__FILE__) . '/plugins/revslider.zip', // pre-packed
	),*/
));

function aitWidgetsAreasInit()
{
	aitRegisterWidgetAreas(array(
		'sidebar-1'      => array('name' => __('Main Sidebar', 'ait-admin')),
		'sidebar-home'   => array('name' => __('Homepage Sidebar', 'ait-admin')),
        'sidebar-item'   => array('name' => __('Items Sidebar', 'ait-admin')),
		'footer-item'   => array('name' => __('Items Footer', 'ait-admin'),
                                 'before_title' => '<h2 class="lp-featured-items-title">',
                                 'after_title' => '</h2>'),
		'footer-widgets' => array(
			'name' => __('Footer Widget Area', 'ait-admin'),
			'before_widget' => '<div id="%1$s" class="box widget-container %2$s"><div class="box-wrapper">',
			'after_widget' => "</div></div>",
			'before_title' => '<div class="title-border-bottom"><div class="title-border-top"><div class="title-decoration"></div><h2 class="widget-title">',
			'after_title' => '</h2></div></div>',
		),
	), array(
		'before_widget' => '<aside id="%1$s" class="widget %2$s">',
		'after_widget'  => "</aside>",
		'before_title'  => '<h3 class="widget-title"><span>',
		'after_title'   => '</span></h3>',
	));
}
add_action('widgets_init', 'aitWidgetsAreasInit');



// ==================================================
// Loads Direcotory Theme's specific functions
// --------------------------------------------------
require_once dirname(__FILE__) . '/functions-dir.php';

// ==================================================
// Some helper functions and filters for theme
// --------------------------------------------------
function default_menu(){
	wp_nav_menu(array('menu' => 'Main Menu', 'fallback_cb' => 'default_page_menu', 'container' => 'nav', 'container_class' => 'mainmenu', 'menu_class' => 'menu clear'));
}

function default_page_menu(){
	echo '<nav class="mainmenu">';
	wp_page_menu(array('menu_class' => 'menu clear'));
	echo '</nav>';
}

function default_footer_menu(){
	wp_nav_menu(array('menu' => 'Main Menu', 'container' => 'nav', 'container_class' => 'footer-menu', 'menu_class' => 'menu clear', 'depth' => 1));
}

remove_action('wp_head', 'wp_generator'); // do not show generator meta element

add_filter('widget_title', 'do_shortcode');
add_filter('widget_text', 'do_shortcode'); // do shortcode in text widget

if(!isset($content_width)) $content_width = 1000;

// ==================================================
// Custom styling of admin interface of Revolution slider
// --------------------------------------------------
if(isset($revSliderVersion)){
	// Some custom styles for slides in Revolution Slider admin
	function aitRevSliderAdminStyles(){ wp_enqueue_style('ait-revolution-slider-admin-css', THEME_URL . '/design/admin-plugins/revslider.css'); }
	function aitRevSliderAdminScripts(){ wp_enqueue_script('ait-revolution-slider-admin-js', THEME_URL . '/design/admin-plugins/revslider.js'); }

	add_action('admin_print_styles', 'aitRevSliderAdminStyles');
	add_action('admin_print_scripts', 'aitRevSliderAdminScripts');
}

// ==================================================
// Update processes for 2.17 version
// --------------------------------------------------
if (!get_option( 'directory_2.17_update_process' )) {

	// calculate and save ratings for all items to db
	$args = array(
		'post_type' => 'ait-dir-item',
		'post_status' => 'any',
		'nopaging' => true
	);
	$items = new WP_Query($args);
	foreach ($items->posts as $item) {
		aitSaveRatingMeanToDB($item->ID,null);
	}

	update_option( 'directory_2.17_update_process', 'yes' );
}

// ==================================================
// Loads Custom Shortcodes
// --------------------------------------------------
require dirname(__FILE__) . '/includes/shortcodes.php';

// ==================================================
// Loads Custom Lokalpages Functions
// --------------------------------------------------
require dirname(__FILE__) . '/includes/cpt/super-cpt.php';
require dirname(__FILE__) . '/includes/cpt/listing_cpt.php';
require dirname(__FILE__) . '/includes/lp_functions.php';
require dirname(__FILE__) . '/includes/lp_fbmsg.php';
require dirname(__FILE__) . '/includes/formatting.php';

require dirname(__FILE__) . '/includes/lp_functions-claim.php';

// Admin
require dirname(__FILE__) . '/includes/admin-lp/lib/submission-class.php';
require dirname(__FILE__) . '/includes/admin-lp/init.php';


add_action('init', 'my_lp_custom_init');
function my_lp_custom_init() {
    add_post_type_support( 'ait-dir-item', 'publicize' );
}