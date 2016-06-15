<?php

/**
 * AIT WordPress Theme
 *
 * Copyright (c) 2012, Affinity Information Technology, s.r.o. (http://ait-themes.com)
 */

/**
 * Template Name: Fullwidth Template (98% width) 
 * Description: Page without sidebar
 */

$latteParams['post'] = WpLatte::createPostEntity(
    $GLOBALS['wp_query']->post,
    array(
        'meta' => $GLOBALS['pageOptions'],
    )
);

$latteParams['fullwidth'] = true;
function lpFullWidthEnqueueScriptsAndStyles()
{
global $latteParams;
$lpBGImage = get_post_meta($latteParams['post']->id, 'lp_fullwidth_page_background', true);  
//echo '<pre>'.print_r($lpBGImage,true).'</pre>';
//$lpBGImage = "http://lokalpages.com/wp-content/uploads/2015/06/water-drops-glass-window-open-city-wide-hd-wallpaper-e1434370803468.jpg";    
$lp_bg_css = "
                #main.defaultContentWidth{
                        width: 100% !important;
                        background-position: 50% 50%;
                        background-size: cover;
                        background-repeat: no-repeat;
                        height: 100%;
                        z-index: 1; 
                        background-image: url('".$lpBGImage."');
                        box-shadow: 0 1px 3px #1d1d1d;
                }
                #main.defaultContentWidth #wrapper-row {
                    display: block;
                    margin: 0 auto !important;
                    position: inherit;
                    max-width: 1000px;
                    z-index: 9999;
                }";
                
    wp_add_inline_style( 'lokalpages-custome-style', $lp_bg_css );
}

add_action('wp_enqueue_scripts', 'lpFullWidthEnqueueScriptsAndStyles'); 
/**
 * Fire!
 */
WPLatte::createTemplate(basename(__FILE__, '.php'), $latteParams)->render();
