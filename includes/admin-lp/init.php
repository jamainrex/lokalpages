<?php

/**
* Nomination Admin
*/
add_action( 'admin_enqueue_scripts', 'lp_admin_scripts' ); 
function lp_admin_scripts(){
    if( is_admin() ){
        //CSS
        wp_enqueue_style(  'lp_admin_css', get_stylesheet_directory_uri(   ).'/includes/admin-lp/admin.css'  );
        //JS
        wp_register_script( 'lp_admin_js', get_stylesheet_directory_uri() .'/includes/admin-lp/admin.js', array('jquery') );
        wp_enqueue_script('lp_admin_js');
        
    }
}

add_action( 'admin_menu', 'lp_admin_menu' );
function lp_admin_menu(){ 
    add_submenu_page('edit.php?post_type=ait-dir-item','Listing Submission','Submission','manage_options','listing-submission','lp_listing_submission_cb');
    add_submenu_page('edit.php?post_type=ait-dir-item','Listing Submission','Submission(New)','manage_options','listing-submission-new','lp_listing_submission_cb_new');
    
    add_submenu_page('ait-admin','Lokalpages Settings','LP Settings','manage_options','lp-general-settings','lp_general_settings');
}

function lp_genSettings_process( $post ){
    
    $lplandingtxtheader = stripslashes_deep( $post['lplandingtxtheader'] );
    if( $opt_value = get_option('lp_landing_header_text_output2') ){
        update_option( 'lp_landing_header_text_output2', $lplandingtxtheader );
    }else{
        add_option('lp_landing_header_text_output2', $lplandingtxtheader, '','yes');
    }      
    
    //echo $post['lplandingbg'];
    if( $opt_value = get_option('lp_landing_bg_image_2') ){
        update_option( 'lp_landing_bg_image_2', $post['lplandingbg'] );
    }else{
        add_option('lp_landing_bg_image_2',$post['lplandingbg'], '','yes');
    }
    
}

function lp_genSettings_afterlistingoutput( $post ){
    
    $listingfooteroutput = stripslashes_deep( $post['listingfooteroutput'] );
    
    if( $opt_value = get_option('lp_after_listing_output') ){
        update_option( 'lp_after_listing_output', $listingfooteroutput );
    }else{
        add_option('lp_after_listing_output', $listingfooteroutput, '','yes');
    }
    
}

function lp_general_settings(){
    if (!defined('ABSPATH')) die('No direct access allowed!');
    
    if( isset( $_POST['update-lp-landing-bg-image'] ) ) $resp = lp_genSettings_process( $_POST );  
    
    if( isset( $_POST['update-after-listing-output'] ) ) $resp = lp_genSettings_afterlistingoutput( $_POST );  
    
    $lplandingbg = get_option('lp_landing_bg_image_2');
    $listingfooteroutput = get_option('lp_after_listing_output');
    $lplandingtxtheader = get_option('lp_landing_header_text_output2');
    
    require_once(  get_stylesheet_directory(  )  .'/includes/admin-lp/general-settings.html' );
}

function lp_listing_submission_cb(){
    $tab = isset( $_REQUEST['tab'] ) ? $_REQUEST['tab'] : 'new';
    
    require_once(  get_stylesheet_directory(  )  .'/includes/admin-lp/admin-navigation.php' );
    
    if( $tab == 'new' ){
        require_once(  get_stylesheet_directory(  )  .'/includes/admin-lp/admin.html' ); 
    }elseif( $tab == 'uploaded' ){
        require_once(  get_stylesheet_directory(  )  .'/includes/admin-lp/admin-uploaded.html' );
    }elseif( $tab == 'deleted' ){
        require_once(  get_stylesheet_directory(  )  .'/includes/admin-lp/admin-deleted.html' );
    }
       
}

