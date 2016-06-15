// animate header
function header_animate(){
    var scrollPosition = jQuery(window).scrollTop();
    
    if( scrollPosition >= 120 ){
        // Header
        jQuery('#directory-search').addClass('dir-header-animate');
        
        // Logo
        jQuery('#lpc-logo img').addClass('dir-header-logo-animate');
        
        // Inputs
        jQuery('#dir-search-button').addClass('dir-input-animate');
        jQuery('#dir-search-inputs').addClass('dir-input-animate');
    }else{
        // Header
        jQuery('#directory-search').removeClass('dir-header-animate'); 
        
        // Logo
        jQuery('#lpc-logo img').removeClass('dir-header-logo-animate');
        
        // Inputs
        jQuery('#dir-search-button').removeClass('dir-input-animate');
        jQuery('#dir-search-inputs').removeClass('dir-input-animate');
        
        
    }
}

jQuery(document).ready(function() { 


/* ========== PARALLAX BACKGROUND ========== */

    jQuery(window).on('scroll', function(e) {
        
        // enable header effect when scrolled
        header_animate();
        
    });
    
});