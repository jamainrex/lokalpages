<?php
/**
* Featured items
*/
/**
 * get items from DB
 * @param  integer $category category ID
 * @param  integer $location location ID
 * @param  string  $search   search keyword
 * @param  array   $radius   (radius in km, lat, lon)
 * @return array             items
 */
add_shortcode('lp_featuredThumbnails', 'lp_getFeaturedItems_sc');
function lp_getFeaturedItems_sc( $atts, $content = null ) {
        extract( shortcode_atts( array(
        'order' => 'DESC',
        'orderby' => 'date',
        'title' => 'Featured Listings',
        'height' => '100',
        'items' => '5',
        ), $atts ) );
        
        $output = "";
        $output .= '<h2 class="lp-featured-items-title">'.$title.'</h2>';
        $output .= lp_getFeaturedItems($order,$orderby,$height,$items);
        return $output;
        
}
        
function lp_getFeaturedItems($orderby='date', $order='DESC', $height='100', $num_items=5) {
global $paged;
    
    $params = array(
        'post_type' => 'ait-dir-item',
        /*'nopaging' => true,*/
        'posts_per_page' =>$num_items,
        'paged' => $paged,
        'post_status' => 'publish',
        'orderby' => $orderby,
        'order' => $order,
        'meta_query' => array( array( 'key' => 'dir_featured', 'value' => 'yes') )
    );

    $itemsQuery = new WP_Query();
    $items = $itemsQuery->query($params);
    
    //echo '<pre>'.print_r($items,true).'</pre>';
    
    $_featured_items = array();
    
    $output = "";
    $output .= '<div id="lp-featured-items-wrap"><ul id="lp-featured-items">';
    
    // add item details
    foreach ($items as $key => $item) {
        $output .= '<li>';   
            // Post ID
             $_featured_items[$item->ID]['ID'] = $item->ID;
             // name
             $_featured_items[$item->ID]['name'] = $item->post_name;
             // title
             $_featured_items[$item->ID]['title'] = get_the_title($item->ID);
            // link
             $_featured_items[$item->ID]['permalink'] = get_permalink($item->ID);
            // thumbnail
            $image = wp_get_attachment_image_src( get_post_thumbnail_id($item->ID) );
            if($image !== false){
                 $_featured_items[$item->ID]['thumbnail'] = getRealThumbnailUrl($image[0]);
            } else {

                 $_featured_items[$item->ID]['thumbnail'] = getRealThumbnailUrl(getCategoryMeta("icon",intval(lp_get_the_term_id($item->ID))));
            }
            
        // Filter out size
            $_featured_items[$item->ID]['thumbnail'] = str_replace('-125x125', '',$_featured_items[$item->ID]['thumbnail']);
            
        $output .= '<a href="'.$_featured_items[$item->ID]['permalink'].'" title="'.$_featured_items[$item->ID]['title'].'" ><img height="'.$height.'" src="'.$_featured_items[$item->ID]['thumbnail'].'"></a>';
        $output .= '</li>';
            
    }
    $output .= '</ul></div>';
    //echo '<pre>'.print_r($_featured_items,true).'</pre>';
    //return $_featured_items;
    return $output;
}

/**
* Featured items
*/
/**
 * get items from DB
 * @param  integer $category category ID
 * @param  integer $location location ID
 * @param  string  $search   search keyword
 * @param  array   $radius   (radius in km, lat, lon)
 * @return array             items
 */
add_shortcode('lp_localThumbnails', 'lp_getLocalItems_sc');
function lp_getLocalItems_sc( $atts, $content = null ) {
        extract( shortcode_atts( array(
        'order' => 'DESC',
        'orderby' => 'date',
        'title' => '',
        'height' => '100',
        'items' => '5',
        ), $atts ) );
        
        $output = "";
        if( !empty($title) ) $output .= '<h2 class="lp-featured-items-title">'.$title.'</h2>';
        
        $output .= lp_getLocalItems($order,$orderby,$height,$items);
        return $output;
        
}
        
