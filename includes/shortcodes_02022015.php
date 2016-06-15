 <?php
 
 function lp_login_form_sc(){
   if ( is_user_logged_in() ){
            echo '<div class="widget_directory">';
            echo '<div class="logged">';
            global $wp_roles;
            $currUser = wp_get_current_user();
            //echo $instance['description_logout'];
            echo '<div class="profile-info clear">';
            echo '<div class="profile-avatar">'.get_avatar( $currUser->ID ).'</div>';
            echo '<div class="profile-name"><span>'.__('Username: ','ait').'</span>'.$currUser->user_nicename .'</div>';
            if(isset($currUser->roles[0])){
                echo '<div class="profile-role"><span>'.__('Account: ','ait').'</span>'.$wp_roles->role_names[$currUser->roles[0]] .'</div>';
            }

            echo '<a href="'.admin_url('edit.php?post_type=ait-dir-item').'" title="My Items" class="widgetlogin-button-myitems">'.__('My Items','ait').'</a>';
            echo '<a href="'.admin_url('edit.php?post_type=ait-rating').'" title="Ratings" class="widgetlogin-button-ratings">'.__('Ratings','ait').'</a>';
            echo '<a href="'.admin_url('profile.php').'" title="Account" class="widgetlogin-button-account">'.__('Account','ait').'</a>';

            echo '<a href="'.wp_logout_url(get_permalink()).'" title="Logout" class="widgetlogin-button-logout">'.__('Logout','ait').'</a>';
            echo '</div></div>';
            echo '</div>';
        } else {
            ?>
            <div class="not-logged">
            <div id="ait-login-tabs">
                <!-- login -->
                <div id="ait-dir-login-tab">
                <p><?php echo ""; ?></p>
                <?php wp_login_form( array( 'form_id' => 'ait-login-form-widget' ) ); ?>
                <div style="text-align: right"><a href="<?php echo site_url('/sign-up'); ?>"> Sign Up </a> | <a href="<?php echo site_url('/login/forgot-password'); ?>">Forgot password?</a></div>
                </div>
            </div>
            </div>
            <?php
        }
}   
add_shortcode('lp_login_form','lp_login_form_sc'); ?>

<?php function lp_forgotpassword_form_sc(){ ?>
            <div class="not-logged">
            <div id="ait-login-tabs">
                <!-- login -->
                <div id="ait-dir-login-tab">
                <p><?php echo ""; ?></p>
                <?php lp_forgotpassword_function( array( 'form_id' => 'ait-lostpassword-form-widget' ) ); ?>
                <div style="text-align: right"><a href="<?php echo site_url('/login'); ?>"> Log In </a> | <a href="<?php echo site_url('/sign-up'); ?>"> Sign Up </a></div>
                </div>
            </div>
            </div>
<?php }   
add_shortcode('lp_forgotpassword_form','lp_forgotpassword_form_sc'); ?>

<?php
    /**
 * Provides a simple login form for use anywhere within WordPress. By default, it echoes
 * the HTML immediately. Pass array('echo'=>false) to return the string instead.
 *
 * @since 3.0.0
 * @param array $args Configuration options to modify the form output.
 * @return string|null String when retrieving, null when displaying.
 */
function lp_forgotpassword_function( $args = array() ) {
    $defaults = array(
        'echo' => true,
        'redirect' => site_url('/login/forgot-password/') /*( is_ssl() ? 'https://' : 'http://' ) . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI']*/ . '?checkemail=confirm', // Default redirect is back to the current page
        'form_id' => 'lostpasswordform',
        'label_username' => __( 'Username or E-mail' ),
        'label_generate_in' => __( 'Get New Password' ),
        'id_username' => 'user_login',
        'id_submit' => 'wp-submit',
        'value_username' => ''
    );
    $args = wp_parse_args( $args, apply_filters( 'lostpassword_form_defaults', $defaults ) );
$confirm = ( $_REQUEST['checkemail'] == 'confirm' ? '<p class="alert" style="font-weight: bold;">Check your e-mail for the confirmation link.</p>' : '' );
$hd_p = $confirm . '<p class="message">' . __('Please enter your username or email address. You will receive a link to create a new password via email.') . '</p>';   
$form = $hd_p.'<form name="' . $args['form_id'] . '" id="' . $args['form_id'] . '" action="' . esc_url( site_url( 'wp-login.php?action=lostpassword', 'login_post' ) ) . '" method="post">
    <p class="login-username">
                <label style="display:inline;" for="' . esc_attr( $args['id_username'] ) . '">' . esc_html( $args['label_username'] ) . '</label>
                <input type="text" name="user_login" id="' . esc_attr( $args['id_username'] ) . '" class="input" value="' . esc_attr( $args['value_username'] ) . '" size="20" />
    </p>
    <p class="login-submit">
                <input type="submit" name="wp-submit" id="' . esc_attr( $args['id_submit'] ) . '" class="button-primary" value="' . esc_attr( $args['label_generate_in'] ) . '" />
                <input type="hidden" name="redirect_to" value="' . esc_url( $args['redirect'] ) . '" />
    </p>
        </form>';

    if ( $args['echo'] )
        echo $form;
    else
        return $form;
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
     }
     
     $html .= '<li><a href="'.$link.'"><img src="'.$img.'" /></a>'.$desc.'</li>';
     
     return $html;
 }
 add_shortcode(  'lp_landing_carousel_item', 'lp_landing_carousel_item_sc'  ); 

?>
