<?php
  function lp_get_sidebar(){
      $sidebar = get_post_meta(get_the_ID(),'cs_replacement_sidebar-item',true);
      return $sidebar;
}

function lp_get_the_term_id($post_id){
    $terms = get_the_terms($post_id,'ait-dir-item-category');
    if( !$terms ) return false;  
    $term = current($terms);
    $id = $term->term_id;
    if( $term->parent > 0 ){
        $parent = get_term_by('id',$term->parent,'ait-dir-item-category');
        $id = $parent->term_id;
    }
    return $id;
}

// disable wp registration page
add_filter( 'login_head', 'lp_registration_redirect');
function lp_registration_redirect(){
    if( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'register' ){
        wp_redirect( home_url() );
        exit;
    }
}

function lp_mostRatedItems($ob='high_rated',$limit=5){
    global $wpdb;
    $_ob = array( 'most_rated' => 'pm4', 'high_rated' => 'pm1' );
    $limit = isset($limit) ? (int)$limit : 5;
    $orderby = $_ob[$ob];
    
    //echo '<pre>'.print_r($wpdb,true).'</pre>';
    $query = "SELECT p.ID, p.post_name, pm1.meta_value as rating_rounded, pm2.meta_value as rating_full, pm3.meta_value as rating_max, pm4.meta_value as rating_count 
                            FROM $wpdb->posts p 
                            INNER JOIN $wpdb->postmeta pm1 
                            ON p.ID=pm1.post_id 
                            AND pm1.meta_key = 'rating_rounded'
                            INNER JOIN $wpdb->postmeta pm2 
                            ON p.ID=pm2.post_id 
                            AND pm2.meta_key = 'rating_full'
                            INNER JOIN $wpdb->postmeta pm3 
                            ON p.ID=pm3.post_id 
                            AND pm3.meta_key = 'rating_max'
                            INNER JOIN $wpdb->postmeta pm4 
                            ON p.ID=pm4.post_id 
                            AND pm4.meta_key = 'rating_count'  
                            WHERE p.post_type = 'ait-dir-item' AND p.post_status = 'publish' order by $orderby.meta_value DESC limit $limit";

    return $wpdb->get_results( $query );
    
    //echo '<pre>'.print_r($result,true).'</pre>';
}
function lp_recentRatedItems(){
    $limit=5;
    $args = array(
        'post_type' => 'ait-rating',
        'post_status' => 'publish',
        'posts_per_page' => $limit,
        'nopaging' => true,
        'orderby' => 'date',
        'order' => 'DESC'
    );
    
    $ratings = new WP_Query($args);
    return $ratings->posts;
    //echo '<pre>'.print_r($ratings->posts,true).'</pre>';
}

function lp_getRecentRatingElement($limit) {
    global $aitThemeOptions;
    $limit=5;

        /*$args = array(
            'post_type' => 'ait-rating',
            'post_status' => 'publish',
            'nopaging' => true,
            'meta_query' => array(
                array(
                    'key' => 'post_id',
                    'value' => $postId
                )
            )
        );*/
        
        $args = array(
        'post_type' => 'ait-rating',
        'post_status' => 'publish',
        'posts_per_page' => $limit,
        'nopaging' => true,
        'orderby' => 'date',
        'order' => 'DESC'
    );
        
        $ratings = new WP_Query($args);

        // count default is 5
        $starsCount = (isset($aitThemeOptions->rating->starsCount) && intval($aitThemeOptions->rating->starsCount) > 1 ) ? intval($aitThemeOptions->rating->starsCount) : 5;

        $metaIp = get_post_meta($postId, "_ait_rating_ip");
        $votedIp = (isset($metaIp[0]) && is_array($metaIp[0])) ? $metaIp[0] : array();

        $html = "";
        $html .= '<div id="ait-rating-system" class="rating-system">';
        
        foreach ($ratings->posts as $rating) { 
                    $item_id = get_post_meta($rating->ID,'post_id',true);
                    // item Title
                    $item_title = get_the_title($item_id);
                    // link
                    $item_perm = get_permalink($item_id);    
                    
                        $sum = 0;
                        $count = 0;
                        for($i = 1; $i <= 5; $i++){
                            $nameEnable = 'rating'.$i.'Enable';
                            if(isset($aitThemeOptions->rating->$nameEnable)){
                                $nameTitle = 'rating'.$i.'Title';
                                $stars = get_post_meta( $rating->ID, 'rating_'.$i, true );
                                $stars = (!empty($stars)) ? intval($stars) : 0;
                                $sum += $stars;
                                $count++;
                            }
                        }
                                        
                    $html .=  '<div class="user-rating" data-post-id="'.$item_id.'">
                                    <div class="user-details">';
                    $html .=            '<div class="name">'.$rating->post_title.'<span style="font-weight: normal;"> reviewed</span> <a href="'.$item_perm.'" class="biz-name">'.$item_title.'</a>
                                            <div class="value">';

                                                if( $count > 0 )
                                                    $mean = round($sum / $count);
                                                else
                                                    $mean = 0;
                                                for($j = 1; $j <= $starsCount; $j++) { 
                                                        $html .= '<div class="star';
                                                            if($j <= $mean) $html .= ' active"';
                                                        $html .= '" data-star-id="'.$j.'"></div>'; 
                                                    }
                                
                    $html .=                '</div>
                                        </div>';
                    $html .=            '<div class="description">'.genesis_truncate_phrase( $rating->post_content, 40 ).'... <a href="'.$item_perm.'" >View More</a></div>
                                    </div>
                              </div>';
                    
        } 
    
    $html .= '</div><hr>';    
    
    return $html;
}

