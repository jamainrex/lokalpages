jQuery(function($) {
 
 $('.delete_listing').click(function(e){
        e.preventDefault();
        var el = $(this); 
        // set data
        var data = {
                action: 'lp_delete_subission',
                bsid: $(this).attr('lang')
            };
            // make the post request and process the response
            $.post(ajaxurl, data, function(response) {
                var ptr = el.parent().parent();
                ptr.css("background-color","#FF3700");
                ptr.fadeOut(400, function(){
                    ptr.remove();
                });
                //console.log(response);
            });
        
    });
    
 $('.upload_listing').click(function(e){
        e.preventDefault();
        var el = $(this); 
        // set data
        var data = {
                action: 'lp_upload_subission',
                bsid: $(this).attr('lang')
            };
            // make the post request and process the response
            $.post(ajaxurl, data, function(response) {
                var ptr = el.parent().parent();
                ptr.css("background-color","#FF3700");
                ptr.fadeOut(400, function(){
                    ptr.remove();
                });
                console.log(response);
            });
        
    });
    
    $('.re_upload_listing').click(function(e){
        e.preventDefault();
        var el = $(this); 
        // set data
        var data = {
                action: 'lp_reupload_subission',
                bsid: $(this).attr('lang')
            };
            // make the post request and process the response
            $.post(ajaxurl, data, function(response) {
                /*var ptr = el.parent().parent();
                ptr.css("background-color","#FF3700");
                ptr.fadeOut(400, function(){
                    ptr.remove();
                });
                console.log(response);*/
            });
        
    });
    
});