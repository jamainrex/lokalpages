<?php

/**
 * AIT Theme Admin
 *
 * Copyright (c) 2011, AIT s.r.o (http://ait-themes.com)
 *
 */

function aitDirItemPostType() {
	
	register_post_type( 'ait-dir-item',
		array(
			'labels' => array(
				'name'			=> 'Businesses',
				'singular_name' => 'Business',
				'add_new'		=> 'Add new',
				'add_new_item'	=> 'Add new business',
				'edit_item'		=> 'Edit business',
				'new_item'		=> 'New business',
				'not_found'		=> 'No businesses found',
				'not_found_in_trash' => 'No businesses found in Trash',
				'menu_name'		=> 'Businesses',
			),
			'description' => 'Manipulating with items',
			'public' => true,
			'show_in_nav_menus' => true,
			'supports' => array(
				'title',
				'thumbnail',
				'author',
				'editor',
				'excerpt',
                'comments',
				'revisions'
			),
			'show_ui' => true,
			'show_in_menu' => true,
			'menu_icon' => AIT_FRAMEWORK_URL . '/CustomTypes/dir-item/dir-item.png',
			'menu_position' => $GLOBALS['aitThemeCustomTypes']['dir-item'],
			'has_archive' => true,
			'query_var' => 'dir-item',
			'rewrite' => array('slug' => 'item'),
			'capability_type' => 'ait-dir-item',
			'map_meta_cap' => true
		)
	);
	aitDirItemTaxonomies();

	flush_rewrite_rules(true);
}

function aitDirItemTaxonomies() {

	register_taxonomy( 'ait-dir-item-category', array( 'ait-dir-item' ), array(
		'hierarchical' => true,
		'labels' => array(
			'name'			=> 'Business Categories',
			'singular_name' => _x( 'Category', 'taxonomy singular name', 'ait'),
			'search_items'	=> __( 'Search Category', 'ait'),
			'all_items'		=> __( 'All Categories', 'ait'),
			'parent_item'	=> __( 'Parent Category', 'ait'),
			'parent_item_colon' => __( 'Parent Category:', 'ait'),
			'edit_item'		=> __( 'Edit Category', 'ait'),
			'update_item'	=> __( 'Update Category', 'ait'),
			'add_new_item'	=> __( 'Add New Category', 'ait'),
			'new_item_name' => __( 'New Category Name', 'ait'),
		),
		'show_ui' => true,
		'rewrite' => array( 'slug' => 'cat' ),
		'capabilities' => array(
				'assign_terms' => 'assign_dir_category'
			)
	));

	register_taxonomy( 'ait-dir-item-location', array( 'ait-dir-item' ), array(
		'hierarchical' => true,
		'labels' => array(
			'name'			=> 'Business Locations',
			'singular_name' => _x( 'Location', 'taxonomy singular name', 'ait'),
			'search_items'	=> __( 'Search Location', 'ait'),
			'all_items'		=> __( 'All Locations', 'ait'),
			'parent_item'	=> __( 'Parent Location', 'ait'),
			'parent_item_colon' => __( 'Parent Location:', 'ait'),
			'edit_item'		=> __( 'Edit Location', 'ait'),
			'update_item'	=> __( 'Update Location', 'ait'),
			'add_new_item'	=> __( 'Add New Location', 'ait'),
			'new_item_name' => __( 'New Location Name', 'ait'),
		),
		'show_ui' => true,
		'rewrite' => array( 'slug' => 'loc' ),
		'capabilities' => array(
				'assign_terms' => 'assign_dir_location'
			)
	));

}
add_action( 'init', 'aitDirItemPostType');

function aitDirItemFeaturedImageMetabox() {
	global $wp_meta_boxes;
	// only if exist
	if(isset($wp_meta_boxes['ait-dir-item'])){
		foreach ($wp_meta_boxes['ait-dir-item'] as $contextName => $context) {
			foreach ($context as $boxesName => $boxes) {
				foreach ($boxes as $boxName => $box) {
					if($boxName == 'postimagediv'){
						remove_meta_box( 'postimagediv', 'ait-dir-item', 'side' );
						add_meta_box('postimagediv', 'Business Profile Picture', 'post_thumbnail_meta_box', 'ait-dir-item', 'normal', 'high');
					}
				}
			}
		}
	}
}
add_action('do_meta_boxes', 'aitDirItemFeaturedImageMetabox',1);

