<?php
  
class LP_FB_MSG{
   
  protected $fbId; 
      
  public function __construct( $fbId )
  {
    self::init( $fbId );
  }  
  
  public function init()
  {
      $this->setId( $id );
      add_action( 'init', array( $this, 'render' ) );
      add_shortcode( 'lpFbMsg', array( $this, 'plugin' ) );
  }
  
  public function render(){
                
  }
  
  public function setId( $id )
  {
     $this->fbId = $id; 
  }
      
  private function plugin()
  {
      ob_start();
      ?>
      <script type="text/javascript">
      jQuery(document).ready(function(){
      jQuery('.tab-content').on('click', function()
      {
        jQuery('.chatbox-wrapper').slideToggle('slow');
      });
      });
      </script>
    <?php
    //echo sendMessageBtnHtml();
    echo chatBoxWrapperHtml( $this->fbId );
    $data = ob_get_contents();
    ob_end_clean();
    
    return $data;
  }
  
  private function chatBoxWrapperHtml( $lpfacebookid )
  {
      ob_start();
      ?>
        <style type="text/css">   
    .ztb-tab-container {
    box-sizing: border-box;
    bottom: 0;
    cursor: pointer;
    left: auto;
    position: fixed;
    right: 5%;
    z-index: 9999;
}
.style1 {
    background: rgba(0, 0, 0, 0) linear-gradient(to bottom, #4e69a2 0%, #304b84 50%) repeat scroll 0 0;
}

.ztb-fbc-tabbutton {
    background-color: #4e69a2;
    border: medium none;
    border-radius: 10px 10px 0 0;
    box-shadow: 0 0 5px 0 rgba(0, 0, 0, 0.2);
    color: #fff;
    cursor: pointer;
    float: left;
    font-family: arial,helvetica,sans-serif;
    font-size: 14px;
    font-weight: normal;
    height: 40px;
    line-height: 32px;
    padding: 4px;
    text-decoration: none;
    margin-bottom: -5px;
}

.tab-content {
    float: left;
    padding-right: 12px;
}
.ztb-fbc-tabbutton {
    color: #fff;
    cursor: pointer;
    font-family: arial,helvetica,sans-serif;
    font-size: 14px;
    font-weight: normal;
    line-height: 32px;
}

/* Desktop */
@media all and (max-width: 1024px) {
    width: 258px;         
}

/* Mobile View */
@media all and (max-width: 768px) {
      .ztb-fbc-tabbutton
    {
        width: 35% !important;
        text-indent: -99999;
    }
}

</style>  
       <style type="text/css">   
      .chatbox-wrapper {
    -moz-border-bottom-colors: none;
    -moz-border-left-colors: none;
    -moz-border-right-colors: none;
    -moz-border-top-colors: none;
    background-color: #fff;
    border-color: #4e69a2 #4e69a2 -moz-use-text-color;
    border-image: none;
    border-style: solid solid none;
    border-top-left-radius: 5px;
    border-top-right-radius: 5px;
    border-width: 4px 4px 0;
    height: 322px;
    left: auto;
    overflow: hidden;
    /*position: relative;*/
    right: 5%;
    width: 258px;
    z-index: 2147483647;
    bottom: 0;
}
</style>
<div class="ztb-tab-container">
    
          
<div class="ztb-fbc-tabbutton style1" id="ztb-fbc-show-widget">
            <div class="tab-content">
            <img style="border: none; vertical-align: middle;" src="http://lokalpages.com/wp-content/themes/directory/design/img/social-icons/facebook-ff.png" height="24" width="24" alt="Facebook" title="Facebook">
            Send Business a Message</div>
        </div>
<div class="chatbox-wrapper">

    <div id="loading-wrapper" style="display: none;">
        <div id="circularG">
            <div class="circularG" id="circularG_1"></div>
            <div class="circularG" id="circularG_2"></div>
            <div class="circularG" id="circularG_3"></div>
            <div class="circularG" id="circularG_4"></div>
            <div class="circularG" id="circularG_5"></div>
            <div class="circularG" id="circularG_6"></div>
            <div class="circularG" id="circularG_7"></div>
            <div class="circularG" id="circularG_8"></div>
        </div>
    </div>
    <div class="ztb-close-chatbox" style="display: block;">
        <span class=" white-icon  icon-075" style="color: rgb(255, 255, 255);"></span>
    </div>
    <div data-show-posts="false" data-show-facepile="true" data-hide-cover="false" data-locale="vi_VN" data-adapt-container-width="true" data-small-header="true" data-height="300" data-width="250" data-href="https://www.facebook.com/<?php echo $lpfacebookid?>/" data-tabs="messages" class="fb-page fb_iframe_widget" fb-xfbml-state="rendered" fb-iframe-plugin-query="adapt_container_width=true&amp;app_id=540185542800080&amp;container_width=251&amp;height=300&amp;hide_cover=false&amp;href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $lpfacebookid ?>%2F&amp;locale=en_US&amp;sdk=joey&amp;show_facepile=true&amp;show_posts=false&amp;small_header=true&amp;tabs=messages&amp;width=250"><span style="vertical-align: bottom; width: 250px; height: 300px;"><iframe width="250px" height="300px" frameborder="0" name="f3e5a0561d6d2b8" allowtransparency="true" allowfullscreen="true" scrolling="no" title="fb:page Facebook Social Plugin" style="border: medium none; visibility: visible; width: 250px; height: 300px;" src="https://www.facebook.com/plugins/page.php?adapt_container_width=true&amp;app_id=540185542800080&amp;channel=https%3A%2F%2Fstaticxx.facebook.com%2Fconnect%2Fxd_arbiter.php%3Fversion%3D42%23cb%3Df2d8da7d891912c%26domain%3Dzotabox.com%26origin%3Dhttps%253A%252F%252Fzotabox.com%252Ff146cc7cf773af%26relation%3Dparent.parent&amp;container_width=251&amp;height=300&amp;hide_cover=false&amp;href=https%3A%2F%2Fwww.facebook.com%2F<?php echo $lpfacebookid?>%2F&amp;locale=en_US&amp;sdk=joey&amp;show_facepile=true&amp;show_posts=false&amp;small_header=true&amp;tabs=messages&amp;width=250" class=""></iframe></span></div>
</div>

</div>
<?php 
$data = ob_get_contents();
ob_end_clean();

return $data;
  }
}

?>
