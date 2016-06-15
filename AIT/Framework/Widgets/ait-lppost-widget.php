<?php
define( 'LP_CRP_LOCAL_NAME', 'lp_crp' );

function ext_lp_rand_ald_crp( $args = array() ) {
    global $wpdb, $post, $single, $crp_settings;

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
        $output = ( $is_widget ) ? get_post_meta( $post->ID, 'crp_random_posts_widget', true ) : get_post_meta( $post->ID, 'ext_crp_random_posts_widget', true );
        if ( $output ) {
            return $output;
        }
    }

    $exclude_categories = explode( ',', $exclude_categories );

    $rel_attribute = ( $link_nofollow ) ? ' rel="nofollow" ' : ' ';
    $target_attribute = ( $link_new_window ) ? ' target="_blank" ' : ' ';

    // Retrieve the list of posts
    $results = ext_lp_get_crp_random_posts_id( array_merge( $args, array(
        'postid' => $post->ID,
        'strict_limit' => TRUE,
    ) ) );

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
            $output .= apply_filters( 'lp_rand_crp_heading_title', $title );
        }

        /**
         * Filter the opening tag of the related posts list
         *
         * @since    1.9
         *
         * @param    string    $before_list    Opening tag set in the Settings Page
         */
        $output .= apply_filters( 'lp_rand_crp_before_list', $before_list );

        foreach ( $results as $result ) {

            /**
             * Filter the post ID for each result. Allows a custom function to hook in and change the ID if needed.
             *
             * @since    1.9
             *
             * @param    int    $result->ID    ID of the post
             */
            $resultid = apply_filters( 'lp_rand_crp_post_id', $result->ID );

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
            $resultid = apply_filters( 'lp_rand_crp_post_cat_id', $result->ID );

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
                $output .= apply_filters( 'lp_rand_crp_before_list_item', $before_list_item, $result );    // Pass the post object to the filter

                $title = crp_max_formatted_content( get_the_title( $result->ID ), $title_length );    // Get the post title and crop it if needed

                /**
                 * Filter the title of each list item.
                 *
                 * @since    1.9
                 *
                 * @param    string    $title    Title of the post.
                 * @param    object    $result    Object of the current post result
                 */
                $title = apply_filters( 'lp_rand_crp_title', $title, $result );

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
                    $author_name = apply_filters( 'lp_rand_crp_author_name', $author_name, $author_info );

                    $crp_author .= '<span class="crp_author"> ' . __( ' by ', CRP_LOCAL_NAME ).'<a href="' . $author_link . '">' . $author_name . '</a></span> ';

                    /**
                     * Filter the text with the author details.
                     *
                     * @since    2.0.0
                     *
                     * @param    string    $crp_author    Formatted string with author details and link
                     * @param    object    $author_info    WP_User object of the post author
                     */
                    $crp_author = apply_filters( 'lp_rand_crp_author', $crp_author, $author_info);

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
                $output .= apply_filters( 'lp_rand_crp_after_list_item', $after_list_item, $result );
            }
            if ( $loop_counter == $limit ) break;    // End loop when related posts limit is reached
        } //end of foreach loop
        if ( $show_credit ) {

            /** This filter is documented in contextual-related-posts.php */
            $output .= apply_filters( 'lp_rand_crp_before_list_item', $before_list_item, $result );    // Pass the post object to the filter

            $output .= sprintf( __( 'Powered by <a href="%s" rel="nofollow">Contextual Related Posts</a>', CRP_LOCAL_NAME ), esc_url( 'http://ajaydsouza.com/wordpress/plugins/contextual-related-posts/' ) );

            /** This filter is documented in contextual-related-posts.php */
            $output .= apply_filters( 'lp_rand_crp_after_list_item', $after_list_item, $result );

        }

        /**
         * Filter the closing tag of the related posts list
         *
         * @since    1.9
         *
         * @param    string    $after_list    Closing tag set in the Settings Page
         */
        $output .= apply_filters( 'lp_rand_crp_after_list', $after_list );

        $clearfix = '<div style="clear:both"></div>';

        /**
         * Filter the clearfix div tag. This is included after the closing tag to clear any miscellaneous floating elements;
         *
         * @since    2.0.0
         *
         * @param    string    $clearfix    Contains: <div style="clear:both"></div>
         */
        $output .= apply_filters( 'lp_rand_crp_clearfix', $clearfix );

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
            update_post_meta( $post->ID, 'ext_crp_random_posts_widget', $output, '' );
        } else {
            update_post_meta( $post->ID, 'ext_crp_random_posts_widget', $output, '' );
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
    return apply_filters( 'ext_lp_rand_ald_crp', $output, $args );
}

