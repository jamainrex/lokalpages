<?php
// Lokalpages Custom Post Types Initialization
 
function lp_custom_post_type() {
    if ( ! class_exists( 'Super_CPT' ) )
        return;
    
    global $aitThemeOptions; 
        
    $listing_meta = new Super_Custom_Post_Meta( 'ait-dir-item' );
    
    
    $_enRatingOpt = array();
    for($i = 1; $i <= 5; $i++){
        $nameEnable = 'rating'.$i.'Enable';
        if(isset($aitThemeOptions->rating->$nameEnable)){
            $nameTitle = 'rating'.$i.'Title';
            $nameID = strtolower( $aitThemeOptions->rating->$nameTitle );
            $_enRatingOpt[$nameEnable] = $aitThemeOptions->rating->$nameTitle; 
                        }
                    }
    
    
    $listing_meta->add_meta_box( array(
        'id' => 'rating-options',
        'context' => 'normal',
        'priority' => 'high',
        'fields' => array( 
            'lp_rating_disable' => array( 'label' => __( 'Disable Ratings' ), 'type' => 'checkbox', 'options' => $_enRatingOpt, 'data-vp_desc' => __( 'Check to disable rating.','lokalpages') ) )
    ) );
    
    $listing_meta->add_meta_box( array(
        'id' => 'facebook-page',
        'context' => 'normal',
        'priority' => 'high',
        'fields' => array( 
            'lp_listing_facebook_page_url' => array( 'label' => __( 'Business Facebook Page','lokalpages'), 'type' => 'text', 'data-zp_desc' => __( 'Place here your Facebook page ID. Example: [lokalpages] - https://www.facebook.com/lokalpages.','lokalpages') )        
        ) ) );
    
    //$_enServOpt = array('n'=>'No','y'=>'Yes');
    $_enServOpt = array(/*'n'=>'No',*/'y'=>'Disable');
    $_enServOpt2 = array(/*'n'=>'No',*/'y'=>'Enable');
    $_enServOpt3 = array(/*'n'=>'No',*/'y'=>'Show');
    $listing_meta->add_meta_box( array(
        'id' => 'listing-service-options',
        'context' => 'normal',
        'priority' => 'high',
        'fields' => array( 
            '_serv_content_show' => array( 'label' => __( 'Business Information' ), 'type' => 'checkbox', 'options' => $_enServOpt3, 'data-vp_desc' => __( 'Check to show Business Information.','lokalpages') ), 
            '_serv_revrate_disable' => array( 'label' => __( 'Review & Ratings' ), 'type' => 'checkbox', 'options' => $_enServOpt, 'data-vp_desc' => __( 'Check to disable review & ratings.','lokalpages') ), 
            //'_serv_gpsmap_disable' => array( 'label' => __( 'GPS & Map' ), 'type' => 'checkbox', 'options' => $_enServOpt, 'data-vp_desc' => __( 'Check to disable GPS & Map.','lokalpages') ), 
            '_serv_map_disable' => array( 'label' => __( 'Map' ), 'type' => 'checkbox', 'options' => $_enServOpt, 'data-vp_desc' => __( 'Check to disable Map.','lokalpages') ), 
            '_serv_gpsmap_disable' => array( 'label' => __( 'GPS' ), 'type' => 'checkbox', 'options' => $_enServOpt, 'data-vp_desc' => __( 'Check to disable GPS.','lokalpages') ),
            '_serv_related_disable' => array( 'label' => __( 'Related' ), 'type' => 'checkbox', 'options' => $_enServOpt, 'data-vp_desc' => __( 'Check to disable listing to show on Related posts.','lokalpages') ), 
            '_serv_fullwidth_enable' => array( 'label' => __( 'Fullwidth' ), 'type' => 'checkbox', 'options' => $_enServOpt2, 'data-vp_desc' => __( 'Check to enable Fullwidth layout for this listing.','lokalpages') ), 
            '_serv_footer_related_listing_disable' => array( 'label' => __( 'Footer Related Listing' ), 'type' => 'checkbox', 'options' => $_enServOpt, 'data-vp_desc' => __( 'Check to disable Related Listing on Footer Widget.','lokalpages') ) 
            )
    ) );
    
    $page_meta = new Super_Custom_Post_Meta( 'page' );
    $page_meta->add_meta_box( array(

        'id' => 'page-background',
        'context' => 'normal',
        'priority' => 'high',
        'fields' => array(

            'lp_fullwidth_page_background' => array( 'label' => __( 'Content Background Image for Fullwidth (98% width)','lokalpages'), 'type' => 'text', 'data-zp_desc' => __( 'Place here the image url.','lokalpages') ),        

        )

    ) );
}
add_action( 'after_setup_theme', 'lp_custom_post_type' );
?>
