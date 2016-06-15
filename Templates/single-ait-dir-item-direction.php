{include 'snippets/map-single-direction-javascript.php'}
<style type="text/css">
    .item-info span {
        display: block;
        padding: 3px;
    }
    .user-location, .listing-location {
        background: none repeat scroll 0 0 #f2f2f2;
        border: 1px dotted #cfcfcf;
        display: table;
        padding: 15px;
        margin: 10px 0;
        width: 45%;
    }
    .listing-location {
        float: right;
    }
    span.data { font-weight: bold;}
    .map-note {
        font-style: italic;
        margin-bottom: 5px;
    }
</style>
<h1>Direction</h1>
{if (!empty($options['address']))}
<div style="display: block;">
<span class="address">{__ 'Address:'}</span>
<span class="data">{!$options['address']}</span>
</div>
{/if}
<div style="display: block;" class="clearfix map-note" ><span class="data">Note: Geolocation is much more accurate for devices with GPS, like iPhone. You can drag the <img height="25" src="<?php echo site_url('wp-content/themes/directory/design/img/red-marker.png'); ?>" />(red pin) to adjust it to your exact position.</span></div>
<div class="item-info user-location">
<input type="hidden" id="user-geoLat" name="user-geoLat" value="">
<input type="hidden" id="user-geoLng" name="user-geoLng" value="">
    <span>Your Location</span>
    <span>
        <label for="user-direction-gpsLatitude">GPS Latitude: </label>
        <input type="text" id="user-direction-gpsLatitude" name="user-direction-gpsLatitude" value="">
    </span>
    <span>
        <label for="user-direction-gpsLongitude">GPS Latitude: </label>
        <input type="text" id="user-direction-gpsLongitude" name="user-direction-gpsLongitude" value="">
    </span>
</div> 
<div class="item-info listing-location">
<input type="hidden" id="item-direction-marker-icon" name="item-direction-marker-icon" value="{!$term->marker}">
    <span>Listing Location</span>
    <span>
        <label for="item-direction-gpsLatitude">GPS Latitude: </label>
        <input readonly="readonly" type="text" id="item-direction-gpsLatitude" name="item-direction-gpsLatitude" value="{$options['gpsLatitude']}">
    </span>
    <span>
        <label for="item-direction-gpsLongitude">GPS Latitude: </label>
        <input readonly="readonly" type="text" id="item-direction-gpsLongitude" name="item-direction-gpsLongitude" value="{$options['gpsLongitude']}">
    </span>
</div>
<div id="directionsPanel" class="clearfix" style="float:right;width:25%;height: 100%">
<p>Total Distance: <span id="total"></span></p>
</div> 
<div id="item-direction-map" class="clearfix" style="float: left; width:75%;"></div>
&nbsp

<div class="clearfix"></div>
&nbsp

<form action="http://maps.google.com/maps/" method="get" target="_blank">
  <input type="hidden" name="q" value="{$options['address']}"/>
   <input name="image" id="submit" type="image" align="center" src="http://lokalpages.com/wp-content/uploads/2015/10/navigate-icon.png" alt="Navigate to map" Open Map/>

</form>

    {ifset $themeOptions->directory->showShareButtons}
    <div class="item-share" style="margin-top: 20px;">
        <!-- facebook -->
        <div class="social-item fb">
            <iframe src="//www.facebook.com/plugins/like.php?href={$post->permalink}&amp;send=false&amp;layout=button_count&amp;width=113&amp;show_faces=true&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:113px; height:21px;" allowTransparency="true"></iframe>
        </div>
        <!-- twitter -->
        <div class="social-item">
            <a href="https://twitter.com/share" class="twitter-share-button" data-url="{$post->permalink}" data-text="{$themeOptions->directory->shareText} {$post->permalink}" data-lang="en">Tweet</a>
            <script>!function(d,s,id){ var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){ js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
        </div>
        <!-- google plus -->
        <!-- Place this tag where you want the +1 button to render. -->
        <div class="social-item">
            <div class="g-plusone"></div>
            <!-- Place this tag after the last +1 button tag. -->
            <script type="text/javascript">
              (function() {
                var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
                po.src = 'https://apis.google.com/js/plusone.js';
                var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
              })();
            </script>
        </div>

    </div>
    {/ifset}