$dirItemOptions = new WPAlchemy_MetaBox(array(
	'id' => '_ait-dir-item',
	'title' => __('Options for business', 'ait'),
	'types' => array('ait-dir-item'),
	'context' => 'normal',
	'priority' => 'core',
	'config' => dirname(__FILE__) . '/' . basename(__FILE__, '.php') . '.neon',
	'js' => dirname(__FILE__) . '/' . basename(__FILE__, '.php') . '.js',
	'save_action' => 'saveMetaAction'
));

function saveMetaAction($meta,$postId) {
	if (!empty($meta['featured'])) {
        update_post_meta( $postId, 'dir_featured', 'yes' );
    } else {
        delete_post_meta( $postId, 'dir_featured' );
    }
    
    if (!empty($meta['localbiz'])) {
		update_post_meta( $postId, 'dir_localbiz', 'yes' );
	} else {
		delete_post_meta( $postId, 'dir_localbiz' );
	}
}

function aitDirItemChangeColumns($cols) {
	
	global $current_user;
	if( isset( $current_user->roles ) && is_array( $current_user->roles ) && ( in_array('directory_1', $current_user->roles) || in_array('directory_2', $current_user->roles) || in_array('directory_3', $current_user->roles) || in_array('directory_4', $current_user->roles) || in_array('directory_5', $current_user->roles) ) ) {
		// easy admin columns
		$cols = array(
			'title'			=> __( 'Title', 'ait'),
			'thumbnail'		=> __( 'Image', 'ait'),
			'address' 		=> __( 'Address', 'ait'),
			'category'		=> __( 'Categories', 'ait'),
			'ait-dir-item-location'	=> __( 'Locations', 'ait'),
		);
	} else {
		$cols = array(
			'cb'			=> '<input type="checkbox" />',
			'title'			=> __( 'Title', 'ait'),
			'author'		=> __('Author', 'ait'),
			'thumbnail'		=> __( 'Image', 'ait'),
			'address' 		=> __( 'Address', 'ait'),
			'category'		=> __( 'Categories', 'ait'),
			'ait-dir-item-location'	=> __( 'Locations', 'ait'),
		);
	}
	return $cols;
}
add_filter( "manage_ait-dir-item_posts_columns", "aitDirItemChangeColumns" );

function aitDirItemCustomColumns($column, $post_id) {
	global $dirItemOptions;
	$options = $dirItemOptions->the_meta();

	switch($column){

		case "ait-dir-item-location":
			$terms = get_the_terms($post_id, 'ait-dir-item-location');
			if(!empty($terms)){
				foreach($terms as $term){
					echo "<a href='".get_term_link($term, 'ait-dir-item-location')."' rel='tag'>{$term->name}</a>, ";
				}
			}else{
				//echo "<p>No locations</p>";
			}
			break;

		case "address":
			if(isset($options['address'])){
				echo $options['address'];
			}
			break;

	}
}
add_action( "manage_posts_custom_column", "aitDirItemCustomColumns", 10, 2);