function lp_listing_submission_cb_new(){
    $tab = isset( $_REQUEST['tab'] ) ? $_REQUEST['tab'] : 'new';
    
    require_once(  get_stylesheet_directory(  )  .'/includes/admin-lp/admin-navigation-new.php' );
    
    if( $tab == 'new' ){
        require_once(  get_stylesheet_directory(  )  .'/includes/admin-lp/admin-new.html' ); 
    }elseif( $tab == 'uploaded' ){
        require_once(  get_stylesheet_directory(  )  .'/includes/admin-lp/admin-uploaded-new.html' );
    }elseif( $tab == 'deleted' ){
        require_once(  get_stylesheet_directory(  )  .'/includes/admin-lp/admin-deleted.html' );
    }
       
}

function lp_get_category_by_name( $name ){
    //return get_term_by('name', $name, 'ait-dir-item-category');
    return $term_id = term_exists( $name, 'ait-dir-item-category' );
}

function lp_set_post_terms( $post_id, $term, $tax = 'ait-dir-item-category' ){
    return wp_set_post_terms( $post_id, $term, $tax );
}

/** Admin delete / upload */
function lp_delete_subission( $bsid ){
        global $wpdb;
        $vtable = "lpd_biz_submitted";
        // Update sumbission
            $wpdb->update( 
            $vtable, 
            array( 'deleted' => date("Y-m-d h:i:s") ),
            array( 'bsid' => $bsid )
            );
        return true;    
    }

function ajax_lp_delete_subission() {
        if ( !current_user_can( 'manage_options' ) ) die(-1);
        $bsid = (int) $_POST['bsid'];
        
        $response = lp_delete_subission( $bsid );
        
        // response output
        header( "Content-Type: application/json" );
        echo json_encode( array('status'=>$response) );
        die();
    }
add_action( 'wp_ajax_lp_delete_subission', 'ajax_lp_delete_subission' );

function lp_media_upload_sideline( $desc, $post_id, $url ){
    $url = $url;
    $tmp = download_url( $url );
    $post_id = $post_id;
    $desc = $desc;
    $file_array = array();

    // Set variables for storage
    // fix file filename for query strings
    preg_match('/[^\?]+\.(jpg|jpe|jpeg|gif|png)/i', $url, $matches);
    $file_array['name'] = basename($matches[0]);
    $file_array['tmp_name'] = $tmp;

    // If error storing temporarily, unlink
    if ( is_wp_error( $tmp ) ) {
        @unlink($file_array['tmp_name']);
        $file_array['tmp_name'] = '';
    }

    // do the validation and storage stuff
    $id = media_handle_sideload( $file_array, $post_id, $desc );
    
    // set featured image
    set_post_thumbnail( $post_id, $id ); 

    // If error storing permanently, unlink
    if ( is_wp_error($id) ) {
        @unlink($file_array['tmp_name']);
        return $id;
    }

    $src = wp_get_attachment_url( $id );
}

function lp_upload_subission( $bsid ){
        global $wpdb;
        $vtable = "lpd_biz_submitted";
        $lp_bizinfo = array();
        $lp_post_id = 0;
        $resp = $wpdb->get_results("select * from $vtable where bsid=$bsid");  
        if( isset( $resp[0] ) ){
            $lp_bizinfo['id'] = $resp[0]->bsid;
            $lp_bizinfo['info'] = json_decode( $resp[0]->info );
            $lp_bizinfo['files'] = json_decode( $resp[0]->files );
            $lp_post_id = $resp[0]->post_id;
        }
        // sanitize data
        $_lpInfo = LPListingSubmission::sanitize_listings($lp_bizinfo);
        
        if( $lp_post_id == 0 ){
            // Insert posts
            $lp_post_id = LPListingSubmission::insert_to_posts( $_lpInfo );
        }
        
        // Update metadata
       //LPListingSubmission::update_post_metadata( $lp_post_id, $_lpInfo['lp_meta'] );
       LPListingSubmission::update_post_metadata( $lp_post_id, $_lpInfo['meta'] );
       
       // Update Post Category
        if( isset( $_lpInfo['categories'][0] ) ){
            foreach( $_lpInfo['categories'] as $term ){
                lp_set_post_terms( $lp_post_id, array( (int) $term ) );    
            }
        }
        
        // Update Post Location
        if( isset( $_lpInfo['locations'][0] ) ){
            foreach( $_lpInfo['locations'] as $term ){
                lp_set_post_terms( $lp_post_id, array( (int) $term ), 'ait-dir-item-location' );    
            }
        }
        
        // insert attachment
        $img_url = 'http://lokalpages.com/wp-content/uploads/lp_submission/'.$_lpInfo['bizImg']->name;
        lp_media_upload_sideline( $_lpInfo['bizName'], $lp_post_id, $img_url );
        
        $lp_bizinfo['post_id'] = $lp_post_id;
        
         // Update sumbission
            $wpdb->update( 
                $vtable, 
                array( 'uploaded' => date("Y-m-d h:i:s"), 'post_id' => $lp_post_id ),
                array( 'bsid' => $bsid )
            );
            
        //echo '<pre>'.print_r($lp_bizinfo,true).'</pre>'; 
        return $lp_bizinfo;    
    }
    
