jQuery.noConflict(); 
(function() {
 
  // store the slider in a local variable
  var $window = jQuery(window),
      flexslider;
 
  // tiny helper function to add breakpoints
  function getGridSize() {
    return (window.innerWidth < 600) ? 2 :
           (window.innerWidth < 900) ? 3 : 4;
  }
 
  jQuery(function() {
    SyntaxHighlighter.all();
  });
 
  jQuery(document).ready(function() {
    //alert('test');
    jQuery('#lp-landing-carousel').flexslider({                                               
      animation: "slide",
      animationLoop: true,
      itemWidth: 210,
      itemMargin: 5,
      controlNav: false,
      minItems: getGridSize(), // use function to pull in initial value
      maxItems: getGridSize(), // use function to pull in initial value
          start: function(slider){
              jQuery('#landing-carousel').removeClass('lp-loading');
            }
    });
  });
 
  // check grid size on resize event
  /*$window.resize(function() {
    var gridSize = getGridSize();
 
    flexslider.vars.minItems = gridSize;
    flexslider.vars.maxItems = gridSize;
  });*/
}());
   