/************************** Category - Add meta ICON **********************************/
add_action( 'ait-dir-item-category_edit_form_fields', 'edit_dir_item_category', 10, 2);
add_action( 'ait-dir-item-category_add_form_fields', 'add_dir_item_category', 10, 2);
function edit_dir_item_category($tag, $taxonomy) {
	$icon = get_option( 'ait_dir_item_category_'.$tag->term_id.'_icon', '' );
	$marker = get_option( 'ait_dir_item_category_'.$tag->term_id.'_marker', '' );
	$excerpt = get_option( 'ait_dir_item_category_'.$tag->term_id.'_excerpt', '' );

	?>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="ait_dir_item_category_excerpt">Excerpt</label></th>
		<td>
			<textarea name="ait_dir_item_category_excerpt" id="ait_dir_item_category_excerpt" cols="30" rows="5"><?php echo $excerpt; ?></textarea>
		</td>
	</tr>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="ait_dir_item_category_icon">Icon</label></th>
		<td>
			<input type="text" name="ait_dir_item_category_icon" id="ait_dir_item_category_icon" value="<?php echo $icon; ?>" style="width: 80%;"/>
			<input type="button" value="Select Image" class="media-select" id="ait_dir_item_category_icon_selectMedia" name="ait_dir_item_category_icon_selectMedia" style="width: 15%;">
			<br />
			<p class="description">Icon for category</p>
		</td>
	</tr>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="ait_dir_item_category_marker">Map Marker</label></th>
		<td>
			<input type="text" name="ait_dir_item_category_marker" id="ait_dir_item_category_marker" value="<?php echo $marker; ?>" style="width: 80%;"/>
			<input type="button" value="Select Image" class="media-select" id="ait_dir_item_category_marker_selectMedia" name="ait_dir_item_category_marker_selectMedia" style="width: 15%;">
			<br />
			<p class="description">Marker image in map for category</p>
		</td>
	</tr>
	<?php
}
function add_dir_item_category($tag) {
	?>
	<div class="form-field">
		<label for="ait_dir_item_category_excerpt">Excerpt</label>
		<textarea name="ait_dir_item_category_excerpt" id="ait_dir_item_category_excerpt" cols="30" rows="5"></textarea>
	</div>
	<div class="form-field">
		<label for="ait_dir_item_category_icon">Icon</label>
		<input type="text" name="ait_dir_item_category_icon" id="ait_dir_item_category_icon" value="" style="width: 80%;"/>
		<input type="button" value="Select Image" class="media-select" id="ait_dir_item_category_icon_selectMedia" name="ait_dir_item_category_icon_selectMedia" style="width: 15%;">
			<br />
			<p class="description">Icon for category</p>
	</div>
	<div class="form-field">
		<label for="ait_dir_item_category_marker">Map Marker</label>
		<input type="text" name="ait_dir_item_category_marker" id="ait_dir_item_category_marker" value="" style="width: 80%;"/>
		<input type="button" value="Select Image" class="media-select" id="ait_dir_item_category_marker_selectMedia" name="ait_dir_item_category_marker_selectMedia" style="width: 15%;">
			<br />
			<p class="description">Marker image in map for category</p>
	</div>
	<?php
}
add_action( 'created_ait-dir-item-category', 'save_dir_item_category', 10, 2);
add_action( 'edited_ait-dir-item-category', 'save_dir_item_category', 10, 2);
function save_dir_item_category($term_id, $tt_id) {
	if (!$term_id) return;

	if (isset($_POST['ait_dir_item_category_excerpt'])){
		$name = 'ait_dir_item_category_' .$term_id. '_excerpt';
		update_option( $name, $_POST['ait_dir_item_category_excerpt'] );
	}

	if (isset($_POST['ait_dir_item_category_icon'])){
		$name = 'ait_dir_item_category_' .$term_id. '_icon';
		update_option( $name, $_POST['ait_dir_item_category_icon'] );
	}

	if (isset($_POST['ait_dir_item_category_marker'])){
		$name = 'ait_dir_item_category_' .$term_id. '_marker';
		update_option( $name, $_POST['ait_dir_item_category_marker'] );
	}
}

add_action( 'deleted_term_taxonomy', 'delete_dir_item_category' );
function delete_dir_item_category($id) {
	delete_option( 'ait_dir_item_category_'.$id.'_excerpt' );
	delete_option( 'ait_dir_item_category_'.$id.'_icon' );
	delete_option( 'ait_dir_item_category_'.$id.'_marker' );
}


// TABLE COLUMNS
add_filter("manage_edit-ait-dir-item-category_columns", 'dir_item_category_columns');
function dir_item_category_columns($category_columns) {
	$new_columns = array(
		'cb'        		=> '<input type="checkbox" />',
		'name'      		=> __('Name', 'ait'),
		'description'     	=> __('Description', 'ait'),
		'item_excerpt'	    => __('Excerpt', 'ait'),
		'icon' 				=> __('Icon', 'ait'),
		'marker'			=> __('Marker', 'ait'),
		'slug'      		=> __('Slug', 'ait'),
		'posts'     		=> __('Items', 'ait'),
		);
	return $new_columns;
}

add_filter("manage_ait-dir-item-category_custom_column", 'manage_dir_item_category_columns', 10, 3);
function manage_dir_item_category_columns($out, $column_name, $cat_id) {

	$icon = get_option( 'ait_dir_item_category_'.$cat_id.'_icon', '' );
	$marker = get_option( 'ait_dir_item_category_'.$cat_id.'_marker', '' );
	$excerpt = get_option( 'ait_dir_item_category_'.$cat_id.'_excerpt', '' );

	switch ($column_name) {
		case 'item_excerpt':
			if($excerpt && !empty($excerpt)){
				$out .= $excerpt;
			}
			break;
		case 'icon':
			if(!empty($icon)){
				$out .= '<img src="'.$icon.'" alt="" width="50" height="50">';
			}
			break;
		case 'marker':
			if(!empty($marker)){
				$out .= '<img src="'.$marker.'" alt="" width="50" height="50">';
			}
			break;
		default:
			break;
	}
	return $out;
}