function ext_lp_get_crp_random_posts_id( $args = array() ) {
    global $wpdb, $post, $single, $crp_settings;

    // Initialise some variables
    $fields = '';
    $where = '';
    $join = '';
    $groupby = '';
    $orderby = 'RAND()';
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
        $fields = " $wpdb->posts.ID ";

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
     //echo '<pre>'.print_r($results,true).'</pre>';
    return apply_filters( 'ext_lp_get_crp_random_posts_id', $results, $post->ID );
}
/**
 * Creates widget with posts
 */

class LP_Posts_Widget extends WP_Widget
{

/**
 * Widget constructor
 *
 * @desc sets default options and controls for widget
 */
    function LP_Posts_Widget () {
        /* Widget settings */
        $widget_ops = array (
            'classname' => 'widget_lp_posts',
            'description' => __('Random Listing Display', 'ait')
        );

        /* Create the widget */
        $this->WP_Widget('lp-posts-widget', __('LP Random Listing', 'ait'), $widget_ops);
    }

/**
 * Displaying the widget
 *
 * Handle the display of the widget
 * @param array
 * @param array
 */
    function widget ( $args, $instance ) {
        global $wpdb, $post, $wp_registered_widgets, $wp_registered_sidebars;

        extract( $args, EXTR_SKIP );

        global $crp_settings;
        
        //echo get_sidebar();
    
        parse_str( $crp_settings['exclude_on_post_types'], $exclude_on_post_types );    // Save post types in $exclude_on_post_types variable
        if ( in_array( $post->post_type, $exclude_on_post_types ) ) return 0;    // Exit without adding related posts

        $exclude_on_post_ids = explode( ',', $crp_settings['exclude_on_post_ids'] );

        if ( ( ( is_single() ) && ( ! is_single( $exclude_on_post_ids ) ) ) || ( ( is_page() ) && ( ! is_page( $exclude_on_post_ids ) ) ) ) {
            // Check if footer related listing is not disabled
            $single_listing_related_listing_disabled = get_post_meta( $post->ID, '_serv_footer_related_listing_disable', true );
        
            if( trim($instance['title']) == 'Business nearby' && isset( $single_listing_related_listing_disabled ) && $single_listing_related_listing_disabled == 'y' ){
                $output = $before_widget;
                //$output .= $before_title . $title . $after_title;
                /*$output .= ext_lp_rand_ald_crp(array(
                    'is_widget' => 1,
                    'limit' => $limit,
                    'show_excerpt' => $instance['show_excerpt'],
                    'show_author' => $instance['show_author'],
                    'show_date' => $instance['show_date'],
                    'post_thumb_op' => $instance['post_thumb_op'],
                    'thumb_height' => $instance['thumb_height'],
                    'thumb_width' => $instance['thumb_width'],
                ));*/

                $output .= $after_widget;
                //$output .= '<pre>'.print_r($instance,true).'</pre>';
                echo $output;
            }else {
                $title = apply_filters('widget_title', empty($instance['title']) ? strip_tags(str_replace("%postname%", $post->post_title, $crp_settings['title'])) : $instance['title']);
                $limit = $instance['limit'];
                if (empty($limit)) $limit = $crp_settings['limit'];

                $output = $before_widget;
                $output .= $before_title . $title . $after_title;
                $output .= ext_lp_rand_ald_crp(array(
                    'is_widget' => 1,
                    'limit' => $limit,
                    'show_excerpt' => $instance['show_excerpt'],
                    'show_author' => $instance['show_author'],
                    'show_date' => $instance['show_date'],
                    'post_thumb_op' => $instance['post_thumb_op'],
                    'thumb_height' => $instance['thumb_height'],
                    'thumb_width' => $instance['thumb_width'],
                ));

                $output .= $after_widget;
                //$output .= '<pre>'.print_r($instance,true).'</pre>';
                echo $output;
            }
        }
    }

/**
 * Update and save widget
 *
 * @param array $new_instance
 * @param array $old_instance
 * @return array New widget values
 */
    function update ( $new_instance, $old_instance ) {
        $instance = $old_instance;
        $instance['title'] = strip_tags( $new_instance['title'] );
        $instance['limit'] = $new_instance['limit'];
        $instance['show_excerpt'] = $new_instance['show_excerpt'];
        $instance['show_author'] = $new_instance['show_author'];
        $instance['show_date'] = $new_instance['show_date'];
        $instance['post_thumb_op'] = $new_instance['post_thumb_op'];
        $instance['thumb_height'] = $new_instance['thumb_height'];
        $instance['thumb_width'] = $new_instance['thumb_width'];
        delete_post_meta_by_key( 'ext_crp_random_posts_widget' ); // Delete the cache
        return $instance;
    }

/**
 * Creates widget controls or settings
 *
 * @param array Return widget options form
 */
    function form ( $instance ) {
        $instance = wp_parse_args( (array) $instance, array(
            'title' => '',
            'limit' => '',
            'show_excerpt' => '',
            'show_author' => '',
            'show_date' => '',
            'post_thumb_op' => '',
            'thumb_height' => '',
            'thumb_width' => ''
        ) );
    ?>
        <p>
            <label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php echo __( 'Title', 'ait' ); ?>:</label>
            <input type="text" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" value="<?php echo $instance['title']; ?>"class="widefat" style="width:100%;" />
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'limit' ); ?>">
            <?php _e( 'No. of posts', LP_CRP_LOCAL_NAME ); ?>: <input class="widefat" id="<?php echo $this->get_field_id( 'limit' ); ?>" name="<?php echo $this->get_field_name( 'limit' ); ?>" type="text" value="<?php echo esc_attr( $limit ); ?>" />
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'show_excerpt' ); ?>">
            <input id="<?php echo $this->get_field_id( 'show_excerpt' ); ?>" name="<?php echo $this->get_field_name( 'show_excerpt' ); ?>" type="checkbox" <?php if ( $show_excerpt ) echo 'checked="checked"' ?> /> <?php _e( ' Show excerpt?', LP_CRP_LOCAL_NAME ); ?>
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'show_author' ); ?>">
            <input id="<?php echo $this->get_field_id( 'show_author' ); ?>" name="<?php echo $this->get_field_name( 'show_author' ); ?>" type="checkbox" <?php if ( $show_author ) echo 'checked="checked"' ?> /> <?php _e( ' Show author?', LP_CRP_LOCAL_NAME ); ?>
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'show_date' ); ?>">
            <input id="<?php echo $this->get_field_id( 'show_date' ); ?>" name="<?php echo $this->get_field_name( 'show_date' ); ?>" type="checkbox" <?php if ( $show_date ) echo 'checked="checked"' ?> /> <?php _e( ' Show date?', LP_CRP_LOCAL_NAME ); ?>
            </label>
        </p>
        <p>
            <?php _e( 'Thumbnail options', LP_CRP_LOCAL_NAME ); ?>: <br />
            <select class="widefat" id="<?php echo $this->get_field_id( 'post_thumb_op' ); ?>" name="<?php echo $this->get_field_name( 'post_thumb_op' ); ?>">
              <option value="inline" <?php if ( 'inline' == $post_thumb_op ) echo 'selected="selected"' ?>><?php _e( 'Thumbnails inline, before title', LP_CRP_LOCAL_NAME ); ?></option>
              <option value="after" <?php if ( 'after' == $post_thumb_op ) echo 'selected="selected"' ?>><?php _e( 'Thumbnails inline, after title', LP_CRP_LOCAL_NAME ); ?></option>
              <option value="thumbs_only" <?php if ( 'thumbs_only' == $post_thumb_op ) echo 'selected="selected"' ?>><?php _e( 'Only thumbnails, no text', LP_CRP_LOCAL_NAME ); ?></option>
              <option value="text_only" <?php if ( 'text_only' == $post_thumb_op ) echo 'selected="selected"' ?>><?php _e( 'No thumbnails, only text.', LP_CRP_LOCAL_NAME ); ?></option>
            </select>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'thumb_height' ); ?>">
            <?php _e( 'Thumbnail height', LP_CRP_LOCAL_NAME ); ?>: <input class="widefat" id="<?php echo $this->get_field_id( 'thumb_height' ); ?>" name="<?php echo $this->get_field_name( 'thumb_height' ); ?>" type="text" value="<?php echo esc_attr( $thumb_height ); ?>" />
            </label>
        </p>
        <p>
            <label for="<?php echo $this->get_field_id( 'thumb_width' ); ?>">
            <?php _e( 'Thumbnail width', LP_CRP_LOCAL_NAME ); ?>: <input class="widefat" id="<?php echo $this->get_field_id( 'thumb_width' ); ?>" name="<?php echo $this->get_field_name( 'thumb_width' ); ?>" type="text" value="<?php echo esc_attr( $thumb_width ); ?>" />
            </label>
        </p>
        <?php
    }
}
register_widget( 'LP_Posts_Widget' );