function ajax_lp_upload_subission() {
        if ( !current_user_can( 'manage_options' ) ) die(-1);
        $bsid = (int) $_POST['bsid'];
        
        $response = lp_upload_subission( $bsid );
        
        // response output
        header( "Content-Type: application/json" );
        //echo json_encode( array('status'=>$response) );
        echo json_encode( $response );
        exit();
    }
add_action( 'wp_ajax_lp_upload_subission', 'ajax_lp_upload_subission' );
    
function lp_reupload_subission( $bsid ){
        global $wpdb;
        $vtable = "lpd_biz_submitted";
        $lp_bizinfo = array();
        $lp_post_id = 0;
        $resp = $wpdb->get_results("select * from $vtable where bsid=$bsid");  
        if( isset( $resp[0] ) ){
            $lp_bizinfo['id'] = $resp[0]->bsid;
            $lp_bizinfo['info'] = json_decode( $resp[0]->info );
            $lp_bizinfo['files'] = json_decode( $resp[0]->files );
            $lp_post_id = $resp[0]->post_id;
        }
        // sanitize data
        $_lpInfo = LPListingSubmission::sanitize_listings( $lp_bizinfo );
        
        if( $lp_post_id == 0 ){
            // Insert posts
            $lp_post_id = LPListingSubmission::insert_to_posts( $_lpInfo );
        }
        
        // Update metadata
       LPListingSubmission::update_post_metadata( $lp_post_id, $_lpInfo['meta'] );
       
       //echo '<pre>'.print_r($_lpInfo['categories'],true).'</pre>';
       
       // Update Post Category
        if( isset( $_lpInfo['categories'][0] ) ){
            foreach( $_lpInfo['categories'] as $term ){
                lp_set_post_terms( $lp_post_id, array( (int) $term ) );    
            }
        }
        
        // Update Post Location
        if( isset( $_lpInfo['locations'][0] ) ){
            foreach( $_lpInfo['locations'] as $term ){
                lp_set_post_terms( $lp_post_id, array( (int) $term ), 'ait-dir-item-location' );    
            }
        }
        
        // insert attachment
        $img_url = 'http://lokalpages.com/wp-content/uploads/lp_submission/'.$_lpInfo['bizImg']->name;
        lp_media_upload_sideline( $_lpInfo['bizName'], $lp_post_id, $img_url );
        
        $lp_bizinfo['post_id'] = $lp_post_id;
        
         // Update sumbission
            $wpdb->update( 
                $vtable, 
                array( 'uploaded' => date("Y-m-d h:i:s") ),
                array( 'bsid' => $bsid )
            );
            
        //echo '<pre>'.print_r($_lpInfo,true).'</pre>'; 
        return $lp_bizinfo;    
    }

function ajax_lp_reupload_subission() {
        if ( !current_user_can( 'manage_options' ) ) die(-1);
        $bsid = (int) $_POST['bsid'];
        
        $response = lp_reupload_subission( $bsid );
        
        // response output
        header( "Content-Type: application/json" );
        //echo json_encode( array('status'=>$response) );
        echo json_encode( $response );
        exit();
    }
add_action( 'wp_ajax_lp_reupload_subission', 'ajax_lp_reupload_subission' );
?>