function lpGetItemRatingOpt($post_id=null){
    if( is_null( $post_id ) ) return;
    
    global $aitThemeOptions;
    $disabledRatings = get_post_meta($post_id, 'lp_rating_disable');
    
    //echo '<pre>'.print_r($disabledRatings,true).'</pre>';     
    
    if( isset( $disabledRatings[0] ) && !empty( $disabledRatings[0] ) ){
        foreach( $disabledRatings as $disableRating ){
            unset( $aitThemeOptions->rating->$disableRating );
        }
    }  
    
    //echo '<pre>'.print_r($aitThemeOptions->rating,true).'</pre>';     
}
add_filter( 'lp_disabled_ratings', 'lpGetItemRatingOpt', 99, 1 );

function lpSingleItem( $args ) {
    global $aitThemeOptions;    
    if ( isset( $args->query_vars['post_type'] ) && $args->query_vars['post_type'] == 'ait-rating' ) {
        if( isset( $args->query_vars['meta_query'][0]['key'] ) && $args->query_vars['meta_query'][0]['key'] == 'post_id' ){
            $post_id = $args->query_vars['meta_query'][0]['value'];
            apply_filters('lp_disabled_ratings', $post_id);
        }
        //echo '<pre>'.print_r($args,true).'</pre>';            
        //echo '<pre>'.print_r($aitThemeOptions->rating,true).'</pre>';            
    }
}
add_action( 'pre_get_posts', 'lpSingleItem' );
function lp_crp_posts_join($join){
    global $post, $wpdb;
    $dbtr = $wpdb->term_relationships;
    $join .= "inner join $dbtr trc on $wpdb->posts.ID = trc.object_id ";
    $join .= "inner join $dbtr trl on $wpdb->posts.ID = trl.object_id";
    return $join; 
    
}
add_filter( 'crp_posts_join', 'lp_crp_posts_join', 99, 1 );
// Recreate where clause
function lp_crp_posts_where(){
    global $post, $wpdb, $crp_settings;
    //$dbtr = $wpdb->term_relationships;
    
    $where = ''; // empty the where clause
    
    $defaults = array(
        'postid' => FALSE,
        'strict_limit' => FALSE,
    );
    $defaults = array_merge( $defaults, $crp_settings ); 
    
    $args = array();
    
    // Parse incomming $args into an array and merge it with $defaults
    $args = wp_parse_args( $args, $defaults );

    // Declare each item in $args as its own variable i.e. $type, $before.
    extract( $args, EXTR_SKIP );
    
    parse_str( $post_types, $post_types );    // Save post types in $post_types variable
    
    //echo '<pre>'.print_r($post_types,true).'</pre>';
    
    // Make sure the post is not from the future
    $time_difference = get_option( 'gmt_offset' );
    $now = gmdate( "Y-m-d H:i:s", ( time() + ( $time_difference * 3600 ) ) );

    // Limit the related posts by time
    $daily_range = $daily_range - 1;
    $from_date = strtotime( '-' . $daily_range . ' DAY' , strtotime( $now ) );
    $from_date = date ( 'Y-m-d H:i:s' , $from_date );
    
    
        $where .= $wpdb->prepare( " AND $wpdb->posts.post_date < '%s' ", $now );        // Show posts before today
        $where .= $wpdb->prepare( " AND $wpdb->posts.post_date >= '%s' ", $from_date );    // Show posts after the date specified
        $where .= " AND $wpdb->posts.post_status = 'publish' ";                    // Only show published posts
        $where .= $wpdb->prepare( " AND $wpdb->posts.ID != %d ", $post->ID );    // Show posts after the date specified
        if ( '' != $exclude_post_ids ) {
            $where .= " AND $wpdb->posts.ID NOT IN (" . $exclude_post_ids . ") ";
        }
        $where .= " AND $wpdb->posts.post_type IN ('" . join( "', '", $post_types ) . "') ";    // Array of post types
    
    $terms_cat = get_the_terms( $post->ID, 'ait-dir-item-category' );
    //echo '<pre>'.print_r($terms_cat,true).'</pre>';
    $term_cat_ids = array(); // default to empty
    if( $terms_cat )
        foreach( $terms_cat as $termc ) $term_cat_ids[] = $termc->term_id; // category
    
     $terms_loc = get_the_terms( $post->ID, 'ait-dir-item-location' );   
     //echo '<pre>'.print_r($terms_loc,true).'</pre>';
     $term_loc_ids = array(); // default to empty    
    if( $terms_loc )
        foreach( $terms_loc as $terml ) $term_loc_ids[] = $terml->term_id; // location
    
    $where .= " AND ( trc.term_taxonomy_id IN ('" . join( "', '", $term_cat_ids ) . "') AND trl.term_taxonomy_id IN ('" . join( "', '", $term_loc_ids ) . "')  )";    // Array of term_ids
    
    return $where; 
    
}
add_filter( 'crp_posts_where', 'lp_crp_posts_where', 99 );