function lp_getLocalItems($orderby='date', $order='DESC', $height='100', $num_items=5) {
global $paged;
    $params = array(
        'post_type' => 'ait-dir-item',
        'posts_per_page' =>$num_items,
        'paged' => $paged,
        'post_status' => 'publish',
        'orderby' => $orderby,
        'order' => $order,
        'meta_query' => array( array( 'key' => 'dir_localbiz', 'value' => 'yes') )
    );

    $itemsQuery = new WP_Query();
    $items = $itemsQuery->query($params);
    
    //echo '<pre>'.print_r($items,true).'</pre>';
    
    $_featured_items = array();
    
    $output = "";
    $output .= '<div id="lp-local-items-wrap"><ul id="lp-local-items">';
    
    // add item details
    foreach ($items as $key => $item) {
        $output .= '<li>';   
            // Post ID
             $_featured_items[$item->ID]['ID'] = $item->ID;
             // name
             $_featured_items[$item->ID]['name'] = $item->post_name;
             // title
             $_featured_items[$item->ID]['title'] = get_the_title($item->ID);
            // link
             $_featured_items[$item->ID]['permalink'] = get_permalink($item->ID);
            // thumbnail
            $image = wp_get_attachment_image_src( get_post_thumbnail_id( $item->ID ) );            
            if($image !== false){
                 $_featured_items[$item->ID]['thumbnail'] = getRealThumbnailUrl($image[0]);
            } else {

                 $_featured_items[$item->ID]['thumbnail'] = getRealThumbnailUrl(getCategoryMeta("icon",intval(lp_get_the_term_id($item->ID))));
            }
            
            // Filter out size
            $_featured_items[$item->ID]['thumbnail'] = str_replace('-125x125', '',$_featured_items[$item->ID]['thumbnail']);
            
        $output .= '<a href="'.$_featured_items[$item->ID]['permalink'].'" title="'.$_featured_items[$item->ID]['title'].'" ><img height="'.$height.'" src="'.$_featured_items[$item->ID]['thumbnail'].'"></a>';
        $output .= '</li>';
            
    }
    $output .= '</ul></div>';
    //echo '<pre>'.print_r($_featured_items,true).'</pre>';
    //return $_featured_items;
    return $output;
}

add_shortcode('lpGetRatings', 'lp_getRatings_sc'); 
function lp_getRatings_sc( $atts, $content = null ) {
        extract( shortcode_atts( array(
        'orderby' => 'high_rated',
        'height' => '100',
        'limit' => '5',
        'title' => 'Most Rated'
        ), $atts ) );
        
        $output = "";        
        $item_most_rated = lp_mostRatedItems($orderby,$limit);
        $_most_rated = array();
        
        //echo '<pre>'.print_r($item_most_rated,true).'</pre>';
        
        $output .= '<div id="lp-local-items-wrap"><ul id="lp-local-items">';
    
        // add item details
        foreach ($item_most_rated as $item) {
            $output .= '<li>';   
                // Post ID
                 $_most_rated[$item->ID]['ID'] = $item->ID;
                 // name
                 $_most_rated[$item->ID]['name'] = $item->post_name;
                 // title
                 $_most_rated[$item->ID]['title'] = get_the_title($item->ID);
                // link
                 $_most_rated[$item->ID]['permalink'] = get_permalink($item->ID);
                // thumbnail
                $image = wp_get_attachment_image_src( get_post_thumbnail_id( $item->ID) );            
                if($image !== false){
                     $_most_rated[$item->ID]['thumbnail'] = getRealThumbnailUrl($image[0]);
                } else {

                     $_most_rated[$item->ID]['thumbnail'] = getRealThumbnailUrl(getCategoryMeta("icon",intval(lp_get_the_term_id($item->ID))));
                }
                
                // Filter out size
                $_most_rated[$item->ID]['thumbnail'] = str_replace('-125x125', '',$_most_rated[$item->ID]['thumbnail']);
                
            $output .= '<a href="'.$_most_rated[$item->ID]['permalink'].'" title="'.$_most_rated[$item->ID]['title'].'" ><img height="'.$height.'" src="'.$_most_rated[$item->ID]['thumbnail'].'"></a>';
            $output .= '</li>';
                
        }
        $output .= '</ul></div>';
        
        return $output;
        
}
add_shortcode('lp_recentReview', 'lp_getRecentReview'); 
function lp_getRecentReview( $atts, $content = null ) {
        extract( shortcode_atts( array(
        'limit' => '5',
        'title' => 'Recent Reviews'
        ), $atts ) );
        
        $output = "";
        
        $output .= '<div id="lp-local-items-wrap">';
    
        $output .= lp_getRecentRatingElement($limit);
       
        $output .= '</div>';
        
        $filter = array('<a> </a>');
        
        $output = str_replace($filter,"",$output);
        
        return $output;
        
}

