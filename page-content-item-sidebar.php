<?php

/**
 * AIT WordPress Theme
 *
 * Copyright (c) 2012, Affinity Information Technology, s.r.o. (http://ait-themes.com)
 */

/**
 * Template Name: Content Sidebar Template (Item Sidebar)
 * Description: Page with item sidebar
 */

$latteParams['post'] = WpLatte::createPostEntity(
    $GLOBALS['wp_query']->post,
    array(
        'meta' => $GLOBALS['pageOptions'],
    )
);


$latteParams['sidebarType'] = 'item';  

/**
 * Fire!
 */
WPLatte::createTemplate(basename(__FILE__, '.php'), $latteParams)->render();