function lp_get_crp_posts_id( $results, $post_id ){
    global $crp_settings;
    
    // Add filter for where clause     
    add_filter( 'crp_posts_where', 'lp_crp_posts_where', 99 );
    
    $defaults = array(
        'is_widget' => FALSE,
        'echo' => TRUE,
    );
    $defaults = array_merge( $defaults, $crp_settings );

    // Parse incomming $args into an array and merge it with $defaults
    $args = wp_parse_args( $args, $defaults );

    // Declare each item in $args as its own variable i.e. $type, $before.
    extract( $args, EXTR_SKIP );
    
    // Retrieve the list of posts
    $lp_results = get_crp_posts_id( array_merge( $args, array(
        'postid' => $post_id,
        'strict_limit' => TRUE,
        'lp_flag' => TRUE,
    ) ) );
    
    $results = array_merge( $lp_results, $results );
    
    // filter for duplicates
    $_res = array();  
    $_filter = array();
    foreach( $results as $result ){
        if( in_array( $result->ID, $_filter ) ) continue;
        $_res[] = $result;
        // add filter ID
        $_filter[] = $result->ID;    
    }
    //echo '<pre>'.print_r($lp_results,true).'</pre>'; 
    //echo '<pre>'.print_r($results,true).'</pre>'; 
    return $_res;
}
//add_filter( 'get_crp_posts_id', 'lp_get_crp_posts_id', 10, 2 );  

function lp_get_submit_content_to_db($items, $result){
    global $wpdb;

    //if( $items['mailSent'] && $_POST['_wpcf7'] == 4 ){
        $biz_info = json_encode( $_POST );
        $biz_files = array();
        //echo '<pre>'.print_r($result,true).'</pre>';
        $lp_uploads_dir = wpcf7_upload_dir( 'dir' ) . '/lp_submission'; 
        foreach( $_FILES as $i => $file){
            $filename = $file['name'];
            $filename = wpcf7_antiscript_file_name( $filename );
            $filename = wp_unique_filename( $lp_uploads_dir, $filename );
            $copied_file = trailingslashit( $lp_uploads_dir ) . $filename;
            
            $biz_files[$i] = array( 'name'=>$file['name'], 'type'=>$file['type'], 'size'=>$file['size'], 'file_dir' => $copied_file );
        }
    
        $wpdb->insert( 'lpd_biz_submitted', array( 'info' => $biz_info, 'files' => json_encode( $biz_files ), 'submitted' => date("Y-m-d h:i:s") ), array( '%s', '%s', '%s' ) );    
        $bsid = $wpdb->insert_id;
    //}
    
    return $items;
}
add_filter( 'wpcf7_ajax_json_echo', 'lp_get_submit_content_to_db', 10, 2 );

/**
* Contact form 7 custom fields
*/
//if( function_exists( 'wpcf7_add_shortcode' ) ):
wpcf7_add_shortcode('listingcategory', 'lp_listing_category_wpcf7', true);
function lp_listing_category_wpcf7(){
    global $region_list;    
    
     $categories = get_terms( 'ait-dir-item-category', array() );
     
    $output = "<select style=\"width:80%;height:150px;\" multiple name='wpcf7listingCat[]' id='wpcf7listingCat' >";
    
        foreach( $categories as $cat ){
            $output .= "<option value='".$cat->term_id."'>".$cat->name."</option>";
        }
    
    $output .="</select>";
    
    $output .="<input type=\"hidden\" name=\"wpcf7listingCatNames\" id=\"wpcf7listingCatNames\" value=\"\">";
    
    $output .='<script type="text/javascript">jQuery.noConflict(); jQuery(function() {jQuery("#wpcf7listingCat").change(function(e) {var opts = e.target.options;var len = opts.length;var selected = [];for (var i = 0; i < len; i++) {if (opts[i].selected) {selected.push(opts[i].text);}}console.dir(selected); jQuery("#wpcf7listingCatNames").val(selected.join(", ")); }); });</script>';
    
    return $output;
}



wpcf7_add_shortcode('listinglocation', 'lp_listing_location_wpcf7', true);
function lp_listing_location_wpcf7(){
    global $region_list;    
    
     $locations = get_terms( 'ait-dir-item-location', array() );
     
    $output = "<select style=\"width:80%;height:150px;\" multiple name='wpcf7listingLoc[]' id='wpcf7listingLoc' >";
    
        foreach( $locations as $loc ){
            $output .= "<option value='".$loc->term_id."'>".$loc->name."</option>";
        }
    
    $output .="</select>";
    
    $output .="<input type=\"hidden\" name=\"wpcf7listingLocNames\" id=\"wpcf7listingLocNames\" value=\"\">";
    
    $output .='<script type="text/javascript">jQuery.noConflict(); jQuery(function() {jQuery("#wpcf7listingLoc").change(function(e) {var opts = e.target.options;var len = opts.length;var selected = [];for (var i = 0; i < len; i++) {if (opts[i].selected) {selected.push(opts[i].text);}}console.dir(selected); jQuery("#wpcf7listingLocNames").val(selected.join(", ")); }); });</script>';
    
    return $output;
}