function lp_landing_carousel_sc($atts, $content = null  ){
global $post;
extract(  shortcode_atts(  array( 
    'id' => '',
 ), $atts ) );
 
 // enqueue new version of flexslider  
 wp_enqueue_style(  'flexslider-v2-css'  );
 wp_enqueue_script(  'jquery_flexslider_v2_2_2_js'  ); 
 wp_enqueue_script(  'jquery_landing_carousel_custom_js'  );  
 wp_enqueue_script(  'jquery_sh_core_js'  ); 
 wp_enqueue_script(  'jquery_sh_xml_js'  ); 
 wp_enqueue_script(  'jquery_sh_scipt_js'  ); 

 
 $output = "";
     $output .= '<div id="landing-carousel" class="lp-loading">
            <div id="landing-carousel-inner">
            <div id="lp-landing-carousel" class="flexslider carousel">
              <ul class="slides">';
    
        $output .= do_shortcode($content);
              
     $output .= '</ul>
            </div>
            </div>
        </div>';
     
    //removing extra <br>
    $Old     = array( '<br />', '<br>' );
    $New     = array( '','' );
    $output = str_replace( $Old, $New, $output );

 return $output;
} 
 add_shortcode(  'lp_landing_carousel', 'lp_landing_carousel_sc'  );
 
 function lp_landing_carousel_item_sc($atts, $content = null  ){
        extract(  shortcode_atts(  array( 
        'type' => 'item',
        'title' => '',
        'link' => '',
        'img' => '',
     ), $atts ) ); 
     
     $html = "";
     
     if( $type == 'item' ){
         $desc = '<div id="lp-carousel-desc">
                <div id="lpc-title">'.$title.'</div>
                <div id="lpc-links">
                    <a href="'.$link.'">See Overview</a> | <a href="'.$link.'?direction=yes">Get Direction</a>
                </div>
            </div>';
     }elseif( $type == 'cat' ){
          $desc = '<div id="lp-carousel-desc">
                <div id="lpc-title-cat"><a href="'.$link.'">'.$title.'</a></div>
            </div>';
     }elseif( $type == 'inner' ){
          $desc = '<div id="lp-carousel-desc">
                <div id="lpc-title-inner"><a href="'.$link.'">'.$title.'</a></div>
            </div>';
     }
     
     $html .= '<li><a href="'.$link.'"><img src="'.$img.'" /></a>'.$desc.'</li>';
     
     return $html;
 }
 add_shortcode(  'lp_landing_carousel_item', 'lp_landing_carousel_item_sc'  ); 

 
 function lp_business_categories_sc($atts, $content = null  ){
        extract(  shortcode_atts(  array( 
        'title' => '',
     ), $atts ) ); 
     
     $subcategories = get_terms( 'ait-dir-item-category', array( 'hide_empty' => false ) );
     $sc = array();
    // add category links
    $html = '<div class="category-subcategories clearfix"><ul class="subcategories lp-inner-page">';
    foreach ($subcategories as $category) {
        $category->link = get_term_link(intval($category->term_id), 'ait-dir-item-category');
        $category->icon = getRealThumbnailUrl(getCategoryMeta("icon", intval($category->term_id)));
        $category->excerpt = getCategoryMeta("excerpt", intval($category->term_id));
        
        $cat_letter = substr($category->name, 0,1);
        //$sc[$cat_letter]['cat'] = $category;
        $sc[strtolower( $cat_letter )] .= '<li class="category">
                    <div class="category-wrap-table">
                        <div class="category-wrap-row">
                            <div class="description">
                                <a href="'.$category->link.'">'.$category->name.'</a>
                            </div>
                        </div>
                    </div>
                </li>';
                
        /*$html .= '<li class="category">
                    <div class="category-wrap-table">
                        <div class="category-wrap-row">
                            <div class="description">
                                <h3><a href="'.$category->link.'">'.$category->name.'</a></h3>
                            </div>
                        </div>
                    </div>
                </li>';*/
    }
    $dc_col = sizeof( $sc ) / 4;
    $col_ctr = 1;
    $ul = "";
    foreach( $sc as $letter => $cbl ){
        $ul .= '<h2 class="lp-cbl-titler-header">'.strtoupper($letter).'</h2>';
        $ul .= '<ul class="lp-cat-inner-page-title">';
            $ul .= $cbl;
        $ul .= '</ul>';         
        
        if( $col_ctr % 4 == 0 ){
            $html .= '<li>'.$ul.'</li>';
            $ul = "";
        }
        $col_ctr++; 
        
    }
     
     //$html .= implode( '', $sc );
     
     $html .= '</ul></div>';
     
     return $html;
 }
