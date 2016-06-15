<?php

$term = $GLOBALS['wp_query']->queried_object;
$subcategories =  get_terms( 'ait-dir-item-category', array('parent' => intval($term->term_id), 'hide_empty' => false) );
$posts = WpLatte::createPostEntity($GLOBALS['wp_query']->posts);
$items = get_posts( array(
	'numberposts'		=> -1,
	'post_type'			=>	'ait-dir-item',
	'tax_query'			=>	array(array(
		'taxonomy' => 'ait-dir-item-category',
		'field' => 'id',
		'terms' => intval($term->term_id),
		'include_children' => true
	))
));

$term->link = get_term_link( $term );
$term->icon = getRealThumbnailUrl(getCategoryMeta("icon",intval($term->term_id)));
$term->marker = getCategoryMeta("marker",intval($term->term_id));   

// add subcategory links
foreach ($subcategories as $category) {
	$category->link = get_term_link(intval($category->term_id), 'ait-dir-item-category');
	$category->icon = getRealThumbnailUrl(getCategoryMeta("icon",intval($category->term_id)));
	$category->excerpt = getCategoryMeta("excerpt", intval($category->term_id));
}

    //echo '<pre>'.print_r($items,true).'</pre>';   

// add items details
foreach ($items as $item) {
	$item->link = get_permalink($item->ID);
	$image = wp_get_attachment_image_src( get_post_thumbnail_id($item->ID) );
	if($image !== false){
		$item->thumbnailDir = getRealThumbnailUrl($image[0]);
	} else {
        //$item->thumbnailDir = getRealThumbnailUrl($aitThemeOptions->directory->defaultItemImage);
        //$item->thumbnailDir = $term->icon;
		$item->thumbnailDir = getRealThumbnailUrl(getCategoryMeta("icon",intval(lp_get_the_term_id($item->ID))));
	}
	$item->marker = $term->marker;
	$item->optionsDir = get_post_meta($item->ID, '_ait-dir-item', true);
    
    if( $item->optionsDir['gpsLatitude'] == 0 ) $item->optionsDir['gpsLatitude'] = 10.3789285;
    if( $item->optionsDir['gpsLongitude'] == 0 ) $item->optionsDir['gpsLongitude'] = 123.8480186;
    
	$item->packageClass = getItemPackageClass($item->post_author);

	$item->rating = get_post_meta( $item->ID, 'rating', true );
    

}
// add posts details
foreach ($posts as $item) {
	$item->link = get_permalink($item->id);
	$image = wp_get_attachment_image_src( get_post_thumbnail_id($item->id) );
	if($image !== false){
		$item->thumbnailDir = getRealThumbnailUrl($image[0]);
	} else {
        //$item->thumbnailDir = getRealThumbnailUrl($aitThemeOptions->directory->defaultItemImage);
        //$item->thumbnailDir = $term->icon;
        $item->thumbnailDir = getRealThumbnailUrl(getCategoryMeta("icon",intval(lp_get_the_term_id($item->ID))));
	}
	$item->optionsDir = get_post_meta($item->id, '_ait-dir-item', true);
	//$item->excerptDir = aitGetPostExcerpt($item->excerpt,$item->content);
	$item->packageClass = getItemPackageClass($item->author->id);

	$item->rating = get_post_meta( $item->id, 'rating', true );
    
    // Retrieves categories list of current post, separated by commas.
    //$item->categoryList = "category: " . get_the_category_list( '', '', $item->ID );
    $args = array('orderby' => 'name', 'order' => 'ASC', 'fields' => 'names'); 
    $terms = wp_get_post_terms($item->id, 'ait-dir-item-category', $args);
    $item->categoryList = implode(", ", $terms);
}

// breadcrumbs
$ancestorsIDs = array_reverse(get_ancestors(intval($term->term_id), 'ait-dir-item-category'));
$ancestors = array();
foreach ($ancestorsIDs as $anc) {
	$cat = get_term($anc, 'ait-dir-item-category');
	$cat->link = get_term_link($anc, 'ait-dir-item-category');
	$ancestors[] = $cat;
}

$latteParams['ancestors'] = $ancestors;
$latteParams['term'] = $term;
$latteParams['subcategories'] = $subcategories;
$latteParams['items'] = $items;
$latteParams['posts'] = $posts;

$latteParams['isDirTaxonomy'] = true;

$latteParams['sidebarType'] = 'item';

WPLatte::createTemplate(basename(__FILE__, '.php'), $latteParams)->render();