function lp_wpcf7_init_uploads() {
    $dir = wpcf7_upload_dir( 'dir' ) . '/lp_submission';
    wp_mkdir_p( trailingslashit( $dir ) );
    @chmod( $dir, 0755 );
    /*$htaccess_file = trailingslashit( $dir ) . '.htaccess';
    if ( file_exists( $htaccess_file ) )
        return;

    if ( $handle = @fopen( $htaccess_file, 'w' ) ) {
        fwrite( $handle, "Deny from all\n" );
        fclose( $handle );
    }*/
}

remove_filter( 'wpcf7_validate_file', 'wpcf7_file_validation_filter' );
add_filter( 'wpcf7_validate_file', 'lp_wpcf7_file_validation_filter', 10, 2 );
function lp_wpcf7_file_validation_filter( $result, $tag ) {
    $tag = new WPCF7_Shortcode( $tag );

    $name = $tag->name;

    $file = isset( $_FILES[$name] ) ? $_FILES[$name] : null;

    if ( $file['error'] && UPLOAD_ERR_NO_FILE != $file['error'] ) {
        $result['valid'] = false;
        $result['reason'][$name] = wpcf7_get_message( 'upload_failed_php_error' );
        return $result;
    }

    if ( empty( $file['tmp_name'] ) && $tag->is_required() ) {
        $result['valid'] = false;
        $result['reason'][$name] = wpcf7_get_message( 'invalid_required' );
        return $result;
    }

    if ( ! is_uploaded_file( $file['tmp_name'] ) )
        return $result;

    $allowed_file_types = array();

    if ( $file_types_a = $tag->get_option( 'filetypes' ) ) {
        foreach ( $file_types_a as $file_types ) {
            $file_types = explode( '|', $file_types );

            foreach ( $file_types as $file_type ) {
                $file_type = trim( $file_type, '.' );
                $file_type = str_replace( array( '.', '+', '*', '?' ),
                    array( '\.', '\+', '\*', '\?' ), $file_type );
                $allowed_file_types[] = $file_type;
            }
        }
    }

    $allowed_file_types = array_unique( $allowed_file_types );
    $file_type_pattern = implode( '|', $allowed_file_types );

    $allowed_size = 1048576; // default size 1 MB

    if ( $file_size_a = $tag->get_option( 'limit' ) ) {
        $limit_pattern = '/^([1-9][0-9]*)([kKmM]?[bB])?$/';

        foreach ( $file_size_a as $file_size ) {
            if ( preg_match( $limit_pattern, $file_size, $matches ) ) {
                $allowed_size = (int) $matches[1];

                if ( ! empty( $matches[2] ) ) {
                    $kbmb = strtolower( $matches[2] );

                    if ( 'kb' == $kbmb )
                        $allowed_size *= 1024;
                    elseif ( 'mb' == $kbmb )
                        $allowed_size *= 1024 * 1024;
                }

                break;
            }
        }
    }

    /* File type validation */

    // Default file-type restriction
    if ( '' == $file_type_pattern )
        $file_type_pattern = 'jpg|jpeg|png|gif|pdf|doc|docx|ppt|pptx|odt|avi|ogg|m4a|mov|mp3|mp4|mpg|wav|wmv';

    $file_type_pattern = trim( $file_type_pattern, '|' );
    $file_type_pattern = '(' . $file_type_pattern . ')';
    $file_type_pattern = '/\.' . $file_type_pattern . '$/i';

    if ( ! preg_match( $file_type_pattern, $file['name'] ) ) {
        $result['valid'] = false;
        $result['reason'][$name] = wpcf7_get_message( 'upload_file_type_invalid' );
        return $result;
    }

    /* File size validation */

    if ( $file['size'] > $allowed_size ) {
        $result['valid'] = false;
        $result['reason'][$name] = wpcf7_get_message( 'upload_file_too_large' );
        return $result;
    }
    
    $uploads_dir = wpcf7_upload_tmp_dir();
    wpcf7_init_uploads(); // Confirm upload dir

    $filename = $file['name'];
    $filename = wpcf7_antiscript_file_name( $filename );
    $filename = wp_unique_filename( $uploads_dir, $filename );

    $new_file = trailingslashit( $uploads_dir ) . $filename;
    

    if ( false === @move_uploaded_file( $file['tmp_name'], $new_file ) ) {
        $result['valid'] = false;
        $result['reason'][$name] = wpcf7_get_message( 'upload_failed' );
        return $result;
    }
    
    /** For Lokalpages photo submissions */
    $lp_uploads_dir = wpcf7_upload_dir( 'dir' ) . '/lp_submission';
    lp_wpcf7_init_uploads();  
    $lp_copy_file = trailingslashit( $lp_uploads_dir ) . $filename; 
    copy( $new_file, $lp_copy_file );

    // Make sure the uploaded file is only readable for the owner process
    @chmod( $new_file, 0400 );
    
    if ( $contact_form = wpcf7_get_current_contact_form() ) {
        $contact_form->uploaded_files[$name] = $new_file;

        if ( empty( $contact_form->posted_data[$name] ) )
            $contact_form->posted_data[$name] = $filename;
    }
    
    
    return $result;
}