add_shortcode( 'lp_business_categories', 'lp_business_categories_sc' );

function lp_business_locations_sc($atts, $content = null  ){
        extract(  shortcode_atts(  array( 
        'title' => '',
     ), $atts ) ); 
     
     $subcategories = get_terms( 'ait-dir-item-location', array( 'hide_empty' => false ) );
     $sc = array();
    // add category links
    $html = '<div class="category-subcategories clearfix"><ul class="subcategories lp-inner-page">';
    foreach ($subcategories as $category) {
        $category->link = get_term_link(intval($category->term_id), 'ait-dir-item-location');
        $category->icon = getRealThumbnailUrl(getCategoryMeta("icon", intval($category->term_id)));
        $category->excerpt = getCategoryMeta("excerpt", intval($category->term_id));
        
        $cat_letter = substr($category->name, 0,1);
        //$sc[$cat_letter]['cat'] = $category;
        $sc[$cat_letter] .= '<li class="category">
                    <div class="category-wrap-table">
                        <div class="category-wrap-row">
                            <div class="description">
                                <a href="'.$category->link.'">'.$category->name.'</a>
                            </div>
                        </div>
                    </div>
                </li>';
                
        /*$html .= '<li class="category">
                    <div class="category-wrap-table">
                        <div class="category-wrap-row">
                            <div class="description">
                                <h3><a href="'.$category->link.'">'.$category->name.'</a></h3>
                            </div>
                        </div>
                    </div>
                </li>';*/
    }
    $dc_col = sizeof( $sc ) / 4;
    $col_ctr = 1;
    $ul = "";
    foreach( $sc as $letter => $cbl ){
        $ul .= '<h2 class="lp-cbl-titler-header">'.strtoupper($letter).'</h2>';
        $ul .= '<ul class="lp-cat-inner-page-title">';
            $ul .= $cbl;
        $ul .= '</ul>';         
        
        if( $col_ctr % $dc_col == 0 ){
            $html .= '<li class="lp-col-fc">'.$ul.'</li>';
            $ul = "";
        }
        $col_ctr++; 
        
    }
     
     //$html .= implode( '', $sc );
     
     $html .= '</ul></div>';
     
     return $html;
 }
add_shortcode( 'lp_business_locations', 'lp_business_locations_sc' );

/**
* Recommended Listings
*/

add_shortcode('lp_localGetListings', 'lp_localGetListings_sc');
function lp_localGetListings_sc( $atts, $content = null ) {
        extract( shortcode_atts( array(
        'order' => 'DESC',
        'orderby' => 'rand',
        'title' => '',
        'height' => '100',
        'items' => '5',
        ), $atts ) );
        
        $output = "";
        $output .= '<hr />';
        if( !empty($title) ) $output .= '<h2 class="lp-featured-items-title">'.$title.'</h2>';
        
        $output .= lp_itemcarousel($orderby, $order, $height, $items);
        return $output;
        
}
        
