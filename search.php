<?php

/**
 * AIT WordPress Theme
 *
 * Copyright (c) 2012, Affinity Information Technology, s.r.o. (http://ait-themes.com)
 */
$latteParams['type'] = (isset($_GET['dir-search'])) ? true : false;
if($latteParams['type']){
	$latteParams['isDirSearch'] = true;
	// show all items on map
	if(isset($aitThemeOptions->search->searchShowMap)){
		$radius = array();
		if(isset($_GET['geo'])){
			$radius[] = $_GET['geo-radius'];
			$radius[] = $_GET['geo-lat'];
			$radius[] = $_GET['geo-lng'];
		}
		$latteParams['items'] = getItems(intval($_GET['categories']),intval($_GET['locations']),$GLOBALS['wp_query']->query_vars['s'],$radius);
	}

	$posts = $wp_query->posts;
	foreach ($posts as $item) {
		$item->link = get_permalink($item->ID);
		$image = wp_get_attachment_image_src( get_post_thumbnail_id($item->ID) );
		if($image !== false){
			$item->thumbnailDir = getRealThumbnailUrl($image[0]);
		} else {
            //$item->thumbnailDir = getRealThumbnailUrl($GLOBALS['aitThemeOptions']->directory->defaultItemImage);
            $item->thumbnailDir = getRealThumbnailUrl(getCategoryMeta("icon",intval(lp_get_the_term_id($item->ID))));
		}
		$item->optionsDir = get_post_meta($item->ID, '_ait-dir-item', true);
		$item->excerptDir = aitGetPostExcerpt($item->post_excerpt,$item->post_content);
		$item->packageClass = getItemPackageClass($item->post_author);

		$item->rating = get_post_meta( $item->ID, 'rating', true );
        
        // Retrieves categories list of current post, separated by commas.
        $args = array('orderby' => 'name', 'order' => 'ASC', 'fields' => 'names'); 
        $terms = wp_get_post_terms($item->ID, 'ait-dir-item-category', $args);
        $item->categoryList = implode(", ", $terms);
	}

} else {
	$posts = WpLatte::createPostEntity($wp_query->posts);
}
$latteParams['posts'] = $posts;

WPLatte::createTemplate(basename(__FILE__, '.php'), $latteParams)->render();