//endif; // End of Contact Form 7

/**
 * Main function to generate the related posts output
 *
 * @since 1.0.1
 *
 * @param    array    $args    Parameters in a query string format
 * @return    string            HTML formatted list of related posts
 */
add_filter( 'ald_crp', 'lp_rand_ald_crp', 99, 2 );
function lp_rand_ald_crp( $output, $args = array() ) {
    global $wpdb, $post, $single, $crp_settings;
    
    /*$_output = '<div id="crp_related"> </div>';
    if( $output != $_output ) return $output;*/
    
    $cache_name = 'crp_related_posts_widget';
    delete_post_meta_by_key( $cache_name ); // Delete the cache
    
    $defaults = array(
        'is_widget' => FALSE,
        'echo' => TRUE,
    );
    $defaults = array_merge( $defaults, $crp_settings );

    // Parse incomming $args into an array and merge it with $defaults
    $args = wp_parse_args( $args, $defaults );

    // Declare each item in $args as its own variable i.e. $type, $before.
    extract( $args, EXTR_SKIP );

    //Support caching to speed up retrieval
    if ( ! empty( $cache ) ) {
        $output = ( $is_widget ) ? get_post_meta( $post->ID, $cache_name, true ) : get_post_meta( $post->ID, $cache_name, true );
        if ( $output ) {
            return $output;
        }
    }
    $exclude_categories = explode( ',', $exclude_categories );

    $rel_attribute = ( $link_nofollow ) ? ' rel="nofollow" ' : ' ';
    $target_attribute = ( $link_new_window ) ? ' target="_blank" ' : ' ';
    
    $terms_cat = get_the_terms( $post->ID, 'ait-dir-item-category' );
        $term_cat_ids = array(); // default to empty
        if( $terms_cat )
            foreach( $terms_cat as $termc ) $term_cat_ids[] = $termc->term_id; // category
    
    
    // Retrieve the list of posts
    $cat_results = lp_get_crp_random_posts_id( array_merge( $args, array(
        'postid' => $post->ID,
        'strict_limit' => TRUE,
        'term_ids' => $term_cat_ids,
        'termk' => 'cat' 
    ) ) );
    
    //echo '<pre>'.print_r($cat_results,true).'</pre>';
        
    $terms_loc = get_the_terms( $post->ID, 'ait-dir-item-location' );   
        $term_loc_ids = array(); // default to empty    
        if( $terms_loc )
            foreach( $terms_loc as $terml ) $term_loc_ids[] = $terml->term_id; // location
    // Retrieve the list of posts - Disabled
    /*$loc_results = lp_get_crp_random_posts_id( array_merge( $args, array(
        'postid' => $post->ID,
        'strict_limit' => TRUE,
        'term_ids' => $term_loc_ids,
        'termk' => 'loc' 
    ) ) );*/ 
    $loc_results = array(); 
    
    // shuffle results
    shuffle($cat_results);
    $results = array_merge($cat_results,$loc_results);
    
    $output = ( is_singular() ) ? '<div id="crp_related" class="crp_related' . ( $is_widget ? '_widget' : '' ) . '">' : '<div class="crp_related' . ( $is_widget ? '_widget' : '' ) . '">';

    if ( $results ) {
        $loop_counter = 0;

        if ( ! $is_widget ) {
            $title = str_replace( "%postname%", $post->post_title, $title );    // Replace %postname% with the title of the current post

            /**
             * Filter the title of the Related Posts list
             *
             * @since    1.9
             *
             * @param    string    $title    Title/heading of the Related Posts list
             */
            $output .= apply_filters( 'crp_heading_title', $title );
        }

        /**
         * Filter the opening tag of the related posts list
         *
         * @since    1.9
         *
         * @param    string    $before_list    Opening tag set in the Settings Page
         */
        $output .= apply_filters( 'crp_before_list', $before_list );

        foreach ( $results as $result ) {
            
            $item_disabled = get_post_meta( $result->ID, '_serv_related_disable', true );
            if( $item_disabled == 'y' ) continue;

            /**
             * Filter the post ID for each result. Allows a custom function to hook in and change the ID if needed.
             *
             * @since    1.9
             *
             * @param    int    $result->ID    ID of the post
             */
            $resultid = apply_filters( 'crp_post_id', $result->ID );

            $result = get_post( $resultid );    // Let's get the Post using the ID

            /**
             * Filter the post ID for each result. This filtered ID is passed as a parameter to fetch categories.
             *
             * This is useful since you might want to fetch a different set of categories for a linked post ID,
             * typically in the case of plugins that let you set mutiple languages
             *
             * @since    1.9
             *
             * @param    int    $result->ID    ID of the post
             */
            $resultid = apply_filters( 'crp_post_cat_id', $result->ID );

            $categorys = get_the_category( $resultid );    //Fetch categories of the plugin

            $p_in_c = false;    // Variable to check if post exists in a particular category
            foreach ( $categorys as $cat ) {    // Loop to check if post exists in excluded category
                $p_in_c = ( in_array( $cat->cat_ID, $exclude_categories ) ) ? true : false;
                if ( $p_in_c ) break;    // End loop if post found in category
            }

            if ( ! $p_in_c ) {

                /**
                 * Filter the opening tag of each list item.
                 *
                 * @since    1.9
                 *
                 * @param    string    $before_list_item    Tag before each list item. Can be defined in the Settings page.
                 * @param    object    $result    Object of the current post result
                 */
                $output .= apply_filters( 'crp_before_list_item', $before_list_item, $result );    // Pass the post object to the filter

                $title = crp_max_formatted_content( get_the_title( $result->ID ), $title_length );    // Get the post title and crop it if needed

                /**
                 * Filter the title of each list item.
                 *
                 * @since    1.9
                 *
                 * @param    string    $title    Title of the post.
                 * @param    object    $result    Object of the current post result
                 */
                $title = apply_filters( 'crp_title', $title, $result );

                if ( 'after' == $post_thumb_op ) {
                    $output .= '<a href="' . get_permalink( $result->ID ) . '" ' . $rel_attribute . ' ' . $target_attribute . 'class="crp_title">' . $title . '</a>'; // Add title if post thumbnail is to be displayed after
                }
                if ( 'inline' == $post_thumb_op || 'after' == $post_thumb_op || 'thumbs_only' == $post_thumb_op ) {
                    $output .= '<a href="' . get_permalink( $result->ID ) . '" ' . $rel_attribute . ' ' . $target_attribute . '>';
                    $output .= crp_get_the_post_thumbnail( array(
                        'postid' => $result->ID,
                        'thumb_height' => $thumb_height,
                        'thumb_width' => $thumb_width,
                        'thumb_meta' => $thumb_meta,
                        'thumb_html' => $thumb_html,
                        'thumb_default' => $thumb_default,
                        'thumb_default_show' => $thumb_default_show,
                        'thumb_timthumb' => $thumb_timthumb,
                        'thumb_timthumb_q' => $thumb_timthumb_q,
                        'scan_images' => $scan_images,
                        'class' => 'crp_thumb',
                        'filter' => 'crp_postimage',
                    ) );
                    $output .= '</a>';
                }
                if ( 'inline' == $post_thumb_op || 'text_only' == $post_thumb_op ) {
                    $output .= '<a href="' . get_permalink( $result->ID ) . '" ' . $rel_attribute . ' ' . $target_attribute . ' class="crp_title">' . $title . '</a>'; // Add title when required by settings
                }
                if ( $show_author ) {
                    $author_info = get_userdata( $result->post_author );
                    $author_link = get_author_posts_url( $author_info->ID );
                    $author_name = ucwords( trim( stripslashes( $author_info->display_name ) ) );

                    /**
                     * Filter the author name.
                     *
                     * @since    1.9.1
                     *
                     * @param    string    $author_name    Proper name of the post author.
                     * @param    object    $author_info    WP_User object of the post author
                     */
                    $author_name = apply_filters( 'crp_author_name', $author_name, $author_info );

                    $crp_author .= '<span class="crp_author"> ' . __( ' by ', CRP_LOCAL_NAME ).'<a href="' . $author_link . '">' . $author_name . '</a></span> ';

                    /**
                     * Filter the text with the author details.
                     *
                     * @since    2.0.0
                     *
                     * @param    string    $crp_author    Formatted string with author details and link
                     * @param    object    $author_info    WP_User object of the post author
                     */
                    $crp_author = apply_filters( 'crp_author', $crp_author, $author_info);

                    $output .= $crp_author;
                }
                if ( $show_date ) {
                    $output .= '<span class="crp_date"> ' . mysql2date( get_option( 'date_format', 'd/m/y' ), $result->post_date ) . '</span> ';
                }
                if ( $show_excerpt ) {
                    $output .= '<span class="crp_excerpt"> ' . crp_excerpt( $result->ID, $excerpt_length ) . '</span>';
                }
                $loop_counter++;

                /**
                 * Filter the closing tag of each list item.
                 *
                 * @since    1.9
                 *
                 * @param    string    $after_list_item    Tag after each list item. Can be defined in the Settings page.
                 * @param    object    $result    Object of the current post result
                 */
                $output .= apply_filters( 'crp_after_list_item', $after_list_item, $result );
            }
            if ( $loop_counter == $limit ) break;    // End loop when related posts limit is reached
        } //end of foreach loop
        if ( $show_credit ) {

            /** This filter is documented in contextual-related-posts.php */
            $output .= apply_filters( 'crp_before_list_item', $before_list_item, $result );    // Pass the post object to the filter

            $output .= sprintf( __( 'Powered by <a href="%s" rel="nofollow">Contextual Related Posts</a>', CRP_LOCAL_NAME ), esc_url( 'http://ajaydsouza.com/wordpress/plugins/contextual-related-posts/' ) );

            /** This filter is documented in contextual-related-posts.php */
            $output .= apply_filters( 'crp_after_list_item', $after_list_item, $result );

        }

        /**
         * Filter the closing tag of the related posts list
         *
         * @since    1.9
         *
         * @param    string    $after_list    Closing tag set in the Settings Page
         */
        $output .= apply_filters( 'crp_after_list', $after_list );

        $clearfix = '<div style="clear:both"></div>';

        /**
         * Filter the clearfix div tag. This is included after the closing tag to clear any miscellaneous floating elements;
         *
         * @since    2.0.0
         *
         * @param    string    $clearfix    Contains: <div style="clear:both"></div>
         */
        $output .= apply_filters( 'crp_clearfix', $clearfix );

    } else {
        $output .= ( $blank_output ) ? ' ' : '<p>' . $blank_output_text . '</p>';
    }

    if ( false === ( strpos( $output, $before_list_item ) ) ) {
        $output = '<div id="crp_related">';
        $output .= ($blank_output) ? ' ' : '<p>' . $blank_output_text . '</p>';
    }

    $output .= '</div>'; // closing div of 'crp_related'


    //Support caching to speed up retrieval
    if ( ! empty( $cache ) ) {
        if ( $is_widget ) {
            update_post_meta( $post->ID, $cache_name, $output, '' );
        } else {
            update_post_meta( $post->ID, $cache_name, $output, '' );
        }
    }

    /**
     * Filter the output
     *
     * @since    1.9.1
     *
     * @param    string    $output    Formatted list of related posts
     * @param    array    $args    Complete set of arguments
     */
    return $output;
}