function lp_itemcarousel($orderby='rand', $order='DESC', $height='100', $num_items=25) {
global $paged;
    $params = array(
        'post_type' => 'ait-dir-item',
        'posts_per_page' =>$num_items,
        'paged' => $paged,
        'post_status' => 'publish',
        'orderby' => $orderby,
        'order'   => $order
    );
    
    // enqueue new version of flexslider  
     wp_enqueue_style(  'flexslider-v2-css'  );
     wp_enqueue_script(  'jquery_flexslider_v2_2_2_js'  ); 
     wp_enqueue_script(  'jquery_listing_carousel_custom_js'  );  
     wp_enqueue_script(  'jquery_sh_core_js'  ); 
     wp_enqueue_script(  'jquery_sh_xml_js'  ); 
     wp_enqueue_script(  'jquery_sh_scipt_js'  );

    $itemsQuery = new WP_Query();
    $items = $itemsQuery->query($params);
    
    $_featured_items = array();
     $output = "";
     $output .= '<div id="landing-carousel" class="lp-loading" style="padding-top: 20px;">
            <div id="landing-carousel-inner">
            <div id="lp-landing-carousel" class="flexslider carousel">
              <ul class="slides">';
    
        
    $inner_html = "";
    // add item details
    foreach ($items as $key => $item) {
    $image = wp_get_attachment_image_src( get_post_thumbnail_id($item->ID) );
    if($image !== false){
                 $thumbnail = getRealThumbnailUrl($image[0]);
            } else {
                 $term_id = intval(lp_get_the_term_id($item->ID));
                 if( !$term_id ) continue; // if no thumbnail
                 
                 $thumbnail = getRealThumbnailUrl(getCategoryMeta("icon", $term_id ));
            }
            
        // Filter out size
        //$thumbnail = str_replace('-125x125', '',$thumbnail);    
    
 $inner_html .= '[lp_landing_carousel_item withdesc="'.$withdesc.'" type="inner" title="'.get_the_title($item->ID).'" link="'.get_permalink($item->ID).'" img="'.$thumbnail.'" width="150" height="150" ][/lp_landing_carousel_item]';  
    }
    
    $output .= do_shortcode($inner_html);
    $output .= '</ul>
            </div>
            </div>
        </div>';
     
    //removing extra <br>
    $Old     = array( '<br />', '<br>' );
    $New     = array( '','' );
    $output = str_replace( $Old, $New, $output );
    return $output;
}

/**
* Landing page shortcode
*/
function lp_landing_blurbsection_sc($atts, $content = null  ){
global $post;
extract(  shortcode_atts(  array( 
    'id' => '',
    'title' => ''
 ), $atts ) );
 
 $output = "";
     $output .= '<div class="entry-content-blurbsection">
                    <div class="defaultContentWidth">
                    <div class="row">
                    <h2 class="subcategories-title">'.$title.'</h2>';
    
        $output .= do_shortcode($content);
              
     $output .= '</div></div></div>';
     
    //removing extra <br>
    $Old     = array( '<br />', '<br>' );
    $New     = array( '','' );
    $output = str_replace( $Old, $New, $output );

 return $output;
} 
 add_shortcode(  'lp_landing_blurbsection', 'lp_landing_blurbsection_sc'  );
 
 function lp_landing_blurbsection_item_sc($atts, $content = null  ){
        extract(  shortcode_atts(  array( 
        'title' => '',
        'btn_title' => '',
        'btn_link' => ''
     ), $atts ) ); 
     
     $html = "";
     
         $html .= '<div class="col-md-4">
                    <div class="bs_title">'.$title.'</div>
                    <div class="bs_content">'.do_shortcode($content).'</div>
                    <div class="bs_content"><a class="btn btn-lp-primary" href="'.$btn_link.'">'.$btn_title.'</a></div>
            </div>';
     
     return $html;
 }
 add_shortcode(  'lp_landing_blurbsection_item', 'lp_landing_blurbsection_item_sc'  ); 
?>