/************************** Location - Add meta ICON **********************************/
add_action( 'ait-dir-item-location_edit_form_fields', 'edit_dir_item_location', 10, 2);
add_action( 'ait-dir-item-location_add_form_fields', 'add_dir_item_location', 10, 2);
function edit_dir_item_location($tag, $taxonomy) {
	$icon = get_option( 'ait_dir_item_location_'.$tag->term_id.'_icon', '' );
	$excerpt = get_option( 'ait_dir_item_location_'.$tag->term_id.'_excerpt', '' );

	?>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="ait_dir_item_location_excerpt">Excerpt</label></th>
		<td>
			<textarea name="ait_dir_item_location_excerpt" id="ait_dir_item_location_excerpt" cols="30" rows="5"><?php echo $excerpt; ?></textarea>
		</td>
	</tr>
	<tr class="form-field">
		<th scope="row" valign="top"><label for="ait_dir_item_location_icon">Icon</label></th>
		<td>
			<input type="text" name="ait_dir_item_location_icon" id="ait_dir_item_location_icon" value="<?php echo $icon; ?>" style="width: 80%;"/>
			<input type="button" value="Select Image" class="media-select" id="ait_dir_item_location_icon_selectMedia" name="ait_dir_item_location_icon_selectMedia" style="width: 15%;">
			<br />
			<p class="description">Icon for location</p>
		</td>
	</tr>
	<?php
}
function add_dir_item_location($tag) {
	?>
	<div class="form-field">
		<label for="ait_dir_item_location_excerpt">Excerpt</label>
		<textarea name="ait_dir_item_location_excerpt" id="ait_dir_item_location_excerpt" cols="30" rows="5"></textarea>
	</div>
	<div class="form-field">
		<label for="ait_dir_item_location_icon">Icon</label>
		<input type="text" name="ait_dir_item_location_icon" id="ait_dir_item_location_icon" value="" style="width: 80%;"/>
		<input type="button" value="Select Image" class="media-select" id="ait_dir_item_location_icon_selectMedia" name="ait_dir_item_location_icon_selectMedia" style="width: 15%;">
			<br />
			<p class="description">Icon for location</p>
	</div>
	<?php
}
add_action( 'created_ait-dir-item-location', 'save_dir_item_location', 10, 2);
add_action( 'edited_ait-dir-item-location', 'save_dir_item_location', 10, 2);
function save_dir_item_location($term_id, $tt_id) {
	if (!$term_id) return;

	if (isset($_POST['ait_dir_item_location_excerpt'])){
		$name = 'ait_dir_item_location_' .$term_id. '_excerpt';
		update_option( $name, $_POST['ait_dir_item_location_excerpt'] );
	}

	if (isset($_POST['ait_dir_item_location_icon'])){
		$name = 'ait_dir_item_location_' .$term_id. '_icon';
		update_option( $name, $_POST['ait_dir_item_location_icon'] );
	}
}

add_action( 'deleted_term_taxonomy', 'delete_dir_item_location' );
function delete_dir_item_location($id) {
	delete_option( 'ait_dir_item_location_'.$id.'_excerpt' );
	delete_option( 'ait_dir_item_location_'.$id.'_icon' );
}

add_filter("manage_edit-ait-dir-item-location_columns", 'dir_item_location_columns');
function dir_item_location_columns($location_columns) {
	$new_columns = array(
		'cb'        		=> '<input type="checkbox" />',
		'name'      		=> __('Name', 'ait'),
		'description'     	=> __('Description', 'ait'),
		'item_excerpt'	    => __('Excerpt', 'ait'),
		'icon' 				=> __('Icon', 'ait'),
		'slug'      		=> __('Slug', 'ait'),
		'posts'     		=> __('Items', 'ait'),
		);
	return $new_columns;
}

add_filter("manage_ait-dir-item-location_custom_column", 'manage_dir_item_location_columns', 10, 3);
function manage_dir_item_location_columns($out, $column_name, $cat_id) {

	$icon = get_option( 'ait_dir_item_location_'.$cat_id.'_icon', '' );
	$excerpt = get_option( 'ait_dir_item_location_'.$cat_id.'_excerpt', '' );

	switch ($column_name) {
		case 'item_excerpt':
			if($excerpt && !empty($excerpt)){
				$out .= $excerpt;
			}
			break;
		case 'icon':
			if(!empty($icon)){
				$out .= '<img src="'.$icon.'" alt="" width="50" height="50">';
			}
			break;
		default:
			break;
	}
	return $out;
}

function aitDirItemSortableColumns() {
  return array(
	'title'=> 'title',
	'category'=> 'category'
  );
}
add_filter( "manage_edit-ait-dir-item_sortable_columns", "aitDirItemSortableColumns" );

?>