function lp_get_crp_random_posts_id( $args = array() ) {
    global $wpdb, $post, $single, $crp_settings;

    // Initialise some variables
    $fields = '';
    $where = '';
    $join = '';
    $groupby = '';
    $orderby = '';
    $limits = '';
    $match_fields = '';

    $defaults = array(
        'postid' => FALSE,
        'strict_limit' => FALSE,
    );
    $defaults = array_merge( $defaults, $crp_settings );

    // Parse incomming $args into an array and merge it with $defaults
    $args = wp_parse_args( $args, $defaults );

    // Declare each item in $args as its own variable i.e. $type, $before.
    extract( $args, EXTR_SKIP );

    $post = ( empty( $postid ) ) ? $post : get_post( $postid );

    $limit = ( $strict_limit ) ? $limit : ( $limit * 3 );

    parse_str( $post_types, $post_types );    // Save post types in $post_types variable

    // Are we matching only the title or the post content as well?
    if( $match_content ) {
        $stuff = $post->post_title . ' ' . crp_excerpt( $post->ID, $match_content_words, false );
        $match_fields = "post_title,post_content";
    } else {
        $stuff = $post->post_title;
        $match_fields = "post_title";
    }

    // Make sure the post is not from the future
    $time_difference = get_option( 'gmt_offset' );
    $now = gmdate( "Y-m-d H:i:s", ( time() + ( $time_difference * 3600 ) ) );

    // Limit the related posts by time
    $daily_range = $daily_range - 1;
    $from_date = strtotime( '-' . $daily_range . ' DAY' , strtotime( $now ) );
    $from_date = date ( 'Y-m-d H:i:s' , $from_date );

    // Create the SQL query to fetch the related posts from the database
    if ( ( is_int( $post->ID ) ) && ( '' != $stuff ) ) {

        // Fields to return
        $fields = " $wpdb->posts.ID, '$termk' as `tax`";

        // Create the base WHERE clause
        //$where .= $wpdb->prepare( " AND MATCH (" . $match_fields . ") AGAINST ('%s') ", $stuff );    // FULLTEXT matching algorithm
        //$where .= $wpdb->prepare( " AND $wpdb->posts.post_date < '%s' ", $now );        // Show posts before today
        //$where .= $wpdb->prepare( " AND $wpdb->posts.post_date >= '%s' ", $from_date );    // Show posts after the date specified
        $where .= " AND $wpdb->posts.post_status = 'publish' ";                    // Only show published posts
        $where .= $wpdb->prepare( " AND $wpdb->posts.ID != %d ", $post->ID );    // Show posts after the date specified
        if ( '' != $exclude_post_ids ) {
            $where .= " AND $wpdb->posts.ID NOT IN (" . $exclude_post_ids . ") ";
        }
        $where .= " AND $wpdb->posts.post_type IN ('" . join( "', '", $post_types ) . "') ";    // Array of post types
        
        //if( $term_cat_ids )
        
        //$where .= " AND ltt.term_id IN ('" . join( "', '", array_merge( $term_cat_ids, $term_loc_ids ) ) . "')";    // Array of term_ids
        $where .= " AND ltt.term_id IN ('" . join( "', '", $term_ids ) . "')";    // Array of term_ids
        // Create the base LIMITS clause
        $limits .= $wpdb->prepare( " LIMIT %d ", $limit );

        /**
         * Filter the SELECT clause of the query.
         *
         * @param string   $fields  The SELECT clause of the query.
         */
        $fields = apply_filters( 'lp_rand_crp_posts_fields', $fields, $post->ID );

        /**
         * Filter the JOIN clause of the query.
         *
         * @param string   $join  The JOIN clause of the query.
         */
         $join .= "inner join lpd_term_relationships trc on lpd_posts.ID = trc.object_id inner join lpd_term_taxonomy ltt on trc.term_taxonomy_id = ltt.term_taxonomy_id";
         $join = apply_filters( 'lp_rand_crp_posts_join', $join, $post->ID );

        /**
         * Filter the WHERE clause of the query.
         *
         * @param string   $where  The WHERE clause of the query.
         */
        $where = apply_filters( 'lp_rand_crp_posts_where', $where, $post->ID );

        /**
         * Filter the GROUP BY clause of the query.
         *
         * @param string   $groupby  The GROUP BY clause of the query.
         */
        $groupby = apply_filters( 'lp_rand_crp_posts_groupby', $groupby, $post->ID );


        /**
         * Filter the ORDER BY clause of the query.
         *
         * @param string   $orderby  The ORDER BY clause of the query.
         */
        $orderby .= 'ltt.taxonomy';
        $orderby = apply_filters( 'lp_rand_crp_posts_orderby', $orderby, $post->ID );

        /**
         * Filter the LIMIT clause of the query.
         *
         * @param string   $limits  The LIMIT clause of the query.
         */
        $limits = apply_filters( 'lp_rand_crp_posts_limits', $limits, $post->ID );

        if ( ! empty( $groupby ) ) {
            $groupby = 'GROUP BY ' . $groupby;
        }
        if ( !empty( $orderby ) ) {
            $orderby = 'ORDER BY ' . $orderby;
        }
        $sql = "SELECT DISTINCT $fields FROM $wpdb->posts $join WHERE 1=1 $where $groupby $orderby $limits";

        
        $results = $wpdb->get_results( $sql );
        //if( isset( $args['lp_flag'] ) ) return $results;
    } else {
        $results = false;
    }

    /**
     * Filter object containing the post IDs.
     *
     * @since    1.9
     *
     * @param     object   $results  Object containing the related post IDs
     */
     //if( $post->ID == 873 )
     //echo '<pre>'.print_r($sql,true).'</pre>';
    return apply_filters( 'ext_lp_get_crp_random_posts_id', $results, $post->ID );
}

