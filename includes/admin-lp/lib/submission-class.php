<?php
  //** Directory_Core Class
if (! class_exists('LPListingSubmission')):
class LPListingSubmission {
    
    static function sanitize_listings( $params ){
        
        if( $params['id'] > 62 ) return self::sanitize_listings_new($params);
        
        $_tempInfo = get_object_vars($params['info']);
        $_tempFiles = get_object_vars($params['files']);
        // Sanitize data
        $_sanitizeData = array();
        
        //$listing_status = 'publish';
        $listing_status = 'pending';   
        
        // Post Data
        $contact = implode( " / ", array( $_tempInfo['tel-637'], $_tempInfo['text-730'] ) );
        $_sanitizeData = array(
                            'bizName' => wp_strip_all_tags( $_tempInfo['text-99'] ),
                            'bizDesc' => $_tempInfo['your-message'],
                            'status' => $listing_status,
                            'meta' => array(
                                    'address' => $_tempInfo['text-94'],   
                                    'telephone' => $contact,  
                                    'email' => $_tempInfo['your-email'],  
                                    'web' => $_tempInfo['url-442']
                                ),
                            ); 
                            
        /*$address_len = strlen($_sanitizeData['meta']['address']);
        $address_val = $_sanitizeData['meta']['address']['val'];
        $contact_len = strlen($_sanitizeData['meta']['phone']['len']);
        $contact_val = $_sanitizeData['meta']['phone']['val'];
        $email_len = strlen($_sanitizeData['meta']['email']['len']);
        $email_val = $_sanitizeData['meta']['email']['val'];
        $web_len = strlen($_sanitizeData['meta']['web']['len']);
        $web_val = $_sanitizeData['meta']['web']['val'];*/
        
        //$lp_meta = 'a:19:{s:7:"address";s:'.$address_len.':"'.$address_val.'";s:11:"gpsLatitude";s:1:"0";s:12:"gpsLongitude";s:1:"0";s:18:"streetViewLatitude";s:0:"";s:19:"streetViewLongitude";s:0:"";s:17:"streetViewHeading";s:1:"0";s:15:"streetViewPitch";s:1:"0";s:14:"streetViewZoom";s:1:"0";s:9:"telephone";s:'.$contact_len.':"'.$contact_val.'";s:5:"email";s:'.$email_len.':"'.$email_val.'";s:3:"web";s:'.$web_len.':"'.$web_val.'";s:11:"hoursMonday";s:0:"";s:12:"hoursTuesday";s:0:"";s:14:"hoursWednesday";s:0:"";s:13:"hoursThursday";s:0:"";s:11:"hoursFriday";s:0:"";s:13:"hoursSaturday";s:0:"";s:11:"hoursSunday";s:0:"";s:18:"alternativeContent";s:0:"";}';
        //$_sanitizeData['lp_meta'] = $lp_meta;
                            
        // Categories
        $cat_ids = array();
        foreach( $_tempInfo['menu-715'] as $_tempCat){
            if( $isterm = lp_get_category_by_name($_tempCat) ){
                $cat_ids[] = $isterm['term_id'];
            }
        }
        // Other Category
        if( $isterm = lp_get_category_by_name( $_tempInfo['text-842'] ) ){
                $cat_ids[] = $isterm['term_id'];
            }
        
        $_sanitizeData['categories'] = $cat_ids;
        
        return $_sanitizeData;
    }
    
    static function sanitize_listings_new( $params ){
        $_tempInfo = get_object_vars($params['info']);
        $_tempFiles = get_object_vars($params['files']);
        // Sanitize data
        $_sanitizeData = array();
        
        //$listing_status = 'publish';
        $listing_status = 'pending';
        // Post Data
        $contact = implode( " / ", array( $_tempInfo['bizphone'], $_tempInfo['bizmobile'] ) );
        $_sanitizeData = array(
                            'bizName' => wp_strip_all_tags( $_tempInfo['bizname'] ),
                            'bizDesc' => $_tempInfo['desc'],
                            'status' => $listing_status,
                            'meta' => array(
                                    'address' => $_tempInfo['bizaddress'],   
                                    'telephone' => $contact,  
                                    'email' => $_tempInfo['your-email'],  
                                    'emailContactOwner' => $_tempInfo['bizemail'],  
                                    'web' => $_tempInfo['bizweb'],
                                    'hoursMonday' => $_tempInfo['ohmon'],
                                    'hoursTuesday' => $_tempInfo['ohtue'],
                                    'hoursWednesday' => $_tempInfo['ohwed'],
                                    'hoursThursday' => $_tempInfo['ohthurs'],
                                    'hoursFriday' => $_tempInfo['ohfri'],
                                    'hoursSaturday' => $_tempInfo['ohsat'],
                                    'hoursSunday' => $_tempInfo['ohsun'],
                                ),
                            );
                            
        // Categories
        $cat_ids = array();
        foreach( $_tempInfo['wpcf7listingCat'] as $_tempCat){
            $cat_ids[] = $_tempCat;
        }
        
        // Other Category
        if( $isterm = lp_get_category_by_name( $_tempInfo['othercat'] ) ){
                $cat_ids[] = $isterm['term_id'];
            }
        
        $_sanitizeData['categories'] = $cat_ids;
        
        // Locations
        $loc_ids = array();
        foreach( $_tempInfo['wpcf7listingLoc'] as $_tempLoc){
            $loc_ids[] = $_tempLoc;
        }
        $_sanitizeData['locations'] = $loc_ids;
        
        // Uploaded Image
        $_sanitizeData['bizImg'] = $_tempFiles['photo1st'];
         
        return $_sanitizeData;
    }
     /**
    * Insert Changes in separate Table.
    *
    **/
    static function insert_to_posts( $params ) {
        global $wpdb;
        
        /* Construct args for the new post */
        $post = array(
                      'post_content'   => $params['bizDesc'], // The full text of the post.
                      'post_name'      => $params['bizName'], // The name (slug) for your post
                      'post_title'     => $params['bizName'], // The title of your post.
                      'post_status'    => $listing_status, // Default 'draft'.
                      'post_type'      => 'ait-dir-item', // Default 'post'.
                      'post_date'      => date( 'Y-m-d H:i:s' ), // The time post was made.
                      'post_date_gmt'  => date( 'Y-m-d H:i:s' ), // The time post was made, in GMT.
                      'comment_status' => 'closed' // Default is the option 'default_comment_status', or 'closed'
                    );  
        
        //now you can use $post_id within add_post_meta or update_post_meta
        $post_id = wp_insert_post( $post, true );
        
        return $post_id;
    }
    
    static function update_post_metadata( $post_id, $meta_value = '', $meta_key = '_ait-dir-item' ){
        // Add or Update the meta field in the database.
        if ( ! update_post_meta($post_id, $meta_key, $meta_value) ) { 
            return add_post_meta($post_id, $meta_key, $meta_value );    
        }else return true;
    }
    
}
endif;
?>
