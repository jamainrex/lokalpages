{ifset $themeOptions->directory->showShareButtons} 
<ul class="social-icons left clearfix">
            <li style="border: none;" class="left"><iframe src="//www.facebook.com/plugins/like.php?href=<?php echo site_url();?>&amp;send=false&amp;layout=button_count&amp;width=113&amp;show_faces=true&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21" scrolling="no" frameborder="0" style="border:none; overflow:hidden; width:113px; height:21px;" allowTransparency="true"></iframe></li>
            <li style="border: none;" class="left">
                <a href="https://twitter.com/share" class="twitter-share-button" data-url="<?php echo site_url();?>" data-text="{$themeOptions->directory->shareText} <?php echo site_url();?>" data-lang="en">Tweet</a>
                <script>!function(d,s,id){ var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){ js=d.createElement(s);js.id=id;js.src="https://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
            </li>
            <li style="border: none;" class="left">
                <div class="g-plusone"></div>
                <!-- Place this tag after the last +1 button tag. -->
                <script type="text/javascript">
                  (function() {
                    var po = document.createElement('script'); po.type = 'text/javascript'; po.async = true;
                    po.src = 'https://apis.google.com/js/plusone.js';
                    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(po, s);
                  })();
                </script>
            </li>
 
</ul>
{/ifset}
{ifset $themeOptions->socialIcons->displayIcons}
    {ifset $themeOptions->socialIcons->icons}
    <ul class="social-icons right clearfix">
        {foreach $themeOptions->socialIcons->icons as $icon}
        <li class="left"><a href="{if !empty($icon->link)}{$icon->link}{else}#{/if}"><img src="{$icon->iconUrl}" height="24" width="24" alt="{$icon->title}" title="{$icon->title}"></a></li>
        {/foreach}
    </ul>
    {/ifset}
{/ifset}