function lp_send_email_($postid,$post){
    $optDir = get_post_meta($postid, '_ait-dir-item', true);
    $usersarray = array();
    $usersarray[] = 'skyguyverph@gmail.com';
    //$thumbnailDir = wp_get_attachment_image_src( get_post_thumbnail_id( $postid ) );
    //$bizThumbmailDir = getRealThumbnailUrl($thumbnailDir[0]);

    $body = sprintf( '
Hello there,

Lokalpages.com is Cebu�s only active online directory listing.  We connect you directly to customers looking for your products and services.


Our aim is to help your business in providing your customers an easier way to search for your business, provide them with the right info and help build lasting relationships.

We believe that putting your business info directly in front of you customers and making it easy for them to get in touch with you directly will give you an edge in this highly social and mobile environment.

We will also help you in giving your customers the right info at the right time, whether that be driving directions to your location, hours of operations or a phone number they can click to call you on mobile phones.


Lokalpages.com helps you build a loyal fan base and your own advertisers by showing their appreciations with ratings and reviews. Strong reviews and pictures of your business, products and services helps your business stand out to customers online.

If you don�t have your own website Lokalpages.com got you covered. Your business listing with us can act as your website.

Your 30-day independent business listing with Lokalpages.com has commenced starting %s. Enjoy the full benefits of Lokalpages.comand allow us to help you grow your business and continue connecting with your most valuable asset � your customers!

We highly recommend you to click this link and update your business info. 

<%s> 
',
        current_time( 'd-m-Y' ),
        get_permalink( $postid )
        //$bizThumbmailDir
    );
    ob_start();
        echo $body;
        /*echo '<pre>'. print_r(get_post($postid),true) .'</pre>';
        echo '<pre>'. print_r($optDir,true) .'</pre>';
        echo '<pre>'. print_r(get_post_status($postid),true) .'</pre>';
        echo '<pre>'. print_r($post,true) .'</pre>';
        echo '<pre>'. print_r($_POST,true) .'</pre>';*/
        $email_body = ob_get_contents();
    ob_end_clean();
    wp_mail( 
    // Send it to yourself
    //get_option( 'admin_email' ), 
    $_POST['_ait-dir-item']['email'], 
    //$optDir['email'], 
    'Welcome!', 
    $email_body, 
    // extra headers
    array (
        'Bcc:' . implode( ",", $usersarray ),
        'From: "Lokalpages" <info@lokalpages.com>'// . get_option( 'admin_email' )
    ) 
);
    // add meta to determine notification was sent.
    add_post_meta($postid,'lp_new_biz_notify',true);
}

add_action( 'save_post', 'lp_send_email', 10, 3);
function lp_send_email( $post_id, $post, $update) {
    // If this is just a revision, don't send the email.
    if ( wp_is_post_revision( $post_id ) )
        return;
    
    // If update don't send email    
    //if( $update ) return;
    
    // lets check if post is not revision
    if ( !wp_is_post_revision( $post_id ) && 'ait-dir-item' === get_post_type( $post_id ) && 'publish' === get_post_status($post_id) ) {
        
        if( !get_post_meta($post_id,'lp_new_biz_notify',true) )
                lp_send_email_($post_id,$post);
    }
}

wpcf7_add_shortcode('get_current_date', 'lp_get_current_date_wpcf7', true); 
function lp_get_current_date_wpcf7()
 {
     return (String) date("F j, Y");
 }
?>
