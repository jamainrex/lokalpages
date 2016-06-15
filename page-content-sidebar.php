<?php

/**
 * AIT WordPress Theme
 *
 * Copyright (c) 2012, Affinity Information Technology, s.r.o. (http://ait-themes.com)
 */

/**
 * Template Name: Content Sidebar Template
 * Description: Page with sidebar
 */

$latteParams['post'] = WpLatte::createPostEntity(
    $GLOBALS['wp_query']->post,
    array(
        'meta' => $GLOBALS['pageOptions'],
    )
);


$latteParams['sidebarType'] = 'home';  

/**
 * Fire!
 */
WPLatte::createTemplate(basename(__FILE__, '.php'), $latteParams)->render();
